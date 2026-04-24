CREATE TABLE IF NOT EXISTS chauffeur_documents (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    chauffeur_id INTEGER NOT NULL,
    document_type TEXT NOT NULL,
    original_name TEXT NOT NULL,
    file_path TEXT NOT NULL,
    mime_type TEXT DEFAULT NULL,
    size_bytes INTEGER DEFAULT 0,
    status TEXT NOT NULL DEFAULT 'en_attente',
    validation_note TEXT DEFAULT NULL,
    validated_at TEXT DEFAULT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT NULL
);

CREATE INDEX IF NOT EXISTS idx_chauffeur_documents_chauffeur_id ON chauffeur_documents(chauffeur_id);
CREATE INDEX IF NOT EXISTS idx_chauffeur_documents_status ON chauffeur_documents(status);
CREATE INDEX IF NOT EXISTS idx_chauffeur_documents_type ON chauffeur_documents(document_type);
