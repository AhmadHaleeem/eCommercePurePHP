$(function () {
  'use strict';
  // Dashbboard Plus
  $('.toggle-info').click(function () {
    $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
    if ($(this).hasClass('selected')) {
      $(this).html('<i class="fa fa-minus fa-lg"></i>');
    } else {
      $(this).html('<i class="fa fa-plus fa-lg"></i>');
    }
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
  var passField = $('.password');
  $('.show-pass').hover(function () {
    passField.attr('type', 'text');
  }, function () {
    passField.attr('type', 'password');
  });
  // Make The Confirm Question
  $('.confirm').click(function () {
    return confirm('Are You Sure');
  });

  // Click On full-view to toggle
  $(".cat h3").click(function () {
    $(this).next('.full-view').fadeToggle(200);

  });

// In Categories Page Show Classic or Full View [Line Num 34]
  $(".option span").click(function () {
    $(this).addClass('active').siblings('span').removeClass('active');
    if ($(this).data('view') === 'full') {
      $(".cat .full-view").fadeIn(200);
    } else {
      $(".cat .full-view").fadeOut(200);
    }
  });



  $(".child-list").hover(function () {
    $(this).find(".child-delete").fadeIn(400);
  }, function () {
    $(this).find(".child-delete").fadeOut(400);
  });
});
