# idea-web

Proyecto Web de Escuela Deportiva
Este proyecto es una aplicación web para una escuela deportiva, donde los usuarios pueden ver información sobre deportes, eventos, galería de fotos y un blog. También permite el registro y acceso de usuarios a través de un formulario de autenticación.

Características
Página de inicio con una galería de imágenes animada.
Secciones para diferentes deportes y eventos deportivos.
Registro y autenticación de usuarios.
Blog y galería interactiva.
Estructura del Proyecto
bash
Copy code
/tu-proyecto
│
├── /css                    # Estilos CSS
├── /img                    # Imágenes del sitio
├── /js                     # Archivos JavaScript
│   └── swiper_deportes.js   # Código para el carrusel de imágenes
├── /tests                  # Pruebas del proyecto
│   ├── test_selenium.py     # Pruebas automatizadas con Selenium
│   └── /java                # Pruebas unitarias con JUnit
│       ├── RegistroTest.java
├── /src                    # Código fuente en Java
│   └── /main
│       └── /java
│           └── tu/paquete/  # Clases de negocio
└── package.json             # Archivo de dependencias (si usas Node.js)
└── pom.xml                  # Archivo de dependencias (si usas Maven)
Requisitos
Antes de comenzar, asegúrate de tener los siguientes programas instalados en tu máquina:

PHP (para el backend)
MySQL (para la base de datos)
Apache o algún otro servidor web
Java (para las pruebas con JUnit)
Selenium WebDriver (para pruebas automáticas)
Maven (si usas Maven para gestionar dependencias)
Configuración del Entorno
1. Clonar el Repositorio
bash
Copy code
git clone https://github.com/tu-usuario/tu-proyecto.git
cd tu-proyecto
2. Configurar la Base de Datos
Crear una base de datos llamada pagina_db.
Ejecutar el script SQL para crear la tabla de usuarios:
sql
Copy code
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);
3. Configurar el Servidor
Coloca el proyecto en el directorio raíz de tu servidor web (por ejemplo, /var/www/html para Apache).
Asegúrate de que el archivo conexion.php tenga los datos correctos de conexión a la base de datos:
php
Copy code
$servername = "localhost";
$username = "root";
$password = "";  // Cambia esto si es necesario
$dbname = "pagina_db";
4. Instalar Dependencias (si usas Maven)
bash
Copy code
mvn clean install
5. Iniciar el Proyecto
Inicia el servidor web y accede al proyecto en http://localhost/tu-proyecto.
Ejecutar las Pruebas
1. Pruebas con JUnit
Para ejecutar las pruebas unitarias con JUnit:

bash
Copy code
mvn test
Esto ejecutará todas las pruebas de la carpeta tests/java y validará que el formulario de registro funcione correctamente.

2. Pruebas Automatizadas con Selenium
Para ejecutar las pruebas con Selenium, asegúrate de tener configurado el WebDriver y ejecuta:

bash
Copy code
python tests/test_selenium.py
Integración Continua
Este proyecto está configurado para utilizar Azure DevOps con un pipeline que ejecuta automáticamente las pruebas y despliega los cambios en un servidor en la nube. Cada vez que se realiza un commit en el repositorio, se desencadenan los siguientes pasos:

Ejecutar pruebas unitarias con JUnit.
Ejecutar pruebas funcionales con Selenium.
Desplegar los cambios en el servidor Apache.
Colaboradores
Ericka Yoseline Escalera Velasco - Desarrollo y Pruebas
 
