
# Project Setup and Run Guide

This project is built using Laravel and depends on Docker, Docker Compose. Follow the steps below to set up and run the project.

## Prerequisites

- Docker
- Docker Compose

## Setup Instructions

1. **Clone the repository:**
   ```sh
   git clone https://github.com/Bugzee321/timesheet-system.git
   cd timesheet-system
   ```

2. **Copy the example environment file and configure it:**
   ```sh
   cp .env.example .env
   ```
5. **Run Docker containers:**
   ```sh
   docker-compose up -d
   ```

3. **Install PHP dependencies:**
   ```sh
   docker-compose exec app composer install
   ```

4. **Generate application key**
   ```sh
   docker-compose exec app php artisan key:generate
   ```


6. **Run database migrations:**
   ```sh
   docker-compose exec app php artisan migrate
   ```

9. **Generate Swagger documentation:**
   ```sh
   docker-compose exec app php artisan l5-swagger:generate
   ```

10. **Run database migrations for testing environment:**
    ```sh
    docker-compose exec app php artisan migrate --env=testing
    ```

11. **Seed the database for testing environment:**
    ```sh
    docker-compose exec app php artisan db:seed --env=testing
    ```

## Running Tests

To run the tests, use the following commands:

1. **Run tests for testing environment**
   ```sh
   docker-compose exec app php artisan test
   ```

## API Documentation

The API documentation is available at:

http://localhost/api/documentation
