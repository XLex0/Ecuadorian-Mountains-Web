-- 1️⃣ Crear la base de datos
CREATE DATABASE IF NOT EXISTS inti_cumbres;
USE inti_cumbres;

-- 2️⃣ Crear la tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_login TIMESTAMP NULL DEFAULT NULL
);

-- 3️⃣ Crear la tabla de montañas con coordenadas y mapas
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

-- 4️⃣ Crear la tabla de rutas
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

-- 5️⃣ Crear la tabla de equipo recomendado
CREATE TABLE IF NOT EXISTS equipo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    obligatorio BOOLEAN DEFAULT FALSE,
    montana_id INT NOT NULL,
    FOREIGN KEY (montana_id) REFERENCES montanas(id) ON DELETE CASCADE
);

-- 6️⃣ Crear la tabla de temporadas ideales
CREATE TABLE IF NOT EXISTS temporadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mes_inicio INT NOT NULL,
    mes_fin INT NOT NULL,
    descripcion TEXT,
    condiciones_climaticas TEXT,
    montana_id INT NOT NULL,
    FOREIGN KEY (montana_id) REFERENCES montanas(id) ON DELETE CASCADE
);

-- 7️⃣ Crear la tabla de refugios
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

-- 8️⃣ Crear la tabla de guías y operadores
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

-- 9️⃣ Crear la tabla de comentarios
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

-- 🔟 Crear la tabla de sesiones para autenticación
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
'https://upload.wikimedia.org/wikipedia/commons/2/2c/Volc%C3%A1n_Chimborazo_Ecuador.jpg',
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3987.819941952447!2d-78.81666699999999!3d-1.4666669999999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d3a8c9b96cf4ef%3A0x26a1d0b86d8e4bb4!2sVolc%C3%A1n%20Chimborazo!5e0!3m2!1ses!2sec!4v1234567890!5m2!1ses!2sec" width="600" height="450" style="border:0;" allowfullscreen loading="lazy"></iframe>'),

('Cayambe', -77.986667, -0.029722, 'Cordillera Oriental, Ecuador', 5790, 'Volcán',
'El Cayambe es un volcán activo ubicado en la Cordillera Oriental de los Andes ecuatorianos.',
'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/Cayambe_volcano.jpg/1280px-Cayambe_volcano.jpg',
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.817647930097!2d-77.98666699999999!3d-0.029722!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e2a1f0b5c7b9b9f%3A0x5c7b9b9f0b5c7b9b!2sVolc%C3%A1n%20Cayambe!5e0!3m2!1ses!2sec!4v1234567890!5m2!1ses!2sec" width="600" height="450" style="border:0;" allowfullscreen loading="lazy"></iframe>'),

('Antisana', -78.141389, -0.481389, 'Cordillera Oriental, Ecuador', 5758, 'Volcán',
'El Antisana es un volcán potencialmente activo en Ecuador.',
'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Antisana.jpg/1280px-Antisana.jpg',
'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.817647930097!2d-78.141389!3d-0.481389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e2a1f0b5c7b9b9f%3A0x5c7b9b9f0b5c7b9b!2sVolc%C3%A1n%20Antisana!5e0!3m2!1ses!2sec!4v1234567890!5m2!1ses!2sec" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>');

-- 🔹 Insertar datos en la tabla de usuarios (para pruebas)
INSERT INTO usuarios (username, email, password_hash) VALUES
('admin', 'admin@example.com', SHA2('admin123', 256)),
('usuario1', 'usuario1@example.com', SHA2('password123', 256));

-- 🔹 Insertar comentarios de prueba
INSERT INTO comentarios (montana_id, usuario_id, comentario, calificacion)
VALUES (1, 1, 'Hermosa montaña, pero el ascenso es difícil.', 5),
       (1, 2, 'Una vista increíble desde la cumbre.', 4);

-- 🔹 Insertar datos en rutas
INSERT INTO rutas (nombre, dificultad, distancia, desnivel_acumulado, punto_partida, punto_llegada, descripcion, recomendaciones, montana_id)
VALUES ('Ruta Normal', 'Intermedia', 10.5, 1300, 'Base del Chimborazo', 'Cumbre', 'Ruta más común para escalar el Chimborazo.', 'Se recomienda aclimatación previa.', 1);

-- 🔹 Insertar equipo recomendado
INSERT INTO equipo (nombre, descripcion, obligatorio, montana_id)
VALUES ('Crampones', 'Equipo esencial para el hielo y nieve.', TRUE, 1);

-- 🔹 Insertar temporada ideal
INSERT INTO temporadas (mes_inicio, mes_fin, descripcion, condiciones_climaticas, montana_id)
VALUES (6, 8, 'Época seca con condiciones más estables.', 'Baja humedad y menor probabilidad de nevadas.', 1);

-- 🔹 Insertar refugios
INSERT INTO refugios (nombre, altitud, capacidad, servicios, estado_actual, contacto, montana_id)
VALUES ('Refugio Carrel', 4800, 40, 'Dormitorios, comida, información', 'Operativo', 'info@refugiocarrel.com', 1);

-- 🔹 Insertar guías
INSERT INTO guias_operadores (nombre, certificaciones, experiencia, contacto, tarifas, montana_id)
VALUES ('Andes Guides', 'UIAGM, ASEGUIM', 'Más de 10 años de experiencia', 'contacto@andesguides.com', '$250 por ascenso', 1);
