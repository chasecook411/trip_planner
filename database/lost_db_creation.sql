USE lost_db;

CREATE TABLE IF NOT EXISTS users (
    user_id         INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    f_name          VARCHAR(20) NOT NULL,
    l_name          VARCHAR(20) NOT NULL,
    email           VARCHAR(30) NOT NULL UNIQUE,
    password        VARCHAR(20) NOT NULL,
    PRIMARY KEY (user_id)
);

CREATE TABLE IF NOT EXISTS trips (
    trip_id     INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id     INTEGER UNSIGNED NOT NULL,
    trip_name   VARCHAR(30),
    day         DATE,
    PRIMARY KEY (trip_id)
);

CREATE TABLE IF NOT EXISTS attractions (
    attraction_id    TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    trip_id          INTEGER UNSIGNED NOT NULL,
    priority         TINYINT, #ordering might start without priority set but priority should be set at some point; what about -1 for skip
    name             VARCHAR(100),
    address          VARCHAR(100),
    longitude        DECIMAL(10,7),
    latitude         DECIMAL(10,7),
    time_spent       SMALLINT UNSIGNED,
    rating           DECIMAL(2,1),
    place_id         VARCHAR(50),
    PRIMARY KEY (attraction_id)
);

