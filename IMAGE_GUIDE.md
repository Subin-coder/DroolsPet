# 🖼️ Product Images Guide

## Current Setup

Product images are now fully integrated! Here's what we have:

### Features:
- ✅ **Product Cards** - Show beautiful product images (220px height)
- ✅ **Product Detail Modal** - Large image display with product info side-by-side
- ✅ **Image Hover Effect** - Smooth zoom animation on hover
- ✅ **Real Unsplash Images** - High-quality pet product images loaded with sample data

---

## How to Update Product Images

### Method 1: Update via Database (SQL)
```sql
UPDATE products 
SET image_url = 'https://your-image-url.jpg' 
WHERE id = 1;
```

### Method 2: Add Image URL when Inserting Product
```sql
INSERT INTO products (name, price, image_url, ...) 
VALUES ('Product Name', 99.99, 'https://image-url.jpg', ...);
```

### Method 3: Upload Images to Server
1. Create a folder: `/uploads/images/`
2. Upload your image
3. Set URL: `http://localhost/PetSupply_eCommerce/uploads/images/product.jpg`

---

## Image URL Sources

### Free Stock Photo Sites (Recommended):
- **Unsplash** - https://unsplash.com (used for sample data)
- **Pexels** - https://pexels.com
- **Pixabay** - https://pixabay.com
- **Placeholder** - https://via.placeholder.com (for testing)

### Example Unsplash URLs:
```
https://images.unsplash.com/photo-ID?w=300&h=300&fit=crop
```

---

## Reload Sample Data with Images

To reload all 24 products with real images:

1. Open: `http://localhost/PetSupply_eCommerce/load-sample.php`
2. Click **"Test All Endpoints"** button (if you already loaded sample data)
3. Or **clear the products table and reload**:

**Via MySQL:**
```sql
DELETE FROM products;
DELETE FROM categories;
-- Then reload sample data
```

---

## Image Display Specifications

| Location | Width | Height | Aspect |
|----------|-------|--------|--------|
| Product Card | 100% | 220px | varies |
| Detail Modal | 400px max | auto | varies |
| Responsive | Scales | Scales | Responsive |

---

## Troubleshooting Images

### Image Not Loading?
1. Check URL is accessible in browser
2. Verify URL starts with `http://` or `https://`
3. Check file permissions for uploaded images
4. Use developer tools (F12) to see error

### Broken Images?
Use this placeholder as fallback:
```
https://via.placeholder.com/300?text=Pet+Product
```

---

## Next Steps

To add a product management page where users can upload images:

```php
// Create /uploads/ folder for user uploads
// Create image upload form in admin panel
// Process with PHP: move_uploaded_file()
// Store path in database
```

Would you like me to create:
- ✅ Product management page with image upload?
- ✅ Image upload endpoint?
- ✅ More detailed sample data?
