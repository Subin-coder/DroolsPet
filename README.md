# 🐾 PetSupply E-Commerce Platform

A **fully-functional, production-ready e-commerce website** for pet supplies with complete user and admin features, secure authentication, and multiple payment options.

## ✨ Features

### 👥 User Features
- ✅ User registration & login with JWT authentication
- ✅ Browse products with category filtering
- ✅ Advanced product search functionality
- ✅ Shopping cart management
- ✅ Two checkout options:
  - Cash on Delivery (COD)
  - Online Payment (eSewa integration)
- ✅ Order history and tracking
- ✅ User profile management
- ✅ Responsive mobile-friendly design

### 🛠️ Admin Features
- ✅ Comprehensive admin dashboard
- ✅ User management (view, delete)
- ✅ Product management (CRUD operations)
- ✅ Category management
- ✅ Order management with status updates
- ✅ Sales reporting and statistics
- ✅ Inventory tracking

### 🔒 Security & Technical
- ✅ Secure password hashing (BCrypt)
- ✅ JWT token-based authentication
- ✅ RESTful API architecture
- ✅ CORS protection
- ✅ Input validation & sanitization
- ✅ SQL injection prevention (PDO prepared statements)
- ✅ Role-based access control

## 🏗️ Tech Stack

| Layer | Technology |
|-------|-----------|
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Backend** | PHP 7.4+ |
| **Database** | MySQL 5.7+ |
| **Server** | Apache with mod_rewrite |
| **Authentication** | JWT (JSON Web Tokens) |
| **API** | RESTful API |

## 📦 Deliverables

✅ **Complete source code** with proper folder structure
✅ **Database schema** with all tables and relationships
✅ **Sample data** for testing (products, users, categories)
✅ **API documentation** with all endpoints
✅ **Setup instructions** for local deployment
✅ **Test credentials** for admin and regular users
✅ **Responsive design** for desktop and mobile
✅ **Clean, modular code** with detailed comments

## 🚀 Quick Start

### Prerequisites
- PHP 7.4+
- MySQL 5.7+
- Apache with mod_rewrite
- (Optional) XAMPP, WampServer, or MAMP for easy setup

### Installation (5 minutes)

1. **Place project in web root**
   ```bash
   # XAMPP: C:\xampp\htdocs\
   # WampServer: C:\wamp64\www\
   # Linux: /var/www/html/
   ```

2. **Create database and import schema**
   ```bash
   mysql -u root -p < database/schema.sql
   mysql -u root -p < database/sample_data.sql
   ```

3. **Configure database** (if needed)
   - Edit `backend/config/database.php`
   - Update DB credentials

4. **Access the application**
   - Frontend: `http://localhost/PetSupply_eCommerce/frontend/`
   - Admin: Login and navigate to admin-dashboard.html

### Test Credentials

**Admin Account:**
- Email: `admin@petsupply.com`
- Password: `admin123`

**Regular User:**
- Email: `john@example.com`
- Password: `admin123`

## 📂 Project Structure

```
PetSupply_eCommerce/
├── frontend/                    # User interface
│   ├── index.html              # Home page
│   ├── login.html              # Login
│   ├── register.html           # Registration
│   ├── cart.html               # Shopping cart
│   ├── checkout.html           # Checkout
│   ├── orders.html             # Order history
│   ├── profile.html            # User profile
│   ├── admin-dashboard.html    # Admin panel
│   ├── styles.css              # Global styles
│   └── app.js                  # API client & helpers
│
├── backend/                     # Server-side logic
│   ├── index.php               # API entry point
│   ├── config/                 # Configuration
│   ├── middleware/             # Auth & validation
│   ├── models/                 # Data models
│   └── api/                    # API endpoints
│
├── database/                    # Database files
│   ├── schema.sql              # Table definitions
│   └── sample_data.sql         # Test data
│
└── docs/                        # Documentation
    └── SETUP.md                # Detailed setup guide
```

## 🔌 Key API Endpoints

### Authentication
- `POST /auth/register` - Create account
- `POST /auth/login` - Login user
- `GET /auth/profile` - Get profile
- `PUT /auth/profile` - Update profile

### Products & Categories
- `GET /products` - List products
- `GET /products/{id}` - Product details
- `GET /categories` - List categories
- `POST /products` - Add product (admin)
- `PUT /products/{id}` - Edit product (admin)

### Shopping
- `GET /cart` - Get cart
- `POST /cart` - Add to cart
- `DELETE /cart/{id}` - Remove from cart
- `POST /orders` - Create order

### Admin
- `GET /admin/users` - List users
- `GET /admin/orders` - List orders
- `PUT /admin/orders/{id}` - Update order status
- `POST /dashboard` - Dashboard stats

## 🛒 Usage Examples

### Customer Journey
1. Register/Login
2. Browse products by category
3. Search for specific items
4. Add products to cart
5. Review cart and proceed to checkout
6. Enter shipping details
7. Choose payment method (COD or eSewa)
8. Place order
9. View order in history
10. Track order status

### Admin Operations
1. Login with admin credentials
2. Access admin dashboard
3. Manage products (add/edit/delete)
4. Manage categories
5. View and update orders
6. View sales statistics
7. Manage users

## 💳 Payment Integration

### Cash on Delivery (COD)
- Order placed immediately
- Status: "Pending COD"
- Payment collected at delivery

### eSewa Online Payment
- Simulated payment gateway
- Redirects to payment page
- Returns success/failure
- Updates order payment status automatically

To test: During checkout, select "Online Payment (eSewa)"

## 🎨 Design Features

- **Responsive Layout**: Works on desktop, tablet, mobile
- **Modern UI**: Clean, professional design
- **Intuitive Navigation**: Easy to use
- **Color Scheme**: Pet-themed branding
- **Forms**: Validation with user feedback
- **Loading States**: Visual feedback during operations
- **Error Handling**: Clear error messages
- **Accessibility**: Semantic HTML

## 🔐 Security Implementation

1. **Password Security**
   - BCrypt hashing (cost factor: 10)
   - Never stored in plain text

2. **Authentication**
   - JWT tokens with 24-hour expiration
   - Token validation on protected endpoints

3. **Authorization**
   - Role-based access (user/admin)
   - Admin-only endpoints protected

4. **Data Protection**
   - PDO prepared statements prevent SQL injection
   - CORS headers restrict cross-origin requests
   - Input validation on all endpoints

5. **Best Practices**
   - Secure password requirements
   - Error messages don't leak sensitive info
   - HTTPS recommended for production

## 📊 Database Schema

**7 Main Tables:**
- `users` - Customer & admin accounts
- `categories` - Product categories
- `products` - Product inventory
- `orders` - Customer orders
- `order_items` - Items in each order
- `cart` - Shopping cart
- `payments` - Payment transactions
- `admin_logs` - Admin activity logs

## 🧪 Testing

### Manual Testing Scenarios

1. **User Registration**
   - Create new account
   - Verify validation
   - Login with credentials

2. **Product Management**
   - Browse all products
   - Filter by category
   - Search products
   - View details

3. **Shopping**
   - Add to cart
   - Modify quantities
   - Remove items
   - Clear cart

4. **Checkout**
   - COD order
   - eSewa payment (simulated)
   - Order confirmation

5. **Admin Tasks**
   - Manage products
   - Manage categories
   - Update order status
   - View reports

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| 404 errors | Check mod_rewrite is enabled, verify paths |
| Database connection failed | Check credentials in database.php, ensure MySQL running |
| API calls not working | Verify API_BASE_URL in app.js, check CORS headers |
| Products not showing | Ensure sample_data.sql was imported, query database |
| Login not working | Check passwords in database, verify JWT secret |

See `docs/SETUP.md` for detailed troubleshooting.

## 📝 Code Quality

- ✅ Well-organized file structure
- ✅ Meaningful variable names
- ✅ Inline comments explaining logic
- ✅ Consistent formatting
- ✅ DRY (Don't Repeat Yourself) principles
- ✅ Separation of concerns
- ✅ Proper error handling
- ✅ Input validation

## 🚢 Deployment Checklist

Before going to production:

- [ ] Change JWT_SECRET in `backend/config/database.php`
- [ ] Update database credentials
- [ ] Change admin password
- [ ] Enable HTTPS
- [ ] Set proper file permissions (755/644)
- [ ] Update API URLs if different domain
- [ ] Test payment gateway integration
- [ ] Set up backup strategy
- [ ] Monitor error logs
- [ ] Consider caching strategies

## 📚 Documentation

- `docs/SETUP.md` - Detailed setup & configuration guide
- Code comments throughout all files
- Clear folder organization
- Sample data for testing
- API endpoint documentation

## 🎓 Educational Use

This project is perfect for:
- Learning full-stack development
- Understanding e-commerce systems
- PHP backend development
- JavaScript frontend development
- Database design
- REST API design
- Authentication systems
- Payment integration basics

## 💡 Features You Can Extend

- Payment gateway integrations (Stripe, PayPal)
- Email notifications
- Advanced admin reporting
- User reviews & ratings
- Wishlist functionality
- Discount codes
- Inventory alerts
- Invoice generation
- Multi-language support
- Two-factor authentication

## 📞 Support & Documentation

Refer to `docs/SETUP.md` for:
- Detailed installation steps
- Configuration instructions
- API reference
- Troubleshooting guide
- Sample use cases
- Database queries

## ⭐ Key Highlights

🎯 **Complete Solution** - Everything you need for a pet supply store
🔐 **Secure** - Industry-standard security practices
📱 **Responsive** - Works on all devices
⚡ **Fast** - Optimized queries and caching
💼 **Professional** - Production-ready code
📖 **Well Documented** - Clear setup and usage guides
🎨 **Modern UI** - Clean, professional design
🛠️ **Maintainable** - Clean code structure

## 🎉 Summary

**PetSupply E-Commerce** is a complete, working e-commerce solution that demonstrates:
- Full-stack development capabilities
- Modern web application architecture
- Secure authentication & authorization
- RESTful API design
- Responsive web design
- Database design & optimization
- Admin panel creation
- Payment integration

**Ready to run locally in minutes!** Just follow the quick start guide above.

---

**Happy coding! 🐾**

For detailed setup instructions, see [docs/SETUP.md](docs/SETUP.md)
