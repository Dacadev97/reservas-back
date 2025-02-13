# API de Gestión de Reservas de Eventos

Este es el backend de la aplicación de gestión de reservas de eventos, desarrollado con Laravel.

## Requisitos Previos

- PHP 8.1 o superior
- Composer
- MySQL o PostgreSQL
- Node.js y npm (para la documentación Swagger)
- Laravel 10
- Extensión PHP `pdo` habilitada

## Instalación y Configuración

1. Clona el repositorio:

    ```sh
    git clone https://github.com/usuario/proyecto-reservas.git
    cd proyecto-reservas/backend
    ```

2. Instala las dependencias:

    ```sh
    composer install
    ```

3. Configura el archivo de entorno:

    ```sh
    cp .env.example .env
    ```

    Modifica las variables del `.env` según tu entorno, especialmente la conexión a la base de datos.

4. Genera la clave de la aplicación:

    ```sh
    php artisan key:generate
    ```

5. Ejecuta las migraciones y semillas:

    ```sh
    php artisan migrate --seed
    ```

6. Inicia el servidor:
    ```sh
    php artisan serve
    ```

## Endpoints

| Método | Ruta                          | Descripción                    |
| ------ | ----------------------------- | ------------------------------ |
| GET    | /api/events                   | Obtener todos los eventos      |
| GET    | /api/events/{id}              | Obtener detalle de un evento   |
| POST   | /api/events                   | Crear un nuevo evento          |
| PUT    | /api/events/{id}              | Actualizar un evento existente |
| DELETE | /api/events/{id}              | Eliminar un evento             |
| POST   | /api/events/{id}/reservations | Reservar un evento             |

Para más detalles, consulta la documentación generada con Swagger:

```
http://localhost:8000/api/documentation
```

## Pruebas

Para ejecutar las pruebas unitarias:

```sh
php artisan test
```
