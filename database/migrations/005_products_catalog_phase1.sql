ALTER TABLE products ADD COLUMN sku TEXT NULL;
ALTER TABLE products ADD COLUMN regular_price REAL NULL;
ALTER TABLE products ADD COLUMN sale_price REAL NULL;
ALTER TABLE products ADD COLUMN manage_stock INTEGER NOT NULL DEFAULT 0;
ALTER TABLE products ADD COLUMN stock_quantity INTEGER NULL;
ALTER TABLE products ADD COLUMN stock_status TEXT NOT NULL DEFAULT 'instock';
ALTER TABLE products ADD COLUMN catalog_visibility TEXT NOT NULL DEFAULT 'visible';
ALTER TABLE products ADD COLUMN product_type TEXT NOT NULL DEFAULT 'simple';
ALTER TABLE products ADD COLUMN short_description TEXT NULL;
ALTER TABLE products ADD COLUMN weight REAL NULL;
ALTER TABLE products ADD COLUMN length REAL NULL;
ALTER TABLE products ADD COLUMN width REAL NULL;
ALTER TABLE products ADD COLUMN height REAL NULL;
ALTER TABLE products ADD COLUMN sort_order INTEGER NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS product_categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    parent_id INTEGER NULL,
    description TEXT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS product_category_relations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    category_id INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS product_attributes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    sort_order INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS product_attribute_terms (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    attribute_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    slug TEXT NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NULL,
    updated_at TEXT NULL
);

CREATE TABLE IF NOT EXISTS product_attribute_relations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    attribute_id INTEGER NOT NULL,
    term_id INTEGER NOT NULL
);
