jQuery(document).ready(function ($) {
    // Initialize Sortable
    $('#hero-slider-list').sortable({
        handle: '.handle',
        placeholder: 'ui-state-highlight'
    });

    // Add Image
    $('#add-hero-image').on('click', function (e) {
        e.preventDefault();
        var image_frame;
        if (image_frame) {
            image_frame.open();
            return;
        }
        image_frame = wp.media({
            title: 'Выберите изображение для слайдера',
            multiple: false,
            library: {
                type: 'image'
            }
        });
        image_frame.on('select', function () {
            var selection = image_frame.state().get('selection').first().toJSON();
            var image_url = selection.url;
            var html = '<li class="hero-slider-item">';
            html += '<div class="image-preview" style="background-image: url(' + image_url + ');"></div>';
            html += '<input type="hidden" name="hero_images[]" value="' + image_url + '">';
            html += '<button type="button" class="button remove-image">Удалить</button>';
            html += '<span class="dashicons dashicons-move handle"></span>';
            html += '</li>';
            $('#hero-slider-list').append(html);
        });
        image_frame.open();
    });

    // Remove Image
    $('#hero-slider-list').on('click', '.remove-image', function () {
        $(this).closest('li').remove();
    });
});
