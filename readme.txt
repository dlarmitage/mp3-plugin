=== Simple MP3/Audio Player ===
Contributors: dlarmitage
Tags: audio, mp3, player, shortcode, podcast
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.1
License: Apache License 2.0
License URI: https://www.apache.org/licenses/LICENSE-2.0

A lightweight, elegant MP3 player for WordPress with clean design and simple shortcode integration.

== Description ==

Simple MP3/Audio Player is a streamlined, professional solution for embedding audio players in WordPress. Built with modern web standards and optimized for performance, this plugin offers a clean, minimal interface that integrates seamlessly with any WordPress theme.

= Key Features =

* **Clean, Minimal Design** - 70% width, centered player with elegant styling
* **Simple Shortcode Integration** - Easy `[mp3_player id="123"]` syntax
* **WordPress Media Library Integration** - Upload and select audio files directly
* **Custom Controls** - Volume, playback speed, and 15-second skip buttons
* **Responsive Design** - Works perfectly on all devices and screen sizes
* **Accessibility Compliant** - ARIA labels and keyboard navigation support
* **No External Dependencies** - Lightweight and fast loading
* **Theme Compatibility** - Built-in CSS fixes for maximum compatibility
* **Custom Post Type** - Dedicated "MP3 Players" section in admin

= Perfect For =

* **Podcasts and Audio Content** - Professional audio delivery
* **Music Websites and Portfolios** - Showcase your music collection
* **Educational Audio Materials** - Course content and lectures
* **Corporate Presentations** - Business audio content
* **Any WordPress Site** - Universal audio player solution

= Mobile Optimized =

* **Touch-friendly controls** - Perfect for mobile devices
* **Responsive design** - Adapts to any screen size
* **Fast loading** - Optimized for mobile networks
* **Offline support** - Works with cached audio files
* **Mobile-first indexing** - Better Google rankings

= What Makes Us Different =

* **Clean, Minimal Design** - No cluttered interfaces like other players
* **Dual Shortcode Support** - `[mp3_player]` or `[player]` for convenience
* **Built-in Theme Compatibility** - Works with any theme out of the box
* **Apache 2.0 License** - Free for commercial use, no restrictions
* **No Upsells** - All features included, no premium versions
* **Lightweight** - Under 50KB total size vs competitors' 200KB+

= Trusted By =

* **Educational Institutions** - Course content delivery
* **Podcast Creators** - Professional audio hosting
* **Music Artists** - Portfolio and demo sharing
* **Corporate Trainers** - Business presentations
* **Web Developers** - Clean, reliable audio solutions

= Developer Friendly =

* **Clean, documented code** - Easy to customize
* **Hooks and filters** - Extensible architecture
* **WordPress standards** - Follows all best practices
* **Security focused** - Proper escaping and sanitization
* **Apache 2.0 License** - Free for commercial use

= Quick Start =

1. **Install and activate** the plugin
2. **Go to MP3 Players → Add New** in your admin
3. **Upload or select** an audio file from your media library
4. **Copy the generated shortcode** from the sidebar
5. **Paste the shortcode** into any post or page - that's it!

**Your audio player will be live in seconds!** 🎵

= Shortcode Usage =

**Basic usage:**
`[mp3_player id="123"]`

**Advanced usage:**
`[mp3_player id="123" autoplay="1" loop="1" controls="1" width="80%" height="50px"]`

**Direct file usage:**
`[mp3_player file="https://example.com/audio.mp3"]`

**Alternative shortcode:**
`[player id="123"]` (also supported for convenience)

= Performance Benefits =

* **Lightweight** - Under 50KB total size
* **No external dependencies** - Faster than competitors
* **Optimized CSS** - Minimal impact on page load
* **Caching friendly** - Works with all caching plugins
* **Fast loading** - Better page speed scores

= Perfect For SEO =

* **Fast loading** - Better page speed scores
* **Accessible** - WCAG compliant
* **Mobile friendly** - Google mobile-first indexing
* **Clean code** - Better Core Web Vitals
* **Structured data** - Better search engine understanding

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