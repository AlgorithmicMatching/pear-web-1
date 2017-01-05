jQuery(function ($) {

    $(document).on("click", "#psf-submit", function (e) {
        // e.preventDefault();

        var fd = new FormData();
        var user_name = $("#name");
        var user_email = $("#email");
        var user_age = $("#age");
        var ref = $("#refferal");
        var utm = $("#source");
        var psf_nonce = $("#psf_nonce");

        fd.append("user_name", user_name.val());
        fd.append("user_email", user_email.val());
        fd.append("user_refferal", ref.val());
        fd.append("utm_source", utm.val());
        fd.append("user_age", user_age.find(":selected").text());
        fd.append("psf_nonce", psf_nonce.val());
        fd.append("action", "psf_form_submit");

        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        if (!user_name.val()) {
            user_name.attr("placeholder", psf.username_error);
            user_name.addClass("error-input");
        } else if (!user_email.val()) {
            user_email.attr("placeholder", psf.useremail_error);
            user_email.addClass("error-input");
        } else if (!regex.test(user_email.val())) {
            user_email.addClass("error-input");
        } else {
            $.ajax({
                type: 'POST',
                url: psf.ajax_url,
                data: fd,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.status === 200) {
                        user_name.val('');
                        user_email.val('');
                        $("#select2-age-container").html('<span class="select2-selection__placeholder">Age</span>');

                        toastr.success(response.title, response.message);
                    } else {
                        toastr.error(response.title, response.message);
                    }
                },
                beforeSend: function () {
                    window.startLoading();
                },
                complete: function () {
                    window.endLoading();
                }
            });
        }
    });
});
