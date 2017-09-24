CREATE TABLE files (
	id int AUTO_INCREMENT PRIMARY KEY,
	name varchar(50) UNIQUE,
	text text,
	password varchar(255)
);