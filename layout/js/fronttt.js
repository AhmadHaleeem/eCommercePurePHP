$(function () {
  'use strict';
  // Trigger The Selectboxit
  $("select").selectBoxIt({
    autoWidth: false
  });
// Start Toggle Login/ Signup Form
  $(".login-page h1 span").click(function () {
    $(this).addClass('selected').siblings().removeClass('selected');
    $('.login-page form').hide();
    $('.' + $(this).data('class')).show();
  });


  // Trigger The Selectboxit
  $("select").selectBoxIt({
    autoWidth: false
  });

  // Hide Placeholde On Form Focus
  $('[placeholder]').focus(function () {
    $(this).attr('data-text', $(this).attr('placeholder'));
    $(this).attr('placeholder', '');
  }).blur(function () {
    $(this).attr('placeholder', $(this).attr('data-text'));
  });

  // Asterisk
  $('input').each(function() {
    if ($(this).attr('required') === 'required') {
      $(this).after("<span class='Asterisk'>*</span>");
    }
  });



  // Make The Confirm Question
  $('.confirm').click(function () {
    return confirm('Are You Sure');
  });


// Write On key up On new Ad
/*$('.live-name').on("keyup", function () {
  $(".live-preview .caption h3").text($(this).val());
});

$('.live-desc').on("keyup", function () {
  $(".live-preview .caption p").text($(this).val());
});

$('.live-price').on("keyup", function () {
  $(".live-preview .price-tag").text("$" + $(this).val());
});
*/
  
// This is Another Way To Live Writing...
$('.live').on("keyup", function () {
  $($(this).data('class')).text($(this).val());
});



});
