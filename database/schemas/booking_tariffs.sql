CREATE TABLE IF NOT EXISTS booking_tariffs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    vehicle_type TEXT NOT NULL UNIQUE,
    label TEXT NOT NULL,
    base_fare REAL NOT NULL DEFAULT 12,
    price_per_km REAL NOT NULL DEFAULT 1.80,
    price_per_minute REAL NOT NULL DEFAULT 0.30,
    minimum_fare REAL NOT NULL DEFAULT 12,
    night_multiplier REAL NOT NULL DEFAULT 1.30,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT NULL
);

INSERT OR IGNORE INTO booking_tariffs (
    vehicle_type,
    label,
    base_fare,
    price_per_km,
    price_per_minute,
    minimum_fare,
    night_multiplier,
    is_active
) VALUES
('berline', 'Berline', 12, 1.80, 0.30, 12, 1.30, 1),
('van', 'Van', 18, 2.20, 0.40, 18, 1.30, 1),
('business', 'Business', 15, 2.00, 0.35, 15, 1.30, 1);
