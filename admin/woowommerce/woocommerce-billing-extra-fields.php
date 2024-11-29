<?php
add_filter( 'woocommerce_checkout_fields' , 'simplbooks_woocommerce_checkout_field_editor_reg_nr' );
// Our hooked in function - $fields is passed via the filter!
function simplbooks_woocommerce_checkout_field_editor_reg_nr( $fields ) {
    $fields['billing']['shipping_field_reg_nr'] = array(
        'label'     => __('Company Reg Nr', 'woocommerce'),
        'placeholder'   => _x('Company Reg Nr', 'placeholder', 'woocommerce'),
        'required'  => true
    );
    return $fields;
}

add_filter( 'woocommerce_checkout_fields' , 'simplbooks_woocommerce_checkout_field_editor_VAT_reg_nr' );
function simplbooks_woocommerce_checkout_field_editor_VAT_reg_nr( $fields ) {
    $fields['billing']['shipping_field_VAT_reg_nr'] = array(
        'label'     => __('Company VAT Reg Nr', 'woocommerce'),
        'placeholder'   => _x('Company VAT Reg Nr', 'placeholder', 'woocommerce'),
        'required'  => true
    );
    return $fields;
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'simplbooks_edit_woocommerce_checkout_page', 10, 1 );
function simplbooks_edit_woocommerce_checkout_page( $order ){
    global $post_id;
    $order = new WC_Order( $post_id );
    echo '<p><strong>'.__('VAT Reg Nr').':</strong> ' . get_post_meta($order->get_id(), '_shipping_field_VAT_reg_nr', true ) . '</p>';

    echo '<p><strong>'.__('Reg Nr').':</strong> ' . get_post_meta($order->get_id(), '_shipping_field_reg_nr', true ) . '</p>';
}


function simplbooks_custom_notes_on_single_order_page( $order ){

      //$order = $this->order_id;
      $category_array=array();
      foreach( $order->get_items() as $item_id => $item ) {
          $product_id=$item['product_id'];
          $product_cats = wp_get_post_terms( $product_id, 'product_cat' );
          foreach( $product_cats as $key => $value ){
              if(!in_array($value->name,$category_array)){
                      array_push($category_array,$value->name);
              }
          }
      }
      $note = '<b>This order has sent to Simplbooks Server</b>';
      echo $note;
      $order->add_order_note( $note );

}
add_action( 'woocommerce_order_details_after_order_table', 'simplbooks_custom_notes_on_single_order_page',10,1 );
