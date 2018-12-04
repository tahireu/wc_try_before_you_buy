jQuery(document).ready(function() {


    /* Load only in admin side product page */
    if (jQuery(".wp-admin.post-type-product").length > 0) {

        /*
        * User names auto suggest
        * */
        jQuery('#user_name').autoComplete({
            source: function(name, response) {

                var data = { action : 'get_listing_names', name : name };

                jQuery.ajax({
                    url: tbyb_admin_ajax_object.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: data
                })

                .done( function(data) {
                    response(data);
                })

                .fail( function(error) {
                    console.log(error);
                })
            }
        });


        /* Prevent form submit with enter key */
        jQuery(".tbyb-meta-box-main-holder input[type='text'], " +
            ".tbyb-meta-box-main-holder input[type='number'], " +
            ".tbyb-meta-box-main-holder select").keypress(function(e) {
            var key = e.charCode || e.keyCode || 0;
            if (key == 13) {
                e.preventDefault();
            }
        });



        /*
        * "Add to customer's cart" form behavior
        * */
        var loadingStyle = jQuery('.tbyb-loader-image, .tbyb-loading-overlay');
        loadingStyle.hide();

        jQuery("#tbyb-prepare-product-form").submit(function(event) {
            event.preventDefault();

            var formData = jQuery(this).serialize();
            loadingStyle.show();

            jQuery.ajax({
                url: tbyb_admin_ajax_object.ajax_url,
                type: 'POST',
                data: formData
            })

            .done( function(response) { // response from the PHP action
                jQuery(" #tbyb-form-feedback ").html(response);
                loadingStyle.hide();
            })

            .fail( function(error) {
                loadingStyle.hide();
                jQuery(" #tbyb-form-feedback ").html( "<div class='tbyb-message tbyb-message-error'>Something went wrong.</div>" );
                console.log(error);
            })

        });
    }


    /* Load only on TBYB Overview page */
    if (jQuery("body.woocommerce_page_wc-try-before-you-buy-overview").length > 0) {

        /*
        * Delete single entry
        * */
        jQuery("button#tbyb-entry-delete-button").on('click', function(event){
            event.preventDefault();
            if (confirm("Do you really want to delete this item?\nDeleted item will not be added to user's cart on user login")) {
                var toBeDeleted =jQuery(this).attr('value');
                var data = { action : 'delete_item', item : toBeDeleted };
                var tbody = jQuery(this).closest('#the-list');

                jQuery.ajax({
                    url: tbyb_admin_ajax_object.ajax_url,
                    type: 'POST',
                    data: data
                })

                .done(function () {
                    jQuery("tr.entry-id-" + toBeDeleted).remove();

                    /* If it's last item, remove whole table */
                    if (tbody.find('tr').length == 0) {
                        tbody.closest('.tbyb-overview-table').remove();
                    }

                    jQuery(" #tbyb-overview-feedback ").html( "<div class='tbyb-message tbyb-message-success'>Item successfully deleted.</div>" );
                })

                .fail(function (error) {
                    console.log(error);
                })
            }

        });



        /*
        * Clear all entries for a single user
        * */
        jQuery("button#tbyb-clear-all").on('click', function(event){
            event.preventDefault();
            if (confirm("Do you really want to remove all items for this user? \nKeep in mind that items which are already added to cart will not be removed from cart.")) {
                var toBeDeleted =jQuery(this).attr('value');
                var data = { action : 'clear_user_items', user_id : toBeDeleted };

                jQuery.ajax({
                    url: tbyb_admin_ajax_object.ajax_url,
                    type: 'POST',
                    data: data
                })

                .done(function () {
                    jQuery("#tbyb-table-user-id-" + toBeDeleted).remove();
                    jQuery(" #tbyb-overview-feedback ").html( "<div class='tbyb-message tbyb-message-success'>All items for selected user successfully removed.</div>" );
                })

                .fail(function (error) {
                    console.log(error);
                })
            }

        });


        /*
        * Toggle prepared items visibility
        * */
        /* Single user items visibility */
        jQuery('.tbyb-overview-table caption span').on('click', function(){
            jQuery(this).closest('.tbyb-overview-table').toggleClass('close');
        });

        /* Expand all */
        jQuery('.tbyb-overview-toggle-visibility #expand-all').on('click', function(){
            jQuery('.tbyb-overview-table').removeClass('close');
        });

        /* Collapse all */
        jQuery('.tbyb-overview-toggle-visibility #collapse-all').on('click', function(){
            jQuery('.tbyb-overview-table').addClass('close');
        });
    }

});