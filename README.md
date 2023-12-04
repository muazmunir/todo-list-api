# Laravel ToDo List API

This Laravel application provides a RESTful API for managing ToDo items.

## Getting Started

### Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/muazmunir/todo-list-api

2. Install Composer dependencies:

    ```bash
    composer install

3. Create a copy of the `.env` file:

    ```bash
    cp .env.example .env

4. Configure the `.env` file with your database settings.

5. Generate the application key:

    ```bash
    php artisan key:generate

6. Run migrations to create the necessary tables:

    ```bash
    php artisan migrate

7. (Optional) Seed the database with sample data:

    ```bash
    php artisan db:seed

8. Start the development server:

    ```bash
    php artisan serve

## API Endpoints

### Authentication

- `POST /register`: Register a new user.
- `POST /login`: Log in and obtain an authentication token.
- `POST /logout`: Log out and invalidate the authentication token.

### ToDo Endpoints

- `GET /todos`: Retrieve paginated ToDo list for the authenticated user.
- `POST /todos`: Create a new ToDo item.
- `GET /todos/{todo}`: View details of a specific ToDo item.
- `PUT /todos/{todo}`: Update a ToDo item.
- `DELETE /todos/{todo}`: Delete a ToDo item.

## Usage

1. Register a new user using `/register` endpoint.
2. Log in via `/login` to obtain an authentication token.
3. Use the token in the `Authorization` header as `Bearer {token}` for protected endpoints.
4. Perform CRUD operations on ToDo items using respective endpoints.
5. Log out via `/logout` endpoint when finished.

## Dependencies

- Laravel Framework
- JWT Authentication
- Eloquent ORM

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
