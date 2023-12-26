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

define( 'DB_NAME', 'jim' );


/** Database username */

define( 'DB_USER', 'wp-user' );


/** Database password */

define( 'DB_PASSWORD', 'Jim@123' );


/** Database hostname */

define( 'DB_HOST', 'localhost' );


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

define('AUTH_KEY',         'Z@$Y^~kcor^LH:v@Y:H>ABY7,)QnQqM<11j(T^+Z/,v-.bn;r*ArQ@dq?`$@+Hae');

define('SECURE_AUTH_KEY',  '1EDzOwNT~9A|pUJD2~L-sw4ck?qe-,JVCSch{h2;4Dn7<0MAK$0@GKsnxYg6vxL|');

define('LOGGED_IN_KEY',    's^Wya+RrfLz$Hg@P/OT*IEr[_+afab_$|{5{6>J4ZB|Nc1jW)k|^?|b$!I~}M`AQ');

define('NONCE_KEY',        '+W!lS)J/T]:y;x/AT#|%Qgoq!ow`X<w#dW<av|Z=9LnV- +<zBpD:GIJF|%kQLmU');

define('AUTH_SALT',        '|M}4NDYI5Oqas50q9{JVsC;o0|ni?IPAE(r(~~$|-DK}y|oD9H|Cdv.4t>^RM(Pf');

define('SECURE_AUTH_SALT', '<LZm`[[|Y2;f0GFt@6~#t@;da`i]my*{9S L(4T.0Go^$`M1PB]I)Z;^Y-g8zBKJ');

define('LOGGED_IN_SALT',   '-v?lxjW.2=h;1P1`jS53}!dGMk4WfZ:|L+DP:v2)||R>31-dRX1X4&|V@gxb~H`k');

define('NONCE_SALT',       '-W0U!=.f95|g+Sk=#w|0`8S<]ksl6ToUIN(g8ET9FB5?-?Mbc@2?ZjbzlV8K>*&E');


/**#@-*/


/**

 * WordPress database table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = 'hjm_';


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




define( 'DISALLOW_FILE_EDIT', false );
define( 'CONCATENATE_SCRIPTS', false );
/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/hjm' );

}


/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';

