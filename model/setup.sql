CREATE USER 'photo'@'localhost' IDENTIFIED BY 'PASSWORD_HERE';
CREATE DATABASE photo_db;
-- allow some CRUD to happen
GRANT SELECT, DELETE, INSERT, UPDATE ON photo_db.* TO 'photo'@'localhost';
FLUSH PRIVILEGES;
