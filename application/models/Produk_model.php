<?php
class Produk_model extends CI_Model {

    public function getBisaDijual()
    {
        $this->db->select('*');
        $this->db->from('produk');
        $this->db->join('kategori','kategori.id_kategori = produk.kategori_id');
        $this->db->join('status','status.id_status = produk.status_id');
        $this->db->where('status.nama_status','bisa dijual');
        return $this->db->get()->result();
    }

    public function getById($id)
    {
        return $this->db->get_where('produk',['id_produk'=>$id])->row();
    }

    public function insert()
    {
        $this->db->insert('produk', [
            'nama_produk' => $this->input->post('nama_produk'),
            'harga' => $this->input->post('harga'),
            'kategori_id' => $this->input->post('kategori_id'),
            'status_id' => $this->input->post('status_id')
        ]);
    }

    public function update($id)
    {
        $this->db->update('produk', [
            'nama_produk' => $this->input->post('nama_produk'),
            'harga' => $this->input->post('harga'),
            'kategori_id' => $this->input->post('kategori_id'),
            'status_id' => $this->input->post('status_id')
        ], ['id_produk'=>$id]);
    }
}
