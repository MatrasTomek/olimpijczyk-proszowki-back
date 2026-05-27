CREATE TABLE IF NOT EXISTS gallery_images (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255),
    category    VARCHAR(100) DEFAULT 'Inne',
    filename    VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
