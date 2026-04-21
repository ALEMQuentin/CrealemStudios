CREATE TABLE IF NOT EXISTS menus (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    location_key TEXT NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS menu_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    menu_id INTEGER NOT NULL,
    label TEXT NOT NULL,
    item_type TEXT NOT NULL DEFAULT 'custom',
    url TEXT NULL,
    page_id INTEGER NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

INSERT OR IGNORE INTO menus (id, name, location_key, created_at, updated_at)
VALUES (1, 'Menu principal', 'primary', datetime('now'), datetime('now'));
