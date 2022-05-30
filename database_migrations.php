<?php

ALTER TABLE `leads`  ADD `document` TEXT NULL  AFTER `client_id`;
ALTER TABLE `clients`  ADD `code` VARCHAR(255) NOT NULL  AFTER `id`;
//pradeep 06.05.22
UPDATE `clients` SET `code` = 'BU_CODE' WHERE `clients`.`id` = 1;
ALTER TABLE `registrations` ADD `client_id` INT NULL AFTER `pin_code`;
ALTER TABLE `registrations` CHANGE `parent_id` `parent_id` INT(11) NULL;
ALTER TABLE `registrations` CHANGE `fee_plan` `fee_plan` INT(11) NULL DEFAULT NULL;
ALTER TABLE `registrations` ADD `address_state_id` INT NULL DEFAULT NULL AFTER `sec_mobile`, ADD `address_city_id` INT NULL DEFAULT NULL AFTER `address_state_id`;


ALTER TABLE `email_templates` ADD `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `content`, ADD `created_at` TIMESTAMP NULL DEFAULT NULL AFTER `updated_at`;


// gulzar may-20

ALTER TABLE `staff_attendance` ADD `latitude` DOUBLE NULL DEFAULT NULL AFTER `created_at`, ADD `longitude` DOUBLE NULL DEFAULT NULL AFTER `latitude`;

ALTER TABLE `users` ADD `pic` TEXT NULL DEFAULT NULL AFTER `gender`;

// gulzar may 23
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `student_tags` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `student_tags` (`id`, `student_id`, `tag_id`, `user_id`) VALUES
(39, 2422, 1, 151),
(40, 2422, 3, 151),
(41, 2422, 5, 151),
(46, 3205, 1, 151),
(47, 3205, 3, 151),
(48, 3205, 6, 151),
(52, 956, 1, 151),
(53, 956, 4, 151),
(54, 2423, 1, 151),
(55, 2423, 3, 151),
(64, 3204, 1, 151),
(65, 3204, 6, 151),
(66, 3204, 4, 151);

ALTER TABLE `student_tags`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `student_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
COMMIT;


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `tag` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `tags` (`id`, `tag`) VALUES
(1, 'Android'),
(3, 'ios'),
(4, 'php'),
(5, 'c++'),
(6, 'java');

ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

ALTER TABLE `students` ADD `tags` TEXT NULL DEFAULT NULL AFTER `inactive`;


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `guest_students` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `full_name` varchar(200) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `remark` text,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `guest_students`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `guest_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


// by Gulzar 24 may


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `cancel_events` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `op_id` int(11) DEFAULT NULL,
  `cancel_reason` varchar(255) DEFAULT NULL,
  `cancel_remarks` text,
  `user_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `cancel_events`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `cancel_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `reasons` (`id`, `reason`) VALUES (NULL, 'Other');


// by Gulzar 26 May

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `group_coachs` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `coach_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `group_coachs` (`id`, `group_id`, `coach_id`) VALUES
(1, 4, 1),
(2, 5, 1),
(3, 6, 1),
(3, 15, 1),
(3, 16, 1),
(3, 17, 1);

// by pradeep 30 May
ALTER TABLE `lead_for` ADD `slug` VARCHAR(200) NULL DEFAULT NULL AFTER `label`;


