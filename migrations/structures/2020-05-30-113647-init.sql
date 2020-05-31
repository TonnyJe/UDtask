CREATE TABLE `user_admin` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL
) ENGINE='InnoDB';

CREATE TABLE `village` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL
) ENGINE='InnoDB';

CREATE TABLE `user_admin_x_village` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_admin_id` int(11) NOT NULL,
  `village_id` int(11) NOT NULL,
  `rights` varchar(63) NOT NULL,
  FOREIGN KEY (`user_admin_id`) REFERENCES `user_admin` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`village_id`) REFERENCES `village` (`id`) ON DELETE CASCADE
) ENGINE='InnoDB';

CREATE TABLE `setting` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE='InnoDB';

CREATE TABLE `rights` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL
) ENGINE='InnoDB';
