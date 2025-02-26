<?php


namespace AgileStoreLocator;

/**
 * Fired during plugin activation
 *
 * @link       https://agilelogix.com
 * @since      1.0.0
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/includes
 */

class Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		\AgileStoreLocator\Activator::add_basic_tables();

		\AgileStoreLocator\Activator::add_cron_job();
	}


	/**
	 * [add_basic_tables add the tables]
	 */
	public static function add_basic_tables() {

		//ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 0);

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
		global $wpdb;
		$charset_collate = 'UTF8MB4';
		$prefix 	 	 = $wpdb->prefix."asl_";
		$database    = $wpdb->dbname;


		/*Categories*/
		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}categories` (
			  `id` int unsigned NOT NULL AUTO_INCREMENT,
			  `category_name` varchar(255) DEFAULT NULL,
			  `ordr` int DEFAULT '0',
			  `lang` varchar(10) DEFAULT '',
			  `is_active` int NOT NULL,
			  `icon` varchar(100) NOT NULL,
			  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			)  DEFAULT CHARSET=UTF8MB4;";
		dbDelta( $sql );



		//	Add Ordr Column for the `categories`
		$sql 	= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}categories' AND COLUMN_NAME = 'ordr'";
		$result = $wpdb->get_results($sql);
		if($result[0]->c == 0) {

			$wpdb->query("ALTER TABLE {$prefix}categories ADD COLUMN `ordr` int(11) DEFAULT '0';");
		}


		//	Add Ordr column for the `brands`
		$sql 	= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}brands' AND COLUMN_NAME = 'ordr'";
		$result = $wpdb->get_results($sql);
		if($result[0]->c == 0) {

			$wpdb->query("ALTER TABLE {$prefix}brands ADD COLUMN `ordr` int(11) DEFAULT '0';");
		}

		//////////////////////
		/// Make DDL Tables //
		//////////////////////

		$ddl_tables = \AgileStoreLocator\Model\Attribute::get_controls();

		//	Create the DDL Filter tables
		foreach($ddl_tables as $ddl_table_name => $ddl_table) {

			$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}$ddl_table_name` (
				  `id` int unsigned NOT NULL AUTO_INCREMENT,
				  `name` varchar(255) DEFAULT NULL,
				  `ordr` int DEFAULT '0',
				  `lang` varchar(10) DEFAULT '',
				  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`)
				)  DEFAULT CHARSET=UTF8MB4;";
			dbDelta( $sql );
	
		}
	
	
		/*Config*/
		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}configs` (
			  `id` int unsigned NOT NULL AUTO_INCREMENT,
			  `key` varchar(50) DEFAULT NULL,
			  `value` varchar(100) DEFAULT NULL,
			  `type` varchar(50) DEFAULT NULL,
			  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			)  DEFAULT CHARSET=UTF8MB4;";
		dbDelta( $sql );
		

		/*Countries*/
		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}countries` (
				  `id` int NOT NULL AUTO_INCREMENT,
				  `country` varchar(50) NOT NULL,
				  `iso_code_2` varchar(2) NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `country_name` (`country`)
				) DEFAULT CHARSET=UTF8MB4;";
		dbDelta( $sql );


		/*Stores Markers*/
		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}markers` (
			  `id` int unsigned NOT NULL AUTO_INCREMENT,
			  `marker_name` varchar(255) DEFAULT NULL,
			  `is_active` tinyint NOT NULL,
			  `icon` varchar(100) NOT NULL,
			  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			)  DEFAULT CHARSET=UTF8MB4;";
		dbDelta( $sql );


		/*CREATE Store Logos*/
		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}storelogos` (
				  `id` int NOT NULL AUTO_INCREMENT,
				  `path` varchar(300) NOT NULL,
				  `name` varchar(50) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=UTF8MB4;";
		dbDelta( $sql );


		###########################################################################

		/* CREATE Stores*/
		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}stores` (
			  `id` int unsigned NOT NULL AUTO_INCREMENT,
				`title` varchar(255) DEFAULT NULL,
				`description` text,
				`street` text,
				`city` varchar(100) DEFAULT NULL,
				`state` varchar(100) DEFAULT NULL,
				`postal_code` varchar(50) DEFAULT NULL,
				`country` int DEFAULT NULL,
				`lat` varchar(50) DEFAULT NULL,
				`lng` varchar(50) DEFAULT NULL,
				`phone` varchar(50) DEFAULT NULL,
				`fax` varchar(50) DEFAULT NULL,
				`email` varchar(100) DEFAULT NULL,
				`website` varchar(255) DEFAULT NULL,
				`description_2` text,
				`logo_id` int DEFAULT NULL,
				`marker_id` int DEFAULT NULL,
				`is_disabled` tinyint DEFAULT NULL,
				`open_hours` text,
				`brand` varchar(100) DEFAULT NULL,
				`special` varchar(100) DEFAULT NULL,
				`custom` mediumtext,
				`ordr` int DEFAULT '0',
				`slug` varchar(255) DEFAULT NULL,
				`lang` varchar(10) DEFAULT '',
				`pending` tinyint DEFAULT NULL,
				`created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				`updated_on` datetime DEFAULT NULL,
				INDEX (`logo_id`),
				INDEX (`country`),
			  PRIMARY KEY (`id`)
			)  DEFAULT CHARSET=UTF8MB4;";
		dbDelta( $sql );


		//	Create the stores meta
		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}stores_meta` (
				  `option_id` int unsigned NOT NULL AUTO_INCREMENT,
				  `option_name` varchar(100) NOT NULL DEFAULT '',
				  `option_value` text DEFAULT NULL,
				  `store_id` int NOT NULL,
				  `is_exec` int(11) DEFAULT NULL,
				  PRIMARY KEY (`option_id`),
				  UNIQUE KEY `option_name` (`option_name`,`store_id`),
				  KEY `store_id` (`store_id`)
				) DEFAULT CHARSET=UTF8MB4;";
		dbDelta( $sql );

		// Alter Character SET
		$sql = "ALTER TABLE {$prefix}stores CHARACTER SET UTF8MB4;";
		$wpdb->query( $sql );


		//	Add is_exec column for the `stores_meta` if not exist!
		$sql 	= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}stores_meta' AND COLUMN_NAME = 'is_exec'";
		$result = $wpdb->get_results($sql);
		
		if($result[0]->c == 0) {

			$wpdb->query("ALTER TABLE {$prefix}stores_meta ADD COLUMN `is_exec` int(11) DEFAULT NULL;");
		}


		/*CREATE Stores Categories*/
		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}stores_categories` (
			  `id` int unsigned NOT NULL AUTO_INCREMENT,
			  `category_id` int NOT NULL,
			  `store_id` int NOT NULL,
			  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  INDEX (`store_id`),
			  PRIMARY KEY (`id`)
			)  DEFAULT CHARSET=UTF8MB4;";
		dbDelta( $sql );


		/*CREATE Settings*/
		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}settings` (
				  `id` int unsigned NOT NULL AUTO_INCREMENT,
				  `name` varchar(50) DEFAULT NULL,
				  `content` mediumtext,
				  `type` varchar(25) DEFAULT NULL,
				  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`)
				)  DEFAULT CHARSET=UTF8MB4;";
		dbDelta( $sql );


		/*CREATE Store Views*/
		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}stores_view` (
				  `id` int unsigned NOT NULL AUTO_INCREMENT,
				  `store_id` int DEFAULT NULL,
				  `search_str` varchar(100) DEFAULT NULL,
				  `place_id` varchar(100) DEFAULT NULL,
				  `is_search` tinyint DEFAULT NULL,
				  `ip_address` varchar(25) DEFAULT NULL,
				  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`)
				)  DEFAULT CHARSET=UTF8MB4;";
		dbDelta( $sql );


		/* CREATE Leads*/
		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}leads` (
			  `id` int unsigned NOT NULL AUTO_INCREMENT,
				`store_id` int DEFAULT NULL,
				`name` varchar(255) DEFAULT NULL,
				`postal_code` varchar(50) DEFAULT NULL,
				`phone` varchar(50) DEFAULT NULL,
				`email` varchar(100) DEFAULT NULL,
				`message` text DEFAULT NULL,
				`rating` varchar(10) DEFAULT NULL,
				`follow_up` tinyint(4) DEFAULT '0',
				`created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				`updated_on` datetime DEFAULT NULL,
			  PRIMARY KEY (`id`)
			)  DEFAULT CHARSET=UTF8MB4;";
		dbDelta( $sql );


		///////////////////////////////////
		///////// ADD INDEXES /////////////
		///////////////////////////////////
		$stores_index = $wpdb->query("SHOW INDEX FROM `{$prefix}stores`");
		if($stores_index < 2) {

			$wpdb->query("ALTER TABLE `{$prefix}stores` ADD INDEX(`logo_id`);");			
			$wpdb->query("ALTER TABLE `{$prefix}stores_categories` ADD INDEX(`store_id`);");
		}
		if($stores_index < 3) {

			$wpdb->query("ALTER TABLE `{$prefix}stores` ADD INDEX(`country`);");
		}




		//categories;
		$c = $wpdb->get_results("SELECT count(*) AS 'count' FROM {$prefix}categories");
		if($c[0]->count <= 0) {
			
			$sql =  "INSERT INTO `{$prefix}categories`(`id`,`category_name`,`is_active`,`icon`,`created_on`) VALUES (1,'Arts & Entertainment',1,'movie-theater.svg','2016-05-07 20:31:04'),(2,'For The Home',1,'home.svg','2016-05-07 20:31:09'),(4,'Amusement Park',1,'amusement-park.svg','2016-05-07 20:31:16'),(5,'Medical / Dental / Vision Care',1,'hospital.svg','2016-05-07 20:31:20'),(6,'Jewelry',1,'jewelry-store.svg','2016-05-07 20:31:23'),(7,'Fitness',1,'gym.svg','2016-05-07 20:31:26'),(8,'Electronics',1,'electronics-store.svg','2016-05-07 20:31:32'),(9,'Pets',1,'pet-store.svg','2016-05-07 20:31:33'),(10,'Auto',1,'car-repair.svg','2016-05-07 20:31:36'),(11,'Local Services',1,'local-government.svg','2016-05-07 20:31:39'),(13,'Beauty and Spas',1,'spa.svg','2016-05-07 20:31:50'),(14,'Nightlife',1,'night-club.svg','2016-05-07 20:31:55'),(16,'Restaurants',1,'restaurant.svg','2016-05-07 20:32:03'),(17,'Travel',1,'airport.svg','2016-05-07 20:32:55');";
			dbDelta( $sql );
		}

		//	Countries
		$c = $wpdb->get_results("SELECT count(*) AS 'count' FROM {$prefix}countries WHERE country = 'Russia'");

		//	Check if new countries are not there, save the list again
		if($c[0]->count <= 0) {

			$wpdb->query("TRUNCATE TABLE `{$prefix}countries`");

			$sql =  "INSERT INTO `{$prefix}countries`(`id`,`country`,`iso_code_2`) VALUES (1,'Afghanistan','AF'),(2,'Albania','AL'),(3,'Algeria','DZ'),(4,'American Samoa','AS'),(5,'Andorra','AD'),(6,'Angola','AO'),(7,'Anguilla','AI'),(8,'Antarctica','AQ'),(9,'Antigua and Barbuda','AG'),(10,'Argentina','AR'),(11,'Armenia','AM'),(12,'Aruba','AW'),(13,'Australia','AU'),(14,'Austria','AT'),(15,'Azerbaijan','AZ'),(16,'Bahamas','BS'),(17,'Bahrain','BH'),(18,'Bangladesh','BD'),(19,'Barbados','BB'),(20,'Belarus','BY'),(21,'Belgium','BE'),(22,'Belize','BZ'),(23,'Benin','BJ'),(24,'Bermuda','BM'),(25,'Bhutan','BT'),(26,'Bolivia','BO'),(27,'Bosnia and Herzegovina','BA'),(28,'Botswana','BW'),(29,'Bouvet Island','BV'),(30,'Brazil','BR'),(31,'British Indian Ocean Territory','IO'),(32,'Brunei Darussalam','BN'),(33,'Bulgaria','BG'),(34,'Burkina Faso','BF'),(35,'Burundi','BI'),(36,'Cambodia','KH'),(37,'Cameroon','CM'),(38,'Canada','CA'),(39,'Cape Verde','CV'),(40,'Cayman Islands','KY'),(41,'Central African Republic','CF'),(42,'Chad','TD'),(43,'Chile','CL'),(44,'China','CN'),(45,'Christmas Island','CX'),(46,'Cocos Islands','CC'),(47,'Colombia','CO'),(48,'Comoros','KM'),(49,'Congo','CG'),(50,'Cook Islands','CK'),(51,'Costa Rica','CR'),(52,'Cote D\'Ivoire','CI'),(53,'Croatia','HR'),(54,'Cuba','CU'),(55,'Cyprus','CY'),(56,'Czech Republic','CZ'),(57,'Denmark','DK'),(58,'Djibouti','DJ'),(59,'Dominica','DM'),(60,'Dominican Republic','DO'),(61,'East Timor','TP'),(62,'Ecuador','EC'),(63,'Egypt','EG'),(64,'El Salvador','SV'),(65,'Equatorial Guinea','GQ'),(66,'Eritrea','ER'),(67,'Estonia','EE'),(68,'Ethiopia','ET'),(69,'Falkland Islands','FK'),(70,'Faroe Islands','FO'),(71,'Fiji','FJ'),(72,'Finland','FI'),(73,'France','FR'),(74,'France, Metropolitan','FX'),(75,'French Guiana','GF'),(76,'French Polynesia','PF'),(77,'French Southern Territories','TF'),(78,'Gabon','GA'),(79,'Gambia','GM'),(80,'Georgia','GE'),(81,'Germany','DE'),(82,'Ghana','GH'),(83,'Gibraltar','GI'),(84,'Greece','GR'),(85,'Greenland','GL'),(86,'Grenada','GD'),(87,'Guadeloupe','GP'),(88,'Guam','GU'),(89,'Guatemala','GT'),(90,'Guinea','GN'),(91,'Guinea-bissau','GW'),(92,'Guyana','GY'),(93,'Haiti','HT'),(94,'Heard and Mc Donald Islands','HM'),(95,'Honduras','HN'),(96,'Hong Kong','HK'),(97,'Hungary','HU'),(98,'Iceland','IS'),(99,'India','IN'),(100,'Indonesia','ID'),(101,'Iran','IR'),(102,'Iraq','IQ'),(103,'Ireland','IE'),(104,'Israel','IL'),(105,'Italy','IT'),(106,'Jamaica','JM'),(107,'Japan','JP'),(108,'Jordan','JO'),(109,'Kazakhstan','KZ'),(110,'Kenya','KE'),(111,'Kiribati','KI'),(112,'South Korea','SK'),(113,'North Korea','NK'),(114,'Kuwait','KW'),(115,'Kyrgyzstan','KG'),(116,'Laos','LA'),(117,'Latvia','LV'),(118,'Lebanon','LB'),(119,'Lesotho','LS'),(120,'Liberia','LR'),(121,'Libya','LY'),(122,'Liechtenstein','LI'),(123,'Lithuania','LT'),(124,'Luxembourg','LU'),(125,'Macau','MO'),(126,'Macedonia','MK'),(127,'Madagascar','MG'),(128,'Malawi','MW'),(129,'Malaysia','MY'),(130,'Maldives','MV'),(131,'Mali','ML'),(132,'Malta','MT'),(133,'Marshall Islands','MH'),(134,'Martinique','MQ'),(135,'Mauritania','MR'),(136,'Mauritius','MU'),(137,'Mayotte','YT'),(138,'Mexico','MX'),(139,'Micronesia','FM'),(140,'Moldova','MD'),(141,'Monaco','MC'),(142,'Mongolia','MN'),(143,'Montserrat','MS'),(144,'Morocco','MA'),(145,'Mozambique','MZ'),(146,'Myanmar','MM'),(147,'Namibia','NA'),(148,'Nauru','NR'),(149,'Nepal','NP'),(150,'Netherlands','NL'),(151,'Netherlands Antilles','AN'),(152,'New Caledonia','NC'),(153,'New Zealand','NZ'),(154,'Nicaragua','NI'),(155,'Niger','NE'),(156,'Nigeria','NG'),(157,'Niue','NU'),(158,'Norfolk Island','NF'),(159,'Northern Mariana Islands','MP'),(160,'Norway','NO'),(161,'Oman','OM'),(162,'Pakistan','PK'),(163,'Palau','PW'),(164,'Panama','PA'),(165,'Papua New Guinea','PG'),(166,'Paraguay','PY'),(167,'Peru','PE'),(168,'Philippines','PH'),(169,'Pitcairn','PN'),(170,'Poland','PL'),(171,'Portugal','PT'),(172,'Puerto Rico','PR'),(173,'Qatar','QA'),(174,'Reunion','RE'),(175,'Romania','RO'),(176,'Russia','RU'),(177,'Rwanda','RW'),(178,'Saint Kitts and Nevis','KN'),(179,'Saint Lucia','LC'),(180,'Saint Vincent and the Grenadines','VC'),(181,'Samoa','WS'),(182,'San Marino','SM'),(183,'Sao Tome and Principe','ST'),(184,'Saudi Arabia','SA'),(185,'Senegal','SN'),(186,'Seychelles','SC'),(187,'Sierra Leone','SL'),(188,'Singapore','SG'),(189,'Slovakia','SK'),(190,'Slovenia','SI'),(191,'Solomon Islands','SB'),(192,'Somalia','SO'),(193,'South Africa','ZA'),(194,'South Georgia and the South Sandwich Islands','GS'),(195,'Spain','ES'),(196,'Sri Lanka','LK'),(197,'St. Helena','SH'),(198,'St. Pierre and Miquelon','PM'),(199,'Sudan','SD'),(200,'Suriname','SR'),(201,'Svalbard and Jan Mayen Islands','SJ'),(202,'Eswatini','SZ'),(203,'Sweden','SE'),(204,'Switzerland','CH'),(205,'Syrian Arab Republic','SY'),(206,'Taiwan','TW'),(207,'Tajikistan','TJ'),(208,'Tanzania','TZ'),(209,'Thailand','TH'),(210,'Togo','TG'),(211,'Tokelau','TK'),(212,'Tonga','TO'),(213,'Trinidad and Tobago','TT'),(214,'Tunisia','TN'),(215,'Turkey','TR'),(216,'Turkmenistan','TM'),(217,'Turks and Caicos Islands','TC'),(218,'Tuvalu','TV'),(219,'Uganda','UG'),(220,'Ukraine','UA'),(221,'United Arab Emirates','AE'),(222,'United Kingdom','GB'),(223,'United States','US'),(224,'United States Minor Outlying Islands','UM'),(225,'Uruguay','UY'),(226,'Uzbekistan','UZ'),(227,'Vanuatu','VU'),(228,'Vatican City','VA'),(229,'Venezuela','VE'),(230,'Viet Nam','VN'),(231,'Virgin Islands (British)','VG'),(232,'Virgin Islands (U.S.)','VI'),(233,'Wallis and Futuna Islands','WF'),(234,'Western Sahara','EH'),(235,'Yemen','YE'),(236,'Serbia','SR'),(237,'Zaire','ZR'),(238,'Zambia','ZM'),(239,'Zimbabwe','ZW'),(240,'Montenegro','ME'),(241,'Kosovo','XK'),(242,'Curaçao','CW'),(243,'Palestine','PS'),(244,'Isle of Man','IM'),(245,'Jersey','JE'),(246,'South Sudan','SS'),(247,'Timor-Leste','TL');";

			dbDelta( $sql );
		}
		
		

		//markers
		$c = $wpdb->get_results("SELECT count(*) AS 'count' FROM {$prefix}markers");
		
		if($c[0]->count <= 0) {
			
			$sql =  "INSERT INTO `{$prefix}markers`(`id`,`marker_name`,`is_active`,`icon`,`created_on`) values (1,'Default',1,'default.png','2016-11-18 21:58:44'),(2,'Active',1, 'active.png','2019-08-01 21:58:44'),(3,'Airport',1,'airport.png','2016-11-18 21:58:44'),(4,'Amusement Park',1,'amusement park.png','2016-11-18 21:58:44'),(5,'Aquarium',1,'aquarium.png','2016-11-18 21:58:44'),(6,'Archery',1,'archery.png','2016-11-18 21:58:44'),(7,'Assistive Listening System',1,'assistive-listening-system.png','2016-11-18 21:58:44'),(8,'Atm',1,'atm.png','2016-11-18 21:58:44'),(9,'Audio Description',1,'audio-description.png','2016-11-18 21:58:44'),(10,'Bakery',1,'bakery.png','2016-11-18 21:58:44'),(11,'Banks',1,'banks.png','2016-11-18 21:58:44'),(12,'Bar',1,'bar.png','2016-11-18 21:58:44'),(13,'Baseball',1,'baseball.png','2016-11-18 21:58:44'),(14,'Beauty Salon',1,'beauty-salon.png','2016-11-18 21:58:45'),(15,'Boat Ramp',1,'boat ramp.png','2016-11-18 21:58:45'),(16,'Boat Tour',1,'boat tour.png','2016-11-18 21:58:45'),(17,'Boat',1,'boat.png','2016-11-18 21:58:45'),(18,'Book Store',1,'book store.png','2016-11-18 21:58:45'),(19,'Bowling Alley',1,'bowling alley.png','2016-11-18 21:58:45'),(20,'Braille',1,'braille.png','2016-11-18 21:58:45'),(21,'Bus Station',1,'bus station.png','2016-11-18 21:58:45'),(22,'Bycycle Store',1,'bycycle store.png','2016-11-18 21:58:45'),(23,'Cafe',1,'cafe.png','2016-11-18 21:58:45'),(24,'Campground',1,'campground.png','2016-11-18 21:58:45'),(25,'Canoe',1,'canoe.png','2016-11-18 21:58:45'),(26,'Car Dealer',1,'car dealer.png','2016-11-18 21:58:45'),(27,'Car Wash',1,'car wash.png','2016-11-18 21:58:45'),(28,'Casino',1,'casino.png','2016-11-18 21:58:45'),(29,'Cemetry',1,'cemetry.png','2016-11-18 21:58:45'),(30,'Chairlift',1,'chairlift.png','2016-11-18 21:58:45'),(31,'Circle',1,'circle.png','2016-11-18 21:58:45'),(32,'City Hall',1,'city hall.png','2016-11-18 21:58:45'),(33,'Climbing',1,'climbing.png','2016-11-18 21:58:45'),(34,'Closed Captioning',1,'closed-captioning.png','2016-11-18 21:58:45'),(35,'Clothing Store',1,'clothing store.png','2016-11-18 21:58:45'),(36,'Compass',1,'compass.png','2016-11-18 21:58:45'),(37,'Convenience Store',1,'convenience-store.png','2016-11-18 21:58:45'),(38,'Courthouse',1,'courthouse.png','2016-11-18 21:58:45'),(39,'Cross Country Skiing',1,'cross-country-skiing.png','2016-11-18 21:58:45'),(40,'Crosshairs',1,'crosshairs.png','2016-11-18 21:58:45'),(41,'Crunch',1,'crunch.png','2016-11-18 21:58:45'),(42,'Abseiling',1,'abseiling.png','2016-11-18 21:58:45'),(43,'Default 1',1,'default_1.png','2016-11-18 21:58:45'),(44,'Default 10',1,'default_10.png','2016-11-18 21:58:45'),(45,'Default 11',1,'default_11.png','2016-11-18 21:58:46'),(46,'Default 12',1,'default_12.png','2016-11-18 21:58:46'),(47,'Default 13',1,'default_13.png','2016-11-18 21:58:46'),(48,'Default 14',1,'default_14.png','2016-11-18 21:58:46'),(49,'Default 15',1,'default_15.png','2016-11-18 21:58:46'),(50,'Default 16',1,'default_16.png','2016-11-18 21:58:46'),(51,'Default 17',1,'default_17.png','2016-11-18 21:58:46'),(52,'Default 18',1,'default_18.png','2016-11-18 21:58:46'),(53,'Default 19',1,'default_19.png','2016-11-18 21:58:46'),(54,'Default 20',1,'default_20.png','2016-11-18 21:58:46'),(55,'Default 21',1,'default_21.png','2016-11-18 21:58:46'),(56,'Default 4',1,'default_4.png','2016-11-18 21:58:46'),(57,'Default 6',1,'default_6.png','2016-11-18 21:58:46'),(58,'Default 7',1,'default_7.png','2016-11-18 21:58:46'),(59,'Default 8',1,'default_8.png','2016-11-18 21:58:46'),(60,'Default 9',1,'default_9.png','2016-11-18 21:58:46'),(61,'Dentist',1,'dentist.png','2016-11-18 21:58:46'),(62,'Department Store',1,'department store.png','2016-11-18 21:58:46'),(63,'Diving',1,'diving.png','2016-11-18 21:58:46'),(64,'Doctor',1,'doctor.png','2016-11-18 21:58:46'),(65,'Electrician',1,'electrician.png','2016-11-18 21:58:46'),(66,'Embassy',1,'embassy.png','2016-11-18 21:58:46'),(67,'Expand',1,'expand.png','2016-11-18 21:58:46'),(68,'Female',1,'female.png','2016-11-18 21:58:46'),(69,'Fianance',1,'fianance.png','2016-11-18 21:58:46'),(70,'Fire Station',1,'fire station.png','2016-11-18 21:58:46'),(71,'Fish Cleaning',1,'fish cleaning.png','2016-11-18 21:58:46'),(72,'Fishing Pier',1,'fishing pier.png','2016-11-18 21:58:46'),(73,'Florist',1,'florist.png','2016-11-18 21:58:46'),(74,'Food',1,'food.png','2016-11-18 21:58:46'),(75,'Full Screen',1,'full screen.png','2016-11-18 21:58:46'),(76,'Funeral Home',1,'funeral-home.png','2016-11-18 21:58:46'),(77,'Furniture Store',1,'furniture-store.png','2016-11-18 21:58:46'),(78,'Gas Station',1,'gas station.png','2016-11-18 21:58:46'),(79,'General Contractor',1,'general contractor.png','2016-11-18 21:58:46'),(80,'Grocery Or Supermarket',1,'grocery or supermarket.png','2016-11-18 21:58:47'),(81,'Hair Care',1,'hair care.png','2016-11-18 21:58:47'),(82,'Hang Gliding',1,'hang gliding.png','2016-11-18 21:58:47'),(83,'Hardware Store',1,'hardware store.png','2016-11-18 21:58:47'),(84,'Health',1,'health.png','2016-11-18 21:58:47'),(85,'Hindu Temple',1,'hindu temple.png','2016-11-18 21:58:47'),(86,'Hospital',1,'hospital.png','2016-11-18 21:58:47'),(87,'Ice Fishing',1,'ice fishing.png','2016-11-18 21:58:47'),(88,'Ice Skating',1,'ice skating.png','2016-11-18 21:58:47'),(89,'Inline Skating',1,'inline skating.png','2016-11-18 21:58:47'),(90,'Insurance Agency',1,'insurance agency.png','2016-11-18 21:58:47'),(91,'Jet Skating',1,'jet skating.png','2016-11-18 21:58:47'),(92,'Kayaking',1,'kayaking.png','2016-11-18 21:58:47'),(93,'Lawyer',1,'lawyer.png','2016-11-18 21:58:47'),(94,'Library',1,'library.png','2016-11-18 21:58:47'),(95,'Liquor Store',1,'liquor store.png','2016-11-18 21:58:47'),(96,'Localsmith',1,'localsmith.png','2016-11-18 21:58:47'),(97,'Location Arrow',1,'location arrow.png','2016-11-18 21:58:47'),(98,'Lodging',1,'lodging.png','2016-11-18 21:58:47'),(99,'Low Vision Access',1,'low-vision-access.png','2016-11-18 21:58:47'),(100,'Male',1,'male.png','2016-11-18 21:58:47'),(101,'Map Pin',1,'map pin.png','2016-11-18 21:58:47'),(102,'Marina',1,'marina.png','2016-11-18 21:58:47'),(103,'Market',1,'market.png','2016-11-18 21:58:47'),(104,'Mosque',1,'mosque.png','2016-11-18 21:58:47'),(105,'Movie Rental',1,'movie rental.png','2016-11-18 21:58:47'),(106,'Movie Theater',1,'movie theater.png','2016-11-18 21:58:47'),(107,'Moving Company',1,'moving company.png','2016-11-18 21:58:47'),(108,'Museum',1,'museum.png','2016-11-18 21:58:47'),(109,'Natural Feature',1,'natural feature.png','2016-11-18 21:58:47'),(110,'Night Club',1,'night club.png','2016-11-18 21:58:48'),(111,'Open Captioning',1,'open captioning.png','2016-11-18 21:58:48'),(112,'Painter',1,'painter.png','2016-11-18 21:58:48'),(113,'Park',1,'park.png','2016-11-18 21:58:48'),(114,'Parking',1,'parking.png','2016-11-18 21:58:48'),(115,'Pet Store',1,'pet store.png','2016-11-18 21:58:48'),(116,'Pharmacy',1,'pharmacy.png','2016-11-18 21:58:48'),(117,'Physiotherapist',1,'physiotherapist.png','2016-11-18 21:58:48'),(118,'Place Of Worship',1,'place of worship.png','2016-11-18 21:58:48'),(119,'Playground',1,'playground.png','2016-11-18 21:58:48'),(120,'Plumber',1,'plumber.png','2016-11-18 21:58:48'),(121,'Point Of Interest',1,'point of interest.png','2016-11-18 21:58:48'),(122,'Police',1,'police.png','2016-11-18 21:58:48'),(123,'Politicial',1,'politicial.png','2016-11-18 21:58:48'),(124,'Post Box',1,'post box.png','2016-11-18 21:58:48'),(125,'Post Office',1,'post office.png','2016-11-18 21:58:48'),(126,'Postal Code',1,'postal code.png','2016-11-18 21:58:48'),(127,'Rafting',1,'rafting.png','2016-11-18 21:58:48'),(128,'Real Estate',1,'real-estate.png','2016-11-18 21:58:48'),(129,'Roofing Contractor',1,'roofing-contractor.png','2016-11-18 21:58:48'),(130,'Route Pin',1,'route-pin.png','2016-11-18 21:58:48'),(131,'Route',1,'route.png','2016-11-18 21:58:48'),(132,'Rv Park',1,'rv-park.png','2016-11-18 21:58:48'),(133,'Sailing',1,'sailing.png','2016-11-18 21:58:48'),(134,'School',1,'school.png','2016-11-18 21:58:48'),(135,'Search',1,'search.png','2016-11-18 21:58:48'),(136,'Shield',1,'shield.png','2016-11-18 21:58:48'),(137,'Shopping',1,'shopping.png','2016-11-18 21:58:48'),(138,'Sledding',1,'sledding.png','2016-11-18 21:58:48'),(139,'Sliding',1,'sliding.png','2016-11-18 21:58:48'),(140,'Snow Mobile',1,'snow-mobile.png','2016-11-18 21:58:48'),(141,'Snow Shoeing',1,'snow-shoeing.png','2016-11-18 21:58:48'),(142,'Snow',1,'snow.png','2016-11-18 21:58:49'),(143,'Square Pin',1,'square-pin.png','2016-11-18 21:58:49'),(144,'Square Rounded',1,'square-rounded.png','2016-11-18 21:58:49'),(145,'Square',1,'square.png','2016-11-18 21:58:49'),(146,'Stadium',1,'stadium.png','2016-11-18 21:58:49'),(147,'Storage',1,'storage.png','2016-11-18 21:58:49'),(148,'Surfing',1,'surfing.png','2016-11-18 21:58:49'),(149,'Swimming',1,'swimming.png','2016-11-18 21:58:49'),(150,'Transit Station',1,'transit-station.png','2016-11-18 21:58:49')";
			dbDelta( $sql );
		}


		$c = $wpdb->get_results("SELECT count(*) AS 'count' FROM {$prefix}storelogos");

		if($c[0]->count <= 0) {

			//logos
			$sql =  "INSERT INTO `{$prefix}storelogos`(`path`,`name`) VALUES ('default.png','Default')";
			dbDelta( $sql );
		}
		

		//stores
		$c = $wpdb->get_results("SELECT count(*) AS 'count' FROM {$prefix}stores");
		
		if($c[0]->count <= 0) {
			
			$sql =  "INSERT INTO `{$prefix}stores`(`id`,`title`,`description`,`street`,`city`,`state`,`postal_code`,`country`,`lat`,`lng`,`phone`,`fax`,`email`,`website`,`description_2`,`logo_id`,`marker_id`,`is_disabled`,`open_hours`,`brand`,`special`,`custom`,`ordr`,`slug`,`pending`,`created_on`,`updated_on`) values (1,'Amanda Food Court','not available','45 North Street','Uitenhage','Eastern Cape','5043',193,'-33.749771','25.405823','041 111 3964','','support@agilelogix.com','https://agilestorelocator.com','',0,1,0,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'amanda-food-court-uitenhage',NULL,'2017-10-24 20:12:29','2022-02-01 10:24:00'),(2,'Aqua Food Store','not available','26 Northriding, Lorraine Manor','Port Elizabeth','Eastern Cape','1234',193,'-33.97506','25.513332','213 882 8888',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'aqua-food-store-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(3,'Astro Club','not available','15 Heartly Road, Parsons Hill','Port Elizabeth','Eastern Cape','45553',193,'-33.947128','25.591169','123 226 2222',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'astro-club-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(4,'Barry Mason Pool Services','not available','34 Saddlewood, Louis Michael Drive, Lovemore Heights','Port Elizabeth','Eastern Cape','33422',193,'-33.99213','25.531601','123 888 5555',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'barry-mason-pool-services-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(5,'Bid-Bon Development','not available','274 Kragga Kamma Road, Lorraine','Port Elizabeth','Eastern Cape','23452',193,'-33.96524','25.50242','041 888 3534',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'bid-bon-development-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(6,'Deon\'S Pool Maintenance Servics','not available','1 Demurville, Lorraine','Port Elizabeth','Eastern Cape','23455',193,'-33.968891','25.52002','072 888 1607',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'deon-s-pool-maintenance-servics-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(7,'East Coast Pools','not available','17 Reith Street, Sidwell','Port Elizabeth','Eastern Cape','5043',193,'-33.92194','25.595169','041 888 4916',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'east-coast-pools-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(8,'Mica Food Court','not available','48 Church Street','Graaf Reinet','Eastern Cape','1234',193,'-32.253212','24.536243','049 888 2640',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'mica-food-court-graaf-reinet',NULL,'2017-10-24 20:12:29',NULL),(9,'Haggie\'s Swimpool Services & Supplies ','not available','Noorsekloof','Jeffreys Bay','Eastern Cape','6331',193,'-34.027143','24.916028','041 888 6343',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'haggie-s-swimpool-services-supplies-jeffreys-bay',NULL,'2017-10-24 20:12:29',NULL),(10,'Heritage Rock Hotel','not available','Idle  Wydle, Sardinia Bay Road, Lovemore Park','Port Elizabeth','Eastern Cape','33422',193,'-34.014511','25.523298','082 888 9282',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'heritage-rock-hotel-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(11,'Norman\'s Hotel','not available','15 Vincent Road','East London','Eastern Cape','23452',193,'-32.978962','27.901928','043 888 8445',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'norman-s-hotel-east-london',NULL,'2017-10-24 20:12:29',NULL),(12,'Pelican Spa','not available','143 Heugh Road, Walmer','Port Elizabeth','Eastern Cape','23455',193,'-33.980839','25.59514','041 888 6453',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'pelican-spa-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(13,'Pool and Spa Centre','not available','6 Boshof Street, Westering','Port Elizabeth','Eastern Cape','5043',193,'-33.944561','25.522751','041 888 8005',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'pool-and-spa-centre-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(14,'Pool Maintenance Services','not available','100 Dijon Road, Lorraine','Port Elizabeth','Eastern Cape','1234',193,'-33.976929','25.529341','041 888 4927',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'pool-maintenance-services-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(15,'Sandstone Pools','not available','5 Yale Road, Blue Water Bay','Port Elizabeth','Eastern Cape','45553',193,'-33.857689','25.62956','072 888 0505',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'sandstone-pools-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(16,'Royal Autos','not available','61 Heugh Road, Walmer','Port Elizabeth','Eastern Cape','33422',193,'-33.976898','25.605591','041 888 8117',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'royal-autos-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(17,'Grahamstown Restaurant','not available','4 Hill Street','Grahamstown','Eastern Cape','23452',193,'-33.308353','26.525585','046 888 4320',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'grahamstown-restaurant-grahamstown',NULL,'2017-10-24 20:12:29',NULL),(18,'Port Alfred Restaurant','not available','88 Albany Road','Port Alfred','Eastern Cape','23455',193,'-33.586407','26.907701','046 888 8618',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'port-alfred-restaurant-port-alfred',NULL,'2017-10-24 20:12:29',NULL),(19,'JIM Beauty Saloon','not available','Jacaranda Street','Jeffreys Bay','Eastern Cape','5043',193,'-34.033333','24.916668','042 888 0813',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'jim-beauty-saloon-jeffreys-bay',NULL,'2017-10-24 20:12:29',NULL),(20,'Sliming Center','not available','28 6th Avenue, Walmer','Port Elizabeth','Eastern Cape','1234',193,'-33.978668','25.594669','041 888 6568',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'sliming-center-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(21,'Artcraft Centre','not available','43 3rd Avenue, Newton Park','Port Elizabeth','Eastern Cape','45553',193,'-33.947609','25.568867','041 888 1257',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'artcraft-centre-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(22,'Tams Hyperstore','not available','5 High Street','Cradock','Eastern Cape','33422',193,'-32.175652','25.621719','048 888 3022',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'tams-hyperstore-cradock',NULL,'2017-10-24 20:12:29',NULL),(23,'The Pool Lab','not available','17 Young Road, Mill Park','Port Elizabeth','Eastern Cape','23452',193,'-33.965435','25.59059','083 888 1181',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'the-pool-lab-port-elizabeth',NULL,'2017-10-24 20:12:29',NULL),(24,'Brandons Club','not available','Leaping Frog Shopping Centre, Cnr William Nicol & Mulbarton Road, Fourways','Johannesburg','Gauteng','23455',193,'-26.005112','28.020357','011 888 1224',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'brandons-club-johannesburg',NULL,'2017-10-24 20:12:29',NULL),(25,'Amanzi Club','not available','46 Longfellow Street, Ridgeway','Johannesburg','Gauteng','5043',193,'-26.253469','27.996401','011 888 4569',NULL,'support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'amanzi-club-johannesburg',NULL,'2017-10-24 20:12:29',NULL),(26,'White Shop','50% Clothes available','Central Park','Denver','CO','60204',223,'37.3990371','-105.6721263','333-3333-333','222-2222-22','support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"0\",\"tue\":\"0\",\"wed\":\"0\",\"thu\":\"0\",\"fri\":\"0\",\"sat\":\"0\",\"sun\":\"0\"}',NULL,NULL,NULL,0,'white-shop-denver',NULL,'2017-10-24 20:12:29',NULL),(27,'Tires Shop Center','ABC Tires','315 West Main Street','Walla Walla','WA','45553',223,'46.0645809','-118.3430209','333-333-33','11-11-11','support@agilelogix.com','https://agilestorelocator.com',NULL,1,1,NULL,'{\"mon\":\"1\",\"tue\":\"1\",\"wed\":\"1\",\"thu\":\"1\",\"fri\":\"1\",\"sat\":\"1\",\"sun\":\"1\"}',NULL,NULL,NULL,0,'tires-shop-center-walla-walla',NULL,'2017-10-24 20:12:29',NULL);";
			dbDelta( $sql );

			//store category relation
			$sql =  "INSERT INTO `{$prefix}stores_categories`(`id`,`category_id`,`store_id`,`created_on`) VALUES (1,16,1,'2016-06-08 11:59:49'),(2,16,2,'2016-06-08 12:00:00'),(3,7,3,'2016-06-08 12:00:39'),(4,14,3,'2016-06-08 12:00:52'),(5,11,4,'2016-06-08 12:01:29'),(6,11,5,'2016-06-08 12:01:30'),(7,11,6,'2016-06-08 12:01:31'),(8,11,7,'2016-06-08 12:01:31'),(9,16,8,'2016-06-08 12:10:09'),(10,11,9,'2016-06-08 12:10:10'),(11,17,1,'2016-06-08 12:10:12'),(12,17,11,'2016-06-08 12:10:12'),(13,17,12,'2016-06-08 12:10:13'),(14,7,13,'2016-06-08 12:10:14'),(15,11,14,'2016-06-08 12:10:15'),(16,7,15,'2016-06-08 12:10:16'),(17,1,16,'2016-06-08 12:10:17'),(18,16,17,'2016-06-08 12:10:18'),(19,16,18,'2016-06-08 12:10:19'),(20,13,19,'2016-06-08 12:10:20'),(21,7,20,'2016-06-08 12:10:21'),(22,1,21,'2016-06-08 12:10:22'),(23,4,22,'2016-06-08 12:10:23'),(24,11,23,'2016-06-08 12:10:24'),(25,14,24,'2016-06-08 12:10:25'),(26,14,25,'2016-06-08 12:10:26'),(261,2,215,'2016-06-20 15:18:19');";
			dbDelta( $sql );
		}


		//	Settings
		$c = $wpdb->get_results("SELECT count(*) AS 'count' FROM {$prefix}settings");
		
		if($c[0]->count <= 1) {

			//	Delete One row
			if($c[0]->count == 1) {

				$sql =  "INSERT INTO `{$prefix}settings`(`id`,`name`,`content`,`type`,`created_on`) values (2,'map_style','','map','2018-03-03 10:00:00');";
				dbDelta( $sql );
			}
			else {

					$sql =  "INSERT INTO `{$prefix}settings`(`id`,`name`,`content`,`type`,`created_on`) values (1,'map_customize','','map','2016-08-04 17:40:03'),(2,'map_style','','map','2018-03-03 10:00:00');";
					dbDelta( $sql );
			}
		}
		


		//	Add Ordering -> ordr and Feature Bit
		$sql 	= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}stores' AND COLUMN_NAME = 'ordr'";
		$result = $wpdb->get_results($sql);
		
		if($result[0]->c == 0) {

			$wpdb->query("ALTER TABLE {$prefix}stores ADD COLUMN `ordr` int(11) DEFAULT '0', ADD COLUMN `featured` tinyint(4) DEFAULT NULL;");
		}

		//$wpdb->query("TRUNCATE TABLE `{$prefix}configs`");

		
		//////////////////////////////
		///// Add Additional Fields //
		//////////////////////////////

		foreach($ddl_tables as $ddl_table_name => $ddl_table) {

			$field_name = $ddl_table['field'];

			//	DDL Fields
			$sql 	= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}stores' AND COLUMN_NAME = '$field_name' AND TABLE_SCHEMA = '{$database}'";
			$result = $wpdb->get_results($sql);
			
			if($result[0]->c == 0) {
				$wpdb->query("ALTER TABLE {$prefix}stores ADD COLUMN `$field_name` text;");
			}
		}

		// Pending
		$sql 	= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}stores' AND COLUMN_NAME = 'pending' AND TABLE_SCHEMA = '{$database}'";
		$result = $wpdb->get_results($sql);
		
		if($result[0]->c == 0) {
			$wpdb->query("ALTER TABLE {$prefix}stores ADD COLUMN `pending` tinyint(4) DEFAULT NULL;");
		}

		// Slug
		$sql 	= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}stores' AND COLUMN_NAME = 'slug' AND TABLE_SCHEMA = '{$database}'";
		$result = $wpdb->get_results($sql);
		
		if($result[0]->c == 0) {
			$wpdb->query("ALTER TABLE {$prefix}stores ADD COLUMN `slug` varchar(255) DEFAULT NULL;");
		}

		//	Custom
		$sql 	= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}stores' AND COLUMN_NAME = 'custom' AND TABLE_SCHEMA = '{$database}'";
		$result = $wpdb->get_results($sql);
		
		if($result[0]->c == 0) {
			$wpdb->query("ALTER TABLE {$prefix}stores ADD COLUMN `custom` mediumtext;");
		}


		//	is_active
		$sql 	= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}categories' AND COLUMN_NAME = 'is_active' AND TABLE_SCHEMA = '{$database}'";
		$result = $wpdb->get_results($sql);
		
		if($result[0]->c == 0) {
			$wpdb->query("ALTER TABLE {$prefix}categories ADD COLUMN `is_active` tinyint(4) NOT NULL;");
		}

		//	Lead
		$sql 	= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}leads' AND COLUMN_NAME = 'follow_up' AND TABLE_SCHEMA = '{$database}'";
		$result = $wpdb->get_results($sql);
		
		if($result[0]->c == 0) {
			$wpdb->query("ALTER TABLE {$prefix}leads ADD COLUMN `follow_up` tinyint(4) DEFAULT '1';");
			$wpdb->query("ALTER TABLE {$prefix}leads ADD COLUMN `store_id` int(11) DEFAULT NULL;");
		}

		//	Add Configs
		self::add_configs();

		//	Validate Auto-increament
		self::validate_autoinc();

		//	Store Timing
		\AgileStoreLocator\Helper::fix_backward_compatible();

		//	Add the langs
		self::add_langs();

		//	Try to migrate the assets
		try {
    	
    	if(!get_option('asl-migrate')) {
    		
    		$is_valid  = \AgileStoreLocator\Helper::migrate_assets();
    	}
			
		} 
		catch (\Exception $e) {}

		//	Migration is marked as done
		update_option('asl-migrate', '1');
	}


	/**
	 * [validate_autoinc make sure the auto increament to the tables are working]
	 * @return [type] [description]
	 */
	public static function validate_autoinc() {

		global $wpdb;

		$prefix 	 	 = $wpdb->prefix."asl_";
		$database    = $wpdb->dbname;

		//	Make sure auto-increament is already done
		$sql 		= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}stores' AND COLUMN_NAME = 'id' AND TABLE_SCHEMA = '{$database}' AND DATA_TYPE = 'int' AND COLUMN_DEFAULT IS NULL AND IS_NULLABLE = 'NO' AND EXTRA like '%auto_increment%'";
		$result = $wpdb->get_results($sql);
		
		if($result[0]->c == 0) {

			$wpdb->query("ALTER TABLE {$prefix}stores MODIFY id int NOT NULL AUTO_INCREMENT PRIMARY KEY;");
			$wpdb->query("ALTER TABLE {$prefix}categories MODIFY id int NOT NULL AUTO_INCREMENT PRIMARY KEY;");
			$wpdb->query("ALTER TABLE {$prefix}stores_categories MODIFY id int NOT NULL AUTO_INCREMENT PRIMARY KEY;");
			$wpdb->query("ALTER TABLE {$prefix}storelogos MODIFY id int NOT NULL AUTO_INCREMENT PRIMARY KEY;");
			$wpdb->query("ALTER TABLE {$prefix}markers MODIFY id int NOT NULL AUTO_INCREMENT PRIMARY KEY;");
			//$wpdb->query("ALTER TABLE {$prefix}brands MODIFY id int NOT NULL AUTO_INCREMENT PRIMARY KEY;");
			//$wpdb->query("ALTER TABLE {$prefix}specials MODIFY id int NOT NULL AUTO_INCREMENT PRIMARY KEY;");
		}
	}


	/**
	 * [add_langs Add langs to the DB tables]
	 */
	public static function add_langs() {

		global $wpdb;

		$prefix 	 	 = $wpdb->prefix."asl_";
		$database    = $wpdb->dbname;

		//	lang column if missing
		$sql 	= "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$prefix}stores' AND COLUMN_NAME = 'lang' AND TABLE_SCHEMA = '{$database}'";
		$result = $wpdb->get_results($sql);
		
		if($result[0]->c == 0) {
			
			$wpdb->query("ALTER TABLE {$prefix}stores ADD COLUMN `lang` varchar(10) DEFAULT '';");
			$wpdb->query("ALTER TABLE {$prefix}categories ADD COLUMN `lang` varchar(10) DEFAULT '';");
			$wpdb->query("ALTER TABLE {$prefix}brands ADD COLUMN `lang` varchar(10) DEFAULT '';");
			$wpdb->query("ALTER TABLE {$prefix}specials ADD COLUMN `lang` varchar(10) DEFAULT '';");
		}
	}


	/**
	 * [add_cron_job Add the WP ASL Cron Jobs]
	 */
	public static function add_cron_job() {

		$args = array();

		if (! wp_next_scheduled ( 'asl_import_cron', $args )) {
        wp_schedule_event( time(), 'hourly', 'asl_import_cron', $args );
    }

    /*
    if (! wp_next_scheduled ( 'asl_lead_cron', $args )) {
      wp_schedule_event( time(), 'hourly', 'asl_lead_cron', $args );
    }
    */
	}


	/**
	 * [validate_configs Validate the config and add them if something is missing, Pro Version]
	 * @return [type] [description]
	 */
	public static function validate_configs() {

		global $wpdb;

		$prefix 	 	 = $wpdb->prefix."asl_";

		//$database    = $wpdb->dbname;
		$c = $wpdb->get_results("SELECT count(*) AS 'c' FROM `{$prefix}configs` WHERE `key` = 'server_key' || `key` = 'tran_lbl'");

		if($c && isset($c[0]) && $c[0]->c != 2) {

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        //  Run the script to add missing tables
        self::add_configs();
		}
	} 


	/**
	 * [add_configs Add the configuration]
	 */
	private static function add_configs() {

		global $wpdb;
		$charset_collate = 'UTF8MB4';
		$prefix 	 	 = $wpdb->prefix."asl_";

		

		$asl_configs = array(
			array('cluster','1',''),
			array('prompt_location','2',''),
			array('map_type','roadmap',''),
			array('distance_unit','Miles',''),
			array('zoom','9',''),
			array('show_categories','1',''),
			array('distance_slider','1',''),
			array('layout','0',''),
			array('default_lat','-33.947128',''),
			array('default_lng','25.591169',''),
			array('map_layout','0','Setting'),
			array('infobox_layout','0','Setting'),
			array('advance_filter','1',''),
			array('color_scheme','0',''),
			array('time_switch','0',''),
			array('category_marker','0',''),
			array('load_all','1',''),
			array('head_title','Number Of Shops',''),
			array('font_color_scheme','1',''),
			array('template','0',''),
			array('color_scheme_1','0',''),
			array('api_key','',''),
			array('display_list','1',''),
			array('full_width','0',''),
			array('time_format','0',''),
			array('category_title','Category',''),
			array('no_item_text','No Item Found',''),
			array('zoom_li','13',''),
			array('single_cat_select','0',''),
			array('country_restrict','',''),
			array('google_search_type','',''),
			array('color_scheme_2','0',''),
			array('analytics','0',''),
			array('sort_by_bound','0',''),
			array('scroll_wheel','0',''),
			array('search_type','0',''),
			//NEW
			array('search_destin','0',''),
			array('full_height','',''),
			array('map_language','',''),
			array('map_region','',''),
			array('sort_by','',''),

			array('distance_control','1',''),
			array('dropdown_range','20,40,60,80,*100',''),
			array('target_blank','0',''),
			array('geo_button','0',''),
			array('week_hours','0',''),

			array('user_center','0',''),
			array('smooth_pan','1',''),
			array('search_zoom','12',''),
			array('stores_limit','',''),
			//array('filter_result','0',''),
			array('radius_circle','1',''),
			array('server_key','','priv'),
			array('first_load','1',''),
			array('map_top','0',''),
			array('direction_redirect','0',''),
			array('color_scheme_3','0',''),
			array('category_bound','1',''),
			array('sort_random','0',''),
			array('and_filter','0',''),
			array('admin_notify','0',''),
			array('geo_marker','1',''),
			array('gdpr','0',''),
			array('notify_email','',''),
			array('store_schema','1',''),
			array('slug_link','0',''),
			array('hide_logo','0',''),
			array('hide_hours','0',''),
			array('rewrite_slug','',''),
			array('link_type','0',''),
			array('rewrite_id','',''),
			array('print_btn','0',''),
			array('direction_btn','1',''),
			array('zoom_btn','1',''),
			array('additional_info','0',''),
			array('address_ddl','0',''),
			array('locale','0',''),
			array('tabs_layout','0',''),
			array('cf7_hook','0','priv'),
			array('cf7_field','','priv'),
			array('lead_follow_up','0','priv'),
			array('filter_ddl','',''),
			array('branches','0',''),
			array('store_schedule','0',''),
			array('tran_lbl','0','')//translation labels
			// array('filter_ddl_store','','')
		);

		foreach($asl_configs as $_config) {	

			$key  = $_config[0];	
			$val  = $_config[1];	
			$type = $_config[2];

			//validate if not exist, else enter it
			$c = $wpdb->get_results("SELECT count(*) AS 'c' FROM `{$prefix}configs` WHERE `key` = '{$key}'");
			if($c[0]->c == 0) {
				
				$sql =  "INSERT INTO `{$prefix}configs`(`key`,`value`,`type`) VALUES ('{$key}','{$val}','{$type}');";
				dbDelta( $sql );
			}
		}
	}

}
