/* GO TO TOP */
jQuery(document).ready(function() {
	var offset = 220;
	var duration = 500;
	jQuery(window).scroll(function() {
		if (jQuery(this).scrollTop() > offset) {
			jQuery('.gtt').fadeIn(duration);
		} else {
			jQuery('.gtt').fadeOut(duration);
		}
	});
	
	jQuery('.gtt').click(function(event) {
		event.preventDefault();
		jQuery('html, body').animate({scrollTop: 0}, duration);
		return false;
	})
});

/* CONTACT FORM */
$(document).ready(function() {
  $("#feedbackSubmit").click(function() {
    //clear any errors
    contactForm.clearErrors();
    //do a little client-side validation -- check that each field has a value and e-mail field is in proper format
    var hasErrors = false;
    $('#feedbackForm input,textarea').each(function() {
      if (!$(this).val()) {
        hasErrors = true;
        contactForm.addError($(this));
      }
    });
    var $email = $('#email');
    if (!contactForm.isValidEmail($email.val())) {
      hasErrors = true;
      contactForm.addError($email);
    }
    //if there are any errors return without sending e-mail
    if (hasErrors) {
      return false;
    }
    //send the feedback e-mail
    $.ajax({
      type: "POST",
      url: "/assets/forms/contact/sendmail.php",
      data: $("#feedbackForm").serialize(),
      success: function(data)
      {
        contactForm.addAjaxMessage(data.message, false);
        //get new Captcha on success
		document.getElementById('name').value='';
		document.getElementById('email').value='';
		document.getElementById('message').value='';
		document.getElementById('captcha_code').value='';
		document.getElementById('feedbackSubmit').focus();
        $('#captcha').attr('src', '/assets/forms/contact/securimage_show.php?' + Math.random());
      },
      error: function(response)
      {
        contactForm.addAjaxMessage(response.responseJSON.message, true);
      }
   });
    return false;
  }); 
});
//namespace as not to pollute global namespace
var contactForm = {
  isValidEmail: function (email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  },
  clearErrors: function () {
    $('#emailAlert').remove();
    $('#feedbackForm .help-block').hide();
    $('#feedbackForm .form-group').removeClass('has-error');
  },
  addError: function ($input) {
    $input.siblings('.help-block').show();
    $input.parent('.form-group').addClass('has-error');
  },
  addAjaxMessage: function(msg, isError) {
    $("#feedbackSubmit").after('<div id="emailAlert" class="alert alert-' + (isError ? 'danger' : 'success') + '" style="margin-top: 5px;">' + $('<div/>').text(msg).html() + '</div>');
  }
};