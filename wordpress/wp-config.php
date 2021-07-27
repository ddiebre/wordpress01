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
define( 'DB_NAME', 'sql10427705' );

/** MySQL database username */
define( 'DB_USER', 'sql10427705' );

/** MySQL database password */
define( 'DB_PASSWORD', 'ts2WnWdVhr' );

/** MySQL hostname */
define( 'DB_HOST', 'sql10.freesqldatabase.com' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         'x7K~qrX(tsJ*ylIfIRI$2p8Y%hf>qQOitn]LbVFQ/f]g[Z_yns6,QQ9Z~|g kZw4' );
define( 'SECURE_AUTH_KEY',  '7Ow>CblA!o8p`8sA@`ZyHe^t7XM/vt4{_I~.+Qbuc+x$+>u<&mt8*=~-Jh3m->&F' );
define( 'LOGGED_IN_KEY',    '71N% A.:AuZMqH|Lq1tqYja7L=lW2x84;(%Fn?`;`:fAa;Du+$0n8V&6Y_D<.FyK' );
define( 'NONCE_KEY',        'ARy-3Z2+|aepa)e|~SsBE>[kd7GeyDa/=65(sWT:v5mz=Osz]0^gH/7[Xqj}T{4K' );
define( 'AUTH_SALT',        '.{ U jnk9VmH~GU!z9d?Xsq~G+5.WLjm=.3MJwiN1]+ra<4m:[iTU%0A@2SNvknP' );
define( 'SECURE_AUTH_SALT', '.~d!x*60@.2OG?O>BUToJ?Nu}LS&|lOHHkrBp[v.$xwM?{VZ2m:~[u0}T6r/$c4l' );
define( 'LOGGED_IN_SALT',   'u^g(Z`kV2za3T-)&_qXC&4Qq~T;]0$8qQ|Xu-F2FTv9+>Nba`vE%mfwZ%d/>.9U`' );
define( 'NONCE_SALT',       'g?W=` XBGL{t A@UI>xmo3/^G+`[X|W%u06vY_QyAHO:1`DWrwg?8/U<$9a)E,gK' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
