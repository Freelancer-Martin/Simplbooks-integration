<?php
Class Simplbooks_GET_Request
{
  private $URL_HASH;
  private $API_TOKEN;


  function __construct( $API_TOKEN, $URL_HASH )
  {
    $this->URL_HASH = $URL_HASH;
    $this->API_TOKEN = $API_TOKEN;
  }



  public function Simplbooks_GET_Clients_List()
  {

    $plugin_options = get_option( 'simplbooks' );
    $api_token = $plugin_options['simplbooks_api_token'];
    $url_hash = $plugin_options['simplbooks_url_hash'];
    $items_in_list = $plugin_options['items_in_list'];


    if ( ! empty( $url_hash )   )
    {

                $post = array(
            'per_page' => $items_in_list,
            'page' => 1

        );
        $ch = curl_init('https://app.simplbooks.com/'.$this->URL_HASH.'/api/clients/list');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Simplbooks-Token: '.$this->API_TOKEN.''));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post );

        // execute!
        $response = curl_exec($ch);

        // close the connection, release resources used
        curl_close($ch);


        if ( isset( $response ) and ! empty( $response ) )
        {
          $decode = json_decode( $response );

          return $decode->data;

        }

        if ( is_admin() )
        {
          if ( $decode->status >= 200 )
          {
            $show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
            $show_error->admin_dashboard_error_notice( __( $decode->errors , 'simplbooks-integration' ) );
          }
        }

    }

  }


   public function Simplbooks_GET_Article_List()
  {

    $plugin_options = get_option( 'simplbooks' );
    $api_token = $plugin_options['simplbooks_api_token'];
    $url_hash = $plugin_options['simplbooks_url_hash'];
    $items_in_list = $plugin_options['items_in_list'];


    if ( ! empty( $url_hash )  )
    {

        $post = array(
            'per_page' => $items_in_list,
            'page' => 1

        );
        $ch = curl_init('https://app.simplbooks.com/'.$this->URL_HASH.'/api/articles/list');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Simplbooks-Token: '.$this->API_TOKEN.''));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post );

        // execute!
        $response = curl_exec($ch);

        // close the connection, release resources used
        curl_close($ch);


        if ( isset( $response ) and ! empty( $response ) )
        {
          $decode = json_decode( $response );

          return $decode->data;

        }


        if ( is_admin() )
        {
          if ( $decode->status >= 200 )
          {
            $show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
            $show_error->admin_dashboard_error_notice( __( $decode->errors , 'simplbooks-integration' ) );
          }
        }

    }


  }



   public function Simplbooks_GET_Invoices_List()
  {

    $plugin_options = get_option( 'simplbooks' );
    $api_token = $plugin_options['simplbooks_api_token'];
    $url_hash = $plugin_options['simplbooks_url_hash'];
    $items_in_list = $plugin_options['items_in_list'];


    if ( ! empty( $url_hash )  )
    {
        $post = array(
            'per_page' => $items_in_list,
            'page' => 1

        );
        $ch = curl_init('https://app.simplbooks.com/'.$this->URL_HASH.'/api/invoices/list');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Simplbooks-Token: '.$this->API_TOKEN.''));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post );

        // execute!
        $response = curl_exec($ch);

        // close the connection, release resources used
        curl_close($ch);


        if ( isset( $response ) and ! empty( $response ) )
        {
          $decode = json_decode( $response );

          if ( isset( $decode->data ) )
          {
            return $decode->data;
          }


        }
        //var_dump( $decode->data );

        if ( is_admin() )
        {
          if ( $decode->status >= 200 )
          {
            $show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
            if (!empty( $show_error ) && isset( $decode->errors ) ) {
              $show_error->admin_dashboard_error_notice( __( $decode->errors , 'simplbooks-integration' ) );
            }

          }
        }  

   }



  }









}

//$a = new Simplbooks_GET_Request( '1fa7b2974c62533079c8a56a5b81bff0', '1c96c4d96f76ef884db72731e56228cb' );
//$s = $a->Simplbooks_GET_Clients_List();
//print_r( $s );
