CREATE TABLE IF NOT EXISTS content (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type TEXT NOT NULL,
    title TEXT NOT NULL,
    slug TEXT NOT NULL,
    excerpt TEXT NULL,
    content TEXT NULL,
    status TEXT NOT NULL DEFAULT 'draft',
    author_id INTEGER NULL,
    parent_id INTEGER NULL,
    menu_order INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE UNIQUE INDEX IF NOT EXISTS idx_content_type_slug
ON content(type, slug);

CREATE TABLE IF NOT EXISTS content_meta (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    content_id INTEGER NOT NULL,
    meta_key TEXT NOT NULL,
    meta_value TEXT NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE INDEX IF NOT EXISTS idx_content_meta_content_id
ON content_meta(content_id);

CREATE INDEX IF NOT EXISTS idx_content_meta_key
ON content_meta(meta_key);
