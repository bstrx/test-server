CREATE TABLE user
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    person_id VARCHAR(50) NOT NULL,
    auth_key VARCHAR(50) NOT NULL,
    network_key VARCHAR(15) NOT NULL,
    level INT DEFAULT 0 NOT NULL,
    money INT DEFAULT 0 NOT NULL,
    last_visit DATETIME DEFAULT CURRENT_TIMESTAMP,
    days_in_row INT DEFAULT 0 NOT NULL
);


CREATE TABLE user_property
(
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    value LONGTEXT NOT NULL,
    PRIMARY KEY (user_id, property)
);

INSERT INTO user (person_id, auth_key, network_key, money, level) VALUES (
  '03d59e663c1af9ac33a9949d1193505a',
  '3097e26b7f3cbdb920765a6c3d2ba94985e465c',
  'vk',
  1500
  80
);

INSERT INTO user_property (user_id, name, value) VALUES (
  1,
  'someProperty',
  'and some value'
);

INSERT INTO user_property (user_id, name, value) VALUES (
  1,
  'anotherProperty',
  420984231289
);