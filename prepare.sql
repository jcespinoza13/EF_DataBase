
CREATE USER IF NOT EXISTS 'nextUser'@'localhost' IDENTIFIED WITH mysql_native_password BY 'NextUser#';
GRANT ALL PRIVILEGES ON * . * TO 'nextUser'@'localhost';
