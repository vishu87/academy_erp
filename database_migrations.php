<?php

ALTER TABLE `leads`  ADD `document` TEXT NULL  AFTER `client_id`;
ALTER TABLE `clients`  ADD `code` VARCHAR(255) NOT NULL  AFTER `id`;