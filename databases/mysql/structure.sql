-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2019 at 03:51 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rrowfbbw_simple_invoices`
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
                           `email_biller` tinyint(1) NOT NULL DEFAULT '0',
                           `email_customer` tinyint(1) NOT NULL DEFAULT '0'
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
                                `notes` text COLLATE utf8_unicode_ci,
                                `default_invoice` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Invoice index_id value to use as the default template',
                                `custom_field1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                `custom_field2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                `custom_field3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                `custom_field4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                `parent_customer_id` int(11) UNSIGNED DEFAULT NULL,
                                `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_custom_fields`
--

CREATE TABLE `si_custom_fields` (
                                    `cf_id` int(11) UNSIGNED NOT NULL,
                                    `cf_custom_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                    `cf_custom_label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                    `cf_display` tinyint(1) NOT NULL DEFAULT '1',
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
                              `note` text COLLATE utf8_unicode_ci,
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
                                 `enabled` tinyint(1) NOT NULL DEFAULT '0'
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
                                `note` text COLLATE utf8_unicode_ci
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
                               `note` text COLLATE utf8_unicode_ci,
                               `owing` decimal(25,6) NOT NULL DEFAULT '0.000000' COMMENT 'Amount owing as of aging-date',
                               `last_activity_date` datetime NOT NULL DEFAULT '2000-12-31 00:00:00' COMMENT 'Date last activity update to the invoice',
                               `aging_date` datetime NOT NULL DEFAULT '2000-12-30 00:00:00' COMMENT 'Date aging was last calculated',
                               `age_days` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Age of invoice balance',
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
                                    `quantity` decimal(25,6) NOT NULL DEFAULT '0.000000',
                                    `product_id` int(11) UNSIGNED DEFAULT NULL,
                                    `unit_price` decimal(25,6) DEFAULT '0.000000',
                                    `tax_amount` decimal(25,6) DEFAULT '0.000000',
                                    `gross_total` decimal(25,6) DEFAULT '0.000000',
                                    `description` text COLLATE utf8_unicode_ci,
                                    `total` decimal(25,6) DEFAULT '0.000000',
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
                                               `attachment` blob COMMENT 'Attached object'
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
                          `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `user_id` int(11) UNSIGNED DEFAULT NULL,
                          `sqlquerie` text COLLATE utf8_unicode_ci,
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
                              `ac_notes` text COLLATE utf8_unicode_ci,
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
                                    `pt_enabled` tinyint(1) NOT NULL DEFAULT '1'
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_products`
--

CREATE TABLE `si_products` (
                               `id` int(11) UNSIGNED NOT NULL,
                               `domain_id` int(11) UNSIGNED NOT NULL,
                               `description` text COLLATE utf8_unicode_ci,
                               `unit_price` decimal(25,6) DEFAULT '0.000000',
                               `default_tax_id` int(11) UNSIGNED DEFAULT NULL,
                               `default_tax_id_2` int(11) UNSIGNED DEFAULT NULL,
                               `cost` decimal(25,6) DEFAULT '0.000000',
                               `reorder_level` int(11) DEFAULT NULL,
                               `custom_field1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                               `custom_field2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                               `custom_field3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                               `custom_field4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                               `notes` text COLLATE utf8_unicode_ci,
                               `enabled` tinyint(1) NOT NULL DEFAULT '1',
                               `visible` tinyint(1) NOT NULL DEFAULT '1',
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
                                          `type_id` int(11) DEFAULT NULL,
                                          `enabled` tinyint(1) NOT NULL DEFAULT '1',
                                          `visible` tinyint(1) NOT NULL DEFAULT '1'
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
                                      `enabled` tinyint(1) NOT NULL DEFAULT '1'
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
                                       `sql_statement` text COLLATE utf8_unicode_ci,
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
                          `tax_percentage` decimal(25,6) DEFAULT '0.000000',
                          `type` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
                          `tax_enabled` tinyint(1) NOT NULL DEFAULT '1',
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
                           `enabled` tinyint(1) NOT NULL DEFAULT '1',
                           `user_id` int(11) NOT NULL DEFAULT '0',
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
    ADD KEY `biller_id` (`biller_id`);

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
    ADD PRIMARY KEY (`id`);

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
    ADD UNIQUE KEY `id` (`id`);

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
    ADD KEY `IdxDI` (`index_id`,`preference_id`,`domain_id`);

--
-- Indexes for table `si_invoice_items`
--
ALTER TABLE `si_invoice_items`
    ADD PRIMARY KEY (`id`),
    ADD KEY `invoice_id` (`invoice_id`),
    ADD KEY `DomainInv` (`invoice_id`,`domain_id`);

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
    ADD PRIMARY KEY (`id`);

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
    ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `si_payment`
--
ALTER TABLE `si_payment`
    ADD PRIMARY KEY (`domain_id`,`id`),
    ADD UNIQUE KEY `id` (`id`),
    ADD KEY `domain_id` (`domain_id`),
    ADD KEY `ac_inv_id` (`ac_inv_id`),
    ADD KEY `ac_amount` (`ac_amount`);

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
    ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `si_products_attributes`
--
ALTER TABLE `si_products_attributes`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `si_products_attribute_type`
--
ALTER TABLE `si_products_attribute_type`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `si_products_values`
--
ALTER TABLE `si_products_values`
    ADD PRIMARY KEY (`id`);

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
    ADD UNIQUE KEY `uname` (`username`);

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
    ADD CONSTRAINT `si_expense_ibfk_1` FOREIGN KEY (`biller_id`) REFERENCES `si_biller` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
