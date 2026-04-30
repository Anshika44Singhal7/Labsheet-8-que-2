-- Run once in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS verdana_studio
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE verdana_studio;

CREATE TABLE IF NOT EXISTS contact_submissions (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(120) NOT NULL,
  email      VARCHAR(255) NOT NULL,
  phone      VARCHAR(20)  DEFAULT NULL,
  service    VARCHAR(100) NOT NULL,
  budget     VARCHAR(100) DEFAULT NULL,
  message    TEXT         NOT NULL,
  ip_address VARCHAR(45)  DEFAULT NULL,
  is_read    TINYINT(1)   NOT NULL DEFAULT 0,
  created_at DATETIME     DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email      (email),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
