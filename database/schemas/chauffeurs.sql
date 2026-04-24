CREATE TABLE IF NOT EXISTS chauffeurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    phone TEXT DEFAULT NULL,
    email TEXT DEFAULT NULL,
    vehicle_label TEXT DEFAULT NULL,
    vehicle_plate TEXT DEFAULT NULL,
    vtc_card_number TEXT DEFAULT NULL,
    status TEXT NOT NULL DEFAULT 'active',
    notes TEXT DEFAULT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT NULL
);

CREATE INDEX IF NOT EXISTS idx_chauffeurs_status ON chauffeurs(status);
CREATE INDEX IF NOT EXISTS idx_chauffeurs_name ON chauffeurs(last_name, first_name);
