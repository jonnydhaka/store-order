;(function($) {

    $(document).on('click', 'a.submitforapi', function(e) {
        e.preventDefault();

        // if (!confirm(WppoolStoreOrder.confirm)) {
        //     return;
        // }
        var self = $(this),
        domainname = self.data('domain');
        buttonaction="add";
        if (self.data('apiaction')) {
            buttonaction = self.data('apiaction');
            if (!confirm(WppoolStoreOrder.confirm)) {
                return;
            }
        }
        wp.ajax.post('wppool-store-order-get-api', {
            domainname: domainname,
            buttonaction: buttonaction,
            _wpnonce: WppoolStoreOrder.nonce
        })
        .done(function(response) {
            console.log("done")
           // window.location.reload();
        })
        .fail(function(e,f) {  
            console.log("error")
            window.location.reload();
        });
    });





})(jQuery);
