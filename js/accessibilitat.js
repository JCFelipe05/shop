$(document).ready(function() {
    
    // Default
    const defaultSettings = {
        fontSize: 1,
        lineHeight: 1.5,
        wordSpacing: 0,
        letterSpacing: 0,
        contrast: 'normal'
    };

    let currentSettings = { ...defaultSettings };

    // Cargar configuración guardada
    loadSettings();

    // Abrir y cerrar el panel
    $('#accessibility-toggle').on('click', function() {
        const $panel = $('#accessibility-panel');
        const isActive = $panel.hasClass('active');
        
        $panel.toggleClass('active');
        $panel.attr('aria-hidden', isActive);
        
        if (!isActive) {
            setTimeout(() => {
                $('#close-panel').focus();
            }, 300);
        }
    });

    $('#close-panel').on('click', function() {
        const $panel = $('#accessibility-panel');
        $panel.removeClass('active');
        $panel.attr('aria-hidden', 'true');
        $('#accessibility-toggle').focus();
    });

    // Cerrar con ESC
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#accessibility-panel').hasClass('active')) {
            $('#close-panel').click();
        }
    });

    // Cerrar haciendo click fuera
    $(document).on('click', function(e) {
        const $panel = $('#accessibility-panel');
        const $toggle = $('#accessibility-toggle');
        
        if ($panel.hasClass('active') && 
            !$panel.is(e.target) && 
            $panel.has(e.target).length === 0 &&
            !$toggle.is(e.target) && 
            $toggle.has(e.target).length === 0) {

            $('#close-panel').click();
        }
    });

    // Contraste
    $('[data-contrast]').on('click', function() {
        const contrastValue = $(this).data('contrast');
        
        // Eliminar clases de contraste
        $('body').removeClass('contrast-grayscale contrast-dark contrast-light contrast-high-saturation contrast-low-saturation');
        
        // Eliminar clase active a todos los botones
        $('[data-contrast]').removeClass('active');
        
        // Aplicar nuevo contraste
        if (contrastValue !== 'normal') {
            $('body').addClass('contrast-' + contrastValue);
            $(this).addClass('active');
        }
        
        currentSettings.contrast = contrastValue;
        saveSettings();
    });

    // Tipografia
    $('.control-btn').on('click', function() {
        const action = $(this).data('action');
        const target = $(this).data('target');
        
        switch(target) {
            case 'font-size':
                adjustFontSize(action);
                break;
            case 'line-height':
                adjustLineHeight(action);
                break;
            case 'word-spacing':
                adjustWordSpacing(action);
                break;
            case 'letter-spacing':
                adjustLetterSpacing(action);
                break;
        }
    });

    // Ajustes
    function adjustFontSize(action) {
        const step = 0.1;
        const min = 0.8;
        const max = 2;
        
        if (action === 'increase' && currentSettings.fontSize < max) {
            currentSettings.fontSize += step;
        } else if (action === 'decrease' && currentSettings.fontSize > min) {
            currentSettings.fontSize -= step;
        }
        
        currentSettings.fontSize = Math.round(currentSettings.fontSize * 10) / 10;
        $('body').css('font-size', currentSettings.fontSize + 'rem');
        $('#font-size-value').text(currentSettings.fontSize.toFixed(1) + 'em');
        saveSettings();
    }

    function adjustLineHeight(action) {
        const step = 0.1;
        const min = 1;
        const max = 3;
        
        if (action === 'increase' && currentSettings.lineHeight < max) {
            currentSettings.lineHeight += step;
        } else if (action === 'decrease' && currentSettings.lineHeight > min) {
            currentSettings.lineHeight -= step;
        }
        
        currentSettings.lineHeight = Math.round(currentSettings.lineHeight * 10) / 10;
        $('body').css('line-height', currentSettings.lineHeight);
        $('#line-height-value').text(currentSettings.lineHeight.toFixed(1));
        saveSettings();
    }

    function adjustWordSpacing(action) {
        const step = 0.1;
        const min = -0.5;
        const max = 1;
        
        if (action === 'increase' && currentSettings.wordSpacing < max) {
            currentSettings.wordSpacing += step;
        } else if (action === 'decrease' && currentSettings.wordSpacing > min) {
            currentSettings.wordSpacing -= step;
        }
        
        currentSettings.wordSpacing = Math.round(currentSettings.wordSpacing * 10) / 10;
        $('body').css('word-spacing', currentSettings.wordSpacing + 'em');
        $('#word-spacing-value').text(currentSettings.wordSpacing.toFixed(1) + 'em');
        saveSettings();
    }

    function adjustLetterSpacing(action) {
        const step = 0.05;
        const min = -0.1;
        const max = 0.5;
        
        if (action === 'increase' && currentSettings.letterSpacing < max) {
            currentSettings.letterSpacing += step;
        } else if (action === 'decrease' && currentSettings.letterSpacing > min) {
            currentSettings.letterSpacing -= step;
        }
        
        currentSettings.letterSpacing = Math.round(currentSettings.letterSpacing * 100) / 100;
        $('body').css('letter-spacing', currentSettings.letterSpacing + 'em');
        $('#letter-spacing-value').text(currentSettings.letterSpacing.toFixed(2) + 'em');
        saveSettings();
    }

    // Reset
    $('#reset-accessibility').on('click', function() {
        currentSettings = { ...defaultSettings };
        
        $('body').removeClass('contrast-grayscale contrast-dark contrast-light contrast-high-saturation contrast-low-saturation');
        $('[data-contrast]').removeClass('active');
        
        $('body').css({
            'font-size': defaultSettings.fontSize + 'rem',
            'line-height': defaultSettings.lineHeight,
            'word-spacing': defaultSettings.wordSpacing + 'em',
            'letter-spacing': defaultSettings.letterSpacing + 'em'
        });
        
        updateDisplayValues();
        localStorage.removeItem('accessibilitySettings');
        
        const $btn = $(this);
        const originalText = $btn.text();
        $btn.text('✓ Resetejat!');
        setTimeout(() => {
            $btn.text(originalText);
        }, 2000);
    });

    // Guardar y cargar
    function saveSettings() {
        localStorage.setItem('accessibilitySettings', JSON.stringify(currentSettings));
    }

    function loadSettings() {
        const saved = localStorage.getItem('accessibilitySettings');
        if (saved) {
            currentSettings = JSON.parse(saved);
            applySettings();
        }
    }

    function applySettings() {
        if (currentSettings.contrast !== 'normal') {
            $('body').addClass('contrast-' + currentSettings.contrast);
            $('[data-contrast="' + currentSettings.contrast + '"]').addClass('active');
        }
        
        $('body').css({
            'font-size': currentSettings.fontSize + 'rem',
            'line-height': currentSettings.lineHeight,
            'word-spacing': currentSettings.wordSpacing + 'em',
            'letter-spacing': currentSettings.letterSpacing + 'em'
        });
        
        updateDisplayValues();
    }

    function updateDisplayValues() {
        $('#font-size-value').text(currentSettings.fontSize.toFixed(1) + 'em');
        $('#line-height-value').text(currentSettings.lineHeight.toFixed(1));
        $('#word-spacing-value').text(currentSettings.wordSpacing.toFixed(1) + 'em');
        $('#letter-spacing-value').text(currentSettings.letterSpacing.toFixed(2) + 'em');
    }

    // Trap focus dentro del panel
    $('#accessibility-panel').on('keydown', function(e) {
        if (e.key === 'Tab' && $(this).hasClass('active')) {
            const $focusableElements = $(this).find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const $firstElement = $focusableElements.first();
            const $lastElement = $focusableElements.last();
            
            if (e.shiftKey) {
                if (document.activeElement === $firstElement[0]) {
                    e.preventDefault();
                    $lastElement.focus();
                }
            } else {
                if (document.activeElement === $lastElement[0]) {
                    e.preventDefault();
                    $firstElement.focus();
                }
            }
        }
    });
});