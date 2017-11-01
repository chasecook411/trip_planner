USE lost_db;

CREATE TABLE IF NOT EXISTS users (
    user_id		INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	f_name		VARCHAR(20) NOT NULL,
	l_name		VARCHAR(20) NOT NULL,
	email 		VARCHAR(30) NOT NULL UNIQUE,
	password 	VARCHAR(20) NOT NULL,
	PRIMARY KEY (user_id)
);

CREATE TABLE IF NOT EXISTS trips (
    trip_id     INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id     INTEGER UNSIGNED NOT NULL,
    trip_name   VARCHAR(30),
    day         DATE,
    PRIMARY KEY (trip_id)
    FOREIGN KEY (user_id)
);

CREATE TABLE IF NOT EXISTS attractions (
	attraction_id    TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	trip_id          INTEGER UNSIGNED NOT NULL,
	priority         TINYINT, #check back about not null; ordering might start without priority set but priority should be set at some point; what about -1 for skip
	address          VARCHAR(100),
	long             DECIMAL,
	lat              DECIMAL,
	time_spent       SMALLINT UNSIGNED,
	rating           TINYINT UNSIGNED,
	PRIMARY KEY (attraction_id)
    FOREIGN KEY (trip_id)
);

