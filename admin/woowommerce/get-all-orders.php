<?php
Class Get_Simplbooks_Woocommerce_Orders
{

    static function Get_Woocommerce_Order_Array()
    {
      $get_post_type = get_post_types();

      if ( class_exists( 'WooCommerce' ) )
      {
        $customer_orders = get_posts( array(
            'numberposts' => -1,
            'post_type'   => wc_get_order_types(),
            'post_status' => array_keys( wc_get_order_statuses() ),
        ) );




        foreach ( $customer_orders as $key => $value ) {

          $order = wc_get_order( $value->ID );
          if ( isset( $order->get_user()->data ) ) {
            $user_data = $order->get_user()->data;
          } else {
            $user_data = '';
          }

          $plugin_options = get_option( 'simplbooks' );
          $api_token = $plugin_options['simplbooks_api_token'];
          $url_hash = $plugin_options['simplbooks_url_hash'];
          $items_in_list = $plugin_options['items_in_list'];
          //var_dump( $api_token );

          if ( ! empty( $api_token ) xor 'Insert here' == ! $api_token  )
          {
            $order_array[] = array(
                'id' => $order->get_id(),

                // Get Order Totals $0.00
                'formatted_order_total' => $order->get_formatted_order_total(),
                'cart_tax' => $order->get_cart_tax(),
                'get_currency' => $order->get_currency(),
                'discount_tax' => $order->get_discount_tax(),
                'discount_to_display' => $order->get_discount_to_display(),
                'discount_total' => $order->get_discount_total(),
                'fees' => $order->get_fees(),
                //$order->get_formatted_line_subtotal();
                'shipping_tax' => $order->get_shipping_tax(),
                'shipping_total' => $order->get_shipping_total(),
                'subtotal' => $order->get_subtotal(),
                'subtotal_to_display' => $order->get_subtotal_to_display(),
                //'tax_location' => $order->get_tax_location(),
                'tax_totals' => $order->get_tax_totals(),
                'taxes' => $order->get_taxes(),
                'total' => $order->get_total(),
                'total_discount' => $order->get_total_discount(),
                'total_tax' => $order->get_total_tax(),
                'total_refunded' => $order->get_total_refunded(),
                'total_tax_refunded' => $order->get_total_tax_refunded(),
                'total_shipping_refunded' => $order->get_total_shipping_refunded(),
                'item_count_refunded' => $order->get_item_count_refunded(),
                'total_qty_refunded' => $order->get_total_qty_refunded(),
                'qty_refunded_for_item' => $order->get_qty_refunded_for_item( 'item' ),
                'total_refunded_for_item' => $order->get_total_refunded_for_item( 'item' ),
                //'tax_refunded_for_item' => $order->get_tax_refunded_for_item( 'item' ),
                'total_tax_refunded_by_rate_id' => $order->get_total_tax_refunded_by_rate_id( 'item' ),
                'remaining_refund_amount' => $order->get_remaining_refund_amount( 'item' ),

                // Get Order Items
                'items' => $order->get_items(),
                'meta_data' => $order->get_meta_data( 'value' ),
                //$order->get_items_key();
                'items_tax_classes' => $order->get_items_tax_classes(),
                //'item' => $order->get_item();
                'item_count' => $order->get_item_count(),
                'item_subtotal' => $order->get_item_subtotal( '' ),
                'item_tax' => $order->get_item_tax( '' ),
                'item_total' => $order->get_item_total( '' ),
                'downloadable_items' => $order->get_downloadable_items(),

                // Get Order Lines
                'line_subtotal' => $order->get_line_subtotal( 'view' ),
                'line_tax' => $order->get_line_tax( 'view' ),
                'line_total' => $order->get_line_total( 'view' ),

                // Get Order Shipping
                'shipping_method' => $order->get_shipping_method(),
                //'shipping_methods' => $order->get_shipping_methods(), // error protected data
                //'shipping_to_display' => $order->get_shipping_to_display(),

                // Get Order Dates
                'date_created' => $order->get_date_created(),
                'date_modified' => $order->get_date_modified(),
                'date_completed' => $order->get_date_completed(),
                'date_paid' => $order->get_date_paid(),

                // Get Order User, Billing & Shipping Addresses
                'customer_id' => $order->get_customer_id(),
                'user_id' => $order->get_user_id(),
                'user' => $user_data,
                'customer_ip_address' => $order->get_customer_ip_address(),
                'customer_user_agent' => $order->get_customer_user_agent(),
                'created_via' => $order->get_created_via(),
                'customer_note' => $order->get_customer_note(),
                //$order->get_address_prop();
                'billing_address' => array(
                  'billing_first_name' => $order->get_billing_first_name(),
                  'billing_last_name' => $order->get_billing_last_name(),
                  'billing_company' => $order->get_billing_company(),
                  'billing_address_1' => $order->get_billing_address_1(),
                  'billing_address_2' => $order->get_billing_address_2(),
                  'billing_city' => $order->get_billing_city(),
                  'billing_state' => $order->get_billing_state(),
                  'billing_postcode' => $order->get_billing_postcode(),
                  'billing_country' => $order->get_billing_country(),
                  'billing_email' => $order->get_billing_email(),
                  'billing_phone' => $order->get_billing_phone(),
                ),
                'shipping_address' => array(
                  'shipping_first_name' => $order->get_shipping_first_name(),
                  'shipping_last_name' => $order->get_shipping_last_name(),
                  'shipping_company' => $order->get_shipping_company(),
                  'shipping_address_1' => $order->get_shipping_address_1(),
                  'shipping_address_2' => $order->get_shipping_address_2(),
                  'shipping_city' => $order->get_shipping_city(),
                  'shipping_state' => $order->get_shipping_state(),
                  'shipping_postcode' => $order->get_shipping_postcode(),
                  'shipping_country' => $order->get_shipping_country(),
                ),
                'address' => $order->get_address(),
                'shipping_address_map_url' => $order->get_shipping_address_map_url(),
                'formatted_billing_full_name' => $order->get_formatted_billing_full_name(),
                'formatted_shipping_full_name' => $order->get_formatted_shipping_full_name(),
                'formatted_billing_address' => $order->get_formatted_billing_address(),
                'formatted_shipping_address' => $order->get_formatted_shipping_address(),

                // Get Order Payment Details
                'payment_method' => $order->get_payment_method(),
                'payment_method_title' => $order->get_payment_method_title(),
                'transaction_id' => $order->get_transaction_id(),

                // Get Order URLs
                'checkout_payment_url' => $order->get_checkout_payment_url(),
                'checkout_order_received_url' => $order->get_checkout_order_received_url(),
                'cancel_order_url' => $order->get_cancel_order_url(),
                'cancel_order_url_raw' => $order->get_cancel_order_url_raw(),
                'cancel_endpoint' => $order->get_cancel_endpoint(),
                'view_order_url' => $order->get_view_order_url(),
                'edit_order_url' => $order->get_edit_order_url(),

                // Get Order Status
                'status' => $order->get_status(),


            );

          }
        }
      } else {
        if ( is_admin() )
        {
          $show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
          $show_error->admin_dashboard_error_notice( __( 'Install Woocommerce Plugin' , 'simplbooks-integration' ) );
        }
      }




      if ( ! empty( $order_array ) ) {
        return $order_array;
        //print_r( $order_array );
      }




    }



    public function Get_Woocommerce_Order_Array_By_Id( $order_id )
    {


      if ( class_exists( 'WooCommerce' ) )
      {

        $order = wc_get_order( $order_id );
        //print_r( $order->get_total() );

        $plugin_options = get_option( 'simplbooks' );
        $api_token = $plugin_options['simplbooks_api_token'];
        $url_hash = $plugin_options['simplbooks_url_hash'];
        $items_in_list = $plugin_options['items_in_list'];
        //var_dump( $api_token );

        if ( ! empty( $api_token ) xor 'Insert here' == ! $api_token /*xor ! empty( $url_hash )*/ )
        {

          $order_array[] = array(
              'id' => $order->get_id(),

              // Get Order Totals $0.00
              'formatted_order_total' => $order->get_formatted_order_total(),
              'cart_tax' => $order->get_cart_tax(),
              'get_currency' => $order->get_currency(),
              'discount_tax' => $order->get_discount_tax(),
              'discount_to_display' => $order->get_discount_to_display(),
              'discount_total' => $order->get_discount_total(),
              'fees' => $order->get_fees(),
              //$order->get_formatted_line_subtotal();
              'shipping_tax' => $order->get_shipping_tax(),
              'shipping_total' => $order->get_shipping_total(),
              'subtotal' => $order->get_subtotal(),
              'subtotal_to_display' => $order->get_subtotal_to_display(),
              //'tax_location' => $order->get_tax_location(),
              'tax_totals' => $order->get_tax_totals(),
              'taxes' => $order->get_taxes(),
              'total' => $order->get_total(),
              'total_discount' => $order->get_total_discount(),
              'total_tax' => $order->get_total_tax(),
              'total_refunded' => $order->get_total_refunded(),
              'total_tax_refunded' => $order->get_total_tax_refunded(),
              'total_shipping_refunded' => $order->get_total_shipping_refunded(),
              'item_count_refunded' => $order->get_item_count_refunded(),
              'total_qty_refunded' => $order->get_total_qty_refunded(),
              'qty_refunded_for_item' => $order->get_qty_refunded_for_item( 'item' ),
              'total_refunded_for_item' => $order->get_total_refunded_for_item( 'item' ),
              //'tax_refunded_for_item' => $order->get_tax_refunded_for_item( 'item' ),
              'total_tax_refunded_by_rate_id' => $order->get_total_tax_refunded_by_rate_id( 'item' ),
              'remaining_refund_amount' => $order->get_remaining_refund_amount( 'item' ),

              // Get Order Items
              'items' => $order->get_items(),
              'meta_data' => $order->get_meta_data( 'value' ),
              //$order->get_items_key();
              'items_tax_classes' => $order->get_items_tax_classes(),
              //'item' => $order->get_item();
              'item_count' => $order->get_item_count(),
              'item_subtotal' => $order->get_item_subtotal( '' ),
              'item_tax' => $order->get_item_tax( '' ),
              'item_total' => $order->get_item_total( '' ),
              'downloadable_items' => $order->get_downloadable_items(),

              // Get Order Lines
              'line_subtotal' => $order->get_line_subtotal( 'view' ),
              'line_tax' => $order->get_line_tax( 'view' ),
              'line_total' => $order->get_line_total( 'view' ),

              // Get Order Shipping
              'shipping_method' => $order->get_shipping_method(),
              //'shipping_methods' => $order->get_shipping_methods(), // error protected data
              //'shipping_to_display' => $order->get_shipping_to_display(),

              // Get Order Dates
              'date_created' => $order->get_date_created(),
              'date_modified' => $order->get_date_modified(),
              'date_completed' => $order->get_date_completed(),
              'date_paid' => $order->get_date_paid(),

              // Get Order User, Billing & Shipping Addresses
              'customer_id' => $order->get_customer_id(),
              'user_id' => $order->get_user_id(),
              'user' => $user_data,
              'customer_ip_address' => $order->get_customer_ip_address(),
              'customer_user_agent' => $order->get_customer_user_agent(),
              'created_via' => $order->get_created_via(),
              'customer_note' => $order->get_customer_note(),
              //$order->get_address_prop();
              'billing_address' => array(
                'billing_first_name' => $order->get_billing_first_name(),
                'billing_last_name' => $order->get_billing_last_name(),
                'billing_company' => $order->get_billing_company(),
                'billing_address_1' => $order->get_billing_address_1(),
                'billing_address_2' => $order->get_billing_address_2(),
                'billing_city' => $order->get_billing_city(),
                'billing_state' => $order->get_billing_state(),
                'billing_postcode' => $order->get_billing_postcode(),
                'billing_country' => $order->get_billing_country(),
                'billing_email' => $order->get_billing_email(),
                'billing_phone' => $order->get_billing_phone(),
              ),
              'shipping_address' => array(
                'shipping_first_name' => $order->get_shipping_first_name(),
                'shipping_last_name' => $order->get_shipping_last_name(),
                'shipping_company' => $order->get_shipping_company(),
                'shipping_address_1' => $order->get_shipping_address_1(),
                'shipping_address_2' => $order->get_shipping_address_2(),
                'shipping_city' => $order->get_shipping_city(),
                'shipping_state' => $order->get_shipping_state(),
                'shipping_postcode' => $order->get_shipping_postcode(),
                'shipping_country' => $order->get_shipping_country(),
              ),
              'address' => $order->get_address(),
              'shipping_address_map_url' => $order->get_shipping_address_map_url(),
              'formatted_billing_full_name' => $order->get_formatted_billing_full_name(),
              'formatted_shipping_full_name' => $order->get_formatted_shipping_full_name(),
              'formatted_billing_address' => $order->get_formatted_billing_address(),
              'formatted_shipping_address' => $order->get_formatted_shipping_address(),

              // Get Order Payment Details
              'payment_method' => $order->get_payment_method(),
              'payment_method_title' => $order->get_payment_method_title(),
              'transaction_id' => $order->get_transaction_id(),

              // Get Order URLs
              'checkout_payment_url' => $order->get_checkout_payment_url(),
              'checkout_order_received_url' => $order->get_checkout_order_received_url(),
              'cancel_order_url' => $order->get_cancel_order_url(),
              'cancel_order_url_raw' => $order->get_cancel_order_url_raw(),
              'cancel_endpoint' => $order->get_cancel_endpoint(),
              'view_order_url' => $order->get_view_order_url(),
              'edit_order_url' => $order->get_edit_order_url(),

              // Get Order Status
              'status' => $order->get_status(),


          );
      }

      } else {
        if ( is_admin() )
        {
          $show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
          $show_error->admin_dashboard_error_notice( __( 'Install Woocommerce Plugin' , 'simplbooks-integration' ) );
        }  
      }



     // }


      if ( ! empty( $order_array ) )
      {
        return $order_array;
      }

    }



    static function Get_Woocommerce_Products_Array()
    {
      $get_post_type = get_post_types();
      //print_r( $get_post_type );

      if ( class_exists( 'WooCommerce' ) )
      {
        $customer_orders = get_posts( array(
            'numberposts' => -1,
            'post_type'   => 'product',
            'posts_per_page' => -1
            //'post_status' => array_keys( wc_get_order_statuses() ),
        ) );
        return $customer_orders;
        //print_r( $customer_orders );

     }

   }



}
