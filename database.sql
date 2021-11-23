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

--RESTRICCIONES DE INTEGRIDAD REFERENCIAL = SET_NULL
CREATE TABLE proyectos(
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT(11),
    proyecto VARCHAR(60),
    url VARCHAR(15),
    FOREIGN KEY (idUsuario) REFERENCES usuarios (id)
);

--RESTRICCIONES DE INTEGRIDAD REFERENCIAL = SET_NULL
CREATE TABLE tareas(
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
    idProyecto INT(11),
    nombre VARCHAR(60),
    estado TINYINT(1),
    FOREIGN KEY (idProyecto) REFERENCES proyectos (id)
);