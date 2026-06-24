# PetSupply E-Commerce - Complete Setup Guide

## 📋 Project Overview

PetSupply is a fully-functional e-commerce platform for pet supplies with the following features:

### User Features
- User authentication (signup & login)
- Browse products by categories
- Shopping cart management
- Checkout with two payment methods:
  - Cash on Delivery (COD)
  - Online Payment (eSewa Sandbox simulation)
- View order history
- User profile management

### Admin Features
- Dashboard with statistics
- User management (view, delete)
- Product management (add, edit, delete)
- Category management
- Order management (view, update status)
- Basic reporting

## 🛠️ Technical Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Server**: Apache with mod_rewrite enabled
- **Authentication**: JWT (JSON Web Tokens)

## 📂 Project Structure

```
PetSupply_eCommerce/
├── frontend/
│   ├── index.html              (Home page)
│   ├── login.html              (Login page)
│   ├── register.html           (Registration page)
│   ├── cart.html               (Shopping cart)
│   ├── checkout.html           (Checkout page)
│   ├── order-confirmation.html (Order confirmation)
│   ├── orders.html             (Order history)
│   ├── profile.html            (User profile)
│   ├── admin-dashboard.html    (Admin panel)
│   ├── checkout-success.html   (Payment success redirect)
│   ├── checkout-failure.html   (Payment failure redirect)
│   ├── styles.css              (Responsive styling)
│   └── app.js                  (API client & utilities)
│
├── backend/
│   ├── index.php               (API entry point)
│   ├── config/
│   │   ├── database.php        (DB connection)
│   │   └── cors.php            (CORS headers)
│   ├── middleware/
│   │   ├── JWTHandler.php      (JWT token handling)
│   │   ├── AuthMiddleware.php  (Authentication)
│   │   └── Validator.php       (Input validation)
│   ├── models/
│   │   ├── User.php            (User operations)
│   │   ├── Product.php         (Product operations)
│   │   ├── Category.php        (Category operations)
│   │   ├── Order.php           (Order operations)
│   │   └── Cart.php            (Cart operations)
│   └── api/
│       ├── router.php          (API routing)
│       ├── auth.php            (Auth endpoints)
│       ├── products.php        (Product endpoints)
│       ├── categories.php      (Category endpoints)
│       ├── cart.php            (Cart endpoints)
│       ├── orders.php          (Order endpoints)
│       ├── payment.php         (Payment endpoints)
│       ├── admin.php           (Admin endpoints)
│       ├── dashboard.php       (Dashboard endpoints)
│       └── esewa-sandbox.php   (Payment gateway simulation)
│
├── database/
│   ├── schema.sql              (Database tables)
│   └── sample_data.sql         (Initial data)
│
└── docs/
    └── SETUP.md                (This file)
```

## 🚀 Installation & Setup

### Prerequisites

1. **Apache Server** with `mod_rewrite` enabled
   - Windows: XAMPP or WampServer
   - Linux: Apache2
   - macOS: MAMP or Homebrew

2. **MySQL** 5.7 or higher

3. **PHP** 7.4 or higher with:
   - PDO extension
   - JSON extension
   - cURL extension

### Step 1: Download & Place Project

1. Download/clone this project
2. Place the `PetSupply_eCommerce` folder in your web server root:
   - **XAMPP**: `C:\xampp\htdocs\`
   - **WampServer**: `C:\wamp64\www\`
   - **Linux Apache**: `/var/www/html/`

### Step 2: Configure Database

#### Option A: Using MySQL Command Line

```bash
# Open MySQL
mysql -u root -p

# Run schema
source /path/to/database/schema.sql

# Run sample data
source /path/to/database/sample_data.sql
```

#### Option B: Using phpMyAdmin

1. Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
2. Create a new database named `petsupply_ecommerce`
3. Go to Import tab
4. Import `database/schema.sql`
5. Import `database/sample_data.sql`

#### Option C: Using MySQL Workbench

1. Connect to your MySQL server
2. File → Run SQL Script
3. Select `database/schema.sql`
4. File → Run SQL Script
5. Select `database/sample_data.sql`

### Step 3: Configure Backend

1. Open `backend/config/database.php`
2. Update database credentials if needed:

```php
define('DB_HOST', 'localhost');    // Your MySQL host
define('DB_USER', 'root');         // Your MySQL username
define('DB_PASSWORD', '');         // Your MySQL password
define('DB_NAME', 'petsupply_ecommerce');
```

3. (Optional) Update JWT secret for production:

```php
define('JWT_SECRET', 'your-super-secret-key-change-this');
```

### Step 4: Configure Frontend

1. Open `frontend/app.js`
2. Verify the API base URL matches your setup:

```javascript
const API_BASE_URL = 'http://localhost/PetSupply_eCommerce/backend/api';
```

### Step 5: Start the Server

#### Using XAMPP
1. Start Apache and MySQL from XAMPP Control Panel
2. Navigate to `http://localhost/PetSupply_eCommerce/frontend/`

#### Using WampServer
1. Click tray icon → Start All Services
2. Navigate to `http://localhost/PetSupply_eCommerce/frontend/`

#### Using Command Line (PHP Built-in)
```bash
cd PetSupply_eCommerce/frontend
php -S localhost:8000
# Visit http://localhost:8000
```

## 👤 Test Credentials

### Admin Account
- **Username**: `admin`
- **Email**: `admin@petsupply.com`
- **Password**: `admin123`

### Regular User Account
- **Username**: `john_doe`
- **Email**: `john@example.com`
- **Password**: `admin123` (sample)

## 📱 Usage Guide

### Customer Flow

1. **Register/Login**
   - Visit `http://localhost/PetSupply_eCommerce/frontend/register.html`
   - Create account or login

2. **Browse Products**
   - View all products on home page
   - Filter by category
   - Search for specific products

3. **Shopping Cart**
   - Click "Add to Cart" on products
   - Manage quantities in cart
   - View cart total

4. **Checkout**
   - Enter shipping details
   - Choose payment method:
     - **COD**: Order marked as "Pending COD"
     - **eSewa**: Redirected to payment gateway (simulation)
   - Confirm order

5. **Order Management**
   - View order history in "My Orders"
   - Track order status
   - View order details

### Admin Flow

1. **Access Dashboard**
   - Login with admin credentials
   - Navigate to `/frontend/admin-dashboard.html`

2. **Manage Users**
   - View all users
   - Delete users (if needed)

3. **Manage Products**
   - Add new products
   - Edit existing products
   - Delete products
   - View product inventory

4. **Manage Categories**
   - Add product categories
   - Edit categories
   - Delete categories

5. **Manage Orders**
   - View all customer orders
   - Update order status
   - Track payments

6. **View Dashboard**
   - Total sales amount
   - Total orders count
   - Recent orders list

## 🔌 API Endpoints

### Authentication
- `POST /auth/register` - Register new user
- `POST /auth/login` - User login
- `GET /auth/profile` - Get user profile
- `PUT /auth/profile` - Update user profile
- `POST /auth/verify-token` - Verify JWT token

### Products
- `GET /products` - Get all products (with pagination)
- `GET /products/{id}` - Get single product
- `GET /products?search=term` - Search products
- `POST /products` - Add product (admin)
- `PUT /products/{id}` - Update product (admin)
- `DELETE /products/{id}` - Delete product (admin)

### Categories
- `GET /categories` - Get all categories
- `GET /categories/{id}` - Get single category
- `POST /categories` - Add category (admin)
- `PUT /categories/{id}` - Update category (admin)
- `DELETE /categories/{id}` - Delete category (admin)

### Cart
- `GET /cart` - Get user's cart
- `POST /cart` - Add item to cart
- `PUT /cart/update` - Update cart item quantity
- `DELETE /cart/{product_id}` - Remove item from cart
- `DELETE /cart/clear` - Clear entire cart

### Orders
- `GET /orders` - Get user's orders
- `GET /orders/{id}` - Get order details
- `POST /orders` - Create new order

### Payments
- `POST /payment/initiate` - Initiate payment
- `POST /payment/callback` - Payment callback
- `POST /payment/verify` - Verify payment

### Admin
- `GET /admin/users` - Get all users
- `DELETE /admin/users/{id}` - Delete user
- `GET /admin/orders` - Get all orders
- `PUT /admin/orders/{id}` - Update order status
- `GET /admin/products` - Get all products

### Dashboard
- `GET /dashboard` - Get dashboard statistics

## 💳 Payment Gateway (eSewa) Integration

The system includes a **eSewa Sandbox simulation** for testing online payments:

1. During checkout, select "Online Payment (eSewa)"
2. You'll be redirected to the simulated payment gateway
3. The gateway automatically processes as success
4. Order is confirmed and payment status updated

### To Test Payment Failure (optional)
- Modify the eSewa sandbox to return failure status
- Edit `backend/api/esewa-sandbox.php`

```php
// Change the redirect to failure:
header('Location: ' . ESEWA_FAILURE_URL . '?tx=' . $transaction_id . '&status=failed');
```

## 🔐 Security Features

1. **Password Hashing**: BCrypt algorithm
2. **JWT Authentication**: Secure token-based auth
3. **CORS Protection**: Configured headers
4. **Input Validation**: Server-side validation
5. **SQL Injection Prevention**: PDO prepared statements
6. **Admin Role-Based Access**: Admin-only endpoints

## 🐛 Troubleshooting

### Issue: "Connection refused" when accessing API

**Solution**:
- Check if Apache is running
- Verify MySQL is running
- Check `backend/config/database.php` credentials
- Make sure port 3306 (MySQL default) is not blocked

### Issue: Blank page or 404 error

**Solution**:
- Verify Apache `mod_rewrite` is enabled
- Check file permissions (755 for folders, 644 for files)
- Ensure project is in correct web root directory
- Check browser console for JavaScript errors (F12)

### Issue: "CORS error" when making API calls

**Solution**:
- Verify `backend/config/cors.php` is loaded
- Check API URL in `frontend/app.js` matches your setup
- Ensure backend `index.php` is properly routing requests

### Issue: Products not showing

**Solution**:
- Verify database is created and populated
- Check if `sample_data.sql` was executed
- Query database: `SELECT * FROM products;`
- Check browser console for API errors

### Issue: Payments not working

**Solution**:
- Verify eSewa URLs in `backend/config/database.php`
- Check `esewa-sandbox.php` is accessible
- Ensure checkout process completes without errors
- Verify payment status updates in database

## 📊 Database Schema

### users
- id, username, email, password_hash, full_name, phone, address, city, country, postal_code, role, is_active

### categories
- id, name, description, image_url, is_active

### products
- id, category_id, name, description, price, quantity_in_stock, image_url, sku, is_active

### orders
- id, user_id, order_number, total_amount, status, payment_method, payment_status, shipping_address, delivery_notes

### order_items
- id, order_id, product_id, quantity, unit_price, subtotal

### cart
- id, user_id, product_id, quantity, added_at

### payments
- id, order_id, payment_gateway, transaction_id, amount, status, response_data

## 🔄 Workflow Example

1. **User Registration**
   ```
   User → Register Form → API → DB → JWT Token → Local Storage
   ```

2. **Product Purchase**
   ```
   Browse → Add to Cart → View Cart → Checkout → Create Order → Payment → Confirmation
   ```

3. **Admin Management**
   ```
   Admin Login → Dashboard → Manage Products/Orders/Users → Update DB
   ```

## 🎨 Features Implemented

✅ User registration and authentication
✅ Product browsing and filtering by categories
✅ Shopping cart functionality
✅ Order creation and management
✅ Two payment methods (COD & eSewa)
✅ Admin dashboard
✅ User and product management
✅ Order tracking
✅ Responsive design (mobile & desktop)
✅ JWT token-based security
✅ Database-backed persistence
✅ Search functionality
✅ Order history
✅ Admin statistics and reporting

## 📝 Sample Use Cases

### Customer Buys Pet Food
1. Customer registers and logs in
2. Searches for "dog food"
3. Selects Premium Dog Kibble
4. Adds to cart (quantity: 2)
5. Proceeds to checkout
6. Enters shipping address
7. Chooses "Cash on Delivery"
8. Places order
9. Order confirmation shows order number
10. Customer receives order in history

### Admin Updates Order Status
1. Admin logs in to dashboard
2. Goes to "Orders" tab
3. Selects an order
4. Updates status to "Shipped"
5. System reflects status change
6. Order now shows as "Shipped"

## 🤝 Contributing & Customization

To customize this system:

1. **Add new payment gateways**: Edit `backend/api/payment.php`
2. **Add new product fields**: Modify `database/schema.sql` and models
3. **Customize UI**: Edit `frontend/styles.css`
4. **Add new admin features**: Create new API endpoints
5. **Change database**: Update `backend/config/database.php`

## 📞 Support

For issues or questions:
1. Check this documentation first
2. Review the troubleshooting section
3. Check browser console for error messages (F12)
4. Verify all database tables are created
5. Ensure all files have correct permissions

## 📄 License

This project is open-source and can be used for educational purposes.

---

## ✨ Happy Shopping & Managing! 🐾

Thank you for using PetSupply E-Commerce!
