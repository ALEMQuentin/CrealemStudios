CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    excerpt TEXT NULL,
    content TEXT NULL,
    price REAL NULL,
    status TEXT NOT NULL DEFAULT 'draft',
    featured_media_id INTEGER NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS forms (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    description TEXT NULL,
    form_schema_json TEXT NULL,
    status TEXT NOT NULL DEFAULT 'draft',
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS gallery_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    image_media_id INTEGER NULL,
    caption TEXT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS testimonials (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    author_name TEXT NOT NULL,
    company TEXT NULL,
    content TEXT NOT NULL,
    rating INTEGER NOT NULL DEFAULT 5,
    status TEXT NOT NULL DEFAULT 'published',
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    email TEXT NULL,
    phone TEXT NULL,
    company TEXT NULL,
    notes TEXT NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NULL,
    title TEXT NOT NULL,
    booking_date TEXT NULL,
    booking_time TEXT NULL,
    status TEXT NOT NULL DEFAULT 'pending',
    amount REAL NULL,
    notes TEXT NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS subscriptions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT NULL,
    price REAL NULL,
    billing_cycle TEXT NOT NULL DEFAULT 'monthly',
    status TEXT NOT NULL DEFAULT 'active',
    created_at TEXT NULL,
    updated_at TEXT NULL
);
