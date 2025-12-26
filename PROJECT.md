# Project Guide - Products API

## Description

This project is a RESTful API developed in Laravel 10 for product management, with authentication via Laravel Sanctum. The project was developed as part of a fullstack challenge.

## Technologies Used

### Backend
- **Laravel 10**: Modern and robust PHP framework for web development
- **Laravel Sanctum**: API token-based authentication system
- **PHP 8.1+**: Programming language
- **MySQL**: Relational database
- **PHPUnit**: Unit testing framework

### Why these technologies?

- **Laravel**: Chosen for being the most popular and mature PHP framework, offering a robust structure, Eloquent ORM, intuitive routing system, and excellent documentation.
- **Laravel Sanctum**: Native Laravel solution for API authentication, lightweight and efficient, ideal for SPAs and mobile applications.
- **PHPUnit**: Standard framework for PHP testing, fully integrated with Laravel.

## Project Structure

```
challenge-fullstack/
├── app/                                # Laravel application
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php      # Authentication controller
│   │   │   └── ProductController.php   # Products controller
│   │   └── Middleware/                 # Custom middlewares
│   └── Models/
│       ├── Product.php                 # Product model
│       └── User.php                    # User model
├── database/
│   ├── factories/
│   │   ├── ProductFactory.php         # Factory for products
│   │   └── UserFactory.php            # Factory for users
│   ├── migrations/                     # Database migrations
│   └── seeders/
│       ├── DatabaseSeeder.php         # Main seeder
│       └── ProductSeeder.php         # Products seeder
├── frontend/                           # ReactJS SPA
│   ├── src/
│   │   ├── components/                # React components
│   │   ├── contexts/                   # React contexts
│   │   ├── pages/                      # Page components
│   │   └── services/                   # API services
│   ├── package.json
│   └── vite.config.js
├── routes/
│   ├── api.php                        # API routes
│   └── web.php                        # Web routes
├── tests/
│   └── Feature/
│       └── ProductControllerTest.php  # Controller tests
└── config/                            # Configuration files
```

## How to Run the Project

### Prerequisites

- PHP 8.1 or higher
- **Composer** (required - see installation instructions if not installed)
- MySQL 5.7+ or MariaDB 10.3+
- Required PHP extensions: PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, pdo_mysql

> **IMPORTANT:** If you get an error about `vendor/autoload.php` not found, it means Composer is not installed. See installation instructions.

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd challenge-fullstack
   ```

2. **Install Composer (if you don't have it)**
   
   - **Windows:** Download and run the installer: https://getcomposer.org/download/
   - **Linux/Mac:** Run: `curl -sS https://getcomposer.org/installer | php`
   - Verify installation: `composer --version`

3. **Install project dependencies**
   ```bash
   composer install
   ```
   
   > If Composer is not in PATH, you can use: `php composer.phar install`

4. **Configure the environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure the database**
   
   Edit the `.env` file and configure database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=challenge_products
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Create the database**
   
   In MySQL, create the database:
   ```sql
   CREATE DATABASE challenge_products;
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

   The API will be available at: `http://localhost:8000`

## API Endpoints

### Authentication

#### Register User
```
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:**
```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "access_token": "1|token...",
    "token_type": "Bearer"
}
```

#### Login
```
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "access_token": "1|token...",
    "token_type": "Bearer"
}
```

#### Logout
```
POST /api/logout
Authorization: Bearer {token}
```

### Products

#### List All Products (Public)
```
GET /api/products
```

**Response:**
```json
[
    {
        "id": 1,
        "name": "Playstation 5",
        "description": "Play PS5 and PS4 games on Blu-ray Disc...",
        "price": 3550,
        "quantity": 100,
        "active": true
    }
]
```

#### View Product (Public)
```
GET /api/products/{id}
```

#### Create Product (Authenticated)
```
POST /api/products
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "New Product",
    "description": "Product description",
    "price": 99.99,
    "quantity": 50,
    "active": true
}
```

**Validations:**
- `name`: required, string, max 255 characters
- `description`: optional, string
- `price`: required, numeric, greater than 0
- `quantity`: required, integer, minimum 0
- `active`: optional, boolean (default: true)

#### Update Product (Authenticated)
```
PUT /api/products/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Product",
    "price": 199.99,
    "quantity": 75
}
```

#### Delete Product (Authenticated)
```
DELETE /api/products/{id}
Authorization: Bearer {token}
```

## Tests

Run unit tests with:

```bash
php artisan test
```

or

```bash
./vendor/bin/phpunit
```

Tests cover:
- Product listing (public)
- Product viewing (public)
- Product creation (authenticated)
- Product update (authenticated)
- Product deletion (authenticated)
- Data validations
- Error handling (404, 401, 422)

## Implemented Features

### First Stage - API (Backend)

1. **Initial Configuration**
   - Laravel project configured
   - .env.example file created
   - Application key configurable

2. **Model and Migration**
   - `Product` model created
   - Migration with fields: id, name, description, price, quantity, active
   - `products` table created

3. **Seeders and Factories**
   - Factory for `Product` created
   - `ProductSeeder` created
   - Fictional data including PlayStation 5 example

4. **Routes and Controllers**
   - RESTful routes implemented
   - `ProductController` with methods: index, show, store, update, destroy
   - Validations implemented:
     - name: required
     - price: required, greater than 0
     - quantity: required, integer

5. **Authentication and Authorization**
   - Authentication via Laravel Sanctum
   - Protected routes for creation, update, and deletion
   - Public routes for listing and viewing

6. **Unit Tests**
   - Tests for `ProductController`
   - Success and failure tests
   - Validation and authentication coverage

## JSON Response Structure

All API responses follow the format:

```json
{
    "id": 1,
    "name": "Playstation 5",
    "description": "Play PS5 and PS4 games on Blu-ray Disc...",
    "price": 3550,
    "quantity": 100,
    "active": true
}
```

## Security

- Authentication via tokens (Laravel Sanctum)
- Input data validation
- CSRF protection for web routes
- Passwords hashed with bcrypt
- Authentication middleware on protected routes

## Frontend (ReactJS SPA)

The frontend is located in the `frontend/` directory and is a complete ReactJS SPA that consumes the API.

### Frontend Installation

```bash
cd frontend
npm install
```

### Running the Frontend

```bash
npm run dev
```

The frontend will be available at `http://localhost:3000`

### Frontend Features

- User authentication (Login page)
- Products listing page with actions column
- Product details page
- Product creation page
- Product editing page
- Product deletion functionality
- Protected routes (authentication required)
- Responsive design

### Frontend Technologies

- **React 18**: UI library
- **React Router DOM 6**: Client-side routing
- **Axios**: HTTP client for API requests
- **Vite**: Build tool and development server

See `frontend/README.md` for more details.

### Login Credentials

After running the seeders, you can use the following credentials to login:

**Option 1: Admin User**
- Email: `admin@test.com`
- Password: `password123`

**Option 2: Test User**
- Email: `test@test.com`
- Password: `password123`

You can also create new users through the registration page at `/register` or via the API endpoint `/api/register`.

## Author

Developed as part of the fullstack challenge.

## License

MIT

