

CREATE TABLE sources (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE genres (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE franchises (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE products (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    genre_id INT,
    franchise_id INT,
    release_dt DATE,
    release_dt_status VARCHAR(20), 
    price DECIMAL (10, 2),
    status VARCHAR(20),
    
    FOREIGN KEY (genre_id)
      REFERENCES genres(id)
      ON DELETE RESTRICT,
    
    FOREIGN KEY (franchise_id)
      REFERENCES franchises(id)
      ON DELETE RESTRICT

);

CREATE TABLE comments (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    comment VARCHAR(255) NOT NULL,
    source_id INT,
    hyperlink VARCHAR(255),
    create_dt DATE,

    FOREIGN KEY (product_id)
      REFERENCES products(id)
      ON DELETE RESTRICT,

    FOREIGN KEY (source_id)
      REFERENCES sources(id)
      ON DELETE RESTRICT

);

CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    userid VARCHAR(100) NOT NULL,
    useremail VARCHAR(100) NOT NULL,
    userpwd VARCHAR(100) NOT NULL
);

