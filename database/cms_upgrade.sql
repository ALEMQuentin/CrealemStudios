CREATE TABLE IF NOT EXISTS settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    setting_key TEXT NOT NULL UNIQUE,
    setting_value TEXT NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    filename TEXT NOT NULL,
    original_name TEXT NOT NULL,
    path TEXT NOT NULL,
    mime_type TEXT NULL,
    size INTEGER NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

INSERT OR IGNORE INTO settings (setting_key, setting_value, created_at, updated_at) VALUES
('module_products', '0', datetime('now'), datetime('now')),
('module_blog', '0', datetime('now'), datetime('now')),
('module_forums', '0', datetime('now'), datetime('now')),
('module_messages', '0', datetime('now'), datetime('now'));
