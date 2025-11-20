CREATE TABLE users (
    id INT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255),
    city VARCHAR(100),
    district VARCHAR(100),
    address VARCHAR(255),
    phone VARCHAR(20),
    role ENUM('admin', 'buyer', 'store') NOT NULL DEFAULT 'buyer'
);

CREATE TABLE categories (
    Cid INT PRIMARY KEY,
    Cname VARCHAR(100) NOT NULL
);

CREATE TABLE types (
    Tid INT PRIMARY KEY,
    Tname VARCHAR(50) NOT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(Cid)
);

CREATE TABLE series (
    seid INT PRIMARY KEY,
    Sname VARCHAR(100) NOT NULL
);

CREATE TABLE manufacturers (
    Mid INT PRIMARY KEY,
    Mname VARCHAR(100) NOT NULL
);

CREATE TABLE brands (
    Bid INT PRIMARY KEY,
    Bname VARCHAR(100) NOT NULL
);

CREATE TABLE stores (
    Sid INT PRIMARY KEY,
    Sname VARCHAR(100) NOT NULL,
    Slocation VARCHAR(255) NOT NULL,
    Smap_url VARCHAR(255) NOT NULL
);

CREATE TABLE products (
    Pid INT PRIMARY KEY,
    Pname VARCHAR(100) NOT NULL,
    Pdescription TEXT NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    type_id INT,
    category_id INT,
    series_id INT,
    manufacturer_id INT,
    brand_id INT,
    Pimage VARCHAR(255) NOT NULL,
    is_hidden BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (type_id) REFERENCES types(Tid),
    FOREIGN KEY (category_id) REFERENCES categories(Cid),
    FOREIGN KEY (series_id) REFERENCES series(seid),
    FOREIGN KEY (manufacturer_id) REFERENCES manufacturers(Mid),
    FOREIGN KEY (brand_id) REFERENCES brands(Bid)
);

CREATE TABLE IN_STOCK (
    product_id INT,
    store_id INT,
    quantity INT NOT NULL DEFAULT 0,
    PRIMARY KEY (product_id, store_id),
    FOREIGN KEY (product_id) REFERENCES products(Pid),
    FOREIGN KEY (store_id) REFERENCES stores(Sid)
);

CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(15, 2) NOT NULL,
    order_date DATETIME,
    city VARCHAR(100),
    district VARCHAR(100),
    address VARCHAR(255),
    phone VARCHAR(20),
    status ENUM('in_cart', 'Placed', 'Shipped', 'Delivered', 'Cancelled', 'Cancel Awaiting') NOT NULL DEFAULT 'in_cart',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(Pid)
);

INSERT INTO categories (Cid, Cname) VALUES
(1, 'Accessories'),
(2, 'PC Components'),
(3, 'Laptops');

INSERT INTO series (seid, Sname) VALUES
(1, 'G Pro X'),
(2, '5090 Series'),
(3, '5080 Series'),
(4, '5070 Series'),
(5, '5060 Series'),
(6, 'DeathAdder'),
(7, 'Viper');

INSERT INTO types (Tid, Tname, category_id) VALUES
(1, 'Mouse', 1),
(2, 'Graphic Card', 2),
(3, 'Keyboard', 1),
(4, 'Monitor', 2);

INSERT INTO manufacturers (Mid, Mname) VALUES
(1, 'Logitech'),
(2, 'NVIDIA'),
(3, 'Razer');

INSERT INTO brands (Bid, Bname) VALUES
(1, 'Logitech'),
(2, 'Asus'),
(3, 'MSI'),
(4, 'ZOTAC'),
(5, 'Colorful'),
(6, 'GIGABYTE'),
(7, 'Razer');

INSERT INTO products (Pid, Pname, Pdescription, price, stock, type_id, category_id, series_id, manufacturer_id, brand_id, Pimage) VALUES
(1, 'Gaming Mouse Logitech G Pro X Superlight 1', 'High-end gaming mouse', 2150000, 100, 1, 1, 1, 1, 1, 'mouse/GProX1.png'),
(2, 'NVIDIA GeForce RTX 5090 Ti ASUS ROG Astral', 'High-end graphics card for gamers', 66000000, 30, 2, 2, 2, 2, 2, 'graphic/RTX5090TiROGAstral.png'),
(3, 'Gaming Mouse Logitech G Pro X Superlight 2', 'High-end gaming mouse', 2650000, 100, 1, 1, 1, 1, 1, 'mouse/GProX1.png'),
(4, 'NVIDIA GeForce RTX 5090 MSI Gaming Trio', 'High-end graphics card for gamers', 55000000, 30, 2, 2, 2, 2, 3, 'graphic/RTX5090MSIGTrio.png'),
(5, 'Gaming Mouse Logitech G Pro X Superlight 2 Dex', 'High-end gaming mouse', 3150000, 100, 1, 1, 1, 1, 1, 'mouse/GProX2Dex.png'),
(6, 'NVIDIA GeForce RTX 5080 Ti ASUS ROG Astral', 'High-end graphics card for gamers', 45000000, 30, 2, 2, 3, 2, 2, 'graphic/RTX5080TiROGAstral.png'),
(7, 'Gaming Mouse Razer DeathAdder V3', 'High-end gaming mouse', 3650000, 100, 1, 1, 6, 3, 7, 'mouse/DeathAdderV3.png'),
(8, 'NVIDIA GeForce RTX 5080 ZOTAC Gaming', 'High-end graphics card for gamers', 35000000, 30, 2, 2, 3, 2, 4, 'graphic/RTX5080Zotac.png'),
(9, 'Gaming Mouse Razer DeathAdder V3 Pro', 'High-end gaming mouse', 4150000, 100, 1, 1, 6, 3, 7, 'mouse/DeathAdderV3.png'),
(10, 'NVIDIA GeForce RTX 5070 Ti AORUS', 'High-end graphics card for gamers', 25000000, 30, 2, 2, 4, 2, 6, 'graphic/RTX5070TiAORUS.png'),
(11, 'Gaming Mouse Razer Viper V3', 'High-end gaming mouse', 3150000, 100, 1, 1, 7, 3, 7, 'mouse/ViperV3.png'),
(12, 'NVIDIA GeForce RTX 5070 Ultra White', 'High-end graphics card for gamers', 20000000, 30, 2, 2, 4, 2, 5, 'graphic/RTX5070UW.png'),
(13, 'Gaming Mouse Razer Viper V3 Pro', 'High-end gaming mouse', 4650000, 100, 1, 1, 7, 3, 7, 'mouse/ViperV3.png'),
(14, 'NVIDIA GeForce RTX 5060 Ti ZOTAC Gaming', 'High-end graphics card for gamers', 15000000, 30, 2, 2, 5, 2, 4, 'graphic/RTX5060TiZOTAC.png'),
(15, 'Gaming Mouse Razer DeathAdder V4 Pro', 'High-end gaming mouse', 4150000, 100, 1, 1, 6, 3, 7, 'mouse/DeathAdderV4Pro.png'),
(16, 'NVIDIA GeForce RTX 5060 GIGABYTE', 'High-end graphics card for gamers', 12000000, 30, 2, 2, 5, 2, 6, 'graphic/RTX5060GIGABYTE.png');

INSERT INTO stores (Sid, Sname, Slocation, Smap_url) VALUES
(1, 'Showroom Tân Bình', '28 - 30 Trần Triệu Luật, Phường 6, Tân Bình, Thành phố Hồ Chí Minh 700000, Việt Nam', 'https://maps.app.goo.gl/DoAwEyayGR9EumFd8'),
(2, 'Showroom Bình Thạnh', '474 Điện Biên Phủ, Phường 17, Bình Thạnh, Hồ Chí Minh 700000, Việt Nam', 'https://maps.app.goo.gl/ZbhYZ8351tpyDV5PA');

INSERT INTO IN_STOCK (product_id, store_id, quantity) VALUES
(1, 1, 50),
(1, 2, 50),
(2, 1, 15),
(2, 2, 15),
(3, 1, 50),
(3, 2, 50),
(4, 1, 15),
(4, 2, 15),
(5, 1, 50),
(5, 2, 50),
(6, 1, 0),
(6, 2, 0),
(7, 1, 50),
(7, 2, 50),
(8, 1, 15),
(8, 2, 15),
(9, 2, 50),
(9, 1, 0),
(10, 1, 15),
(10, 2, 15),
(11, 1, 50),
(11, 2, 50),
(12, 1, 15),
(12, 2, 15),
(13, 1, 50),
(13, 2, 50),
(14, 1, 15),
(14, 2, 15),
(15, 1, 50),
(15, 2, 50),
(16, 1, 15),
(16, 2, 0);


ALTER TABLE users
    MODIFY id INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE categories
    MODIFY Cid INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE types
    MODIFY Tid INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE series
    MODIFY seid INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE manufacturers
    MODIFY Mid INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE brands
    MODIFY Bid INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE stores
    MODIFY Sid INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE products
    MODIFY Pid INT(11) NOT NULL AUTO_INCREMENT;
