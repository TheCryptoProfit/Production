<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/admin
 */
 
class Steemit_Feed_Admin {
	
	/**
	 * The plugin options.
	 *
	 * @since		1.1.0
	 * @access 		private
	 * @var 		string 			$options    The plugin options.
	 */
	private $options;
	
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.0
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		$this->set_options();

	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/steemit-feed-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/steemit-feed-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Add settings/help page links to plugin menu.
	 *
	 * @since 		1.1.0
	 */
	public function add_menu() {
		
		/*add_submenu_page(
			'edit.php?post_type=steemitfeed',
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Steemit Feed - Settings', 'steemit-feed' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Settings', 'steemit-feed' ) ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'page_options' )
		);*/
		
		/*add_submenu_page(
			'edit.php?post_type=steemitfeed',
			apply_filters( $this->plugin_name . '-about-page-title', esc_html__( 'Steemit Feed - About', 'steemit-feed' ) ),
			apply_filters( $this->plugin_name . '-about-menu-title', esc_html__( 'About', 'steemit-feed' ) ),
			'manage_options',
			$this->plugin_name . '-about',
			array( $this, 'page_about' )
		);*/

	}
	
	/**
	 * Creates the about page
	 *
	 * @since 		1.1.0
	 * @return 		void
	 */
	public function page_about() {

		include( plugin_dir_path( __FILE__ ) . 'partials/steemit-feed-admin-page-about.php' );

	}
	
	/**
	 * Creates the options page
	 *
	 * @since 		1.1.0
	 * @return 		void
	 */
	public function page_options() {

		include( plugin_dir_path( __FILE__ ) . 'partials/steemit-feed-admin-page-settings.php' );

	}
	
	/**
	 * Creates a settings section
	 *
	 * @since 		1.1.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function section_update( $params ) {

		include( plugin_dir_path( __FILE__ ) . 'partials/steemit-feed-admin-section-update.php' );

	}
	
	/**
	 * Sets the class variable $options
	 *
	 * @since 		1.1.0
	 */
	private function set_options() {

		$this->options = get_option( $this->plugin_name . '-options' );

	}
	
	/**
	 * Returns an array of options names, fields types, and default values
	 *
	 * @since 		1.1.0
	 * @return 		array 			An array of options
	 */
	public static function get_options_list() {

		$options = array();

		$options[] = array( 'download-id', 'text', '' );

		return $options;

	}
	
	/**
	 * Creates a text field
	 *
	 * @since 		1.1.0
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_text( $args ) {

		$defaults['class'] 			= 'text widefat';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );

	}
	
	/**
	 * Registers settings fields with WordPress
	 *
	 * @since 		1.1.0
	 */
	public function register_fields() {

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

		add_settings_field(
			'download-id',
			apply_filters( $this->plugin_name . 'label-download-id', esc_html__( 'Download ID', 'steemit-feed' ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-update',
			array(
				'description' 	=> 'You can find your Download ID at the Minitek Dashboard.',
				'id' 			=> 'download-id',
				'value' 		=> '',
				'desc_link'		=> 'https://www.minitek.gr/dashboard'
			)
		);

	}
	
	/**
	 * Registers plugin settings
	 *
	 * @since 		1.1.0
	 * @return 		void
	 */
	public function register_settings() {

		// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-options',
			array( $this, 'validate_options' )
		);

	}
	
	/**
	 * Registers settings sections with WordPress
	 *
	 * @since 		1.1.0
	 */
	public function register_sections() {

		// add_settings_section( $id, $title, $callback, $menu_slug );

		add_settings_section(
			$this->plugin_name . '-update',
			apply_filters( $this->plugin_name . 'section-title-update', esc_html__( 'Update', 'steemit-feed' ) ),
			array( $this, 'section_update' ),
			$this->plugin_name
		);
		
	}
	
	/**
	 * Sanitizes plugin options
	 *
	 * @since 		1.1.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	private function sanitizer( $type, $data ) {

		if ( empty( $type ) ) { return; }
		if ( empty( $data ) ) { return; }

		$return 	= '';
		$sanitizer 	= new Steemit_Feed_Sanitize();

		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );

		$return = $sanitizer->clean();

		unset( $sanitizer );

		return $return;

	}
	
	/**
	 * Validates saved options
	 *
	 * @since 		1.1.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function validate_options( $input ) {

		//wp_die( print_r( $input ) );

		$valid 		= array();
		$options 	= $this->get_options_list();

		foreach ( $options as $option ) {

			$name = $option[0];
			$type = $option[1];

			$valid[$option[0]] = $this->sanitizer( $type, $input[$name] );

		}

		return $valid;

	}
	
	/**
	 * Create a new custom post type (steemitfeed) - Master.
	 *
	 * @since 	1.1.0
	 */
	public static function steemitfeed_register_post_type() {

		$cap_type 		= 'post';
		$global_name 	= 'Steemit Feed';
		$plural 		= 'Feeds';
		$single 		= 'Feed';
		$feed_name 		= 'steemitfeed';

		$opts['can_export']								= FALSE;
		$opts['capability_type']						= $cap_type;
		$opts['description']							= '';
		$opts['exclude_from_search']					= TRUE;
		$opts['has_archive']							= FALSE;
		$opts['hierarchical']							= FALSE;
		$opts['map_meta_cap']							= TRUE;
		$opts['menu_icon']								= 'dashicons-admin-generic';
		$opts['menu_position']							= 25;
		$opts['public']									= FALSE;
		$opts['publicly_querable']						= FALSE;
		$opts['query_var']								= FALSE;
		$opts['register_meta_box_cb']					= '';
		$opts['rewrite']								= FALSE;
		$opts['show_in_admin_bar']						= TRUE;
		$opts['show_in_menu']							= TRUE;
		$opts['show_in_nav_menu']						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['supports']								= array( 'title' );
		$opts['taxonomies']								= array();

		//$opts['capabilities']['create_posts']			= "do_not_allow";
		$opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']			= "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']				= "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$opts['capabilities']['read_post']				= "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";

		$opts['labels']['add_new']						= esc_html__( "Add New {$single}", 'steemit-feed' );
		$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'steemit-feed' );
		$opts['labels']['all_items']					= esc_html__( $plural, 'steemit-feed' );
		$opts['labels']['edit_item']					= esc_html__( "Edit {$single}" , 'steemit-feed' );
		$opts['labels']['menu_name']					= esc_html__( $global_name, 'steemit-feed' );
		$opts['labels']['name']							= esc_html__( $plural, 'steemit-feed' );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, 'steemit-feed' );
		$opts['labels']['new_item']						= esc_html__( "New {$single}", 'steemit-feed' );
		$opts['labels']['not_found']					= esc_html__( "No {$plural} Found", 'steemit-feed' );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", 'steemit-feed' );
		$opts['labels']['parent_item_colon']			= esc_html__( "Parent {$plural} :", 'steemit-feed' );
		$opts['labels']['search_items']					= esc_html__( "Search {$plural}", 'steemit-feed' );
		$opts['labels']['singular_name']				= esc_html__( $single, 'steemit-feed' );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", 'steemit-feed' );

		$opts['rewrite']['ep_mask']						= EP_PERMALINK;
		$opts['rewrite']['feeds']						= FALSE;
		$opts['rewrite']['pages']						= TRUE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $plural ), 'steemit-feed' );
		$opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'steemit-feed-steemitfeed-options', $opts );

		register_post_type( strtolower( $feed_name ), $opts );

	}
	
	/**
	 * Add a shortcode column in the feed list.
	 *
	 * @since 		1.1.0
	 */
	public function steemitfeed_add_shortcode_column( $columns ) {
	
		return array_merge( $columns, 
        array( 'shortcode' => __( 'Shortcode', 'steemit-feed' ) ) );
	
	}
	
	/**
	 * Display the shortcode column in the feed list.
	 *
	 * @since 		1.1.0
	 */
	public function steemitfeed_posts_shortcode_display( $column, $post_id ) {
		
		if ($column == 'shortcode') {
			?>
			<input type="text" onClick="this.select();" value="[steemitfeed <?php echo 'id=&quot;'.$post_id.'&quot;';?>]" />
			<?php				
		}
		
	}	
}
