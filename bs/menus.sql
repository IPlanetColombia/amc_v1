
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
  
CREATE TABLE `configurations` (
  `id` int(11) UNSIGNED NOT NULL,
  `name_app` varchar(45) NOT NULL,
  `icon_app` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `intro` text,
  `footer` text,
  `meta_description` text,
  `meta_keywords` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `menus` (
  `id` int(11) UNSIGNED NOT NULL,
  `option` varchar(40) NOT NULL,
  `url` varchar(100) NOT NULL,
  `icon` varchar(20) DEFAULT NULL,
  `position` int(3) DEFAULT NULL,
  `type` enum('primario','secundario') NOT NULL DEFAULT 'primario',
  `references` int(11) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `component` enum('table','controller') NOT NULL DEFAULT 'table',
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  `table` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `typeUser` enum('Super Administrator','Registered') NOT NULL DEFAULT 'Super Administrator',
  `menu_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `notifications` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `icon` varchar(45) NOT NULL,
  `color` enum('','cyan','amber','orange','purple','red darken-1') NOT NULL DEFAULT 'cyan',
  `created_at` datetime DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `configurations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_menu_id_foreign` (`menu_id`);
  
ALTER TABLE `menus`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
ALTER TABLE `permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
ALTER TABLE `permissions`
  ADD CONSTRAINT `permission_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`);

  

ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);
  
ALTER TABLE `notifications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
  
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
