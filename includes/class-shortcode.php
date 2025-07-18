<?php
/**
 * Shortcode functionality for MP3 Playback Plugin
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

class MP3Playback_Shortcode {
    
    public function __construct() {
        add_shortcode('mp3_player', array($this, 'render_player'));
    }
    
    public function render_player($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'id' => 0,
            'file' => '',
            'autoplay' => '',
            'loop' => '',
            'controls' => '1',
            'width' => '70%',
            'height' => 'auto',
        ), $atts, 'mp3_player');
        
        // If file is provided directly, use it
        if (!empty($atts['file'])) {
            return $this->render_direct_player($atts);
        }
        
        // If ID is provided, get the player data
        if (!empty($atts['id'])) {
            return $this->render_player_by_id($atts);
        }
        
        return '<p>' . __('Error: No audio file or player ID specified.', 'mp3-playback') . '</p>';
    }
    
    private function render_player_by_id($atts) {
        $post = get_post($atts['id']);
        
        if (!$post || $post->post_type !== 'mp3_player') {
            return '<p>' . __('Error: MP3 Player not found.', 'mp3-playback') . '</p>';
        }
        
        // Get player settings
        $audio_file = get_post_meta($post->ID, '_mp3_audio_file', true);
        $autoplay = get_post_meta($post->ID, '_mp3_autoplay', true);
        $loop = get_post_meta($post->ID, '_mp3_loop', true);
        $show_controls = get_post_meta($post->ID, '_mp3_show_controls', true);
        
        if (empty($audio_file)) {
            return '<p>' . __('Error: No audio file selected for this player.', 'mp3-playback') . '</p>';
        }
        
        // Override with shortcode attributes if provided
        if ($atts['autoplay'] !== '') {
            $autoplay = $atts['autoplay'];
        }
        if ($atts['loop'] !== '') {
            $loop = $atts['loop'];
        }
        if ($atts['controls'] !== '1') {
            $show_controls = $atts['controls'];
        }
        
        return $this->generate_player_html($audio_file, $autoplay, $loop, $show_controls, $atts['width'], $atts['height'], '');
    }
    
    private function render_direct_player($atts) {
        $audio_file = $atts['file'];
        
        // Check if it's a media library ID
        if (is_numeric($audio_file)) {
            $audio_url = wp_get_attachment_url($audio_file);
            if (!$audio_url) {
                return '<p>' . __('Error: Audio file not found in media library.', 'mp3-playback') . '</p>';
            }
            $audio_file = $audio_url;
        }
        
        return $this->generate_player_html($audio_file, $atts['autoplay'], $atts['loop'], $atts['controls'], $atts['width'], $atts['height']);
    }
    
    private function generate_player_html($audio_file, $autoplay, $loop, $show_controls, $width, $height, $title = '') {
        // Generate unique ID for this player instance
        $player_id = 'mp3-player-' . uniqid();
        
        // Build attributes
        $attributes = array();
        $attributes[] = 'src="' . esc_url($audio_file) . '"';
        $attributes[] = 'preload="metadata"';
        
        if ($autoplay === '1' || $autoplay === 'true') {
            $attributes[] = 'autoplay';
        }
        
        if ($loop === '1' || $loop === 'true') {
            $attributes[] = 'loop';
        }
        
        if ($show_controls === '1' || $show_controls === 'true') {
            $attributes[] = 'controls';
        }
        
        $attributes[] = 'style="width: ' . esc_attr($width) . '; height: ' . esc_attr($height) . ';"';
        $attributes[] = 'class="mp3-player-audio"';
        $attributes[] = 'data-player-id="' . esc_attr($player_id) . '"';
        
        $html = '<div class="mp3-player-container" id="' . esc_attr($player_id) . '">';
        
        if ($title) {
            $html .= '<h4 class="mp3-player-title">' . esc_html($title) . '</h4>';
        }
        
        $html .= '<audio ' . implode(' ', $attributes) . '>';
        $html .= '<source src="' . esc_url($audio_file) . '" type="audio/mpeg">';
        $html .= __('Your browser does not support the audio element.', 'mp3-playback');
        $html .= '</audio>';
        
        // Add custom controls if not using native controls
        if ($show_controls !== '1' && $show_controls !== 'true') {
            $html .= $this->generate_custom_controls($player_id);
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    private function generate_custom_controls($player_id) {
        $html = '<div class="mp3-player-custom-controls" data-player-id="' . esc_attr($player_id) . '">';
        $html .= '<div class="mp3-player-controls-row">';
        
        // Play/Pause button
        $html .= '<button class="mp3-player-btn mp3-player-play-pause" aria-label="' . __('Play', 'mp3-playback') . '">';
        $html .= '<span class="mp3-player-icon mp3-player-icon-play">▶</span>';
        $html .= '<span class="mp3-player-icon mp3-player-icon-pause" style="display: none;">⏸</span>';
        $html .= '</button>';
        
        // Rewind 15s
        $html .= '<button class="mp3-player-btn mp3-player-rewind" aria-label="' . __('Rewind 15 seconds', 'mp3-playback') . '">⏪</button>';
        
        // Progress bar
        $html .= '<div class="mp3-player-progress-container">';
        $html .= '<div class="mp3-player-progress-bar">';
        $html .= '<div class="mp3-player-progress-fill"></div>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Forward 15s
        $html .= '<button class="mp3-player-btn mp3-player-forward" aria-label="' . __('Forward 15 seconds', 'mp3-playback') . '">⏩</button>';
        
        // Time display
        $html .= '<div class="mp3-player-time">';
        $html .= '<span class="mp3-player-current-time">0:00</span>';
        $html .= '<span class="mp3-player-time-separator"> / </span>';
        $html .= '<span class="mp3-player-duration">0:00</span>';
        $html .= '</div>';
        
        $html .= '</div>'; // .mp3-player-controls-row
        
        $html .= '<div class="mp3-player-controls-row">';
        
        // Volume control
        $html .= '<div class="mp3-player-volume-container">';
        $html .= '<button class="mp3-player-btn mp3-player-mute" aria-label="' . __('Mute', 'mp3-playback') . '">🔊</button>';
        $html .= '<input type="range" class="mp3-player-volume" min="0" max="100" value="100" aria-label="' . __('Volume', 'mp3-playback') . '">';
        $html .= '</div>';
        
        // Playback speed
        $html .= '<div class="mp3-player-speed-container">';
        $html .= '<select class="mp3-player-speed" aria-label="' . __('Playback Speed', 'mp3-playback') . '">';
        $html .= '<option value="0.5">0.5x</option>';
        $html .= '<option value="0.75">0.75x</option>';
        $html .= '<option value="1" selected>1x</option>';
        $html .= '<option value="1.25">1.25x</option>';
        $html .= '<option value="1.5">1.5x</option>';
        $html .= '<option value="2">2x</option>';
        $html .= '</select>';
        $html .= '</div>';
        
        $html .= '</div>'; // .mp3-player-controls-row
        
        $html .= '</div>'; // .mp3-player-custom-controls
        
        return $html;
    }
} 