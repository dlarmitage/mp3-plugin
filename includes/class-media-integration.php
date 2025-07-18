<?php
/**
 * Media integration for MP3 Playback Plugin
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

class MP3Playback_Media_Integration {
    
    public function __construct() {
        add_filter('upload_mimes', array($this, 'add_mp3_mime_type'));
        add_filter('wp_handle_upload', array($this, 'handle_mp3_upload'));
        add_action('add_attachment', array($this, 'add_mp3_meta'));
        add_filter('attachment_fields_to_edit', array($this, 'add_mp3_fields'), 10, 2);
        add_filter('attachment_fields_to_save', array($this, 'save_mp3_fields'), 10, 2);
    }
    
    /**
     * Add MP3 mime type to allowed upload types
     */
    public function add_mp3_mime_type($mimes) {
        if (!isset($mimes['mp3'])) {
            $mimes['mp3'] = 'audio/mpeg';
        }
        return $mimes;
    }
    
    /**
     * Handle MP3 upload and add metadata
     */
    public function handle_mp3_upload($upload) {
        if ($upload['type'] === 'audio/mpeg') {
            // Add duration and other metadata
            $this->add_audio_metadata($upload['file']);
        }
        return $upload;
    }
    
    /**
     * Add metadata when attachment is created
     */
    public function add_mp3_meta($attachment_id) {
        $attachment = get_post($attachment_id);
        $file_path = get_attached_file($attachment_id);
        
        if ($attachment && $file_path && pathinfo($file_path, PATHINFO_EXTENSION) === 'mp3') {
            $this->add_audio_metadata($file_path, $attachment_id);
        }
    }
    
    /**
     * Add audio metadata to attachment
     */
    private function add_audio_metadata($file_path, $attachment_id = null) {
        if (!function_exists('getid3_init')) {
            require_once(ABSPATH . 'wp-admin/includes/media.php');
        }
        
        $getID3 = new getID3;
        $file_info = $getID3->analyze($file_path);
        
        if ($attachment_id) {
            // Store duration
            if (isset($file_info['playtime_seconds'])) {
                update_post_meta($attachment_id, '_mp3_duration', $file_info['playtime_seconds']);
            }
            
            // Store bitrate
            if (isset($file_info['audio']['bitrate'])) {
                update_post_meta($attachment_id, '_mp3_bitrate', $file_info['audio']['bitrate']);
            }
            
            // Store sample rate
            if (isset($file_info['audio']['sample_rate'])) {
                update_post_meta($attachment_id, '_mp3_sample_rate', $file_info['audio']['sample_rate']);
            }
            
            // Store file size
            if (isset($file_info['filesize'])) {
                update_post_meta($attachment_id, '_mp3_filesize', $file_info['filesize']);
            }
        }
    }
    
    /**
     * Add custom fields to media library for MP3 files
     */
    public function add_mp3_fields($form_fields, $post) {
        if (strpos($post->post_mime_type, 'audio/mpeg') !== false) {
            $duration = get_post_meta($post->ID, '_mp3_duration', true);
            $bitrate = get_post_meta($post->ID, '_mp3_bitrate', true);
            $sample_rate = get_post_meta($post->ID, '_mp3_sample_rate', true);
            
            $form_fields['mp3_duration'] = array(
                'label' => __('Duration', 'mp3-playback'),
                'input' => 'html',
                'html' => $duration ? $this->format_duration($duration) : __('Unknown', 'mp3-playback'),
                'helps' => __('Length of the audio file', 'mp3-playback')
            );
            
            $form_fields['mp3_bitrate'] = array(
                'label' => __('Bitrate', 'mp3-playback'),
                'input' => 'html',
                'html' => $bitrate ? round($bitrate / 1000) . ' kbps' : __('Unknown', 'mp3-playback'),
                'helps' => __('Audio quality (bits per second)', 'mp3-playback')
            );
            
            $form_fields['mp3_sample_rate'] = array(
                'label' => __('Sample Rate', 'mp3-playback'),
                'input' => 'html',
                'html' => $sample_rate ? $sample_rate . ' Hz' : __('Unknown', 'mp3-playback'),
                'helps' => __('Audio sample rate', 'mp3-playback')
            );
            
            // Add shortcode field
            $shortcode = '[mp3_player file="' . $post->ID . '"]';
            $form_fields['mp3_shortcode'] = array(
                'label' => __('Shortcode', 'mp3-playback'),
                'input' => 'html',
                'html' => '<input type="text" readonly value="' . esc_attr($shortcode) . '" style="width: 100%;" onclick="this.select();" />',
                'helps' => __('Copy this shortcode to use this audio file in your posts', 'mp3-playback')
            );
        }
        
        return $form_fields;
    }
    
    /**
     * Save custom fields for MP3 files
     */
    public function save_mp3_fields($post, $attachment) {
        // This function can be used to save any custom fields if needed
        return $post;
    }
    
    /**
     * Format duration in MM:SS format
     */
    private function format_duration($seconds) {
        $minutes = floor($seconds / 60);
        $seconds = floor($seconds % 60);
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
    
    /**
     * Get MP3 file information
     */
    public static function get_mp3_info($attachment_id) {
        $duration = get_post_meta($attachment_id, '_mp3_duration', true);
        $bitrate = get_post_meta($attachment_id, '_mp3_bitrate', true);
        $sample_rate = get_post_meta($attachment_id, '_mp3_sample_rate', true);
        $filesize = get_post_meta($attachment_id, '_mp3_filesize', true);
        
        return array(
            'duration' => $duration,
            'duration_formatted' => $duration ? self::format_duration($duration) : '',
            'bitrate' => $bitrate,
            'bitrate_formatted' => $bitrate ? round($bitrate / 1000) . ' kbps' : '',
            'sample_rate' => $sample_rate,
            'sample_rate_formatted' => $sample_rate ? $sample_rate . ' Hz' : '',
            'filesize' => $filesize,
            'filesize_formatted' => $filesize ? size_format($filesize) : '',
        );
    }
} 