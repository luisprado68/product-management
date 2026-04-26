🚀 API CRUD PHP Nativo
Esta API es una implementación robusta de un CRUD (Create, Read, Update, Delete) desarrollada en PHP puro (Vanilla PHP). El objetivo principal es demostrar una arquitectura limpia y profesional sin depender de frameworks pesados, aplicando patrones de diseño sólidos y técnicas de contenedorización.


🛠 Características Técnicas
Arquitectura: MVC (Modelo-Vista-Controlador) y patrón Singleton para la gestión de la base de datos.

Contenedores: Entorno totalmente orquestado con Docker y Docker Compose.

Seguridad: Uso de PDO con sentencias preparadas para prevenir inyecciones SQL.

Manejo de errores: Global Exception Handler con respuestas estandarizadas en formato JSON.

Configuración: Gestión de variables de entorno (.env) para configuraciones sensibles y lógica de negocio (conversión de divisas).


📋 Requisitos Previos
Asegúrate de tener instalados los siguientes componentes en tu máquina:

Docker

Docker Compose

Para que tu proyecto tenga un acabado profesional y sea fácil de entender para otros desarrolladores (o para ti mismo en el futuro), tu README.md debe ser claro, directo y técnico.

Aquí tienes una propuesta de estructura ideal. Copia y pega esto en un archivo llamado README.md en la raíz de tu proyecto y ajusta los detalles que falten.

🚀 API CRUD PHP Nativo
Esta API es una implementación robusta de un CRUD (Create, Read, Update, Delete) desarrollada en PHP puro (Vanilla PHP). El objetivo principal es demostrar una arquitectura limpia y profesional sin depender de frameworks pesados, aplicando patrones de diseño sólidos y técnicas de contenedorización.

🛠 Características Técnicas
Arquitectura: MVC (Modelo-Vista-Controlador) y patrón Singleton para la gestión de la base de datos.

Contenedores: Entorno totalmente orquestado con Docker y Docker Compose.

Seguridad: Uso de PDO con sentencias preparadas para prevenir inyecciones SQL.

Manejo de errores: Global Exception Handler con respuestas estandarizadas en formato JSON.

Configuración: Gestión de variables de entorno (.env) para configuraciones sensibles y lógica de negocio (conversión de divisas).

📋 Requisitos Previos
Asegúrate de tener instalados los siguientes componentes en tu máquina:

Docker

Docker Compose

🚀 Instalación y Ejecución
Clonar el repositorio:

```
- `git clone [tu-url-del-repositorio]`
- `cd [nombre-de-tu-carpeta]`
```

Configurar variables de entorno:
Copia el archivo de ejemplo para crear tu propia configuración:

```
 `cp .env.example .env
```

Edita el archivo .env y define tus valores:

```
DB_HOST=db
DB_NAME=tu_base_de_datos
DB_USER=tu_usuario
DB_PASS=tu_password
PRECIO_USD=1000  # Variable utilizada para la lógica de negocio
```

Ejecuta el siguiente comando para orquestar los contenedores

```
docker-compose up -d --build
```
Acceso:
La API estará disponible en http://localhost:8080 (ajusta el puerto según tu configuración en docker-compose.yml).


📂 Estructura del Proyecto

```
/
├── docker/            # Configuración de Docker/Apache
├── src/
│   ├── Controllers/   # Lógica de las rutas
│   ├── Models/        # Interacción con la BD (Singleton)
│   ├── Config/        # Gestión de conexión y variables
│   └── index.php      # Router y Exception Handler
├── .env               # Variables de entorno (No subir a Git)
└── docker-compose.yml
```

📝 API Endpoints


| Método | Endpoint | Acción | Descripción |
| :--- | :--- | :--- | :--- |
| **GET** | `/productos` | Listar | Obtiene todos los productos registrados |
| **GET** | `/productos/{id}` | Consultar | Obtiene los detalles de un producto específico |
| **POST** | `/productos` | Crear | Registra un nuevo producto (requiere JSON) |
| **PUT** | `/productos/{id}` | Actualizar | Modifica un producto existente (requiere JSON) |
| **DELETE** | `/productos/{id}` | Eliminar | Borra un producto del sistema |



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
    "precio_usd": 1200.00
    }
}
```