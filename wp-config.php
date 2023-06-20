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
define( 'DB_NAME', 'gestiondepagos' );

/** Database username */
define( 'DB_USER', 'gestiondepagos' );

/** Database password */
define( 'DB_PASSWORD', 'gestiondepagos' );

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
define( 'AUTH_KEY',         'Yi|Vni[&xf4{]N,!m.dD^r51UU_o%6I ,H#niYqijNs{T! GX8z2[Qwb?;FMF5NK' );
define( 'SECURE_AUTH_KEY',  '(j6~o@u]e$Rw*63&-~c>.hYOIEv$,T?b^rH;+ge.]_s.]TWeZ21l^UtBW*0w0jxN' );
define( 'LOGGED_IN_KEY',    'R`_A.o8^j;&eC^|4lGy|^uV?MdJzf6qCbvRix]wD>){PH:<Q:#(Ac&}!H)mnR^X(' );
define( 'NONCE_KEY',        's%3Qu.:*.8RB/Pt1&~+f j;HARV?fbs~oG+GcXUtI+d-ubiPQp<t8p[(Y#Mkx/Fa' );
define( 'AUTH_SALT',        '](&].SzHn!8VtZcILV#l}zy`oR$t,a>va;RBtNPUb=gpKUPLPD,p^!-X-7C|cJ|E' );
define( 'SECURE_AUTH_SALT', 'JKPHEOIERG+BIMNu>95)E@oojkS>Rrp~cdD@.HrX`rIBF`:+D4bA yl+dutJE7Vq' );
define( 'LOGGED_IN_SALT',   ']OUyZhZBsR&1h9l#$Tpxi$s_s?cVYc;jW&=<WmR9e!4}DxugD`[~XjtJG77O|gVn' );
define( 'NONCE_SALT',       'dJ^fKBvH{aTo8SX}^XiiTMec6qq*o`8F.24/PRZ//>V/c:v|O&2-ZAM.hI[qaRl2' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
