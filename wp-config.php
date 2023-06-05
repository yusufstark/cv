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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'profile1' );


/** Database username */
define( 'DB_USER', 'profile1' );


/** Database password */
define( 'DB_PASSWORD', '[Le)Ey.Vt35~' );


/** Database hostname */
define( 'DB_HOST', 'cpc80979-perr17-2-0-cust594.19-1.cable.virginm.net' );


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
define( 'AUTH_KEY',         'UdN-RM`ne8)EpWc55Fh,dGh!:`J$4GV tq+`LyI@gYC)xX%v*ukzXs;2k$wei:c%' );

define( 'SECURE_AUTH_KEY',  '3^34mh)o^]`WcJ.g[J=@9D)=~1;GZ@Dd`|7Klw58y>N!6J5`K-1@zy5F~1?g6xB/' );

define( 'LOGGED_IN_KEY',    'v6:CW!T8~oUhL,xTJm6^0%8`6+i~ #0U[|Z<m.1D*Mm%`UMW)*+frvl;!B&,LIYV' );

define( 'NONCE_KEY',        '`0>h)rv%~QI})F-,.!5L<wZxj2Roh[,Jg|Jw( Oq@.Xi*(kkXeLv*zA?dV>j,[LC' );

define( 'AUTH_SALT',        'F}j~4g47;H=S0ly#`}7?9&2QDldH~Ach<C:=tIs9n:2[)u`p!KP,.0>v#0A-5+Zm' );

define( 'SECURE_AUTH_SALT', ';Oh#`t4vB>+twNk/<-d,%i4wflH.^LX#t)z?s-RVoOac,LmDG=JlwBn(-V),gG(s' );

define( 'LOGGED_IN_SALT',   'SM#c)<IfQkOtXReM*l`$R{3j!5euJnQiLy(8lUJXrK:XM8=Zl|1m5Cx3~CUozW,E' );

define( 'NONCE_SALT',       'D3Y-#H}6E7aPEbYqZf><$X+NYORw{|*a#..$4Z$)^ij,&!<#nsFb_FD^! }>.$x?' );


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
