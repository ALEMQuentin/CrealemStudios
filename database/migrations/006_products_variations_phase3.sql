CREATE TABLE IF NOT EXISTS product_variations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    sku TEXT NULL,
    regular_price REAL NULL,
    sale_price REAL NULL,
    stock_quantity INTEGER NULL,
    stock_status TEXT NOT NULL DEFAULT 'instock',
    image_media_id INTEGER NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    status TEXT NOT NULL DEFAULT 'published',
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS product_variation_attribute_values (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    variation_id INTEGER NOT NULL,
    attribute_id INTEGER NOT NULL,
    term_id INTEGER NOT NULL
);
