CREATE TABLE IF NOT EXISTS news (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(255) NOT NULL,
    excerpt      TEXT,
    content      TEXT,
    category     VARCHAR(50) DEFAULT 'Ogólne',
    image_path   VARCHAR(255),
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
