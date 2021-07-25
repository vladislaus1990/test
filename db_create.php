<?php
$connection = new PDO('mysql:host=localhost', 'root', 'root');
$connection->exec("CREATE DATABASE `test`;CREATE USER 'root'@'localhost' IDENTIFIED BY 'root';GRANT ALL ON `my`.* TO 'root'@'localhost';FLUSH PRIVILEGES;");
$connection->exec("CREATE TABLE `test`.`posts` ( `id` INT NOT NULL AUTO_INCREMENT , `userId` TEXT NOT NULL , `title` TEXT NOT NULL , `body` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB");
$connection->exec("CREATE TABLE `test`.`commentaries` ( `id` INT NOT NULL AUTO_INCREMENT , `postId` TEXT NOT NULL , `name` TEXT NOT NULL , `email` TEXT NOT NULL , `body` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
header('Location: /');
?>