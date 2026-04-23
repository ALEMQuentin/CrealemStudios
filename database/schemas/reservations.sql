CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_name TEXT,
    client_phone TEXT,
    pickup_address TEXT,
    dropoff_address TEXT,
    datetime TEXT,
    status TEXT,
    created_at TEXT,
    updated_at TEXT
);
