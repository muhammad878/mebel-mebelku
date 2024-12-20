# Mebel Mebelku - Furniture Website

A web application for a furniture store built with PHP and MongoDB.

## Prerequisites

Before running this application, make sure you have the following installed:
- PHP 8.0 or higher
- MongoDB
- MongoDB PHP Extension
- Composer
- Web Server (Apache/Nginx/etc.)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/muhammad878/mebel-mebelku.git
cd mebel-mebelku
```

2. Install dependencies:
```bash
composer install
```

3. Configure MongoDB:
- Make sure MongoDB service is running
- Update the MongoDB connection settings in `db.php` if needed

## Running the Application

1. Start your web server (e.g., Apache, Nginx, or PHP's built-in server)

2. If using PHP's built-in server:
```bash
php -S localhost:8000
```

3. Access the application in your web browser:
```
http://localhost:8000
```

## Features

- User Registration and Login
- Admin Dashboard
- Product Catalog
- Order Management
- Responsive Design

## File Structure

- `index.php` - Main entry point
- `db.php` - Database configuration
- `login.php` - User login
- `loginad.php` - Admin login
- `halaman_admin.php` - Admin dashboard
- `registrasi.php` - User registration
- `regisadmin.php` - Admin registration

## Dependencies

- MongoDB PHP Library
- Bootstrap 5.3.2
- Font Awesome 5.15.3

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is open-sourced software. 