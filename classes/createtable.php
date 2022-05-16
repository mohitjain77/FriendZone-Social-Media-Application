<?php
        $conn = mysqli_connect("localhost", "root", "", "mydatabase");


            $query = "CREATE DATABASE [IF NOT EXISTS] mydatabase
                    [CHARACTER SET charset_name]
                    [COLLATE collation_name]"
            
            mysqli_query($conn, $query);

            $query = "CREATE TABLE IF NOT EXISTS `posts` (
                `id` bigint(9) NOT NULL AUTO_INCREMENT,
                `postid` bigint(9) NOT NULL,
                `username` varchar(50) NOT NULL,
                `post` text DEFAULT NULL,
                `picture` varchar(1000) DEFAULT NULL,
                `is_profilepicture` tinyint(1) NOT NULL DEFAULT 0,
                `is_coverpicture` tinyint(1) NOT NULL DEFAULT 0,
                `likes` int(11) NOT NULL DEFAULT 0,
                `comments` int(11) NOT NULL DEFAULT 0,
                `has_picture` tinyint(1) NOT NULL DEFAULT 0,
                `parent` bigint(9) NOT NULL,
                `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`),
                KEY `postid` (`postid`),
                KEY `username` (`username`),
                KEY `likes` (`likes`),
                KEY `comments` (`comments`),
                KEY `date` (`date`),
                KEY `has_picture` (`has_picture`) USING BTREE,
                KEY `is_profilepicture` (`is_profilepicture`),
                KEY `is_coverpicture` (`is_coverpicture`),
                KEY `parent` (`parent`),
                FULLTEXT KEY `post` (`post`)
               ) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4";

            mysqli_query($conn, $query);
    


            $query = "CREATE TABLE IF NOT EXISTS `likescounter` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `matterid` bigint(20) NOT NULL,
                `type` varchar(10) NOT NULL,
                `likes` text NOT NULL,
                PRIMARY KEY (`id`),
                KEY `matterid` (`matterid`),
                KEY `type` (`type`)
               ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

            mysqli_query($conn, $query);


            $query = "CREATE TABLE `registration` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `fullname` varchar(50) NOT NULL,
                `username` varchar(50) NOT NULL,
                `email` varchar(50) NOT NULL,
                `password` varchar(255) NOT NULL,
                `profile_pic` varchar(1000) DEFAULT '',
                `cover_pic` varchar(1000) DEFAULT '',
                `phone` varchar(15) NOT NULL DEFAULT '0',
                `dob` varchar(11) DEFAULT NULL,
                `address` text,
                `bio` text,
                `created_by` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `fullname` (`fullname`),
                KEY `username` (`username`),
                KEY `email` (`email`),
                KEY `created_by` (`created_by`)
               ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4";

            mysqli_query($conn, $query);


?>