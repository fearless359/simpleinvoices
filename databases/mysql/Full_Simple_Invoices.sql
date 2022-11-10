-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2022 at 03:32 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


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
-- Table structure for table `si_cron_invoice_items`
--

CREATE TABLE `si_cron_invoice_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `cron_id` int(11) UNSIGNED NOT NULL,
  `quantity` decimal(25,6) NOT NULL DEFAULT 0.000000,
  `product_id` int(11) UNSIGNED NOT NULL,
  `unit_price` decimal(25,6) DEFAULT 0.000000,
  `tax_amount` decimal(25,6) DEFAULT 0.000000,
  `gross_total` decimal(25,6) DEFAULT 0.000000,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` decimal(25,6) DEFAULT 0.000000,
  `attribute` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_cron_invoice_item_tax`
--

CREATE TABLE `si_cron_invoice_item_tax` (
  `id` int(11) UNSIGNED NOT NULL,
  `cron_invoice_item_id` int(11) UNSIGNED NOT NULL,
  `tax_id` int(11) UNSIGNED NOT NULL,
  `tax_type` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate` decimal(25,6) NOT NULL,
  `tax_amount` decimal(25,6) NOT NULL
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
  `invoice_item_id` int(11) UNSIGNED NOT NULL,
  `tax_id` int(11) UNSIGNED NOT NULL,
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
  `customer_id` int(11) UNSIGNED DEFAULT NULL,
  `ac_amount` decimal(25,6) NOT NULL,
  `ac_notes` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `ac_date` datetime NOT NULL,
  `ac_payment_type` int(11) UNSIGNED DEFAULT NULL,
  `domain_id` int(11) UNSIGNED NOT NULL,
  `online_payment_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ac_check_number` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `warehouse_amount` decimal(25,6) NOT NULL DEFAULT 0.000000
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
-- Table structure for table `si_payment_warehouse`
--

CREATE TABLE `si_payment_warehouse` (
  `id` int(11) UNSIGNED NOT NULL,
  `customer_id` int(11) UNSIGNED NOT NULL,
  `last_payment_id` int(11) UNSIGNED DEFAULT NULL,
  `balance` decimal(25,6) NOT NULL,
  `payment_type` int(11) UNSIGNED NOT NULL,
  `check_number` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
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
  `custom_flags` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_group` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
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
-- Table structure for table `si_products_attributes_values`
--

CREATE TABLE `si_products_attributes_values` (
  `id` int(11) UNSIGNED NOT NULL,
  `attribute_id` int(11) UNSIGNED DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1
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
-- Table structure for table `si_product_groups`
--

CREATE TABLE `si_product_groups` (
  `name` varchar(60) NOT NULL,
  `markup` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Indexes for table `si_cron_invoice_items`
--
ALTER TABLE `si_cron_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cron_id` (`cron_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `si_cron_invoice_item_tax`
--
ALTER TABLE `si_cron_invoice_item_tax`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cron_invoice_item_id` (`cron_invoice_item_id`),
  ADD KEY `tax_id` (`tax_id`);

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
  ADD KEY `tax_id` (`tax_id`),
  ADD KEY `invoice_item_id` (`invoice_item_id`);

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
  ADD KEY `ac_payment_type` (`ac_payment_type`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `si_payment_types`
--
ALTER TABLE `si_payment_types`
  ADD PRIMARY KEY (`domain_id`,`pt_id`),
  ADD UNIQUE KEY `pt_id` (`pt_id`);

--
-- Indexes for table `si_payment_warehouse`
--
ALTER TABLE `si_payment_warehouse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `last_payment_id` (`last_payment_id`),
  ADD KEY `payment_type` (`payment_type`);

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
-- Indexes for table `si_products_attributes_values`
--
ALTER TABLE `si_products_attributes_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `si_products_attribute_type`
--
ALTER TABLE `si_products_attribute_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `si_product_groups`
--
ALTER TABLE `si_product_groups`
  ADD PRIMARY KEY (`name`);

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
-- AUTO_INCREMENT for table `si_cron_invoice_items`
--
ALTER TABLE `si_cron_invoice_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_cron_invoice_item_tax`
--
ALTER TABLE `si_cron_invoice_item_tax`
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
-- AUTO_INCREMENT for table `si_payment_warehouse`
--
ALTER TABLE `si_payment_warehouse`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `si_products_attributes_values`
--
ALTER TABLE `si_products_attributes_values`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_products_attribute_type`
--
ALTER TABLE `si_products_attribute_type`
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
-- Constraints for table `si_cron_invoice_items`
--
ALTER TABLE `si_cron_invoice_items`
  ADD CONSTRAINT `si_cron_invoice_items_ibfk_1` FOREIGN KEY (`cron_id`) REFERENCES `si_cron` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_cron_invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `si_products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `si_cron_invoice_item_tax`
--
ALTER TABLE `si_cron_invoice_item_tax`
  ADD CONSTRAINT `si_cron_invoice_item_tax_ibfk_1` FOREIGN KEY (`tax_id`) REFERENCES `si_tax` (`tax_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_cron_invoice_item_tax_ibfk_2` FOREIGN KEY (`cron_invoice_item_id`) REFERENCES `si_cron_invoice_items` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `si_invoice_item_tax_ibfk_1` FOREIGN KEY (`tax_id`) REFERENCES `si_tax` (`tax_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_invoice_item_tax_ibfk_2` FOREIGN KEY (`invoice_item_id`) REFERENCES `si_invoice_items` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `si_payment_ibfk_2` FOREIGN KEY (`ac_payment_type`) REFERENCES `si_payment_types` (`pt_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_payment_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `si_customers` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `si_payment_warehouse`
--
ALTER TABLE `si_payment_warehouse`
  ADD CONSTRAINT `si_payment_warehouse_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `si_customers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `si_payment_warehouse_ibfk_2` FOREIGN KEY (`last_payment_id`) REFERENCES `si_payment` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `si_payment_warehouse_ibfk_3` FOREIGN KEY (`payment_type`) REFERENCES `si_payment_types` (`pt_id`) ON UPDATE CASCADE;

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
-- Constraints for table `si_products_attributes_values`
--
ALTER TABLE `si_products_attributes_values`
  ADD CONSTRAINT `si_products_attributes_values_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `si_products_attributes` (`id`) ON UPDATE CASCADE;

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
        '<p><strong>Moe&#39;s Tavern</strong> is a fictional <a href=&#39;https://en.wikipedia.org/wiki/Bar_%28establishment%29&#39; title=&#39;Bar (establishment)&#39;>bar</a> seen on <em><a href=&#39;https://en.wikipedia.org/wiki/The_Simpsons&#39;
title=&#39;The Simpsons&#39;>The Simpsons</a></em>. The owner of the bar is <a href=&#39;https://en.wikipedia.org/wiki/Moe_Szyslak&#39; title=&#39;Moe Szyslak&#39;>Moe Szyslak</a>.</p>
<p>In The Simpsons world, it is located on the corner of Walnut Street, neighboring King Toot&#39;s Music Store, across the street is the Moeview Motel, and a factory formerly owned by <a href=&#39;https://en.wikipedia.org/wiki/Bart_Simpson&#39;
title=&#39;Bart Simpson&#39;>Bart Simpson</a>, until it collapsed. The inside of the bar has a few pool tables and a dartboard. It is very dank and &quot;smells like <a href=&#39;https://en.wikipedia.org/wiki/Urine&#39; title=&#39;Urine&#39;>tinkle</a>.
&quot; Because female customers are so rare, Moe frequently uses the women&#39;s restroom as an office. Moe claimed that there haven&#39;t been any ladies at Moe&#39;s since <a href=&#39;https://en.wikipedia.org/wiki/1979&#39; title=&#39;1979&#39;>1979</a>
(though earlier episodes show otherwise). A jar of pickled eggs perpetually stands on the bar. Another recurring element is a rat problem. This can be attributed to the episode <a href=&#39;https://en.wikipedia.org/wiki/Homer%27s_Enemy&#39;
title=&#39;Homer&#39;s Enemy&#39;>Homer&#39;s Enemy</a> in which Bart&#39;s factory collapses, and the rats are then shown to find a new home at Moe&#39;s. In &quot;<a href=&#39;https://en.wikipedia.org/wiki/Who_Shot_Mr._Burns&#39;
title=&#39;Who Shot Mr. Burns&#39;>Who Shot Mr. Burns</a>,&quot; Moe&#39;s Tavern was forced to close down because Mr. Burns&#39; slant-drilling operation near the tavern caused unsafe pollution.
It was stated in the &quot;<a href=&#39;https://en.wikipedia.org/wiki/Flaming_Moe%27s&#39; title=&#39;Flaming Moe&#39;s&#39;>Flaming Moe&#39;s</a>&quot; episode that Moe&#39;s Tavern was on Walnut Street. The phone number would be 76484377,
since in &quot;<a href=&#39;https://en.wikipedia.org/wiki/Homer_the_Smithers&#39; title=&#39;Homer the Smithers&#39;>Homer the Smithers</a>,&quot; Mr. Burns tried to call Smithers but did not know his phone number. He tried the buttons marked with the
letters for Smithers and called Moe&#39;s. In &quot;<a href=&#39;https://en.wikipedia.org/wiki/Principal_Charming&#39; title=&#39;Principal Charming&#39;>Principal Charming</a>&quot; Bart is asked to call Homer by Principal Skinner, the number visible on
the card is WORK: KLondike 5-6832 HOME: KLondike 5-6754 MOE&#39;S TAVERN: KLondike 5-1239 , Moe answers the phone and Bart asks for Homer Sexual. The bar serves <a href=&#39;https://en.wikipedia.org/wiki/Duff_Beer&#39;
title=&#39;Duff Beer&#39;>Duff Beer</a> and Red Tick Beer, a beer flavored with dogs.</p>',
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
     , (2, 'Itemized');

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

INSERT INTO `si_sql_patchmanager` (`sql_patch_ref`, `sql_patch`, `sql_release`, `sql_statement`, `source`) VALUES
(319, 'Add set_aging field to si_preferences', '20200123', 'ALTER TABLE `si_preferences` ADD COLUMN `set_aging` BOOL NOT NULL DEFAULT 0 AFTER `index_group`;UPDATE `si_preferences` SET `set_aging` = 1 WHERE pref_id = 1;', 'fearless359'),
(320, 'Remove deleted extensions mini and measurement', '20200822', 'DELETE IGNORE FROM `si_extensions` WHERE `name` = \'mini\' OR `name` = \'measurement\';', 'fearless359'),
(321, 'Add parent_customer_id field to the database', '20200924', 'ALTER TABLE `si_customers` ADD `parent_customer_id` INT(11) NULL AFTER `notes`;DELETE IGNORE FROM `si_extensions` WHERE `name` = \'sub_customer\';INSERT INTO `si_system_defaults` (`name`, `value`, `domain_id`, `extension_id`) VALUES (\'sub_customer\', 0, 1, 1);', 'fearless359'),
(322, 'Add product_groups table to the database.', '20201010', 'CREATE TABLE `si_product_groups` (name VARCHAR(60) NOT NULL PRIMARY KEY, markup INT(2) NOT NULL DEFAULT 0) ENGINE = InnoDb; DELETE IGNORE FROM `si_extensions` WHERE `name` = \'invoice_grouped\'; INSERT INTO `si_system_defaults` (`name`, `value`, `domain_id`, `extension_id`) VALUES (\'product_groups\', 0, $domainId, 1); ALTER TABLE `si_products` ADD product_group VARCHAR(60) NOT NULL DEFAULT \'\'; INSERT INTO `si_product_groups` (`name`, `markup`) VALUES (\'Labor\', 0); INSERT INTO `si_product_groups` (`name`, `markup`) VALUES (\'Equipment\', 0); INSERT INTO `si_product_groups` (`name`, `markup`) VALUES (\'Materials\', 0); INSERT INTO `si_product_groups` (`name`, `markup`) VALUES (\'Subcontractor\', 0);', 'fearless359'),
(323, 'Add invoice description open option.', '20210413', 'INSERT INTO `si_system_defaults` (name ,value ,domain_id ,extension_id ) VALUES (\'invoice_description_open\', 0, $domainId, 1);', 'fearless359'),
(324, 'Rename si_products_values table to si_products_attributes_values.', '20210527', 'ALTER TABLE `si_products_values` RENAME TO si_products_attributes_values;', 'fearless359'),
(325, 'Remove unused items from the si_system_defaults table.', '20200615', 'DELETE IGNORE FROM `si_system_defaults` WHERE `name` in (\'company_name\', \'emailhost\', \'emailpassword\', \'emailusername\', \'pdfbottommargin\', \'pdfleftmargin\', \'pdfpapersize\', \'pdfrightmargin\', \'pdfscreensize\', \'pdftopmargin\', \'spreadsheet\', \'wordprocessor\'); DELETE IGNORE FROM `si_system_defaults` WHERE `name` LIKE \'dateformat%\';', 'fearless359'),
(326, 'Add display department option.', '20210930', 'INSERT INTO `si_system_defaults` (name ,value ,domain_id ,extension_id ) VALUES (\'display_department\', 1, $domainId, 1);', 'fearless359'),
(327, 'Make invoice_item_id a key field in the invoice_item_tax table.', '20220909', 'ALTER TABLE `si_invoice_item_tax` ADD KEY `invoice_item_id` (`invoice_item_id`);', 'fearless359'),
(328, 'Add cron_invoice_items table to the database.', '20220909', 'CREATE TABLE `si_cron_invoice_items` (id int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `cron_id` int(11) UNSIGNED NOT NULL, `quantity` decimal(25,6) NOT NULL DEFAULT 0.000000, `product_id` int(11) UNSIGNED NOT NULL, `unit_price` decimal(25,6) DEFAULT 0.000000, `tax_amount` decimal(25,6) DEFAULT 0.000000, `gross_total` decimal(25,6) DEFAULT 0.000000, `description` text COLLATE utf8_unicode_ci DEFAULT NULL, `total` decimal(25,6) DEFAULT 0.000000, `attribute` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL) ENGINE = InnoDb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_cron_invoice_items` ADD KEY `cron_id` (`cron_id`), ADD KEY `product_id` (`product_id`); ALTER TABLE `si_cron_invoice_items` ADD CONSTRAINT `si_cron_invoice_items_ibfk_1` FOREIGN KEY (`cron_id`) REFERENCES `si_cron` (`id`) ON UPDATE CASCADE, ADD CONSTRAINT `si_cron_invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `si_products` (`id`) ON UPDATE CASCADE;', 'fearless359'),
(329, 'Add cron_invoice_item_tax table to the database.', '20220909', 'CREATE TABLE `si_cron_invoice_item_tax` (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `cron_invoice_item_id` int(11) UNSIGNED NOT NULL, `tax_id` int(11) UNSIGNED NOT NULL, `tax_type` char(1) COLLATE utf8_unicode_ci DEFAULT NULL, `tax_rate` decimal(25,6) NOT NULL, `tax_amount` decimal(25,6) NOT NULL) ENGINE = InnoDb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_cron_invoice_item_tax` ADD KEY `cron_invoice_item_id` (`cron_invoice_item_id`), ADD KEY `tax_id` (`tax_id`);ALTER TABLE `si_cron_invoice_item_tax` ADD CONSTRAINT `si_cron_invoice_item_tax_ibfk_1` FOREIGN KEY (`tax_id`) REFERENCES `si_tax` (`tax_id`) ON UPDATE CASCADE, ADD CONSTRAINT `si_cron_invoice_item_tax_ibfk_2` FOREIGN KEY (`cron_invoice_item_id`) REFERENCES `si_cron_invoice_items` (`id`) ON UPDATE CASCADE;', 'fearless359'),
(330, 'Add invoice_item_id as a key for the invoice_item_tax table.', '20220926', 'ALTER TABLE `si_invoice_item_tax` MODIFY `invoice_item_id` INT(11) UNSIGNED NOT NULL, MODIFY `tax_id` INT(11) UNSIGNED NOT NULL; ALTER TABLE `si_invoice_item_tax` ADD KEY `invoice_item_id` (`invoice_item_id`);', 'fearless359'),
(331, 'Add foreign key for invoice_item_id to invoice_item_tax table.', '20221013', 'ALTER TABLE `si_invoice_item_tax` ADD CONSTRAINT `si_invoice_item_tax_ibfk_2` FOREIGN KEY (`invoice_item_id`) REFERENCES `si_invoice_items` (`id`) ON UPDATE CASCADE;', 'fearless359'),
(332, 'Add payment_warehouse table to the database.', '20221013', 'CREATE TABLE `si_payment_warehouse` (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `customer_id` int(11) UNSIGNED NOT NULL, `last_payment_id` int(11) UNSIGNED, `balance` decimal(25,6) NOT NULL, `payment_type` int(11) UNSIGNED NOT NULL, `check_number` varchar(10) DEFAULT NULL) ENGINE = InnoDb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;ALTER TABLE `si_payment_warehouse` ADD KEY `customer_id` (`customer_id`), ADD KEY `last_payment_id` (`last_payment_id`), ADD KEY `payment_type` (`payment_type`);ALTER TABLE `si_payment_warehouse` ADD CONSTRAINT `si_payment_warehouse_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `si_customers` (`id`) ON UPDATE CASCADE,ADD CONSTRAINT `si_payment_warehouse_ibfk_2` FOREIGN KEY (`last_payment_id`) REFERENCES `si_payment` (`id`) ON UPDATE SET NULL ON DELETE SET NULL,ADD CONSTRAINT `si_payment_warehouse_ibfk_3` FOREIGN KEY (`payment_type`) REFERENCES `si_payment_types` (`pt_id`) ON UPDATE CASCADE;', 'fearless359'),
(333, 'Add warehouse_amount field to payment table.', '20221019', 'ALTER TABLE `si_payment` ADD `customer_id` int(11) UNSIGNED AFTER `ac_inv_id`, ADD `warehouse_amount` decimal(25,6) NOT NULL DEFAULT 0 AFTER `ac_check_number`; ALTER TABLE `si_payment` ADD KEY `customer_id` (`customer_id`); ALTER TABLE `si_payment` ADD CONSTRAINT `si_payment_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `si_customers` (`id`) ON UPDATE SET NULL ON DELETE SET NULL;', 'fearless359'),
(334, 'Add payment delete days option.', '20221022', 'INSERT INTO `si_system_defaults` (name ,value ,domain_id ,extension_id ) VALUES (\'payment_delete_days\', 0, 1, 1);', 'fearless359'),
(335, 'Add invoice display days option.', '20221110', 'INSERT INTO `si_system_defaults` (name ,value ,domain_id ,extension_id ) VALUES (\'invoice_display_days\', 0, 1, 1);', 'fearless359');

--
-- Test/required data for `si_system_defaults` table - no constraints
--

INSERT INTO `si_system_defaults` (`name`, `value`, `domain_id`, `extension_id`)
VALUES ('biller', '', '1', '1')
     , ('company_logo', 'simple_invoices_logo.png', '1', '1')
     , ('company_name_item', 'SimpleInvoices', '1', '1')
     , ('customer', '', '1', '1')
     , ('default_invoice', '', '1', '1')
     , ('delete', '0', '1', '1')
     , ('display_department', '1', '1', '1')
     , ('expense', '0', '1', '1')
     , ('inventory', '0', '1', '1')
     , ('invoice_description_open', '0', '1', '1')
     , ('invoice_display_days', '0', '1', '1')
     , ('language', 'en_US', '1', '1')
     , ('line_items', '3', '1', '1')
     , ('logging', '0', '1', '1')
     , ('password_lower', '1', '1', '1')
     , ('password_min_length', '8', '1', '1')
     , ('password_number', '1', '1', '1')
     , ('password_special', '1', '1', '1')
     , ('password_upper', '1', '1', '1')
     , ('payment_delete_days', '0', '1', '1')
     , ('payment_type', '1', '1', '1')
     , ('preference', '1', '1', '1')
     , ('product_attributes', '0', '1', '1')
     , ('product_groups', '0', '1', '1')
     , ('session_timeout', '60', '1', '1')
     , ('sub_customer', '0', '1', '1')
     , ('tax', '1', '1', '1')
     , ('tax_per_line_item', '1', '1', '1')
     , ('template', 'default', '1', '1');

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
     , (4, 'customer')
     , (5, 'biller');

--
-- Test/required data for `si_products_attributes` table - must follow si_products_attributes_type
--
INSERT INTO `si_products_attributes`
VALUES ('1', 'Size', '1', '1', '1')
     , ('2', 'Colour', '1', '1', '1');

--
-- Test/required data for `si_products_attributes_values` table - must follow si_products_attributes
--
INSERT INTO `si_products_attributes_values`
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
                           `notes_as_description`, `show_description`, `custom_flags`, `product_group`)
VALUES (1, 1, 'Hourly charge', 60.000000, 1, NULL, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000', '')
     , (2, 1, 'Power Supply', 85.000000, 1, NULL, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000', '')
     , (3, 1, 'Keyboard', 15.000000, 1, NULL, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000', '')
     , (4, 1, 'Mouse', 20.000000, 1, NULL, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000', '');

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
-- Test/required data for `si_invoice_item_tax` table - must follow si_tax
--
INSERT INTO `si_invoice_item_tax` (`id`, `invoice_item_id`, `tax_id`, `tax_type`, `tax_rate`, `tax_amount`)
VALUES (1, 1, 3, ''%'', 10.000000, 12.500000)
     , (2, 2, 1, ''%'', 10.000000, 12.500000)
     , (3, 3, 4, ''%'', 0.000000, 0.000000)
     , (4, 4, 1, ''%'', 10.000000, 14.000000)
     , (5, 5, 4, ''%'', 0.000000, 0.000000);

--
-- Test/required data for `si_user` table - must follow si_user_domain and si_user_role
--
INSERT INTO `si_user` (`id`, `username`, `email`, `role_id`, `domain_id`, `password`, `enabled`, `user_id`)
VALUES (1, 'demo', 'demo@simpleinvoices.group', 1, 1, 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 0);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
