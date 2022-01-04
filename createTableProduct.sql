

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


CREATE TABLE notification_statuses (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL
);


CREATE TABLE events (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    event_type_id INT,
    genre_id INT,
    franchise_id INT,
    event_from_datetime DATE NOT NULL,
    event_to_datetime DATE NOT NULL,
    event_status VARCHAR(20), 
    price DECIMAL (10, 2),
    creator_user_id INT,
    
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
    event_id INT NOT NULL,

    FOREIGN KEY (user_id)
      REFERENCES users(id)
      ON DELETE RESTRICT,
    
    FOREIGN KEY (event_id)
      REFERENCES events(id)
      ON DELETE RESTRICT
);


CREATE TABLE notifications (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    topic VARCHAR(200),
    source_id INT,
    status_id INT,
    creator_user_id INT,
    created_datetime DATE,
    sent_datetime DATE,

    FOREIGN KEY (event_id)
      REFERENCES events(id)
      ON DELETE RESTRICT,

    FOREIGN KEY (source_id)
      REFERENCES sources(id)
      ON DELETE RESTRICT,

    FOREIGN KEY (status_id)
      REFERENCES notification_statuses(id)
      ON DELETE RESTRICT,

    FOREIGN KEY (creator_user_id)
      REFERENCES users(id)
      ON DELETE RESTRICT
);

CREATE TABLE notification_devices (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    notification_token VARCHAR(100) NOT NULL,
    device_type VARCHAR(100),

    FOREIGN KEY (user_id)
      REFERENCES users(id)
      ON DELETE RESTRICT
);


SQL to be run in production to have database brought up to date:
[Done] ALTER TABLE notifications DROP COLUMN status;
[Done] ALTER TABLE notifications ADD COLUMN status_id INT;
[Done] ALTER TABLE notifications ADD FOREIGN KEY (status_id)
      REFERENCES notification_statuses(id)
      ON DELETE RESTRICT;
      
[Dec 26, 2021]      
[Done] ALTER TABLE notifications ADD COLUMN creator_user_id INT;
[Done]  ALTER TABLE notifications ADD FOREIGN KEY (creator_user_id)
      REFERENCES users(id)
      ON DELETE RESTRICT;
[Done] ALTER TABLE notifications ADD COLUMN status_id INT;
[Done] ALTER TABLE notifications DROP status;


[Dec 27, 2021]      
ALTER TABLE events ADD COLUMN creator_user_id INT;

[Dec 31, 2021]
CREATE TABLE notification_devices (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    notification_token VARCHAR(100) NOT NULL,
    device_type VARCHAR(100),

    FOREIGN KEY (user_id)
      REFERENCES users(id)
      ON DELETE RESTRICT
);

[Jan 2, 2022]
CREATE TABLE subscription_notifications (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    subscription_id INT NOT NULL,
    notification_id INT NOT NULL,
    status VARCHAR(100),
    created_datetime DATE
);

