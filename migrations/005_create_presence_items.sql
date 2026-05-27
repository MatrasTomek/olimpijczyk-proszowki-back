CREATE TABLE IF NOT EXISTS presence_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(100) NOT NULL,
    subtitle    VARCHAR(100),
    description TEXT,
    metric      VARCHAR(50),
    icon        VARCHAR(50),
    image_path  VARCHAR(255),
    sort_order  INT DEFAULT 0,
    active      TINYINT(1) DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
