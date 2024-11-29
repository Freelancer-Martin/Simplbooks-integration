<?php
Class Simplbooks_POST_Request
{
  private $URL_HASH;
  private $API_TOKEN;


  function __construct( $API_TOKEN, $URL_HASH )
  {
    $this->URL_HASH = $URL_HASH;
    $this->API_TOKEN = $API_TOKEN;
  }



  public function Simplbooks_POST_Clients_List( $payload_array )
  {

    $plugin_options = get_option( 'simplbooks' );
    $api_token = $plugin_options['simplbooks_api_token'];
    $url_hash = $plugin_options['simplbooks_url_hash'];


    if ( ! empty( $url_hash )  )
    {
        $request = wp_remote_post( 'https://app.simplbooks.com/'.$this->URL_HASH.'/api/clients/create' , array(

                  'method' => 'POST',
                  'headers' => array(

                      'X-Simplbooks-Token' => $this->API_TOKEN,
                      //'X-Output-Format' => 'Screen'
                  ),
                  'body' => $payload_array


              )  );

        $decode = json_decode( $request['body'] );

        $plugin_options = get_option( 'simplbooks' );
      	$error = $plugin_options['error_message'];
        if ( is_admin() )
        {
        if ( 'on' == $error )
         {
          if ( $decode->status > 200 )
          {
            $show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
            $show_error->admin_dashboard_error_notice( __( $decode->errors[0] , 'simplbooks-integration' ) );
          }
          else
          {
            $show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
            $show_error->admin_dashboard_success_notice( __( 'Client was added to Simplebooks server' , 'simplbooks-integration' ) );
          }
        }
       }


    }


  }


  public function Simplbooks_POST_Article_List( $payload_array )
  {

    $plugin_options = get_option( 'simplbooks' );
    $api_token = $plugin_options['simplbooks_api_token'];
    $url_hash = $plugin_options['simplbooks_url_hash'];


    if ( ! empty( $url_hash ) )
    {
        $request = wp_remote_post( 'https://app.simplbooks.com/'.$this->URL_HASH.'/api/articles/create' , array(

                  'method' => 'POST',
                  'headers' => array(

                      'X-Simplbooks-Token' => $this->API_TOKEN,
                      //'X-Output-Format' => 'Screen'
                  ),
                  'body' => $payload_array


              )  );
        $decode = json_decode( $request['body'] );
        //print_r( $decode->errors );

        $plugin_options = get_option( 'simplbooks' );
      	$error = $plugin_options['error_message'];
        if ( is_admin() )
        {
          if ( 'on' == $error ) {
            if ( $decode->status > 200 )
            {
              $show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
              $show_error->admin_dashboard_success_notice( __( 'Article was added to Simplebooks server' , 'simplbooks-integration' ) );
            }

          }
        }  


    }


  }



   public function Simplbooks_POST_Invoices_List( $payload_array )
  {

    $plugin_options = get_option( 'simplbooks' );
    $api_token = $plugin_options['simplbooks_api_token'];
    $url_hash = $plugin_options['simplbooks_url_hash'];
    $item_per_request = $plugin_options['items_per_request'];


    if ( ! empty( $url_hash ) )
    {

      for ($i=0; $i < $item_per_request; $i++)
      {

        $request = wp_remote_post( 'https://app.simplbooks.com/'.$this->URL_HASH.'/api/invoices/create' , array(

                  'method' => 'POST',
                  'headers' => array(

                      'X-Simplbooks-Token' => $this->API_TOKEN,
                      //'X-Output-Format' => 'Screen'

                  ),
                  'body' => $payload_array[$i]


              )  );


      }


      $decode = json_decode( $request['body'] );

      $plugin_options = get_option( 'simplbooks' );
      $error = $plugin_options['error_message'];
      if ( is_admin() )
      {
        if ( 'on' == $error )
        {
          if ( $decode->status > 200 )
          {
            $show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
            $show_error->admin_dashboard_error_notice( __( $decode->errors[0] , 'simplbooks-integration' ) );
          }
          else
          {
            $show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
            $show_error->admin_dashboard_success_notice( __( 'Invoice was added to Simplebooks server' , 'simplbooks-integration' ) );
          }
        }
      }


    }

  }


}
