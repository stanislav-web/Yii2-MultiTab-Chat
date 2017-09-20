SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `messages`;

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `ip` int(4) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`ip`,`username`) # prevent douplicates between users
) ENGINE=InnoDB DEFAULT
CHARACTER SET utf8
  COLLATE utf8_general_ci
  COMMENT='Users';

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` int(11) UNSIGNED NOT NULL,
  `message` TEXT NOT NULL,
  `publication` TIMESTAMP NOT NULL
                                    DEFAULT CURRENT_TIMESTAMP
  ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`userId`) REFERENCES users(id)
    ON UPDATE RESTRICT
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT
CHARACTER SET utf8
  COLLATE utf8_general_ci
  COMMENT='User\'s Messages';
SET FOREIGN_KEY_CHECKS = 1;
