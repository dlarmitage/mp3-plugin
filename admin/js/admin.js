/**
 * Admin JavaScript for MP3 Playback Plugin
 * 
 * @package MP3PlaybackPlugin
 * @author David Armitage - Ambient Technology
 * @version 1.0.0
 * @license Apache License 2.0
 * @link https://ambient.technology
 */

jQuery(document).ready(function($) {
    
    // Media library integration
    $('#select_audio_file').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var fileInput = $('#mp3_audio_file');
        var preview = $('#audio_file_preview');
        var removeButton = $('#remove_audio_file');
        
        // Create media frame
        var frame = wp.media({
            title: mp3PlaybackAdmin.strings.selectAudioFile,
            button: {
                text: mp3PlaybackAdmin.strings.useThisFile
            },
            multiple: false,
            library: {
                type: 'audio'
            }
        });
        
        // When file is selected
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            
            // Update hidden input
            fileInput.val(attachment.url);
            
            // Show remove button
            removeButton.show();
            
            // Update preview
            preview.html('<audio controls style="max-width: 100%;">' +
                '<source src="' + attachment.url + '" type="audio/mpeg">' +
                'Your browser does not support the audio element.' +
                '</audio>');
        });
        
        frame.open();
    });
    
    // Remove audio file
    $('#remove_audio_file').on('click', function(e) {
        e.preventDefault();
        
        $('#mp3_audio_file').val('');
        $('#audio_file_preview').empty();
        $(this).hide();
    });
    
    // Copy shortcode functionality
    $('#copy_shortcode').on('click', function(e) {
        e.preventDefault();
        
        var shortcodeText = $('#shortcode_text').text();
        var copyMessage = $('#copy_message');
        
        // Create temporary textarea to copy text
        var textarea = document.createElement('textarea');
        textarea.value = shortcodeText;
        document.body.appendChild(textarea);
        textarea.select();
        
        try {
            document.execCommand('copy');
            copyMessage.show();
            
            // Hide message after 2 seconds
            setTimeout(function() {
                copyMessage.fadeOut();
            }, 2000);
        } catch (err) {
            console.error('Failed to copy shortcode:', err);
        }
        
        document.body.removeChild(textarea);
    });
    
    // Auto-select shortcode text when clicked
    $('#shortcode_text').on('click', function() {
        var range = document.createRange();
        range.selectNodeContents(this);
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    });
    
    // Add click-to-copy functionality to shortcode columns in admin list
    $('.h5ap_front_shortcode input').on('click', function() {
        this.select();
        document.execCommand('copy');
        
        // Show tooltip
        var tooltip = $(this).siblings('.htooltip');
        tooltip.text('Copied!').show();
        
        setTimeout(function() {
            tooltip.fadeOut();
        }, 1000);
    });
    
    // Form validation
    $('form#post').on('submit', function(e) {
        var audioFile = $('#mp3_audio_file').val();
        var title = $('#title').val();
        
        if (!title.trim()) {
            alert('Please enter a title for the MP3 player.');
            $('#title').focus();
            e.preventDefault();
            return false;
        }
        
        if (!audioFile) {
            alert('Please select an audio file.');
            $('#select_audio_file').focus();
            e.preventDefault();
            return false;
        }
    });
    
    // Auto-save draft when audio file is selected
    $('#mp3_audio_file').on('change', function() {
        var audioFile = $(this).val();
        if (audioFile && $('#post_status').val() === 'auto-draft') {
            // Trigger auto-save
            if (typeof wp.autosave !== 'undefined') {
                wp.autosave.server.triggerSave();
            }
        }
    });
    
    // Preview functionality
    $('#preview-button').on('click', function(e) {
        var audioFile = $('#mp3_audio_file').val();
        if (audioFile) {
            // Open preview in new window
            var previewUrl = $('#post-preview').attr('href');
            if (previewUrl) {
                window.open(previewUrl, 'preview');
            }
        }
    });
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 83) {
            e.preventDefault();
            $('#publish').click();
        }
        
        // Ctrl/Cmd + Enter to publish
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 13) {
            e.preventDefault();
            $('#publish').click();
        }
    });
    
    // Enhanced media library filtering
    if (typeof wp.media !== 'undefined') {
        wp.media.view.AttachmentFilters.Uploaded.prototype.createFilters = function() {
            var filters = this.options.filters;
            
            // Add MP3 filter
            filters.mp3 = {
                text: 'MP3 Files',
                props: {
                    type: 'audio/mpeg'
                },
                priority: 10
            };
            
            this.filters = filters;
        };
    }
    
    // Auto-refresh when returning from media library
    $(window).on('focus', function() {
        // Check if we need to refresh the page (e.g., after media upload)
        if (sessionStorage.getItem('mp3_playback_refresh')) {
            sessionStorage.removeItem('mp3_playback_refresh');
            location.reload();
        }
    });
    
    // Store refresh flag when media is uploaded
    if (typeof wp.media !== 'undefined') {
        wp.media.view.Attachment.Details.prototype.updateAttachment = function() {
            var self = this;
            wp.media.post('get-attachment', {
                id: this.model.get('id')
            }).done(function(attachment) {
                self.model.set(attachment);
                sessionStorage.setItem('mp3_playback_refresh', 'true');
            });
        };
    }
}); 