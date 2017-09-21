SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `messages`;

CREATE TABLE `users` (
  `ip`       INT UNSIGNED NOT NULL,
  `username` VARCHAR(64)  NOT NULL DEFAULT '',
  PRIMARY KEY (`ip`)
)
  ENGINE = InnoDB
  DEFAULT
  CHARACTER SET utf8
  COLLATE utf8_general_ci
  COMMENT ='Users';

CREATE TABLE `messages` (
  `id`          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userIp`      INT UNSIGNED        NOT NULL,
  `message`     TEXT                NOT NULL,
  `publication` TIMESTAMP           NOT NULL
                                             DEFAULT CURRENT_TIMESTAMP
  ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`userIp`) REFERENCES users (ip)
    ON UPDATE RESTRICT
    ON DELETE CASCADE
)
  ENGINE = InnoDB
  DEFAULT
  CHARACTER SET utf8
  COLLATE utf8_general_ci
  COMMENT ='User\'s Messages';
SET FOREIGN_KEY_CHECKS = 1;
