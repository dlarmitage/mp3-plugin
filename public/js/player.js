/**
 * Frontend JavaScript for MP3 Playback Plugin
 * 
 * @package MP3PlaybackPlugin
 * @author David Armitage - Ambient Technology
 * @version 1.0.0
 * @license Apache License 2.0
 * @link https://ambient.technology
 */

jQuery(document).ready(function($) {
    
    // Initialize all MP3 players
    $('.mp3-player-container').each(function() {
        var container = $(this);
        var audio = container.find('.mp3-player-audio')[0];
        var customControls = container.find('.mp3-player-custom-controls');
        
        if (audio && customControls.length > 0) {
            initCustomPlayer(container, audio);
        }
    });
    
    function initCustomPlayer(container, audio) {
        var playerId = container.attr('id');
        var controls = container.find('.mp3-player-custom-controls');
        
        // Get control elements
        var playPauseBtn = controls.find('.mp3-player-play-pause');
        var rewindBtn = controls.find('.mp3-player-rewind');
        var forwardBtn = controls.find('.mp3-player-forward');
        var progressBar = controls.find('.mp3-player-progress-bar');
        var progressFill = controls.find('.mp3-player-progress-fill');
        var currentTime = controls.find('.mp3-player-current-time');
        var duration = controls.find('.mp3-player-duration');
        var volumeSlider = controls.find('.mp3-player-volume');
        var muteBtn = controls.find('.mp3-player-mute');
        var speedSelect = controls.find('.mp3-player-speed');
        
        var isPlaying = false;
        var isMuted = false;
        var originalVolume = 1;
        
        // Format time helper
        function formatTime(seconds) {
            if (isNaN(seconds)) return '0:00';
            var mins = Math.floor(seconds / 60);
            var secs = Math.floor(seconds % 60);
            return mins + ':' + (secs < 10 ? '0' : '') + secs;
        }
        
        // Update progress bar
        function updateProgress() {
            if (audio.duration) {
                var progress = (audio.currentTime / audio.duration) * 100;
                progressFill.css('width', progress + '%');
                currentTime.text(formatTime(audio.currentTime));
            }
        }
        
        // Update play/pause button
        function updatePlayPauseButton() {
            var playIcon = playPauseBtn.find('.mp3-player-icon-play');
            var pauseIcon = playPauseBtn.find('.mp3-player-icon-pause');
            
            if (isPlaying) {
                playIcon.hide();
                pauseIcon.show();
                playPauseBtn.attr('aria-label', mp3PlaybackPlayer.strings.pause);
            } else {
                playIcon.show();
                pauseIcon.hide();
                playPauseBtn.attr('aria-label', mp3PlaybackPlayer.strings.play);
            }
        }
        
        // Update mute button
        function updateMuteButton() {
            if (isMuted || audio.volume === 0) {
                muteBtn.text('🔇');
                muteBtn.attr('aria-label', mp3PlaybackPlayer.strings.unmute);
            } else {
                muteBtn.text('🔊');
                muteBtn.attr('aria-label', mp3PlaybackPlayer.strings.mute);
            }
        }
        
        // Play/Pause functionality
        playPauseBtn.on('click', function() {
            if (isPlaying) {
                audio.pause();
            } else {
                audio.play();
            }
        });
        
        // Rewind 15 seconds
        rewindBtn.on('click', function() {
            audio.currentTime = Math.max(0, audio.currentTime - 15);
        });
        
        // Forward 15 seconds
        forwardBtn.on('click', function() {
            audio.currentTime = Math.min(audio.duration, audio.currentTime + 15);
        });
        
        // Progress bar click to seek
        progressBar.on('click', function(e) {
            var rect = this.getBoundingClientRect();
            var clickX = e.clientX - rect.left;
            var width = rect.width;
            var percentage = clickX / width;
            audio.currentTime = percentage * audio.duration;
        });
        
        // Volume control
        volumeSlider.on('input', function() {
            var volume = this.value / 100;
            audio.volume = volume;
            originalVolume = volume;
            
            if (volume === 0) {
                isMuted = true;
            } else {
                isMuted = false;
            }
            
            updateMuteButton();
        });
        
        // Mute/Unmute
        muteBtn.on('click', function() {
            if (isMuted) {
                audio.volume = originalVolume;
                volumeSlider.val(originalVolume * 100);
                isMuted = false;
            } else {
                originalVolume = audio.volume;
                audio.volume = 0;
                volumeSlider.val(0);
                isMuted = true;
            }
            
            updateMuteButton();
        });
        
        // Playback speed
        speedSelect.on('change', function() {
            audio.playbackRate = parseFloat(this.value);
        });
        
        // Audio event listeners
        audio.addEventListener('loadstart', function() {
            container.addClass('mp3-player-loading');
        });
        
        audio.addEventListener('canplay', function() {
            container.removeClass('mp3-player-loading');
            duration.text(formatTime(audio.duration));
        });
        
        audio.addEventListener('play', function() {
            isPlaying = true;
            updatePlayPauseButton();
        });
        
        audio.addEventListener('pause', function() {
            isPlaying = false;
            updatePlayPauseButton();
        });
        
        audio.addEventListener('timeupdate', updateProgress);
        
        audio.addEventListener('ended', function() {
            isPlaying = false;
            updatePlayPauseButton();
        });
        
        audio.addEventListener('volumechange', function() {
            updateMuteButton();
        });
        
        // Keyboard shortcuts
        container.on('keydown', function(e) {
            switch(e.keyCode) {
                case 32: // Spacebar
                    e.preventDefault();
                    if (isPlaying) {
                        audio.pause();
                    } else {
                        audio.play();
                    }
                    break;
                    
                case 37: // Left arrow
                    e.preventDefault();
                    audio.currentTime = Math.max(0, audio.currentTime - 15);
                    break;
                    
                case 39: // Right arrow
                    e.preventDefault();
                    audio.currentTime = Math.min(audio.duration, audio.currentTime + 15);
                    break;
                    
                case 38: // Up arrow
                    e.preventDefault();
                    var newVolume = Math.min(1, audio.volume + 0.1);
                    audio.volume = newVolume;
                    volumeSlider.val(newVolume * 100);
                    break;
                    
                case 40: // Down arrow
                    e.preventDefault();
                    var newVolume = Math.max(0, audio.volume - 0.1);
                    audio.volume = newVolume;
                    volumeSlider.val(newVolume * 100);
                    break;
                    
                case 77: // M key
                    e.preventDefault();
                    muteBtn.click();
                    break;
            }
        });
        
        // Focus management for accessibility
        container.attr('tabindex', '0');
        
        // Initialize volume
        audio.volume = 1;
        volumeSlider.val(100);
        updateMuteButton();
        
        // Add loading state
        if (audio.readyState === 0) {
            container.addClass('mp3-player-loading');
        }
    }
    
    // Handle dynamically added players (e.g., via AJAX)
    $(document).on('mp3-player-added', function(e, container) {
        var audio = container.find('.mp3-player-audio')[0];
        var customControls = container.find('.mp3-player-custom-controls');
        
        if (audio && customControls.length > 0) {
            initCustomPlayer(container, audio);
        }
    });
    
    // Global audio management (pause other players when one starts)
    $(document).on('play', '.mp3-player-audio', function() {
        var currentPlayer = $(this).closest('.mp3-player-container');
        
        $('.mp3-player-audio').not(this).each(function() {
            var otherPlayer = $(this).closest('.mp3-player-container');
            if (otherPlayer.attr('id') !== currentPlayer.attr('id')) {
                this.pause();
            }
        });
    });
    
    // Touch support for mobile devices
    if ('ontouchstart' in window) {
        $('.mp3-player-container').addClass('mp3-player-touch');
        
        // Add touch feedback
        $('.mp3-player-btn').on('touchstart', function() {
            $(this).addClass('mp3-player-btn-touch');
        }).on('touchend touchcancel', function() {
            $(this).removeClass('mp3-player-btn-touch');
        });
    }
    
    // Error handling
    $(document).on('error', '.mp3-player-audio', function() {
        var container = $(this).closest('.mp3-player-container');
        container.addClass('mp3-player-error');
        
        var errorMsg = $('<div class="mp3-player-error-message">')
            .text('Error loading audio file. Please check the file URL.')
            .appendTo(container);
    });
    
    // Performance optimization: throttle timeupdate events
    function throttle(func, limit) {
        var inThrottle;
        return function() {
            var args = arguments;
            var context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(function() {
                    inThrottle = false;
                }, limit);
            }
        };
    }
    
    // Apply throttling to timeupdate events
    $(document).on('timeupdate', '.mp3-player-audio', throttle(function() {
        var container = $(this).closest('.mp3-player-container');
        var controls = container.find('.mp3-player-custom-controls');
        var progressFill = controls.find('.mp3-player-progress-fill');
        var currentTime = controls.find('.mp3-player-current-time');
        
        if (this.duration) {
            var progress = (this.currentTime / this.duration) * 100;
            progressFill.css('width', progress + '%');
            currentTime.text(formatTime(this.currentTime));
        }
    }, 100));
    
    // Helper function for time formatting
    function formatTime(seconds) {
        if (isNaN(seconds)) return '0:00';
        var mins = Math.floor(seconds / 60);
        var secs = Math.floor(seconds % 60);
        return mins + ':' + (secs < 10 ? '0' : '') + secs;
    }
}); 