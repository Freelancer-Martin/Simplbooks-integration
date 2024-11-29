<?php
Class Admin_Simplbooks_Dashboard_Error_Notice
{


    public function __construct() {

        add_action( 'admin_notices', array( $this,  'admin_dashboard_error_notice' ) );
        add_action( 'admin_notices', array( $this,  'admin_dashboard_success_notice' ) );

    }


    public function dashboard_orders_columns() {

        add_filter( 'manage_edit-shop_order_columns', array( $this, 'shop_order_columns' ) );
        add_action( 'manage_shop_order_posts_custom_column', array( $this, 'shop_order_posts_custom_column' ) );

    }


    public function admin_dashboard_error_notice( $error_message )
    {
        $class = 'notice notice-error';
        if ( ! empty( $error_message ) && isset( $error_message ) ) {
          $txt = sprintf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $error_message ) );
          print $txt;
        }

    }


    public function admin_dashboard_success_notice( $error_message )
    {
        $class = 'notice notice-success';
        if ( ! empty( $error_message ) && isset( $error_message )  ) {
          $txt = sprintf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $error_message ) );
          print $txt;
        }

    }


    function shop_order_columns( $columns )
    {
        $new_columns = (is_array($columns)) ? $columns : array();

        $new_columns['phone'] = 'Simplbooks Order Status';

        return $new_columns;
    }


    function shop_order_posts_custom_column( $column )
    {
        global $post, $the_order;

        if ( empty( $the_order ) || $the_order->get_id() != $post->ID ) {
            $the_order = wc_get_order( $post->ID );
        }


        $billing_address = $the_order->get_address();
        $status = $the_order->get_status();
        if ( $status == 'completed') {
          if ( $column == 'phone' ) {
              print_r ( isset( $billing_address['phone'] ) ? 'Sended' : '');
          }
        } else {
          if ( $column == 'phone' ) {
              print_r ( isset( $billing_address['phone'] ) ? 'NOT Sended' : '');
          }
        }

    }



}
