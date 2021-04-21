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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'veloxdb' );

/** MySQL database username */
define( 'DB_USER', 'veloxdb' );

/** MySQL database password */
define( 'DB_PASSWORD', 'dPdlKDd%{^SKd' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'zEEtWrK$dZW,6.?g9;{Yhe`~g37*ghp?@ x/n{7zS  GR^&xFgR|v5q 3^chT~vZ' );
define( 'SECURE_AUTH_KEY',   'e<b1GjS:i.Gomy%T9sfEy9#2-lq:ay }(P+$!Lu0&LrigETZrj;O</J~Tx*afZsh' );
define( 'LOGGED_IN_KEY',     'lk}+^_tvf(,Z`J/L]7$lMMB:&gz&b;TB%9#O,Q*z<zj^6M$#CmH7m+9z2y`1<uQ9' );
define( 'NONCE_KEY',         '$7WE~oTR~1?o>kDo%j%*LSb9[4=F@&?bA_/KtU n#5Ch/-@Howo|[/9%s>>ZRo@8' );
define( 'AUTH_SALT',         'vb+AM6,de`-=X7bgJ+&)rcJmSw[I*BwS8QOg;[`ZE)}Z6:G8NK.t&}JoE.FzV.Hk' );
define( 'SECURE_AUTH_SALT',  'C?}#T!WAGrnK2RO=m]-t0DYR=&gzv0Y(fqVn47tSYc?WPK<cwN>nZVgO4;hn!P#>' );
define( 'LOGGED_IN_SALT',    'Jx!um^;#Mh?0-:HT75J9 H)ZIV`>w_]E$5Ps)KFwREL#6Hr$vOG&bt9T#TldA`>G' );
define( 'NONCE_SALT',        ':Hdn(&p$/[9Y]/9OR0|RR{h)ojNSv NC/ ]w?!|fe=E2^B2MUg?@!9UUFD1k<Zc8' );
define( 'WP_CACHE_KEY_SALT', '@zmY^4<oq>d=(/YxxrDC%~|GH=p(_1k=/LrQE}r3ME^(K]ROS9z3X!SdrUtH-V<5' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
