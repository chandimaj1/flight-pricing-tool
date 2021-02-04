<?php
define( 'WP_CACHE', true );

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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'veilmhrv_wp250' );

/** MySQL database username */
define( 'DB_USER', 'veilmhrv_wp250' );

/** MySQL database password */
define( 'DB_PASSWORD', '(R1!p8y264]1S-' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'xlxeucfyqwjmv0s4md3oyixwv5ld2eohv1mudssqocerytswxg4b267ufhoxngdt' );
define( 'SECURE_AUTH_KEY',  'dwa3fhrefxnjsebh2dsliaasgshhrlcaucfyhg5hugcpw4wnuz7spc9x0zy7ox5v' );
define( 'LOGGED_IN_KEY',    'cm3hzzklvg8soxdbrzvunktduugkyqsxyifu0amh9vxsq56ehjkfwhgl8pghcjb4' );
define( 'NONCE_KEY',        'h1glh0w3m72acilh37l7ownr4r6arevytn2ybzh1dtu6poquhejrbqdw4oustzx8' );
define( 'AUTH_SALT',        'jgzbkgkd73zxmnxgkkjnfrgdi7ioyhlzqfcgijbrvduubffh8wsxuzkavuoyeddz' );
define( 'SECURE_AUTH_SALT', 'gxochdtk74uplqfxg5dtksldz9acxktvskzgjvq76mqvehmgitclcoo5nstfd0yf' );
define( 'LOGGED_IN_SALT',   'mr9fvbhecy1kytfyidoxpioknlf12ml1onio03xi7m0p90bissjgl2bsu6tdjvlj' );
define( 'NONCE_SALT',       'lusm4n1olcpcpelwsmythee2xjui8pwhsl3ov9trsfgxn1eusa77ezky4hdeoxux' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp0k_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
