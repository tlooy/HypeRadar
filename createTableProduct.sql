

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


CREATE TABLE event_types (
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

CREATE TABLE events (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    event_type_id INT,
    genre_id INT,
    franchise_id INT,
    event_from_datetime DATE NOT NULL,
    event_to_datetime DATE,
    event_status VARCHAR(20), 
    price DECIMAL (10, 2),
    
    FOREIGN KEY (genre_id)
      REFERENCES genres(id)
      ON DELETE RESTRICT,
    
    FOREIGN KEY (franchise_id)
      REFERENCES franchises(id)
      ON DELETE RESTRICT,

    FOREIGN KEY (event_type_id)
      REFERENCES event_types(id)
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
    userpwd VARCHAR(100) NOT NULL,
    userrole VARCHAR(20) DEFAULT 'User'
);

CREATE TABLE subscriptions (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,

    FOREIGN KEY (user_id)
      REFERENCES users(id)
      ON DELETE RESTRICT,
    
    FOREIGN KEY (product_id)
      REFERENCES products(id)
      ON DELETE RESTRICT
);

ALTER TABLE users ADD COLUMN userrole VARCHAR(20) DEFAULT 'User';
