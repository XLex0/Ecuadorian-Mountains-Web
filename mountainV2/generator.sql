-- 1️ Crear la base de datos
CREATE DATABASE IF NOT EXISTS inti_cumbres;
USE inti_cumbres;

-- 2️ Crear la tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_login TIMESTAMP NULL DEFAULT NULL
);

-- 3️ Crear la tabla de montañas con coordenadas y mapas
CREATE TABLE IF NOT EXISTS montanas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    longitud DECIMAL(10,6) NOT NULL,
    latitud DECIMAL(10,6) NOT NULL,
    ubicacion VARCHAR(255) NOT NULL,
    altura INT NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    urlImagenPrincipal VARCHAR(500),
    mapsEmbeded TEXT
);

-- 4️ Crear la tabla de rutas
CREATE TABLE IF NOT EXISTS rutas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    dificultad ENUM('Fácil', 'Intermedia', 'Difícil') NOT NULL,
    distancia DECIMAL(5,2) NOT NULL,
    desnivel_acumulado DECIMAL(5,2),
    punto_partida VARCHAR(255),
    punto_llegada VARCHAR(255),
    descripcion TEXT,
    recomendaciones TEXT,
    montana_id INT NOT NULL,
    FOREIGN KEY (montana_id) REFERENCES montanas(id) ON DELETE CASCADE
);

-- 5️ Crear la tabla de equipo recomendado
CREATE TABLE IF NOT EXISTS equipo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    obligatorio BOOLEAN DEFAULT FALSE,
    montana_id INT NOT NULL,
    FOREIGN KEY (montana_id) REFERENCES montanas(id) ON DELETE CASCADE
);

-- 6️ Crear la tabla de temporadas ideales
CREATE TABLE IF NOT EXISTS temporadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mes_inicio INT NOT NULL,
    mes_fin INT NOT NULL,
    descripcion TEXT,
    condiciones_climaticas TEXT,
    montana_id INT NOT NULL,
    FOREIGN KEY (montana_id) REFERENCES montanas(id) ON DELETE CASCADE
);

-- 7️ Crear la tabla de refugios
CREATE TABLE IF NOT EXISTS refugios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    altitud FLOAT NOT NULL,
    capacidad INT,
    servicios TEXT,
    estado_actual VARCHAR(50),
    contacto VARCHAR(255),
    montana_id INT NOT NULL,
    FOREIGN KEY (montana_id) REFERENCES montanas(id) ON DELETE CASCADE
);

-- 8️ Crear la tabla de guías y operadores
CREATE TABLE IF NOT EXISTS guias_operadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    certificaciones TEXT,
    experiencia TEXT,
    contacto VARCHAR(255),
    tarifas TEXT,
    montana_id INT NOT NULL,
    FOREIGN KEY (montana_id) REFERENCES montanas(id) ON DELETE CASCADE
);

-- 9️ Crear la tabla de comentarios
CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    montana_id INT NOT NULL,
    usuario_id INT NOT NULL,
    comentario TEXT NOT NULL,
    calificacion INT CHECK (calificacion BETWEEN 1 AND 5),
    fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (montana_id) REFERENCES montanas(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

--  Crear la tabla de sesiones para autenticación
CREATE TABLE IF NOT EXISTS sesiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- 🔹 Insertar datos en la tabla de montañas
INSERT INTO montanas (nombre, longitud, latitud, ubicacion, altura, tipo, descripcion, urlImagenPrincipal, mapsEmbeded)
VALUES 
('Chimborazo', -78.816667, -1.466667, 'Cordillera Occidental, Ecuador', 6263, 'Volcán Inactivo', 
'El Chimborazo es el volcán más alto de Ecuador, con una altura de 6.263 metros sobre el nivel del mar.',
'https://th.bing.com/th/id/OIP.qOj_mWLj0xCRNR83dDKJGgHaE8?rs=1&pid=ImgDetMain',
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3987.819941952447!2d-78.81666699999999!3d-1.4666669999999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d3a8c9b96cf4ef%3A0x26a1d0b86d8e4bb4!2sVolc%C3%A1n%20Chimborazo!5e0!3m2!1ses!2sec!4v1234567890!5m2!1ses!2sec" width="600" height="450" style="border:0;" allowfullscreen loading="lazy"></iframe>'),

('Cayambe', -77.986667, -0.029722, 'Cordillera Oriental, Ecuador', 5790, 'Volcán',
'El Cayambe es un volcán activo ubicado en la Cordillera Oriental de los Andes ecuatorianos.',
'https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Cayambe_Vulkan_w.jpg/1200px-Cayambe_Vulkan_w.jpg',
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.817647930097!2d-77.98666699999999!3d-0.029722!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e2a1f0b5c7b9b9f%3A0x5c7b9b9f0b5c7b9b!2sVolc%C3%A1n%20Cayambe!5e0!3m2!1ses!2sec!4v1234567890!5m2!1ses!2sec" width="600" height="450" style="border:0;" allowfullscreen loading="lazy"></iframe>'),

('Antisana', -78.141389, -0.481389, 'Cordillera Oriental, Ecuador', 5758, 'Volcán',
'El Antisana es un volcán potencialmente activo en Ecuador.',
'https://upload.wikimedia.org/wikipedia/commons/5/56/Antisana.jpg',
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.817647930097!2d-78.141389!3d-0.481389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e2a1f0b5c7b9b9f%3A0x5c7b9b9f0b5c7b9b!2sVolc%C3%A1n%20Antisana!5e0!3m2!1ses!2sec!4v1234567890!5m2!1ses!2sec" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'),

('Cotopaxi', -78.436111, -0.680833, 'Cordillera Central, Ecuador', 5897, 'Volcán', 
'El Cotopaxi es un volcán activo en Ecuador, con una altura de 5.897 metros sobre el nivel del mar.',
'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMwf12bVBRvN931nFH9R7TO6uauZd6Yp6iyQ&s',
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15958.13694917274!2d-78.44748985754404!3d-0.6837326326529115!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d44e5dbbd52ce7%3A0x61297ba77301c1e8!2sVolc%C3%A1n%20Cotopaxi!5e0!3m2!1ses-419!2sus!4v1738628073462!5m2!1ses-419!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'),

('Tungurahua', -78.436111, -1.467222, 'Cordillera Oriental, Ecuador', 5023, 'Volcán',
'El Tungurahua es un volcán activo en Ecuador, con una altura de 5.023 metros sobre el nivel del mar.',
'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4bR1WCkwDRLif7BEiBGaYWE7EOsxS2q3gSQ&s',
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15954.019696326677!2d-78.45510525747167!3d-1.47018122549106!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d397b8949d7887%3A0x7a6fc778454797fb!2sVolc%C3%A1n%20Tungurahua!5e0!3m2!1ses-419!2sus!4v1738628281193!5m2!1ses-419!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'),

('Pichincha', -78.467778, -0.170833, 'Cordillera Occidental, Ecuador', 4784, 'Volcán',
'El Pichincha es un volcán activo en Ecuador, con una altura de 4.784 metros sobre el nivel del mar.',
'https://bushop.com/ecuador/wp-content/uploads/sites/5/2019/03/pichincha-volcano-2-1.jpg',
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15959.202361179523!2d-78.62293495758358!3d-0.17081113950692722!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d59cef1a0b67fb%3A0x9f4de2049b30fbc4!2sVolc%C3%A1n%20Pichincha!5e0!3m2!1ses-419!2sus!4v1738628372740!5m2!1ses-419!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'),
    
('Iliniza', -78.820833, -0.6625, 'Cordillera Occidental, Ecuador', 5248, 'Volcán',
'Los Ilinizas son dos volcanes gemelos en Ecuador, con alturas de 5.248 y 5.126 metros sobre el nivel del mar.',
'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSqIu0k5pRYXAUkJ2yRiUxWdj4GpEUItW0Hmg&s',
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15958.203811820784!2d-78.72529975754571!3d-0.6633117828928707!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d4fecd40ac9709%3A0x3e56acb98a42527a!2sIlliniza!5e0!3m2!1ses-419!2sus!4v1738628503945!5m2!1ses-419!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>');


-- 🔹 Insertar datos en la tabla de usuarios (para pruebas)
INSERT INTO usuarios (username, email, password_hash) VALUES
('admin', 'admin@example.com', SHA2('admin123', 256)),
('usuario1', 'usuario1@example.com', SHA2('password123', 256));

-- 🔹 Insertar comentarios de prueba
INSERT INTO comentarios (montana_id, usuario_id, comentario, calificacion)
VALUES (1, 1, 'Hermosa montaña, pero el ascenso es difícil.', 5),
       (1, 2, 'Una vista increíble desde la cumbre.', 4),
       (2, 1, 'El Cayambe es un volcán impresionante.', 5),
       (2, 2, 'El glaciar es muy bonito.', 4),
       (3, 1, 'El Antisana es un volcán poco conocido.', 3),
       (3, 2, 'La ruta de ascenso es muy técnica.', 4),
       (4, 1, 'El Cotopaxi es un volcán icónico de Ecuador.', 5),
       (4, 2, 'El refugio es muy cómodo.', 4),
       (5, 1, 'El Tungurahua es un volcán activo.', 3),
       (5, 2, 'La vista desde el cráter es impresionante.', 4),
       (6, 1, 'El Pichincha es una montaña accesible desde Quito.', 4),
       (6, 2, 'La ruta de ascenso es muy empinada.', 3),
       (7, 1, 'Los Ilinizas son dos montañas gemelas.', 4),
       (7, 2, 'El ascenso es muy técnico.', 3);

-- 🔹 Insertar datos en rutas
INSERT INTO rutas (nombre, dificultad, distancia, desnivel_acumulado, punto_partida, punto_llegada, descripcion, recomendaciones, montana_id)
VALUES ('Ruta Normal', 'Intermedia', 10.5, 1300, 'Base del Chimborazo', 'Cumbre', 'Ruta más común para escalar el Chimborazo.', 'Se recomienda aclimatación previa.', 1),
        ('Ruta Sur', 'Difícil', 8.5, 1200, 'Refugio Carrel', 'Cumbre', 'Ruta técnica con pendientes pronunciadas.', 'Se recomienda equipo técnico.', 1),
        ('Ruta Normal', 'Fácil', 6.5, 800, 'Refugio Ruales-Oleas-Berge', 'Cumbre', 'Ruta más común para escalar el Cayambe.', 'Se recomienda aclimatación previa.', 2),
        ('Ruta Sur', 'Intermedia', 7.5, 1000, 'Refugio Carrel', 'Cumbre', 'Ruta técnica con pendientes pronunciadas.', 'Se recomienda equipo técnico.', 2),
        ('Ruta Normal', 'Fácil', 5.5, 700, 'Refugio Antisana', 'Cumbre', 'Ruta más común para escalar el Antisana.', 'Se recomienda aclimatación previa.', 3),
        ('Ruta Sur', 'Intermedia', 6.5, 900, 'Refugio Carrel', 'Cumbre', 'Ruta técnica con pendientes pronunciadas.', 'Se recomienda equipo técnico.', 3),
        ('Ruta Normal', 'Fácil', 7.5, 900, 'Refugio José Rivas', 'Cumbre', 'Ruta más común para escalar el Cotopaxi.', 'Se recomienda aclimatación previa.', 4),
        ('Ruta Sur', 'Intermedia', 8.5, 1100, 'Refugio Carrel', 'Cumbre', 'Ruta técnica con pendientes pronunciadas.', 'Se recomienda equipo técnico.', 4),
        ('Ruta Normal', 'Fácil', 4.5, 600, 'Refugio Tungurahua', 'Cumbre', 'Ruta más común para escalar el Tungurahua.', 'Se recomienda aclimatación previa.', 5),
        ('Ruta Sur', 'Intermedia', 5.5, 800, 'Refugio Carrel', 'Cumbre', 'Ruta técnica con pendientes pronunciadas.', 'Se recomienda equipo técnico.', 5),
        ('Ruta Normal', 'Fácil', 3.5, 500, 'Refugio Rucu Pichincha', 'Cumbre', 'Ruta más común para escalar el Pichincha.', 'Se recomienda aclimatación previa.', 6),
        ('Ruta Sur', 'Intermedia', 4.5, 700, 'Refugio Carrel', 'Cumbre', 'Ruta técnica con pendientes pronunciadas.', 'Se recomienda equipo técnico.', 6),
        ('Ruta Normal', 'Fácil', 5.5, 600, 'Refugio Iliniza Norte', 'Cumbre', 'Ruta más común para escalar el Iliniza Norte.', 'Se recomienda aclimatación previa.', 7),
        ('Ruta Sur', 'Intermedia', 6.5, 800, 'Refugio Carrel', 'Cumbre', 'Ruta técnica con pendientes pronunciadas.', 'Se recomienda equipo técnico.', 7);

;

-- 🔹 Insertar equipo recomendado
INSERT INTO equipo (nombre, descripcion, obligatorio, montana_id)
VALUES ('Crampones', 'Equipo esencial para el hielo y nieve.', TRUE, 1),
        ('Piolet', 'Herramienta para el hielo y nieve.', TRUE, 1),
        ('Casco', 'Protección para la cabeza.', TRUE, 1),
        ('Botas de montaña', 'Calzado adecuado para terrenos rocosos.', TRUE, 1),
        ('Cuerda', 'Equipo de seguridad para ascensos.', FALSE, 1),
        ('Gafas de sol', 'Protección para los ojos en la nieve.', FALSE, 1),
        ('Protector solar', 'Protección para la piel en la montaña.', FALSE, 1),
        ('Botiquín de primeros auxilios', 'Equipo de emergencia para lesiones.', FALSE, 1),
        ('Crampones', 'Equipo esencial par el hielo y nieve', TRUE, 2),
        ('Piolet', 'Herramienta para el hielo y nieve.', TRUE, 2),
        ('Casco', 'Protección para la cabeza.', TRUE, 2),
        ('Botas de montaña', 'Calzado adecuado para terrenos rocosos.', TRUE, 2),
        ('Cuerda', 'Equipo de seguridad para ascensos.', FALSE, 2),
        ('Gafas de sol', 'Protección para los ojos en la nieve.', FALSE, 2),
        ('Protector solar', 'Protección para la piel en la montaña.', FALSE, 2),
        ('Botiquín de primeros auxilios', 'Equipo de emergencia para lesiones.', FALSE, 2),
        ('Crampones', 'Equipo esencial para el hielo y nieve.', TRUE, 3),
        ('Piolet', 'Herramienta para el hielo y nieve.', TRUE, 3),
        ('Casco', 'Protección para la cabeza.', TRUE, 3),
        ('Botas de montaña', 'Calzado adecuado para terrenos rocosos.', TRUE, 3),
        ('Cuerda', 'Equipo de seguridad para ascensos.', FALSE, 3),
        ('Gafas de sol', 'Protección para los ojos en la nieve.', FALSE, 3),
        ('Protector solar', 'Protección para la piel en la montaña.', FALSE, 3),
        ('Botiquín de primeros auxilios', 'Equipo de emergencia para lesiones.', FALSE, 3),
        ('Crampones', 'Equipo esencial para el hielo y nieve.', TRUE, 4),
        ('Piolet', 'Herramienta para el hielo y nieve.', TRUE, 4),
        ('Casco', 'Protección para la cabeza.', TRUE, 4),
        ('Botas de montaña', 'Calzado adecuado para terrenos rocosos.', TRUE, 4),
        ('Cuerda', 'Equipo de seguridad para ascensos.', FALSE, 4),
        ('Gafas de sol', 'Protección para los ojos en la nieve.', FALSE, 4),
        ('Protector solar', 'Protección para la piel en la montaña.', FALSE, 4),
        ('Botiquín de primeros auxilios', 'Equipo de emergencia para lesiones.', FALSE, 4),
        ('Crampones', 'Equipo esencial para el hielo y nieve.', TRUE, 5),
        ('Piolet', 'Herramienta para el hielo y nieve.', TRUE, 5),
        ('Casco', 'Protección para la cabeza.', TRUE, 5),
        ('Botas de montaña', 'Calzado adecuado para terrenos rocosos.', TRUE, 5),
        ('Cuerda', 'Equipo de seguridad para ascensos.', FALSE, 5),
        ('Gafas de sol', 'Protección para los ojos en la nieve.', FALSE, 5),
        ('Protector solar', 'Protección para la piel en la montaña.', FALSE, 5),
        ('Botiquín de primeros auxilios', 'Equipo de emergencia para lesiones.', FALSE, 5),
        ('Crampones', 'Equipo esencial para el hielo y nieve.', TRUE, 6),
        ('Piolet', 'Herramienta para el hielo y nieve.', TRUE, 6),
        ('Casco', 'Protección para la cabeza.', TRUE, 6),
        ('Botas de montaña', 'Calzado adecuado para terrenos rocosos.', TRUE, 6),
        ('Cuerda', 'Equipo de seguridad para ascensos.', FALSE, 6),
        ('Gafas de sol', 'Protección para los ojos en la nieve.', FALSE, 6),
        ('Protector solar', 'Protección para la piel en la montaña.', FALSE, 6),
        ('Botiquín de primeros auxilios', 'Equipo de emergencia para lesiones.', FALSE, 6),
        ('Crampones', 'Equipo esencial para el hielo y nieve.', TRUE, 7),
        ('Piolet', 'Herramienta para el hielo y nieve.', TRUE, 7),
        ('Casco', 'Protección para la cabeza.', TRUE, 7),
        ('Botas de montaña', 'Calzado adecuado para terrenos rocosos.', TRUE, 7),
        ('Cuerda', 'Equipo de seguridad para ascensos.', FALSE, 7),
        ('Gafas de sol', 'Protección para los ojos en la nieve.', FALSE, 7),
        ('Protector solar', 'Protección para la piel en la montaña.', FALSE, 7),
        ('Botiquín de primeros auxilios', 'Equipo de emergencia para lesiones.', FALSE, 7)
;

-- 🔹 Insertar temporada ideal
INSERT INTO temporadas (mes_inicio, mes_fin, descripcion, condiciones_climaticas, montana_id)
VALUES (6, 8, 'Época seca con condiciones más estables.', 'Baja humedad y menor probabilidad de nevadas.', 1),
        (6, 9, 'Época seca con condiciones más estables.', 'Baja humedad y menor probabilidad de nevadas.', 2),
        (6, 8, 'Época seca con condiciones más estables.', 'Baja humedad y menor probabilidad de nevadas.', 3),
        (5, 8, 'Época seca con condiciones más estables.', 'Baja humedad y menor probabilidad de nevadas.', 4),
        (4, 8, 'Época seca con condiciones más estables.', 'Baja humedad y menor probabilidad de nevadas.', 5),
        (6, 8, 'Época seca con condiciones más estables.', 'Baja humedad y menor probabilidad de nevadas.', 6),
        (5, 8, 'Época seca con condiciones más estables.', 'Baja humedad y menor probabilidad de nevadas.', 7);
;

-- 🔹 Insertar refugios
INSERT INTO refugios (nombre, altitud, capacidad, servicios, estado_actual, contacto, montana_id)
VALUES ('Refugio Carrel', 4800, 40, 'Dormitorios, comida, información', 'Operativo', 'info@refugiocarrel.com', 1),
        ('Refugio Ruales-Oleas-Berge', 4600, 30, 'Dormitorios, comida, información', 'Operativo', 'https://www.summitpost.org/cayambe-refuge/629857', 2),
        ('Refugio Carrel', 4800, 40, 'Dormitorios, comida, información', 'Operativo', 'na', 3),
        ('Refugio José Rivas', 4800, 40, 'Dormitorios, comida, información', 'Operativo', '+593 98 790 8704', 4),
        ('Refugio Carrel', 4800, 40, 'Dormitorios, comida, información', 'Operativo', 'na', 5),
        ('Refugio Rucu Pichincha', 4600, 30, 'Dormitorios, comida, información', 'Operativo', '+593 98 409 0956', 6),
        ('Refugio Nuevos Horizontes', 4800, 40, 'Dormitorios, comida, información', 'Operativo', '+593 09 969 9068', 7);
;

-- 🔹 Insertar guías
INSERT INTO guias_operadores (nombre, certificaciones, experiencia, contacto, tarifas, montana_id)
VALUES ('Andes Guides', 'UIAGM, ASEGUIM', 'Más de 10 años de experiencia', 'contacto@andesguides.com', '$250 por ascenso', 1),
        ('Ian Taylor Trekking', 'ASEGUIM', 'Más de 5 años de experiencia', 'info@iantaylortrekking.com', '$200 por ascenso', 2),
        ('Andean Face', 'ASEGUIM', 'Más de 5 años de experiencia', 'info@andeanface.com', '$200 por ascenso', 3),
        ('Ian Taylor Trekking', 'ASEGUIM', 'Más de 5 años de experiencia', 'info@iantaylortrekking.com', '$200 por ascenso', 4),
        ('Steven Moore', 'ASEGUIM', 'Más de 5 años de experiencia', 'stevenmoore9@outlook.com', '$100 por ascenso', 5),
        ('Andes Guides', 'UIAGM, ASEGUIM', 'Más de 10 años de experiencia', 'info@andeanface.com', '$80 por ascenso', 6),
        ('Ian Taylor Trekking', 'ASEGUIM', 'Más de 5 años de experiencia', 'info@iantaylortrekking.com', '$180 por ascenso', 7);