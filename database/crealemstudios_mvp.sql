CREATE DATABASE IF NOT EXISTS crealemstudios CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crealemstudios;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'editor',
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(190) NOT NULL UNIQUE,
    content LONGTEXT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'draft',
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS media (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    mime_type VARCHAR(150) NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(190) NOT NULL,
    body TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (name, email, password, role, created_at, updated_at)
SELECT 'Admin', 'admin@crealemstudios.local', '$2y$10$7eQZ7uM6XJi.Q4R9i9sWmOhwXSGuwK8qv7j6G4t2g7lXg8tiA3z4G', 'admin', NOW(), NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'admin@crealemstudios.local'
);

INSERT INTO pages (title, slug, content, status, created_at, updated_at)
SELECT 'Accueil', 'accueil', 'Première page du CMS CrealemStudios.', 'published', NOW(), NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM pages WHERE slug = 'accueil'
);
