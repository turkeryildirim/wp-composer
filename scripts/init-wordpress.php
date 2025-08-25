<?php
# 1- gereksiz dosyaları sil.
$targets = array(
	'wordpress/license.txt',
	'wordpress/readme.html',
	'wordpress/composer.json',
	'wordpress/index.php',
	'wordpress/wp-config.php',
	'wordpress/wp-config-sample.php',
	'wordpress/wp-content',
);

function removeDir( $dir ) {
	if ( ! is_dir( $dir ) ) {
		return;
	}
	$items = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::CHILD_FIRST
	);
	foreach ( $items as $item ) {
		$item->isDir() ? rmdir( $item ) : unlink( $item );
	}
	rmdir( $dir );
}

foreach ( $targets as $path ) {
	if ( is_dir( $path ) ) {
		removeDir( $path );
		echo "Deleted: $path\n";
	} elseif ( file_exists( $path ) ) {
		unlink( $path );
		echo "Deleted: $path\n";
	}
}
