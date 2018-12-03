jQuery(document).ready(function() {


    /* Load only on Cart page */
    if (jQuery("body.woocommerce-cart").length > 0) {

        /*
        * RETURN REASON buttons behavior on cart page
        * */
        jQuery("#tbyb-return-reason-form").submit(function (event) {
            event.preventDefault();

            // serialize the form data
            var formData = jQuery(this).serializeArray();

            jQuery.ajax({
                url: tbyb_public_ajax_object.ajax_url,
                type: 'POST',
                data: formData
            })

                .done(function () {
                    jQuery("[name='update_cart']").removeAttr('disabled').trigger('click');
                })

                .fail(function (error) {
                    console.log(error);
                })

        });


        /* Trigger "Update Cart" button each time return reason is submitted */
        jQuery(".tbyb-return-options input").live('click', function () {
            jQuery(".tbyb-submit-return-reason").trigger("click");
        })
    }
});