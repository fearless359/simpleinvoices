SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

CREATE TABLE `si_biller` (
  `id` int(10) NOT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street_address2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Email signature',
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `footer` text COLLATE utf8_unicode_ci,
  `paypal_business_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_notify_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_return_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `eway_customer_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paymentsgateway_api_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `custom_field1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_cron` (
  `id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recurrence` int(11) NOT NULL,
  `recurrence_type` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `email_biller` tinyint(1) NOT NULL DEFAULT '0',
  `email_customer` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_cron_log` (
  `id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `cron_id` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `run_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_customers` (
  `id` int(10) NOT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `attention` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street_address2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit_card_holder_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit_card_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit_card_expiry_month` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit_card_expiry_year` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `custom_field1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_customer_id` int(11) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_custom_fields` (
  `cf_id` int(11) NOT NULL,
  `cf_custom_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cf_custom_label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cf_display` tinyint(1) NOT NULL DEFAULT '1',
  `domain_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_custom_flags` (
  `domain_id` int(11) NOT NULL,
  `associated_table` char(10) NOT NULL COMMENT 'Table flag is associated with. Only defined for products for now.',
  `flg_id` tinyint(3) UNSIGNED NOT NULL COMMENT 'Flag number ranging from 1 to 10',
  `field_label` varchar(20) NOT NULL COMMENT 'Label to use on screen where option is set.',
  `enabled` tinyint(1) NOT NULL COMMENT 'Defaults to enabled when record created. Can disable to retire flag.',
  `field_help` varchar(255) NOT NULL COMMENT 'Help information to display for this field.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Specifies an allowed setting for a flag field';

CREATE TABLE `si_extensions` (
  `id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_index` (
  `id` int(11) NOT NULL,
  `node` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `sub_node` int(11) NOT NULL,
  `sub_node_2` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_inventory` (
  `id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(25,6) NOT NULL,
  `cost` decimal(25,6) DEFAULT NULL,
  `date` date NOT NULL,
  `note` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_invoices` (
  `id` int(10) NOT NULL,
  `index_id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `biller_id` int(10) NOT NULL DEFAULT '0',
  `customer_id` int(10) NOT NULL DEFAULT '0',
  `type_id` int(10) NOT NULL DEFAULT '0',
  `preference_id` int(10) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `custom_field1` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field3` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field4` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_invoice_items` (
  `id` int(10) NOT NULL,
  `invoice_id` int(10) NOT NULL DEFAULT '0',
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `quantity` decimal(25,6) NOT NULL DEFAULT '0.000000',
  `product_id` int(10) DEFAULT '0',
  `unit_price` decimal(25,6) DEFAULT '0.000000',
  `tax_amount` decimal(25,6) DEFAULT '0.000000',
  `gross_total` decimal(25,6) DEFAULT '0.000000',
  `description` text COLLATE utf8_unicode_ci,
  `total` decimal(25,6) DEFAULT '0.000000',
  `attribute` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_invoice_item_attachments` (
  `id` int(10) NOT NULL COMMENT 'Unique ID for this entry',
  `invoice_item_id` int(10) NOT NULL COMMENT 'ID of invoice item this attachment is associated with',
  `name` varchar(255) NOT NULL COMMENT 'Name of attached object',
  `attachment` blob COMMENT 'Attached object'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Objects attached to an invoice item';

CREATE TABLE `si_invoice_item_tax` (
  `id` int(11) NOT NULL,
  `invoice_item_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '%',
  `tax_rate` decimal(25,6) NOT NULL,
  `tax_amount` decimal(25,6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_invoice_type` (
  `inv_ty_id` int(11) NOT NULL,
  `inv_ty_description` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_log` (
  `id` bigint(20) NOT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid` int(11) NOT NULL DEFAULT '1',
  `sqlquerie` text COLLATE utf8_unicode_ci NOT NULL,
  `last_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_payment` (
  `id` int(10) NOT NULL,
  `ac_inv_id` int(11) NOT NULL,
  `ac_amount` decimal(25,6) NOT NULL,
  `ac_notes` text COLLATE utf8_unicode_ci NOT NULL,
  `ac_date` datetime NOT NULL,
  `ac_payment_type` int(10) NOT NULL DEFAULT '1',
  `domain_id` int(11) NOT NULL,
  `online_payment_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ac_check_number` varchar(10) COLLATE utf8_unicode_ci DEFAULT '' NOT NULL COMMENT 'Check number for CHECK payment types'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_payment_types` (
  `pt_id` int(10) NOT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `pt_description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `pt_enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_preferences` (
  `pref_id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `pref_description` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_currency_sign` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_heading` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_wording` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_detail_heading` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_detail_line` text COLLATE utf8_unicode_ci,
  `pref_inv_payment_method` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_payment_line1_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_payment_line1_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_payment_line2_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_payment_line2_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL,
  `locale` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `index_group` int(11) NOT NULL,
  `currency_code` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `include_online_payment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_position` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_products` (
  `id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `unit_price` decimal(25,6) DEFAULT '0.000000',
  `default_tax_id` int(11) DEFAULT NULL,
  `default_tax_id_2` int(11) DEFAULT NULL,
  `cost` decimal(25,6) DEFAULT '0.000000',
  `reorder_level` int(11) DEFAULT NULL,
  `custom_field1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `attribute` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes_as_description` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_description` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_flags` char(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'User defined flags'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_products_attributes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type_id` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `visible` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `si_products_attribute_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_products_values` (
  `id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `si_sql_patchmanager` (
  `sql_id` int(11) NOT NULL,
  `sql_patch_ref` int(11) NOT NULL,
  `sql_patch` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sql_release` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sql_statement` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_system_defaults` (
  `id` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domain_id` int(5) NOT NULL DEFAULT '0',
  `extension_id` int(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_tax` (
  `tax_id` int(11) NOT NULL,
  `tax_description` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_percentage` decimal(25,6) DEFAULT '0.000000',
  `type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '%',
  `tax_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `domain_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '0',
  `password` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_user_domain` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `si_user_role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `si_biller`
  ADD PRIMARY KEY (`domain_id`,`id`);

ALTER TABLE `si_cron`
  ADD PRIMARY KEY (`domain_id`,`id`);

ALTER TABLE `si_cron_log`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `CronIdUnq` (`domain_id`,`cron_id`,`run_date`);

ALTER TABLE `si_customers`
  ADD PRIMARY KEY (`domain_id`,`id`);

ALTER TABLE `si_custom_fields`
  ADD PRIMARY KEY (`cf_id`,`domain_id`);

ALTER TABLE `si_custom_flags`
  ADD PRIMARY KEY (`domain_id`,`associated_table`,`flg_id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `dtable` (`domain_id`,`associated_table`);

ALTER TABLE `si_extensions`
  ADD PRIMARY KEY (`id`,`domain_id`);

ALTER TABLE `si_index`
  ADD PRIMARY KEY (`node`,`sub_node`,`sub_node_2`,`domain_id`);

ALTER TABLE `si_inventory`
  ADD PRIMARY KEY (`domain_id`,`id`);

ALTER TABLE `si_invoices`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `UniqDIB` (`index_id`,`preference_id`,`biller_id`,`domain_id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `biller_id` (`biller_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `IdxDI` (`index_id`,`preference_id`,`domain_id`);

ALTER TABLE `si_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `DomainInv` (`invoice_id`,`domain_id`);

ALTER TABLE `si_invoice_item_attachments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `invoice_item_id` (`invoice_item_id`);

ALTER TABLE `si_invoice_item_tax`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `si_invoice_type`
  ADD PRIMARY KEY (`inv_ty_id`);

ALTER TABLE `si_log`
  ADD PRIMARY KEY (`id`,`domain_id`);

ALTER TABLE `si_payment`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `ac_inv_id` (`ac_inv_id`),
  ADD KEY `ac_amount` (`ac_amount`);

ALTER TABLE `si_payment_types`
  ADD PRIMARY KEY (`domain_id`,`pt_id`);

ALTER TABLE `si_preferences`
  ADD PRIMARY KEY (`domain_id`,`pref_id`);

ALTER TABLE `si_products`
  ADD PRIMARY KEY (`domain_id`,`id`);

ALTER TABLE `si_products_attributes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `si_products_attribute_type`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `si_products_values`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `si_sql_patchmanager`
  ADD PRIMARY KEY (`sql_id`);

ALTER TABLE `si_system_defaults`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `UnqNameInDomain` (`domain_id`,`name`),
  ADD KEY `name` (`name`);

ALTER TABLE `si_tax`
  ADD PRIMARY KEY (`domain_id`,`tax_id`);

ALTER TABLE `si_user`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `uname` (`username`);

ALTER TABLE `si_user_domain`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `si_user_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `si_biller`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_cron`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_cron_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_customers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_custom_fields`
  MODIFY `cf_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_extensions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_invoices`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_invoice_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_invoice_item_attachments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for this entry';

ALTER TABLE `si_invoice_item_tax`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_invoice_type`
  MODIFY `inv_ty_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_payment`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_payment_types`
  MODIFY `pt_id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_preferences`
  MODIFY `pref_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_products_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_products_attribute_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_products_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_sql_patchmanager`
  MODIFY `sql_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_system_defaults`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_tax`
  MODIFY `tax_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_user_domain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `si_user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;
