"EcoCraft." :
Project Overview:

Repository: Shandyshulton/WebECommerce-EcoCraft
Framework: Laravel 9.x (PHP web framework)
Project Type: E-commerce platform with multi-user roles

Key Features & Architecture:
The application implements a three-tier user system:

Admins - Manage and verify sellers/products
Sellers - Create and manage their products/stores
Customers - Browse and purchase products

Core Functionality:

User Authentication: Separate login systems for each user type
Product Management: CRUD operations with image galleries
Approval Workflow: Admin verification required for sellers and products
Profile Management: Each user type can update their profiles
Shopping Features: Cart, checkout, order tracking

Technical Structure:

Models: Admin, Customer, Seller, Product, ProductImage
Controllers: Separate controllers for each user role and functionality
Middleware: Role-based access control
Database: MySQL with proper migrations and relationships
File Storage: Image handling for profiles, products, and KTP verification

Notable Features:

KTP (Indonesian ID card) image upload for seller verification
Multi-guard authentication system
Status-based approval workflow (pending/approved/rejected)
Image gallery support for products
Provincial/city location tracking

To install this Laravel EcoCraft e-commerce application, you'll need to follow these steps:

## Prerequisites
- PHP 8.0 or higher
- Composer (PHP dependency manager)
- MySQL/MariaDB database
- Node.js and npm (for frontend assets)
- Web server (Apache/Nginx) or use Laravel's built-in server

## Installation Steps

### 1. Clone/Download the Repository
```bash
git clone https://github.com/Shandyshulton/WebECommerce-EcoCraft.git
cd WebECommerce-EcoCraft
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node Dependencies
```bash
npm install
```

### 4. Environment Configuration
```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Configure Database
Edit the `.env` file and update database settings:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecocraft_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Create Database
Create a MySQL database named `ecocraft_db` (or whatever you specified in .env)

### 7. Run Migrations
```bash
php artisan migrate
```

### 8. Create Storage Link
```bash
php artisan storage:link
```

### 9. Compile Frontend Assets
```bash
npm run dev
# or for production
npm run production
```

### 10. Seed Database (Optional)
You may need to create seeders or manually add an admin user to start using the system.

### 11. Start the Application
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Important Notes

**Potential Issues:**
- The migration file for products appears to be truncated in the provided code
- No database seeders are visible, so you'll need to manually create initial admin users
- The application uses Indonesian-specific features (KTP validation) that may need adjustment for other regions

**Missing Setup Requirements:**
- You'll likely need to create an initial admin user directly in the database
- Configure mail settings if you plan to use email features
- Set up proper file permissions for the storage directory

**Security Considerations:**
- Change default passwords
- Review and update validation rules as needed
- Ensure proper file upload restrictions are in place

The installation process is standard for Laravel applications, but you may encounter issues with the incomplete migration files or missing seeders that would need to be addressed based on the full repository content.
