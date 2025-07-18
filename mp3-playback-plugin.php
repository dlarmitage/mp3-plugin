<?php
/**
 * Plugin Name: Simple MP3/Audio Player
 * Plugin URI: https://ambient.technology
 * Description: A streamlined MP3 player for WordPress with basic controls and shortcode support.
 * Version: 1.0.1
 * Author: David Armitage - Ambient Technology
 * Author URI: https://ambient.technology
 * License: Apache License 2.0
 * License URI: https://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: simple-mp3-audio-player
 * Requires at least: 5.0
 * Tested up to: 6.8
 * Requires PHP: 7.4
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MP3_PLAYBACK_VERSION', '1.0.1');
define('MP3_PLAYBACK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MP3_PLAYBACK_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MP3_PLAYBACK_PLUGIN_FILE', __FILE__);
define('MP3_PLAYBACK_AUTHOR', 'David Armitage - Ambient Technology');
define('MP3_PLAYBACK_AUTHOR_URI', 'https://ambient.technology');

// Main plugin class
class MP3PlaybackPlugin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init();
    }
    
    private function init() {
        // Load dependencies
        $this->load_dependencies();
        
        // Initialize components
        add_action('init', array($this, 'init_components'));
        
        // Register custom post type
        add_action('init', array($this, 'create_post_type'));
        
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    private function load_dependencies() {
        // Load admin class
        if (is_admin()) {
            require_once MP3_PLAYBACK_PLUGIN_DIR . 'includes/class-admin.php';
        }
        
        // Load public class
        require_once MP3_PLAYBACK_PLUGIN_DIR . 'includes/class-public.php';
        
        // Load shortcode class
        require_once MP3_PLAYBACK_PLUGIN_DIR . 'includes/class-shortcode.php';
        
        // Load media integration class
        require_once MP3_PLAYBACK_PLUGIN_DIR . 'includes/class-media-integration.php';
    }
    
    public function init_components() {
        // Initialize admin
        if (is_admin()) {
            new MP3Playback_Admin();
        }
        
        // Initialize public
        new MP3Playback_Public();
        
        // Initialize shortcode
        new MP3Playback_Shortcode();
        
        // Initialize media integration
        new MP3Playback_Media_Integration();
    }
    
    public function activate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    public function create_post_type() {
        register_post_type('mp3_player', array(
            'labels' => array(
                'name' => esc_html__('MP3 Players', 'simple-mp3-audio-player'),
                'singular_name' => esc_html__('MP3 Player', 'simple-mp3-audio-player'),
                'add_new' => esc_html__('Add New Player', 'simple-mp3-audio-player'),
                'add_new_item' => esc_html__('Add New MP3 Player', 'simple-mp3-audio-player'),
                'edit_item' => esc_html__('Edit MP3 Player', 'simple-mp3-audio-player'),
                'new_item' => esc_html__('New MP3 Player', 'simple-mp3-audio-player'),
                'view_item' => esc_html__('View MP3 Player', 'simple-mp3-audio-player'),
                'search_items' => esc_html__('Search MP3 Players', 'simple-mp3-audio-player'),
                'not_found' => esc_html__('No MP3 players found', 'simple-mp3-audio-player'),
                'not_found_in_trash' => esc_html__('No MP3 players found in trash', 'simple-mp3-audio-player'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => false,
            'supports' => array('title'),
            'menu_icon' => 'dashicons-controls-play',
            'show_in_rest' => true,
        ));
    }
}

// Initialize the plugin
function mp3_playback_init() {
    return MP3PlaybackPlugin::get_instance();
}

// Start the plugin
mp3_playback_init(); 