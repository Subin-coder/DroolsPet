<?php
/**
 * Direct Sample Data Loader
 * Loads sample data directly without JSON parsing issues
 */

header('Content-Type: text/html; charset=utf-8');

// Database connection
try {
    $pdo = new PDO(
        'mysql:host=localhost;port=3306;dbname=petsupply_ecommerce',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('<h2 style="color: red;">Database connection failed: ' . $e->getMessage() . '</h2>');
}

$success = true;
$messages = [];

// Sample categories
$categories = [
    ['name' => 'Dog Food', 'description' => 'Nutritious food products for dogs', 'image_url' => 'https://via.placeholder.com/200?text=Dog+Food'],
    ['name' => 'Cat Food', 'description' => 'Premium food products for cats', 'image_url' => 'https://via.placeholder.com/200?text=Cat+Food'],
    ['name' => 'Dog Toys', 'description' => 'Fun toys for dogs', 'image_url' => 'https://via.placeholder.com/200?text=Dog+Toys'],
    ['name' => 'Cat Toys', 'description' => 'Interactive toys for cats', 'image_url' => 'https://via.placeholder.com/200?text=Cat+Toys'],
    ['name' => 'Grooming', 'description' => 'Grooming supplies', 'image_url' => 'https://via.placeholder.com/200?text=Grooming'],
    ['name' => 'Accessories', 'description' => 'Pet accessories', 'image_url' => 'https://via.placeholder.com/200?text=Accessories'],
];

// Insert categories
try {
    foreach ($categories as $cat) {
        $pdo->prepare('INSERT INTO categories (name, description, image_url) VALUES (?, ?, ?)')
            ->execute([$cat['name'], $cat['description'], $cat['image_url']]);
    }
    $messages[] = '✓ Added ' . count($categories) . ' categories';
} catch (Exception $e) {
    $messages[] = '⚠ Categories: ' . $e->getMessage();
}

// Sample products
$products = [
    ['cat' => 'Dog Food', 'name' => 'Premium Dog Kibble - 5kg', 'price' => 2500.00, 'stock' => 50, 'sku' => 'DOG-KIBBLE-5KG', 'image' => 'https://images.unsplash.com/photo-1597933697716-021c56619c27?w=300&h=300&fit=crop'],
    ['cat' => 'Dog Food', 'name' => 'Organic Puppy Food', 'price' => 1800.00, 'stock' => 40, 'sku' => 'PUPPY-ORGANIC', 'image' => 'https://images.unsplash.com/photo-1596854407944-bf87f6fdd49e?w=300&h=300&fit=crop'],
    ['cat' => 'Dog Food', 'name' => 'Beef & Rice Dog Food - 2kg', 'price' => 1200.00, 'stock' => 60, 'sku' => 'BEEF-RICE-2KG', 'image' => 'https://images.unsplash.com/photo-1594647869516-5a01a9014e0e?w=300&h=300&fit=crop'],
    ['cat' => 'Dog Food', 'name' => 'Low Fat Dog Food', 'price' => 2200.00, 'stock' => 30, 'sku' => 'LOW-FAT-5KG', 'image' => 'https://images.unsplash.com/photo-1609032227486-169dfd79b0e6?w=300&h=300&fit=crop'],
    
    ['cat' => 'Cat Food', 'name' => 'Premium Cat Dry Food - 3kg', 'price' => 1800.00, 'stock' => 45, 'sku' => 'CAT-DRY-3KG', 'image' => 'https://images.unsplash.com/photo-1577755176994-d03dbc5a3356?w=300&h=300&fit=crop'],
    ['cat' => 'Cat Food', 'name' => 'Cat Wet Food Variety Pack', 'price' => 800.00, 'stock' => 80, 'sku' => 'CAT-WET-PACK', 'image' => 'https://images.unsplash.com/photo-1590973961558-dc9e0e87cf9c?w=300&h=300&fit=crop'],
    ['cat' => 'Cat Food', 'name' => 'Kitten Formula Food', 'price' => 1500.00, 'stock' => 35, 'sku' => 'KITTEN-FORMULA', 'image' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=300&h=300&fit=crop'],
    ['cat' => 'Cat Food', 'name' => 'Senior Cat Food', 'price' => 2000.00, 'stock' => 25, 'sku' => 'SENIOR-CAT-2KG', 'image' => 'https://images.unsplash.com/photo-1568699620914-98d8d3eab40a?w=300&h=300&fit=crop'],
    
    ['cat' => 'Dog Toys', 'name' => 'Rubber Kong Toy', 'price' => 600.00, 'stock' => 70, 'sku' => 'KONG-RUBBER-LG', 'image' => 'https://images.unsplash.com/photo-1535241749838-299277b6305f?w=300&h=300&fit=crop'],
    ['cat' => 'Dog Toys', 'name' => 'Tennis Ball Set', 'price' => 350.00, 'stock' => 100, 'sku' => 'TENNIS-BALL-3PC', 'image' => 'https://images.unsplash.com/photo-1597937466474-37bdb0f8e4a2?w=300&h=300&fit=crop'],
    ['cat' => 'Dog Toys', 'name' => 'Squeaky Duck Toy', 'price' => 450.00, 'stock' => 65, 'sku' => 'SQUEAKY-DUCK', 'image' => 'https://images.unsplash.com/photo-1576193067805-ddd228de6f64?w=300&h=300&fit=crop'],
    ['cat' => 'Dog Toys', 'name' => 'Rope Tug Toy', 'price' => 380.00, 'stock' => 85, 'sku' => 'ROPE-TOY-2PC', 'image' => 'https://images.unsplash.com/photo-1558958899-c7e6a82a0e6f?w=300&h=300&fit=crop'],
    
    ['cat' => 'Cat Toys', 'name' => 'Feather Wand Toy', 'price' => 400.00, 'stock' => 90, 'sku' => 'FEATHER-WAND', 'image' => 'https://images.unsplash.com/photo-1594677318286-39812b348b90?w=300&h=300&fit=crop'],
    ['cat' => 'Cat Toys', 'name' => 'Laser Pointer', 'price' => 300.00, 'stock' => 110, 'sku' => 'LASER-POINTER-RED', 'image' => 'https://images.unsplash.com/photo-1597932018838-76159c25e3a0?w=300&h=300&fit=crop'],
    ['cat' => 'Cat Toys', 'name' => 'Catnip Mouse Toys', 'price' => 500.00, 'stock' => 75, 'sku' => 'CATNIP-MOUSE-5PC', 'image' => 'https://images.unsplash.com/photo-1534084246d59379b0f5e43d4a0fd3b94f78287f?w=300&h=300&fit=crop'],
    ['cat' => 'Cat Toys', 'name' => 'Ball with Bell', 'price' => 250.00, 'stock' => 120, 'sku' => 'BELL-BALL-MULTI', 'image' => 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=300&h=300&fit=crop'],
    
    ['cat' => 'Grooming', 'name' => 'Pet Grooming Brush', 'price' => 650.00, 'stock' => 55, 'sku' => 'BRUSH-SOFT-LG', 'image' => 'https://images.unsplash.com/photo-1576662712957-fc00765a37c6?w=300&h=300&fit=crop'],
    ['cat' => 'Grooming', 'name' => 'Dog Nail Clippers', 'price' => 800.00, 'stock' => 40, 'sku' => 'NAIL-CLIPPER-PRO', 'image' => 'https://images.unsplash.com/photo-1584622314961-3ec03d9ca326?w=300&h=300&fit=crop'],
    ['cat' => 'Grooming', 'name' => 'Pet Shampoo - 500ml', 'price' => 450.00, 'stock' => 95, 'sku' => 'SHAMPOO-500ML', 'image' => 'https://images.unsplash.com/photo-1599305589939-e654d0c6b13b?w=300&h=300&fit=crop'],
    ['cat' => 'Grooming', 'name' => 'Grooming Glove', 'price' => 320.00, 'stock' => 78, 'sku' => 'GROOMING-GLOVE-PINK', 'image' => 'https://images.unsplash.com/photo-1574081900373-c0b2e5876dff?w=300&h=300&fit=crop'],
    
    ['cat' => 'Accessories', 'name' => 'Pet Collar', 'price' => 500.00, 'stock' => 100, 'sku' => 'COLLAR-ADJ-L', 'image' => 'https://images.unsplash.com/photo-1595642632823-38e3993ceb5d?w=300&h=300&fit=crop'],
    ['cat' => 'Accessories', 'name' => 'Dog Leash - 1.5m', 'price' => 450.00, 'stock' => 120, 'sku' => 'LEASH-1.5M', 'image' => 'https://images.unsplash.com/photo-1599305589939-e654d0c6b13b?w=300&h=300&fit=crop'],
    ['cat' => 'Accessories', 'name' => 'Pet Bed - Medium', 'price' => 2000.00, 'stock' => 25, 'sku' => 'BED-MED-GREY', 'image' => 'https://images.unsplash.com/photo-1559821481-d38d39e5b36f?w=300&h=300&fit=crop'],
    ['cat' => 'Accessories', 'name' => 'Food Bowl Set', 'price' => 600.00, 'stock' => 80, 'sku' => 'BOWL-SET-2PC', 'image' => 'https://images.unsplash.com/photo-1596854407944-bf87f6fdd49e?w=300&h=300&fit=crop'],
];

// Get category IDs
try {
    $stmt = $pdo->prepare('SELECT id, name FROM categories');
    $stmt->execute();
    $cat_map = [];
    foreach ($stmt->fetchAll() as $row) {
        $cat_map[$row['name']] = $row['id'];
    }
    
    // Insert products
    $product_count = 0;
    foreach ($products as $prod) {
        if (isset($cat_map[$prod['cat']])) {
            $pdo->prepare('INSERT INTO products (category_id, name, description, price, quantity_in_stock, image_url, sku) VALUES (?, ?, ?, ?, ?, ?, ?)')
                ->execute([
                    $cat_map[$prod['cat']],
                    $prod['name'],
                    'Premium quality pet product - perfect for your beloved pets',
                    $prod['price'],
                    $prod['stock'],
                    $prod['image'] ?? 'https://via.placeholder.com/300',
                    $prod['sku']
                ]);
            $product_count++;
        }
    }
    $messages[] = '✓ Added ' . $product_count . ' products with images';
} catch (Exception $e) {
    $messages[] = '⚠ Products: ' . $e->getMessage();
}

// Add test user
try {
    $password_hash = password_hash('user123', PASSWORD_BCRYPT);
    $pdo->prepare('INSERT INTO users (username, email, password_hash, full_name, role, is_active) VALUES (?, ?, ?, ?, ?, ?)')
        ->execute(['testuser', 'user@example.com', $password_hash, 'Test User', 'user', 1]);
    $messages[] = '✓ Added test user (user@example.com / user123)';
} catch (Exception $e) {
    $messages[] = '⚠ User: ' . $e->getMessage();
}

// Add admin user
try {
    $password_hash = password_hash('admin123', PASSWORD_BCRYPT);
    $pdo->prepare('INSERT INTO users (username, email, password_hash, full_name, role, is_active) VALUES (?, ?, ?, ?, ?, ?)')
        ->execute(['admin', 'admin@example.com', $password_hash, 'Admin User', 'admin', 1]);
    $messages[] = '✓ Added admin user (admin@example.com / admin123)';
} catch (Exception $e) {
    $messages[] = '⚠ Admin: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sample Data Loaded</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .container { border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        h1 { color: #333; }
        .status { padding: 10px; margin: 10px 0; border-radius: 3px; }
        .success { background: #d4edda; color: #155724; }
        ul { line-height: 1.8; }
        a { color: #007bff; text-decoration: none; display: inline-block; margin: 10px 5px 10px 0; padding: 10px 15px; background: #f0f0f0; border-radius: 3px; }
        a:hover { background: #e0e0e0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Sample Data Loaded!</h1>
        
        <div class="status success">
            <strong>✓ All sample data has been added successfully!</strong>
        </div>
        
        <h3>What was added:</h3>
        <ul>
            <?php foreach ($messages as $msg): ?>
                <li><?php echo htmlspecialchars($msg); ?></li>
            <?php endforeach; ?>
        </ul>
        
        <h3>Test Accounts:</h3>
        <ul>
            <li><strong>User:</strong> user@example.com / user123</li>
            <li><strong>Admin:</strong> admin@example.com / admin123</li>
        </ul>
        
        <h3>Next Steps:</h3>
        <div>
            <a href="frontend/index.html">View Store</a>
            <a href="frontend/register.html">Create Account</a>
            <a href="frontend/login.html">Test Login</a>
        </div>
    </div>
</body>
</html>
