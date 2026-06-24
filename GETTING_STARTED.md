# 🐾 PetSupply E-Commerce - Quick Start Guide

## Current Status
Pages are loading but the **backend API** is not responding. Follow these steps to fix it:

---

## Step 1: Initialize the Database ⚡ (CRITICAL)

**Open this URL in your browser:**
```
http://localhost/PetSupply_eCommerce/setup-db.php
```

Click **"Initialize Database"** button. You should see:
```
✓ Database setup complete!
```

If you see errors, check the diagnostic tool (Step 2).

---

## Step 2: Verify System Status 🔍

**Open this URL to check all components:**
```
http://localhost/PetSupply_eCommerce/diagnostic.php
```

This will show:
- ✓ PHP version & extensions
- ✓ Database connection status
- ✓ Required tables
- ✓ Data counts

**Look for:**
- `Database: SUCCESS`
- All required tables exist (users, products, categories, orders, cart)
- `API_ENDPOINTS: RESPONDING`

---

## Step 3: Load Sample Data 📦 (Optional)

Go back to setup-db.php and click **"Load Sample Data"** to populate:
- 10+ pet product categories
- 50+ pet products
- Sample admin/user accounts

---

## Step 4: Test the Application 🚀

Open these URLs:

1. **Main Store** → `http://localhost/PetSupply_eCommerce/frontend/index.html`
   - Should show categories and products
   
2. **Register** → `http://localhost/PetSupply_eCommerce/frontend/register.html`
   - Should be able to create account
   
3. **Login** → `http://localhost/PetSupply_eCommerce/frontend/login.html`
   - Should be able to login with created account

---

## Troubleshooting

### Still seeing errors?

**Check browser console (F12):**
1. Open developer tools (F12)
2. Go to **Console** tab
3. Look for red error messages
4. Check **Network** tab for failed API calls

### Common Issues:

| Error | Solution |
|-------|----------|
| "Database connection failed" | MySQL not running - Start XAMPP MySQL |
| "Tables missing" | Run setup-db.php again |
| "API not responding" | Check backend/api/router.php exists |
| "Network error" | Clear browser cache (Ctrl+Shift+Del) |

### Manual Database Setup:

If setup-db.php doesn't work, use MySQL directly:

```bash
# Open MySQL command line
mysql -u root -p

# Run in MySQL:
source C:\xampp\htdocs\PetSupply_eCommerce\database\schema.sql
source C:\xampp\htdocs\PetSupply_eCommerce\database\sample_data.sql
```

---

## Project Structure

```
PetSupply_eCommerce/
├── frontend/           # User interface (HTML/CSS/JS)
│   ├── index.html     # Main store page
│   ├── login.html     # Login page
│   ├── register.html  # Registration page
│   └── app.js         # API client & logic
├── backend/           # PHP API
│   ├── api/           # API endpoints
│   ├── models/        # Database models
│   ├── config/        # Configuration
│   └── middleware/    # Auth, validation
├── database/          # SQL schemas
│   ├── schema.sql     # Database structure
│   └── sample_data.sql # Test data
└── setup-db.php       # Database setup tool
```

---

## Default Admin Account (after loading sample data)

- **Email:** admin@example.com
- **Password:** admin123

---

## API Endpoints

All endpoints are at: `http://localhost/PetSupply_eCommerce/backend/api/`

### Public:
- `GET /categories` - List all categories
- `GET /products` - List products
- `POST /auth/register` - Create account
- `POST /auth/login` - Login

### Protected (requires token):
- `GET /cart` - Get cart
- `POST /orders` - Create order
- `GET /orders` - List user's orders

---

**Questions?** Check:
1. Browser console for error messages
2. diagnostic.php for system status
3. README.md in project root
