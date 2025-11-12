CREATE USER 'login_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT SELECT, UPDATE, DELETE
ON login.* TO 'login_user'@'localhost';
FLUSH PRIVILEGES;