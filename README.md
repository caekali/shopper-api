# EasyShop API – Laravel Backend

![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4.svg?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1.svg?style=flat&logo=mysql)
![License](https://img.shields.io/badge/License-MIT-yellow.svg)
![API](https://img.shields.io/badge/API-RESTful-blue.svg)

A robust, scalable **RESTful API** built with Laravel for the EasyShop Android e-commerce application.

Features JWT authentication, product management, order processing, and more.

## ✨ Tech Stack

| Technology           | Version       | Purpose                          |
|---------------------|---------------|----------------------------------|
| Laravel             | 12.x          | Backend Framework                |
| PHP                 | 8.2+          | Programming Language             |
| MySQL               | 8.0+          | Database                         |
| Laravel Sanctum     | (Alternative) | API Token Authentication         |


## 📋 Features

### Authentication & Authorization
- ✅ JWT-based authentication
- ✅ User registration & login
- ✅ Password reset via email
- ✅ Role-based access control (Admin/Customer)
- ✅ Token refresh & invalidation

### Product Management
- ✅ CRUD operations for products
- ✅ Product categories & tags
- ✅ Image upload & management
- ✅ Product search & filtering
- ✅ Pagination support

### Order Management
- ✅ Shopping cart operations
- ✅ Order creation & tracking
- ✅ Order status updates
- ✅ Order history
- ✅ Integrated with paychangu

### User Management
- ✅ Profile management
- ✅ Address management
- ✅ Wishlist functionality

## 🏗️ Project Structure

```
easyshop-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── ProductController.php
│   │   │   ├── OrderController.php
│   │   │   └── CartController.php
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   └── Cart.php
│   ├── Repositories/
│   └── Services/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── routes/
│   ├── api.php
│   └── web.php
├── tests/
└── .env.example
```

## 📚 API Documentation

### Base URL
```
https://api.easyshop.mw/api/v1
```

### Authentication Endpoints

| Method | Endpoint              | Description           |
|--------|----------------------|----------------------|
| POST   | /auth/register       | Register new user    |
| POST   | /auth/login          | Login user           |
| POST   | /auth/logout         | Logout user          |
| POST   | /auth/refresh        | Refresh JWT token    |
| GET    | /auth/me             | Get user profile     |

### Product Endpoints

| Method | Endpoint              | Description           | Auth Required |
|--------|----------------------|----------------------|---------------|
| GET    | /products            | List all products    | No            |
| GET    | /products/{id}       | Get product details  | No            |
| POST   | /products            | Create product       | Yes (Admin)   |
| PUT    | /products/{id}       | Update product       | Yes (Admin)   |
| DELETE | /products/{id}       | Delete product       | Yes (Admin)   |

### Cart Endpoints

| Method | Endpoint              | Description           | Auth Required |
|--------|----------------------|----------------------|---------------|
| GET    | /cart                | Get user cart        | Yes           |
| POST   | /cart/add            | Add item to cart     | Yes           |
| PUT    | /cart/update/{id}    | Update cart item     | Yes           |
| DELETE | /cart/remove/{id}    | Remove from cart     | Yes           |

### Order Endpoints

| Method | Endpoint              | Description           | Auth Required |
|--------|----------------------|----------------------|---------------|
| GET    | /orders              | List user orders     | Yes           |
| GET    | /orders/{id}         | Get order details    | Yes           |
| POST   | /orders              | Create new order     | Yes           |
| PUT    | /orders/{id}/status  | Update order status  | Yes (Admin)   |

## 🚀 Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (for frontend assets if needed)

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/caekali/easyshop-api.git
cd easyshop-api
```

2. **Install dependencies**
```bash
composer install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

4. **Configure database**
Update your `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=easyshop-api
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Run migrations & seeders**
```bash
php artisan migrate --seed
```

6. **Start development server**
```bash
php artisan serve
```

API will be available at: `http://localhost:8000`

### Testing
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 🔧 Configuration

### CORS Configuration
Edit `config/cors.php` to allow your Android app:
```php
'allowed_origins' => ['*'], // Or specific origins
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

## 📦 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Set up SSL certificate (HTTPS)
- [ ] Configure queue worker for background jobs
- [ ] Set up Redis for caching
- [ ] Configure email service (Mailgun, SES, etc.)
- [ ] Run `php artisan optimize`
- [ ] Set up scheduled tasks in cron

### Example Deployment (Ubuntu/Nginx)
```bash
# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## 🧪 Testing with Postman

Import the API collection:
1. Download [EasyShop.postman_collection.json](docs/postman/collection.json)
2. Import into Postman
3. Set environment variables:
   - `base_url`: Your API URL

## 📊 Database Schema

```sql
users
- id, name, email, password, role, created_at, updated_at

products
- id, name, description, price, stock, category_id, image, created_at, updated_at

categories
- id, name, slug, created_at, updated_at

orders
- id, user_id, total, status, created_at, updated_at

order_items
- id, order_id, product_id, quantity, price, created_at, updated_at

carts
- id, user_id, product_id, quantity, created_at, updated_at
```

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 🔒 Security

If you discover any security issues, please email ckalikunde@gmail.com instead of using the issue tracker.

## 📄 License

```
MIT License - free for commercial and personal use
```

## 🚀 Roadmap
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Advanced product filtering
- [ ] Product reviews & ratings
- [ ] Admin dashboard
- [ ] Analytics & reporting
- [ ] Multi-language support


**Related Projects:**
- [EasyShop Android App](https://github.com/caekali/Easy-Shop) - The mobile client for this API
