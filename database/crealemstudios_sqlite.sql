CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'editor',
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS pages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    content TEXT NULL,
    status TEXT NOT NULL DEFAULT 'draft',
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    filename TEXT NOT NULL,
    path TEXT NOT NULL,
    mime_type TEXT NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    subject TEXT NOT NULL,
    body TEXT NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

INSERT OR IGNORE INTO users (id, name, email, password, role, created_at, updated_at)
VALUES (
    1,
    'Admin',
    'admin@crealemstudios.local',
    '$2y$10$7eQZ7uM6XJi.Q4R9i9sWmOhwXSGuwK8qv7j6G4t2g7lXg8tiA3z4G',
    'admin',
    datetime('now'),
    datetime('now')
);

INSERT OR IGNORE INTO pages (id, title, slug, content, status, created_at, updated_at)
VALUES (
    1,
    'Accueil',
    'accueil',
    'Première page du CMS CrealemStudios.',
    'published',
    datetime('now'),
    datetime('now')
);
