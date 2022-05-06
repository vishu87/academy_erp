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