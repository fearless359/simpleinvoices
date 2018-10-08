SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `si_biller` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `street_address2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT '' NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `footer` text,
  `paypal_business_name` varchar(255) DEFAULT NULL,
  `paypal_notify_url` varchar(255) DEFAULT NULL,
  `paypal_return_url` varchar(255) DEFAULT NULL,
  `eway_customer_id` varchar(255) DEFAULT NULL,
  `paymentsgateway_api_id` varchar(255) DEFAULT NULL,
  `notes` text,
  `custom_field1` varchar(255) DEFAULT NULL,
  `custom_field2` varchar(255) DEFAULT NULL,
  `custom_field3` varchar(255) DEFAULT NULL,
  `custom_field4` varchar(255) DEFAULT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

INSERT INTO `si_biller` (`id`, `domain_id`, `name`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `signature`, `logo`, `footer`, `paypal_business_name`, `paypal_notify_url`, `paypal_return_url`, `eway_customer_id`, `paymentsgateway_api_id`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`) VALUES
 (1, 1, 'Mr Plough', '43 Evergreen Terrace', '', 'Springfield', 'NY', '90245', '', '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@mrplough.com', '', 'ubuntulogo.png', '', '', '', '', '', '', '', '', '', '7898-87987-87', '', '1')
,(2, 1, 'Homer Simpson', '43 Evergreen Terrace', NULL, 'Springfield', 'NY', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@yahoo.com', '', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, '1')
,(3, 1, 'The Beer Baron', '43 Evergreen Terrace', NULL, 'Springfield', 'NY', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'beerbaron@yahoo.com', '', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, '1')
,(4, 1, 'Fawlty Towers', '13 Seaside Drive', NULL, 'Torquay', 'Brixton on Avon', '65894', 'United Kingdom', '089 6985 4569', '0425 5477 8789', '089 6985 4568', 'penny@fawltytowers.co.uk', '', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, '1');

CREATE TABLE IF NOT EXISTS `si_cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` varchar(10) DEFAULT NULL,
  `recurrence` int(11) NOT NULL,
  `recurrence_type` varchar(11) NOT NULL,
  `email_biller` TINYINT(1) DEFAULT 0 NOT NULL,
  `email_customer` TINYINT(1) DEFAULT 0 NOT NULL,
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_cron_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `cron_id` varchar(25) DEFAULT NULL,
  `run_date` date NOT NULL,
  PRIMARY KEY (`domain_id`,`id`),
  UNIQUE KEY `CronIdUnq` (`domain_id`, `cron_id`, `run_date`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_custom_fields` (
  `cf_id` int(11) NOT NULL AUTO_INCREMENT,
  `cf_custom_field` varchar(255) DEFAULT NULL,
  `cf_custom_label` varchar(255) DEFAULT NULL,
  `cf_display` TINYINT(1) DEFAULT 1 NOT NULL,
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY (`cf_id`, `domain_id`)
) ENGINE=MyISAM;

INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES
 (1, 'biller_cf1',   NULL, '0', 1)
,(2, 'biller_cf2',   NULL, '0', 1)
,(3, 'biller_cf3',   NULL, '0', 1)
,(4, 'biller_cf4',   NULL, '0', 1)
,(5, 'customer_cf1', NULL, '0', 1)
,(6, 'customer_cf2', NULL, '0', 1)
,(7, 'customer_cf3', NULL, '0', 1)
,(8, 'customer_cf4', NULL, '0', 1)
,(9, 'product_cf1',  NULL, '0', 1)
,(10, 'product_cf2', NULL, '0', 1)
,(11, 'product_cf3', NULL, '0', 1)
,(12, 'product_cf4', NULL, '0', 1)
,(13, 'invoice_cf1', NULL, '0', 1)
,(14, 'invoice_cf2', NULL, '0', 1)
,(15, 'invoice_cf3', NULL, '0', 1)
,(16, 'invoice_cf4', NULL, '0', 1);

CREATE TABLE `si_custom_flags` (
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `associated_table` char(10) NOT NULL COMMENT 'Table flag is associated with. Only defined for products for now.',
  `flg_id` tinyint(3) UNSIGNED NOT NULL COMMENT 'Flag number ranging from 1 to 10',
  `field_label` varchar(20) NOT NULL COMMENT 'Label to use on screen where option is set.',
  `enabled` tinyint(1) NOT NULL COMMENT 'Defaults to enabled when record created. Can disable to retire flag.',
  `field_help` varchar(255) NOT NULL COMMENT 'Help information to display for this field.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Specifies an allowed setting for a flag field';

INSERT INTO `si_custom_flags` (`domain_id`, `associated_table`, `flg_id`, `field_label`, `enabled`, `field_help`) VALUES
(1, 'products', 1, '', 0, ''),
(1, 'products', 2, '', 0, ''),
(1, 'products', 3, '', 0, ''),
(1, 'products', 4, '', 0, ''),
(1, 'products', 5, '', 0, ''),
(1, 'products', 6, '', 0, ''),
(1, 'products', 7, '', 0, ''),
(1, 'products', 8, '', 0, ''),
(1, 'products', 9, '', 0, ''),
(1, 'products', 10, '', 0, '');

ALTER TABLE `si_custom_flags`
  ADD PRIMARY KEY (`domain_id`,`associated_table`,`flg_id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `dtable` (`domain_id`,`associated_table`);

CREATE TABLE IF NOT EXISTS `si_customers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `attention` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `street_address2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `credit_card_holder_name` varchar(255) DEFAULT NULL,
  `credit_card_number` varchar(20) DEFAULT NULL,
  `credit_card_expiry_month` varchar(2) DEFAULT NULL,
  `credit_card_expiry_year` varchar(4) DEFAULT NULL,
  `notes` text,
  `custom_field1` varchar(255) DEFAULT NULL,
  `custom_field2` varchar(255) DEFAULT NULL,
  `custom_field3` varchar(255) DEFAULT NULL,
  `custom_field4` varchar(255) DEFAULT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

INSERT INTO `si_customers` (`id`, `domain_id`, `attention`, `name`, `department`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `credit_card_holder_name`, `credit_card_number`, `credit_card_expiry_month`, `credit_card_expiry_year`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`) VALUES
 (1, 1, 'Moe Sivloski', 'Moes Tavern', '', '45 Main Road', '', 'Springfield', 'NY', '65891', '', '04 1234 5698', '', '04 5689 4566', 'moe@moestavern.com', '', '', '', '', '<p><strong>Moe&#39;s Tavern</strong> is a fictional <a href=&#39;http://en.wikipedia.org/wiki/Bar_%28establishment%29&#39; title=&#39;Bar (establishment)&#39;>bar</a> seen on <em><a href=&#39;http://en.wikipedia.org/wiki/The_Simpsons&#39; title=&#39;The Simpsons&#39;>The Simpsons</a></em>. The owner of the bar is <a href=&#39;http://en.wikipedia.org/wiki/Moe_Szyslak&#39; title=&#39;Moe Szyslak&#39;>Moe Szyslak</a>.</p> <p>In The Simpsons world, it is located on the corner of Walnut Street, neighboring King Toot&#39;s Music Store, across the street is the Moeview Motel, and a factory formerly owned by <a href=&#39;http://en.wikipedia.org/wiki/Bart_Simpson&#39; title=&#39;Bart Simpson&#39;>Bart Simpson</a>, until it collapsed. The inside of the bar has a few pool tables and a dartboard. It is very dank and &quot;smells like <a href=&#39;http://en.wikipedia.org/wiki/Urine&#39; title=&#39;Urine&#39;>tinkle</a>.&quot; Because female customers are so rare, Moe frequently uses the women&#39;s restroom as an office. Moe claimed that there haven&#39;t been any ladies at Moe&#39;s since <a href=&#39;http://en.wikipedia.org/wiki/1979&#39; title=&#39;1979&#39;>1979</a> (though earlier episodes show otherwise). A jar of pickled eggs perpetually stands on the bar. Another recurring element is a rat problem. This can be attributed to the episode <a href=&#39;http://en.wikipedia.org/wiki/Homer%27s_Enemy&#39; title=&#39;Homer&#39;s Enemy&#39;>Homer&#39;s Enemy</a> in which Bart&#39;s factory collapses, and the rats are then shown to find a new home at Moe&#39;s. In &quot;<a href=&#39;http://en.wikipedia.org/wiki/Who_Shot_Mr._Burns&#39; title=&#39;Who Shot Mr. Burns&#39;>Who Shot Mr. Burns</a>,&quot; Moe&#39;s Tavern was forced to close down because Mr. Burns&#39; slant-drilling operation near the tavern caused unsafe pollution. It was stated in the &quot;<a href=&#39;http://en.wikipedia.org/wiki/Flaming_Moe%27s&#39; title=&#39;Flaming Moe&#39;s&#39;>Flaming Moe&#39;s</a>&quot; episode that Moe&#39;s Tavern was on Walnut Street. The phone number would be 76484377, since in &quot;<a href=&#39;http://en.wikipedia.org/wiki/Homer_the_Smithers&#39; title=&#39;Homer the Smithers&#39;>Homer the Smithers</a>,&quot; Mr. Burns tried to call Smithers but did not know his phone number. He tried the buttons marked with the letters for Smithers and called Moe&#39;s. In &quot;<a href=&#39;http://en.wikipedia.org/wiki/Principal_Charming&#39; title=&#39;Principal Charming&#39;>Principal Charming</a>&quot; Bart is asked to call Homer by Principal Skinner, the number visible on the card is WORK: KLondike 5-6832 HOME: KLondike 5-6754 MOE&#39;S TAVERN: KLondike 5-1239 , Moe answers the phone and Bart asks for Homer Sexual. The bar serves <a href=&#39;http://en.wikipedia.org/wiki/Duff_Beer&#39; title=&#39;Duff Beer&#39;>Duff Beer</a> and Red Tick Beer, a beer flavored with dogs.</p>', '', '', '', '', '1')
,(2, 1, 'Mr Burns', 'Springfield Power Plant', '', '4 Power Plant Drive', '', 'Springfield', 'NY', '90210', '', '04 1235 5698', '', '04 5678 7899', 'mburns@spp.com', '', '', '', '', '<p><strong>Springfield Nuclear Power Plant</strong> is a fictional electricity generating facility in the <a href=&#39;http://en.wikipedia.org/wiki/Television&#39; title=&#39;Television&#39;>television</a> <a href=&#39;http://en.wikipedia.org/wiki/Animated_cartoon&#39; title=&#39;Animated cartoon&#39;>animated cartoon</a> series <em><a href=&#39;http://en.wikipedia.org/wiki/The_Simpsons&#39; title=&#39;The Simpsons&#39;>The Simpsons</a></em>. The plant has a <a href=&#39;http://en.wikipedia.org/wiki/Monopoly&#39; title=&#39;Monopoly&#39;>monopoly</a> on the city of <a href=&#39;http://en.wikipedia.org/wiki/Springfield_%28The_Simpsons%29&#39; title=&#39;Springfield (The Simpsons)&#39;>Springfield&#39;s</a> energy supply, but is sometimes mismanaged and endangers much of the town with its presence.</p> <p>Based on the plant&#39;s appearance and certain episode plots, it likely houses only a single &quot;unit&quot; or reactor (although, judging from the number of <a href=&#39;http://en.wikipedia.org/wiki/Containment_building&#39; title=&#39;Containment building&#39;>containment buildings</a> and <a href=&#39;http://en.wikipedia.org/wiki/Cooling_tower&#39; title=&#39;Cooling tower&#39;>cooling towers</a>, there is a chance it may have two). In one episode an emergency occurs and Homer resorts to the manual, which begins &quot;Congratulations on your purchase of a Fissionator 1952 Slow-Fission Reactor&quot;.</p> <p>The plant is poorly maintained, largely due to owner Montgomery Burns&#39; miserliness. Its <a href=&#39;http://en.wikipedia.org/wiki/Nuclear_safety&#39; title=&#39;Nuclear safety&#39;>safety record</a> is appalling, with various episodes showing luminous rats in the bowels of the building, pipes and drums leaking radioactive waste, the disposal of waste in a children&#39;s playground, <a href=&#39;http://en.wikipedia.org/wiki/Plutonium&#39; title=&#39;Plutonium&#39;>plutonium</a> used as a paperweight, cracked cooling towers (fixed in one episode using a piece of <a href=&#39;http://en.wikipedia.org/wiki/Chewing_gum&#39; title=&#39;Chewing gum&#39;>Chewing gum</a>), dangerously high <a href=&#39;http://en.wikipedia.org/wiki/Geiger_counter&#39; title=&#39;Geiger counter&#39;>Geiger counter</a> readings around the perimeter of the plant, and even a giant spider. In the opening credits a bar of some <a href=&#39;http://en.wikipedia.org/wiki/Radioactive&#39; title=&#39;Radioactive&#39;>radioactive</a> substance is trapped in Homer&#39;s overalls and later disposed of in the street.</p>', '13245-789798', '', '', '', '1')
,(3, 1, 'Kath Day-Knight', 'Kath and Kim Pty Ltd', '', '82 Fountain Drive', '', 'Fountain Lakes', 'VIC', '3567', 'Australia', '03 9658 7456', '', '03 9658 7457', 'kath@kathandkim.com.au', '', '', '', '', 'Kath Day-Knight (<a href=&#39;http://en.wikipedia.org/wiki/Jane_Turner&#39; title=&#39;Jane Turner&#39;>Jane Turner</a>) is an &#39;empty nester&#39; divorc&eacute;e who wants to enjoy time with her &quot;hunk o&#39; spunk&quot; Kel Knight (<a href=&#39;http://en.wikipedia.org/wiki/Glenn_Robbins&#39; title=&#39;Glenn Robbins&#39;>Glenn Robbins</a>), a local &quot;purveyor of fine meats&quot;, but whose lifestyle is often cramped by the presence of her self-indulgent and spoilt rotten twenty-something daughter Kim Craig <a href=&#39;http://en.wikipedia.org/wiki/List_of_French_phrases_used_by_English_speakers#I_.E2.80.93_Q&#39; title=&#39;List of French phrases used by English speakers&#39;>n&eacute;e</a> Day (<a href=&#39;http://en.wikipedia.org/wiki/Gina_Riley&#39; title=&#39;Gina Riley&#39;>Gina Riley</a>). Kim enjoys frequent and lengthy periods of spiteful estrangement from her forgiving husband Brett Craig (<a href=&#39;http://en.wikipedia.org/wiki/Peter_Rowsthorn&#39; title=&#39;Peter Rowsthorn&#39;>Peter Rowsthorn</a>) for imagined slights and misdemeanors, followed by loving reconciliations with him. During Kim and Brett&#39;s frequent rough patches Kim usually seeks solace from her servile &quot;second best friend&quot; Sharon Strzelecki (<a href=&#39;http://en.wikipedia.org/wiki/Magda_Szubanski&#39; title=&#39;Magda Szubanski&#39;>Magda Szubanski</a>), screaming abuse at Sharon for minor infractions while issuing her with intricately-instructed tasks, such as stalking Brett. Kim and Brett had a baby in the final episode of the second series whom they named Epponnee-Raelene Kathleen Darlene Charlene Craig, shortened to Epponnee-Rae.', '13245-789798', '', '', '', '1');

CREATE TABLE IF NOT EXISTS `si_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `enabled` TINYINT(1) DEFAULT 0 NOT NULL,
  PRIMARY KEY (`id`, `domain_id`)
) ENGINE=MyISAM;

INSERT INTO `si_extensions` (`id`, `domain_id`, `name`, `description`, `enabled`) VALUES
 (1, 0, 'core', 'Core part of SimpleInvoices - always enabled', '1');

CREATE TABLE IF NOT EXISTS `si_index` (
  `id` int(11) NOT NULL,
  `node` varchar(255) NOT NULL,
  `sub_node` varchar(255) DEFAULT NULL,
  `sub_node_2` varchar(255) DEFAULT NULL,
  `domain_id` int(11) NOT NULL
) ENGINE=MyISAM;

INSERT INTO `si_index` (`id`, `node`, `sub_node`, `sub_node_2`, `domain_id`) VALUES
 (1, 'invoice', '1', '', 1);

CREATE TABLE `si_install_complete` (
  `completed` tinyint(1) NOT NULL COMMENT 'Flag SI install has completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `si_install_complete` (`completed`) VALUES (1);

CREATE TABLE IF NOT EXISTS `si_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(25,6) NOT NULL,
  `cost` decimal(25,6) DEFAULT NULL,
  `date` date NOT NULL,
  `note` text,
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_invoice_item_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_item_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_type` CHAR(1) DEFAULT '%' NOT NULL,
  `tax_rate` decimal(25,6) NOT NULL,
  `tax_amount` decimal(25,6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UnqInvTax` (`invoice_item_id`, `tax_id`)
) ENGINE=MyISAM;

INSERT INTO `si_invoice_item_tax` (`id`, `invoice_item_id`, `tax_id`, `tax_type`, `tax_rate`, `tax_amount`) VALUES
 (1, 1, 3, '%', 10.000000, 12.500000)
,(2, 2, 1, '%', 10.000000, 12.500000)
,(3, 3, 4, '%', 0.000000, 0.000000)
,(4, 4, 1, '%', 10.000000, 14.000000)
,(5, 5, 4, '%', 0.000000, 0.000000);

CREATE TABLE IF NOT EXISTS `si_invoice_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(10) NOT NULL DEFAULT '0',
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `quantity` decimal(25,6) NOT NULL DEFAULT '0.000000',
  `product_id` int(10) DEFAULT '0',
  `unit_price` decimal(25,6) DEFAULT '0.000000',
  `tax_amount` decimal(25,6) DEFAULT '0.000000',
  `gross_total` decimal(25,6) DEFAULT '0.000000',
  `description` text,
  `total` decimal(25,6) DEFAULT '0.000000',
  `attribute` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `DomainInv` (`invoice_id`, `domain_id`)
) ENGINE=MyISAM;

INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `domain_id`, `quantity`, `product_id`, `unit_price`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
 (1, 1, 1, 1.000000, 5, 125.000000, 12.500000, 125.000000, '', 137.500000)
,(2, 1, 1, 1.000000, 3, 125.000000, 12.500000, 125.000000, '', 137.500000)
,(3, 1, 1, 1.000000, 2, 140.000000, 0.000000, 140.000000, '', 140.000000)
,(4, 1, 1, 1.000000, 2, 140.000000, 14.000000, 140.000000, '', 154.000000)
,(5, 1, 1, 1.000000, 1, 150.000000, 0.000000, 150.000000, '', 150.000000);

CREATE TABLE IF NOT EXISTS `si_invoice_type` (
  `inv_ty_id` int(11) NOT NULL AUTO_INCREMENT,
  `inv_ty_description` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`inv_ty_id`)
) ENGINE=MyISAM;

INSERT INTO `si_invoice_type` (`inv_ty_id`, `inv_ty_description`) VALUES
 (1, 'Total')
,(2, 'Itemized')
,(3, 'Consulting');

CREATE TABLE IF NOT EXISTS `si_invoices` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `index_id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `biller_id` int(10) NOT NULL DEFAULT '0',
  `customer_id` int(10) NOT NULL DEFAULT '0',
  `type_id` int(10) NOT NULL DEFAULT '0',
  `preference_id` int(10) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `custom_field1` varchar(50) DEFAULT NULL,
  `custom_field2` varchar(50) DEFAULT NULL,
  `custom_field3` varchar(50) DEFAULT NULL,
  `custom_field4` varchar(50) DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `domain_id` (`domain_id`),
  KEY `biller_id` (`biller_id`),
  KEY `customer_id` (`customer_id`),
  KEY `UniqDIB` (`index_id`, `preference_id`, `biller_id`, `domain_id`), 
  KEY `IdxDI` (`index_id`, `preference_id`, `domain_id`)
) ENGINE=MyISAM;

INSERT INTO `si_invoices` (`id`, `index_id`, `domain_id`, `biller_id`, `customer_id`, `type_id`, `preference_id`, `date`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `note`) VALUES
 (1, 1, 1, 4, 3, 2, 1, '2008-12-30 00:00:00', '', '', '', '', '');

CREATE TABLE IF NOT EXISTS `si_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid` int(11) NOT NULL DEFAULT '1',
  `sqlquerie` text NOT NULL,
  `last_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`, `domain_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_payment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ac_inv_id` int(11) NOT NULL,
  `ac_amount` decimal(25,6) NOT NULL,
  `ac_notes` text NOT NULL,
  `ac_date` datetime NOT NULL,
  `ac_payment_type` int(10) NOT NULL DEFAULT '1',
  `domain_id` int(11) NOT NULL,
  `online_payment_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `domain_id` (`domain_id`),
  KEY `ac_inv_id` (`ac_inv_id`),
  KEY `ac_amount` (`ac_amount`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_payment_types` (
  `pt_id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `pt_description` varchar(250) NOT NULL,
  `pt_enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  PRIMARY KEY (`domain_id`,`pt_id`)
) ENGINE=MyISAM;

INSERT INTO `si_payment_types` (`pt_id`, `domain_id`, `pt_description`, `pt_enabled`) VALUES
 (1, 1, 'Cash', '1')
,(2, 1, 'Credit Card', '1');

CREATE TABLE IF NOT EXISTS `si_preferences` (
  `pref_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `pref_description` varchar(50) DEFAULT NULL,
  `pref_currency_sign` varchar(50) DEFAULT NULL,
  `pref_inv_heading` varchar(50) DEFAULT NULL,
  `pref_inv_wording` varchar(50) DEFAULT NULL,
  `pref_inv_detail_heading` varchar(50) DEFAULT NULL,
  `pref_inv_detail_line` text,
  `pref_inv_payment_method` varchar(50) DEFAULT NULL,
  `pref_inv_payment_line1_name` varchar(50) DEFAULT NULL,
  `pref_inv_payment_line1_value` varchar(50) DEFAULT NULL,
  `pref_inv_payment_line2_name` varchar(50) DEFAULT NULL,
  `pref_inv_payment_line2_value` varchar(50) DEFAULT NULL,
  `pref_enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  `status` TINYINT(1) NOT NULL,
  `locale` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `index_group` int(11) NOT NULL,
  `currency_code` varchar(25) DEFAULT NULL,
  `include_online_payment` varchar(255) DEFAULT NULL,
  `currency_position` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`domain_id`,`pref_id`)
) ENGINE=MyISAM;

INSERT INTO `si_preferences` (`pref_id`, `domain_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`, `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`, `status`, `locale`, `language`, `index_group`, `currency_code`, `include_online_payment`, `currency_position`) VALUES
 (1, 1, 'Invoice', '$', 'Invoice', 'Invoice', 'Details', 'Payment is to be made within 14 days of the invoice being sent', 'Electronic Funds Transfer', 'Account name', 'H. & M. Simpson', 'Account number:', '0123-4567-7890', '1', 1, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left')
,(2, 1, 'Receipt', '$', 'Receipt', 'Receipt', 'Details', '<br />This transaction has been paid in full, please keep this receipt as proof of purchase.<br /> Thank you', '', '', '', '', '', '1', 1, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left')
,(3, 1, 'Estimate', '$', 'Estimate', 'Estimate', 'Details', '<br />This is an estimate of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1', 0, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left')
,(4, 1, 'Quote', '$', 'Quote', 'Quote', 'Details', '<br />This is a quote of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1', 0, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left');

CREATE TABLE IF NOT EXISTS `si_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  `unit_price` decimal(25,6) DEFAULT '0.000000',
  `default_tax_id` int(11) DEFAULT NULL,
  `default_tax_id_2` int(11) DEFAULT NULL,
  `cost` decimal(25,6) DEFAULT '0.000000',
  `reorder_level` int(11) DEFAULT NULL,
  `custom_field1` varchar(255) DEFAULT NULL,
  `custom_field2` varchar(255) DEFAULT NULL,
  `custom_field3` varchar(255) DEFAULT NULL,
  `custom_field4` varchar(255) DEFAULT NULL,
  `notes` text NOT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  `visible` TINYINT(1) DEFAULT 1 NOT NULL,
  `attribute` varchar(255) DEFAULT NULL,
  `notes_as_description` CHAR(1) DEFAULT NULL,
  `show_description` CHAR(1) DEFAULT NULL,
  `custom_flags` char(10) COLLATE utf8_unicode_ci NOT NULL ,
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

INSERT INTO `si_products` (`id`, `domain_id`, `description`, `unit_price`, `default_tax_id`, `default_tax_id_2`, `cost`, `reorder_level`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `notes`, `enabled`, `visible`, `attribute`, `notes_as_description`, `show_description`, `custom_flags`) VALUES
 (1, 1, 'Hourly charge', 150.000000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000')
,(2, 1, 'Accounting services', 140.000000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000')
,(3, 1, 'Ploughing service', 125.000000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000')
,(4, 1, 'Bootleg homebrew', 15.500000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000')
,(5, 1, 'Accommodation', 125.500000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '', '0000000000');

CREATE TABLE IF NOT EXISTS `si_products_attribute_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_products_attribute_type` VALUES
 ('1','list')
,('2','decimal')
,('3','free');

CREATE TABLE IF NOT EXISTS `si_products_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type_id` varchar(255) NOT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  `visible` TINYINT(1) DEFAULT 1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_products_attributes` VALUES
 ('1','Size',  '1','1','1')
,('2','Colour','1','1','1');

CREATE TABLE IF NOT EXISTS `si_products_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_products_values` VALUES
 ('1','1','S','1')
,('2','1','M','1')
,('3','1','L','1')
,('4','2','Red','1')
,('5','2','White','1');

CREATE TABLE IF NOT EXISTS `si_sql_patchmanager` (
  `sql_id` int(11) NOT NULL AUTO_INCREMENT,
  `sql_patch_ref` int(11) NOT NULL,
  `sql_patch` varchar(255) NOT NULL,
  `sql_release` varchar(25) NOT NULL DEFAULT '',
  `sql_statement` text NOT NULL,
  `source` varchar(20) NOT NULL,
  PRIMARY KEY (`sql_id`)
) ENGINE=MyISAM;

INSERT INTO `si_sql_patchmanager`(`sql_patch_ref`,`sql_patch`,`sql_release`,`sql_statement`, `source`) VALUES
 (1,'Create sql_patchmanger table','20060514','CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 255 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) ENGINE = MYISAM ','original')
,(2,'','','','original')
,(3,'','','','original')
,(4,'','','','original')
,(5,'','','','original')
,(6,'','','','original')
,(7,'','','','original')
,(8,'','','','original')
,(9,'','','','original')
,(10,'','','','original')
,(11,'','','','original')
,(12,'','','','original')
,(13,'','','','original')
,(14,'','','','original')
,(15,'','','','original')
,(16,'','','','original')
,(17,'','','','original')
,(18,'','','','original')
,(19,'','','','original')
,(20,'','','','original')
,(21,'','','','original')
,(22,'','','','original')
,(23,'','','','original')
,(24,'','','','original')
,(25,'','','','original')
,(26,'','','','original')
,(27,'','','','original')
,(28,'','','','original')
,(29,'','','','original')
,(30,'','','','original')
,(31,'','','','original')
,(32,'','','','original')
,(33,'','','','original')
,(34,'','','','original')
,(35,'','','','original')
,(36,'','','','original')
,(37,'','','','original')
,(38,'','','','original')
,(39,'','','','original')
,(40,'','','','original')
,(41,'','','','original')
,(42,'','','','original')
,(43,'','','','original')
,(44,'','','','original')
,(45,'','','','original')
,(46,'','','','original')
,(47,'','','','original')
,(48,'','','','original')
,(49,'','','','original')
,(50,'','','','original')
,(51,'','','','original')
,(52,'','','','original')
,(53,'','','','original')
,(54,'','','','original')
,(55,'','','','original')
,(56,'','','','original')
,(57,'','','','original')
,(58,'','','','original')
,(59,'','','','original')
,(60,'','','','original')
,(61,'','','','original')
,(62,'','','','original')
,(63,'','','','original')
,(64,'','','','original')
,(65,'','','','original')
,(66,'','','','original')
,(67,'','','','original')
,(68,'','','','original')
,(69,'','','','original')
,(70,'','','','original')
,(71,'','','','original')
,(72,'','','','original')
,(73,'','','','original')
,(74,'','','','original')
,(75,'','','','original')
,(76,'','','','original')
,(77,'','','','original')
,(78,'','','','original')
,(79,'','','','original')
,(80,'','','','original')
,(81,'','','','original')
,(82,'','','','original')
,(83,'','','','original')
,(84,'','','','original')
,(85,'','','','original')
,(86,'','','','original')
,(87,'','','','original')
,(88,'','','','original')
,(89,'','','','original')
,(90,'','','','original')
,(91,'','','','original')
,(92,'','','','original')
,(93,'','','','original')
,(94,'','','','original')
,(95,'','','','original')
,(96,'','','','original')
,(97,'','','','original')
,(98,'','','','original')
,(99,'','','','original')
,(100,'','','','original')
,(101,'','','','original')
,(102,'','','','original')
,(103,'','','','original')
,(104,'','','','original')
,(105,'','','','original')
,(106,'','','','original')
,(107,'','','','original')
,(108,'','','','original')
,(109,'','','','original')
,(110,'','','','original')
,(111,'','','','original')
,(112,'','','','original')
,(113,'','','','original')
,(114,'','','','original')
,(115,'','','','original')
,(116,'','','','original')
,(117,'','','','original')
,(118,'','','','original')
,(119,'','','','original')
,(120,'','','','original')
,(121,'','','','original')
,(122,'','','','original')
,(123,'','','','original')
,(124,'','','','original')
,(125,'','','','original')
,(126,'','','','original')
,(127,'','','','original')
,(128,'','','','original')
,(129,'','','','original')
,(130,'','','','original')
,(131,'','','','original')
,(132,'','','','original')
,(133,'','','','original')
,(134,'','','','original')
,(135,'','','','original')
,(136,'','','','original')
,(137,'','','','original')
,(138,'','','','original')
,(139,'','','','original')
,(140,'','','','original')
,(141,'','','','original')
,(142,'','','','original')
,(143,'','','','original')
,(144,'','','','original')
,(145,'','','','original')
,(146,'','','','original')
,(147,'','','','original')
,(148,'','','','original')
,(149,'','','','original')
,(150,'','','','original')
,(151,'','','','original')
,(152,'','','','original')
,(153,'','','','original')
,(154,'','','','original')
,(155,'','','','original')
,(156,'','','','original')
,(157,'','','','original')
,(158,'','','','original')
,(159,'','','','original')
,(160,'','','','original')
,(161,'','','','original')
,(162,'','','','original')
,(163,'','','','original')
,(164,'','','','original')
,(165,'','','','original')
,(166,'','','','original')
,(167,'','','','original')
,(168,'','','','original')
,(169,'','','','original')
,(170,'','','','original')
,(171,'','','','original')
,(172,'','','','original')
,(173,'','','','original')
,(174,'','','','original')
,(175,'','','','original')
,(176,'','','','original')
,(177,'','','','original')
,(178,'','','','original')
,(179,'','','','original')
,(180,'','','','original')
,(181,'','','','original')
,(182,'','','','original')
,(183,'','','','original')
,(184,'','','','original')
,(185,'','','','original')
,(186,'','','','original')
,(187,'','','','original')
,(188,'','','','original')
,(189,'','','','original')
,(190,'','','','original')
,(191,'','','','original')
,(192,'','','','original')
,(193,'','','','original')
,(194,'','','','original')
,(195,'','','','original')
,(196,'','','','original')
,(197,'','','','original')
,(198,'','','','original')
,(199,'','','','original')
,(200,'Update extensions table','20090529','UPDATE si_extensions SET id = 0 WHERE name = core LIMIT 1','original')
,(201,'Set domain_id on system defaults table to 1','20090622','UPDATE si_system_defaults SET domain_id = 1','original')
,(202,'Set extension_id on system defaults table to 1','20090622','UPDATE si_system_defaults SET extension_id = 1','original')
,(203,'Move all old consulting style invoices to itemized','20090704','UPDATE si_invoices SET type_id = 2 where type_id = 3','original')
,(204,'','','','original')
,(205,'','','','original')
,(206,'','','','original')
,(207,'','','','original')
,(208,'','','','original')
,(209,'','','','original')
,(210,'','','','original')
,(211,'','','','original')
,(212,'','','','original')
,(213,'','','','original')
,(214,'','','','original')
,(215,'','','','original')
,(216,'','','','original')
,(217,'','','','original')
,(218,'','','','original')
,(219,'','','','original')
,(220,'','','','original')
,(221,'','','','original')
,(222,'','','','original')
,(223,'','','','original')
,(224,'','','','original')
,(225,'','','','original')
,(226,'','','','original')
,(227,'','','','original')
,(228,'','','','original')
,(229,'','','','original')
,(230,'','','','original')
,(231,'','','','original')
,(232,'','','','original')
,(233,'','','','original')
,(234,'','','','original')
,(235,'','','','original')
,(236,'','','','original')
,(237,'','','','original')
,(238,'','','','original')
,(239,'','','','original')
,(240,'','','','original')
,(241,'','','','original')
,(242,'','','','original')
,(243,'','','','original')
,(244,'','','','original')
,(245,'','','','original')
,(246,'','','','original')
,(247,'','','','original')
,(248,'','','','original')
,(249,'','','','original')
,(250,'','','','original')
,(251,'','','','original')
,(252,'','','','original')
,(253,'','','','original')
,(254,'','','','original')
,(255,'','','','original')
,(256,'','','','original')
,(257,'','','','original')
,(258,'','','','original')
,(259,'','','','original')
,(260,'','','','original')
,(261,'','','','original')
,(262,'','','','original')
,(263,'','','','original')
,(264,'','','','original')
,(265,'','','','original')
,(266,'','','','original')
,(267,'','','','original')
,(268,'','','','original')
,(269,'','','','original')
,(270,'','','','original')
,(271,'','','','original')
,(272,'','','','original')
,(273,'','','','original')
,(274,'','','','original')
,(275,'','','','original')
,(276,'','','','original')
,(277,'','','','original')
,(278,'','','','original')
,(279,'','','','original')
,(280,'','','','original')
,(281,'','','','original')
,(282,'','','','original')
,(283,'','','','original')
,(284,'','','','original')
,(285,'','','','original')
,(286,'','','','original')
,(287,'','','','original')
,(288,'','','','original')
,(289,'','','','original')
,(290,'','','','original')
,(291,'','','','original')
,(292,'','','','original')
,(293,'Add department to the customers','20161004','ALTER TABLE `si_customers` ADD COLUMN `department` VARCHAR(255) NULL AFTER `name`','original')
,(294,'Add custom_flags table for products.','20180922','','fearless359')
,(295,'Add net income report.','20180923','','fearless359')
,(296,'Add past due report.','20180924','','fearless359')
,(297,'Add User Security enhancement fields and values','20180924','','fearless359')
,(298,'Add Signature field to the biller table.','20181003','ALTER TABLE `si_biller` ADD `signature` varchar(255) DEFAULT "" NOT NULL COMMENT "Email signature" AFTER `email`','fearless359')
,(299,'Add check number field to the payment table.','20181003','ALTER TABLE `si_payment` ADD `ac_check_number` varchar(10) DEFAULT "" NOT NULL COMMENT "Check number for CHECK payment types"','fearless359');

CREATE TABLE IF NOT EXISTS `si_system_defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `value` varchar(30) NOT NULL,
  `domain_id` int(5) NOT NULL DEFAULT '0',
  `extension_id` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`domain_id`,`id`),
  UNIQUE KEY `UnqNameInDomain` (`domain_id`, `name`)
) ENGINE=MyISAM;

INSERT INTO `si_system_defaults` (`id`, `name`, `value`, `domain_id`, `extension_id`) VALUES
 ('1','biller','','1','1')
,('2','company_logo','simple_invoices_logo.png','1','1')
,('3','company_name','SimpleInvoices','1','1')
,('4','company_name_item','SimpleInvoices','1','1')
,('5','customer','','1','1')
,('6','dateformate','Y-m-d','1','1')
,('7','delete','N','1','1')
,('8','emailhost','localhost','1','1')
,('9','emailpassword','','1','1')
,('10','emailusername','','1','1')
,('11','inventory','0','1','1')
,('12','language','en_US','1','1')
,('13','large_dataset','0','1','1')
,('14','line_items','5','1','1')
,('15','logging','0','1','1')
,('16','password_lower','1','1','1')
,('17','password_min_length','8','1','1')
,('18','password_number','1','1','1')
,('19','password_special','1','1','1')
,('20','password_upper','1','1','1')
,('21','payment_type','1','1','1')
,('22','pdfbottommargin','15','1','1')
,('23','pdfleftmargin','15','1','1')
,('24','pdfpapersize','A4','1','1')
,('25','pdfrightmargin','15','1','1')
,('26','pdfscreensize','800','1','1')
,('27','pdftopmargin','15','1','1')
,('28','preference','1','1','1')
,('29','product_attributes','0','1','1')
,('30','session_timeout','60','1','1')
,('31','spreadsheet','xls','1','1')
,('32','tax','1','1','1')
,('33','tax_per_line_item','1','1','1')
,('34','template','default','1','1')
,('35','wordprocessor','doc','1','1');

CREATE TABLE IF NOT EXISTS `si_tax` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_description` varchar(50) DEFAULT NULL,
  `tax_percentage` decimal(25,6) DEFAULT '0.000000',
  `type` CHAR(1) DEFAULT '%' NOT NULL,
  `tax_enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY (`domain_id`,`tax_id`)
) ENGINE=MyISAM;

INSERT INTO `si_tax` (`tax_id`, `tax_description`, `tax_percentage`, `type`, `tax_enabled`, `domain_id`) VALUES
 (1, 'GST', 10.000000, '%', '1', 1)
,(2, 'VAT', 10.000000, '%', '1', 1)
,(3, 'Sales Tax', 10.000000, '%', '1', 1)
,(4, 'No Tax', 0.000000, '%', '1', 1)
,(5, 'Postage', 20.000000, '$', '1', 1);

CREATE TABLE IF NOT EXISTS `si_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '0',
  `password` varchar(64) DEFAULT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`domain_id`,`id`),
  UNIQUE KEY `UnqEMailPwd` (`email`, `password`)
) ENGINE=MyISAM;

INSERT INTO `si_user` (`id`, `username`, `email`, `role_id`, `domain_id`, `password`, `enabled`, `user_id`) VALUES
 (1, 'demo', 'demo@simpleinvoices.group', 1, 1, 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 0);

CREATE TABLE IF NOT EXISTS `si_user_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM;

INSERT INTO `si_user_domain` (`id`, `name`) VALUES
 (1, 'default');

CREATE TABLE IF NOT EXISTS `si_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM;

INSERT INTO `si_user_role` (`id`, `name`) VALUES
 (1, 'administrator')
,(2, 'domain_administrator')
,(3, 'user')
,(4, 'operator')
,(5, 'customer')
,(6, 'biller')
,(7, 'viewer');

