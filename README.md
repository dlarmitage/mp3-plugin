# MP3 Playback Plugin

A streamlined, lightweight MP3 player plugin for WordPress with clean design and easy shortcode integration.

**Version:** 1.0.0  
**Author:** David Armitage - Ambient Technology  
**License:** Apache License 2.0  
**Website:** https://ambient.technology

## Description

The MP3 Playback Plugin provides a simple, elegant solution for embedding audio players in WordPress posts and pages. Built with modern web standards and optimized for performance, this plugin offers a clean, minimal interface that integrates seamlessly with any WordPress theme.

## Features

- **Clean, Minimal Design** - 70% width, centered player with no unnecessary elements
- **Easy Shortcode Integration** - Simple `[mp3_player id="123"]` syntax
- **WordPress Media Library Integration** - Upload and select audio files directly
- **Custom Post Type** - Dedicated "MP3 Players" section in admin
- **Responsive Design** - Works on all devices and screen sizes
- **Accessibility Support** - ARIA labels and keyboard navigation
- **No External Dependencies** - Lightweight and fast
- **Theme Compatibility** - Built-in CSS fixes for maximum compatibility

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Modern web browser with HTML5 audio support

## Installation

### Method 1: WordPress Admin (Recommended)

1. Download the plugin ZIP file
2. Go to **Plugins → Add New** in your WordPress admin
3. Click **"Upload Plugin"**
4. Choose the ZIP file and click **"Install Now"**
5. Click **"Activate Plugin"**

### Method 2: Manual Installation

1. Extract the plugin files
2. Upload the `mp3-playback-plugin` folder to `/wp-content/plugins/`
3. Go to **Plugins → Installed Plugins**
4. Find "MP3 Playback Plugin" and click **"Activate"**

## Usage

### Creating an MP3 Player

1. Go to **MP3 Players → Add New** in your WordPress admin
2. Give your player a title (e.g., "Episode 1 - Introduction")
3. In the **MP3 Player Settings** section:
   - Click **"Select Audio File"** to choose an MP3 from your media library
   - Configure player options (Autoplay, Loop, Show Controls)
4. **Publish** the player
5. Copy the generated shortcode from the **Shortcode** sidebar

### Using the Shortcode

#### Basic Usage
```
[mp3_player id="123"]
```

#### Advanced Usage
```
[mp3_player id="123" autoplay="1" loop="1" controls="1" width="80%" height="50px"]
```

#### Direct File Usage
```
[mp3_player file="https://example.com/audio.mp3"]
```

### Shortcode Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `id` | integer | - | MP3 Player post ID |
| `file` | string | - | Direct audio file URL |
| `autoplay` | string | - | Enable autoplay (1/0) |
| `loop` | string | - | Enable loop (1/0) |
| `controls` | string | 1 | Show controls (1/0) |
| `width` | string | 70% | Player width |
| `height` | string | auto | Player height |

## Admin Interface

### MP3 Players Menu

The plugin adds a dedicated "MP3 Players" menu item to your WordPress admin with:

- **List View** - See all players with shortcodes and file information
- **Add New** - Create new MP3 players
- **Bulk Actions** - Manage multiple players at once

### Player Settings

Each MP3 player can be configured with:

- **Audio File** - Select from WordPress media library
- **Autoplay** - Start playing automatically when page loads
- **Loop** - Repeat audio when finished
- **Show Controls** - Display native browser controls

### Shortcode Generation

Every player automatically generates a shortcode that can be:
- Copied with one click
- Used in any post or page
- Customized with additional parameters

## Customization

### CSS Customization

The plugin includes built-in CSS for theme compatibility. To customize further, add CSS to your theme:

```css
/* Custom player styling */
.mp3-player-container {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.mp3-player-audio {
    width: 100% !important;
    height: 50px !important;
}
```

### JavaScript Customization

The plugin includes JavaScript for enhanced functionality. Custom scripts can be added to your theme:

```javascript
// Custom audio player events
document.addEventListener('DOMContentLoaded', function() {
    const audioPlayers = document.querySelectorAll('.mp3-player-audio');
    audioPlayers.forEach(function(player) {
        player.addEventListener('play', function() {
            console.log('Audio started playing');
        });
    });
});
```

## File Structure

```
mp3-playback-plugin/
├── mp3-playback-plugin.php          # Main plugin file
├── README.md                        # This file
├── LICENSE                          # Apache 2.0 License
├── assets/                          # Plugin images and graphics
│   ├── icon.svg                     # Plugin icon (SVG)
│   ├── banner-description.txt       # Banner image specifications
│   └── screenshot-description.txt   # Screenshot specifications
├── includes/
│   ├── class-admin.php              # Admin functionality
│   ├── class-shortcode.php          # Shortcode handling
│   ├── class-public.php             # Frontend functionality
│   └── class-media-integration.php  # Media library integration
├── admin/
│   └── js/
│       └── admin.js                 # Admin JavaScript
└── public/
    └── js/
        └── player.js                # Frontend JavaScript
```

## API Reference

### Hooks and Filters

#### Actions
- `mp3_player_before_render` - Fired before player HTML is generated
- `mp3_player_after_render` - Fired after player HTML is generated
- `mp3_player_audio_loaded` - Fired when audio file is loaded

#### Filters
- `mp3_player_default_settings` - Modify default player settings
- `mp3_player_shortcode_output` - Modify shortcode HTML output
- `mp3_player_audio_attributes` - Modify audio element attributes

### Example Usage

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

## Troubleshooting

### Common Issues

**Player not visible:**
- Check if your theme has CSS conflicts
- Verify the audio file URL is accessible
- Try refreshing the page

**Audio doesn't play:**
- Check browser console for errors
- Verify audio file format (MP3 recommended)
- Test audio file URL directly in browser

**Shortcode not working:**
- Verify the player ID exists
- Check if the player has an audio file selected
- Ensure the shortcode syntax is correct

### Debug Mode

For administrators, the plugin includes debug information when needed. Check the browser console for any JavaScript errors.

## Performance

- **Lightweight** - No external dependencies
- **Optimized CSS** - Minimal, efficient styles
- **Lazy Loading** - Scripts only load when needed
- **Caching Friendly** - Compatible with caching plugins

## Security

- **Nonce Verification** - All admin actions are secured
- **Input Sanitization** - All user inputs are sanitized
- **Capability Checks** - Proper WordPress permissions
- **XSS Protection** - Output is properly escaped

## Changelog

### Version 1.0.0
- Initial release
- Basic MP3 player functionality
- WordPress media library integration
- Shortcode support
- Admin interface
- Responsive design
- Theme compatibility fixes

## Support

For support, feature requests, or bug reports:

- **Website:** https://ambient.technology
- **Author:** David Armitage
- **Company:** Ambient Technology

## License

This plugin is licensed under the Apache License 2.0. See the [LICENSE](LICENSE) file for details.

### Apache License 2.0 Summary

- **Commercial Use** - Allowed
- **Modification** - Allowed
- **Distribution** - Allowed
- **Patent Use** - Allowed
- **Private Use** - Allowed

**Limitations:**
- **Liability** - No warranty provided
- **Trademark Use** - Not allowed

## Credits

- **Developer:** David Armitage
- **Company:** Ambient Technology
- **Website:** https://ambient.technology
- **License:** Apache License 2.0

---

**Made with ❤️ by Ambient Technology** 