<h2>List Produk</h2>
<a href="<?= site_url('produk/api-import') ?>">Impor Data dari API</a> |
<a href="<?= site_url('produk/tambah') ?>">Tambah Produk</a>
<br />
<br />
<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success'); ?>
    </div>
    <br />
<?php endif; ?>
<table border="1" cellpadding="5">
    <tr>
        <th>Nama</th>
        <th>Harga</th>
        <th>Kategori</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php if (!$produk) : ?>
        <tr>
            <td colspan="5">Tidak ada produk.</td>
        </tr>
    <?php else: ?>
        <?php foreach($produk as $p): ?>
            <tr>
                <td><?= $p->nama_produk ?></td>
                <td><?= $p->harga ?></td>
                <td><?= $p->nama_kategori ?></td>
                <td><?= $p->nama_status ?></td>
                <td>
                    <a href="<?= site_url('produk/edit/'.$p->id_produk) ?>">Edit</a> |
                    <a href="<?= site_url('produk/hapus/'.$p->id_produk) ?>" 
                    onclick="return confirm('Anda yakin ingin menghapus produk &quot;<?= $p->nama_produk ?>&quot;?')">
                        Hapus
                    </a>
                </td>
            </tr>
        <?php endforeach ?>
    <?php endif; ?>
</table>
