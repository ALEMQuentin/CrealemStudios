CREATE TABLE IF NOT EXISTS content_blocks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    content_id INTEGER NOT NULL,
    block_type TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    settings_json TEXT NULL,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE INDEX IF NOT EXISTS idx_content_blocks_content_id
ON content_blocks(content_id);

CREATE INDEX IF NOT EXISTS idx_content_blocks_sort_order
ON content_blocks(sort_order);
