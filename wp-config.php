<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'u709361739_labsg');


/** MySQL database username */
define('DB_USER', 'u709361739_labsg');


/** MySQL database password */
define('DB_PASSWORD', '1r2h3o4n5y');


/** MySQL hostname */
define('DB_HOST', 'localhost');



/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'v~0Jn`lI_2C%{oO8X u|N03sl4|#|Ir1&G@er(/KJYO/Q%VQCJx|]d+ksNpU)&?v');
define('SECURE_AUTH_KEY',  '|exIE}st0 z[ENo|fXDg||71Aw{5>;4xq4w8uRE%qeU&yA_Vpje1[t_,51}.Gnq_');
define('LOGGED_IN_KEY',    ',95V/MY8q|j-UAy^!jWDftuG.*}zvGxubT9-O)}[RT,9qS,M[JBZWSJ}c>Cg5^<4');
define('NONCE_KEY',        'j3?4-ymhq!;$j+yJ2+5_/S=j0#-|>sbRd7qEj@pM6E8lh-v<LRlqw]HXJ0yzK1GS');
define('AUTH_SALT',        'GIUO_mmjxh[~.Umc~Y;mCD+n0xzidhb6zSnu1OmKd[<&..2l2cwQ[wCR<p^{p&k)');
define('SECURE_AUTH_SALT', '|-|{{6p|!u[LH3]crIrlc~z7$r~B@orO|%j6h3!gH_A43P NGF:|C-{NzE|<,jp,');
define('LOGGED_IN_SALT',   'VH/Qn`o-|Re>z]aDGITYp2).vv/,UcRbf&d D_ger|-X-oVp()6V}Oc|cD`InZg,');
define('NONCE_SALT',       'uy-_^hR#GJqLZNXXI|u0T7jG6XzSg79xd)nQw}pU(]fD57RXc[^gr[O[+=2bGLte');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

