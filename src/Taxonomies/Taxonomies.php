<?php

namespace BookstoreOrganizer\Taxonomies;
class Taxonomies
{

      private static $taxonomies = [];

      public function __construct($args)
      {
            self::$taxonomies = $args;
      }

      public function register_all_taxonomies()
      {
            foreach (self::$taxonomies as $key => $taxonomy) {
                  $this->register_taxonomy($taxonomy);
            }
      }

      public function register_taxonomy($taxonomy)
      {
            $labels = array(
                  'name'              => $taxonomy . 's',
                  'singular_name'     => $taxonomy,
                  'search_items'      => 'Search' . ' ' . $taxonomy . 's',
                  'all_items'         => 'All' . ' ' . $taxonomy . 's',
                  'parent_item'       => 'Parent' . $taxonomy,
                  'parent_item_colon' => 'Parent' . $taxonomy . ':',
                  'edit_item'         => 'Edit' . $taxonomy,
                  'update_item'       => 'Update' . $taxonomy,
                  'add_new_item'      => 'Add New' . $taxonomy,
                  'new_item_name'     => 'New' . $taxonomy . 'Name',
                  'menu_name'         => $taxonomy . 's',
            );

            $args = array(
                  'hierarchical'      => true,
                  'labels'            => $labels,
                  'show_ui'           => true,
                  'show_admin_column' => true,
                  'query_var'         => true,
                  'rewrite'           => array('slug' => $taxonomy),
            );

            register_taxonomy($taxonomy, 'hm_book', $args);
      }
}
