<?php 
namespace BookstoreOrganizer\PostTypes;
use BookstoreOrganizer\PostTypes\Book;
class PostTypes{

      public static function register_all_post_types(){
            Book::register();
      }
}