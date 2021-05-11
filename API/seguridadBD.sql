DROP DATABASE IF EXISTS seguridadBD;

CREATE DATABASE seguridadBD;

USE seguridadBD;

CREATE TABLE Usuario
(
  idUsuario INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(35) NOT NULL,
  password VARCHAR(250) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  nombre VARCHAR(50) NOT NULL,
  apellidoP VARCHAR(30) NOT NULL,
  apellidoM VARCHAR(30) NOT NULL,
  direccion VARCHAR(120) NOT NULL,
  estado INT NOT NULL DEFAULT 1,
  PRIMARY KEY (idUsuario)
);

CREATE TABLE video
(
  idVideo INT NOT NULL AUTO_INCREMENT,
  ruta VARCHAR(255) NOT NULL,
  fecha DATE NOT NULL DEFAULT CURDATE(),
  hora TIME NOT NULL DEFAULT CURTIME(),
  PRIMARY KEY (idVideo)
);

CREATE TABLE ubicacion
(
  idUbicacion INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(30) NOT NULL,
  PRIMARY KEY (idUbicacion)
);

CREATE TABLE dispositivo
(
  idDispositivo INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(30) NOT NULL,
  ip VARCHAR(15) NOT NULL,
  estado INT NOT NULL DEFAULT 1,
  posicion INT NOT NULL DEFAULT 90,
  idUsuario INT NOT NULL,
  idUbicacion INT NOT NULL,
  PRIMARY KEY (idDispositivo),
  FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario),
  FOREIGN KEY (idUbicacion) REFERENCES ubicacion(idUbicacion)
);

CREATE TABLE alerta
(
  idAlerta INT NOT NULL AUTO_INCREMENT,
  fecha_hora DATE NOT NULL DEFAULT CURDATE(),
  hora TIME NOT NULL DEFAULT CURTIME(),
  tipo INT NOT NULL,
  idDispositivo INT NOT NULL,
  PRIMARY KEY (idAlerta),
  FOREIGN KEY (idDispositivo) REFERENCES dispositivo(idDispositivo)
);

CREATE TABLE historia
(
  idVideo INT NOT NULL,
  idAlerta INT NOT NULL,
  PRIMARY KEY (idVideo, idAlerta),
  FOREIGN KEY (idVideo) REFERENCES video(idVideo),
  FOREIGN KEY (idAlerta) REFERENCES alerta(idAlerta)
);

INSERT INTO ubicacion (nombre) VALUES ("DEFAULT");
INSERT INTO usuario (email, password, telefono, nombre, apellidoP, apellidoM, direccion, estado) VALUES ('juan_perez_lopez@gmail.com', '123456', '31-35-29-26', 'Juan', 'Perez', 'Lopez', 'Av. La Paz #58', '1');
INSERT INTO dispositivo (nombre, ip, estado, posicion, idUsuario, idUbicacion) VALUES ('CAM1', '192.168.0.18', '1', '90', '1', '1');