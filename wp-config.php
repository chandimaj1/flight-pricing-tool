<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
 
define( 'WP_MEMORY_LIMIT', '256M' );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'veilmhrv_wp78');

/** MySQL database username */
define('DB_USER', 'veilmhrv_wp78');

/** MySQL database password */
define('DB_PASSWORD', 'xSp)v!469p');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'y6oos9x1yipaobxatow7uhivt3moqvy3556uk1kferrgy1ytmacazdw8f1p8kyhn');
define('SECURE_AUTH_KEY',  '7rorp3qa9ilytqf9sgj8xitr3bfpeaifu3vlm6lymk3by51nfvikw36wyr9h8ijs');
define('LOGGED_IN_KEY',    'nbmulkyv7hwrsuglg6ha0xbfil84wvqv5ylka0xefmjk7p9wztvmlkpmvnpxvbw1');
define('NONCE_KEY',        'txsfnvnrgwyfidnpiwotz8fs2retwd9ljlgssrdofzg0w11je8ofgjimg6tyf0mg');
define('AUTH_SALT',        's1i9vgcjsqmie7xmf1dkuwzsfkusyi5le5spcopq0hmo88wvdfmvqhcmjhuibwmt');
define('SECURE_AUTH_SALT', 'x7qtmjsjavzzcttgnz49fxrbq0akupdsvfni2n2kt59nahcgskka7jeprmqixa8z');
define('LOGGED_IN_SALT',   'gtfqfhwvplrogy8zjupy3uwos5mwznwifzdz3vr93x3ftw0xdszhec4yu2wi6swt');
define('NONCE_SALT',       'ls8cimfsd1zygir0rbo7mixg6ahwdsydg3jdh3u8lfzlmkuw3irbekbxfua78x8s');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpgi_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
