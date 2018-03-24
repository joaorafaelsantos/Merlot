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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'merlot');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'Kfi&>G#*V&mK`;Arzbs75y5[+XbSeMj>rAVb9$##]sqpiXruA2SSkt/2WCr3rap2');
define('SECURE_AUTH_KEY',  'fO85Y.F_`]KnjJh{>Orw}Q^W)e^V6a6]f:DTw}&~WDM4:_x&,|J}*>b:m-m&X=pJ');
define('LOGGED_IN_KEY',    'x/<0Q-;b;~!<(Ji &Sq4vkLOB9TMg?FiX5fDU7XU@j%e>;};GrUTcp -Dhn.k;MT');
define('NONCE_KEY',        '#!7_SBZYU%P9]bnAr^rNo3fl&~$iW$#B]:0X/BTH7>i8v<i^|PRi;?y6Po~c/Qlf');
define('AUTH_SALT',        '!g4%P!y@llvCi[#xJ_zRC38s~(OL4y*hLs%=o`krbUO=cF| @EZ^5)8jO-wC yU%');
define('SECURE_AUTH_SALT', 'vd]6YlAk]5:y,+Hi=6[.@pS^2cFdr[($cl@p*-L-Kd+`rCuw!aWbZgAO7`~T^pc(');
define('LOGGED_IN_SALT',   'FAWUl<#P`:[#X-97H(_{9TpA_&.+brawA.}dX@KtwXAmKDWb`YfxQ3:^/,upR)|e');
define('NONCE_SALT',       '<D>9%Q;G4TjQ $ni57W:@pc$G62 4q6H)>4C~L! o+pU-U P{m&>8w&d_y^D5=w^');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ffnst_';

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
//Disable File Edits
define('DISALLOW_FILE_EDIT', true);