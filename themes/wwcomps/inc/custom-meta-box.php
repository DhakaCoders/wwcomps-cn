<?php

/*Custom Meta Box*/

/* Remove specific page metaboxes*/
function specific_page_remove_meta_boxes() {
  global $post;
  $frontID = get_option( 'page_on_front' );
  if( $frontID == $post->ID)
    remove_meta_box( 'cbv_az_metabox_id', 'page', 'cbv-custom-metabox-holder' );

}
add_action( 'admin_head', 'specific_page_remove_meta_boxes' );


/*Add custom meta box*/
function cbv_custom_meta_box_markup($post){
  $custom_page_title = get_post_meta( $post->ID, '_custom_page_title', true );
  // We'll use this nonce field later on when saving.
  wp_nonce_field( 'somerandomstr', '_cbvnonce' );
?>
  <div id="custom-page-title">
    <input type="text" name="_custom_page_title" id="_custom_page_title" spellcheck="true" autocomplete="off" value="<?php echo esc_attr( $custom_page_title ); ?>" style="width: 100%;height: 34px;">
  </div>
<?php
}

function cbv_add_custom_meta_box() {

    add_meta_box(
      'cbv_az_metabox_id',
      __( 'Custom Page Title', THEME_NAME ),
      'cbv_custom_meta_box_markup',
      'page',
      'cbv-custom-metabox-holder' //Look what we have here, a new context
    );
  
}
add_action( 'add_meta_boxes', 'cbv_add_custom_meta_box' );


add_action( 'save_post', 'cbv_save_meta', 10, 2 );
 
function cbv_save_meta( $post_id, $post ) {
 
  // nonce check
  if ( ! isset( $_POST[ '_cbvnonce' ] ) || ! wp_verify_nonce( $_POST[ '_cbvnonce' ], 'somerandomstr' ) ) {
    return $post_id;
  }
 
  // check current use permissions
  $post_type = get_post_type_object( $post->post_type );
 
  if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
    return $post_id;
  }
 
  // Do not save the data if autosave
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
    return $post_id;
  }
 
  // define your own post type here
  if( $post->post_type != 'page' ) {
    return $post_id;
  }
 
  if( isset( $_POST[ '_custom_page_title' ] ) ) {
    update_post_meta( $post_id, '_custom_page_title', $_POST[ '_custom_page_title' ] );
  }else {
    delete_post_meta( $post_id, '_custom_page_title' );
  } 
 
  return $post_id;
 
}

function cbv_create_new_metboax_context( $post ) {
    
    do_meta_boxes( null, 'cbv-custom-metabox-holder', $post );
}
add_action( 'edit_form_after_title', 'cbv_create_new_metboax_context' );
