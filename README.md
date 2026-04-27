 API CRUD PHP
Esta API es una implementación robusta de un CRUD (Create, Read, Update, Delete) desarrollada en PHP.

🛠 Características Técnicas
Arquitectura: MVC (Modelo-Vista-Controlador) y patrón Singleton para la gestión de la base de datos.

Contenedores: Entorno totalmente orquestado con Docker y Docker Compose.

Seguridad: Uso de PDO con sentencias preparadas para prevenir inyecciones SQL.

Manejo de errores: Global Exception Handler con respuestas estandarizadas en formato JSON.

Configuración: Gestión de variables de entorno (.env) para configuraciones y lógica de negocio.


📋 Requisitos Previos
Tener instalados los siguientes componentes en tu máquina:

Docker
Docker Compose

🚀 Instalación y Ejecución
Clonar el repositorio:

```
- git clone [url-del-repositorio]
- cd [nombre-de-la-carpeta]
```

Configurar variables de entorno:
Copia el archivo de ejemplo para crear tu propia configuración:

```
 `cp .env.example .env
```

Edita el archivo .env y define tus valores:

```
DB_HOST=db
DB_NAME=base_de_datos
DB_USER=usuario
DB_PASS=password
PRECIO_USD=1000  # Variable utilizada para la lógica de negocio
```

Ejecuta el siguiente comando para orquestar los contenedores

```
docker-compose up -d --build
```
Acceso:
La API estará disponible en http://localhost:8895 (ajusta el puerto según tu configuración en docker-compose.yml).


Intalar las dependencias dentro del contenedor

```
composer install
```

📝 API Endpoints


| Método | Endpoint          | Acción | Descripción |
| :--- |:------------------| :--- | :--- |
| **GET** | `api/productos`   | Listar | Obtiene todos los productos registrados |
| **GET** | `api/productos/{id}` | Consultar | Obtiene los detalles de un producto específico |
| **POST** | `api/productos`      | Crear | Registra un nuevo producto (requiere JSON) |
| **PUT** | `api/productos/{id}` | Actualizar | Modifica un producto existente (requiere JSON) |
| **DELETE** | `api/productos/{id}` | Eliminar | Borra un producto del sistema |



Ejemplo de creación (POST /productos)

```
{
"nombre": "Notebook Asus x515",
"descripcion": "Descripción del producto",
"precio": 1200000
}
```

Ejemplo de respuesta (Éxito)

```
{
"success": true,
"message": "Producto creado con éxito",
"data": {
    "id": 1,
    "nombre": "Notebook Asus x515",
    "descripcion": "Descripción del producto",
    "precio_usd": 1200.00
    }
}
```
