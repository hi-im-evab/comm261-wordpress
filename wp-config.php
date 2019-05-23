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
define( 'DB_NAME', 'eveggers_261s19' );

/** MySQL database username */
define( 'DB_USER', 'eveggers_261s19' );

/** MySQL database password */
define( 'DB_PASSWORD', '1yEgg21I0c' );

/** MySQL hostname */
define( 'DB_HOST', 'rei.cs.ndsu.nodak.edu' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '_)p7%.!R=xmF`H,l[`n0&?MD(j{d#b2V1xX!yR)8Qsx(RbSA:3Tn]a~F+G~(XIlp' );
define( 'SECURE_AUTH_KEY',  'hfThrLD~ rb^0`0P1Ds35D<&_zxo ^_;wXym[OjewY-o|C6vETVVIFY(bnUyQl%(' );
define( 'LOGGED_IN_KEY',    'Ta#n& 6PHuHZY/PNeKI%k^{F:tP+!**IJhk{49yny/_dmLe[uv#[N7qRhLbU*Dlk' );
define( 'NONCE_KEY',        '?iT%Zt?8j9x^EUF.o+Ur#3K=O yLA_vWDWwNOa|epr^|LTJ FSA^ bo@Owl(Q#k]' );
define( 'AUTH_SALT',        '%c=aR`Ge2L|JJkjQ=t_i[eK(2P@Ld>4A^>FEWOh_]vhlm-m]CL,?ytU;-*a#h-V;' );
define( 'SECURE_AUTH_SALT', '{y]+eM}:G@V&+Z,ywf]<mY@SA^,o=V77^+z6$nyXvM^B{%bx|y5Ayq@l__LV$7#>' );
define( 'LOGGED_IN_SALT',   'H+b~4k9S6stKk@fF0|>!h<oJqXCof>wl5uDwuK0F>w$/h5ML/9jUsII4s]Q2BFpI' );
define( 'NONCE_SALT',       '}7%[XgD=2sFR`oc@oAn9>R[;&@$!q5zL<i_]iqs2|pl9]yiP~#szCZ).l;*9#It,' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
