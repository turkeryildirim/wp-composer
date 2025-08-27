<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

require_once __DIR__ . '/init.php';

setlocale( LC_ALL, 'C' );

define( 'SITE_DOMAIN', env( 'SITE_DOMAIN', 'http://localhost' ) );
define( 'SITE_ENV', env( 'SITE_ENV', 'local' ) );

const WP_HOME        = SITE_DOMAIN;
const WP_SITEURL     = SITE_DOMAIN . '/wordpress';
const WP_CONTENT_DIR = __DIR__ . '/app';
const WP_CONTENT_URL = SITE_DOMAIN . '/app';

define( 'SITE_TITLE', env( 'SITE_TITLE', 'My WordPress' ) );
define( 'SITE_ADMIN_USER', env( 'SITE_ADMIN_USER', 'admin' ) );
define( 'SITE_ADMIN_EMAIL', env( 'SITE_ADMIN_EMAIL', 'admin@wordpress.test' ) );

// MySQL.
// phpcs:ignore
$table_prefix = env( 'MYSQL_DB_PREFIX', 'wp_' );

define( 'DB_NAME', env( 'MYSQL_DB', 'wordpress' ) );
define( 'DB_USER', env( 'MYSQL_USER', 'root' ) );
define( 'DB_PASSWORD', env( 'MYSQL_PASSWORD', '' ) );
$db_host = env( 'MYSQL_HOST', 'localhost' ) . ':' . env( 'MYSQL_PORT', '3306' );
define( 'DB_HOST', $db_host );
define( 'DB_CHARSET', env( 'MYSQL_CHARSET', 'utf8mb4' ) );
define( 'DB_COLLATE', env( 'MYSQL_COLLATION', 'utf8mb4_unicode_ci' ) );

// AWS S3.
define( 'AWS_S3_KEY', env( 'REDIS_PREFIX', 's3_key' ) );
define( 'AWS_S3_SECRET', env( 'REDIS_PREFIX', 's3_secret' ) );
define( 'AWS_S3_BUCKET', env( 'REDIS_PREFIX', 's3_bucket' ) );
define( 'AWS_S3_REGION', env( 'REDIS_PREFIX', 's3_region' ) );
define( 'AWS_S3_DOMAIN', env( 'REDIS_PREFIX', 's3_domain' ) );

// Redis.
define( 'WP_REDIS_PREFIX', env( 'REDIS_PREFIX', 'local' ) );
define( 'WP_REDIS_CLIENT', env( 'REDIS_CLIENT', 'phpredis' ) );
define( 'WP_REDIS_HOST', env( 'REDIS_HOST', '127.0.0.1' ) );
define( 'WP_REDIS_PORT', env( 'REDIS_PORT', 6379 ) );
define( 'WP_REDIS_PASSWORD', env( 'REDIS_PASSWORD' ) );
define( 'WP_REDIS_DATABASE', env( 'REDIS_DB', 0 ) );
define( 'WP_REDIS_SCHEME', env( 'REDIS_SCHEME', 'tcp' ) );
define( 'WP_REDIS_MAXTTL', env( 'REDIS_MAXTTL', 3600 ) );
define( 'WP_REDIS_DISABLED', filter_var( env( 'REDIS_DISABLED', false ), FILTER_VALIDATE_BOOLEAN ) );

// Mail.
define( 'WP_MAILER', env( 'MAILER', 'mail' ) );
define( 'WP_MAIL_HOST', env( 'MAIL_HOST', 'localhost' ) );
define( 'WP_MAIL_PORT', env( 'MAIL_PORT', 25 ) );
define( 'WP_MAIL_USERNAME', env( 'MAIL_USERNAME', '' ) );
define( 'WP_MAIL_PASSWORD', env( 'MAIL_PASSWORD', '' ) );
define( 'WP_MAIL_ENCRYPTION', env( 'MAIL_ENCRYPTION', '' ) );
define( 'WP_MAIL_FROM_ADDRESS', env( 'MAIL_FROM_ADDRESS', 'wordpress@local.test' ) );
define( 'WP_MAIL_FROM_NAME', env( 'MAIL_FROM_NAME', 'WordPress Dev' ) );

// Salt.
define( 'AUTH_KEY', env( 'AUTH_KEY', '' ) );
define( 'SECURE_AUTH_KEY', env( 'SECURE_AUTH_KEY', '' ) );
define( 'LOGGED_IN_KEY', env( 'LOGGED_IN_KEY', '' ) );
define( 'NONCE_KEY', env( 'NONCE_KEY', '' ) );
define( 'AUTH_SALT', env( 'AUTH_SALT', '' ) );
define( 'SECURE_AUTH_SALT', env( 'SECURE_AUTH_SALT', '' ) );
define( 'LOGGED_IN_SALT', env( 'LOGGED_IN_SALT', '' ) );
define( 'NONCE_SALT', env( 'NONCE_SALT', '' ) );

// some common setups, make changes if needed.
define( 'AUTOSAVE_INTERVAL', intval( env( 'AUTOSAVE_INTERVAL', 300 ) ) );

define( 'WP_POST_REVISIONS', intval( env( 'WP_POST_REVISIONS', 3 ) ) );
define( 'DISALLOW_FILE_EDIT', filter_var( env( 'DISALLOW_FILE_EDIT', false ), FILTER_VALIDATE_BOOLEAN ) );

define( 'WP_MEMORY_LIMIT', env( 'WP_MEMORY_LIMIT', '256M' ) );
define( 'WP_MAX_MEMORY_LIMIT', env( 'WP_MAX_MEMORY_LIMIT', '1024M' ) );

define( 'COMPRESS_CSS', filter_var( env( 'COMPRESS_CSS', false ), FILTER_VALIDATE_BOOLEAN ) );
define( 'COMPRESS_SCRIPTS', filter_var( env( 'COMPRESS_SCRIPTS', false ), FILTER_VALIDATE_BOOLEAN ) );
define( 'CONCATENATE_SCRIPTS', filter_var( env( 'CONCATENATE_SCRIPTS', false ), FILTER_VALIDATE_BOOLEAN ) );

define( 'WP_DEBUG', filter_var( env( 'WP_DEBUG', false ), FILTER_VALIDATE_BOOLEAN ) );
define( 'WP_DEBUG_LOG', filter_var( env( 'WP_DEBUG_LOG', false ), FILTER_VALIDATE_BOOLEAN ) );
define( 'WP_DEBUG_DISPLAY', filter_var( env( 'WP_DEBUG_DISPLAY', false ), FILTER_VALIDATE_BOOLEAN ) );

if ( WP_DEBUG ) {
	/**
	 * This filter runs before it can be used by plugins. It is designed for non-web runtimes.
	 * Returning false causes the WP_DEBUG and related constants to not be checked and the default
	 * PHP values for errors will be used unless you take care to update them yourself.
	 */
    // phpcs:ignore
	$GLOBALS['wp_filter'] = array(
		'enable_wp_debug_mode_checks' => array(
			10 => array(
				array(
					'accepted_args' => 0,
					'function'      => function () {
						return false;
					},
				),
			),
		),
	);
}

// don't make any change below.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/wordpress/' );
}
require_once ABSPATH . 'wp-settings.php';
