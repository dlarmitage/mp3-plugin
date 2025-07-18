=== MP3 Playback Plugin ===
Contributors: dlarmitage
Tags: audio, mp3, player, shortcode, media, podcast, music
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: Apache License 2.0
License URI: https://www.apache.org/licenses/LICENSE-2.0

A clean, streamlined MP3 player plugin for WordPress with easy shortcode integration and minimal design.

== Description ==

The MP3 Playback Plugin provides a simple, elegant solution for embedding audio players in WordPress posts and pages. Built with modern web standards and optimized for performance, this plugin offers a clean, minimal interface that integrates seamlessly with any WordPress theme.

= Features =

* **Clean, Minimal Design** - 70% width, centered player with no unnecessary elements
* **Easy Shortcode Integration** - Simple `[mp3_player id="123"]` syntax
* **WordPress Media Library Integration** - Upload and select audio files directly
* **Custom Post Type** - Dedicated "MP3 Players" section in admin
* **Responsive Design** - Works on all devices and screen sizes
* **Accessibility Support** - ARIA labels and keyboard navigation
* **No External Dependencies** - Lightweight and fast
* **Theme Compatibility** - Built-in CSS fixes for maximum compatibility

= Perfect For =

* Podcasts and audio content
* Music websites
* Educational audio materials
* Corporate audio presentations
* Any WordPress site needing audio players

= Quick Start =

1. Install and activate the plugin
2. Go to **MP3 Players → Add New**
3. Upload or select an audio file
4. Copy the generated shortcode
5. Paste the shortcode into any post or page

= Shortcode Usage =

Basic usage:
`[mp3_player id="123"]`

Advanced usage:
`[mp3_player id="123" autoplay="1" loop="1" controls="1" width="80%" height="50px"]`

Direct file usage:
`[mp3_player file="https://example.com/audio.mp3"]`

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/mp3-playback-plugin` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the MP3 Players menu to configure the plugin

== Frequently Asked Questions ==

= Does this plugin work with all themes? =

Yes! The plugin includes built-in CSS fixes to ensure compatibility with any WordPress theme.

= Can I customize the player appearance? =

Yes, you can customize the CSS by adding styles to your theme. The plugin uses classes like `.mp3-player-container` and `.mp3-player-audio`.

= What audio formats are supported? =

The plugin works best with MP3 files, but any format supported by HTML5 audio will work.

= Is this plugin accessible? =

Yes, the plugin includes ARIA labels and keyboard navigation for accessibility compliance.

= Can I use this for commercial projects? =

Yes, this plugin is licensed under Apache License 2.0, which allows commercial use.

== Screenshots ==

1. Admin interface showing MP3 player creation
2. Shortcode generation in the admin panel
3. Player embedded in a WordPress post
4. List view of all MP3 players

== Changelog ==

= 1.0.0 =
* Initial release
* Basic MP3 player functionality
* WordPress media library integration
* Shortcode support
* Admin interface
* Responsive design
* Theme compatibility fixes

== Upgrade Notice ==

= 1.0.0 =
Initial release of the MP3 Playback Plugin.

== Developer Information ==

= Hooks and Filters =

The plugin provides several hooks for developers:

**Actions:**
* `mp3_player_before_render` - Fired before player HTML is generated
* `mp3_player_after_render` - Fired after player HTML is generated
* `mp3_player_audio_loaded` - Fired when audio file is loaded

**Filters:**
* `mp3_player_default_settings` - Modify default player settings
* `mp3_player_shortcode_output` - Modify shortcode HTML output
* `mp3_player_audio_attributes` - Modify audio element attributes

= Example Usage =

```php
// Add custom CSS to player
add_action('mp3_player_after_render', function($player_id, $html) {
    echo '<style>.mp3-player-container { border: 2px solid #007cba; }</style>';
}, 10, 2);

// Modify default settings
add_filter('mp3_player_default_settings', function($settings) {
    $settings['width'] = '80%';
    return $settings;
});
```

== Support ==

For support, feature requests, or bug reports:

* **Website:** https://ambient.technology
* **Author:** David Armitage
* **Company:** Ambient Technology

== Credits ==

* **Developer:** David Armitage
* **Company:** Ambient Technology
* **Website:** https://ambient.technology
* **License:** Apache License 2.0

Made with ❤️ by Ambient Technology 