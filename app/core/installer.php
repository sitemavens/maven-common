<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Installer {

	public function __construct () {
		;
	}

	public function install () {
		global $wpdb;

		$create = array(
			"CREATE TABLE `mvn_attributes` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(250) NOT NULL,
				`plugin_key` varchar(50) DEFAULT NULL,
				`description` varchar(500) DEFAULT NULL,
				`default_amount` float DEFAULT NULL,
				`default_wholesale_amount` float DEFAULT NULL,
				PRIMARY KEY (`id`)
			  )",
			"CREATE TABLE IF NOT EXISTS `mvn_attributes_values` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `attribute_id` int(11) NOT NULL,
				  `amount` float NOT NULL,
				  `wholesale_amount` float DEFAULT NULL,
				  `thing_id` int(11) NOT NULL,
				  `plugin_key` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				)",
			"
			CREATE TABLE IF NOT EXISTS `mvn_address` (
				`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`type` varchar(100) NOT NULL DEFAULT '',
				`name` varchar(50) DEFAULT '',
				`description` varchar(250) DEFAULT '',
				`first_line` varchar(100) DEFAULT '',
				`second_line` varchar(100) DEFAULT '',
				`neighborhood` varchar(50) DEFAULT '',
				`city` varchar(50) DEFAULT '',
				`state` varchar(50) DEFAULT '',
				`country` varchar(4) DEFAULT '',
				`zipcode` varchar(10) DEFAULT '',
				`notes` text,
				`phone` varchar(100) DEFAULT NULL,
				`phone_alternative` varchar(100) DEFAULT NULL,
				`profile_id` int(11) NOT NULL,
				`primary` tinyint(4) DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `type` (`type`,`profile_id`)
			  )",
			"CREATE TABLE IF NOT EXISTS `mvn_orders` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`number` int(11) NOT NULL,
				`status_id` varchar(50) DEFAULT NULL,
				`description` varchar(500) DEFAULT NULL,
				`order_date` varchar(45) DEFAULT NULL,
				`subtotal` float DEFAULT NULL,
				`total` float NOT NULL,
				`shipping_method` text,
				`shipping_amount` float DEFAULT NULL,
				`discount_amount` float DEFAULT NULL,
				`plugin_key` varchar(100) NOT NULL,
				`last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				`extra_fields` longtext,
				`promotions` text,
				`billing_contact_id` bigint(20) DEFAULT NULL,
				`billing_contact` text,
				`shipping_contact_id` bigint(20) DEFAULT NULL,
				`shipping_contact` text,
				`contact_id` int(11) NOT NULL,
				`contact` text,
				`credit_card` text,
				`transaction_id` varchar(100) DEFAULT NULL,
				`user_id` int(11) DEFAULT NULL,
				`user` text,
				`shipping_carrier` varchar(100) NOT NULL,
				`shipping_tracking_code` varchar(100) NOT NULL,
				`shipping_tracking_url` varchar(500) NOT NULL,
				PRIMARY KEY (`id`) 
			  )",
			"CREATE TABLE IF NOT EXISTS `mvn_orders_items` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`order_id` varchar(45) NOT NULL,
				`name` varchar(500) DEFAULT NULL,
				`quantity` tinyint(4) DEFAULT NULL,
				`price` float DEFAULT NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				`sum` varchar(500) NOT NULL,
				`plugin_key` varchar(100) NOT NULL,
				`thing_id` int(11) NOT NULL,
				`thing_variation_id` int(11) NOT NULL,
				`sku` varchar(100) NOT NULL,
				`attributes` text,
				PRIMARY KEY (`id`)
			  )",
			"CREATE TABLE IF NOT EXISTS `mvn_orders_status` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`order_id` int(11) NOT NULL,
				`status_id` varchar(1000) DEFAULT NULL,
				`status_description` varchar(500) DEFAULT NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				`description` varchar(500) DEFAULT NULL,
				PRIMARY KEY (`id`)
			  )",
			"CREATE TABLE IF NOT EXISTS `mvn_profile` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`description` text NOT NULL,
				`user_id` int(11) DEFAULT NULL,
				`notes` varchar(500) DEFAULT NULL,
				`admin_notes` varchar(500) DEFAULT NULL,
				`salutation` varchar(5) DEFAULT NULL,
				`first_name` varchar(250) DEFAULT NULL,
				`last_name` varchar(250) DEFAULT NULL,
				`email` varchar(200) DEFAULT NULL,
				`phone` varchar(100) DEFAULT NULL,
				`profile_image` varchar(500) DEFAULT NULL,
				`last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				`website` varchar(500) DEFAULT NULL,
				`company` varchar(500) DEFAULT NULL,
				`twitter` varchar(500) DEFAULT NULL,
				`facebook` varchar(500) DEFAULT NULL,
				`google_plus` varchar(500) DEFAULT NULL,
				`linked_in` varchar(500) DEFAULT NULL,
				`sex` varchar(5) NOT NULL,
				`auto_login_key` varchar(60) DEFAULT NULL,
				`wholesale` tinyint(4) DEFAULT NULL,
				`created_on` timestamp NOT NULL DEFAULT 0,
				PRIMARY KEY (`id`),
				UNIQUE KEY `email` (`email`)
			  )",
			"CREATE TABLE IF NOT EXISTS `mvn_promotions` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`section` varchar(64) NOT NULL,
				`name` varchar(256) NOT NULL,
				`description` varchar(500) DEFAULT NULL,
				`plugin_key` varchar(100) NOT NULL,
				`code` varchar(100) NOT NULL,
				`type` varchar(64) NOT NULL,
				`value` float NOT NULL,
				`limit_of_use` int(11) DEFAULT NULL,
				`uses` int(11) DEFAULT '0',
				`from` date DEFAULT NULL,
				`to` date DEFAULT NULL,
				`enabled` tinyint(4) NOT NULL DEFAULT '0',
				`exclusive` tinyint(4) NOT NULL DEFAULT '0',
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			  ) ",
			"CREATE TABLE IF NOT EXISTS `mvn_shipping_methods` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(500) NOT NULL,
				`method` text NOT NULL,
				`enabled` tinyint(1) NOT NULL,
				`description` varchar(500) DEFAULT NULL,
				`method_type` varchar(250) NOT NULL,
				PRIMARY KEY (`id`)
			  )",
			"CREATE TABLE IF NOT EXISTS `mvn_taxes` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(128) NOT NULL,
				`plugin_key` varchar(100) NOT NULL,
				`slug` varchar(128) DEFAULT NULL,
				`country` varchar(512) DEFAULT NULL,
				`state` varchar(512) DEFAULT NULL,
				`value` float NOT NULL,
				`for_shipping` tinyint(4) DEFAULT NULL,
				`compound` tinyint(4) DEFAULT NULL,
				`enabled` tinyint(4) NOT NULL DEFAULT '0',
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			  )",
			"CREATE TABLE IF NOT EXISTS `mvn_variation` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(100) NOT NULL,
				`plugin_key` varchar(50) NOT NULL,
				`thing_id` int(11) NOT NULL,
				PRIMARY KEY (`id`)
			  )",
			"CREATE TABLE IF NOT EXISTS `mvn_variation_group` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(1000) NOT NULL,
				`image` varchar(45) DEFAULT NULL,
				`group_key` varchar(50) DEFAULT NULL,
				`variation_id` int(11) NOT NULL,
				`plugin_key` varchar(50) NOT NULL,
				`thing_id` int(11) NOT NULL,
				`identifier` varchar(100) DEFAULT NULL,
				`quantity` int(11) DEFAULT NULL,
				`price` float DEFAULT NULL,
				`wholesale_price` float DEFAULT NULL,
				`price_operator` varchar(45) DEFAULT NULL,
				`sale_price` float DEFAULT NULL,
				PRIMARY KEY (`id`)
			  ) ",
			"CREATE TABLE IF NOT EXISTS `mvn_variation_group_option` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`variacion_opcion_id` int(11) NOT NULL,
				`variation_group_id` int(11) NOT NULL,
				PRIMARY KEY (`id`)
			  )",
			"CREATE TABLE IF NOT EXISTS `mvn_variation_option` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(500) DEFAULT NULL,
				`variation_id` int(11) NOT NULL,
				`default` tinyint(4) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			  )",
			"CREATE TABLE IF NOT EXISTS `mvns_categories_attributes` (
				`id` int(11) NOT NULL,
				 `attribute_id` int(11) NOT NULL,
				 `category_id` int(11) NOT NULL
				)",
			"CREATE TABLE `mvns_products_prices_roles` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`role` varchar(255) NOT NULL,
				`price` float NOT NULL,
				`product_id` int(11) NOT NULL,
				PRIMARY KEY (`id`)
			  ) ",
			
			"CREATE TABLE IF NOT EXISTS `mvn_profile_product_lists` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`profile_id` int(11) unsigned NOT NULL,
				`comment` text,
				`created` datetime DEFAULT NULL,
				`type` varchar(255) DEFAULT NULL,
				`title` varchar(255) DEFAULT NULL,
				PRIMARY KEY (`id`)
			  )",
			
			"CREATE TABLE IF NOT EXISTS `mvn_profile_product_lists_items` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`created` datetime DEFAULT NULL,
				`thing_id` int(11) unsigned NOT NULL,
				`variation_id` int(11) NOT NULL,
				`plugin_key` varchar(255) NOT NULL,
				`profile_product_list_id` int(11) unsigned NOT NULL,
				PRIMARY KEY (`id`)
			  )"
		);

		foreach ( $create AS $sql ) {
			$wpdb->query( $sql );
		}
	}

	public function uninstall () {
		global $wpdb;

		$settings = \Maven\Settings\MavenRegistry::instance();
		$settings->reset();

		//To danger to remove the tables in the uninstall process
		$drop = array(
//		    "DROP TABLE IF  EXISTS mvn_orders;",
//		    "DROP TABLE IF  EXISTS mvn_orders_items;",
//		    "DROP TABLE IF  EXISTS mvn_orders_status;",
//		    "DROP TABLE IF  EXISTS mvn_profile;",
//		    "DROP TABLE IF  EXISTS mvn_promotions;",
//		    "DROP TABLE IF  EXISTS mvn_taxes;",
//			"DROP TABLE IF  EXISTS mvn_address;"
		);


		foreach ( $drop AS $sql ) {
			if ( $wpdb->query( $sql ) === false )
				return false;
		}
	}

}
