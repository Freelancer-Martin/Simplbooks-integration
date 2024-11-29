<?php
Class Woocommerce_Simplbooks_Filter_Arrays
{

  private $URL_HASH;
  private $API_KEY;


  function __construct( $API_KEY, $URL_HASH )
  {

    $this->URL_HASH = $URL_HASH;
    $this->API_KEY = $API_KEY;
    $this->respond();
    add_action('init', array( $this, 'Filter_Client_Array' ), 10, 0);
    add_action('init', array( $this, 'Filter_Article_Array' ), 10, 0);
    add_action('init', array( $this, 'Filter_Invoice_Array' ), 10, 0);


  }


  public function respond()
  {

    $plugin_options = get_option( 'simplbooks' );
    $API_ID = $plugin_options['plugin_api_id'];

    $remote_get =  wp_remote_get( 'https://wp-liides.ee/wp-json/merit_activa_clients/public/api_id='.$API_ID.''  );
    $json_decode = json_decode( wp_remote_retrieve_body ( $remote_get ) );
        if( isset( $json_decode->data ) && ! empty( $json_decode->data )  )
        {
            if ( $json_decode->data->status > 200 ) {
              return $json_decode->data->status;
            }
            else
            {
                return $json_decode->return;
                
            }
        }
        else
        {
          return $json_decode->return;
        }

        if ( is_admin() )
        {
          if ($json_decode->data->status == false )
        	{
        		$show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
        		$show_error->admin_dashboard_error_notice( __( 'Plugin Key is not correct' , 'simplbooks-integration' ) );
        	}
        	if ( $json_decode->data->status == ! true )
        	{
        		$show_error = new Admin_Simplbooks_Dashboard_Error_Notice();
        		$show_error->admin_dashboard_error_notice( __( 'Plugin Token or Url hash is wrong' , 'simplbooks-integration' ) );
        	}
        }



  }


  public static function arrayDifference(array $array1, array $array2, array $keysToCompare = null) {
      $serialize = function (&$item, $idx, $keysToCompare) {
          if (is_array($item) && $keysToCompare) {
              $a = array();
              foreach ($keysToCompare as $k) {
                  if (array_key_exists($k, $item)) {
                      $a[$k] = $item[$k];
                  }
              }
              $item = $a;
          }
          $item = serialize($item);
      };
      $deserialize = function (&$item) {
          $item = unserialize($item);
      };
      array_walk($array1, $serialize, $keysToCompare);
      array_walk($array2, $serialize, $keysToCompare);
      // Items that are in the original array but not the new one
      $deletions = array_diff($array1, $array2);
      $insertions = array_diff($array2, $array1);
      array_walk($insertions, $deserialize);
      array_walk($deletions, $deserialize);
      return array('insertions' => $insertions, 'deletions' => $deletions);
  }



  static function Filter_Client_Array()
  {

    if ( $this->respond() == true  )
    {
      $Get_Orders = new Get_Simplbooks_Woocommerce_Orders();
      $display_Woo_arrays = $Get_Orders->Get_Woocommerce_Order_Array();

      $Get_Client_List = new Simplbooks_GET_Request( $this->API_KEY, $this->URL_HASH );
      $display_Simpl_arrays = $Get_Client_List->Simplbooks_GET_Clients_List();
    }


    if ( !empty( $display_Simpl_arrays ) )
    {
      foreach ( $display_Simpl_arrays as $List_key => $List_value )
      {

        $return_simpl_array[] = array(
          'billing_email' => $List_value->Client->e_mail,
          'billing_phone' => $List_value->Client->phone,
          'shipping_company' => $List_value->Client->name,
          'shipping_address' => $List_value->Client->address_street,
          'shipping_city' => $List_value->Client->address_city,
          'shipping_state' => $List_value->Client->address_county,
          'shipping_postcode' => $List_value->Client->address_postal_code,
          'shipping_country' => $List_value->Client->address_country,
          '_shipping_field_reg_nr' => $List_value->Client->reg_no,
          '_shipping_field_VAT_reg_nr' => $List_value->Client->vat_no

        );


      }
    }

    if ( ! empty( $display_Woo_arrays ) )
    {
      foreach ( $display_Woo_arrays as $order_key => $order_value ) {

      $return_Woo_array[] = array(

          'billing_email' => $order_value['billing_address']['billing_email'],
          'billing_phone' => $order_value['billing_address']['billing_phone'],
          'billing_company' => $order_value['billing_address']['billing_company'],
          'billing_address' => $order_value['billing_address']['billing_address_1'] . $order_value['billing_address']['billing_address_2'],
          'billing_city' => $order_value['billing_address']['billing_city'],
          'billing_state' => $order_value['billing_address']['billing_state'],
          'billing_postcode' => $order_value['billing_address']['billing_postcode'],
          'billing_country' => $order_value['billing_address']['billing_country'],
          '_shipping_field_reg_nr' => $order_value['meta_data'][0]->value,
          '_shipping_field_VAT_reg_nr' => $order_value['metadata'][1]->value,

        );

      }


    }

    if ( ! empty( $return_simpl_array ) && ! empty( $return_Woo_array ) )
    {
      $return_diff = $this->arrayDifference( $return_simpl_array, $return_Woo_array );
    }

    //print_r( $return_Woo_array );
    if ( ! empty( $return_diff['insertions'] ) /*&& ! empty( $return_diff['deletions'] )*/ )
    {

      //print_r( $return_diff['insertions'] );
      array_multisort( $return_diff['insertions'] );
      foreach ( $return_diff['insertions'] as $diff_key => $diff_value )
      {

        foreach ( $return_simpl_array as $simpl_array_key => $simpl_array_value )
        {
          $return_email[] = $simpl_array_value['billing_email'];
        }

        foreach ( $return_simpl_array as $simpl_array_key => $simpl_array_value )
        {
          $return_company[] = $simpl_array_value['shipping_company'];
        }

        //print_r( $return_company );
        if ( ! in_array( $diff_value['billing_company'], $return_company ) && ! in_array( $diff_value['billing_email'], $return_email ) )
        {

          $payload_array = array(
            'Client' => array(
              "name" => $diff_value['billing_company'],
              "reg_no" => $diff_value['_shipping_field_reg_nr'],
              "vat_no" => $diff_value['_shipping_field_VAT_reg_nr'],
              "address_street" => $diff_value['billing_address'],
              "address_city" => $diff_value['billing_city'],
              "address_postal_code" => $diff_value['billing_postcode'],
              "address_county" => $diff_value['billing_state'],
              "address_country" => $diff_value['billing_country'],
              "webpage" => null,
              "e_mail" => $diff_value['billing_email'],
              "phone" => $diff_value['billing_phone'],
              "fax" => $diff_value['billing_phone'],
              "account_no" => null,
              "bank" => null
            )
          );


          if ( $this->respond() == true  )
          {

            $post = new Simplbooks_POST_Request( $this->API_KEY, $this->URL_HASH );
            $post->Simplbooks_POST_Clients_List( $payload_array );

          }

        }
        elseif ( in_array( $diff_value['shipping_company'], $return_company ) /*&& ! in_array( $diff_value['billing_phone'], $return_phone )*/ )
        {
          return;

        }



      }
    }
    else
    {


    }


  }




  static function Filter_Article_Array()
  {

    $products = Get_Simplbooks_Woocommerce_Orders::Get_Woocommerce_Products_Array();

    if ( $this->respond() == true  )
    {
      $Get_Orders = new Get_Simplbooks_Woocommerce_Orders();
      $display_Woo_arrays = $Get_Orders->Get_Woocommerce_Order_Array();

      $Get_Client_List = new Simplbooks_GET_Request( $this->API_KEY, $this->URL_HASH );
      $display_Simpl_arrays = $Get_Client_List->Simplbooks_GET_Article_List();
    }


    if ( !empty( $display_Simpl_arrays ) )
    {
      foreach ( $display_Simpl_arrays as $List_key => $List_value )
      {

        $return_simpl_array[] = array(


              'code' => $List_value->Article->code,
              'name' => $List_value->Article->legacy_name,
              'contents' => $List_value->Article->contents,
              'unit' => $List_value->Article->unit,
              'amount' => $List_value->Article->amount,
              'price_per_unit' => $List_value->Article->price_per_unit,
              'sum_with_vat' => $List_value->Article->sum_with_vat,
              'markup_value' => $List_value->Article->markup_value,
              'markup_type' => $List_value->Article->markup_type,
              'is_inventory' => $List_value->Article->is_inventory,
              'active' => $List_value->Article->active


          );
      }
    }

    if ( ! empty( $display_Woo_arrays ) )
    {
      foreach ( $products as $order_key => $order_value ) {


            $for_product = wc_get_product( $order_value->ID );


            $return_Woo_array[] = array(

                'code' => $order_value->post_title,
                'name' => $order_value->post_title,
                'contents' => $order_value->post_content,
                'unit' => 'tk',
                'amount' => $for_product->get_stock_quantity(),
                'price_per_unit' => $for_product->get_price(),
                'sum_with_vat' => "0",
                'markup_value' => "0",
                'markup_type' => "percent",
                'is_inventory' => 1,
                'active' => 1

            );


      }


    }

    if ( ! empty( $return_Woo_array ) )
    {

      if( ! empty( $return_Woo_array ) )
      {
        $return_diff = $this->arrayDifference( (array)$return_Woo_array, (array)$return_simpl_array );

      }

      if ( ! empty( $return_simpl_array ) )
      {
          foreach ( $return_simpl_array as $simpl_array_key => $simpl_array_value ) {
            $id_code_array[] = $simpl_array_value['code'];
          }

          foreach ( $return_simpl_array as $simpl_array_key => $simpl_array_value ) {
            $id_name_array[] = $simpl_array_value['name'];
          }
      }


    }



    if ( ! empty( $return_diff['deletions'] ) ) {

      foreach ( $return_diff['deletions'] as $diff_key => $diff_value )
      {


        if ( ! in_array( str_replace( " ","-",$diff_value['code'] ) , $id_code_array ) && ! in_array( $diff_value['name'], $id_name_array ) )
        {
          $payload_array = array(
            'Article' => array(
              'code' => str_replace( " ","-",$diff_value['code'] ),
              'name' => $diff_value['name'],
              'contents' => $diff_value['contents'],
              'unit' => 'tk',
              'amount' => $diff_value['amount'],
              'price_per_unit' => $diff_value['price_per_unit'],
              'sum_with_vat' => $diff_value['sum_with_vat'],
              'markup_value' => '0.00',
              'markup_type' => 'percent',
              'is_inventory' => 1,
              'active' => 1

            )
          );

          if ( $this->respond() == true  )
          {
            $post = new Simplbooks_POST_Request( $this->API_KEY, $this->URL_HASH );
            $post->Simplbooks_POST_Article_List( $payload_array );

          }



        }
        else
        {

          if ( empty( $return_simpl_array ) )
          {
              $payload_array = array(
                'Article' => array(
                  'code' => str_replace( " ","-",$diff_value['code'] ),
                  'name' => $diff_value['name'],
                  'contents' => $diff_value['contents'],
                  'unit' => 'tk',
                  'amount' => $diff_value['amount'],
                  'price_per_unit' => $diff_value['price_per_unit'],
                  'sum_with_vat' => $diff_value['sum_with_vat'],
                  'markup_value' => '0.00',
                  'markup_type' => 'percent',
                  'is_inventory' => 1,
                  'active' => 1

                )
              );

              if ( $this->respond() == true  )
              {
                $post = new Simplbooks_POST_Request( $this->API_KEY, $this->URL_HASH );
                $post->Simplbooks_POST_Article_List( $payload_array );

              }
         }

        }


        }

    }
    else
    {

      if ( ! empty( $return_Woo_array ) )
      {

        return;
        var_dump( 'sa oled homo'  );

      }


  }

}



  static function Get_Order_Items( $orders )
  {

    if ( ! empty( $orders ) ) {
      foreach ( $orders as $order_key => $order_value )
      {

        $item_array[] = array(
          'Task' => array(
            'article_id' => null,//$order_value['product_id'],
            'warehouse_id' => "0",
            'code' => str_replace( " ","-",$order_value['order_id'] ),
            'name' => $order_value['name'],
            "unit" => "tk",
            "amount" => $order_value['quantity'],
            "price_per_unit" => $order_value['subtotal'],
            "vat" => null,
            "vat_type_id" => "0",
            "discount" => "0",
            "contents" => "Sado Maso"

          )


        );

      }
    }

    return $item_array;


  }


  static function Filter_Invoice_Array()
  {
    if ( $this->respond() == true  )
    {
      $plugin_options = get_option( 'simplbooks' );
      if( isset( $plugin_options['simplbooks_api_key'] ) && !empty( $plugin_options['simplbooks_api_key'] ) )
      {
        $API_KEY = $plugin_options['simplbooks_api_key'];
      }
      if( isset( $plugin_options['simplbooks_api_id'] ) && !empty( $plugin_options['simplbooks_api_id'] ) )
      {
        $API_ID = $plugin_options['simplbooks_api_id'];
      }

      $date_class = new DateTime();
      $date = $date_class->format('Y-m-d');

      $Get_Orders = new Get_Simplbooks_Woocommerce_Orders();
      $display_Woo_arrays = $Get_Orders->Get_Woocommerce_Order_Array();

      $Get_Client_List = new Simplbooks_GET_Request( $this->API_KEY, $this->URL_HASH );
      $display_Simpl_Client_arrays = $Get_Client_List->Simplbooks_GET_Clients_List();

      $Get_Invoices_List = new Simplbooks_GET_Request( $this->API_KEY, $this->URL_HASH );
      $display_Simpl_arrays = $Get_Invoices_List->Simplbooks_GET_Invoices_List();

    }

    if ( !empty( $display_Simpl_arrays ) )
    {
      foreach ( $display_Simpl_arrays as $List_key => $List_value )
      {

        $return_simpl_array[] = array(

          'id' => $List_value->invoices->number,
          'name' => $List_value->invoices->client_name,


        );

      }
    }

    if ( ! empty( $display_Woo_arrays )  )
    {
      foreach ( $display_Woo_arrays as $order_key => $order_value ) {

            $return_Woo_array[ ] = array(
              'id' => $order_value['id'],
              'name' => $order_value['billing_address']['billing_company'],

            );

      }


    }




    if ( ! empty( $display_Simpl_Client_arrays ) )
    {
      foreach ( $display_Simpl_Client_arrays as $Simpl_Client_value ) {

         $client_list_array[] = array(
                'client_id' => $Simpl_Client_value->Client->id,
                'name' => $Simpl_Client_value->Client->name
              );
      }
    }



    if ( ! empty( $return_simpl_array ) )
    {
      foreach ( $return_simpl_array as $client_list_key => $client_list_value )
      {
        $client_name_array[] = $client_list_value['name'];
      }
    }

    if ( ! empty( $return_simpl_array ) )
    {
      foreach ( $return_simpl_array as $client_id_key => $client_id_value )
      {
        $client_id_array[] = $client_id_value['id'];
      }
    }


    if ( ! empty( $return_Woo_array ) )
    {
      foreach ( $return_Woo_array as $client_id_key => $client_id_value )
      {
        $Woo_id_array[] = $client_id_value['id'];
      }
    }



    if ( ! empty( $return_Woo_array ) )
    {
      foreach ( $return_Woo_array as $Woo_key => $Woo_value ) {
        $Woo_array[] = $Woo_value['name'];
      }
    }


    //includes client id for woo array
    if ( ! empty( $return_Woo_array ) )
    {

      $reverse_Woo_array = array_reverse( $return_Woo_array );




        for ( $y = 0; $y <= (  count( $return_Woo_array ) -1 ); $y++)
        {


          foreach ( $client_list_array as $client_list_key => $client_list_value ) {
            if ( in_array(  $reverse_Woo_array[$y]['name'], $client_list_value ) )
            {

              $client_array[] = array( 'id' => $reverse_Woo_array[$y]['id'], 'name' => $client_list_value['name'], 'client_id' => $client_list_value['client_id'] );
            }
          }
          $new_client_array[] = $client_array[$y];



      }

    }


    $finalArray = [];
    if ( ! empty ( $new_client_array ) )
    {
      foreach ( $new_client_array as $item )
      {
        if (!in_array($item, array_column($finalArray, 'name'))) {

            $finalArray[] = ['name' => $item];

          }
      }
    }



    if ( $this->respond() == true  )
    {
      if ( ! empty( $finalArray ) )
      {
        foreach ( $finalArray as $k => $v )
        {

          if ( ! empty( $client_id_array ) )
          {

            if ( ! in_array( $v['name']['id'], $client_id_array ) )
            {
              $s = Convert_Simplebooks_Arrays::convert_array( $v['name'] );
              $post = new Simplbooks_POST_Request( $this->API_KEY, $this->URL_HASH );
              $push[] = $s;
            }


          }
          elseif ( empty( $client_name_array ) )
          {

            if ( $this->respond() == true  )
            {

                $s = Convert_Simplebooks_Arrays::convert_array( $v['name'] );
                $push[] = $s;

            }
          }
        }
      }
    }


    if ( $this->respond() == true  )
    {
      if ( ! empty( $finalArray ) )
      {

          if ( ! empty( $client_id_array ) )
          {

            if ( ! in_array( $v['name']['id'], $client_id_array ) )
            {
              $post = new Simplbooks_POST_Request( $this->API_KEY, $this->URL_HASH );
              $post->Simplbooks_POST_Invoices_List( $push );
            }


          }
          elseif ( empty( $client_name_array ) )
          {

            $post = new Simplbooks_POST_Request( $this->API_KEY, $this->URL_HASH );
            $post->Simplbooks_POST_Invoices_List( $push );

          }

      }

    }







  }






}
$plugin_options = get_option( 'simplbooks' );
$api_token = $plugin_options['simplbooks_api_token'];
$url_hash = $plugin_options['simplbooks_url_hash'];

if ( ! empty( $url_hash ) && ! empty( $api_token ) )
{
  $class = new Woocommerce_Simplbooks_Filter_Arrays( $api_token, $url_hash );
}
else
{
  add_action( 'admin_notices', 'admin_dashboard_success_notice' );
  function admin_dashboard_success_notice()
  {
      print '<div class="notice notice-error"><p>Plugin Token or Url hash is wrong or missing</p></div>';
  }
}
