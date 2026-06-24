# API Reference - PetSupply E-Commerce

## 📍 Base URL
```
http://localhost/PetSupply_eCommerce/backend/api
```

## 🔐 Authentication

All requests (except auth/login and auth/register) require:
```
Authorization: Bearer <JWT_TOKEN>
```

---

## 🔑 Authentication Endpoints (5)

### 1. Register New User
```
POST /auth/register

Request Body:
{
  "username": "john_doe",
  "email": "john@example.com",
  "password": "password123",
  "full_name": "John Doe" (optional)
}

Response (201):
{
  "success": true,
  "token": "jwt_token_here",
  "user": {
    "id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "full_name": "John Doe",
    "role": "user"
  }
}
```

### 2. Login User
```
POST /auth/login

Request Body:
{
  "email": "john@example.com",
  "password": "password123"
}

Response (200):
{
  "success": true,
  "token": "jwt_token_here",
  "user": {
    "id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "full_name": "John Doe",
    "role": "user"
  }
}

Error (401):
{
  "success": false,
  "error": "Invalid email or password"
}
```

### 3. Get User Profile
```
GET /auth/profile

Header:
Authorization: Bearer <token>

Response (200):
{
  "success": true,
  "user": {
    "id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "full_name": "John Doe",
    "phone": "9841234567",
    "address": "123 Main St",
    "city": "Kathmandu",
    "country": "Nepal",
    "postal_code": "44600",
    "role": "user",
    "created_at": "2024-01-01 10:00:00"
  }
}
```

### 4. Update User Profile
```
PUT /auth/profile

Header:
Authorization: Bearer <token>

Request Body:
{
  "full_name": "John Doe Updated",
  "phone": "9841234567",
  "address": "456 Oak Ave",
  "city": "Lalitpur",
  "country": "Nepal",
  "postal_code": "44700"
}

Response (200):
{
  "success": true,
  "message": "Profile updated successfully"
}
```

### 5. Verify Token
```
POST /auth/verify-token

Header:
Authorization: Bearer <token>

Response (200):
{
  "success": true,
  "user": {
    "user_id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "role": "user",
    "exp": 1234567890
  }
}
```

---

## 📦 Product Endpoints (6)

### 1. Get All Products
```
GET /products?page=1&limit=20&category_id=1

Query Parameters:
- page: Page number (default: 1)
- limit: Items per page (default: 20)
- category_id: Filter by category (optional)

Response (200):
{
  "success": true,
  "products": [
    {
      "id": 1,
      "category_id": 1,
      "name": "Premium Dog Kibble - 5kg",
      "description": "High-quality dog food",
      "price": 2500.00,
      "quantity_in_stock": 50,
      "image_url": "https://example.com/image.jpg",
      "sku": "DOG-KIBBLE-5KG",
      "is_active": true,
      "created_at": "2024-01-01 10:00:00"
    }
  ],
  "total": 45
}
```

### 2. Get Single Product
```
GET /products/{id}

Response (200):
{
  "success": true,
  "product": {
    "id": 1,
    "category_id": 1,
    "name": "Premium Dog Kibble - 5kg",
    "description": "High-quality dog food",
    "price": 2500.00,
    "quantity_in_stock": 50,
    "image_url": "https://example.com/image.jpg",
    "sku": "DOG-KIBBLE-5KG",
    "is_active": true,
    "category_name": "Dog Food"
  }
}
```

### 3. Search Products
```
GET /products?search=dog%20food

Response (200):
{
  "success": true,
  "products": [
    { product objects matching search }
  ],
  "total": 5
}
```

### 4. Add Product (Admin Only)
```
POST /products

Header:
Authorization: Bearer <admin_token>

Request Body:
{
  "category_id": 1,
  "name": "New Dog Food",
  "description": "Description here",
  "price": 1500.00,
  "quantity_in_stock": 30,
  "image_url": "https://example.com/image.jpg",
  "sku": "NEW-DOG-FOOD-1"
}

Response (201):
{
  "success": true,
  "product_id": 21,
  "message": "Product added successfully"
}
```

### 5. Update Product (Admin Only)
```
PUT /products/{id}

Header:
Authorization: Bearer <admin_token>

Request Body:
{
  "name": "Updated Name",
  "price": 1800.00,
  "quantity_in_stock": 40
}

Response (200):
{
  "success": true,
  "message": "Product updated successfully"
}
```

### 6. Delete Product (Admin Only)
```
DELETE /products/{id}

Header:
Authorization: Bearer <admin_token>

Response (200):
{
  "success": true,
  "message": "Product deleted successfully"
}
```

---

## 🏷️ Category Endpoints (4)

### 1. Get All Categories
```
GET /categories

Response (200):
{
  "success": true,
  "categories": [
    {
      "id": 1,
      "name": "Dog Food",
      "description": "Nutritious food for dogs",
      "image_url": "https://example.com/image.jpg",
      "is_active": true,
      "created_at": "2024-01-01 10:00:00"
    }
  ]
}
```

### 2. Get Single Category
```
GET /categories/{id}

Response (200):
{
  "success": true,
  "category": {
    "id": 1,
    "name": "Dog Food",
    "description": "Nutritious food for dogs",
    "image_url": "https://example.com/image.jpg",
    "is_active": true
  }
}
```

### 3. Add Category (Admin Only)
```
POST /categories

Header:
Authorization: Bearer <admin_token>

Request Body:
{
  "name": "New Category",
  "description": "Description here",
  "image_url": "https://example.com/image.jpg"
}

Response (201):
{
  "success": true,
  "category_id": 7,
  "message": "Category added successfully"
}
```

### 4. Update & Delete Categories (Admin Only)
```
PUT /categories/{id}
DELETE /categories/{id}
```

---

## 🛒 Cart Endpoints (5)

### 1. Get User Cart
```
GET /cart

Header:
Authorization: Bearer <token>

Response (200):
{
  "success": true,
  "cart": [
    {
      "id": 1,
      "user_id": 1,
      "product_id": 1,
      "quantity": 2,
      "name": "Premium Dog Kibble",
      "price": 2500.00,
      "image_url": "https://example.com/image.jpg"
    }
  ],
  "total": 5000.00,
  "count": 1
}
```

### 2. Add to Cart
```
POST /cart

Header:
Authorization: Bearer <token>

Request Body:
{
  "product_id": 1,
  "quantity": 2
}

Response (200):
{
  "success": true,
  "message": "Item added to cart"
}
```

### 3. Update Cart Item
```
PUT /cart/update

Header:
Authorization: Bearer <token>

Request Body:
{
  "product_id": 1,
  "quantity": 3
}

Response (200):
{
  "success": true,
  "message": "Cart updated"
}
```

### 4. Remove from Cart
```
DELETE /cart/{product_id}

Header:
Authorization: Bearer <token>

Response (200):
{
  "success": true,
  "message": "Item removed from cart"
}
```

### 5. Clear Cart
```
DELETE /cart/clear

Header:
Authorization: Bearer <token>

Response (200):
{
  "success": true,
  "message": "Cart cleared"
}
```

---

## 📋 Order Endpoints (3)

### 1. Get User Orders
```
GET /orders?page=1&limit=10

Header:
Authorization: Bearer <token>

Query Parameters:
- page: Page number (default: 1)
- limit: Items per page (default: 10)

Response (200):
{
  "success": true,
  "orders": [
    {
      "id": 1,
      "user_id": 1,
      "order_number": "ORD-20240101120000-ABC123",
      "total_amount": 5000.00,
      "status": "pending",
      "payment_method": "cod",
      "payment_status": "pending",
      "shipping_address": "123 Main St, Kathmandu 44600",
      "created_at": "2024-01-01 12:00:00"
    }
  ]
}
```

### 2. Get Single Order
```
GET /orders/{id}

Header:
Authorization: Bearer <token>

Response (200):
{
  "success": true,
  "order": {
    "id": 1,
    "user_id": 1,
    "order_number": "ORD-20240101120000-ABC123",
    "total_amount": 5000.00,
    "status": "pending",
    "payment_method": "cod",
    "payment_status": "pending",
    "shipping_address": "123 Main St",
    "email": "john@example.com",
    "full_name": "John Doe",
    "phone": "9841234567",
    "items": [
      {
        "id": 1,
        "order_id": 1,
        "product_id": 1,
        "quantity": 2,
        "unit_price": 2500.00,
        "subtotal": 5000.00,
        "product_name": "Premium Dog Kibble"
      }
    ]
  }
}
```

### 3. Create Order
```
POST /orders

Header:
Authorization: Bearer <token>

Request Body:
{
  "payment_method": "cod",  // or "esewa"
  "shipping_address": "123 Main St, Kathmandu 44600"
}

Response (201):
{
  "success": true,
  "order_id": 5,
  "order_number": "ORD-20240101120000-XYZ789",
  "total_amount": 5000.00,
  "payment_method": "cod",
  "message": "Order created successfully"
}
```

---

## 💳 Payment Endpoints (3)

### 1. Initiate Payment
```
POST /payment/initiate

Header:
Authorization: Bearer <token>

Request Body:
{
  "order_id": 5,
  "amount": 5000.00
}

Response (200):
{
  "success": true,
  "transaction_id": "TXN-1234567890-ABC123",
  "redirect_url": "http://localhost/...esewa-sandbox.php?tx=...",
  "message": "Payment initiated"
}
```

### 2. Payment Callback
```
POST /payment/callback

Request Body:
{
  "transaction_id": "TXN-1234567890-ABC123",
  "status": "success",  // or "failed"
  "order_id": 5,
  "amount": 5000.00
}

Response (200/400):
{
  "success": true/false,
  "message": "Payment successful/failed"
}
```

### 3. Verify Payment
```
POST /payment/verify

Header:
Authorization: Bearer <token>

Request Body:
{
  "transaction_id": "TXN-1234567890-ABC123"
}

Response (200):
{
  "success": true,
  "status": "completed",
  "message": "Payment verified"
}
```

---

## 👥 Admin Endpoints (6+)

### 1. Get All Users
```
GET /admin/users?page=1&limit=10

Header:
Authorization: Bearer <admin_token>

Response (200):
{
  "success": true,
  "users": [
    {
      "id": 1,
      "username": "john_doe",
      "email": "john@example.com",
      "full_name": "John Doe",
      "role": "user",
      "is_active": true,
      "created_at": "2024-01-01 10:00:00"
    }
  ]
}
```

### 2. Delete User
```
DELETE /admin/users/{id}

Header:
Authorization: Bearer <admin_token>

Response (200):
{
  "success": true,
  "message": "User deleted successfully"
}
```

### 3. Get All Orders
```
GET /admin/orders?page=1&limit=10

Header:
Authorization: Bearer <admin_token>

Response (200):
{
  "success": true,
  "orders": [
    {
      "id": 1,
      "user_id": 1,
      "order_number": "ORD-20240101120000-ABC123",
      "total_amount": 5000.00,
      "status": "pending",
      "payment_method": "cod",
      "username": "john_doe",
      "email": "john@example.com",
      "created_at": "2024-01-01 12:00:00"
    }
  ]
}
```

### 4. Update Order Status
```
PUT /admin/orders/{id}

Header:
Authorization: Bearer <admin_token>

Request Body:
{
  "status": "processing"  // pending, processing, shipped, delivered, cancelled
}

Response (200):
{
  "success": true,
  "message": "Order status updated"
}
```

### 5. Get All Products (Admin View)
```
GET /admin/products?page=1&limit=20

Header:
Authorization: Bearer <admin_token>

Response (200):
{
  "success": true,
  "products": [ products array ]
}
```

### 6. Get Dashboard Statistics
```
GET /dashboard

Header:
Authorization: Bearer <admin_token>

Response (200):
{
  "success": true,
  "statistics": {
    "total_orders": 45,
    "total_sales": 125000.00,
    "recent_orders": [
      { order objects }
    ]
  }
}
```

---

## ✅ Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Request successful |
| 201 | Created - Resource created |
| 400 | Bad Request - Invalid input |
| 401 | Unauthorized - Missing/invalid token |
| 403 | Forbidden - Admin access required |
| 404 | Not Found - Resource doesn't exist |
| 405 | Method Not Allowed - Wrong HTTP method |
| 500 | Server Error - Internal error |

---

## 📝 Error Responses

### Validation Error
```json
{
  "error": "Validation failed",
  "errors": {
    "email": "Invalid email format",
    "password": "Password must be at least 6 characters"
  }
}
```

### Authentication Error
```json
{
  "error": "Unauthorized - Missing token"
}
```

### Not Found Error
```json
{
  "error": "Product not found"
}
```

---

## 🔗 Complete API Flow Example

### Customer Purchase Flow
```
1. POST /auth/login
   → Get token

2. GET /categories
   → Browse categories

3. GET /products?category_id=1
   → Browse products

4. GET /products/5
   → View product details

5. POST /cart
   → Add to cart

6. GET /cart
   → View cart

7. POST /orders
   → Create order

8. POST /payment/initiate
   → Start payment

9. GET /orders
   → View order history
```

---

## 🧪 Testing with cURL

### Login Example
```bash
curl -X POST http://localhost/PetSupply_eCommerce/backend/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"admin123"}'
```

### Get Products Example
```bash
curl -X GET "http://localhost/PetSupply_eCommerce/backend/api/products?page=1&limit=20" \
  -H "Content-Type: application/json"
```

### Add to Cart Example
```bash
curl -X POST http://localhost/PetSupply_eCommerce/backend/api/cart \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <your_token>" \
  -d '{"product_id":1,"quantity":2}'
```

---

**API Documentation Complete!** 🎉

For more information, see [README.md](README.md) and [docs/SETUP.md](docs/SETUP.md)
