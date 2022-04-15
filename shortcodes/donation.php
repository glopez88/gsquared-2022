<?php 

namespace GSquare\Shortcodes;

if (! defined('ABSPATH')) {
    exit;
}

use Gsquare\Theme;

/**
 * Class for handling the donations shortcode 
 */
class Donation 
{
    /**
     * name of the shortcode
     */
    const NAME = 'donation';
    
    /**
     * the title prefix to use when saving a donation post type
     */
    const POST_TITLE_PREFIX = 'Donation';
    
    /**
     * the available payment methods 
     */
    private static $paymentMethods = ['paypal', 'offline'];
    
    /**
     * handles the shortcode registration 
     */
    public static function register()
    {
        add_shortcode(self::NAME, array(__CLASS__, 'create'));
        
        add_action('wp_enqueue_scripts', array(__CLASS__, 'loadScripts'));
        add_action('wp_ajax_donate', array(__CLASS__, 'donate'));
        add_action('wp_ajax_nopriv_donate', array(__CLASS__, 'donate'));
    }
    
    public static function loadScripts()
    {
        $templateDir = get_template_directory_uri();
        
        wp_register_style('gsquared2022-swal-style',  "$templateDir/node_modules/sweetalert2/dist/sweetalert2.min.css", array(), wp_get_theme()->get('Version'));
        
        wp_register_script('gsquared2022-swal-script', "$templateDir/node_modules/sweetalert2/dist/sweetalert2.min.js", array(), wp_get_theme()->get('Version'));
        wp_register_script('gsquared2022-donation', "$templateDir/assets/dist/donation.min.js", array('jquery', 'gsquared2022-swal-script'), wp_get_theme()->get('Version'));
    }
    
    /**
     * the shortcode handler
     * 
     * @param array $atts Shortcode attributes.
     * @return string 
     */
    public static function create($atts, $content = '')
    {
        $atts = shortcode_atts(
            array(
                'title' => '',
                /**
                 * the target donation amount 
                 */
                'target_amount' => 4000000,
                /**
                 * allowed methods 
                 */
                'payment_methods' => 'paypal,offline',
                /**
                 * the max amount allowed by each submission
                 */
                'max_donation' => 100000,
            ), 
            $atts,
            self::NAME
        );
        
        $data = [
            'title' => $atts['title'],
            'target_amount' => abs($atts['target_amount']), 
            'max_donation' => abs($atts['max_donation']), 
            'payment_methods' => explode(',', $atts['payment_methods']), 
            'raised_amount' => self::getTotalRaisedAmount(), 
            'formatted_target_amount' => self::formatAmount($atts['target_amount']), 
            'content' => $content, 
        ];
        
        $data['raised_percent'] = $data['raised_amount'] / $data['target_amount'] * 100;
        
        // enqueues style and script required by the shortcode 
        wp_enqueue_style('gsquared2022-swal-style');
        wp_enqueue_script('gsquared2022-donation');
        wp_localize_script('gsquared2022-donation', 'donations', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));
        
        ob_start();
        get_template_part('template-parts/shortcode/donation', null, $data);
        $contents = ob_get_contents();
        ob_end_clean();
        
        return $contents;
    }
    
    /**
     * ajax handler for donation submission
     * 
     */
    public static function donate()
    {
        $data = array(
            'first_name' => isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '', 
            'last_name' => isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '', 
            'email' => isset($_POST['email']) ? sanitize_email($_POST['email']) : '', 
            'phone' => isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '', 
            'payment_method' => isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '', 
            'amount_donated' => isset($_POST['amount_donated']) ? abs($_POST['amount_donated']) : 0,
        );
        
        if (! $data['first_name']
            || ! $data['last_name']
            || ! $data['email']
            || ! $data['phone']
            || ! $data['payment_method']
            || ! $data['amount_donated']
        ) {
            $errorMessage = 'Please fill out all required fields.';
        } elseif (! in_array($data['payment_method'], self::$paymentMethods)) {
            $errorMessage = 'Please select a valid payment method.';
        } elseif (! $data['email']) {
            $errorMessage = 'Please enter a valid email address.';
        }
        
        if ($errorMessage) {
            wp_send_json(
                array(
                    'status' => 'error', 
                    'message' => $errorMessage,
                ), 
                422
            );
        }
        
        $postData = array(
            'post_title'    => self::makePostTitle($data),
            'post_type'     => Theme::POST_TYPE_DONATION,
            'post_status'   => 'publish'
        );
        $postId = wp_insert_post($postData);
        
        foreach ($data as $acfField => $acfValue) {
            update_field($acfField, $acfValue, $postId);
        }
        
        $newRaisedAmount = self::getTotalRaisedAmount();
        
        wp_send_json(
            array(
                'status' => 'success',
                'data' => array(
                    'raised_amount' => $newRaisedAmount,
                    'raised_amount_formatted' => number_format($newRaisedAmount),
                )
            )
        );
    }
    
    /**
     * generates a post title out of the given data
     *
     * @return string
     */
    private static function makePostTitle(array $data)
    {
        $name = array_filter(array( 
            isset($data['first_name']) ? $data['first_name'] : '',
            isset($data['last_name']) ? $data['last_name'] : '',
        ));
        $name = trim(implode(' ', $name));
        
        if (! $name) {
            $name = isset($data['email']) ? $data['email'] : '';
        } 
        
        $title = self::POST_TITLE_PREFIX; 
        $title .= $name ? " from $name" : '';
        $title .= ' @ ' . date('Y-m-d H:i:s');
        
        $title = array(
            self::POST_TITLE_PREFIX,
            ($name ? " from $name" : ''),
            '@', 
            date('Y-m-d H:i:s')
        );
        
        return implode(' ', $title);
        
    }
    
    /**
     * formats a monetary value into a easy readable form
     *
     * @param integer|float $value
     * @param integer $digit  reduce the format into how many digit
     * @return string
     */
    private static function formatAmount($value, $digits = 1) 
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        
        if ($value >= 1000000000) {
            $value = number_format(($value / 1000000000), $digits, '.', '') + 0;
            $value = $value . ' billion';
        }
        if ($value >= 1000000) {
            $value = number_format(($value / 1000000), $digits, '.', '') + 0;
            $value = $value . ' million';
        }
        if ($value >= 1000) {
            $value = number_format(($value / 1000), $digits, '.', '') + 0;
            $value = $value . ' thousand';
        }
        
        return $value;
    }
    
    /**
     * queries the total raised amount
     *
     * @return integer
     */
    private static function getTotalRaisedAmount()
    {
        global $wpdb; 
        
        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT 
                    SUM(pm.meta_value)
                FROM 
                    {$wpdb->prefix}posts p
                INNER JOIN 
                    {$wpdb->prefix}postmeta pm
                ON
                    p.ID = pm.post_id 
                AND 
                    pm.meta_key = %s
                WHERE
                    p.post_status = %s",
                'amount_donated',
                'publish'
            )
        );
    }
} 