# PetSupply E-Commerce - Quick Reference

## 🚀 Getting Started in 5 Minutes

### Step 1: Place Project
```
Copy PetSupply_eCommerce folder to:
- XAMPP: C:\xampp\htdocs\
- WampServer: C:\wamp64\www\
- Linux: /var/www/html/
```

### Step 2: Import Database
```sql
-- Using MySQL command line or phpMyAdmin
source database/schema.sql
source database/sample_data.sql
```

### Step 3: Start Server
```bash
# XAMPP: Start Apache & MySQL from control panel
# WampServer: Click tray icon → Start All Services
# Command line: cd frontend && php -S localhost:8000
```

### Step 4: Access Application
```
Frontend: http://localhost/PetSupply_eCommerce/frontend/
API: http://localhost/PetSupply_eCommerce/backend/api
Admin: Login then go to admin-dashboard.html
```

## 📋 Test Credentials

### Admin Login
```
Email: admin@petsupply.com
Password: admin123
Role: Admin
Access: Full admin dashboard
```

### User Login
```
Email: john@example.com
Password: admin123
Role: Customer
```

## 🗂️ File Structure Quick Reference

```
PetSupply_eCommerce/
├── README.md               ← Start here
├── frontend/
│   ├── index.html          ← Home page (start here)
│   ├── login.html
│   ├── register.html
│   ├── cart.html
│   ├── checkout.html
│   ├── orders.html
│   ├── profile.html
│   ├── admin-dashboard.html ← Admin panel
│   ├── styles.css
│   └── app.js
├── backend/
│   ├── index.php
│   ├── config/database.php  ← Edit DB credentials here
│   ├── api/router.php
│   └── ...
├── database/
│   ├── schema.sql          ← Import this first
│   └── sample_data.sql     ← Then import this
└── docs/
    └── SETUP.md            ← Detailed guide
```

## 🔑 Key Features Checklist

### User Features
- [x] Registration & Login
- [x] Browse & search products
- [x] Filter by category
- [x] Shopping cart
- [x] Checkout (COD & eSewa)
- [x] Order history
- [x] Profile management
- [x] Mobile responsive

### Admin Features
- [x] Dashboard with stats
- [x] Manage users
- [x] Manage products
- [x] Manage categories
- [x] Manage orders
- [x] Update order status
- [x] View sales reports

## 🔧 Configuration Quick Links

| File | Purpose |
|------|---------|
| `backend/config/database.php` | Database connection |
| `backend/config/cors.php` | CORS settings |
| `frontend/app.js` | API base URL |
| `backend/middleware/JWTHandler.php` | JWT secret |

## 📍 URLs to Remember

| Page | URL |
|------|-----|
| Home | `http://localhost/PetSupply_eCommerce/frontend/` |
| Login | `.../frontend/login.html` |
| Register | `.../frontend/register.html` |
| Cart | `.../frontend/cart.html` |
| Checkout | `.../frontend/checkout.html` |
| Orders | `.../frontend/orders.html` |
| Profile | `.../frontend/profile.html` |
| Admin | `.../frontend/admin-dashboard.html` |
| API | `http://localhost/PetSupply_eCommerce/backend/api` |

## 🔌 API Endpoints Quick Reference

### Auth
```
POST   /auth/register
POST   /auth/login
GET    /auth/profile
PUT    /auth/profile
```

### Products
```
GET    /products?page=1&limit=20
GET    /products/{id}
GET    /products?search=term
POST   /products (admin)
PUT    /products/{id} (admin)
DELETE /products/{id} (admin)
```

### Cart
```
GET    /cart
POST   /cart
PUT    /cart/update
DELETE /cart/{id}
DELETE /cart/clear
```

### Orders
```
GET    /orders
GET    /orders/{id}
POST   /orders
```

### Admin
```
GET    /admin/users
DELETE /admin/users/{id}
GET    /admin/orders
PUT    /admin/orders/{id}
GET    /dashboard
```

## 🐛 Quick Troubleshooting

### Can't access website?
- [ ] Apache running?
- [ ] MySQL running?
- [ ] Files in correct folder?
- [ ] Port not blocked?

### Database errors?
- [ ] Database created?
- [ ] Schema imported?
- [ ] Credentials correct in `database.php`?
- [ ] MySQL running?

### API not working?
- [ ] Check browser console (F12)
- [ ] API URL correct in `app.js`?
- [ ] Backend files exist?
- [ ] CORS enabled?

### Login not working?
- [ ] Check credentials in database
- [ ] User exists in `users` table?
- [ ] Browser storage enabled?
- [ ] Check console for errors

## 💡 Common Tasks

### Change Database Credentials
```php
// File: backend/config/database.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'petsupply_ecommerce');
```

### Change API Base URL
```javascript
// File: frontend/app.js
const API_BASE_URL = 'http://your-domain/PetSupply_eCommerce/backend/api';
```

### Change JWT Secret
```php
// File: backend/config/database.php
define('JWT_SECRET', 'your-secret-key-here');
```

### Add New Product (Admin)
1. Login as admin
2. Go to Admin Dashboard
3. Click "Products" tab
4. Click "Add New Product"
5. Fill form and submit

### Update Order Status (Admin)
1. Go to Admin Dashboard
2. Click "Orders" tab
3. Find order
4. Click "Update"
5. Change status
6. Save

## 🌐 Browser Compatibility

- Chrome ✓ (v90+)
- Firefox ✓ (v88+)
- Safari ✓ (v14+)
- Edge ✓ (v90+)
- Mobile browsers ✓

## 📱 Responsive Breakpoints

```css
Mobile: < 480px
Tablet: 481px - 768px
Desktop: > 768px
```

## 🔐 Default JWT Settings

```php
Expiration: 24 hours (86400 seconds)
Algorithm: HS256
Location: Authorization header (Bearer token)
```

## 📊 Sample Product Categories

1. Dog Food
2. Cat Food
3. Dog Toys
4. Cat Toys
5. Grooming
6. Accessories

Each has sample products for testing.

## ✅ Pre-Launch Checklist

- [ ] Database imported successfully
- [ ] All files in correct location
- [ ] Database credentials configured
- [ ] Can access frontend
- [ ] Can login with test credentials
- [ ] Can add products to cart
- [ ] Can checkout (COD)
- [ ] Can access admin dashboard
- [ ] Can add new product (admin)
- [ ] Error console is clean

## 🎯 Next Steps

1. Read `docs/SETUP.md` for detailed guide
2. Set up database
3. Configure backend
4. Test with provided credentials
5. Explore admin dashboard
6. Customize as needed
7. Deploy when ready

## 📞 Need Help?

1. Check `docs/SETUP.md` section "Troubleshooting"
2. Review browser console (F12) for errors
3. Check MySQL error logs
4. Verify all files are in place
5. Double-check database credentials

---

**You're ready to go! Happy shopping! 🐾**

Start with: `http://localhost/PetSupply_eCommerce/frontend/`
