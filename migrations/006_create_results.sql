CREATE TABLE IF NOT EXISTS results (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    athlete          VARCHAR(150) NOT NULL,
    competition      VARCHAR(255),
    discipline       VARCHAR(100),
    result_time      VARCHAR(20),
    place            INT,
    competition_date DATE,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
