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
define('DB_NAME','playaren_productionWP_st1');

/** MySQL database username */
define('DB_USER','playaren_st1');

/** MySQL database password */
define('DB_PASSWORD','Xm9aFLdfW.JrWsBwaNje');

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
define('AUTH_KEY',         'dfpbf2xcxaxblh4xvqgyrklwbkikr04flrxxegc9pi4nwqcgaxvntviomeg3ckx2');
define('SECURE_AUTH_KEY',  'ndk8um5rqtgchdsqa8gg2ji5ghcd4en69ech0tzgxhzuwjrd2epjghvuefsiaffu');
define('LOGGED_IN_KEY',    '4xyvskqniaiayluheojgzl3hakwgqnj1yklgns3xz19x6iia8p6xf9i8vfpi7spr');
define('NONCE_KEY',        'bg2lkjac5vkrtin2u4lwbl6df76m9bzjwn3gxa7zthjlayhulyigr4hb0xpai7in');
define('AUTH_SALT',        '8dm3okowitz1pqrqcxlbzj3kctmxo2mmb6qjknfwwyf8gr5qhdkgzrhlqktxvl6f');
define('SECURE_AUTH_SALT', 'hyrio5rxbtiks33gn83lkt6qmpwp4xotoana6evd6vgbcloo8swzth6zajoujmmo');
define('LOGGED_IN_SALT',   'u8wojbql4wxyfm0zykwgyms7v9eycfnxurnkdild8tbcvbvw5sxssvfs3toqhfow');
define('NONCE_SALT',       'kg486esadqcechcccqzur7zbbfoztu3z4k3xsjo4axqhinybfyzus7kizlufkqdj');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
define('WP_CACHE_KEY_SALT', 'P9SGBzjq/hYArgGTJp41xw');
$table_prefix  = 'wp4s_';

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

 // Enable WP_DEBUG mode
define( 'WP_DEBUG', true );

// Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', true );

// Disable display of errors and warnings 
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', false );
 
define('WP_CACHE', false);
define( 'WP_MEMORY_LIMIT', '128M' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

# Disables all core updates. Added by SiteGround Autoupdate:
define( 'WP_AUTO_UPDATE_CORE', false );
