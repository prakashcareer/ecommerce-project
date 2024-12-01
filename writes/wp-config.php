<?php
define( 'WP_CACHE', true );

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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u858493842_6mXYb' );

/** Database username */
define( 'DB_USER', 'u858493842_9Mx3d' );

/** Database password */
define( 'DB_PASSWORD', 'Zo708hTVBW' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          ':bL3k9U,_#{A^^im: 1HX_5s md;ux]#s#QC}S<j<B_,?H}#!ajwdw3ude]QA~v^' );
define( 'SECURE_AUTH_KEY',   '5Eh7tD-;>!4G}+06&.?B;Cs?DIu} /YnhzZSrbN_jC;EBuC)M?=Bu88uvJw&Z)!s' );
define( 'LOGGED_IN_KEY',     '_-ch)mfm}2{A`C{]l%w~MvsV8,d+7M9sG { ,*[:UIqSW<CD <dE,4t2sk>M+wp1' );
define( 'NONCE_KEY',         'M1Vli ^I1kf#[PoW!cdEqd4ej52rqW.Xz&(L5&yW`$(O[CU1[s8v#aXoHramFE:x' );
define( 'AUTH_SALT',         '2{zBF0~UR-,*xL/C>BcET+1se_7rjEnO;0@@zxhd,#r:7bi~Q^^I:L}:<n@v6OA;' );
define( 'SECURE_AUTH_SALT',  'M#:2p_ct;#o0|`75t&Ia|e!]PB|+[;9Px[fOfF?5B gv?HJ6;*r_WF3J!e?DuPdh' );
define( 'LOGGED_IN_SALT',    '3&n+ EFNx`/GL{O4fUQ7|6fhBo_Z5cHy]:jC /s:^Gkwrp](D:us2w~pAYTZ{13!' );
define( 'NONCE_SALT',        'oh}2)yO9P/>EZ+EkBk.]Pm;(#&q1?02@YW4&.HuzXk=+A;$PJ9p${`=#rEI@{Ldt' );
define( 'WP_CACHE_KEY_SALT', ');h;,vKJ(u]!hN@jnt{m*YfEgF(ju[oqEj6I!)/Sp|SzHkuFT<ga!6ihx-LQh-#!' );


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



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
