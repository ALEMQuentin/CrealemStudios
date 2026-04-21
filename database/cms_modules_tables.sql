CREATE TABLE IF NOT EXISTS forms (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    created_at TEXT
);

CREATE TABLE IF NOT EXISTS form_submissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    form_id INTEGER,
    data TEXT,
    created_at TEXT
);

CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    email TEXT,
    date TEXT,
    service TEXT,
    status TEXT DEFAULT 'pending',
    created_at TEXT
);

CREATE TABLE IF NOT EXISTS testimonials (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    author TEXT,
    content TEXT,
    status TEXT DEFAULT 'published',
    created_at TEXT
);

CREATE TABLE IF NOT EXISTS galleries (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    created_at TEXT
);

CREATE TABLE IF NOT EXISTS gallery_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    gallery_id INTEGER,
    media_path TEXT,
    created_at TEXT
);
