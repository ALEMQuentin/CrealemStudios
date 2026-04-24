CREATE TABLE IF NOT EXISTS chauffeur_documents (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    chauffeur_id INTEGER NOT NULL,
    document_type TEXT NOT NULL,
    original_name TEXT NOT NULL,
    stored_name TEXT NOT NULL,
    file_path TEXT NOT NULL,
    mime_type TEXT DEFAULT NULL,
    status TEXT NOT NULL DEFAULT 'en_attente',
    rejection_reason TEXT DEFAULT NULL,
    uploaded_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    validated_at TEXT DEFAULT NULL,
    updated_at TEXT DEFAULT NULL
);

CREATE INDEX IF NOT EXISTS idx_chauffeur_documents_chauffeur_id ON chauffeur_documents(chauffeur_id);
CREATE INDEX IF NOT EXISTS idx_chauffeur_documents_status ON chauffeur_documents(status);
