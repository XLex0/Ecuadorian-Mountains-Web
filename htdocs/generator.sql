CREATE DATABASE MontañasDB;
USE MontañasDB;

CREATE TABLE Usuario (
    UsuarioID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    verificado BOOLEAN DEFAULT FALSE
    
);

CREATE TABLE Montaña (
    MontañaID INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Longitud FLOAT NOT NULL,
    Latitud FLOAT NOT NULL,
    Ubicacion VARCHAR(100) NOT NULL,
    Altura FLOAT NOT NULL,
    Tipo VARCHAR(100) NOT NULL,
    Description TEXT,
    urlImagenPrincipal VARCHAR(300),
    mapsEmbeded TEXT
);

CREATE TABLE Comentarios (
    ComentarioID INT AUTO_INCREMENT PRIMARY KEY,
    Fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Likes INT DEFAULT 0,
    Contenido TEXT NOT NULL,
    MontañaID INT NOT NULL,
    UsuarioID INT NOT NULL,
    FOREIGN KEY (UsuarioID) REFERENCES Usuario(UsuarioID),
    FOREIGN KEY (MontañaID) REFERENCES Montaña(MontañaID)
);

CREATE TABLE Puntaje (
    PuntajeID INT AUTO_INCREMENT PRIMARY KEY,
    cantidadVotos INT DEFAULT 0,
    sumTotal FLOAT DEFAULT 0,
    MontañaID INT NOT NULL,
    FOREIGN KEY (MontañaID) REFERENCES Montaña(MontañaID)
);

CREATE TABLE Tags (
    TagID INT AUTO_INCREMENT PRIMARY KEY,
    TagTexto VARCHAR(50) NOT NULL,
    MontañaID INT NOT NULL,
    FOREIGN KEY (MontañaID) REFERENCES Montaña(MontañaID)
);

CREATE TABLE Galeria (
    imageID INT AUTO_INCREMENT PRIMARY KEY,
    urlImage VARCHAR(255) NOT NULL,
    MontañaID INT NOT NULL,
    FOREIGN KEY (MontañaID) REFERENCES Montaña(MontañaID)
);

CREATE TABLE InformacionExtra (
    ExtraInfoID INT AUTO_INCREMENT PRIMARY KEY,
    Titulo VARCHAR(255) NOT NULL,
    Tipo VARCHAR(100),
    Contenido TEXT,
    MontañaID INT NOT NULL,
    FOREIGN KEY (MontañaID) REFERENCES Montaña(MontañaID)
);


-- Inserción de Montañas
INSERT INTO Montaña (Nombre, Longitud, Latitud, Ubicacion, Altura, Tipo, Description, urlImagenPrincipal, mapsEmbeded)
VALUES
('Cotopaxi', -78.436389, -0.680556, 'Cotopaxi-Pichincha, Ecuador', 5897, 'Volcán', 
'El Cotopaxi es un volcán activo en la cordillera de los Andes, uno de los más altos del mundo. <br>Es un estratovolcán activo, situado en el centro-norte de Ecuador, en la Provincia de Cotopaxi. Perteneciente a la cordillera de los Andes, específicamente a los Andes septentrionales, cuenta con una altitud de 5897 m s. n. m.; por lo que es la segunda montaña más alta del Ecuador y de los Andes septentrionales, sólo superado por el volcán Chimborazo. <br>Su última gran erupción se remonta al 26 de junio de 1877; no obstante, desde 2015 se encuentra en un nuevo proceso eruptivo', 
'https://upload.wikimedia.org/wikipedia/commons/thumb/7/74/V%C3%B3lcan_Cotopaxi.jpg/640px-V%C3%B3lcan_Cotopaxi.jpg', 
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d459005.63173374406!2d-78.95757880742113!3d-0.7072264358155101!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d44e5dbbd52ce7%3A0x61297ba77301c1e8!2sVolc%C3%A1n%20Cotopaxi!5e0!3m2!1ses-419!2sec!4v1736886378881!5m2!1ses-419!2sec" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>');

INSERT INTO Montaña (Nombre, Longitud, Latitud, Ubicacion, Altura, Tipo, Description, urlImagenPrincipal, mapsEmbeded)
VALUES
('Guagua Pichincha', -78.598333, -0.171667, 'Pichincha, Ecuador', 4784, 'Volcán', 
'El Macizo de los Pichinchas son un conjunto de cerros y volcanes de Ecuador, situado en la capital del país, Quito. Pertenecen a la Cordillera Occidental de los Andes ecuatorianos.<br>Sus principales volcanes son el Guagua Pichincha y el Rucu Pichincha. <br>El Rucu Pichincha cuenta con una altura de 4696 metros de altura sobre nivel del mar. Es un volcán inactivo que posee dos cráteres, uno dentro del otro, como resultado de sus erupciones. Es el volcán más cercano a la ciudad de Quito.<br>El Guagua Pichincha es el volcán más activo de los Andes Occidentales Ecuatorianos y del Ecuador, El volcán Guagua Pichincha tiene una altura de 4.784 metros y su última erupción registrada ocurrió en 1999.', 
'https://img.goraymi.com/2016/05/04/ae23f7c35f3dde905f242e58b3642026_xl.jpg', 
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63836.85670626567!2d-78.60454231300145!3d-0.15593464190020132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d59cf09618226b%3A0x24e44e303a5557f0!2sCerro%20Guagua%20Pichincha!5e0!3m2!1ses-419!2sec!4v1736887359510!5m2!1ses-419!2sec" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>');




-- Si algo sale mal
-- Eliminar las tablas si existen
DROP TABLE IF EXISTS InformacionExtra;
DROP TABLE IF EXISTS Galeria;
DROP TABLE IF EXISTS Tags;
DROP TABLE IF EXISTS Puntaje;
DROP TABLE IF EXISTS Comentarios;
DROP TABLE IF EXISTS Montaña;
DROP TABLE IF EXISTS Usuario;

