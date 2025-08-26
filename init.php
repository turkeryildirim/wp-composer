<?php
$autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $autoload ) ) {
	require_once $autoload;
	Dotenv\Dotenv::createImmutable( __DIR__ )->load();
} else {
	http_response_code( 500 );
	exit( 'Dependencies missing: run "composer install".' );
}

if ( ! function_exists( 'env' ) ) {
	function env( string $env_key, $env_default = null ) {
		$value = $_ENV[ $env_key ] ?? false;

		if ( false === $value ) {
			return $env_default;
		}

		$lower = strtolower( $value );
		if ( in_array( $lower, array( 'true', '(true)' ), true ) ) {
			return true;
		}
		if ( in_array( $lower, array( 'false', '(false)' ), true ) ) {
			return false;
		}
		if ( in_array( $lower, array( 'null', '(null)' ), true ) ) {
			return null;
		}

		if ( is_numeric( $value ) ) {
            // phpcs:ignore
            return (string) $value === (string) (int) $value
				? (int) $value
				: (float) $value;
		}

		return $value;
	}
}
