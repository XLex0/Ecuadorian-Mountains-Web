# 🏔️ Inti Cumbres: Explorador de Montañas Ecuatorianas 🇪🇨
Plataforma web integral desarrollada en la Escuela Politécnica Nacional (6to Semestre) para descubrir y explorar las majestuosas montañas del Ecuador.

## ✨ **Índice**

1. [Resumen del Proyecto](#-resumen-del-proyecto)  
2. [Características Principales](#-características-principales)  
3. [Tecnologías Utilizadas](#-tecnologías-utilizadas)  
4. [Requisitos Previos](#-requisitos-previos)  
5. [Instalación y Configuración](#-instalación-y-configuración)  
   - [Instalación Local (XAMPP)](#1-instalación-local-xampp)  
   - [Despliegue en Clever Cloud](#2-despliegue-en-clever-cloud)  
6. [Arquitectura y Estructura](#-arquitectura-y-estructura)  
7. [Modelo de Datos](#-modelo-de-datos)  
8. [Funcionalidades Clave](#-funcionalidades-clave)  
9. [Credenciales de Prueba](#-credenciales-de-prueba)  
10. [Contribución](#-contribución)  
11. [Licencia](#-licencia)  
12. [Desarrolladores](#-desarrolladores)  

---

## 📝 **Resumen del Proyecto**

**Inti Cumbres** es una aplicación web enfocada en promover el turismo y la aventura en las montañas del Ecuador. Permite:
- Conocer información detallada de cada montaña (ubicación, altura, dificultad, etc.).
- Conectar con guías certificados.
- Descubrir las mejores temporadas de escalada y refugios disponibles.
- Interactuar con otros usuarios mediante comentarios y calificaciones.

Este proyecto forma parte del curso **Aplicaciones Web** de la **Escuela Politécnica Nacional** (6to Semestre), demostrando competencias en **desarrollo web full-stack** (Frontend y Backend).

---

## 🌟 **Características Principales**

- 🗻 **Catálogo de Montañas**  
  Listado de montañas con descripciones, imágenes y datos clave (ubicación, altura, etc.).

- 👥 **Guías de Montaña**  
  Información de contacto y experiencia de guías locales.

- 🧗 **Equipo Recomendado**  
  Listado de equipo esencial para la escalada y senderismo.

- 🏕️ **Refugios de Montaña**  
  Localización y detalles de refugios en diferentes rutas.

- 📅 **Temporadas de Escalada**  
  Recomendaciones de las mejores fechas para cada montaña.

- 💬 **Comentarios y Calificaciones**  
  Sistema de reseñas de usuarios para compartir experiencias y consejos.

---

## 🛠️ **Tecnologías Utilizadas**

| **Frontend** | **Backend**  | **Base de Datos** | **Alojamiento**   |
|--------------|--------------|-------------------|-------------------|
| - HTML5      | - PHP        | - MySQL           | - XAMPP (Local)   |
| - CSS3       | - MVC nativo | - Modelo Relacional | - Clever Cloud   |
| - JavaScript | - Sesiones   |                   |                   |
| - TailwindCSS (opcional) |    |                   |                   |

---

## 📋 **Requisitos Previos**

1. **Entorno de desarrollo PHP** (ej. [XAMPP](https://www.apachefriends.org/es/index.html), LAMP/WAMP, etc.)  
2. **Base de Datos MySQL** (versión 5.7+ recomendada)  
3. **Navegador Web** moderno (Chrome, Firefox, Edge, etc.)  
4. (Opcional) **Cuenta en Clever Cloud** para despliegue remoto.  

---

## ⚙️ **Instalación y Configuración**

### 1. **Instalación Local (XAMPP)**

1. **Clona el repositorio** en tu carpeta `htdocs` de XAMPP:
   ```bash
   git clone https://github.com/tuUsuario/Ecuadorian-Mountains-Web.git
   ```
2. **Inicia Apache y MySQL** desde el panel de control de XAMPP.
3. **Crea la base de datos** en `phpMyAdmin` (por ejemplo, `inti_cumbres_db`).
4. **Importa el archivo SQL**:
   - Ve a `phpMyAdmin`.
   - Selecciona tu nueva base de datos.
   - Importa el archivo `generator.sql` (o el nombre que tenga tu script) desde la opción *Importar*.
5. **Configura tus credenciales** en `configBD/login.php` o el archivo donde se encuentre la conexión:
   ```php
   <?php
   $host = "localhost";
   $user = "root";
   $password = "";
   $dbname = "inti_cumbres_db";
   // ...
   ?>
   ```
6. **Abre tu navegador** y visita:  
   ```
   http://localhost/Ecuadorian-Mountains-Web/
   ```
   ¡Listo! Podrás navegar por la aplicación.

### 2. **Despliegue en Clever Cloud**

1. **Crea una cuenta** en [Clever Cloud](https://www.clever-cloud.com).
2. **Conecta el repositorio** de Git con Clever Cloud (pasos disponibles en su panel).
3. **Crea una base de datos MySQL** desde tu panel de Clever Cloud.
4. **Configura las variables de entorno** en Clever Cloud con tus datos de conexión.
5. **Realiza push al repositorio remoto**: Clever Cloud desplegará automáticamente tu aplicación.  
   - Verifica que las credenciales de tu archivo de conexión concuerden con las asignadas por Clever Cloud.

---

## 🏛️ **Arquitectura y Estructura**

**Modelo-Vista-Controlador (MVC)**  
- **Modelo**: Conexión y manejo de datos (lógica en `configBD/`).  
- **Vista**: Plantillas HTML en `templates2/` (se incluyen archivos `.html` o `.php` con diseño responsivo).  
- **Controlador**: Gestiona la interacción entre modelo y vista, usualmente en scripts PHP separados.  

### 📁 **Estructura de Carpetas**

```bash
Ecuadorian-Mountains-Web/
│
├── configBD/        # Lógica de backend y conexiones
│   ├── login.php
│   ├── auth.php
│   └── ...
│
├── statics2/        # Recursos estáticos
│   ├── css/
│   ├── img/
│   └── js/
│
└── templates2/      # Vistas HTML/PHP
```

---

## 💾 **Modelo de Datos**

- **`usuarios`**  
  Campos: `id_usuario`, `nombre`, `email`, `password`, `rol`, etc.

- **`montanas`**  
  Campos: `id_montana`, `nombre`, `descripcion`, `altura`, `ubicacion`, etc.

- **`comentarios`**  
  Campos: `id_comentario`, `id_usuario`, `id_montana`, `texto`, `fecha`, etc.

- **`rutas`**  
  Campos: `id_ruta`, `id_montana`, `nombre_ruta`, `dificultad`, etc.

- **`refugios`**  
  Campos: `id_refugio`, `nombre`, `ubicacion`, etc.

- **`temporadas`**  
  Campos: `id_temporada`, `nombre`, `fecha_inicio`, `fecha_fin`, etc.

- **`guias_operadores`**  
  Campos: `id_guia`, `nombre`, `contacto`, `experiencia`, etc.

---

## 🌟 **Funcionalidades Clave**

1. **Exploración de Montañas**  
   - Catálogo con fichas de información: altura, localización, fotos, etc.  
   - Mapas interactivos (opcional) para ubicar cada montaña.

2. **Sistema de Usuarios**  
   - Registro e inicio de sesión (hash de contraseñas).  
   - Creación de perfiles y edición de información personal.  
   - Publicación de comentarios y valoraciones de montañas.

3. **Recursos para Escaladores**  
   - **Guías**: contacto y experiencia.  
   - **Equipo**: recomendaciones según dificultad de la ruta.  
   - **Refugios**: ubicación y capacidad.  
   - **Temporadas**: reseñas de fechas recomendadas.

---

## 🔒 **Credenciales de Prueba**

- **Admin**  
  - Usuario: `admin`  
  - Contraseña: `admin123`  

- **Usuario Regular**  
  - Usuario: `usuario1`  
  - Contraseña: `password123`  

Utiliza estas credenciales para explorar roles y funcionalidades.

---

## 🤝 **Contribución**

¿Te gustaría mejorar **Inti Cumbres** o proponer nuevas funcionalidades? ¡Eres bienvenido!

1. **Haz un fork** del repositorio.  
2. **Crea una rama** con tu nueva característica (`feature/nombre-caracteristica`).  
3. **Realiza commits** con tus cambios (asegúrate de detallar cada cambio en los mensajes de commit).  
4. **Haz push** a tu rama en tu repositorio.  
5. **Crea un Pull Request** explicando tu contribución.  

---

## 📝 **Licencia**

Este proyecto se distribuye bajo la licencia **MIT**, lo que significa que puedes usarlo, modificarlo y distribuirlo libremente mientras mantengas los avisos de copyright.

---

## 🙌 **Desarrolladores**

**Estudiantes de Aplicaciones Web - Escuela Politécnica Nacional (6to Semestre).**  

¡Gracias por visitar **Inti Cumbres** y contribuir a difundir la belleza de las montañas ecuatorianas!  

---

> **¡Sigue explorando y disfruta de la aventura!**  

---
