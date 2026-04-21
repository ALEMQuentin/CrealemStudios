CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    excerpt TEXT NULL,
    content TEXT NULL,
    status TEXT NOT NULL DEFAULT 'draft',
    meta_title TEXT NULL,
    meta_description TEXT NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);
