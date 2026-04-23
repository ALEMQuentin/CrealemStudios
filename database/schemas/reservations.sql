CREATE TABLE IF NOT EXISTS reservations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,

    client_id INTEGER DEFAULT NULL,
    chauffeur_id INTEGER DEFAULT NULL,

    client_name TEXT NOT NULL,
    client_phone TEXT NOT NULL,
    client_email TEXT DEFAULT NULL,

    pickup_address TEXT NOT NULL,
    dropoff_address TEXT NOT NULL,

    pickup_datetime TEXT NOT NULL,

    passengers INTEGER NOT NULL DEFAULT 1,
    luggage INTEGER NOT NULL DEFAULT 0,

    vehicle_type TEXT DEFAULT 'berline',
    payment_method TEXT DEFAULT NULL,

    price REAL DEFAULT NULL,
    distance_meters INTEGER DEFAULT NULL,
    duration_seconds INTEGER DEFAULT NULL,
    routing_provider TEXT DEFAULT NULL,

    customer_note TEXT DEFAULT NULL,
    internal_note TEXT DEFAULT NULL,

    status TEXT NOT NULL DEFAULT 'a_confirmer',
    is_archived INTEGER NOT NULL DEFAULT 0,

    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT NULL
);

CREATE INDEX IF NOT EXISTS idx_reservations_status ON reservations(status);
CREATE INDEX IF NOT EXISTS idx_reservations_pickup_datetime ON reservations(pickup_datetime);
CREATE INDEX IF NOT EXISTS idx_reservations_archived ON reservations(is_archived);
CREATE INDEX IF NOT EXISTS idx_reservations_client_id ON reservations(client_id);
CREATE INDEX IF NOT EXISTS idx_reservations_chauffeur_id ON reservations(chauffeur_id);
