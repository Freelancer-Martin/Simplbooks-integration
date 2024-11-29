<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Simplbooks_Integration_Admin_Options {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );
	}
    public function get_all_emails() {
        $all_users = get_users();
        $user_email_list = array();
        foreach ($all_users as $user) {
            $user_email_list[esc_html($user->user_email)] = esc_html($user->display_name);
        }
        return $user_email_list;
    }
    public function create_menu() {
        /**
         * Create a submenu page under Plugins.
         * Framework also add "Settings" to your plugin in plugins list.
         * @link https://github.com/JoeSz/Exopite-Simple-Options-Framework
         */
         /**
  * Create a submenu page under Plugins.
  * Framework also add "Settings" to your plugin in plugins list.
  * @link https://github.com/JoeSz/Exopite-Simple-Options-Framework
  */
 $config_submenu = array(
     'type'              => 'menu',                          // Required, menu or metabox
     'id'                => $this->plugin_name,              // Required, meta box id,
                                                             // unique per page, to save:
                                                             // get_option( id )
     'parent'            => 'wp-admin',                   // Required, sub page to your options page
     'submenu'           => false,                            // Required for submenu
     'title'             => 'Simplbooks Integration',               //The name of this page
     'capability'        => 'manage_options',                // The capability needed to view the page
     'plugin_basename'   => 'simplbooks-integration',
		 'icon'							 => 'dashicons-welcome-write-blog',
     // 'tabbed'            => false,                        // is tabbed or not
                                                             // Note: if only one section then
                                                             // Tabs are disabled.
      'multilang'         => false                         // Disable mutilang support, default: true

 );

 /*
  * To add a metabox.
  * This normally go to your functions.php or another hook
  */
 $config_metabox = array(
     /*
      * METABOX
      */
     'type'              => 'metabox',
     'id'                => $this->plugin_name . '-meta',
     'post_types'        => array( 'simplbooks-integration' ),    // Post types to display meta box
     'context'           => 'advanced',
     'priority'          => 'default',
     'title'             => 'Demo Metabox',
     'capability'        => 'edit_posts',              // The capability needed to view the page
     // 'tabbed'            => false,                  // Add tabs or not, default true
     // 'simple'            => true,                   // Save post meta as simple insted of an array, default false
     // 'multilang'         => true,                   // Multilang support, required for ONLY qTranslate-X and WP Multilang
                                                       // for WPML and Polilang leave it in default.
                                                       // default: false
 );

 $fields[] = array(
    'name'   => 'first_simplbooks',
    'title'  => 'Options',
    'icon'   => 'dashicons-welcome-write-blog',
    'fields' => array(

				array(
             'id'             => 'error_message',
             'type'           => 'select',
             'title'          => 'ERROR MESSAGE',
						 'description'    => 'Show plugin error mesages',
             'options'        => array(
                 'on'      => 'On',
                 'off'      => 'Off',

             ),
             'default_option' => 'Select Type',
             'default'     => 'off',
         ),
/*
				 array(
							 'id'             => 'select_order_status',
							 'type'           => 'select',
							 'title'          => 'Order Status',
							'description'    => 'Select with state WC order have to be to send them to Simplbooks Server',
							 'options'        => array(
									 'completed'    => 'Completed',
									 'pending'      => 'Pending',
									 'processing'   => 'Processing',
									 'onhold'       => 'Onhold',
									 'cancelled'    => 'Cancelled',
									 'refunded'     => 'Refunded',
									 'failed'       => 'Failed',
									 //'other'        => 'Other',
							 ),
							 'default_option' => 'Select status',
							 'default'     => 'completed',
					 ),
*/
				 array(
							 'id'             => 'select_country',
							 'type'           => 'select',
							 'title'          => 'COUNTRY',
							 'description'    => 'Select which country you are going to use with Simplbooks Server',
							 'options'        => array(
									 'et_EE'    => 'Estonia',
									 'en_GB'     => 'English',
									 'ru_RU'     => 'Russian',

							 ),
							 'default_option' => 'Select country',
							 'default'     => 'et_EE',
					 ),
				 array(
						 'id'          => 'simplbooks_url_hash',
						 'type'        => 'text',
						 'title'       => 'SIMPLBOOKS URL HASH',
						 'before'      => __( 'Go Simplebook Site -> Settings ->  API Settings', 'Simplbooks_integration' ),
						 'after'       => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxx',
						 'class'       => 'text-class',
						 'attributes'  => 'data-test="test"',
						 'description' => __( 'Insert URL hash from simplbook url and copy and paste it here', 'Simplbooks_integration' ),
						 'default'     => 'Insert Here',
						 'attributes'    => array(
								'rows'        => 10,
								'cols'        => 5,
								'placeholder' => 'Insert Plug api id',
						 ),
						 'help'        => 'Help text',
				 ),
				 array(
						 'id'          => 'simplbooks_api_token',
						 'type'        => 'text',
						 'title'       => 'SIMPLBOOKS API TOKEN',
						 'before'      => __( 'Go Simplebook Site -> address bar ->  Copy and Paste from url bar  Example:1c96c4d96f76ef884db72731e56228vc', 'Simplbooks_integration' ),
						 'after'       => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
						 'class'       => 'text-class',
						 'attributes'  => 'data-test="test"',
						 'description' => __( 'On simmplbook site go settings->user and create API token and  copy and paste it here', 'Simplbooks_integration' ),
						 'default'     => 'Insert here',
						 'attributes'    => array(
								'rows'        => 10,
								'cols'        => 5,
								'placeholder' => 'Insert plugin api key',
						 ),
						 'help'        => 'Help text',
				 ),
				 array(
						 'id'          => 'plugin_api_id',
						 'type'        => 'text',
						 'title'       => 'PLUGIN API',
						 'before'      => __( 'Insert plugin API key, what you received via email', 'Simplbooks_integration' ),
						 'after'       => 'xxxxxx-xxxxxx-xxxxxx-xxxxxx-xxxxxx',
						 'class'       => 'text-class',
						 'attributes'  => 'data-test="test"',
						 'description' => __( 'Copy and paste your Plugin API key ', 'Simplbooks_integration' ),
						 'default'     => 'Insert here',
						 'attributes'    => array(
								'rows'        => 10,
								'cols'        => 5,
								'placeholder' => 'Insert plugin api id',
						 ),
						 'help'        => 'Help text',
				 ),
         array(
             'id'          => 'items_in_list',
             'type'        => 'range',
             'title'       => 'ITEM AMOUNT FROM SIMPLBOOKS SERVER',
             'before'      => __( 'Insert plugin API key, what you received via email', 'Simplbooks_integration' ),
             //'after'       => 'xxxxxx-xxxxxx-xxxxxx-xxxxxx-xxxxxx',
             'class'       => 'text-class',
             'attributes'  => 'data-test="test"',
             'description' => __( 'How many Article, Client and Invoice items with GET request to simplebook server', 'Simplbooks_integration' ),
             'default'     => '400',
             'min'     => '2',
             'max'     => '1000',
             'help'        => 'Help text',
         ),
				 array(
             'id'          => 'items_per_request',
             'type'        => 'range',
             'title'       => 'ITEMS SENT TO SERVER PER REQUEST',
             'before'      => __( 'Choose item amount what is sent to simplbooks server per request, best choise would be 10 ', 'Simplbooks_integration' ),
             //'after'       => 'xxxxxx-xxxxxx-xxxxxx-xxxxxx-xxxxxx',
             'class'       => 'text-class',
             'attributes'  => 'data-test="test"',
             'description' => __( 'Choose item amount what is sent to simplbooks server per request, best choise would be 10', 'Simplbooks_integration' ),
             'default'     => '10',
             'min'     => '1',
             'max'     => '60',
             'help'        => 'Help text',
         ),
/*
        array(
            'id'            => 'video_1',
            'type'          => 'video',
            'title'         => 'HOW TO USE THE PLUGIN ?',
            // 'default'       => '/wp-content/uploads/2018/01/video.mp4',
            // - OR for oEmbed: -
            'default'       => 'https://www.youtube.com/watch?v=KujZ__rrs0k',
            'info'          => 'oEmbed',
            'attributes'    => array(
                'placeholder'   => 'oEmbed',
            ),
            'options'       => array(
                'input'         => false,
                'oembed'        => true,
            ),
        ),
*/
				array(
						'id'     => 'editor_1',
						'type'   => 'editor',
						'title'  => ' ',

				),




    )
  );



 $options_panel = new Exopite_Simple_Options_Framework( $config_submenu, $fields );
 //$metabox_panel = new Exopite_Simple_Options_Framework( $config_metabox, $fields );
    }
    // Modify columns in customers list in admin area
    public function admin_list_edit_columns( $columns ) {
        // Remove unnecessary columns
        // unset(
        //     $columns['author'],
        //     $columns['comments']
        // );
        // Rename title and add ID and Address
        $columns['text_1'] = __( 'Text', 'plugin-name' );
        $columns['color_2'] = __( 'Color', 'plugin-name' );
        $columns['date_2'] = __( 'Date', 'plugin-name' );
        /*
         * Rearrange column order
         *
         * Now define a new order. you need to look up the column
         * names in the HTML of the admin interface HTML of the table header.
         *
         *     "cb" is the "select all" checkbox.
         *     "title" is the title column.
         *     "date" is the date column.
         *     "icl_translations" comes from a plugin (in this case, WPML).
         *
         * change the order of the names to change the order of the columns.
         *
         * @link http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
         */
        $customOrder = array('cb', 'title', 'text_1', 'color_2', 'date_2', 'author', 'comments', 'icl_translations', 'date');
        /*
         * return a new column array to wordpress.
         * order is the exactly like you set in $customOrder.
         */
        foreach ($customOrder as $column_name)
            $rearranged[$column_name] = $columns[$column_name];
        return $rearranged;
    }
    // Populate new columns in customers list in admin area
    public function admin_list_custom_columns( $column ) {
        /*
        'user_login' => 'js@markatus.de',
        'arbeitszeiterfassung' => 'no',
        'soll_stunden' => '',
        'anstellung' => 'vollzeit',
        'festlegung_zeitraum' => 'abreitstage_pro_woche',
        'verwaltung_jahresurlaub' => 'ja',
        'code_fuer_erfassungsbestaetigung' => '',
        'team' => 'markatus',
         */
        global $post;
        $custom = get_post_custom();
        $meta = maybe_unserialize( $custom[$this->plugin_name . '-meta'][0] );
        // Populate column form meta
        switch ($column) {
            case "text_1":
                // echo var_export( $meta, true );
                echo $meta["text_1"];
                break;
            case "color_2":
                echo $meta["color_2"];
                break;
            case "date_2":
                echo $meta["date_2"];
                break;
        }
    }
}

$r = new Simplbooks_Integration_Admin_Options( 'simplbooks', '');
$r->create_menu();
