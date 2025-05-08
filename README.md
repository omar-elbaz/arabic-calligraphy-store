# Arabic Calligraphy

A modern PHP e-commerce web app for Arabic calligraphy art. Users can browse, add to cart, register/login, manage their profile, and checkout with a beautiful, responsive UI.

## Tech Stack

- **PHP** — Backend logic and server-side rendering
- **CSS** — Responsive and modern styling
- **PostgreSQL** — Relational database for users, profiles, and cart data
- **Railway** — Cloud hosting and managed PostgreSQL
- **Mailgun** — API used to efficiently send emails to users.

## Features

- Browse a curated collection of Arabic calligraphy art
- Add items to a shopping cart
- User registration and login (with PostgreSQL)
- Profile management (name, address, phone)
- Cart persists per user profile
- Responsive design and modern UI
- Checkout with profile confirmation in a modal popup
- Thank you page after purchase
- Confirmation Email Sent

## File Structure

```
/ (project root)
├── assets/                # Product images and favicon
├── process_login.php
├── process_register.php
├── update_profile.php
├── add_to_cart.php
├── logout.php
└── finalize_checkout.php
├── index.php
├── cart.php
├── checkout.php
├── login.php
├── register.php
└── profile.php
├── includes/              # Shared PHP includes (header, db, products)
├── scripts/               # Setup scripts (e.g., setup_db.sh)
├── style.css              # Main stylesheet
├── .env.example           # Example environment variables for Railway
└── README.md              # This file
```

## Environment Variables

Copy `.env.example` to `.env` and fill in your Railway PostgreSQL credentials (or set them in the Railway dashboard).

## Database Setup

See `scripts/setup_db.sh` for a sample script to create the `users` table and a test user.

## Customization

- Add more products in `includes/products.php`.
- Update styles in `style.css`.
- Add more profile fields or features as needed.
- Add Stripe Integration for payment processing
- Send a confirmation email to users

## License

MIT
