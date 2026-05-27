CREATE TABLE IF NOT EXISTS camps (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    date_from   DATE NOT NULL,
    date_to     DATE NOT NULL,
    location    VARCHAR(255),
    description TEXT,
    status      ENUM('upcoming','open','past') DEFAULT 'upcoming',
    image_path  VARCHAR(255),
    spots_total INT DEFAULT 0,
    spots_left  INT DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
