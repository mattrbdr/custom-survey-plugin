jQuery(document).ready(function($) {
    $('.question-title').click(function() {
        var $responses = $(this).next('.responses');
        $responses.toggle();

        // Toggle arrow direction
        var $arrow = $(this).find('.toggle-arrow');
        if ($responses.is(':visible')) {
            $arrow.html('&#9660;'); // Down arrow
            $(this).addClass('responses-visible');
        } else {
            $arrow.html('&#9654;'); // Right arrow
            $(this).removeClass('responses-visible');
        }
    });
});
