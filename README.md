# Task Manager API

This is a task management application built with the Symfony framework, featuring a GraphQL API for all interactions.

## Core Technologies

*   **PHP 8.2**
*   **Symfony 7.4**
*   **Doctrine ORM** (for database interactions)
*   **PostgreSQL** (as the database)
*   **OverblogGraphQLBundle** (for the GraphQL API)
*   **LexikJWTAuthenticationBundle** (for token-based authentication)
*   **Docker** (for containerized development)

## Getting Started

### Prerequisites

*   PHP 8.2 or higher
*   Composer
*   Docker & Docker Compose
*   Symfony CLI

### 1. Installation

1.  **Clone the repository:**
    ```bash
    git clone git@github.com:runaway90/test4programa.git
    cd task_manager
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Configure Environment Variables:**
    Copy the example environment file and customize it for your local setup.
    ```bash
    cp .env .env.local
    ```
    **Important:** Open `.env.local` and configure your `DATABASE_URL`. If you are using the provided Docker setup, the default values should work.

### 2. Running the Application with Docker

This project is configured to run in Docker containers.

1.  **Build and start the containers:**
    This command will start the PHP-FPM, Nginx, and PostgreSQL services.
    ```bash
    docker-compose up -d --build
    ```

2.  **Run Database Migrations:**
    Execute the migrations inside the PHP container to set up the database schema.
    ```bash
    docker-compose exec php-fpm php bin/console doctrine:migrations:migrate
    ```

3.  **Load Data Fixtures (Optional):**
    To populate the database with sample data (users and tasks), run the fixtures command.
    ```bash
    docker-compose exec php-fpm php bin/console doctrine:fixtures:load
    ```
    A default user will be created with the credentials:
    *   **Email:** `admin@example.com`
    *   **Password:** `password`

The application will be available at `http://localhost:8080`.

## GraphQL API

The API is the primary way to interact with the application.

*   **Endpoint**: `http://localhost:8080/graphql`
*   **GraphiQL Interface**: `http://localhost:8080/graphiql` (An in-browser IDE for exploring and testing the API)

### Authentication

Most API operations require authentication.

1.  **Get a JWT Token:**
    Send a `login` mutation with the user's credentials to receive a token.
    ```graphql
    mutation {
      login(email: "admin@example.com", password: "password") {
        token
        user {
          id
          email
        }
      }
    }
    ```

2.  **Use the Token:**
    To access protected queries or mutations, include the token in the `Authorization` HTTP header. In the GraphiQL interface, you can add this in the "HTTP Headers" panel:
    ```json
    {
      "Authorization": "Bearer <YOUR_JWT_TOKEN>"
    }
    ```

### Example Queries & Mutations

*   **Get all tasks:**
    ```graphql
    query {
      tasks {
        id
        title
        status
        assignedUser {
          id
          name
        }
      }
    }
    ```

*   **Create a new task:**
    ```graphql
    mutation CreateTask($input: CreateTaskInput!) {
      createTask(input: $input) {
        id
        title
        status
      }
    }
    ```
    *Variables:*
    ```json
    {
      "input": {
        "title": "My New Task",
        "description": "Details about the task.",
        "userId": "the-user-id"
      }
    }
    ```

*   **Update a task's status:**
    ```graphql
    mutation {
      updateTaskStatus(id: "the-task-id", newStatus: "IN_PROGRESS") {
        id
        status
      }
    }
    ```

## Console Commands

Several custom commands are available. Run them inside the PHP container.

*   **Sync Users from External API:**
    This command fetches users from a placeholder API and syncs them with the local database.
    ```bash
    docker-compose exec php-fpm php bin/console app:sync-users
    ```
