<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller 
{
    const API_URL = "https://recruitment.fastprint.co.id/tes/api_tes_programmer";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produk_model');
        $this->load->library(['form_validation', 'session']); // pastikan session di-load
    }

    // ================= List Produk =================
    public function index()
    {
        $data['produk'] = $this->Produk_model->getBisaDijual();
        $this->load->view('produk/index', $data);
    }

    // ================= Import Produk dari API =================
    public function api_import()
    {
        $data = $this->get_produk_dari_api();

        foreach($data as $p){
            $this->insert_produk($p);
        }

        $this->session->set_flashdata('success', 'Data produk berhasil diimport dari API!');
        redirect('/');
    }

    // ================= Username API =================
    public function get_username() 
    {
        $url = self::API_URL;
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => true
        ]);

        $response = curl_exec($ch);
        if ($response === false) die('CURL Error: ' . curl_error($ch));
        curl_close($ch);

        preg_match('/x-credentials-username:\s*(.+)/i', $response, $userMatch);
        $raw = trim($userMatch[1]);
        $username = trim(explode(' ', $raw)[0]);

        return $username;
    }

    // ================= Generate Password =================
    public function generate_password() 
    {
        $tanggal = date('d');
        $bulan   = date('m');
        $tahun   = date('y');

        return md5("bisacoding-{$tanggal}-{$bulan}-{$tahun}");
    }

    // ================= Ambil Data Produk dari API =================
    public function get_produk_dari_api()
    {
        $url = self::API_URL;
        $username = $this->get_username();
        $password = $this->generate_password();
        $cookieFile = FCPATH . 'cookie.txt';
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'username' => $username,
                'password' => $password
            ]),
            CURLOPT_COOKIEJAR  => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_HEADER      => false,
        ]);

        $response = curl_exec($ch);
        if ($response === false) die('CURL Error: ' . curl_error($ch));

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200) die("HTTP Error: $httpCode. Response: $response");

        $result = json_decode($response, true);
        if (!$result) die('Response API tidak valid JSON: ' . $response);
        if (isset($result['error']) && $result['error'] == 1) die($result['ket']);
        
        return $result['data'] ?? [];
    }

    // ================= Insert/Get Kategori =================
    private function insert_kategori($nama_kategori)
    {
        $row = $this->db->where('nama_kategori', $nama_kategori)->get('kategori')->row();
        if ($row) return $row->id_kategori;

        $max_id = $this->db->select_max('id_kategori', 'max_id')->get('kategori')->row()->max_id;
        $id = $max_id ? $max_id + 1 : 1;

        $this->db->insert('kategori', [
            'id_kategori' => $id,
            'nama_kategori' => $nama_kategori
        ]);
        return $id;
    }

    // ================= Insert/Get Status =================
    private function insert_status($nama_status)
    {
        $row = $this->db->where('nama_status', $nama_status)->get('status')->row();
        if ($row) return $row->id_status;

        $max_id = $this->db->select_max('id_status', 'max_id')->get('status')->row()->max_id;
        $id = $max_id ? $max_id + 1 : 1;

        $this->db->insert('status', [
            'id_status' => $id,
            'nama_status' => $nama_status
        ]);
        return $id;
    }

    // ================= Insert Produk =================
    private function insert_produk($produk)
    {
        $kategori_id = $this->insert_kategori($produk['kategori']);
        $status_id   = $this->insert_status($produk['status']);

        $this->db->replace('produk', [
            'id_produk' => $produk['id_produk'],
            'nama_produk' => $produk['nama_produk'],
            'harga' => $produk['harga'],
            'kategori_id' => $kategori_id,
            'status_id' => $status_id
        ]);
    }

    // ================= Tambah Produk =================
    public function tambah()
    {
        $this->form_validation->set_rules('nama_produk','Nama','required');
        $this->form_validation->set_rules('harga','Harga','required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $data['kategori'] = $this->db->get('kategori')->result();
            $data['status'] = $this->db->get('status')->result();
            $this->load->view('produk/form', $data);
        } else {
            $this->Produk_model->insert();
            $this->session->set_flashdata('success', 'Produk berhasil ditambahkan!');
            redirect('/');
        }
    }

    // ================= Edit Produk =================
    public function edit($id)
    {
        $this->form_validation->set_rules('nama_produk','Nama','required');
        $this->form_validation->set_rules('harga','Harga','required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $data['produk'] = $this->Produk_model->getById($id);
            $data['kategori'] = $this->db->get('kategori')->result();
            $data['status'] = $this->db->get('status')->result();
            $this->load->view('produk/form', $data);
        } else {
            $this->Produk_model->update($id);
            $this->session->set_flashdata('success', 'Produk berhasil diupdate!');
            redirect('/');
        }
    }

    // ================= Hapus Produk =================
    public function hapus($id)
    {
        $this->db->delete('produk', ['id_produk' => $id]);
        $this->session->set_flashdata('success', 'Produk berhasil dihapus!');
        redirect('/');
    }
}
