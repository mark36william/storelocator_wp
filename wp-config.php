<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'WP_ALLOW_MULTISITE', true );
define( 'DB_NAME', 'wp_storelocator' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'vertrigo' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'znsgnippmw4hwkr74dwtdspvsnmlr6thfxqded58mevzzzsfbaimnnweztxbqqxg' );
define( 'SECURE_AUTH_KEY',  'w82pxns5nl3dfmbh8sc2lpwoqmnjc7p9emhneoavjq73dvta8yy7gelqg307mj54' );
define( 'LOGGED_IN_KEY',    '6li8zq2mk8jym3pvxp7oov7e9dz6nztpkwudkhmebyij8zofr8qufrsrqpl5cxq7' );
define( 'NONCE_KEY',        'o8inlpmc6ha6fptvwshvhwmfj2u218zouklmxpew5rmzjzy3tudnyacbx7nybgud' );
define( 'AUTH_SALT',        '6uspertzs1wvw1jae9hbr0q80kaadav8ncttr1y3tshinmv0vpj7e6v7gkazdp2x' );
define( 'SECURE_AUTH_SALT', 'xhxs0rdq5av61ov14ads0hbsiorlfhoj2uwn55gr5aubkbogez1vuz5fmonawur0' );
define( 'LOGGED_IN_SALT',   'd9dhomakccsglwce8lidwwuboskvruwsba1kjxudqgpu4a0h1lc47l8vgtwnft4e' );
define( 'NONCE_SALT',       '0fo1np4fek3fx3jiapqqbvsaas7jyue8kcbdjdc29lwnmw5pdjiq7pipzkxuuaq4' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wphg_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */
// define( 'MULTISITE', true );
// define( 'SUBDOMAIN_INSTALL', true );
// define( 'DOMAIN_CURRENT_SITE', 'localhost/wp_storelocator' );
// define( 'PATH_CURRENT_SITE', '/' );
// define( 'SITE_ID_CURRENT_SITE', 1 );
// define( 'BLOG_ID_CURRENT_SITE', 1 );

// Shopify Integration Settings
define('SHOPIFY_API_KEY', 'your_shopify_api_key');
define('SHOPIFY_API_SECRET', 'your_shopify_api_secret');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
