CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'admin',
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    setting_key TEXT NOT NULL UNIQUE,
    setting_value TEXT NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS pages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    content TEXT NULL,
    meta_title TEXT NULL,
    meta_description TEXT NULL,
    status TEXT NOT NULL DEFAULT 'draft',
    created_at TEXT NULL,
    updated_at TEXT NULL
);
