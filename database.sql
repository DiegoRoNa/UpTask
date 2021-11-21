USE uptask;

CREATE TABLE usuarios(
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(30),
    apellidos VARCHAR(80),
    email VARCHAR(50),
    password VARCHAR(60),
    token VARCHAR(15),
    confirmado TINYINT(1)
);