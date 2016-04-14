?php
/**
 * Get default text and accent colors for Customizer.
 *
 * Abstracted here since at least two functions use it.
 *
 * @since 1.0.0
 *
 * @return string Hex color code.
 */
function pwp_customizer_get_default_text_color() { return '#fefefe'; }
function pwp_customizer_get_default_accent_color() { return '#6f6697'; }
// add_action( 'customize_register', 'contextual_static_front_page_section', 11 );
// /**
//  * Contextual Static Front Page section in the customizer.
//  */
// function contextual_static_front_page_section( $wp_customize ) {
// 	$wp_customize->get_section( 'static_front_page' )->active_callback = 'is_front_page';
// }
// Added priority to load late for moving Genesis stuffs.
add_action( 'customize_register', 'pwp_customize_register', 999 );
/**
 * Register settings and controls with the Customizer.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function pwp_customize_register( $wp_customize ) {
	/**
	 * Customize Background Image Control Class
	 *
	 * @package WordPress
	 * @subpackage Customize
	 * @since 3.4.0
	 */
	class Child_Theme_Image_Control extends WP_Customize_Image_Control {
		/**
		 * Constructor.
		 *
		 * If $args['settings'] is not defined, use the $id as the setting ID.
		 *
		 * @since 3.4.0
		 * @uses WP_Customize_Upload_Control::__construct()
		 *
		 * @param WP_Customize_Manager $manager
		 * @param string $id
		 * @param array $args
		 */
		public function __construct( $manager, $id, $args ) {
			$this->statuses = array( '' => __( 'No Image', 'pwp' ) );
			parent::__construct( $manager, $id, $args );
			$this->add_tab( 'upload-new', __( 'Upload New', 'pwp' ), array( $this, 'tab_upload_new' ) );
			$this->add_tab( 'uploaded',   __( 'Uploaded', 'pwp' ),   array( $this, 'tab_uploaded' ) );
			if ( $this->setting->default )
				$this->add_tab( 'default',  __( 'Default', 'pwp' ),  array( $this, 'tab_default_background' ) );
			// Early priority to occur before $this->manager->prepare_controls();
			add_action( 'customize_controls_init', array( $this, 'prepare_control' ), 5 );
		}
		/**
		 * @since 3.4.0
		 * @uses WP_Customize_Image_Control::print_tab_image()
		 */
		public function tab_default_background() {
			$this->print_tab_image( $this->setting->default );
		}
	}
	// global $wp_customize;
	// ----------------------------------------
	// Custom Header Logo
	// ----------------------------------------
	// Add setting for logo uploader
	$wp_customize->add_setting( 'pwp_site_logo' );
	// Add control for logo uploader (actual uploader)
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'pwp_site_logo',
			array(
				'label'       => __( 'Site Logo', 'pwp' ),
				'description' => __( 'The Site Logo will replace the Site Title when an image is uploaded here.', 'pwp' ),
				'section'     => 'title_tagline',
				'settings'    => 'pwp_site_logo',
				)
			)
		);
	// --------------------------------------------------
	// GENERAL SETTINGS
	// --------------------------------------------------
	// Add General Settings Panel
	$wp_customize->add_panel( 'pwp_general_panel', array(
		'title'    => 'General Settings',
		'priority' => 10,
		) );
	// ----------------------------------------
	// CUSTOM THEME SETTINGS
	// ----------------------------------------
	// Add Theme Options Panel
	$wp_customize->add_panel( 'pwp_theme_panel', array(
		'title'    => 'Theme Options',
		'priority' => 20,
		) );
	// ----------------------------------------
	// General Theme Options
	// ----------------------------------------
	$wp_customize->add_section( 'pwp_general_options', array(
		'title'       => __( 'General Theme Options', 'pwp' ),
		'priority'    => 20,
		'panel'       => 'pwp_theme_panel',
		) );
	// Add setting for an image uploader
	$wp_customize->add_setting( 'pwp_page_before_content_image' );
	// Add control for the image uploader (actual uploader)
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'pwp_page_before_content_image',
			array(
				'label'       => __( 'Static Hero Image', 'pwp' ),
				'description' => __( 'The Static Hero Image appears directly below the header on all pages except the landing page.', 'pwp' ),
				'section'     => 'pwp_general_options',
				'settings'    => 'pwp_page_before_content_image',
				)
			)
		);
	// ----------------------------------------
	// Front Page Options
	// ----------------------------------------
	$admin_url = admin_url();
	// Add Front Page Panel
	$wp_customize->add_panel( 'pwp_fp_panel', array(
		'description' => __( '<p>This panel is used for managing many of the dynamic features that make up the homepage or Front Page. You can change backgrounds as well as update settings related to the Front Page Activity Feed.</p><p>Much of the Front Page content is managed by accessing the <strong><a href="' . $admin_url . 'customize.php?autofocus[panel]=widgets">widgets panel</a></strong> and you can update the Front Page Slideshow by editing the <strong><a href="' . $admin_url . 'post.php?post=1849&action=edit">FP Billboard Soliloquy Slider</a></strong>.</p>', 'pwp' ),
		'title'    => 'Front Page Options',
		'priority' => 30,
		) );
	$wp_customize->add_section( 'pwp_fp_backgrounds', array(
		'title'       => __( 'FP Background Images', 'pwp' ),
		'priority'    => 10,
		'panel'       => 'pwp_fp_panel',
		) );
	$backgrounds = apply_filters( 'pwp_images', array( '5' ) );
	foreach( $backgrounds as $background ) {
		$wp_customize->add_setting( 'fp_image_' . $background, array(
			'default'  => sprintf( '%s/images/bg-%s.jpg', get_stylesheet_directory_uri(), $background ),
			'type'     => 'option',
			) );
		$wp_customize->add_control( new Child_Theme_Image_Control( $wp_customize, 'fp_image_' . $background, array(
			'section'     => 'pwp_fp_backgrounds',
			'settings'    => 'fp_image_' . $background,
			'priority'    => $background+1,
			) ) );
	}
	$wp_customize->get_control( 'fp_image_5' )->label = __( 'FP Call to Action', 'pwp' );
	$wp_customize->get_control( 'fp_image_5' )->description = __( 'The default image is <strong>1600px x 1050px</strong>.', 'pwp' );
	// ----------------------------------------
	// FP Activity Feed: Social Media
	// ----------------------------------------
	$wp_customize->add_section( 'pwp_fp_social', array(
		'description' => __( 'These images link to your social media accounts from the Activity Feed on the Front Page. Use the default images here or customize your site by uploading your own image(s) and don\'t forget to update the links.<br /><br />The default images are <strong>320 pixels wide and 320 pixels tall</strong>.<br /><br /><em>* Uploading an Instagram Image overrides the Instagram Feed plugin on the homepage.</em>', 'pwp' ),
		'title'       => __( 'FP Activity Feed: Social Media', 'pwp' ),
		'priority'    => 40,
		'panel'       => 'pwp_fp_panel',
		) );
	$networks = apply_filters( 'pwp_networks', array(
		'facebook',
		'pinterest',
		'twitter',
		'instagram',
		)
	);
	foreach( $networks as $network ) {
		$wp_customize->add_setting( 'pwp_' . $network . '_link', array(
			'default'  => esc_url( 'http://' . $network . '.com/' ),
			'type'     => 'option',
			'sanitize_callback' => 'esc_url_raw'
			) );
		$wp_customize->add_control( 'pwp_' . $network . '_link', array(
			'label'    => __( ucfirst( $network ) . ' Link:'),
			'section'  => 'pwp_fp_social',
			'settings' => 'pwp_' . $network . '_link',
			'priority' => $network+1,
			) );
		$wp_customize->add_setting( 'pwp_' . $network . '_image', array(
			// 'default'  => sprintf( '%s/images/%s.png', get_stylesheet_directory_uri(), $network ),
			'type'     => 'option',
			) );
		$wp_customize->add_control( new Child_Theme_Image_Control( $wp_customize, 'pwp_' . $network . '_image', array(
			'label'    => sprintf( __( ucfirst( $network ) . ' Image:', 'pwp' ), $network ),
			'section'  => 'pwp_fp_social',
			'settings' => 'pwp_' . $network . '_image',
			'priority' => $network+1,
			) ) );
	}
	// ----------------------------------------
	// FP Activity Feed: Organizational Video
	// ----------------------------------------
	$wp_customize->add_section( 'pwp_fp_featured_video', array(
		'description' => __( 'Add an organizational video to the Activity Feed on your Front Page.<br /><br /><strong>Copy and paste the embed code from YouTube below.</strong>', 'pwp' ),
		'title'       => __( 'FP Activity Feed: Video', 'pwp' ),
		'priority'    => 60,
		'panel'       => 'pwp_fp_panel',
		) );
	$wp_customize->add_setting( 'pwp_fp_video', array(
		'type'              => 'option',
		'capability'        => 'manage_options',
		'default'           => '',
		// 'sanitize_callback' => 'esc_raw_url',
		) );
	$wp_customize->add_control( 'pwp_fp_video', array(
		'label'      => __( 'Featured Video' ),
		'section'    => 'pwp_fp_featured_video',
		) );
	// $wp_customize->add_setting( 'pwp_fp_video_poster' );
	// $wp_customize->add_control( 'pwp_fp_video_poster', array(
	// 	'label'      => __( 'Placeholder Image' ),
	// 	'section'    => 'pwp_fp_featured_video',
	// 	) );
	// Add control for logo uploader (actual uploader)
	// $wp_customize->add_control(
	// 	new WP_Customize_Image_Control(
	// 		$wp_customize,
	// 		'pwp_fp_video_poster',
	// 		array(
	// 			// 'description' => __( 'The Site Logo will replace the Site Title when an image is uploaded here.', 'pwp' ),
	// 			'label'    => __( 'Placeholder Image', 'pwp' ),
	// 			'section'  => 'pwp_fp_featured_video',
	// 			'settings' => 'pwp_fp_video_poster',
	// 			)
	// 		)
	// 	);
	// ----------------------------------------
	// FP Activity Feed: Buttons
	// ----------------------------------------
	$wp_customize->add_section( 'pwp_fp_feed_buttons', array(
		'description' => __( 'Add an organizational video to the Activity Feed on your Front Page.', 'pwp' ),
		'title'       => __( 'FP Activity Feed: Buttons', 'pwp' ),
		'priority'    => 60,
		'panel'       => 'pwp_fp_panel',
		) );
	// Button 1 Text
	$wp_customize->add_setting( 'pwp_fp_feed_btn_1_text', array(
		'default'    => '',
		'type'       => 'option',
		'capability' => 'manage_options',
		) );
	$wp_customize->add_control( 'pwp_fp_feed_btn_1_text', array(
		'label'      => __( 'CTA Button 1 Text' ),
		'section'    => 'pwp_fp_feed_buttons',
		) );
	// Button 1 Url
	$wp_customize->add_setting( 'pwp_fp_feed_btn_1_url', array(
		'default'    => '',
		'type'       => 'option',
		'capability' => 'manage_options',
		'sanitize_callback' => 'esc_url_raw',
		) );
	$wp_customize->add_control( 'pwp_fp_feed_btn_1_url', array(
		'label'      => __( 'CTA Button 1 Link' ),
		'section'    => 'pwp_fp_feed_buttons',
		) );
	// Button 2 Text
	$wp_customize->add_setting( 'pwp_fp_feed_btn_2_text', array(
		'default'    => '',
		'type'       => 'option',
		'capability' => 'manage_options',
		) );
	$wp_customize->add_control( 'pwp_fp_feed_btn_2_text', array(
		'label'      => __( 'CTA Button 2 Text' ),
		'section'    => 'pwp_fp_feed_buttons',
		) );
	// Button 2 Url
	$wp_customize->add_setting( 'pwp_fp_feed_btn_2_url', array(
		'default'    => '',
		'type'       => 'option',
		'capability' => 'manage_options',
		'sanitize_callback' => 'esc_url_raw',
		) );
	$wp_customize->add_control( 'pwp_fp_feed_btn_2_url', array(
		'label'      => __( 'CTA Button 2 Link' ),
		'section'    => 'pwp_fp_feed_buttons',
		) );
	// ----------------------------------------
	// PANEL ASSIGNMENT
	// ----------------------------------------
	// General Settings
	$wp_customize->get_section( 'title_tagline' )->panel = 'pwp_general_panel';
	$wp_customize->get_section( 'colors' )->panel = 'pwp_general_panel';
	$wp_customize->get_section( 'static_front_page' )->panel = 'pwp_general_panel';
	$wp_customize->get_section( 'genesis_layout' )->panel = 'pwp_general_panel';
	$wp_customize->get_section( 'genesis_breadcrumbs' )->panel = 'pwp_general_panel';
	$wp_customize->get_section( 'genesis_comments' )->panel = 'pwp_general_panel';
	$wp_customize->get_section( 'genesis_archives' )->panel = 'pwp_general_panel';
	// Child Theme Settings
	if ( current_theme_supports( 'genesis-style-selector' ) ) {
		$wp_customize->get_section( 'genesis_color_scheme' )->panel = 'pwp_general_panel';
	}
	$wp_customize->get_section( 'background_image' )->panel = 'pwp_theme_panel';
	$wp_customize->get_section( 'background_image' )->priority = 1000;
	$wp_customize->get_section( 'header_image' )->panel = 'pwp_theme_panel';
	$wp_customize->get_section( 'title_tagline' )->title = __( 'Website Identity' );
}
	// // --------------------------------------------------
	// // Custom Page Options
	// // --------------------------------------------------
	// // Add Front Page Options Panel
	// $wp_customize->add_panel( 'pwp_fp_panel', array(
	// 	'title'    => 'Custom Page Options',
	// 	'priority' => 115,
	// 	// 'active_callback' => 'is_front_page',
	// 	) );
	// ----------------------------------------
	// Custom Page Images
	// ----------------------------------------
// 	$wp_customize->add_section( 'pwp_page_images', array(
// 		'description' => __( 'Use the included default image(s) or customize your site by uploading your own image(s).', 'pwp' ),
// 		'title'       => __( 'Custom Page Images', 'pwp' ),
// 		'priority'    => 20,
// 		'panel'       => 'pwp_fp_panel',
// 		) );
// 	$backgrounds = apply_filters( 'pwp_images', array( '5' ) );
// 	foreach( $backgrounds as $background ) {
// 		$wp_customize->add_setting( $ba'pwp_' . ckground . '_image', array(
// 			'default'  => sprintf( '%s/images/bg-%s.jpg', get_stylesheet_directory_uri(), $background ),
// 			'type'     => 'option',
// 			) );
// 		$wp_customize->add_control( new Child_Theme_Image_Control( $wp_customize, $ba'pwp_' . ckground . '_image', array(
// 			'label'       => sprintf( __( 'FP Call to Action Background:', 'pwp' ), $background ),
// 			'description' => __( 'The default image is <strong>1600 pixels wide and 1050 pixels tall</strong>.', 'pwp' ),
// 			'section'     => 'pwp_page_images',
// 			'settings'    => $ba'pwp_' . ckground . '_image',
// 			'priority'    => $background+1,
// 			) ) );
// 	}
// 	// Add setting for an image uploader
// 	$wp_customize->add_setting( 'pwp_page_before_content_image' );
// 	// Add control for the image uploader (actual uploader)
// 	$wp_customize->add_control(
// 		new WP_Customize_Image_Control(
// 			$wp_customize,
// 			'pwp_page_before_content_image',
// 			array(
// 				'label'       => __( 'Static Hero Image', 'pwp' ),
// 				'description' => __( 'The Static Hero Image appears directly below the header on all pages except the landing page.', 'pwp' ),
// 				'section'     => 'pwp_page_images',
// 				'settings'    => 'pwp_page_before_content_image',
// 				)
// 			)
// 		);
// 	// ----------------------------------------
// 	// FP Activity Feed: Social Media
// 	// ----------------------------------------
// 	$wp_customize->add_section( 'pwp_fp_social', array(
// 		'description' => __( 'These images link to your social media accounts from the Activity Feed on the Front Page. Use the default images here or customize your site by uploading your own image(s) and don\'t forget to update the links.<br /><br />The default images are <strong>320 pixels wide and 320 pixels tall</strong>.<br /><br /><em>* Uploading an Instagram Image overrides the Instagram Feed plugin on the homepage.</em>', 'pwp' ),
// 		'title'       => __( 'FP Activity Feed: Social Media', 'pwp' ),
// 		'priority'    => 40,
// 		'panel'       => 'pwp_fp_panel',
// 		) );
// 	$networks = apply_filters( 'pwp_networks', array(
// 		'facebook',
// 		'pinterest',
// 		'twitter',
// 		'instagram',
// 		)
// 	);
// 	foreach( $networks as $network ) {
// 		$wp_customize->add_setting( 'pwp_' . $network . '_link', array(
// 			'default'  => esc_url( 'http://' . $network . '.com/' ),
// 			'type'     => 'option',
// 			'sanitize_callback' => 'esc_url_raw'
// 			) );
// 		$wp_customize->add_control( 'pwp_' . $network . '_link', array(
// 			'label'    => __( ucfirst( $network ) . ' Link:'),
// 			'section'  => 'pwp_fp_social',
// 			'settings' => 'pwp_' . $network . '_link',
// 			'priority' => $network+1,
// 			) );
// 		$wp_customize->add_setting( 'pwp_' . $network . '_image', array(
// 			// 'default'  => sprintf( '%s/images/%s.png', get_stylesheet_directory_uri(), $network ),
// 			'type'     => 'option',
// 			) );
// 		$wp_customize->add_control( new Child_Theme_Image_Control( $wp_customize, 'pwp_' . $network . '_image', array(
// 			'label'    => sprintf( __( ucfirst( $network ) . ' Image:', 'pwp' ), $network ),
// 			'section'  => 'pwp_fp_social',
// 			'settings' => 'pwp_' . $network . '_image',
// 			'priority' => $network+1,
// 			) ) );
// 	}
// 	// ----------------------------------------
// 	// FP Activity Feed: Organizational Video
// 	// ----------------------------------------
// 	$wp_customize->add_section( 'pwp_fp_featured_video', array(
// 		'description' => __( 'Add an organizational video to the Activity Feed on your Front Page.<br /><br /><strong>Copy and paste the embed code from YouTube below.</strong>', 'pwp' ),
// 		'title'       => __( 'FP Activity Feed: Video', 'pwp' ),
// 		'priority'    => 60,
// 		'panel'       => 'pwp_fp_panel',
// 		) );
// 	$wp_customize->add_setting( 'pwp_fp_video', array(
// 		'type'              => 'option',
// 		'capability'        => 'manage_options',
// 		'default'           => '',
// 		'sanitize_callback' => 'esc_raw_url',
// 		) );
// 	$wp_customize->add_control( 'pwp_fp_video', array(
// 		'label'      => __( 'Featured Video' ),
// 		'section'    => 'pwp_fp_featured_video',
// 		) );
// 	$wp_customize->add_setting( 'pwp_fp_video_poster', array(
// 		'type'       => 'option',
// 		'capability' => 'manage_options',
// 		'default'    => '',
// 		) );
// 	// $wp_customize->add_control( 'pwp_fp_video_poster', array(
// 	// 	'label'      => __( 'Placeholder Image' ),
// 	// 	'section'    => 'pwp_fp_featured_video',
// 	// 	) );
// 	// Add control for logo uploader (actual uploader)
// 	$wp_customize->add_control(
// 		new WP_Customize_Image_Control(
// 			$wp_customize,
// 			'pwp_fp_video_poster',
// 			array(
// 				// 'description' => __( 'The Site Logo will replace the Site Title when an image is uploaded here.', 'pwp' ),
// 				'label'    => __( 'Placeholder Image', 'pwp' ),
// 				'section'  => 'pwp_fp_featured_video',
// 				'settings' => 'pwp_fp_video_poster',
// 				)
// 			)
// 		);
// 	// ----------------------------------------
// 	// FP Activity Feed: Buttons
// 	// ----------------------------------------
// 	$wp_customize->add_section( 'pwp_fp_feed_buttons', array(
// 		'description' => __( 'Add an organizational video to the Activity Feed on your Front Page.', 'pwp' ),
// 		'title'       => __( 'FP Activity Feed: Buttons', 'pwp' ),
// 		'priority'    => 60,
// 		'panel'       => 'pwp_fp_panel',
// 		) );
// 	// Button 1 Text
// 	$wp_customize->add_setting( 'pwp_fp_feed_btn_1_text', array(
// 		'default'    => '',
// 		'type'       => 'option',
// 		'capability' => 'manage_options',
// 		) );
// 	$wp_customize->add_control( 'pwp_fp_feed_btn_1_text', array(
// 		'label'      => __( 'CTA Button 1 Text' ),
// 		'section'    => 'pwp_fp_feed_buttons',
// 		) );
// 	// Button 1 Url
// 	$wp_customize->add_setting( 'pwp_fp_feed_btn_1_url', array(
// 		'default'    => '',
// 		'type'       => 'option',
// 		'capability' => 'manage_options',
// 		'sanitize_callback' => 'esc_url_raw',
// 		) );
// 	$wp_customize->add_control( 'pwp_fp_feed_btn_1_url', array(
// 		'label'      => __( 'CTA Button 1 Link' ),
// 		'section'    => 'pwp_fp_feed_buttons',
// 		) );
// 	// Button 2 Text
// 	$wp_customize->add_setting( 'pwp_fp_feed_btn_2_text', array(
// 		'default'    => '',
// 		'type'       => 'option',
// 		'capability' => 'manage_options',
// 		) );
 	$wp_customize->add_control( 'pwp_fp_feed_btn_2_text', array(
 		'label'      => __( 'CTA Button 2 Text' ),
 		'section'    => 'pwp_fp_feed_buttons',
 		) );
 	// Button 2 Url
 	$wp_customize->add_setting( 'pwp_fp_feed_btn_2_url', array(
 		'default'    => '',
 		'type'       => 'option',
 		'capability' => 'manage_options',
 		'sanitize_callback' => 'esc_url_raw',
 		) );
 	$wp_customize->add_control( 'pwp_fp_feed_btn_2_url', array(
		'label'      => __( 'CTA Button 2 Link' ),
		'section'    => 'pwp_fp_feed_buttons',
		) );
 }