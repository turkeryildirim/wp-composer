<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

const WP_USE_THEMES = true;
require __DIR__ . '/wordpress/wp-blog-header.php';
