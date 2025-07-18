<?php
/**
 * Public functionality for MP3 Playback Plugin
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

class MP3Playback_Public {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'add_custom_styles'));
    }
    
    public function enqueue_scripts() {
        // Always enqueue scripts to ensure they're available
        wp_enqueue_script(
            'mp3-playback-player',
            MP3_PLAYBACK_PLUGIN_URL . 'public/js/player.js',
            array('jquery'),
            MP3_PLAYBACK_VERSION,
            true
        );
        
        wp_localize_script('mp3-playback-player', 'mp3PlaybackPlayer', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mp3_playback_nonce'),
            'strings' => array(
                'play' => esc_html__('Play', 'simple-mp3-audio-player'),
                'pause' => esc_html__('Pause', 'simple-mp3-audio-player'),
                'mute' => esc_html__('Mute', 'simple-mp3-audio-player'),
                'unmute' => esc_html__('Unmute', 'simple-mp3-audio-player'),
            )
        ));
    }
    
    public function add_custom_styles() {
        ?>
        <style>
        /* MP3 Player Visibility Fix - Override any theme hiding */
        .mp3-player-container,
        .mp3-player-container * {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        .mp3-player-container {
            max-width: 100%;
            margin: 0 auto 20px auto;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            text-align: center;
        }
        
        .mp3-player-title {
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }
        
        .mp3-player-audio {
            width: 70% !important;
            height: 40px !important;
            border-radius: 6px;
            background: #f5f5f5;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            margin: 0 auto;
        }
        
        /* Force audio element visibility */
        audio[controls] {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            width: 70% !important;
            height: 40px !important;
            margin: 0 auto;
        }
        
        /* Custom Controls */
        .mp3-player-custom-controls {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }
        
        .mp3-player-controls-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .mp3-player-controls-row:last-child {
            margin-bottom: 0;
        }
        
        .mp3-player-btn {
            background: #007cba;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .mp3-player-btn:hover {
            background: #005a87;
        }
        
        .mp3-player-btn:active {
            transform: translateY(1px);
        }
        
        .mp3-player-btn:focus {
            outline: 2px solid #007cba;
            outline-offset: 2px;
        }
        
        .mp3-player-progress-container {
            flex: 1;
            position: relative;
        }
        
        .mp3-player-progress-bar {
            width: 100%;
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            cursor: pointer;
            position: relative;
        }
        
        .mp3-player-progress-fill {
            height: 100%;
            background: #007cba;
            border-radius: 3px;
            width: 0%;
            transition: width 0.1s;
        }
        
        .mp3-player-progress-bar:hover .mp3-player-progress-fill {
            background: #005a87;
        }
        
        .mp3-player-time {
            font-size: 12px;
            color: #666;
            min-width: 80px;
            text-align: center;
        }
        
        .mp3-player-volume-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .mp3-player-volume {
            width: 80px;
            height: 4px;
            -webkit-appearance: none;
            appearance: none;
            background: #e9ecef;
            border-radius: 2px;
            outline: none;
        }
        
        .mp3-player-volume::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 16px;
            height: 16px;
            background: #007cba;
            border-radius: 50%;
            cursor: pointer;
        }
        
        .mp3-player-volume::-moz-range-thumb {
            width: 16px;
            height: 16px;
            background: #007cba;
            border-radius: 50%;
            cursor: pointer;
            border: none;
        }
        
        .mp3-player-speed-container {
            display: flex;
            align-items: center;
        }
        
        .mp3-player-speed {
            padding: 6px 8px;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            background: white;
            font-size: 12px;
            cursor: pointer;
        }
        
        .mp3-player-speed:focus {
            outline: 2px solid #007cba;
            outline-offset: 2px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .mp3-player-controls-row {
                flex-wrap: wrap;
                gap: 8px;
            }
            
            .mp3-player-btn {
                min-width: 36px;
                height: 36px;
                font-size: 12px;
            }
            
            .mp3-player-volume {
                width: 60px;
            }
            
            .mp3-player-time {
                min-width: 70px;
                font-size: 11px;
            }
        }
        
        /* Accessibility */
        .mp3-player-btn[aria-pressed="true"] {
            background: #005a87;
        }
        
        .mp3-player-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Loading state */
        .mp3-player-loading .mp3-player-progress-fill {
            background: linear-gradient(90deg, #007cba 25%, #e9ecef 25%, #e9ecef 50%, #007cba 50%, #007cba 75%, #e9ecef 75%);
            background-size: 20px 100%;
            animation: loading 1s infinite linear;
        }
        
        @keyframes loading {
            0% { background-position: 0 0; }
            100% { background-position: 20px 0; }
        }
        
        /* Ultimate visibility fix - override everything */
        .mp3-player-container,
        .mp3-player-container audio,
        .mp3-player-container .mp3-player-audio,
        audio[controls],
        audio.mp3-player-audio {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            width: 70% !important;
            height: 40px !important;
            max-width: none !important;
            max-height: none !important;
            min-width: 200px !important;
            min-height: 30px !important;
            position: static !important;
            float: none !important;
            clear: both !important;
            overflow: visible !important;
            clip: auto !important;
            clip-path: none !important;
            transform: none !important;
            filter: none !important;
            backdrop-filter: none !important;
        }
        </style>
        <?php
    }
} 