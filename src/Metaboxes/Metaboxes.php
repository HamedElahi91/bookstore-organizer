<?php 
namespace BookstoreOrganizer\MetaBoxes;
// use BookstoreOrganizer\MetaBoxes\AuthorMetabox;
// use BookstoreOrganizer\MetaBoxes\IsbnMetabox;
// use BookstoreOrganizer\MetaBoxes\PriceMetabox;
class MetaBoxes{

      private static $metaboxes = [];

      public function __construct($args)
      {
            self::$metaboxes = $args;
            
      }

      public function add_all_metaboxes(){
            
            // AuthorMetabox::book_author_metaboxe();
            // IsbnMetabox::book_isbn_metaboxe();
            // PriceMetabox::book_price_metaboxe();
            foreach( self::$metaboxes as $metakey => $metaboxe){
                  $this->add_custom_metabox($metakey, $metaboxe);
            }
             
      }

      private function add_custom_metabox( $metakey, $metaboxe){
            global $post;
            add_meta_box(
                  'book_' . $metakey,
                  'Book ' . $metaboxe,
                  function() use ($metakey, $post){
                        $name = get_post_meta($post->ID, 'book_' . $metakey, true);
                        ?>
                        <input type="text" name="<?php echo 'book_' . $metakey; ?>" id="<?php echo 'book_' . $metakey; ?>" value="<?php echo esc_attr($name); ?>">
                        <?php     
                  },
                  'hm_book',
                  'normal',
                  'default'
              );
      }

      public function bookstore_organizer_save_metaboxes($post_id){
            //Checking for manual save
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                  return;
            }
            // [TODO:] Save action must to come dynamic after option page created.
           
            foreach(self::$metaboxes as $metakey => $metaboxe){
                  switch('book_' . $metakey){
                        case 'book_author':
                              if( self::check_empty_entry($_POST['book_author']) ){
                                    add_settings_error('missing_fields','missing_fields','Please complete all meta data. The Author field is necessary', 'error');
                                    set_transient('settings_errors', get_settings_errors(),30);
                              }else{
                                    error_log($_POST['book_' . $metakey]);
                                    update_post_meta($post_id, 'book_author', sanitize_text_field($_POST['book_author']));
                              }
                              break;
                        case 'book_isbn':
                              if(! (is_numeric($_POST['book_isbn']) ) || strlen($_POST['book_isbn']) < 13 || self::check_empty_entry($_POST['book_isbn']) ){
                                    add_settings_error('invalid_isbn','invalid_isbn','Please enter a valid ISBN. The ISBN must be numerical and at least 13 digits', 'error');
                                    set_transient('settings_errors', get_settings_errors(),30);
                              }else{
                                    update_post_meta($post_id, 'book_isbn', sanitize_text_field($_POST['book_isbn']));
                              }
                              break;      
                        case 'book_price':
                              if( is_numeric($_POST['book_price'] ) && !(self::check_empty_entry($_POST['book_price'])) ){
                                    update_post_meta($post_id, 'book_price', sanitize_text_field($_POST['book_price']));
                              }else{
                                    add_settings_error('invalid_price','invalid_price','Please enter a valid Price', 'error');
                                    set_transient('settings_errors', get_settings_errors(),30);
                              }
                              break;
                        default:
                              error_log($_POST['book_' . $metakey]);
                  }
            }

            

      }

      private function check_empty_entry($metabox_feild){
            if ( empty ($metabox_feild)){
                  return true;
            }
            return false;
      }

      public function book_metabox_validation_notice()
            {
            
                if(!($errors = get_transient('settings_errors'))){
                    return;
                }
              
                $message = '<div id="message" class="notice notice-error is-dismissible">';
                foreach($errors as $error){
                    $message .= '<p>' . $error['message'] . '</p><button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Dismiss this notice.</span></button>';
                }

                echo $message . '</div>';

                delete_transient('settings_errors');
            }
      
}


