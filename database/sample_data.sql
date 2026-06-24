-- Sample Data for Pet Supply E-Commerce

USE petsupply_ecommerce;

-- Insert categories
INSERT INTO categories (name, description, image_url) VALUES
('Dog Food', 'Nutritious food products for dogs of all ages and sizes', 'https://via.placeholder.com/200?text=Dog+Food'),
('Cat Food', 'Premium food products specially formulated for cats', 'https://via.placeholder.com/200?text=Cat+Food'),
('Dog Toys', 'Fun and engaging toys for dogs', 'https://via.placeholder.com/200?text=Dog+Toys'),
('Cat Toys', 'Interactive toys for cats to keep them entertained', 'https://via.placeholder.com/200?text=Cat+Toys'),
('Grooming', 'Grooming supplies and tools for pet care', 'https://via.placeholder.com/200?text=Grooming'),
('Accessories', 'Collars, leashes, beds, and other pet accessories', 'https://via.placeholder.com/200?text=Accessories');

-- Insert products for Dog Food
INSERT INTO products (category_id, name, description, price, quantity_in_stock, image_url, sku) VALUES
(1, 'Premium Dog Kibble - 5kg', 'High-quality dog food with all essential nutrients', 2500.00, 50, 'https://via.placeholder.com/200?text=Dog+Kibble', 'DOG-KIBBLE-5KG'),
(1, 'Organic Puppy Food', 'Specially formulated for growing puppies', 1800.00, 40, 'https://via.placeholder.com/200?text=Puppy+Food', 'PUPPY-ORGANIC-500G'),
(1, 'Beef & Rice Dog Food - 2kg', 'Delicious beef flavored dog food', 1200.00, 60, 'https://via.placeholder.com/200?text=Beef+Food', 'BEEF-RICE-2KG'),
(1, 'Low Fat Dog Food', 'Perfect for weight management', 2200.00, 30, 'https://via.placeholder.com/200?text=Low+Fat', 'LOW-FAT-5KG');

-- Insert products for Cat Food
INSERT INTO products (category_id, name, description, price, quantity_in_stock, image_url, sku) VALUES
(2, 'Premium Cat Dry Food - 3kg', 'Complete nutrition for adult cats', 1800.00, 45, 'https://via.placeholder.com/200?text=Cat+Dry+Food', 'CAT-DRY-3KG'),
(2, 'Cat Wet Food Variety Pack', 'Assorted flavors of wet food pouches', 800.00, 80, 'https://via.placeholder.com/200?text=Cat+Wet', 'CAT-WET-PACK'),
(2, 'Kitten Formula Food', 'Nutritious food for young kittens', 1500.00, 35, 'https://via.placeholder.com/200?text=Kitten+Formula', 'KITTEN-FORMULA-1.5KG'),
(2, 'Senior Cat Food', 'Easy to digest formula for senior cats', 2000.00, 25, 'https://via.placeholder.com/200?text=Senior+Cat', 'SENIOR-CAT-2KG');

-- Insert products for Dog Toys
INSERT INTO products (category_id, name, description, price, quantity_in_stock, image_url, sku) VALUES
(3, 'Rubber Kong Toy', 'Durable rubber toy for heavy chewers', 600.00, 70, 'https://via.placeholder.com/200?text=Kong+Toy', 'KONG-RUBBER-LG'),
(3, 'Tennis Ball Set', 'Pack of 3 tennis balls for fetch games', 350.00, 100, 'https://via.placeholder.com/200?text=Tennis+Ball', 'TENNIS-BALL-3PC'),
(3, 'Squeaky Duck Toy', 'Fun squeaky toy dogs love', 450.00, 65, 'https://via.placeholder.com/200?text=Squeaky+Duck', 'SQUEAKY-DUCK'),
(3, 'Rope Tug Toy', 'Great for tug-of-war games', 380.00, 85, 'https://via.placeholder.com/200?text=Rope+Toy', 'ROPE-TOY-2PC');

-- Insert products for Cat Toys
INSERT INTO products (category_id, name, description, price, quantity_in_stock, image_url, sku) VALUES
(4, 'Feather Wand Toy', 'Interactive feather toy for cats', 400.00, 90, 'https://via.placeholder.com/200?text=Feather+Wand', 'FEATHER-WAND'),
(4, 'Laser Pointer', 'Fun interactive laser toy for cats', 300.00, 110, 'https://via.placeholder.com/200?text=Laser+Pointer', 'LASER-POINTER-RED'),
(4, 'Catnip Mouse Toys', 'Pack of 5 catnip-filled mouse toys', 500.00, 75, 'https://via.placeholder.com/200?text=Catnip+Mouse', 'CATNIP-MOUSE-5PC'),
(4, 'Ball with Bell', 'Interactive jingle ball for cats', 250.00, 120, 'https://via.placeholder.com/200?text=Bell+Ball', 'BELL-BALL-MULTI');

-- Insert products for Grooming
INSERT INTO products (category_id, name, description, price, quantity_in_stock, image_url, sku) VALUES
(5, 'Pet Grooming Brush', 'Soft grooming brush for all pet types', 650.00, 55, 'https://via.placeholder.com/200?text=Brush', 'BRUSH-SOFT-LG'),
(5, 'Dog Nail Clippers', 'Professional dog nail trimming tool', 800.00, 40, 'https://via.placeholder.com/200?text=Nail+Clipper', 'NAIL-CLIPPER-PRO'),
(5, 'Pet Shampoo - 500ml', 'Gentle and effective pet shampoo', 450.00, 95, 'https://via.placeholder.com/200?text=Shampoo', 'SHAMPOO-500ML'),
(5, 'Grooming Glove', 'Soft rubber grooming glove', 320.00, 78, 'https://via.placeholder.com/200?text=Glove', 'GROOMING-GLOVE-PINK');

-- Insert products for Accessories
INSERT INTO products (category_id, name, description, price, quantity_in_stock, image_url, sku) VALUES
(6, 'Adjustable Dog Collar', 'Comfortable and durable dog collar', 450.00, 120, 'https://via.placeholder.com/200?text=Collar', 'COLLAR-ADJ-BLUE'),
(6, 'Dog Leash - 5ft', 'Strong and comfortable 5-foot leash', 550.00, 100, 'https://via.placeholder.com/200?text=Leash', 'LEASH-5FT-BLK'),
(6, 'Pet Bed - Medium', 'Comfortable orthopedic pet bed', 3500.00, 30, 'https://via.placeholder.com/200?text=Pet+Bed', 'BED-ORTHO-MED'),
(6, 'Stainless Steel Food Bowl Set', 'Set of 2 food and water bowls', 650.00, 85, 'https://via.placeholder.com/200?text=Bowl+Set', 'BOWL-STEEL-2PC');

-- Insert admin user (password: admin123)
INSERT INTO users (username, email, password_hash, full_name, role) VALUES
('admin', 'admin@petsupply.com', '$2y$10$YIjlrHxQqmv5P3w7t7I2puNHiZb8B8i.UmY0V3q1KzqQ8qT4vKTzm', 'Admin User', 'admin');

-- Insert sample regular users
INSERT INTO users (username, email, password_hash, full_name, phone, address, city, country, postal_code, role) VALUES
('john_doe', 'john@example.com', '$2y$10$XJXm7qQ8vH9K2L6N3P4Q5rS7TuVwXyZaBcDeF0GhIjK1LmNoPq', 'John Doe', '9841234567', '123 Pet Street', 'Kathmandu', 'Nepal', '44600', 'user'),
('jane_smith', 'jane@example.com', '$2y$10$XJXm7qQ8vH9K2L6N3P4Q5rS7TuVwXyZaBcDeF0GhIjK1LmNoPq', 'Jane Smith', '9849876543', '456 Animal Ave', 'Lalitpur', 'Nepal', '44700', 'user');
