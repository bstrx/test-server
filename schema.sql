CREATE TABLE myserver.user
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    person_id VARCHAR(30) NOT NULL,
    auth_key VARCHAR(30) NOT NULL,
    network_key VARCHAR(15) NOT NULL,
    level INT DEFAULT 0 NOT NULL,
    money INT DEFAULT 0 NOT NULL,
    last_visit DATETIME NOT NULL,
    days_in_row INT DEFAULT 0 NOT NULL
);

CREATE TABLE myserver.user_property
(
    user_id INT NOT NULL,
    property VARCHAR(100) NOT NULL,
    value LONGTEXT NOT NULL,
    PRIMARY KEY (user_id, property)
);