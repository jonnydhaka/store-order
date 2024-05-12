;(function($) {

    $('#wppool-enquiry-form form').on('submit', function(e) {
        e.preventDefault();

        var data = $(this).serialize();

        $.post(WppoolStoreOrder.ajaxurl, data, function(response) {
            if (response.success) {
                console.log(response.success);
            } else {
                alert(response.data.message);
            }
        })
        .fail(function() {
            alert(WppoolStoreOrder.error);
        })

    });

})(jQuery);
