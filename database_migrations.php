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


CREATE TABLE `setting_params` ( `id` INT NOT NULL AUTO_INCREMENT , `parameter` VARCHAR(255) NOT NULL , `type` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;


CREATE TABLE `setting_values` ( `id` INT NOT NULL AUTO_INCREMENT , `client_id` INT NULL , `param_id` INT NULL , `value` TEXT NULL , `modified_by` INT NULL , `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , `created_by` TIMESTAMP NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `evals` (
  `id` int(11) NOT NULL,
  `eval_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `evals` (`id`, `eval_name`, `start_date`, `end_date`) VALUES
(7, 'Jul-Sept 2017', '2017-07-01', '2017-09-30'),
(2, 'Apr-Jun 2016', '2016-04-01', '2016-06-30'),
(3, 'July-Sept 2016', '2016-07-01', '2016-09-30'),
(4, 'Oct-Dec 2016', '2016-10-01', '2016-12-31'),
(5, 'Jan-Mar 2017', '2017-01-01', '2017-03-31'),
(6, 'Apr-Jun 2017', '2017-04-01', '2017-06-30'),
(8, 'October - December 2017', '2017-10-01', '2017-12-31'),
(9, 'Jan-Mar 2018', '2018-01-01', '2018-03-31'),
(10, 'Apr-Jun 2018', '2018-04-01', '2018-06-30'),
(11, 'July - Sept 2018', '2018-07-01', '2018-09-30'),
(12, 'Oct- Dec 2018', '2018-10-01', '2018-12-31'),
(13, 'Jan - Mar 2019', '2019-01-01', '2019-03-31'),
(14, 'Apr - Jun 2019', '2019-04-01', '2019-06-30'),
(15, 'Jul - Sept. 2019', '2019-07-01', '2019-09-30'),
(16, 'Oct - Dec 19', '2019-10-01', '2019-12-31'),
(17, 'Jan - Mar 2020', '2020-01-01', '2020-03-31');

ALTER TABLE `evals`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `evals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `parameter_id` int(11) NOT NULL,
  `marks` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `evaluations` (`id`, `student_id`, `group_id`, `evaluation_id`, `parameter_id`, `marks`, `category_id`, `remarks`, `created_at`) VALUES
(51, 956, 21, 17, 68, 3, NULL, 'dsfa222', '2022-03-11 06:44:51'),
(50, 956, 21, 17, 67, 5, NULL, NULL, '2022-03-11 06:44:51'),
(49, 956, 21, 17, 66, 3, NULL, NULL, '2022-03-11 06:44:51'),
(48, 956, 21, 17, 0, 0, 5, 'over all2222', '2022-03-11 06:44:51'),
(47, 956, 21, 17, 72, 1, NULL, NULL, '2022-03-11 06:44:51'),
(46, 956, 21, 17, 71, 1, NULL, 'vdfsbs1111', '2022-03-11 06:44:51'),
(45, 956, 21, 17, 70, 1, NULL, NULL, '2022-03-11 06:44:51'),
(44, 956, 21, 17, 0, 0, 6, 'over all1111', '2022-03-11 06:44:51'),
(52, 956, 21, 17, 69, 2, NULL, NULL, '2022-03-11 06:44:51'),
(53, 885, 17, 17, 70, 2, NULL, '', '2022-03-11 06:54:30'),
(54, 885, 17, 17, 71, 4, NULL, '', '2022-03-11 06:54:30'),
(55, 885, 17, 17, 72, 4, NULL, '', '2022-03-11 06:54:30'),
(67, 891, 17, 17, 66, 4, NULL, '', '2022-03-11 07:01:40'),
(66, 891, 17, 17, 72, 1, NULL, '', '2022-03-11 07:01:40'),
(65, 891, 17, 17, 71, 3, NULL, '', '2022-03-11 07:01:40'),
(64, 891, 17, 17, 70, 2, NULL, '', '2022-03-11 07:01:40'),
(68, 891, 17, 17, 68, 2, NULL, '', '2022-03-11 07:01:40');
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
COMMIT;


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `eval_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `hidden` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `eval_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `eval_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

INSERT INTO `eval_categories` (`id`, `category_name`, `priority`, `hidden`) VALUES
(2, 'GENERAL MOVEMENTS / PHYSICAL', 1, 0),
(3, 'TACTICAL', 16, 1),
(4, 'CO-ORDINATIONAL', 17, 1),
(5, 'PSYCHOLOGICAL', 8, 0),
(6, 'SOCIAL', 9, 0),
(7, 'TECHNICAL', 20, 1),
(8, 'PASSING', 3, 0),
(9, 'DRIBBLING', 5, 0),
(10, 'SHOOTING', 7, 0),
(11, 'HEADING', 14, 1),
(12, 'Ball Feeling', 2, 0),
(13, 'Receiving', 4, 0),
(14, 'BALL CONTROL ', 6, 0);



SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `eval_parameters` (
  `id` int(11) NOT NULL,
  `parameter_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parameter_show_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_category_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `no_show` int(1) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `eval_parameters` (`id`, `parameter_name`, `parameter_show_name`, `parent_category_id`, `parent_id`, `no_show`, `priority`) VALUES
(1, 'Ability to play with both feet', 'Ability to<br>play with<br>both feet', 7, 0, 1, 0),
(2, 'Passing', '', 7, 0, 1, 0),
(3, 'Short', 'Passing<br>Short', 7, 2, 1, 0),
(4, 'Medium', 'Passing<br>Medium', 7, 2, 1, 0),
(5, 'Organised Long Ball', 'Passing<br>Organised<br>Long Ball', 7, 2, 1, 0),
(6, 'Dis-Organised Long Ball', 'Passing<br>Dis-Organised<br>Long Ball', 7, 2, 1, 0),
(7, 'First Touch', 'First<br>Touch', 7, 0, 1, 0),
(8, 'Attacking 1 v 1', 'Attacking<br>1 v 1', 7, 0, 1, 0),
(9, 'Defending 1 v 1', 'Defending<br>1 v 1', 7, 0, 1, 0),
(10, 'Shootinng / Striking at Goal - Outside Penalty Box', 'Shootinng / Striking<br>at Goal<br>Outside Penalty Box', 7, 0, 1, 0),
(11, 'Finishing Inside Penalty Box', 'Finishing Inside<br>Penalty Box', 7, 0, 1, 0),
(12, 'Heading', '', 7, 0, 1, 0),
(13, 'Defensive', 'Heading<br>Defensive', 7, 12, 1, 0),
(14, 'Attacking', 'Heading<br>Attacking', 7, 12, 1, 0),
(15, 'In - Play', 'Heading<br>In - Play', 7, 12, 1, 0),
(16, 'Strength', '', 2, 0, 0, 3),
(17, 'Stamina', '', 2, 0, 1, 0),
(18, 'Jumping', '', 2, 0, 1, 0),
(19, 'Speed', '', 2, 0, 0, 5),
(20, 'Attacking 1 v 1', 'Attacking<br>1 v 1', 2, 0, 1, 0),
(21, 'Defending 1 v 1', 'Defending<br>1 v 1', 2, 0, 1, 0),
(22, 'Game Knowledge<br>Understanding', '', 3, 0, 1, 0),
(23, 'Attacking Play', 'Attacking<br>Play', 3, 0, 1, 0),
(24, 'Defending Play', 'Defending<br>Play', 3, 0, 1, 0),
(25, 'Attacking Transition', 'Attacking<br>Transition', 3, 0, 1, 0),
(26, 'Defending Transition', 'Defending<br>Transition', 3, 0, 1, 0),
(27, 'Action / Reaction', 'Action /<br>Reaction', 3, 0, 1, 0),
(28, 'Creativity', '', 3, 0, 1, 0),
(29, 'Orientation', '', 4, 0, 1, 0),
(30, 'Rhythm', '', 4, 0, 1, 0),
(31, 'Balance', '', 4, 0, 1, 0),
(32, 'Cocentration', '', 5, 0, 1, 0),
(33, 'Determination', '', 5, 0, 1, 0),
(34, 'Perseverance', '', 5, 0, 1, 0),
(35, 'Confidence', '', 5, 0, 1, 0),
(36, 'Communication', '', 6, 0, 1, 0),
(37, 'Behaviour', '', 6, 0, 1, 0),
(38, 'Team Bonding / Spirit', 'Team<br>Bonding<br>/ Spirit', 6, 0, 1, 0),
(39, 'Medium', '', 8, 0, 1, 0),
(40, 'Short Passes', '', 8, 0, 0, 2),
(41, 'Long', '', 8, 0, 1, 0),
(42, 'Use of weaker foot', '', 8, 0, 1, 0),
(43, 'Always open up to receive the ball', '', 8, 0, 1, 0),
(44, 'Applies passing and receiving skills in game situations', '', 8, 0, 1, 0),
(45, 'First Touch', '', 9, 0, 0, 0),
(46, 'Ball Control while Dribbling', '', 9, 0, 0, 0),
(47, 'Agility', '', 9, 0, 1, 0),
(48, 'Use of weaker foot', '', 9, 0, 1, 0),
(49, 'Applies different dribbling skills in game situations', '', 9, 0, 1, 0),
(50, 'Finishing', '', 10, 0, 1, 0),
(51, 'Body Positioning', '', 10, 0, 1, 0),
(52, 'Uses forehead to head the ball', '', 11, 0, 1, 0),
(53, 'Keeps eyes open while heading', '', 11, 0, 1, 0),
(54, 'Jumping ability during heading the ball', '', 11, 0, 1, 0),
(55, 'Game Knowledge Understanding', '', 3, 0, 1, 0),
(56, 'Attacking Play ', '', 3, 0, 1, 0),
(57, 'Defending Play', '', 3, 0, 1, 0),
(58, 'Attacking Transition ', '', 3, 0, 1, 0),
(59, 'Defending Transition ', '', 3, 0, 1, 0),
(60, 'Decision making with the ball', '', 3, 0, 1, 0),
(61, 'Decision making without the ball', '', 3, 0, 1, 0),
(62, 'Balance', '', 4, 0, 1, 0),
(63, 'Reaction', '', 4, 0, 1, 0),
(64, 'Mobility', '', 4, 0, 1, 0),
(65, 'Has good balance while competing for the ball', '', 4, 0, 1, 0),
(66, 'Confidence', '', 5, 0, 0, 0),
(67, 'Concentration', '', 5, 0, 0, 0),
(68, 'Composure', '', 5, 0, 0, 0),
(69, 'Determination', '', 5, 0, 0, 0),
(70, 'Discipline', '', 6, 0, 0, 0),
(71, 'Communication', '', 6, 0, 0, 0),
(72, 'Behaviour', '', 6, 0, 0, 0),
(73, 'Communication proper with teammates and Coach', '', 6, 0, 1, 0),
(74, 'Behavior on and off the field', '', 6, 0, 1, 0),
(75, 'Discipline', '', 6, 0, 1, 0),
(76, 'Power', '', 10, 0, 1, 0),
(77, 'Accuracy', '', 10, 0, 1, 0),
(78, 'Use of weaker foot', '', 10, 0, 1, 0),
(79, 'Balance', '', 2, 0, 0, 1),
(80, 'Coordination', '', 2, 0, 0, 2),
(81, 'Core', '', 2, 0, 0, 4),
(82, 'Mobility ', '', 2, 0, 0, 6),
(83, 'Reaction', '', 2, 0, 0, 7),
(84, 'Technique of Passing', '', 8, 0, 0, 1),
(85, 'Accuracy', '', 8, 0, 0, 3),
(86, 'Passing with Right Foot', '', 8, 0, 0, 4),
(87, 'Passing with Left Foot', '', 8, 0, 0, 5),
(88, 'Movement with the Ball - Right Foot', '', 9, 0, 0, 0),
(89, 'Movement with the Ball - Left Foot', '', 9, 0, 0, 0),
(90, 'Technique of Shooting', '', 10, 0, 0, 0),
(91, 'Shooting with Right Foot', '', 10, 0, 0, 0),
(92, 'Shooting with Left Foot', '', 10, 0, 0, 0),
(93, 'Ball touches', '', 12, 0, 0, 0),
(94, 'Ball touches with Right Foot', '', 12, 0, 0, 0),
(95, 'Ball touches with Left Foot', '', 12, 0, 0, 0),
(96, 'Ball Control', '', 12, 0, 0, 0),
(97, 'Technique of Receiving', '', 13, 0, 0, 0),
(98, 'Cushioning', '', 13, 0, 0, 0),
(99, 'Receiving with Right Foot', '', 13, 0, 0, 0),
(100, 'Receiving with Left Foot', '', 13, 0, 0, 0),
(101, 'Technique of Ball Control', '', 14, 0, 0, 0),
(102, 'On Ground', '', 14, 0, 0, 0),
(103, 'In Air', '', 14, 0, 0, 0);

ALTER TABLE `eval_parameters`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `eval_parameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;
COMMIT;



ALTER TABLE `users` ADD `user_type` TINYINT NOT NULL DEFAULT '1' AFTER `api_key`;

ALTER TABLE `items` ADD `client_id` INT NULL DEFAULT NULL AFTER `added_by`;
ALTER TABLE `companies` ADD `client_id` INT NULL DEFAULT NULL AFTER `added_by`;


CREATE TABLE `user_students` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NULL , `student_id` INT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `users`  ADD `last_login` TIMESTAMP NULL DEFAULT NULL  AFTER `is_admin`;

ALTER TABLE `sms_templates` ADD `added_by` INT NULL DEFAULT NULL AFTER `dlt_pe_id`, ADD `client_id` INT NULL DEFAULT NULL AFTER `added_by`;
ALTER TABLE `email_templates` ADD `added_by` INT NULL DEFAULT NULL AFTER `content`, ADD `client_id` INT NULL DEFAULT NULL AFTER `added_by`;

ALTER TABLE `sessions` ADD `added_by` INT NULL DEFAULT NULL AFTER `end_date`, ADD `client_id` INT NULL DEFAULT NULL AFTER `added_by`;




// vashistha

ALTER TABLE `groups` CHANGE `remark` `remark` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

DROP - > groups table mein group_type_id

ALTER TABLE `center_images` CHANGE `image_thumb` `image_thumb` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `inv_stocks` ADD `client_id` INT NOT NULL AFTER `quantity`;



ALTER TABLE `lead_for`  ADD `page_title` VARCHAR(255) NULL  AFTER `slug`,  ADD `page_description` TEXT NULL  AFTER `page_title`;