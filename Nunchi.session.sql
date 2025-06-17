
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `profile` longblob,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255)NOT NULL,
  `profile_picture` longblob,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('NO', 'YES') DEFAULT 'NO',
  PRIMARY KEY (`id`),
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE video (
    v_id INT(11) NOT NULL AUTO_INCREMENT,
    id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    video LONGBLOB NOT NULL,
    thumbnail LONGBLOB NOT NULL,
    likes INT(11) DEFAULT 0,
    dislikes INT(11) DEFAULT 0,
    views INT(11) DEFAULT 0,
    topic VARCHAR(255) DEFAULT NULL,
    upload_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (v_id)
    FOREIGN KEY `id` REFERENCES users(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE downloads (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    video_id INT(11) NOT NULL,
    download_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY user_id REFERENCES users(`id`) ON DELETE CASCADE,
    FOREIGN KEY video_id REFERENCES video (`v_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE user_likes (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    video_id INT(11) NOT NULL,
    action ENUM('like', 'dislike') NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY user_id REFERENCES users(`id`) ON DELETE CASCADE,
    FOREIGN KEY video_id REFERENCES video(`v_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE subscriptions (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    subscribed_to_user_id INT(11) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subscribed_to_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE bins_user (
    id INT(11) NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_picture LONGBLOB,
    reason TEXT DEFAULT NULL;
    created_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
    payment_status ENUM('NO', 'YES') DEFAULT 'NO',
    deleted_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE bins_video (
    v_id INT(11) NOT NULL AUTO_INCREMENT,
    id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    video LONGBLOB NOT NULL,
    thumbnail LONGBLOB NOT NULL,
    likes INT(11) DEFAULT 0,
    dislikes INT(11) DEFAULT 0,
    views INT(11) DEFAULT 0,
    topic VARCHAR(255) DEFAULT NULL,
    upload_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (v_id),
    UNIQUE KEY (id, v_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;