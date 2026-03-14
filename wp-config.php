<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'event' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'MySQL-5.7' );

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
define( 'AUTH_KEY',         '8h1@:6gPl_@n=pTP~ZCUb|&sx`//]9|L`+`=lVWC$me.cyP>xMcc_|1q/DU(!2dO' );
define( 'SECURE_AUTH_KEY',  'B)?o r13_>8WOV4T,gE)pHxtDUgfn=s)8^Ym`1$G6YQw%3vV`UJ9KZ)$DR,oDv9k' );
define( 'LOGGED_IN_KEY',    '1awQ0X)+!O/OwLOKK/h5nXD^Z<Xf7ihCmP Um5t(<R:xzR|0|mSvGC-_BruF 3y$' );
define( 'NONCE_KEY',        'cdd!iji^~mkgjxQe4x3)fNb0ua!JF&Mxubg+in:&:&3d^{ Z_>A6FS~+^BX+0WHZ' );
define( 'AUTH_SALT',        'J]%=;y[EB6mt@!?U,26yX< fJV4!D`)S6T/:;K>RS;ImYUnS~9RRA7k`+3K>@a`l' );
define( 'SECURE_AUTH_SALT', 'TkEb$ME7@ewwIIB?p,u19#p*PKVp`NVG~*zY%7EE>^ s9%k]YA*EETMJEYt+ZZ12' );
define( 'LOGGED_IN_SALT',   'DurfpcU%&F%}w^S!vj6qm[g_QuG*aX{8}@f=Az`s00!o?82j&&kR{Z}}+jiYv}ro' );
define( 'NONCE_SALT',       '}&}l:O0Gy1{=(V({9yW5lg)77Q2f]G:dow<9g+J}tJyI*<Irg98j5JyWHsWhK`0-' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
