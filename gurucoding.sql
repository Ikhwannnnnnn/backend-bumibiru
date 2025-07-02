USE `db_bumibiru_new`;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`email`,`password`,`role`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Admin','gurucoding@example.com','$2y$10$Aj2Vg4d5PwgGdyDM7uscHe0pzx9lS7BXuVtYf85vUSoFFcrGv7IXC','admin','2024-05-17 09:19:58','2024-05-17 09:19:58',NULL);

/*Table structure for table `mentors` */

DROP TABLE IF EXISTS `mentors`;

CREATE TABLE `mentors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `description` text DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'img_empty.gif',
  `cv` varchar(255) DEFAULT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'not_published',
  `alamat` varchar(255) DEFAULT NULL,
  `latitude` double(10,6) DEFAULT NULL,
  `longitude` double(10,6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mentors_id_user_foreign` (`id_user`),
  CONSTRAINT `mentors_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `mentors` */
