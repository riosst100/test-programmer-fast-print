<a href="<?= site_url('/') ?>">Kembali ke List Produk</a>
<h2>
    <?= isset($produk) ? 'Edit Produk' : 'Tambah Produk' ?>
</h2>
<form method="post">
    Nama:<br>
    <input type="text" name="nama_produk"
        value="<?= set_value('nama_produk', isset($produk)?$produk->nama_produk:'') ?>"><br>
    <small style="color:red"><?= form_error('nama_produk') ?></small><br>

    Harga:<br>
    <input type="number" name="harga"
        value="<?= set_value('harga', isset($produk)?$produk->harga:'') ?>"><br>
    <small style="color:red"><?= form_error('harga') ?></small><br>

    Kategori:<br>
    <select name="kategori_id">
        <?php foreach($kategori as $k): ?>
        <option value="<?= $k->id_kategori ?>"
            <?= set_select('kategori_id', $k->id_kategori, isset($produk) && $produk->kategori_id==$k->id_kategori) ?>>
            <?= $k->nama_kategori ?>
        </option>
        <?php endforeach ?>
    </select><br><br>

    Status:<br>
    <select name="status_id">
        <?php foreach($status as $s): ?>
        <option value="<?= $s->id_status ?>"
            <?= set_select('status_id', $s->id_status, isset($produk) && $produk->status_id==$s->id_status) ?>>
            <?= $s->nama_status ?>
        </option>
        <?php endforeach ?>
    </select><br><br>

    <button type="submit"><?= isset($produk) ? 'Update' : 'Simpan' ?></button>
</form>
