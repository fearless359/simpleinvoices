-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2019 at 04:29 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simpleinvoices`
--

-- --------------------------------------------------------

--
-- Table structure for table `si_biller`
--

CREATE TABLE IF NOT EXISTS `si_biller`
(
  `id`                     int(10)                              NOT NULL,
  `domain_id`              int(11)                              NOT NULL DEFAULT '1',
  `name`                   varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `street_address`         varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `street_address2`        varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `city`                   varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `state`                  varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `zip_code`               varchar(20) COLLATE utf8_unicode_ci           DEFAULT NULL,
  `country`                varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `phone`                  varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `mobile_phone`           varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `fax`                    varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `email`                  varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `signature`              varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Email signature',
  `logo`                   varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `footer`                 text COLLATE utf8_unicode_ci,
  `paypal_business_name`   varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `paypal_notify_url`      varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `paypal_return_url`      varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `eway_customer_id`       varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `paymentsgateway_api_id` varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `notes`                  text COLLATE utf8_unicode_ci,
  `custom_field1`          varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `custom_field2`          varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `custom_field3`          varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `custom_field4`          varchar(255) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `enabled`                tinyint(1)                           NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_cron`
--

CREATE TABLE IF NOT EXISTS `si_cron`
(
  `id`              int(11)                             NOT NULL,
  `domain_id`       int(11)                             NOT NULL,
  `invoice_id`      int(11)                             NOT NULL,
  `start_date`      date                                NOT NULL,
  `end_date`        varchar(10) COLLATE utf8_unicode_ci          DEFAULT NULL,
  `recurrence`      int(11)                             NOT NULL,
  `recurrence_type` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `email_biller`    tinyint(1)                          NOT NULL DEFAULT '0',
  `email_customer`  tinyint(1)                          NOT NULL DEFAULT '0'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_cron_log`
--

CREATE TABLE IF NOT EXISTS `si_cron_log`
(
  `id`        int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `cron_id`   int(11) DEFAULT NULL,
  `run_date`  date    NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_customers`
--

CREATE TABLE IF NOT EXISTS `si_customers`
(
  `id`                       int(10)          NOT NULL,
  `domain_id`                int(11)          NOT NULL            DEFAULT '1',
  `attention`                varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name`                     varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `department`               varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street_address`           varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street_address2`          varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city`                     varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state`                    varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip_code`                 varchar(20) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `country`                  varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone`                    varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_phone`             varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax`                      varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email`                    varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit_card_holder_name`  varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit_card_number`       varchar(20) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `credit_card_expiry_month` varchar(2) COLLATE utf8_unicode_ci   DEFAULT NULL,
  `credit_card_expiry_year`  varchar(4) COLLATE utf8_unicode_ci   DEFAULT NULL,
  `notes`                    text COLLATE utf8_unicode_ci,
  `default_invoice`          int(10) UNSIGNED NOT NULL            DEFAULT '0' COMMENT 'Invoice index_id value to use as the default template',
  `custom_field1`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field2`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field3`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field4`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_customer_id`       int(11)                              DEFAULT NULL,
  `enabled`                  tinyint(1)       NOT NULL            DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_custom_fields`
--

CREATE TABLE IF NOT EXISTS `si_custom_fields`
(
  `cf_id`           int(11)    NOT NULL,
  `cf_custom_field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cf_custom_label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cf_display`      tinyint(1) NOT NULL                  DEFAULT '1',
  `domain_id`       int(11)    NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_custom_flags`
--

CREATE TABLE IF NOT EXISTS `si_custom_flags`
(
  `domain_id`        int(11)                          NOT NULL,
  `associated_table` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `flg_id`           tinyint(3) UNSIGNED              NOT NULL COMMENT 'Flag number ranging from 1 to 10',
  `field_label`      varchar(20) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `enabled`          tinyint(1)                       NOT NULL COMMENT 'Defaults to enabled when record created. Can disable to retire flag.',
  `field_help`       varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci COMMENT ='Specifies an allowed setting for a flag field';

-- --------------------------------------------------------

--
-- Table structure for table `si_expense`
--

CREATE TABLE IF NOT EXISTS `si_expense`
(
  `id`                 int(11)        NOT NULL,
  `domain_id`          int(11)        NOT NULL,
  `amount`             decimal(25, 6) NOT NULL,
  `expense_account_id` int(11)        NOT NULL,
  `biller_id`          int(11)        NOT NULL,
  `customer_id`        int(11)        NOT NULL,
  `invoice_id`         int(11)        NOT NULL,
  `product_id`         int(11)        NOT NULL,
  `date`               date           NOT NULL,
  `note`               text COLLATE utf8_unicode_ci,
  `status`             tinyint(1)     NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_expense_account`
--

CREATE TABLE IF NOT EXISTS `si_expense_account`
(
  `id`        int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `name`      varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_expense_item_tax`
--

CREATE TABLE IF NOT EXISTS `si_expense_item_tax`
(
  `id`         int(11)        NOT NULL,
  `expense_id` int(11)        NOT NULL,
  `tax_id`     int(11)        NOT NULL,
  `tax_type`   varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate`   decimal(25, 6) NOT NULL,
  `tax_amount` decimal(25, 6) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_extensions`
--

CREATE TABLE IF NOT EXISTS `si_extensions`
(
  `id`          int(11)    NOT NULL,
  `domain_id`   int(11)    NOT NULL,
  `name`        varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled`     tinyint(1) NOT NULL                  DEFAULT '0'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_index`
--

CREATE TABLE IF NOT EXISTS `si_index`
(
  `id`         int(11)                             NOT NULL,
  `node`       varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `sub_node`   int(11)                             NOT NULL,
  `sub_node_2` int(11)                             NOT NULL,
  `domain_id`  int(11)                             NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_install_complete`
--

CREATE TABLE IF NOT EXISTS `si_install_complete`
(
  `completed` tinyint(1) NOT NULL COMMENT 'Flag SI install has completed'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci COMMENT ='Specifies an allowed setting for a flag field';

-- --------------------------------------------------------

--
-- Table structure for table `si_inventory`
--

CREATE TABLE IF NOT EXISTS `si_inventory`
(
  `id`         int(11)        NOT NULL,
  `domain_id`  int(11)        NOT NULL,
  `product_id` int(11)        NOT NULL,
  `quantity`   decimal(25, 6) NOT NULL,
  `cost`       decimal(25, 6) DEFAULT NULL,
  `date`       date           NOT NULL,
  `note`       text COLLATE utf8_unicode_ci
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoices`
--

CREATE TABLE IF NOT EXISTS `si_invoices`
(
  `id`                   int(10)                            NOT NULL,
  `index_id`             int(11)                            NOT NULL,
  `domain_id`            int(11)                            NOT NULL DEFAULT '1',
  `biller_id`            int(10)                            NOT NULL DEFAULT '0',
  `customer_id`          int(10)                            NOT NULL DEFAULT '0',
  `type_id`              int(10)                            NOT NULL DEFAULT '0',
  `preference_id`        int(10)                            NOT NULL DEFAULT '0',
  `date`                 datetime                           NOT NULL DEFAULT '0000-00-00 00:00:00',
  `custom_field1`        varchar(50) COLLATE utf8_unicode_ci         DEFAULT NULL,
  `custom_field2`        varchar(50) COLLATE utf8_unicode_ci         DEFAULT NULL,
  `custom_field3`        varchar(50) COLLATE utf8_unicode_ci         DEFAULT NULL,
  `custom_field4`        varchar(50) COLLATE utf8_unicode_ci         DEFAULT NULL,
  `note`                 text COLLATE utf8_unicode_ci,
  `owing`                decimal(25, 6)                     NOT NULL DEFAULT '0.000000' COMMENT 'Amount owing as of aging-date',
  `last_activity_date`   datetime                           NOT NULL DEFAULT '2000-12-31 00:00:00' COMMENT 'Date last activity update to the invoice',
  `aging_date`           datetime                           NOT NULL DEFAULT '2000-12-30 00:00:00' COMMENT 'Date aging was last calculated',
  `age_days`             smallint(5) UNSIGNED               NOT NULL DEFAULT '0' COMMENT 'Age of invoice balance',
  `aging`                varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Aging string (1-14, 15-30, etc.',
  `sales_representative` varchar(50) COLLATE utf8_unicode_ci         DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoice_items`
--

CREATE TABLE IF NOT EXISTS `si_invoice_items`
(
  `id`          int(10)        NOT NULL,
  `invoice_id`  int(10)        NOT NULL              DEFAULT '0',
  `domain_id`   int(11)        NOT NULL              DEFAULT '1',
  `quantity`    decimal(25, 6) NOT NULL              DEFAULT '0.000000',
  `product_id`  int(10)                              DEFAULT '0',
  `unit_price`  decimal(25, 6)                       DEFAULT '0.000000',
  `tax_amount`  decimal(25, 6)                       DEFAULT '0.000000',
  `gross_total` decimal(25, 6)                       DEFAULT '0.000000',
  `description` text COLLATE utf8_unicode_ci,
  `total`       decimal(25, 6)                       DEFAULT '0.000000',
  `attribute`   varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoice_item_attachments`
--

CREATE TABLE IF NOT EXISTS `si_invoice_item_attachments`
(
  `id`              int(10) NOT NULL COMMENT 'Unique ID for this entry',
  `invoice_item_id` int(10) NOT NULL COMMENT 'ID of invoice item this attachment is associated with',
  `name`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attachment`      blob COMMENT 'Attached object'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci COMMENT ='Objects attached to an invoice item';

-- --------------------------------------------------------

--
-- Table structure for table `si_invoice_item_tax`
--

CREATE TABLE IF NOT EXISTS `si_invoice_item_tax`
(
  `id`              int(11)        NOT NULL,
  `invoice_item_id` int(11)        NOT NULL,
  `tax_id`          int(11)        NOT NULL,
  `tax_type`        char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_rate`        decimal(25, 6) NOT NULL,
  `tax_amount`      decimal(25, 6) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoice_type`
--

CREATE TABLE IF NOT EXISTS `si_invoice_type`
(
  `inv_ty_id`          int(11) NOT NULL,
  `inv_ty_description` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_log`
--

CREATE TABLE IF NOT EXISTS `si_log`
(
  `id`        bigint(20) NOT NULL,
  `domain_id` int(11)    NOT NULL DEFAULT '1',
  `timestamp` timestamp  NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid`    int(11)    NOT NULL DEFAULT '1',
  `sqlquerie` text COLLATE utf8_unicode_ci,
  `last_id`   int(11)             DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_payment`
--

CREATE TABLE IF NOT EXISTS `si_payment`
(
  `id`                int(10)        NOT NULL,
  `ac_inv_id`         int(11)        NOT NULL,
  `ac_amount`         decimal(25, 6) NOT NULL,
  `ac_notes`          text COLLATE utf8_unicode_ci,
  `ac_date`           datetime       NOT NULL,
  `ac_payment_type`   int(10)        NOT NULL              DEFAULT '1',
  `domain_id`         int(11)        NOT NULL,
  `online_payment_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ac_check_number`   varchar(10) COLLATE utf8_unicode_ci  DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_payment_types`
--

CREATE TABLE IF NOT EXISTS `si_payment_types`
(
  `pt_id`          int(10)    NOT NULL,
  `domain_id`      int(11)    NOT NULL                  DEFAULT '1',
  `pt_description` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pt_enabled`     tinyint(1) NOT NULL                  DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_preferences`
--

CREATE TABLE IF NOT EXISTS `si_preferences`
(
  `pref_id`                      int(11)    NOT NULL,
  `domain_id`                    int(11)    NOT NULL                  DEFAULT '1',
  `pref_description`             varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `pref_currency_sign`           varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `pref_inv_heading`             varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `pref_inv_wording`             varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `pref_inv_detail_heading`      varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `pref_inv_detail_line`         text COLLATE utf8_unicode_ci,
  `pref_inv_payment_method`      varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `pref_inv_payment_line1_name`  varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `pref_inv_payment_line1_value` varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `pref_inv_payment_line2_name`  varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `pref_inv_payment_line2_value` varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `pref_enabled`                 tinyint(1) NOT NULL                  DEFAULT '1',
  `status`                       tinyint(1) NOT NULL,
  `locale`                       varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language`                     varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `index_group`                  int(11)    NOT NULL,
  `currency_code`                varchar(25) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `include_online_payment`       varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_position`            varchar(25) COLLATE utf8_unicode_ci  DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_products`
--

CREATE TABLE IF NOT EXISTS `si_products`
(
  `id`                   int(11)    NOT NULL,
  `domain_id`            int(11)    NOT NULL                  DEFAULT '1',
  `description`          text COLLATE utf8_unicode_ci,
  `unit_price`           decimal(25, 6)                       DEFAULT '0.000000',
  `default_tax_id`       int(11)                              DEFAULT NULL,
  `default_tax_id_2`     int(11)                              DEFAULT NULL,
  `cost`                 decimal(25, 6)                       DEFAULT '0.000000',
  `reorder_level`        int(11)                              DEFAULT NULL,
  `custom_field1`        varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field2`        varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field3`        varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_field4`        varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes`                text COLLATE utf8_unicode_ci,
  `enabled`              tinyint(1) NOT NULL                  DEFAULT '1',
  `visible`              tinyint(1) NOT NULL                  DEFAULT '1',
  `attribute`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes_as_description` char(1) COLLATE utf8_unicode_ci      DEFAULT NULL,
  `show_description`     char(1) COLLATE utf8_unicode_ci      DEFAULT NULL,
  `custom_flags`         char(10) COLLATE utf8_unicode_ci     DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_products_attributes`
--

CREATE TABLE IF NOT EXISTS `si_products_attributes`
(
  `id`      int(11)    NOT NULL,
  `name`    varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type_id` int(11)                              DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL                  DEFAULT '1',
  `visible` tinyint(1) NOT NULL                  DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_products_attribute_type`
--

CREATE TABLE IF NOT EXISTS `si_products_attribute_type`
(
  `id`   int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_products_values`
--

CREATE TABLE IF NOT EXISTS `si_products_values`
(
  `id`           int(11)    NOT NULL,
  `attribute_id` int(11)    NOT NULL,
  `value`        varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled`      tinyint(1) NOT NULL                  DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_sql_patchmanager`
--

CREATE TABLE IF NOT EXISTS `si_sql_patchmanager`
(
  `sql_id`        int(11) NOT NULL,
  `sql_patch_ref` int(11) NOT NULL,
  `sql_patch`     varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sql_release`   varchar(25) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `sql_statement` text COLLATE utf8_unicode_ci,
  `source`        varchar(20) COLLATE utf8_unicode_ci  DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_system_defaults`
--

CREATE TABLE IF NOT EXISTS `si_system_defaults`
(
  `id`           int(11) NOT NULL,
  `name`         varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value`        varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domain_id`    int(5)  NOT NULL                    DEFAULT '0',
  `extension_id` int(5)  NOT NULL                    DEFAULT '0'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_tax`
--

CREATE TABLE IF NOT EXISTS `si_tax`
(
  `tax_id`          int(11)    NOT NULL,
  `tax_description` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_percentage`  decimal(25, 6)                      DEFAULT '0.000000',
  `type`            char(1) COLLATE utf8_unicode_ci     DEFAULT NULL,
  `tax_enabled`     tinyint(1) NOT NULL                 DEFAULT '1',
  `domain_id`       int(11)    NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_user`
--

CREATE TABLE IF NOT EXISTS `si_user`
(
  `id`        int(11)    NOT NULL,
  `email`     varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id`   int(11)                              DEFAULT NULL,
  `domain_id` int(11)    NOT NULL                  DEFAULT '0',
  `password`  varchar(64) COLLATE utf8_unicode_ci  DEFAULT NULL,
  `enabled`   tinyint(1) NOT NULL                  DEFAULT '1',
  `user_id`   int(11)    NOT NULL                  DEFAULT '0',
  `username`  varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_user_domain`
--

CREATE TABLE IF NOT EXISTS `si_user_domain`
(
  `id`   int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `si_user_role`
--

CREATE TABLE IF NOT EXISTS `si_user_role`
(
  `id`   int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `si_biller`
--
ALTER TABLE `si_biller`
  ADD PRIMARY KEY (`domain_id`, `id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `si_cron`
--
ALTER TABLE `si_cron`
  ADD PRIMARY KEY (`domain_id`, `id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `si_cron_log`
--
ALTER TABLE `si_cron_log`
  ADD PRIMARY KEY (`domain_id`, `id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `CronIdUnq` (`domain_id`, `cron_id`, `run_date`),
  ADD KEY `cron_id` (`cron_id`);

--
-- Indexes for table `si_customers`
--
ALTER TABLE `si_customers`
  ADD PRIMARY KEY (`domain_id`, `id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `si_custom_fields`
--
ALTER TABLE `si_custom_fields`
  ADD PRIMARY KEY (`cf_id`, `domain_id`),
  ADD UNIQUE KEY `cf_id` (`cf_id`);

--
-- Indexes for table `si_custom_flags`
--
ALTER TABLE `si_custom_flags`
  ADD PRIMARY KEY (`domain_id`, `associated_table`, `flg_id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `dtable` (`domain_id`, `associated_table`);

--
-- Indexes for table `si_expense`
--
ALTER TABLE `si_expense`
  ADD PRIMARY KEY (`domain_id`, `id`),
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
  ADD PRIMARY KEY (`domain_id`, `id`),
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
  ADD PRIMARY KEY (`id`, `domain_id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `si_index`
--
ALTER TABLE `si_index`
  ADD PRIMARY KEY (`node`, `sub_node`, `sub_node_2`, `domain_id`);

--
-- Indexes for table `si_inventory`
--
ALTER TABLE `si_inventory`
  ADD PRIMARY KEY (`domain_id`, `id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `si_invoices`
--
ALTER TABLE `si_invoices`
  ADD PRIMARY KEY (`domain_id`, `id`),
  ADD UNIQUE KEY `UniqDIB` (`index_id`, `preference_id`, `biller_id`, `domain_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `biller_id` (`biller_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `IdxDI` (`index_id`, `preference_id`, `domain_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `preference_id` (`preference_id`);

--
-- Indexes for table `si_invoice_items`
--
ALTER TABLE `si_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `DomainInv` (`invoice_id`, `domain_id`),
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
  ADD PRIMARY KEY (`id`, `domain_id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `si_payment`
--
ALTER TABLE `si_payment`
  ADD PRIMARY KEY (`domain_id`, `id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `ac_inv_id` (`ac_inv_id`),
  ADD KEY `ac_amount` (`ac_amount`),
  ADD KEY `ac_payment_type` (`ac_payment_type`);

--
-- Indexes for table `si_payment_types`
--
ALTER TABLE `si_payment_types`
  ADD PRIMARY KEY (`domain_id`, `pt_id`),
  ADD UNIQUE KEY `pt_id` (`pt_id`);

--
-- Indexes for table `si_preferences`
--
ALTER TABLE `si_preferences`
  ADD PRIMARY KEY (`domain_id`, `pref_id`),
  ADD UNIQUE KEY `pref_id` (`pref_id`);

--
-- Indexes for table `si_products`
--
ALTER TABLE `si_products`
  ADD PRIMARY KEY (`domain_id`, `id`),
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
  ADD PRIMARY KEY (`domain_id`, `id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `UnqNameInDomain` (`domain_id`, `name`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `si_tax`
--
ALTER TABLE `si_tax`
  ADD PRIMARY KEY (`domain_id`, `tax_id`),
  ADD UNIQUE KEY `tax_id` (`tax_id`);

--
-- Indexes for table `si_user`
--
ALTER TABLE `si_user`
  ADD PRIMARY KEY (`domain_id`, `id`),
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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_cron`
--
ALTER TABLE `si_cron`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_cron_log`
--
ALTER TABLE `si_cron_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_customers`
--
ALTER TABLE `si_customers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_custom_fields`
--
ALTER TABLE `si_custom_fields`
  MODIFY `cf_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_expense`
--
ALTER TABLE `si_expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_expense_account`
--
ALTER TABLE `si_expense_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_expense_item_tax`
--
ALTER TABLE `si_expense_item_tax`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_extensions`
--
ALTER TABLE `si_extensions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_inventory`
--
ALTER TABLE `si_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_invoices`
--
ALTER TABLE `si_invoices`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_invoice_items`
--
ALTER TABLE `si_invoice_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_invoice_item_attachments`
--
ALTER TABLE `si_invoice_item_attachments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for this entry';

--
-- AUTO_INCREMENT for table `si_invoice_item_tax`
--
ALTER TABLE `si_invoice_item_tax`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_invoice_type`
--
ALTER TABLE `si_invoice_type`
  MODIFY `inv_ty_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_log`
--
ALTER TABLE `si_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_payment`
--
ALTER TABLE `si_payment`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_payment_types`
--
ALTER TABLE `si_payment_types`
  MODIFY `pt_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_preferences`
--
ALTER TABLE `si_preferences`
  MODIFY `pref_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_products`
--
ALTER TABLE `si_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_products_attributes`
--
ALTER TABLE `si_products_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_products_attribute_type`
--
ALTER TABLE `si_products_attribute_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_products_values`
--
ALTER TABLE `si_products_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_sql_patchmanager`
--
ALTER TABLE `si_sql_patchmanager`
  MODIFY `sql_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_system_defaults`
--
ALTER TABLE `si_system_defaults`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_tax`
--
ALTER TABLE `si_tax`
  MODIFY `tax_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_user`
--
ALTER TABLE `si_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_user_domain`
--
ALTER TABLE `si_user_domain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `si_user_role`
--
ALTER TABLE `si_user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
                              `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`, `status`, `locale`, `language`, `index_group`, `currency_code`, `include_online_payment`, `currency_position`)
VALUES (1, 1, 'Invoice', '$', 'Invoice', 'Invoice', 'Details', 'Payment is to be made within 14 days of the invoice being sent', 'Electronic Funds Transfer', 'Account name', 'H. & M. Simpson', 'Account number:', '0123-4567-7890', '1', 1, 'en_GB', 'en_GB',
        1, 'USD', NULL, 'left');

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
INSERT INTO `si_sql_patchmanager`(`sql_patch_ref`, `sql_patch`, `sql_release`, `sql_statement`, `source`)
VALUES (1, '', '', '', 'original')
     , (2, '', '', '', 'original')
     , (3, '', '', '', 'original')
     , (4, '', '', '', 'original')
     , (5, '', '', '', 'original')
     , (6, '', '', '', 'original')
     , (7, '', '', '', 'original')
     , (8, '', '', '', 'original')
     , (9, '', '', '', 'original')
     , (10, '', '', '', 'original')
     , (11, '', '', '', 'original')
     , (12, '', '', '', 'original')
     , (13, '', '', '', 'original')
     , (14, '', '', '', 'original')
     , (15, '', '', '', 'original')
     , (16, '', '', '', 'original')
     , (17, '', '', '', 'original')
     , (18, '', '', '', 'original')
     , (19, '', '', '', 'original')
     , (20, '', '', '', 'original')
     , (21, '', '', '', 'original')
     , (22, '', '', '', 'original')
     , (23, '', '', '', 'original')
     , (24, '', '', '', 'original')
     , (25, '', '', '', 'original')
     , (26, '', '', '', 'original')
     , (27, '', '', '', 'original')
     , (28, '', '', '', 'original')
     , (29, '', '', '', 'original')
     , (30, '', '', '', 'original')
     , (31, '', '', '', 'original')
     , (32, '', '', '', 'original')
     , (33, '', '', '', 'original')
     , (34, '', '', '', 'original')
     , (35, '', '', '', 'original')
     , (36, '', '', '', 'original')
     , (37, '', '', '', 'original')
     , (38, '', '', '', 'original')
     , (39, '', '', '', 'original')
     , (40, '', '', '', 'original')
     , (41, '', '', '', 'original')
     , (42, '', '', '', 'original')
     , (43, '', '', '', 'original')
     , (44, '', '', '', 'original')
     , (45, '', '', '', 'original')
     , (46, '', '', '', 'original')
     , (47, '', '', '', 'original')
     , (48, '', '', '', 'original')
     , (49, '', '', '', 'original')
     , (50, '', '', '', 'original')
     , (51, '', '', '', 'original')
     , (52, '', '', '', 'original')
     , (53, '', '', '', 'original')
     , (54, '', '', '', 'original')
     , (55, '', '', '', 'original')
     , (56, '', '', '', 'original')
     , (57, '', '', '', 'original')
     , (58, '', '', '', 'original')
     , (59, '', '', '', 'original')
     , (60, '', '', '', 'original')
     , (61, '', '', '', 'original')
     , (62, '', '', '', 'original')
     , (63, '', '', '', 'original')
     , (64, '', '', '', 'original')
     , (65, '', '', '', 'original')
     , (66, '', '', '', 'original')
     , (67, '', '', '', 'original')
     , (68, '', '', '', 'original')
     , (69, '', '', '', 'original')
     , (70, '', '', '', 'original')
     , (71, '', '', '', 'original')
     , (72, '', '', '', 'original')
     , (73, '', '', '', 'original')
     , (74, '', '', '', 'original')
     , (75, '', '', '', 'original')
     , (76, '', '', '', 'original')
     , (77, '', '', '', 'original')
     , (78, '', '', '', 'original')
     , (79, '', '', '', 'original')
     , (80, '', '', '', 'original')
     , (81, '', '', '', 'original')
     , (82, '', '', '', 'original')
     , (83, '', '', '', 'original')
     , (84, '', '', '', 'original')
     , (85, '', '', '', 'original')
     , (86, '', '', '', 'original')
     , (87, '', '', '', 'original')
     , (88, '', '', '', 'original')
     , (89, '', '', '', 'original')
     , (90, '', '', '', 'original')
     , (91, '', '', '', 'original')
     , (92, '', '', '', 'original')
     , (93, '', '', '', 'original')
     , (94, '', '', '', 'original')
     , (95, '', '', '', 'original')
     , (96, '', '', '', 'original')
     , (97, '', '', '', 'original')
     , (98, '', '', '', 'original')
     , (99, '', '', '', 'original')
     , (100, '', '', '', 'original')
     , (101, '', '', '', 'original')
     , (102, '', '', '', 'original')
     , (103, '', '', '', 'original')
     , (104, '', '', '', 'original')
     , (105, '', '', '', 'original')
     , (106, '', '', '', 'original')
     , (107, '', '', '', 'original')
     , (108, '', '', '', 'original')
     , (109, '', '', '', 'original')
     , (110, '', '', '', 'original')
     , (111, '', '', '', 'original')
     , (112, '', '', '', 'original')
     , (113, '', '', '', 'original')
     , (114, '', '', '', 'original')
     , (115, '', '', '', 'original')
     , (116, '', '', '', 'original')
     , (117, '', '', '', 'original')
     , (118, '', '', '', 'original')
     , (119, '', '', '', 'original')
     , (120, '', '', '', 'original')
     , (121, '', '', '', 'original')
     , (122, '', '', '', 'original')
     , (123, '', '', '', 'original')
     , (124, '', '', '', 'original')
     , (125, '', '', '', 'original')
     , (126, '', '', '', 'original')
     , (127, '', '', '', 'original')
     , (128, '', '', '', 'original')
     , (129, '', '', '', 'original')
     , (130, '', '', '', 'original')
     , (131, '', '', '', 'original')
     , (132, '', '', '', 'original')
     , (133, '', '', '', 'original')
     , (134, '', '', '', 'original')
     , (135, '', '', '', 'original')
     , (136, '', '', '', 'original')
     , (137, '', '', '', 'original')
     , (138, '', '', '', 'original')
     , (139, '', '', '', 'original')
     , (140, '', '', '', 'original')
     , (141, '', '', '', 'original')
     , (142, '', '', '', 'original')
     , (143, '', '', '', 'original')
     , (144, '', '', '', 'original')
     , (145, '', '', '', 'original')
     , (146, '', '', '', 'original')
     , (147, '', '', '', 'original')
     , (148, '', '', '', 'original')
     , (149, '', '', '', 'original')
     , (150, '', '', '', 'original')
     , (151, '', '', '', 'original')
     , (152, '', '', '', 'original')
     , (153, '', '', '', 'original')
     , (154, '', '', '', 'original')
     , (155, '', '', '', 'original')
     , (156, '', '', '', 'original')
     , (157, '', '', '', 'original')
     , (158, '', '', '', 'original')
     , (159, '', '', '', 'original')
     , (160, '', '', '', 'original')
     , (161, '', '', '', 'original')
     , (162, '', '', '', 'original')
     , (163, '', '', '', 'original')
     , (164, '', '', '', 'original')
     , (165, '', '', '', 'original')
     , (166, '', '', '', 'original')
     , (167, '', '', '', 'original')
     , (168, '', '', '', 'original')
     , (169, '', '', '', 'original')
     , (170, '', '', '', 'original')
     , (171, '', '', '', 'original')
     , (172, '', '', '', 'original')
     , (173, '', '', '', 'original')
     , (174, '', '', '', 'original')
     , (175, '', '', '', 'original')
     , (176, '', '', '', 'original')
     , (177, '', '', '', 'original')
     , (178, '', '', '', 'original')
     , (179, '', '', '', 'original')
     , (180, '', '', '', 'original')
     , (181, '', '', '', 'original')
     , (182, '', '', '', 'original')
     , (183, '', '', '', 'original')
     , (184, '', '', '', 'original')
     , (185, '', '', '', 'original')
     , (186, '', '', '', 'original')
     , (187, '', '', '', 'original')
     , (188, '', '', '', 'original')
     , (189, '', '', '', 'original')
     , (190, '', '', '', 'original')
     , (191, '', '', '', 'original')
     , (192, '', '', '', 'original')
     , (193, '', '', '', 'original')
     , (194, '', '', '', 'original')
     , (195, '', '', '', 'original')
     , (196, '', '', '', 'original')
     , (197, '', '', '', 'original')
     , (198, '', '', '', 'original')
     , (199, '', '', '', 'original')
     , (200, '', '', '', 'original')
     , (201, '', '', '', 'original')
     , (202, '', '', '', 'original')
     , (203, '', '', '', 'original')
     , (204, '', '', '', 'original')
     , (205, '', '', '', 'original')
     , (206, '', '', '', 'original')
     , (207, '', '', '', 'original')
     , (208, '', '', '', 'original')
     , (209, '', '', '', 'original')
     , (210, '', '', '', 'original')
     , (211, '', '', '', 'original')
     , (212, '', '', '', 'original')
     , (213, '', '', '', 'original')
     , (214, '', '', '', 'original')
     , (215, '', '', '', 'original')
     , (216, '', '', '', 'original')
     , (217, '', '', '', 'original')
     , (218, '', '', '', 'original')
     , (219, '', '', '', 'original')
     , (220, '', '', '', 'original')
     , (221, '', '', '', 'original')
     , (222, '', '', '', 'original')
     , (223, '', '', '', 'original')
     , (224, '', '', '', 'original')
     , (225, '', '', '', 'original')
     , (226, '', '', '', 'original')
     , (227, '', '', '', 'original')
     , (228, '', '', '', 'original')
     , (229, '', '', '', 'original')
     , (230, '', '', '', 'original')
     , (231, '', '', '', 'original')
     , (232, '', '', '', 'original')
     , (233, '', '', '', 'original')
     , (234, '', '', '', 'original')
     , (235, '', '', '', 'original')
     , (236, '', '', '', 'original')
     , (237, '', '', '', 'original')
     , (238, '', '', '', 'original')
     , (239, '', '', '', 'original')
     , (240, '', '', '', 'original')
     , (241, '', '', '', 'original')
     , (242, '', '', '', 'original')
     , (243, '', '', '', 'original')
     , (244, '', '', '', 'original')
     , (245, '', '', '', 'original')
     , (246, '', '', '', 'original')
     , (247, '', '', '', 'original')
     , (248, '', '', '', 'original')
     , (249, '', '', '', 'original')
     , (250, '', '', '', 'original')
     , (251, '', '', '', 'original')
     , (252, '', '', '', 'original')
     , (253, '', '', '', 'original')
     , (254, '', '', '', 'original')
     , (255, '', '', '', 'original')
     , (256, '', '', '', 'original')
     , (257, '', '', '', 'original')
     , (258, '', '', '', 'original')
     , (259, '', '', '', 'original')
     , (260, '', '', '', 'original')
     , (261, '', '', '', 'original')
     , (262, '', '', '', 'original')
     , (263, '', '', '', 'original')
     , (264, '', '', '', 'original')
     , (265, '', '', '', 'original')
     , (266, '', '', '', 'original')
     , (267, '', '', '', 'original')
     , (268, '', '', '', 'original')
     , (269, '', '', '', 'original')
     , (270, '', '', '', 'original')
     , (271, '', '', '', 'original')
     , (272, '', '', '', 'original')
     , (273, '', '', '', 'original')
     , (274, '', '', '', 'original')
     , (275, '', '', '', 'original')
     , (276, '', '', '', 'original')
     , (277, '', '', '', 'original')
     , (278, '', '', '', 'original')
     , (279, '', '', '', 'original')
     , (280, '', '', '', 'original')
     , (281, '', '', '', 'original')
     , (282, '', '', '', 'original')
     , (283, '', '', '', 'original')
     , (284, '', '', '', 'original')
     , (285, '', '', '', 'original')
     , (286, '', '', '', 'original')
     , (287, '', '', '', 'original')
     , (288, '', '', '', 'original')
     , (289, '', '', '', 'original')
     , (290, '', '', '', 'original')
     , (291, '', '', '', 'original')
     , (292, '', '', '', 'original')
     , (293, 'Add department to the customers', '20161004', 'ALTER TABLE `si_customers` ADD COLUMN `department` VARCHAR(255) NULL AFTER `name`', 'original')
     , (294, 'Add custom_flags table for products.', '20180922', '', 'fearless359')
     , (295, 'Add net income report.', '20180923', '', 'fearless359')
     , (296, 'Add past due report.', '20180924', '', 'fearless359')
     , (297, 'Add User Security enhancement fields and values', '20180924', '', 'fearless359')
     , (298, 'Add Signature field to the biller table.', '20181003', 'ALTER TABLE `si_biller` ADD `signature` varchar(255) DEFAULT "" NOT NULL COMMENT "Email signature" AFTER `email`', 'fearless359')
     , (299, 'Add check number field to the payment table.', '20181003', 'ALTER TABLE `si_payment` ADD `ac_check_number` varchar(10) DEFAULT "" NOT NULL COMMENT "Check number for CHECK payment types"', 'fearless359')
     , (300, 'Add install complete table.', '20181008', 'CREATE TABLE `si_install_complete` (`completed` tinyint(1) NOT NULL COMMENT "Flag SI install has completed") ENGINE=InnoDB COMMENT="Specifies an allowed setting for a flag field"', 'fearless359')
     , (301, 'Add last_activity_date, aging_date and aging_value to invoices.', '20181012',
        'ALTER TABLE `si_invoices` ADD `last_activity_date` DATE NULL COMMENT "Date last activity update to the invoice", ADD `aging_date` DATE NULL COMMENT "Date aging was last calculated", ADD `age_days` SMALLINT(5) UNSIGNED DEFAULT 0 NOT NULL COMMENT "Age of invoice balance"',
        'fearless359')
     , (302, 'Added owing to invoices table', '20181017', 'ALTER TABLE `si_invoices` ADD COLUMN `owing` DECIMAL(25,6) DEFAULT 0 NOT NULL COMMENT "Amount owing as of aging-date" AFTER "note"; UPDATE `si_invoices` SET owing = 1;', 'fearless359')
     , (303, 'Add invoice custom field report extension to standard application and add sales_representative field.', '20181018', 'ALTER TABLE `si_invoices` ADD `sales_representative` VARCHAR(50) DEFAULT "" NOT NULL;', 'fearless359')
     , (304, 'Add default_invoice field to the customers table.', '20181020', 'ALTER TABLE `si_customers` ADD `default_invoice` INT(10) UNSIGNED DEFAULT 0 NOT NULL COMMENT "Invoice index_id value to use as the default template" AFTER `notes`;',
        'fearless359')
     , (305, 'Add expense tables to the database.', '20181027',
        'CREATE TABLE `si_expense` (id INT(11) NOT NULL AUTO_INCREMENT UNIQUE KEY, domain_id INT(11) NOT NULL, amount DECIMAL(25,6) NOT NULL, expense_account_id INT(11) NOT NULL, biller_id INT(11) NOT NULL, customer_id INT(11) NOT NULL, invoice_id INT(11) NOT NULL, product_id INT(11) NOT NULL, date DATE NOT NULL, note TEXT NOT NULL) ENGINE = InnoDb;',
        'fearless359');

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

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
