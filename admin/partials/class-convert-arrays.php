<?php
Class Convert_Simplebooks_Arrays
{

  static function convert_array( $variable )
  {
    //foreach ($variable as $key => $value) {
      if ( isset( $variable ) )
      {
        $plugin_options = get_option( 'simplbooks' );
        $language = $plugin_options['select_country'];

        $return_item_array = Get_Simplbooks_Woocommerce_Orders::Get_Woocommerce_Order_Array_By_Id( $variable['id'] );
        $Get_Order_Items = Woocommerce_Simplbooks_Filter_Arrays::Get_Order_Items( $return_item_array[0]['items'] );
        $Get_Order_Items['list']['Tasks'] = $Get_Order_Items;

        $payload_array = array(
          "Invoice" => array(
             "number" => $variable['id'],//rand(1, 100000),
             "currency_name" => "EUR",
             "currency_rate" => "1",
             "overdue_charge_percent" => "0.5",
             "created" => $date,
             "due" => $date,
             "sent" => "0000-00-00",
             "reference" => null, //$date_class->format('YmdHis'),//"201506262811",
             "client_id" => $variable['client_id'],
             "language" => $language,
             "additional_info" => "Täname õigeaegselt tasutud arve eest!",
             "proforma" => 0,
             "proforma_percent" => "0",
             "rounding" => "0",
             "row_sum_with_vat" => "250",
           ),


        );

        $merge = array_merge( $payload_array, $Get_Order_Items['list'] );
        return $merge;
        //print_r( $merge  );
      }
    //}


  }

}
