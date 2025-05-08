#!/bin/bash

# Set these variables to your desired values
DB_NAME="calligraphy"
DB_USER="omarelbaz"

# Prompt for password if needed
echo "Creating database '$DB_NAME' (if it doesn't exist)..."
createdb -U "$DB_USER" "$DB_NAME" 2>/dev/null || echo "Database may already exist."

echo "Setting up database tables..."
psql -U "$DB_USER" -d "$DB_NAME" <<'EOF'
-- Create users table with all required fields
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    address TEXT,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id),
    product_id INTEGER REFERENCES products(id),
    quantity INTEGER NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert test user (password is 'password123')
INSERT INTO users (email, password, first_name, last_name, address, phone) VALUES (
    'user@example.com',
    '$2y$10$e0NRw6Qw1Qw1Qw1Qw1Qw1uQw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q',
    'Test',
    'User',
    '123 Test Street',
    '123-456-7890'
)
ON CONFLICT (email) DO NOTHING;

-- Insert products
INSERT INTO products (name, price, image, description) VALUES
    ('Bismillah Wall Art', 35.00, 'assets/bismil.jpg', 'Beautiful Bismillah calligraphy perfect for your home or office.'),
    ('Alhamdulillah Calligraphy', 45.00, 'assets/alhamd.jpg', 'Elegant Alhamdulillah design in traditional Arabic calligraphy.'),
    ('Ayatul Kursi Poster', 60.00, 'assets/ayat.jpg', 'Ayatul Kursi beautifully rendered in classic Arabic calligraphy style.'),
    ('Golden Calligraphy', 70.00, 'assets/golden_caligraphy.jpg', 'A stunning golden Arabic calligraphy piece that adds luxury to any space.'),
    ('Calligraphy Trio', 90.00, 'assets/caligraphy_trio.jpg', 'A trio of calligraphy artworks, perfect for a modern and elegant wall display.'),
    ('Classic Calligraphy Art', 55.00, 'assets/caligraphy1.jpg', 'Classic Arabic calligraphy with timeless beauty and intricate detail.')
ON CONFLICT (id) DO NOTHING;

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_orders_user_id ON orders(user_id);
CREATE INDEX IF NOT EXISTS idx_order_items_order_id ON order_items(order_id);
CREATE INDEX IF NOT EXISTS idx_products_name ON products(name);
EOF

echo "Database setup complete!"