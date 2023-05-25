<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @since      1.0.0
 *
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove .git directory
$git_directory = __DIR__ . '/.git';
if (is_dir($git_directory)) {
    removeDirectory($git_directory);
}

// Create a backup of the custom post types
$args = array(
	'post_type' => 'hm_book',
	'posts_per_page' => -1,
);
$books = new WP_Query($args);

$csv_data = array();
$csv_data[] = array('Title', 'Author', 'ISBN', 'Price', 'Content');

if ($books->have_posts()) {
    while ($books->have_posts()) {
		$books->the_post();
		$title = get_the_title();
		$author = get_post_meta(get_the_ID(), 'book_author_name', true);
		$isbn = get_post_meta(get_the_ID(), 'book_isbn', true);
		$price = get_post_meta(get_the_ID(), 'book_price', true);
		$content = get_post_field('post_content');	
		$csv_data[] = array($title, $author, $isbn, $price, $content);
	}
	wp_reset_postdata();
}

// Create a backup of the custom taxonomies
$author_terms = get_terms(array(
    'taxonomy' => 'author',
    'hide_empty' => false,
));
$genre_terms = get_terms(array(
    'taxonomy' => 'genre',
    'hide_empty' => false,
));

  // Combine post data and taxonomy data
$csv_data = array_merge($csv_data, termsToCsvData($author_terms, 'Author'));
$csv_data = array_merge($csv_data, termsToCsvData($genre_terms, 'Genre'));

  // Export data as a CSV file
$backup_directory = WP_CONTENT_DIR . '/bookstore-orzanizer-backups/';
wp_mkdir_p($backup_directory);

$backup_filename = 'bookstore-organizer-backup-' . date('Y-m-d') . '.csv';
$backup_path = $backup_directory . $backup_filename;
exportCsv($backup_path, $csv_data);

// Optionally, you can store the backup file path in the plugin settings or send it via email, etc.


function termsToCsvData($terms, $taxonomy) {
	$csv_data = array();
	if($terms){
		foreach ($terms as $term) {
			if (isset($term->name)) {
				$csv_data[] = array($term->name, $taxonomy);
			}
		}
	}

	return $csv_data;
}

function exportCsv($file, $data) {
	$handle = fopen($file, 'w');
	if ($handle){
		foreach ($data as $row) {
			fputcsv($handle, $row);
		}
		fclose($handle);
	}else{
		error_log('Failed to open the backup file: ' . $file);
	}

}

return;
delete_all_books();
function delete_all_books($post_type = 'he_book') {
	$args = array(
	    'post_type' => $post_type,
	    'posts_per_page' => -1, // Retrieve all posts
	);
  
	$books = new WP_Query($args);
  
	if ($books->have_posts()) {
	    while ($books->have_posts()) {
		  $books->the_post();
		  wp_delete_post(get_the_ID(), true); // Set the second parameter to true to bypass trash
	    }
	}
  
	wp_reset_postdata();
}

// Recursive function to remove a directory and its contents
function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != '.' && $object != '..') {
				if (is_dir($dir . '/' . $object)) {
					rrmdir($dir . '/' . $object);
				} else {
					unlink($dir . '/' . $object);
				}
			}
		}
		rmdir($dir);
	}
}
  
/**
 * Recursively remove a directory and its contents.
 *
 * @param string $directory Directory path to remove.
 */
function removeDirectory($directory) {
	if (!is_dir($directory)) {
	    return;
	}
  
	$files = array_diff(scandir($directory), array('.', '..'));
	foreach ($files as $file) {
	    $path = $directory . '/' . $file;
	    if (is_dir($path)) {
		  removeDirectory($path);
	    } else {
		  if (is_writable($path)) {
			// Remove read-only attribute if set
			$attributes = file_exists($path) ? fileperms($path) : null;
			if ($attributes !== null && ($attributes & 0x0100) === 0x0100) {
			    chmod($path, $attributes & ~0x0100);
			}
			unlink($path);
		  }
	    }
	}
  
	// Try to remove the directory forcefully
	if (is_dir($directory)) {
	    rmdir($directory);
	}
  }