<?php
/**
 * Admin functionality for MP3 Playback Plugin
 * 
 * @package MP3PlaybackPlugin
 * @author David Armitage - Ambient Technology
 * @version 1.0.0
 * @license Apache License 2.0
 * @link https://ambient.technology
 */

if (!defined('ABSPATH')) {
    exit;
}

class MP3Playback_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_box_data'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_filter('manage_mp3_player_posts_columns', array($this, 'add_custom_columns'));
        add_action('manage_mp3_player_posts_custom_column', array($this, 'custom_column_content'), 10, 2);
    }
    
    public function add_admin_menu() {
        // The menu is automatically added by the custom post type
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'mp3_player_settings',
            __('MP3 Player Settings', 'simple-mp3-audio-player'),
            array($this, 'render_meta_box'),
            'mp3_player',
            'normal',
            'high'
        );
        
        add_meta_box(
            'mp3_player_shortcode',
            __('Shortcode', 'simple-mp3-audio-player'),
            array($this, 'render_shortcode_box'),
            'mp3_player',
            'side',
            'high'
        );
    }
    
    public function render_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('mp3_player_meta_box', 'mp3_player_meta_box_nonce');
        
        // Get current values
        $audio_file = get_post_meta($post->ID, '_mp3_audio_file', true);
        $autoplay = get_post_meta($post->ID, '_mp3_autoplay', true);
        $loop = get_post_meta($post->ID, '_mp3_loop', true);
        $show_controls = get_post_meta($post->ID, '_mp3_show_controls', true);
        
        // Default values
        $show_controls = $show_controls !== '' ? $show_controls : '1';
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="mp3_audio_file"><?php esc_html_e('Audio File', 'simple-mp3-audio-player'); ?></label>
                </th>
                <td>
                    <input type="hidden" id="mp3_audio_file" name="mp3_audio_file" value="<?php echo esc_attr($audio_file); ?>" />
                    <button type="button" id="select_audio_file" class="button">
                        <?php esc_html_e('Select Audio File', 'simple-mp3-audio-player'); ?>
                    </button>
                    <button type="button" id="remove_audio_file" class="button" style="display: <?php echo $audio_file ? 'inline-block' : 'none'; ?>;">
                        <?php esc_html_e('Remove', 'simple-mp3-audio-player'); ?>
                    </button>
                    <div id="audio_file_preview" style="margin-top: 10px;">
                        <?php if ($audio_file): ?>
                            <audio controls style="max-width: 100%;">
                                <source src="<?php echo esc_url($audio_file); ?>" type="audio/mpeg">
                                <?php esc_html_e('Your browser does not support the audio element.', 'simple-mp3-audio-player'); ?>
                            </audio>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Player Options', 'simple-mp3-audio-player'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="mp3_autoplay" value="1" <?php checked($autoplay, '1'); ?> />
                        <?php esc_html_e('Autoplay', 'simple-mp3-audio-player'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="mp3_loop" value="1" <?php checked($loop, '1'); ?> />
                        <?php esc_html_e('Loop', 'simple-mp3-audio-player'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="mp3_show_controls" value="1" <?php checked($show_controls, '1'); ?> />
                        <?php esc_html_e('Show Controls', 'simple-mp3-audio-player'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function render_shortcode_box($post) {
        $shortcode = '[mp3_player id="' . $post->ID . '"]';
        ?>
        <p><?php esc_html_e('Use this shortcode to display the player in your posts or pages:', 'simple-mp3-audio-player'); ?></p>
        <div style="background: #f1f1f1; padding: 10px; border-radius: 3px;">
            <code id="shortcode_text" style="display: block; word-break: break-all;"><?php echo esc_html($shortcode); ?></code>
        </div>
        <p>
            <button type="button" id="copy_shortcode" class="button button-small">
                <?php esc_html_e('Copy Shortcode', 'simple-mp3-audio-player'); ?>
            </button>
        </p>
        <div id="copy_message" style="display: none; color: green; font-weight: bold;">
            <?php esc_html_e('Shortcode copied!', 'simple-mp3-audio-player'); ?>
        </div>
        <?php
    }
    
    public function save_meta_box_data($post_id) {
        // Check if nonce is valid
        if (!isset($_POST['mp3_player_meta_box_nonce']) || 
            !wp_verify_nonce(wp_unslash($_POST['mp3_player_meta_box_nonce']), 'mp3_player_meta_box')) {
            return;
        }
        
        // Check if user has permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Check if not an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Save audio file
        if (isset($_POST['mp3_audio_file'])) {
            update_post_meta($post_id, '_mp3_audio_file', sanitize_url(wp_unslash($_POST['mp3_audio_file'])));
        }
        
        // Save autoplay setting
        $autoplay = isset($_POST['mp3_autoplay']) ? '1' : '0';
        update_post_meta($post_id, '_mp3_autoplay', $autoplay);
        
        // Save loop setting
        $loop = isset($_POST['mp3_loop']) ? '1' : '0';
        update_post_meta($post_id, '_mp3_loop', $loop);
        
        // Save show controls setting
        $show_controls = isset($_POST['mp3_show_controls']) ? '1' : '0';
        update_post_meta($post_id, '_mp3_show_controls', $show_controls);
    }
    
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if ($post_type !== 'mp3_player') {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script(
            'mp3-playback-admin',
            MP3_PLAYBACK_PLUGIN_URL . 'admin/js/admin.js',
            array('jquery'),
            MP3_PLAYBACK_VERSION,
            true
        );
        
        wp_localize_script('mp3-playback-admin', 'mp3PlaybackAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mp3_playback_nonce'),
            'strings' => array(
                'selectAudioFile' => __('Select Audio File', 'mp3-playback'),
                'useThisFile' => __('Use This File', 'mp3-playback'),
            )
        ));
    }
    
    public function add_custom_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['shortcode'] = esc_html__('Shortcode', 'simple-mp3-audio-player');
        $new_columns['audio_file'] = esc_html__('Audio File', 'simple-mp3-audio-player');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    public function custom_column_content($column, $post_id) {
        switch ($column) {
            case 'shortcode':
                $shortcode = '[mp3_player id="' . $post_id . '"]';
                echo '<code>' . esc_html($shortcode) . '</code>';
                break;
                
            case 'audio_file':
                $audio_file = get_post_meta($post_id, '_mp3_audio_file', true);
                if ($audio_file) {
                    echo '<a href="' . esc_url($audio_file) . '" target="_blank">' . esc_html(basename($audio_file)) . '</a>';
                } else {
                    echo '<span style="color: #999;">' . esc_html__('No file selected', 'simple-mp3-audio-player') . '</span>';
                }
                break;
        }
    }
    

} 