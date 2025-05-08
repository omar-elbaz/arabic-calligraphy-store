#!/bin/bash

# Set these variables to your desired values
DB_NAME="calligraphy"
DB_USER="omarelbaz"

# Prompt for password if needed
echo "Creating database '$DB_NAME' (if it doesn't exist)..."
createdb -U "$DB_USER" "$DB_NAME" 2>/dev/null || echo "Database may already exist."

echo "Setting up users table and test user..."
psql -U "$DB_USER" -d "$DB_NAME" <<'EOF'
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Password is 'password123'
INSERT INTO users (email, password) VALUES (
    'user@example.com',
    '$2y$10$e0NRw6Qw1Qw1Qw1Qw1Qw1uQw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q'
)
ON CONFLICT (email) DO NOTHING;
EOF

echo "Database setup complete!"