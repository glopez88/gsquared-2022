<?php 

namespace GSquare; 

use GSquare\Shortcodes\Donation;

class Theme 
{
    /**
     * the name of the custom post type
     *
     * @var string
     */
    const POST_TYPE_DONATION = 'donation';
    
    /**
     * the domain use for internationalization
     *
     * @var string
     */
    const TEXT_DOMAIN = 'gsquared2022';
    
    /*
     * Contains the hooks to setup a theme
     */
    public static function setup()
    {
        require __DIR__ . '/shortcodes/donation.php';
        
        add_action('after_setup_theme', array(__CLASS__, 'afterSetup'));
        add_action('init', array(__CLASS__, 'registerPostType'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'loadScripts'));
        add_action('init', array(Donation::class, 'register'));
        
        add_filter('content_before_two_columns', array(__CLASS__, 'applyFilterOnCustomContent'));
    }
    
    /**
     * Performs theme related registration and setup here 
     */
    public static function afterSetup()
    {
        /*
		 * Post Thumbnails support for posts and pages
         */
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(681, 908);
        
        add_shortcode('donations', array(__CLASS__, 'createDonationsShortCode'));
    }
    
    /**
     * Load custom script and styles here
     */
    public static function loadScripts()
    {
        wp_enqueue_style('gsquared2022-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get('Version'));
    }
    
    /**
     * Registers the "donation" custom post type
     */
    public static function registerPostType()
    {
        register_post_type(self::POST_TYPE_DONATION, array(
            'labels' => array(
                'name'              => __('Donations', self::TEXT_DOMAIN),
                'singular_name'     => __('Donation', self::TEXT_DOMAIN),
                'menu_name'         => __('Donations', self::TEXT_DOMAIN),
                'name_admin_bar'    => __('Donation', self::TEXT_DOMAIN),
                'add_new'           => __('Add Donation', self::TEXT_DOMAIN),
                'add_new_item'      => __( 'Add New Donation', self::TEXT_DOMAIN),
                'new_item'          => __( 'New Donation', self::TEXT_DOMAIN),
                'edit_item'         => __( 'Edit Donation', self::TEXT_DOMAIN),
                'view_item'         => __( 'View Donation', self::TEXT_DOMAIN),
                'all_items'         => __( 'All Donations', self::TEXT_DOMAIN),
                'search_items'      => __( 'Search Donations', self::TEXT_DOMAIN),
            ), 
            'public' => false, 
            'publicly_queryable' => false, 
            'show_ui' => true, 
            'show_in_menu' => true, 
            'rewrite' => self::POST_TYPE_DONATION,
            'capability_type' => 'post', 
            'supports' => array('title'),
        ));
    }
    
    /**
     * applies filter on custom content
     * 
     */
    public static function applyFilterOnCustomContent($content)
    {
        // perform additional logic here 
        
        return $content;
    }
}

Theme::setup();