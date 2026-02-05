CREATE TABLE kategori (
    id_kategori INT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE status (
    id_status INT PRIMARY KEY,
    nama_status VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE produk (
    id_produk INT PRIMARY KEY,
    nama_produk VARCHAR(150) NOT NULL,
    harga INT NOT NULL,
    kategori_id INT NOT NULL,
    status_id INT NOT NULL,

    CONSTRAINT fk_produk_kategori
        FOREIGN KEY (kategori_id)
        REFERENCES kategori(id_kategori)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_produk_status
        FOREIGN KEY (status_id)
        REFERENCES status(id_status)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;