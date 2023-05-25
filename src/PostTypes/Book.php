<?php
namespace BookstoreOrganizer\PostTypes;
class Book{

      public static function register(){
            register_post_type('hm_book',
		array(
			'labels'      => array(
				'name'          => __('Books', 'textdomain'),
				'singular_name' => __('Book', 'textdomain'),
			),
				'public'      => true,
				'has_archive' => true,
				'supports' => array('title', 'editor', 'thumbnail'),
				'taxonomies' => array('author', 'genre'),
		)
	);
      }
}