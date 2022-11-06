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
define( 'DB_NAME', 'bain' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '123456' );

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
define( 'AUTH_KEY',         'u%`m%R 26|Or~IVd&L4wrc{1mAxG}67@?Z7l&#9bUy[`~BOk8)O&v:?v%h8N[F^g' );
define( 'SECURE_AUTH_KEY',  ']jXLv_?F5r:Y ^}bg2;Q!_at4H`qepWz`4A-+/ N^3xhDu!U4=]J$aF>@m23M#$N' );
define( 'LOGGED_IN_KEY',    'rjuy6^h/n7^(@*rk~?K&h(h.XFx@EQ q)~0Y]ufZ8QO;*)>2&qBN=FpLeGMM|<NQ' );
define( 'NONCE_KEY',        'qVSu;S^<{s!V~FIafA+#sB,@V<BT;C[kpfNK`g%H+]CC7DPXb9,MB5]8w?w.:u}k' );
define( 'AUTH_SALT',        ';]4wlrKa_Lg[)R;M(~})3zOeYaRK7Tyq8o>1_- oJ/PPl%+KfbL[]H^Rj`d*;xZ@' );
define( 'SECURE_AUTH_SALT', 'R/! -o,jsd&qGER<wFG&2v}988q6{[!kwJQsy|?F^g=g2-BKLbcmC%jPMLxSiA2q' );
define( 'LOGGED_IN_SALT',   'n1tiS3Kr`t4=.z,P.(r)l5-(NX/GIY5h]^1nRi1hnFJRA8+1qj`NfHtyPeLU@SNo' );
define( 'NONCE_SALT',       ',Y#m=^R6<teU ~w&qzSUeRpq[;nu=z2.g4Kx&G.@Gst^~;&S#m;Nnx LQWT!0gz3' );

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
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
