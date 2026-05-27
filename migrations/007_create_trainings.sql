CREATE TABLE IF NOT EXISTS training_groups (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    age_range  VARCHAR(50),
    level      VARCHAR(100),
    sort_order INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS training_sessions (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    group_id         INT NOT NULL,
    day_of_week      VARCHAR(20) NOT NULL,
    time_start       VARCHAR(20) NOT NULL,
    time_morning     VARCHAR(20),
    workout_type     VARCHAR(50),
    pool             VARCHAR(100),
    location         VARCHAR(150),
    pool_summer      VARCHAR(100),
    location_summer  VARCHAR(150),
    sort_order       INT DEFAULT 0,
    FOREIGN KEY (group_id) REFERENCES training_groups(id) ON DELETE CASCADE
);
