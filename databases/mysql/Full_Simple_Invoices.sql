-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2020 at 04:26 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simpleinvoices`
--

-- --------------------------------------------------------

--
-- Table structure for table `si_biller`
--

CREATE TABLE `si_biller` (
  `id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
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
  `footer` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_business_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_notify_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_return_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `eway_customer_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paymentsgateway_api_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_cron`
--

CREATE TABLE `si_cron` (
  `id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `invoice_id` int(11) UNSIGNED DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recurrence` int(11) NOT NULL,
  `recurrence_type` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `email_biller` tinyint(1) NOT NULL DEFAULT 0,
  `email_customer` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_cron_log`
--

CREATE TABLE `si_cron_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `cron_id` int(11) UNSIGNED DEFAULT NULL,
  `run_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_customers`
--

CREATE TABLE `si_customers` (
  `id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
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
  `notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_invoice` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Invoice index_id value to use as the default template',
  `custom_field1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_customer_id` int(11) UNSIGNED DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_custom_fields`
--

CREATE TABLE `si_custom_fields` (
  `cf_id` int(11) UNSIGNED NOT NULL,
  `cf_custom_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cf_custom_label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cf_display` tinyint(1) NOT NULL DEFAULT 1,
  `domain_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_custom_flags`
--

CREATE TABLE `si_custom_flags` (
  `domain_id` int(11) UNSIGNED NOT NULL,
  `associated_table` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `flg_id` tinyint(3) UNSIGNED NOT NULL COMMENT 'Flag number ranging from 1 to 10',
  `field_label` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL COMMENT 'Defaults to enabled when record created. Can disable to retire flag.',
  `field_help` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Specifies an allowed setting for a flag field';

-- --------------------------------------------------------

--
-- Table structure for table `si_expense`
--

CREATE TABLE `si_expense` (
  `id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(25,6) NOT NULL,
  `expense_account_id` int(11) UNSIGNED DEFAULT NULL,
  `biller_id` int(11) UNSIGNED DEFAULT NULL,
  `customer_id` int(11) UNSIGNED DEFAULT NULL,
  `invoice_id` int(11) UNSIGNED DEFAULT NULL,
  `product_id` int(11) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_expense_account`
--

CREATE TABLE `si_expense_account` (
  `id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_expense_item_tax`
--

CREATE TABLE `si_expense_item_tax` (
  `id` int(11) UNSIGNED NOT NULL,
  `expense_id` int(11) UNSIGNED DEFAULT NULL,
  `tax_id` int(11) UNSIGNED DEFAULT NULL,
  `tax_type` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate` decimal(25,6) NOT NULL,
  `tax_amount` decimal(25,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_extensions`
--

CREATE TABLE `si_extensions` (
  `id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_index`
--

CREATE TABLE `si_index` (
  `id` int(11) UNSIGNED NOT NULL,
  `node` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `sub_node` int(11) NOT NULL,
  `sub_node_2` int(11) NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_install_complete`
--

CREATE TABLE `si_install_complete` (
  `completed` tinyint(1) NOT NULL COMMENT 'Flag SI install has completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Specifies an allowed setting for a flag field';

-- --------------------------------------------------------

--
-- Table structure for table `si_inventory`
--

CREATE TABLE `si_inventory` (
  `id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED DEFAULT NULL,
  `quantity` decimal(25,6) NOT NULL,
  `cost` decimal(25,6) DEFAULT NULL,
  `date` date NOT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoices`
--

CREATE TABLE `si_invoices` (
  `id` int(11) UNSIGNED NOT NULL,
  `index_id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `biller_id` int(11) UNSIGNED DEFAULT NULL,
  `customer_id` int(11) UNSIGNED DEFAULT NULL,
  `type_id` int(11) UNSIGNED DEFAULT NULL,
  `preference_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `custom_field1` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field3` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field4` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `owing` decimal(25,6) NOT NULL DEFAULT 0.000000 COMMENT 'Amount owing as of aging-date',
  `last_activity_date` datetime NOT NULL DEFAULT '2000-12-31 00:00:00' COMMENT 'Date last activity update to the invoice',
  `aging_date` datetime NOT NULL DEFAULT '2000-12-30 00:00:00' COMMENT 'Date aging was last calculated',
  `age_days` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Age of invoice balance',
  `aging` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Aging string (1-14, 15-30, etc.',
  `sales_representative` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoice_items`
--

CREATE TABLE `si_invoice_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `invoice_id` int(11) UNSIGNED DEFAULT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `quantity` decimal(25,6) NOT NULL DEFAULT 0.000000,
  `product_id` int(11) UNSIGNED DEFAULT NULL,
  `unit_price` decimal(25,6) DEFAULT 0.000000,
  `tax_amount` decimal(25,6) DEFAULT 0.000000,
  `gross_total` decimal(25,6) DEFAULT 0.000000,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` decimal(25,6) DEFAULT 0.000000,
  `attribute` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoice_item_attachments`
--

CREATE TABLE `si_invoice_item_attachments` (
  `id` int(11) UNSIGNED NOT NULL,
  `invoice_item_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attachment` blob DEFAULT NULL COMMENT 'Attached object'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Objects attached to an invoice item';

-- --------------------------------------------------------

--
-- Table structure for table `si_invoice_item_tax`
--

CREATE TABLE `si_invoice_item_tax` (
  `id` int(11) UNSIGNED NOT NULL,
  `invoice_item_id` int(11) UNSIGNED DEFAULT NULL,
  `tax_id` int(11) UNSIGNED DEFAULT NULL,
  `tax_type` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate` decimal(25,6) NOT NULL,
  `tax_amount` decimal(25,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoice_type`
--

CREATE TABLE `si_invoice_type` (
  `inv_ty_id` int(11) UNSIGNED NOT NULL,
  `inv_ty_description` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_log`
--

CREATE TABLE `si_log` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `sqlquerie` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_payment`
--

CREATE TABLE `si_payment` (
  `id` int(11) UNSIGNED NOT NULL,
  `ac_inv_id` int(11) UNSIGNED DEFAULT NULL,
  `ac_amount` decimal(25,6) NOT NULL,
  `ac_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `ac_date` datetime NOT NULL,
  `ac_payment_type` int(11) UNSIGNED DEFAULT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `online_payment_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ac_check_number` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_payment_types`
--

CREATE TABLE `si_payment_types` (
  `pt_id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `pt_description` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pt_enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_preferences`
--

CREATE TABLE `si_preferences` (
  `pref_id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `pref_description` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_currency_sign` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_heading` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_wording` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_detail_heading` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_detail_line` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_payment_method` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_payment_line1_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_payment_line1_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_payment_line2_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_inv_payment_line2_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pref_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL,
  `locale` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `index_group` int(11) NOT NULL,
  `set_aging` tinyint(1) NOT NULL DEFAULT 0,
  `currency_code` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `include_online_payment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_position` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_products`
--

CREATE TABLE `si_products` (
  `id` int(11) UNSIGNED NOT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_price` decimal(25,6) DEFAULT 0.000000,
  `default_tax_id` int(11) UNSIGNED DEFAULT NULL,
  `default_tax_id_2` int(11) UNSIGNED DEFAULT NULL,
  `cost` decimal(25,6) DEFAULT 0.000000,
  `reorder_level` int(11) DEFAULT NULL,
  `custom_field1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `attribute` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes_as_description` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_description` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_flags` char(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_products_attributes`
--

CREATE TABLE `si_products_attributes` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type_id` int(11) UNSIGNED DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `visible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_products_attribute_type`
--

CREATE TABLE `si_products_attribute_type` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_products_values`
--

CREATE TABLE `si_products_values` (
  `id` int(11) UNSIGNED NOT NULL,
  `attribute_id` int(11) UNSIGNED DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_sql_patchmanager`
--

CREATE TABLE `si_sql_patchmanager` (
  `sql_id` int(11) UNSIGNED NOT NULL,
  `sql_patch_ref` int(11) NOT NULL,
  `sql_patch` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sql_release` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sql_statement` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_system_defaults`
--

CREATE TABLE `si_system_defaults` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `extension_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_tax`
--

CREATE TABLE `si_tax` (
  `tax_id` int(11) UNSIGNED NOT NULL,
  `tax_description` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_percentage` decimal(25,6) DEFAULT 0.000000,
  `type` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `domain_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_user`
--

CREATE TABLE `si_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` int(11) UNSIGNED DEFAULT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_user_domain`
--

CREATE TABLE `si_user_domain` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_user_role`
--

CREATE TABLE `si_user_role` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `si_biller`
--
ALTER TABLE `si_biller`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `si_cron`
--
ALTER TABLE `si_cron`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `si_cron_log`
--
ALTER TABLE `si_cron_log`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `CronIdUnq` (`domain_id`,`cron_id`,`run_date`),
  ADD KEY `cron_id` (`cron_id`);

--
-- Indexes for table `si_customers`
--
ALTER TABLE `si_customers`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `si_custom_fields`
--
ALTER TABLE `si_custom_fields`
  ADD PRIMARY KEY (`cf_id`,`domain_id`),
  ADD UNIQUE KEY `cf_id` (`cf_id`);

--
-- Indexes for table `si_custom_flags`
--
ALTER TABLE `si_custom_flags`
  ADD PRIMARY KEY (`domain_id`,`associated_table`,`flg_id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `dtable` (`domain_id`,`associated_table`);

--
-- Indexes for table `si_expense`
--
ALTER TABLE `si_expense`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `biller_id` (`biller_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `expense_account_id` (`expense_account_id`);

--
-- Indexes for table `si_expense_account`
--
ALTER TABLE `si_expense_account`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `si_expense_item_tax`
--
ALTER TABLE `si_expense_item_tax`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_id` (`expense_id`),
  ADD KEY `tax_id` (`tax_id`);

--
-- Indexes for table `si_extensions`
--
ALTER TABLE `si_extensions`
  ADD PRIMARY KEY (`id`,`domain_id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `si_index`
--
ALTER TABLE `si_index`
  ADD PRIMARY KEY (`node`,`sub_node`,`sub_node_2`,`domain_id`);

--
-- Indexes for table `si_inventory`
--
ALTER TABLE `si_inventory`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `si_invoices`
--
ALTER TABLE `si_invoices`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `UniqDIB` (`index_id`,`preference_id`,`biller_id`,`domain_id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `biller_id` (`biller_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `IdxDI` (`index_id`,`preference_id`,`domain_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `preference_id` (`preference_id`);

--
-- Indexes for table `si_invoice_items`
--
ALTER TABLE `si_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `DomainInv` (`invoice_id`,`domain_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `si_invoice_item_attachments`
--
ALTER TABLE `si_invoice_item_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_item_id` (`invoice_item_id`);

--
-- Indexes for table `si_invoice_item_tax`
--
ALTER TABLE `si_invoice_item_tax`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_id` (`tax_id`);

--
-- Indexes for table `si_invoice_type`
--
ALTER TABLE `si_invoice_type`
  ADD PRIMARY KEY (`inv_ty_id`);

--
-- Indexes for table `si_log`
--
ALTER TABLE `si_log`
  ADD PRIMARY KEY (`id`,`domain_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `si_payment`
--
ALTER TABLE `si_payment`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `ac_inv_id` (`ac_inv_id`),
  ADD KEY `ac_amount` (`ac_amount`),
  ADD KEY `ac_payment_type` (`ac_payment_type`);

--
-- Indexes for table `si_payment_types`
--
ALTER TABLE `si_payment_types`
  ADD PRIMARY KEY (`domain_id`,`pt_id`),
  ADD UNIQUE KEY `pt_id` (`pt_id`);

--
-- Indexes for table `si_preferences`
--
ALTER TABLE `si_preferences`
  ADD PRIMARY KEY (`domain_id`,`pref_id`),
  ADD UNIQUE KEY `pref_id` (`pref_id`);

--
-- Indexes for table `si_products`
--
ALTER TABLE `si_products`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `default_tax_id` (`default_tax_id`),
  ADD KEY `default_tax_id_2` (`default_tax_id_2`);

--
-- Indexes for table `si_products_attributes`
--
ALTER TABLE `si_products_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `si_products_attribute_type`
--
ALTER TABLE `si_products_attribute_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `si_products_values`
--
ALTER TABLE `si_products_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `si_sql_patchmanager`
--
ALTER TABLE `si_sql_patchmanager`
  ADD PRIMARY KEY (`sql_id`);

--
-- Indexes for table `si_system_defaults`
--
ALTER TABLE `si_system_defaults`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `UnqNameInDomain` (`domain_id`,`name`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `si_tax`
--
ALTER TABLE `si_tax`
  ADD PRIMARY KEY (`domain_id`,`tax_id`),
  ADD UNIQUE KEY `tax_id` (`tax_id`);

--
-- Indexes for table `si_user`
--
ALTER TABLE `si_user`
  ADD PRIMARY KEY (`domain_id`,`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `uname` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `si_user_domain`
--
ALTER TABLE `si_user_domain`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `si_user_role`
--
ALTER TABLE `si_user_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `si_biller`
--
ALTER TABLE `si_biller`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_cron`
--
ALTER TABLE `si_cron`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_cron_log`
--
ALTER TABLE `si_cron_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_customers`
--
ALTER TABLE `si_customers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_custom_fields`
--
ALTER TABLE `si_custom_fields`
  MODIFY `cf_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_expense`
--
ALTER TABLE `si_expense`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_expense_account`
--
ALTER TABLE `si_expense_account`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_expense_item_tax`
--
ALTER TABLE `si_expense_item_tax`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_extensions`
--
ALTER TABLE `si_extensions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_inventory`
--
ALTER TABLE `si_inventory`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_invoices`
--
ALTER TABLE `si_invoices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_invoice_items`
--
ALTER TABLE `si_invoice_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_invoice_item_attachments`
--
ALTER TABLE `si_invoice_item_attachments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_invoice_item_tax`
--
ALTER TABLE `si_invoice_item_tax`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_invoice_type`
--
ALTER TABLE `si_invoice_type`
  MODIFY `inv_ty_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_log`
--
ALTER TABLE `si_log`
  MODIFY `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_payment`
--
ALTER TABLE `si_payment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_payment_types`
--
ALTER TABLE `si_payment_types`
  MODIFY `pt_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_preferences`
--
ALTER TABLE `si_preferences`
  MODIFY `pref_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_products`
--
ALTER TABLE `si_products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_products_attributes`
--
ALTER TABLE `si_products_attributes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_products_attribute_type`
--
ALTER TABLE `si_products_attribute_type`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_products_values`
--
ALTER TABLE `si_products_values`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_sql_patchmanager`
--
ALTER TABLE `si_sql_patchmanager`
  MODIFY `sql_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_system_defaults`
--
ALTER TABLE `si_system_defaults`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_tax`
--
ALTER TABLE `si_tax`
  MODIFY `tax_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_user`
--
ALTER TABLE `si_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_user_domain`
--
ALTER TABLE `si_user_domain`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_user_role`
--
ALTER TABLE `si_user_role`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `si_cron`
--
ALTER TABLE `si_cron`
  ADD CONSTRAINT `si_cron_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `si_invoices` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_cron_log`
--
ALTER TABLE `si_cron_log`
  ADD CONSTRAINT `si_cron_log_ibfk_1` FOREIGN KEY (`cron_id`) REFERENCES `si_cron` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_expense`
--
ALTER TABLE `si_expense`
  ADD CONSTRAINT `si_expense_ibfk_1` FOREIGN KEY (`biller_id`) REFERENCES `si_biller` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_expense_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `si_customers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_expense_ibfk_3` FOREIGN KEY (`invoice_id`) REFERENCES `si_invoices` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_expense_ibfk_4` FOREIGN KEY (`product_id`) REFERENCES `si_products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_expense_ibfk_5` FOREIGN KEY (`expense_account_id`) REFERENCES `si_expense_account` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_expense_item_tax`
--
ALTER TABLE `si_expense_item_tax`
  ADD CONSTRAINT `si_expense_item_tax_ibfk_1` FOREIGN KEY (`expense_id`) REFERENCES `si_expense` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_expense_item_tax_ibfk_2` FOREIGN KEY (`tax_id`) REFERENCES `si_tax` (`tax_id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_inventory`
--
ALTER TABLE `si_inventory`
  ADD CONSTRAINT `si_inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `si_products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_invoices`
--
ALTER TABLE `si_invoices`
  ADD CONSTRAINT `si_invoices_ibfk_1` FOREIGN KEY (`biller_id`) REFERENCES `si_biller` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_invoices_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `si_customers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_invoices_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `si_invoice_type` (`inv_ty_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_invoices_ibfk_4` FOREIGN KEY (`preference_id`) REFERENCES `si_preferences` (`pref_id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_invoice_items`
--
ALTER TABLE `si_invoice_items`
  ADD CONSTRAINT `si_invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `si_invoices` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `si_products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_invoice_item_attachments`
--
ALTER TABLE `si_invoice_item_attachments`
  ADD CONSTRAINT `si_invoice_item_attachments_ibfk_1` FOREIGN KEY (`invoice_item_id`) REFERENCES `si_invoice_items` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_invoice_item_tax`
--
ALTER TABLE `si_invoice_item_tax`
  ADD CONSTRAINT `si_invoice_item_tax_ibfk_1` FOREIGN KEY (`tax_id`) REFERENCES `si_tax` (`tax_id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_log`
--
ALTER TABLE `si_log`
  ADD CONSTRAINT `si_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `si_user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_payment`
--
ALTER TABLE `si_payment`
  ADD CONSTRAINT `si_payment_ibfk_1` FOREIGN KEY (`ac_inv_id`) REFERENCES `si_invoices` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_payment_ibfk_2` FOREIGN KEY (`ac_payment_type`) REFERENCES `si_payment_types` (`pt_id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_products`
--
ALTER TABLE `si_products`
  ADD CONSTRAINT `si_products_ibfk_1` FOREIGN KEY (`default_tax_id`) REFERENCES `si_tax` (`tax_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_products_ibfk_2` FOREIGN KEY (`default_tax_id_2`) REFERENCES `si_tax` (`tax_id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_products_attributes`
--
ALTER TABLE `si_products_attributes`
  ADD CONSTRAINT `si_products_attributes_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `si_products_attribute_type` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_products_values`
--
ALTER TABLE `si_products_values`
  ADD CONSTRAINT `si_products_values_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `si_products_attributes` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_user`
--
ALTER TABLE `si_user`
  ADD CONSTRAINT `si_user_ibfk_1` FOREIGN KEY (`domain_id`) REFERENCES `si_user_domain` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_user_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `si_user_role` (`id`) ON UPDATE CASCADE;

--
-- Test data for required tables
--

--
-- Test/required data for `si_biller` table - no constraints
--
INSERT INTO `si_biller` (`id`, `domain_id`, `name`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `signature`, `logo`, `footer`, `paypal_business_name`, `paypal_notify_url`,
                         `paypal_return_url`, `eway_customer_id`, `paymentsgateway_api_id`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`)
VALUES (1, 1, 'Mr Plough', '43 Evergreen Terrace', '', 'Springfield', 'NY', '90245', '', '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@mrplough.com', '', 'ubuntulogo.png', '', '', '', '', '', '', '', '', '', '7898-87987-87', '', '1');

--
-- Test/required data for `si_custom_fields` table - no constraints
--
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`)
VALUES (1, 'biller_cf1', NULL, '0', 1)
     , (2, 'biller_cf2', NULL, '0', 1)
     , (3, 'biller_cf3', NULL, '0', 1)
     , (4, 'biller_cf4', NULL, '0', 1)
     , (5, 'customer_cf1', NULL, '0', 1)
     , (6, 'customer_cf2', NULL, '0', 1)
     , (7, 'customer_cf3', NULL, '0', 1)
     , (8, 'customer_cf4', NULL, '0', 1)
     , (9, 'product_cf1', NULL, '0', 1)
     , (10, 'product_cf2', NULL, '0', 1)
     , (11, 'product_cf3', NULL, '0', 1)
     , (12, 'product_cf4', NULL, '0', 1)
     , (13, 'invoice_cf1', NULL, '0', 1)
     , (14, 'invoice_cf2', NULL, '0', 1)
     , (15, 'invoice_cf3', NULL, '0', 1)
     , (16, 'invoice_cf4', NULL, '0', 1);

--
-- Test/required data for `si_custom_flags` table - no constraints
--
INSERT INTO `si_custom_flags` (`domain_id`, `associated_table`, `flg_id`, `field_label`, `enabled`, `field_help`)
VALUES (1, 'products', 1, '', 0, ''),
       (1, 'products', 2, '', 0, ''),
       (1, 'products', 3, '', 0, ''),
       (1, 'products', 4, '', 0, ''),
       (1, 'products', 5, '', 0, ''),
       (1, 'products', 6, '', 0, ''),
       (1, 'products', 7, '', 0, ''),
       (1, 'products', 8, '', 0, ''),
       (1, 'products', 9, '', 0, ''),
       (1, 'products', 10, '', 0, '');

--
-- Test/required data for `si_customers` table - no constraints
--
INSERT INTO `si_customers` (`id`, `domain_id`, `attention`, `name`, `department`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `credit_card_holder_name`, `credit_card_number`,
                            `credit_card_expiry_month`, `credit_card_expiry_year`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`)
VALUES (1, 1, 'Moe Sivloski', 'Moes Tavern', '', '45 Main Road', '', 'Springfield', 'NY', '65891', '', '04 1234 5698', '', '04 5689 4566', 'moe@moestavern.com', '', '', '', '',
        '<p><strong>Moe&#39;s Tavern</strong> is a fictional <a href=&#39;http://en.wikipedia.org/wiki/Bar_%28establishment%29&#39; title=&#39;Bar (establishment)&#39;>bar</a> seen on <em><a href=&#39;http://en.wikipedia.org/wiki/The_Simpsons&#39; title=&#39;The Simpsons&#39;>The Simpsons</a></em>. The owner of the bar is <a href=&#39;http://en.wikipedia.org/wiki/Moe_Szyslak&#39; title=&#39;Moe Szyslak&#39;>Moe Szyslak</a>.</p> <p>In The Simpsons world, it is located on the corner of Walnut Street, neighboring King Toot&#39;s Music Store, across the street is the Moeview Motel, and a factory formerly owned by <a href=&#39;http://en.wikipedia.org/wiki/Bart_Simpson&#39; title=&#39;Bart Simpson&#39;>Bart Simpson</a>, until it collapsed. The inside of the bar has a few pool tables and a dartboard. It is very dank and &quot;smells like <a href=&#39;http://en.wikipedia.org/wiki/Urine&#39; title=&#39;Urine&#39;>tinkle</a>.&quot; Because female customers are so rare, Moe frequently uses the women&#39;s restroom as an office. Moe claimed that there haven&#39;t been any ladies at Moe&#39;s since <a href=&#39;http://en.wikipedia.org/wiki/1979&#39; title=&#39;1979&#39;>1979</a> (though earlier episodes show otherwise). A jar of pickled eggs perpetually stands on the bar. Another recurring element is a rat problem. This can be attributed to the episode <a href=&#39;http://en.wikipedia.org/wiki/Homer%27s_Enemy&#39; title=&#39;Homer&#39;s Enemy&#39;>Homer&#39;s Enemy</a> in which Bart&#39;s factory collapses, and the rats are then shown to find a new home at Moe&#39;s. In &quot;<a href=&#39;http://en.wikipedia.org/wiki/Who_Shot_Mr._Burns&#39; title=&#39;Who Shot Mr. Burns&#39;>Who Shot Mr. Burns</a>,&quot; Moe&#39;s Tavern was forced to close down because Mr. Burns&#39; slant-drilling operation near the tavern caused unsafe pollution. It was stated in the &quot;<a href=&#39;http://en.wikipedia.org/wiki/Flaming_Moe%27s&#39; title=&#39;Flaming Moe&#39;s&#39;>Flaming Moe&#39;s</a>&quot; episode that Moe&#39;s Tavern was on Walnut Street. The phone number would be 76484377, since in &quot;<a href=&#39;http://en.wikipedia.org/wiki/Homer_the_Smithers&#39; title=&#39;Homer the Smithers&#39;>Homer the Smithers</a>,&quot; Mr. Burns tried to call Smithers but did not know his phone number. He tried the buttons marked with the letters for Smithers and called Moe&#39;s. In &quot;<a href=&#39;http://en.wikipedia.org/wiki/Principal_Charming&#39; title=&#39;Principal Charming&#39;>Principal Charming</a>&quot; Bart is asked to call Homer by Principal Skinner, the number visible on the card is WORK: KLondike 5-6832 HOME: KLondike 5-6754 MOE&#39;S TAVERN: KLondike 5-1239 , Moe answers the phone and Bart asks for Homer Sexual. The bar serves <a href=&#39;http://en.wikipedia.org/wiki/Duff_Beer&#39; title=&#39;Duff Beer&#39;>Duff Beer</a> and Red Tick Beer, a beer flavored with dogs.</p>',
        '', '', '', '', '1');

--
-- Test/required data for `si_extensions` table - no constraints
--
INSERT INTO `si_extensions` (`id`, `domain_id`, `name`, `description`, `enabled`)
VALUES (1, 0, 'core', 'Core part of SimpleInvoices - always enabled', '1');

--
-- Test/required data for `si_index` table - no constraints
--
INSERT INTO `si_index` (`id`, `node`, `sub_node`, `sub_node_2`, `domain_id`)
VALUES (1, 'invoice', '1', '', 1);

--
-- Test/required data for `si_invoice_type` table - no constraints
--
INSERT INTO `si_invoice_type` (`inv_ty_id`, `inv_ty_description`)
VALUES (1, 'Total')
     , (2, 'Itemized')
     , (3, 'Consulting');

--
-- Test/required data for `si_payment_types` table - no constraints
--
INSERT INTO `si_payment_types` (`pt_id`, `domain_id`, `pt_description`, `pt_enabled`)
VALUES (1, 1, 'Cash', '1')
     , (2, 1, 'Credit Card', '1');

--
-- Test/required data for `si_preferences` table - no constraints
--
INSERT INTO `si_preferences` (`pref_id`, `domain_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`,
                              `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`, `status`, `locale`, `language`, `index_group`, `currency_code`, `include_online_payment`, `currency_position`,
                              `set_aging`)
VALUES (1, 1, 'Invoice', '$', 'Invoice', 'Invoice', 'Details', 'Payment is to be made within 14 days of the invoice being sent', 'Electronic Funds Transfer', 'Account name', 'H. & M. Simpson', 'Account number:', '0123-4567-7890', '1', 1, 'en_US', 'en_US',
        1, 'USD', NULL, 'left', 1);

--
-- Test/required data for `si_products_attribute_type` table - no constraints
--
INSERT INTO `si_products_attribute_type`
VALUES ('1', 'list')
     , ('2', 'decimal')
     , ('3', 'free');

--
-- Test/required data for `si_sql_patchmanager` table - no constraints
--

INSERT INTO `si_sql_patchmanager` (`sql_id`, `sql_patch_ref`, `sql_patch`, `sql_release`, `sql_statement`, `source`) VALUES
(1, 1, 'Create sql_patchmanger table', '20060514', 'CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 255 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) ENGINE = MYISAM ', 'original'),
(2, 2, '', '', '', 'original'),
(3, 3, '', '', '', 'original'),
(4, 4, '', '', '', 'original'),
(5, 5, '', '', '', 'original'),
(6, 6, '', '', '', 'original'),
(7, 7, '', '', '', 'original'),
(8, 8, '', '', '', 'original'),
(9, 9, '', '', '', 'original'),
(10, 10, '', '', '', 'original'),
(11, 11, '', '', '', 'original'),
(12, 12, '', '', '', 'original'),
(13, 13, '', '', '', 'original'),
(14, 14, '', '', '', 'original'),
(15, 15, '', '', '', 'original'),
(16, 16, '', '', '', 'original'),
(17, 17, '', '', '', 'original'),
(18, 18, '', '', '', 'original'),
(19, 19, '', '', '', 'original'),
(20, 20, '', '', '', 'original'),
(21, 21, '', '', '', 'original'),
(22, 22, '', '', '', 'original'),
(23, 23, '', '', '', 'original'),
(24, 24, '', '', '', 'original'),
(25, 25, '', '', '', 'original'),
(26, 26, '', '', '', 'original'),
(27, 27, '', '', '', 'original'),
(28, 28, '', '', '', 'original'),
(29, 29, '', '', '', 'original'),
(30, 30, '', '', '', 'original'),
(31, 31, '', '', '', 'original'),
(32, 32, '', '', '', 'original'),
(33, 33, '', '', '', 'original'),
(34, 34, '', '', '', 'original'),
(35, 35, '', '', '', 'original'),
(36, 36, '', '', '', 'original'),
(37, 0, 'Start', '20060514', '', 'original'),
(38, 37, '', '', '', 'original'),
(39, 38, '', '', '', 'original'),
(40, 39, '', '', '', 'original'),
(41, 40, '', '', '', 'original'),
(42, 41, '', '', '', 'original'),
(43, 42, '', '', '', 'original'),
(44, 43, '', '', '', 'original'),
(45, 44, '', '', '', 'original'),
(46, 45, '', '', '', 'original'),
(47, 46, '', '', '', 'original'),
(48, 47, '', '', '', 'original'),
(49, 48, '', '', '', 'original'),
(50, 49, '', '', '', 'original'),
(51, 50, '', '', '', 'original'),
(52, 51, '', '', '', 'original'),
(53, 52, '', '', '', 'original'),
(54, 53, '', '', '', 'original'),
(599, 54, '', '', '', 'original'),
(600, 55, '', '', '', 'original'),
(601, 56, '', '', '', 'original'),
(602, 57, '', '', '', 'original'),
(603, 58, '', '', '', 'original'),
(604, 59, '', '', '', 'original'),
(605, 60, '', '', '', 'original'),
(606, 61, '', '', '', 'original'),
(607, 62, '', '', '', 'original'),
(608, 63, '', '', '', 'original'),
(609, 64, '', '', '', 'original'),
(610, 65, '', '', '', 'original'),
(611, 66, '', '', '', 'original'),
(612, 67, '', '', '', 'original'),
(613, 68, '', '', '', 'original'),
(614, 69, '', '', '', 'original'),
(615, 70, '', '', '', 'original'),
(616, 71, '', '', '', 'original'),
(617, 72, '', '', '', 'original'),
(618, 73, '', '', '', 'original'),
(619, 74, '', '', '', 'original'),
(620, 75, '', '', '', 'original'),
(621, 76, '', '', '', 'original'),
(622, 77, '', '', '', 'original'),
(623, 78, '', '', '', 'original'),
(624, 79, '', '', '', 'original'),
(625, 80, '', '', '', 'original'),
(626, 81, '', '', '', 'original'),
(627, 82, '', '', '', 'original'),
(628, 83, '', '', '', 'original'),
(629, 84, '', '', '', 'original'),
(630, 85, '', '', '', 'original'),
(631, 86, '', '', '', 'original'),
(632, 87, '', '', '', 'original'),
(633, 88, '', '', '', 'original'),
(634, 89, '', '', '', 'original'),
(635, 90, '', '', '', 'original'),
(636, 91, '', '', '', 'original'),
(637, 92, '', '', '', 'original'),
(638, 93, '', '', '', 'original'),
(639, 94, '', '', '', 'original'),
(640, 95, '', '', '', 'original'),
(641, 96, '', '', '', 'original'),
(642, 97, '', '', '', 'original'),
(643, 98, '', '', '', 'original'),
(644, 99, '', '', '', 'original'),
(645, 100, '', '', '', 'original'),
(646, 101, '', '', '', 'original'),
(647, 102, '', '', '', 'original'),
(648, 103, '', '', '', 'original'),
(649, 104, '', '', '', 'original'),
(650, 105, '', '', '', 'original'),
(651, 106, '', '', '', 'original'),
(652, 107, '', '', '', 'original'),
(653, 108, '', '', '', 'original'),
(654, 109, '', '', '', 'original'),
(655, 110, '', '', '', 'original'),
(656, 111, '', '', '', 'original'),
(657, 112, '', '', '', 'original'),
(658, 113, '', '', '', 'original'),
(659, 114, '', '', '', 'original'),
(660, 115, '', '', '', 'original'),
(661, 116, '', '', '', 'original'),
(662, 117, '', '', '', 'original'),
(663, 118, '', '', '', 'original'),
(664, 119, '', '', '', 'original'),
(665, 120, '', '', '', 'original'),
(666, 121, '', '', '', 'original'),
(667, 122, '', '', '', 'original'),
(668, 123, '', '', '', 'original'),
(669, 124, '', '', '', 'original'),
(670, 125, '', '', '', 'original'),
(671, 126, '', '', '', 'original'),
(672, 127, '', '', '', 'original'),
(673, 128, '', '', '', 'original'),
(674, 129, '', '', '', 'original'),
(675, 130, '', '', '', 'original'),
(676, 131, '', '', '', 'original'),
(677, 132, '', '', '', 'original'),
(678, 133, '', '', '', 'original'),
(679, 134, '', '', '', 'original'),
(680, 135, '', '', '', 'original'),
(681, 136, '', '', '', 'original'),
(682, 137, '', '', '', 'original'),
(683, 138, '', '', '', 'original'),
(684, 139, '', '', '', 'original'),
(685, 140, '', '', '', 'original'),
(686, 141, '', '', '', 'original'),
(687, 142, '', '', '', 'original'),
(688, 143, '', '', '', 'original'),
(689, 144, '', '', '', 'original'),
(690, 145, '', '', '', 'original'),
(691, 146, '', '', '', 'original'),
(692, 147, '', '', '', 'original'),
(693, 148, '', '', '', 'original'),
(694, 149, '', '', '', 'original'),
(695, 150, '', '', '', 'original'),
(696, 151, '', '', '', 'original'),
(697, 152, '', '', '', 'original'),
(698, 153, '', '', '', 'original'),
(699, 154, '', '', '', 'original'),
(700, 155, '', '', '', 'original'),
(701, 156, '', '', '', 'original'),
(702, 157, '', '', '', 'original'),
(703, 158, '', '', '', 'original'),
(704, 159, '', '', '', 'original'),
(705, 160, '', '', '', 'original'),
(706, 161, '', '', '', 'original'),
(707, 162, '', '', '', 'original'),
(708, 163, '', '', '', 'original'),
(709, 164, '', '', '', 'original'),
(710, 165, '', '', '', 'original'),
(711, 166, '', '', '', 'original'),
(712, 167, '', '', '', 'original'),
(713, 168, '', '', '', 'original'),
(714, 169, '', '', '', 'original'),
(715, 170, '', '', '', 'original'),
(716, 171, '', '', '', 'original'),
(717, 172, '', '', '', 'original'),
(718, 173, '', '', '', 'original'),
(719, 174, '', '', '', 'original'),
(720, 175, '', '', '', 'original'),
(721, 176, '', '', '', 'original'),
(722, 177, '', '', '', 'original'),
(723, 178, '', '', '', 'original'),
(724, 179, '', '', '', 'original'),
(725, 180, '', '', '', 'original'),
(726, 181, '', '', '', 'original'),
(727, 182, '', '', '', 'original'),
(728, 183, '', '', '', 'original'),
(729, 184, '', '', '', 'original'),
(730, 185, '', '', '', 'original'),
(731, 186, '', '', '', 'original'),
(732, 187, '', '', '', 'original'),
(733, 188, '', '', '', 'original'),
(734, 189, '', '', '', 'original'),
(735, 190, '', '', '', 'original'),
(736, 191, '', '', '', 'original'),
(737, 192, '', '', '', 'original'),
(738, 193, '', '', '', 'original'),
(739, 194, '', '', '', 'original'),
(740, 195, '', '', '', 'original'),
(741, 196, '', '', '', 'original'),
(742, 197, '', '', '', 'original'),
(743, 198, '', '', '', 'original'),
(744, 199, '', '', '', 'original'),
(745, 200, 'Update extensions table', '20090529', 'UPDATE si_extensions SET id = 0 WHERE name = core LIMIT 1', 'original'),
(746, 201, 'Set domain_id on system defaults table to 1', '20090622', 'UPDATE si_system_defaults SET domain_id = 1', 'original'),
(747, 202, 'Set extension_id on system defaults table to 1', '20090622', 'UPDATE si_system_defaults SET extension_id = 1', 'original'),
(748, 203, 'Move all old consulting style invoices to itemised', '20090704', 'UPDATE si_invoices SET type_id = 2 where type_id = 3', 'original'),
(749, 204, '', '', '', 'original'),
(750, 205, '', '', '', 'original'),
(751, 206, '', '', '', 'original'),
(752, 207, '', '', '', 'original'),
(753, 208, '', '', '', 'original'),
(754, 209, '', '', '', 'original'),
(755, 210, '', '', '', 'original'),
(756, 210, '', '', '', 'original'),
(757, 211, '', '', '', 'original'),
(758, 212, '', '', '', 'original'),
(759, 213, '', '', '', 'original'),
(760, 214, '', '', '', 'original'),
(761, 215, '', '', '', 'original'),
(762, 216, '', '', '', 'original'),
(763, 217, '', '', '', 'original'),
(764, 218, '', '', '', 'original'),
(765, 219, '', '', '', 'original'),
(766, 220, '', '', '', 'original'),
(767, 221, '', '', '', 'original'),
(768, 222, '', '', '', 'original'),
(769, 223, '', '', '', 'original'),
(770, 224, '', '', '', 'original'),
(771, 225, '', '', '', 'original'),
(772, 226, '', '', '', 'original'),
(773, 227, '', '', '', 'original'),
(774, 228, '', '', '', 'original'),
(775, 229, '', '', '', 'original'),
(776, 230, '', '', '', 'original'),
(777, 231, '', '', '', 'original'),
(778, 232, '', '', '', 'original'),
(779, 233, '', '', '', 'original'),
(780, 234, '', '', '', 'original'),
(781, 235, '', '', '', 'original'),
(782, 236, '', '', '', 'original'),
(783, 237, '', '', '', 'original'),
(784, 238, '', '', '', 'original'),
(785, 239, '', '', '', 'original'),
(786, 240, '', '', '', 'original'),
(787, 241, '', '', '', 'original'),
(788, 242, '', '', '', 'original'),
(789, 243, '', '', '', 'original'),
(790, 244, '', '', '', 'original'),
(791, 245, '', '', '', 'original'),
(792, 246, '', '', '', 'original'),
(793, 247, '', '', '', 'original'),
(794, 248, '', '', '', 'original'),
(795, 249, '', '', '', 'original'),
(796, 250, '', '', '', 'original'),
(797, 251, '', '', '', 'original'),
(798, 252, 'Language - reset to en_GB - due to folder renaming', '20100419', 'UPDATE `si_system_defaults` SET value =\'en_GB\' where name=\'language\';', 'original'),
(799, 253, 'Add PaymentsGateway API ID field', '20110918', 'ALTER TABLE `si_biller` ADD  `paymentsgateway_api_id` VARCHAR( 255 ) NULL AFTER `eway_customer_id`;', 'original'),
(800, 254, 'Product Matrix - update line items table', '20130313', 'ALTER TABLE `si_invoice_items` ADD `attribute` VARCHAR( 255 ) NULL ;', 'original'),
(801, 255, 'Product Matrix - update line items table', '20130313', ' \n        CREATE TABLE `si_products_attributes` (\n            `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,\n            `name` VARCHAR( 255 ) NOT NULL,\n            `type_id` VARCHAR( 255 ) NOT NULL\n            ) ENGINE = MYISAM ;', 'original'),
(802, 256, 'Product Matrix - update line items table', '20130313', 'INSERT INTO `si_products_attributes` (`id`, `name`, `type_id`) VALUES (NULL, \'Size\',\'1\'), (NULL,\'Colour\',\'1\');', 'original'),
(803, 257, 'Product Matrix - update line items table', '20130313', 'CREATE TABLE `si_products_values` (\n`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,\n`attribute_id` INT( 11 ) NOT NULL ,\n`value` VARCHAR( 255 ) NOT NULL\n) ENGINE = MYISAM ;', 'original'),
(804, 258, 'Product Matrix - update line items table', '20130313', 'INSERT INTO `si_products_values` (`id`, `attribute_id`,`value`) VALUES (NULL,\'1\', \'S\'),  (NULL,\'1\', \'M\'), (NULL,\'1\', \'L\'),  (NULL,\'2\', \'Red\'),  (NULL,\'2\', \'White\');', 'original'),
(805, 259, 'Product Matrix - update line items table', '20130313', 'SELECT 1+1;', 'original'),
(806, 260, 'Product Matrix - update line items table', '20130313', 'SELECT 1+1;', 'original'),
(807, 261, 'Product Matrix - update line items table', '20130313', 'SELECT 1+1;', 'original'),
(808, 262, 'Add product attributes system preference', '20130313', 'INSERT INTO si_system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , \'product_attributes\', \'0\', \'1\', \'1\');', 'original'),
(809, 263, 'Product Matrix - update line items table', '20130313', 'ALTER TABLE `si_products` ADD `attribute` VARCHAR( 255 ) NULL ;', 'original'),
(810, 264, 'Product - use notes as default line item description', '20130314', 'ALTER TABLE `si_products` ADD `notes_as_description` VARCHAR( 1 ) NULL ;', 'original'),
(811, 265, 'Product - expand/show line item description', '20130314', 'ALTER TABLE `si_products` ADD `show_description` VARCHAR( 1 ) NULL ;', 'original'),
(812, 266, 'Product - expand/show line item description', '20130322', 'CREATE TABLE `si_products_attribute_type` (\n            `id` int(11) NOT NULL AUTO_INCREMENT,\n                `name` varchar(255) NOT NULL,\n                  PRIMARY KEY (`id`)\n              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;', 'original'),
(813, 267, 'Product Matrix - insert attribute types', '20130325', 'INSERT INTO `si_products_attribute_type` (`id`, `name`) VALUES (NULL,\'list\'),  (NULL,\'decimal\'), (NULL,\'free\');', 'original'),
(814, 268, 'Product Matrix - insert attribute types', '20130327', 'ALTER TABLE  `si_products_attributes` ADD  `enabled` VARCHAR( 1 ) NULL DEFAULT  \'1\',\n        ADD  `visible` VARCHAR( 1 ) NULL DEFAULT  \'1\';', 'original'),
(815, 269, 'Product Matrix - insert attribute types', '20130327', 'ALTER TABLE  `si_products_values` ADD  `enabled` VARCHAR( 1 ) NULL DEFAULT  \'1\';', 'original'),
(816, 270, 'Make Simple Invoices faster - add index', '20100419', 'ALTER TABLE `si_payment` ADD INDEX(`ac_inv_id`);', 'original'),
(817, 271, 'Make Simple Invoices faster - add index', '20100419', 'ALTER TABLE `si_payment` ADD INDEX(`ac_amount`);', 'original'),
(818, 272, 'Add product attributes system preference', '20130313', 'INSERT INTO si_system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , \'large_dataset\', \'0\', \'1\', \'1\');', 'original'),
(819, 273, 'Make Simple Invoices faster - add index', '20130927', 'ALTER TABLE `si_invoice_items` ADD INDEX(`invoice_id`);', 'original'),
(820, 274, 'Only One Default Variable name per domain allowed - add unique index', '20131007', 'ALTER TABLE `si_system_defaults` ADD UNIQUE INDEX `UnqNameInDomain` (`domain_id`, `name`);', 'original'),
(821, 275, 'Make EMail / Password pair unique per domain - add unique index', '20131007', 'ALTER TABLE `si_user` CHANGE `password` `password` VARCHAR(64) NULL, ADD UNIQUE INDEX `UnqEMailPwd` (`email`, `password`);', 'original'),
(822, 276, 'Each invoice Item must belong to a specific Invoice with a specific domain_id', '20131008', 'ALTER TABLE `si_invoice_items` ADD COLUMN `domain_id` INT NOT NULL DEFAULT \'1\' AFTER `invoice_id`;', 'original'),
(823, 277, 'Add Index for Quick Invoice Item Search for a domain_id', '20131008', 'ALTER TABLE `si_invoice_items` ADD INDEX `DomainInv` (`invoice_id`, `domain_id`);', 'original'),
(824, 278, 'Each Invoice Item can have only one instance of each tax', '20131008', 'SELECT 1+1;', 'original'),
(825, 279, 'Drop unused superceeded table si_product_matrix if present', '20131009', 'DROP TABLE IF EXISTS `si_products_matrix`;', 'original'),
(826, 280, 'Each domain has their own extension instances', '20131011', 'ALTER TABLE `si_extensions` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `domain_id`);', 'original'),
(827, 281, 'Each domain has their own custom_field id sets', '20131011', 'ALTER TABLE `si_custom_fields` DROP PRIMARY KEY, ADD PRIMARY KEY (`cf_id`, `domain_id`);', 'original'),
(828, 282, 'Each domain has their own logs', '20131011', 'ALTER TABLE `si_log` ADD COLUMN `domain_id` INT NOT NULL DEFAULT \'1\' AFTER `id`, DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `domain_id`);', 'original'),
(829, 283, 'Match field type with foreign key field si_user.id', '20131012', 'ALTER TABLE `si_log` CHANGE `userid` `userid` INT NOT NULL DEFAULT \'1\';', 'original'),
(830, 284, 'Make si_index sub_node and sub_node_2 fields as integer', '20131016', 'ALTER TABLE `si_index` CHANGE `node` `node` VARCHAR(64) NOT NULL, CHANGE `sub_node` `sub_node` INT NOT NULL, CHANGE `sub_node_2` `sub_node_2` INT NOT NULL;', 'original'),
(831, 285, 'Fix compound Primary Key for si_index table', '20131016', 'ALTER TABLE `si_index` ADD PRIMARY KEY (`node`, `sub_node`, `sub_node_2`, `domain_id`);', 'original'),
(832, 286, 'Speedup lookups from si_index table with indices in si_invoices table', '20131016', 'ALTER TABLE `si_invoices` ADD UNIQUE INDEX `UniqDIB` (`index_id`, `preference_id`, `biller_id`, `domain_id`), ADD INDEX `IdxDI` (`index_id`, `preference_id`, `domain_id`);', 'original'),
(833, 287, 'Populate additional user roles like domain_administrator', '20131017', 'INSERT IGNORE INTO `si_user_role` (`name`) VALUES (\'domain_administrator\'), (\'customer\'), (\'biller\');', 'original'),
(834, 288, 'Fully relational now - do away with the si_index table', '20131017', 'SELECT 1+1;', 'original'),
(835, 289, 'Each cron_id can run a maximum of only once a day for each domain_id', '20131108', 'ALTER TABLE `si_cron_log` ADD UNIQUE INDEX `CronIdUnq` (`domain_id`, `cron_id`, `run_date`);', 'original'),
(836, 290, 'Set all Flag fields to tinyint(1) and other 1 byte fields to char', '20131109', '\n		ALTER TABLE `si_biller` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;\n		ALTER TABLE `si_customers` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;\n		ALTER TABLE `si_extensions` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 0 NOT NULL;\n		ALTER TABLE `si_payment_types` CHANGE `pt_enabled` `pt_enabled` TINYINT(1) DEFAULT 1 NOT NULL;\n		ALTER TABLE `si_preferences` CHANGE `pref_enabled` `pref_enabled` TINYINT(1) DEFAULT 1 NOT NULL,\n			CHANGE `status` `status` TINYINT(1) NOT NULL;\n		ALTER TABLE `si_products` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL,\n			CHANGE `notes_as_description` `notes_as_description` CHAR(1) NULL,\n			CHANGE `show_description` `show_description` CHAR(1) NULL;\n		ALTER TABLE `si_tax` CHANGE `tax_enabled` `tax_enabled` TINYINT(1) DEFAULT 1 NOT NULL;\n		ALTER TABLE `si_cron` CHANGE `email_biller` `email_biller` TINYINT(1) DEFAULT 0 NOT NULL,\n			CHANGE `email_customer` `email_customer` TINYINT(1) DEFAULT 0 NOT NULL;\n		ALTER TABLE `si_custom_fields` CHANGE `cf_display` `cf_display` TINYINT(1) DEFAULT 1 NOT NULL;\n		ALTER TABLE `si_invoice_item_tax` CHANGE `tax_type` `tax_type` CHAR(1) DEFAULT \'%\' NOT NULL;\n		ALTER TABLE `si_tax` CHANGE `type` `type` CHAR(1) DEFAULT \'%\' NOT NULL;\n		ALTER TABLE `si_products_attributes` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL,\n			CHANGE `visible` `visible` TINYINT(1) DEFAULT 1 NOT NULL;\n		ALTER TABLE `si_products_values` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL; \n		ALTER TABLE `si_user` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL; \n	', 'original'),
(837, 291, 'Clipped size of zip_code and credit_card_number fields to realistic values', '20131111', '\n		ALTER TABLE `si_customers` CHANGE `zip_code` `zip_code` VARCHAR(20) NULL,\n		CHANGE `credit_card_number` `credit_card_number` VARCHAR(20) NULL;\n		ALTER TABLE `si_biller` CHANGE `zip_code` `zip_code` VARCHAR(20) NULL;\n	', 'original'),
(838, 292, 'Added Customer/Biller User ID column to user table', '20140103', 'ALTER TABLE `si_user` ADD COLUMN `user_id` INT  DEFAULT 0 NOT NULL AFTER `enabled`;', 'original'),
(839, 293, 'Add Signature field to the biller table.', '20180921', 'DELETE IGNORE FROM `si_extensions` WHERE `name` = \'signature_field\';', 'original'),
(840, 294, 'Add custom_flags table for products.', '20180922', 'DELETE IGNORE FROM `si_extensions` WHERE `name` = \'custom_flags\';', 'fearless359'),
(841, 295, 'Add net income report.', '20180923', 'DELETE IGNORE FROM `si_extensions` WHERE `name` = \'net_income_report\';', 'fearless359'),
(842, 296, 'Add past due report.', '20180924', 'DELETE IGNORE FROM `si_extensions` WHERE `name` = \'past_due_report\';', 'fearless359'),
(843, 297, 'Add User Security enhancement fields and values', '20180924', 'UPDATE `si_system_defaults` SET `extension_id` = 1 WHERE `name` IN\n                            (\'company_logo\',\'company_name\',\'company_name_item\',\'password_min_length\',\'password_lower\',\'password_number\',\'password_special\',\'password_upper\',\'session_timeout\');                                \n                       DELETE IGNORE FROM `si_extensions` WHERE `name` = \'user_security\';', 'fearless359'),
(844, 298, 'Add Signature field to the biller table.', '20181003', 'DELETE IGNORE FROM `si_extensions` WHERE `name` = \'signature_field\';', 'fearless359'),
(845, 299, 'Add check number field to the payment table.', '20181003', 'DELETE IGNORE FROM `si_extensions` WHERE `name` = \'payments\';', 'fearless359'),
(846, 300, 'Add install complete table.', '20181008', 'CREATE TABLE `si_install_complete` (\n                                            `completed` tinyint(1) NOT NULL COMMENT \'Flag SI install has completed\'\n                                            ) ENGINE=InnoDB COMMENT=\'Specifies an allowed setting for a flag field\';\n                               INSERT INTO `si_install_complete` (completed) VALUES (1);', 'fearless359'),
(847, 301, 'Add last_activity_date, aging_date and aging_value to invoices.', '20181012', 'ALTER TABLE `si_invoices` ADD `last_activity_date` DATETIME DEFAULT \'2000-12-31 00:00:00\' NOT NULL COMMENT \'Date last activity update to the invoice\', ADD `aging_date` DATETIME DEFAULT \'2000-12-30 00:00:00\' NOT NULL COMMENT \'Date aging was last calculated\', ADD `age_days` SMALLINT(5) UNSIGNED DEFAULT 0 NOT NULL COMMENT \'Age of invoice balance\', ADD `aging` VARCHAR(5) DEFAULT \'\' NOT NULL COMMENT \'Aging string (1-14, 15-30, etc.\';DELETE IGNORE FROM `si_system_defaults` WHERE `name` = \'large_dataset\';', 'fearless359'),
(848, 302, 'Added owing to invoices table', '20181017', 'ALTER TABLE `si_invoices` ADD COLUMN `owing` DECIMAL(25,6) DEFAULT 0 NOT NULL COMMENT \'Amount owing as of aging-date\' AFTER `note`;UPDATE `si_invoices` SET `owing` = 1;', 'fearless359'),
(849, 303, 'Add invoice custom field report extension to standard application and add sales_representative field.', '20181018', 'ALTER TABLE `si_invoices` ADD `sales_representative` VARCHAR(50) DEFAULT \'\' NOT NULL;', 'fearless359'),
(850, 304, 'Add default_invoice field to the customers table.', '20181020', 'ALTER TABLE `si_customers` ADD `default_invoice` INT(10) UNSIGNED DEFAULT 0 NOT NULL COMMENT \'Invoice index_id value to use as the default template\' AFTER `notes`;', 'fearless359'),
(851, 305, 'Add expense tables to the database.', '20181027', 'CREATE TABLE `si_expense` (id INT(11) NOT NULL AUTO_INCREMENT UNIQUE KEY, domain_id INT(11) NOT NULL, amount DECIMAL(25,6) NOT NULL, expense_account_id INT(11) NOT NULL, biller_id INT(11) NOT NULL, customer_id INT(11) NOT NULL, invoice_id INT(11) NOT NULL, product_id INT(11) NOT NULL, date DATE NOT NULL, note TEXT NOT NULL) ENGINE = InnoDb;ALTER TABLE `si_expense` ADD PRIMARY KEY (domain_id, id);CREATE TABLE `si_expense_account` (id INT(11) NOT NULL AUTO_INCREMENT UNIQUE KEY, domain_id INT(11) NOT NULL, name VARCHAR(255) NOT NULL) ENGINE = InnoDb;ALTER TABLE `si_expense_account` ADD PRIMARY KEY (domain_id, id);CREATE TABLE `si_expense_item_tax` (id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, expense_id INT(11) NOT NULL, tax_id INT(11) NOT NULL, tax_type VARCHAR(1) NOT NULL, tax_rate DECIMAL(25, 6) NOT NULL, tax_amount DECIMAL(25, 6) NOT NULL) ENGINE = MYISAM;ALTER TABLE `si_expense` ADD status TINYINT(1) NOT NULL;INSERT INTO `si_system_defaults` (`name`, `value`, `domain_id`, `extension_id`) VALUES (\'expense\', 0, 1, 1) ;DELETE IGNORE FROM `si_extensions` WHERE `name` = \'expense\';', 'fearless359'),
(852, 306, 'Clean up default_tax_id and default_tax_id_2 for products', '20190329', 'UPDATE `si_products` SET `default_tax_id` = NULL WHERE `default_tax_id` = 0;UPDATE `si_products` SET `default_tax_id_2` = NULL WHERE `default_tax_id_2` = 0;', 'fearless359'),
(853, 307, 'Fix cron_log cron_id data type', '20190329', 'DROP INDEX `CronIdUnq` ON `si_cron_log`;ALTER TABLE `si_cron_log` MODIFY `cron_id` INT(11) NULL;UPDATE `si_cron_log` SET `cron_id` = NULL WHERE `cron_id` = 0;ALTER TABLE `si_cron_log` ADD UNIQUE KEY `CronIdUnq`  (`domain_id`,`cron_id`,`run_date`);', 'fearless359'),
(854, 308, 'Remove dup id key from invoice_item_attachments and fix products_attributes type_id data type', '20190329', 'ALTER TABLE `si_invoice_item_attachments` DROP INDEX `id`;ALTER TABLE `si_products_attributes` MODIFY `type_id` INT(11) NULL;', 'fearless359'),
(855, 309, 'Rename userid to user_id in log table', '20190329', 'ALTER TABLE `si_log` CHANGE `userid` `user_id` INT(11) NULL;', 'fearless359'),
(856, 310, 'Make record unique id fields consistent in size and properties', '20190329', 'ALTER TABLE `si_biller` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_invoices` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_invoice_item_attachments` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_payment` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;', 'fearless359'),
(857, 311, 'Remove default from all id fields.', '20190329', 'ALTER TABLE `si_biller` ALTER `id` DROP DEFAULT;ALTER TABLE `si_invoices` ALTER `biller_id` DROP DEFAULT;ALTER TABLE `si_invoices` ALTER `customer_id` DROP DEFAULT;ALTER TABLE `si_invoices` ALTER `type_id` DROP DEFAULT;ALTER TABLE `si_invoices` ALTER `preference_id` DROP DEFAULT;ALTER TABLE `si_invoice_items` ALTER `invoice_id` DROP DEFAULT;ALTER TABLE `si_invoice_items` ALTER `product_id` DROP DEFAULT;ALTER TABLE `si_payment` ALTER `ac_payment_type` DROP DEFAULT;ALTER TABLE `si_system_defaults` ALTER `extension_id` DROP DEFAULT;', 'fearless359'),
(858, 312, 'Remove default from domain_id.', '20190329', 'ALTER TABLE `si_biller` ALTER `domain_id` DROP DEFAULT;ALTER TABLE `si_invoices` ALTER `biller_id` DROP DEFAULT;ALTER TABLE `si_invoices` ALTER `customer_id` DROP DEFAULT;ALTER TABLE `si_invoices` ALTER `type_id` DROP DEFAULT;ALTER TABLE `si_invoices` ALTER `preference_id` DROP DEFAULT;ALTER TABLE `si_invoice_items` ALTER `invoice_id` DROP DEFAULT;ALTER TABLE `si_invoice_items` ALTER `product_id` DROP DEFAULT;ALTER TABLE `si_payment` ALTER `ac_payment_type` DROP DEFAULT;ALTER TABLE `si_system_defaults` ALTER `extension_id` DROP DEFAULT;', 'fearless359'),
(859, 313, 'Use common characteristics for all auto increment fields.', '20190329', 'ALTER TABLE `si_cron` MODIFY `invoice_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_expense` MODIFY `expense_account_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_expense` MODIFY `biller_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_expense` MODIFY `customer_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_expense` MODIFY `invoice_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_expense` MODIFY `product_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_expense_item_tax` MODIFY `expense_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_expense_item_tax` MODIFY `tax_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_inventory` MODIFY `product_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_invoices` MODIFY `biller_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_invoices` MODIFY `customer_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_invoices` MODIFY `type_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_invoices` MODIFY `preference_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_invoice_items` MODIFY `invoice_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_invoice_items` MODIFY `product_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_invoice_item_attachments` MODIFY `invoice_item_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_invoice_item_tax` MODIFY `invoice_item_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_invoice_item_tax` MODIFY `tax_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_payment` MODIFY `ac_inv_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_payment` MODIFY `ac_payment_type` INT(11) UNSIGNED NULL;ALTER TABLE `si_payment_types` MODIFY `pt_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_products_values` MODIFY `attribute_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_system_defaults` MODIFY `extension_id` INT(11) UNSIGNED NULL;', 'fearless359'),
(860, 314, 'Create the index items for all auto increment fields.', '20190329', 'ALTER TABLE `si_biller` ADD CONSTRAINT `id` UNIQUE(`id`);ALTER TABLE `si_cron` ADD CONSTRAINT `id` UNIQUE(`id`);ALTER TABLE `si_cron_log` ADD CONSTRAINT `id` UNIQUE(`id`);ALTER TABLE `si_custom_fields` ADD CONSTRAINT `cf_id` UNIQUE(`cf_id`);ALTER TABLE `si_customers` ADD CONSTRAINT `id` UNIQUE(`id`);ALTER TABLE `si_extensions` ADD CONSTRAINT `id` UNIQUE(`id`);ALTER TABLE `si_inventory` ADD CONSTRAINT `id` UNIQUE(`id`);ALTER TABLE `si_invoices` ADD CONSTRAINT `id` UNIQUE(`id`);ALTER TABLE `si_log` ADD CONSTRAINT `id` UNIQUE(`id`);ALTER TABLE `si_payment` ADD CONSTRAINT `id` UNIQUE(`id`);ALTER TABLE `si_payment_types` ADD CONSTRAINT `pt_id` UNIQUE(`pt_id`);ALTER TABLE `si_preferences` ADD CONSTRAINT `pref_id` UNIQUE(`pref_id`);ALTER TABLE `si_products` ADD CONSTRAINT `id` UNIQUE(`id`);ALTER TABLE `si_system_defaults` ADD CONSTRAINT `id` UNIQUE(`id`);ALTER TABLE `si_tax` ADD CONSTRAINT `tax_id` UNIQUE(`tax_id`);ALTER TABLE `si_user` ADD CONSTRAINT `id` UNIQUE(`id`);', 'fearless359'),
(861, 315, 'Make all tables InnoDB, utf8 and utr8_unicode_ci', '20190329', 'ALTER TABLE `si_biller` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_cron` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_cron_log` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_custom_fields` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_custom_flags` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_customers` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_expense` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_expense_account` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_expense_item_tax` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_extensions` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_index` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_install_complete` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_inventory` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_invoice_item_attachments` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_invoice_item_tax` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_invoice_items` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_invoice_type` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_invoices` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_log` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_payment` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_payment_types` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_preferences` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_products` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_products_attribute_type` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_products_attributes` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_products_values` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_sql_patchmanager` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_system_defaults` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_tax` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_user` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_user_domain` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_user_role` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;', 'fearless359'),
(862, 316, 'Change character type field settings to charset utf8 collate utf8_unicode_ci', '20190329', 'ALTER TABLE `si_custom_flags` MODIFY `associated_table` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_custom_flags` MODIFY `field_label` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_custom_flags` MODIFY `field_help` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_expense` MODIFY `note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_expense_account` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_expense_item_tax` MODIFY `tax_type` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_extensions` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_extensions` MODIFY `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_index` MODIFY `node` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_inventory` MODIFY `note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_invoices` MODIFY `custom_field1` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_invoices` MODIFY `custom_field2` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_invoices` MODIFY `custom_field3` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_invoices` MODIFY `custom_field4` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_invoices` MODIFY `note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_invoices` MODIFY `sales_representative` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_invoice_items` MODIFY `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_invoice_items` MODIFY `attribute` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_invoice_item_attachments` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_invoice_item_tax` MODIFY `tax_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_invoice_type` MODIFY `inv_ty_description` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_log` MODIFY `sqlquerie` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_payment` MODIFY `ac_notes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_payment` MODIFY `online_payment_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_payment` MODIFY `ac_check_number` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_payment_types` MODIFY `pt_description` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `pref_description` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `pref_currency_sign` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `pref_inv_heading` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `pref_inv_wording` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `pref_inv_detail_heading` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `pref_inv_detail_line` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `pref_inv_payment_method` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `pref_inv_payment_line1_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `pref_inv_payment_line1_value` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `pref_inv_payment_line2_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `pref_inv_payment_line2_value` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `locale` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `language` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `currency_code` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `include_online_payment` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_preferences` MODIFY `currency_position` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products` MODIFY `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products` MODIFY `custom_field1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products` MODIFY `custom_field2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products` MODIFY `custom_field3` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products` MODIFY `custom_field4` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products` MODIFY `notes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products` MODIFY `attribute` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products` MODIFY `notes_as_description` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products` MODIFY `show_description` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products` MODIFY `custom_flags` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products_attributes` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products_attribute_type` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_products_values` MODIFY `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_sql_patchmanager` MODIFY `sql_patch` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_sql_patchmanager` MODIFY `sql_release` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_sql_patchmanager` MODIFY `sql_statement` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_sql_patchmanager` MODIFY `source` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_system_defaults` MODIFY `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_system_defaults` MODIFY `value` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_tax` MODIFY `tax_description` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_tax` MODIFY `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_user` MODIFY `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_user` MODIFY `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_user` MODIFY `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_user_domain` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;ALTER TABLE `si_user_role` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;', 'fearless359'),
(863, 317, 'Add foreign keys to tables.', '20190329', 'ALTER TABLE `si_cron` ADD FOREIGN KEY (`invoice_id`) REFERENCES `si_invoices` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_cron_log` ADD FOREIGN KEY (`cron_id`) REFERENCES `si_cron` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_expense` ADD FOREIGN KEY (`biller_id`) REFERENCES `si_biller` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT; ALTER TABLE `si_expense` ADD FOREIGN KEY (`customer_id`) REFERENCES `si_customers` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_expense` ADD FOREIGN KEY (`invoice_id`) REFERENCES `si_invoices` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT; ALTER TABLE `si_expense` ADD FOREIGN KEY (`product_id`) REFERENCES `si_products` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_expense` ADD FOREIGN KEY (`expense_account_id`) REFERENCES `si_expense_account` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_expense_item_tax` ADD FOREIGN KEY (`expense_id`) REFERENCES `si_expense` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_expense_item_tax` ADD FOREIGN KEY (`tax_id`) REFERENCES `si_tax` (`tax_id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_inventory` ADD FOREIGN KEY (`product_id`) REFERENCES `si_products` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_invoices` ADD FOREIGN KEY (`biller_id`) REFERENCES `si_biller` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT; ALTER TABLE `si_invoices` ADD FOREIGN KEY (`customer_id`) REFERENCES `si_customers` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_invoices` ADD FOREIGN KEY (`type_id`) REFERENCES `si_invoice_type` (`inv_ty_id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_invoices` ADD FOREIGN KEY (`preference_id`) REFERENCES `si_preferences` (`pref_id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_invoice_items` ADD FOREIGN KEY (`invoice_id`) REFERENCES `si_invoices` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_invoice_items` ADD FOREIGN KEY (`product_id`) REFERENCES `si_products` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_invoice_item_tax` ADD FOREIGN KEY (`tax_id`) REFERENCES `si_tax` (`tax_id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_invoice_item_attachments` ADD FOREIGN KEY (`invoice_item_id`) REFERENCES `si_invoice_items` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_log` ADD FOREIGN KEY (`userid`) REFERENCES `si_user` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_payment` ADD FOREIGN KEY (`ac_inv_id`) REFERENCES `si_invoices` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_payment` ADD FOREIGN KEY (`ac_payment_type`) REFERENCES `si_payment_types` (`pt_id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_products` ADD FOREIGN KEY (`default_tax_id`) REFERENCES `si_tax` (`tax_id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_products` ADD FOREIGN KEY (`default_tax_id_2`) REFERENCES `si_tax` (`tax_id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_products_attributes` ADD FOREIGN KEY (`type_id`) REFERENCES `si_products_attribute_type` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_products_values` ADD FOREIGN KEY (`attribute_id`) REFERENCES `si_products_attributes` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_user` ADD FOREIGN KEY (`domain_id`) REFERENCES `si_user_domain` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;ALTER TABLE `si_user` ADD FOREIGN KEY (`role_id`) REFERENCES `si_user_role` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;', 'fearless359'),
(864, 318, 'Additional foreign key field characteristic setting changes', '20190425', 'ALTER TABLE `si_biller` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_cron` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_cron_log` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_cron_log` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_customers` ALTER `domain_id` DROP DEFAULT;ALTER TABLE `si_customers` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_customers` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_customers` MODIFY `parent_customer_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_custom_fields` MODIFY `cf_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_custom_fields` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_custom_flags` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_expense` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_expense` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_expense_account` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_expense_account` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_expense_item_tax` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_extensions` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_extensions` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_index` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_index` MODIFY `id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_inventory` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_inventory` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_invoice_items` ALTER `domain_id` DROP DEFAULT;ALTER TABLE `si_invoice_items` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_invoice_items` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_invoice_item_tax` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_invoice_type` MODIFY `inv_ty_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_invoices` ALTER `domain_id` DROP DEFAULT;ALTER TABLE `si_invoices` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_invoices` MODIFY `index_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_log` ALTER `domain_id` DROP DEFAULT;ALTER TABLE `si_log` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_log` MODIFY `id` BIGINT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_log` MODIFY `last_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_log` MODIFY `user_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_payment` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_payment_types` ALTER `domain_id` DROP DEFAULT;ALTER TABLE `si_payment_types` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_payment_types` MODIFY `pt_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_preferences` ALTER `domain_id` DROP DEFAULT;ALTER TABLE `si_preferences` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_preferences` MODIFY `pref_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_products` MODIFY `default_tax_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_products` MODIFY `default_tax_id_2` INT(11) UNSIGNED NULL;ALTER TABLE `si_products` ALTER `domain_id` DROP DEFAULT;ALTER TABLE `si_products` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_products` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_products_attributes` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_products_attribute_type` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_products_values` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_sql_patchmanager` MODIFY `sql_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_system_defaults` ALTER `domain_id` DROP DEFAULT;ALTER TABLE `si_system_defaults` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_system_defaults` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_tax` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_tax` MODIFY `tax_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_user` ALTER `domain_id` DROP DEFAULT;ALTER TABLE `si_user` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;ALTER TABLE `si_user` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_user` MODIFY `role_id` INT(11) UNSIGNED NULL;ALTER TABLE `si_user_domain` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;ALTER TABLE `si_user_role` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;', 'fearless359'),
(865, 319, 'Add set_aging field to si_preferences', '20200123', 'ALTER TABLE `si_preferences` ADD COLUMN `set_aging` BOOL NOT NULL DEFAULT 0 AFTER `index_group`;UPDATE `si_preferences` SET `set_aging` = 1 WHERE pref_id = 1;', 'fearless359');

--
-- Test/required data for `si_system_defaults` table - no constraints
--
INSERT INTO `si_system_defaults` (`name`, `value`, `domain_id`, `extension_id`)
VALUES ('biller', '', '1', '1')
     , ('company_logo', 'simple_invoices_logo.png', '1', '1')
     , ('company_name', 'SimpleInvoices', '1', '1')
     , ('company_name_item', 'SimpleInvoices', '1', '1')
     , ('customer', '', '1', '1')
     , ('dateformate', 'Y-m-d', '1', '1')
     , ('default_invoice', '', '1', '1')
     , ('delete', 'N', '1', '1')
     , ('emailhost', 'localhost', '1', '1')
     , ('emailpassword', '', '1', '1')
     , ('emailusername', '', '1', '1')
     , ('expense', '0', '1', '1')
     , ('inventory', '0', '1', '1')
     , ('language', 'en_US', '1', '1')
     , ('line_items', '3', '1', '1')
     , ('logging', '0', '1', '1')
     , ('password_lower', '1', '1', '1')
     , ('password_min_length', '8', '1', '1')
     , ('password_number', '1', '1', '1')
     , ('password_special', '1', '1', '1')
     , ('password_upper', '1', '1', '1')
     , ('payment_type', '1', '1', '1')
     , ('pdfbottommargin', '15', '1', '1')
     , ('pdfleftmargin', '15', '1', '1')
     , ('pdfpapersize', 'A4', '1', '1')
     , ('pdfrightmargin', '15', '1', '1')
     , ('pdfscreensize', '800', '1', '1')
     , ('pdftopmargin', '15', '1', '1')
     , ('preference', '1', '1', '1')
     , ('product_attributes', '0', '1', '1')
     , ('session_timeout', '60', '1', '1')
     , ('spreadsheet', 'xls', '1', '1')
     , ('tax', '1', '1', '1')
     , ('tax_per_line_item', '1', '1', '1')
     , ('template', 'default', '1', '1')
     , ('wordprocessor', 'doc', '1', '1');

--
-- Test/required data for `si_tax` table - no constraints
--
INSERT INTO `si_tax` (`tax_id`, `tax_description`, `tax_percentage`, `type`, `tax_enabled`, `domain_id`)
VALUES (1, 'GST', 10.000000, '%', '1', 1)
     , (2, 'VAT', 10.000000, '%', '1', 1)
     , (3, 'Sales Tax', 10.000000, '%', '1', 1)
     , (4, 'No Tax', 0.000000, '%', '1', 1)
     , (5, 'Postage', 20.000000, '$', '1', 1);

--
-- Test/required data for `si_user_domain` table - no constraints
--
INSERT INTO `si_user_domain` (`id`, `name`)
VALUES (1, 'default');

--
-- Test/required data for `si_user_role` table - no constraints
--
INSERT INTO `si_user_role` (`id`, `name`)
VALUES (1, 'administrator')
     , (2, 'domain_administrator')
     , (3, 'user')
     , (4, 'operator')
     , (5, 'customer')
     , (6, 'biller')
     , (7, 'viewer');

--
-- Test/required data for `si_invoice_item_tax` table - must follow si_tax
--
INSERT INTO `si_invoice_item_tax` (`id`, `invoice_item_id`, `tax_id`, `tax_type`, `tax_rate`, `tax_amount`)
VALUES (1, 1, 3, '%', 10.000000, 12.500000)
     , (2, 2, 1, '%', 10.000000, 12.500000)
     , (3, 3, 4, '%', 0.000000, 0.000000)
     , (4, 4, 1, '%', 10.000000, 14.000000)
     , (5, 5, 4, '%', 0.000000, 0.000000);

--
-- Test/required data for `si_products_attributes` table - must follow si_products_attributes_type
--
INSERT INTO `si_products_attributes`
VALUES ('1', 'Size', '1', '1', '1')
     , ('2', 'Colour', '1', '1', '1');

--
-- Test/required data for `si_products_values` table - must follow si_products_attributes
--
INSERT INTO `si_products_values`
VALUES ('1', '1', 'S', '1')
     , ('2', '1', 'M', '1')
     , ('3', '1', 'L', '1')
     , ('4', '2', 'Red', '1')
     , ('5', '2', 'White', '1');

--
-- Test/required data for `si_invoices` table - must follow si_biller, si_customers, si_invoice_type, si_preferences
--
INSERT INTO `si_invoices` (`id`, `index_id`, `domain_id`, `biller_id`, `customer_id`, `type_id`, `preference_id`, `date`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `note`)
VALUES (1, 1, 1, 1, 1, 2, 1, '2018-12-30 00:00:00', '', '', '', '', '');

--
-- Test/required data for `si_products` table - must follow si_tax
--
INSERT INTO `si_products` (`id`, `domain_id`, `description`, `unit_price`, `default_tax_id`, `default_tax_id_2`, `cost`, `reorder_level`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `notes`, `enabled`, `visible`, `attribute`,
                           `notes_as_description`, `show_description`, `custom_flags`)
VALUES (1, 1, 'Hourly charge', 60.000000, 1, NULL, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000')
     , (2, 1, 'Power Supply', 85.000000, 1, NULL, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000')
     , (3, 1, 'Keyboard', 15.000000, 1, NULL, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000')
     , (4, 1, 'Mouse', 20.000000, 1, NULL, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000');

--
-- Test/required data for `si_invoice_items` table - must follow si_invoices and si_products
--
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `domain_id`, `quantity`, `product_id`, `unit_price`, `tax_amount`, `gross_total`, `description`, `total`)
VALUES (1, 1, 1, 1.000000, 4, 125.000000, 12.500000, 125.000000, '', 137.500000)
     , (2, 1, 1, 1.000000, 3, 125.000000, 12.500000, 125.000000, '', 137.500000)
     , (3, 1, 1, 1.000000, 2, 140.000000, 0.000000, 140.000000, '', 140.000000)
     , (4, 1, 1, 1.000000, 2, 140.000000, 14.000000, 140.000000, '', 154.000000)
     , (5, 1, 1, 1.000000, 1, 150.000000, 0.000000, 150.000000, '', 150.000000);
--
-- Test/required data for `si_user` table - must follow si_user_domain and si_user_role
--
INSERT INTO `si_user` (`id`, `username`, `email`, `role_id`, `domain_id`, `password`, `enabled`, `user_id`)
VALUES (1, 'demo', 'demo@simpleinvoices.group', 1, 1, 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 0);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
