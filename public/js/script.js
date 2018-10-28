$(document).ready(function() {

  $("textarea").css('min-height', $('main').height());

  $("textarea").each(function() { // Resize textarea on load
    this.style.height = (this.scrollHeight+2)+ 'px'; // scrollHeight is js to element height considering scroll. 2 accounts for border
  });

  $('textarea').on('input', function() { // Resize textarea on input
    $(this).css('height', '0px'); // Reset height, so that it not only grows but also shrinks
    // this.style.height = '0px'; // Reset height, so that it not only grows but also shrinks
    this.style.height = (this.scrollHeight+2) + 'px'; // Set new height
  });

  // Display .msg-wrapper when not empty
  if($('.msg').text() !== '') {

    $('.msg-wrapper').css('display', 'block').fadeOut(4000);
    // $('.modal-msg-wrapper').css('display', 'block').fadeOut(4000);

  }

  $('.more-less-btn').click(function() {

    $('.more-less-btn').toggleClass('fa-minus-square').toggleClass('fa-plus-square'); // Toggle .more-less-btn icon

    // Toggle .more-less-btn title
    if($('.more-less-btn').prop('title') === "Show less") {
      $('.more-less-btn').attr('title', "Show more");
    } else {
      $('.more-less-btn').attr('title', "Show less");
    }

    $('header, .edit-header, footer').slideToggle(500);
    // $('textarea').css('min-height', $('body').height() + '2px').toogleClass('no-margin-bottom');
    $('textarea').toggleClass('textarea-full');

  });

  $('.change-password-form').submit(function(e) {

    var pass1 = $('#password-input-1').val();
    var pass2 = $('#password-input-2').val();

    if(pass1 !== pass2) {

      var msg = "<span class='red-background'>Passwords did not match</span>";

      $('.modal-msg-wrapper').finish().html(msg).show().fadeOut(4000);
      e.preventDefault();

    }

  });

  // Clear modal when closed
  $(document).on('closed', '.remodal', function(e) {

    $('.remodal input').val("");
    $('.modal-msg-wrapper').finish();

  });

  var clipboard = new Clipboard('.copy-btn');

  clipboard.on('success', function(e) {

    $classes = 'hint--bottom hint--always hint--rounded';
    $ariaLabel = 'Copied!';

    e.clearSelection(); // Remove selection from target text

    $(e.trigger).addClass($classes); // Add hint tooltip classes
    $(e.trigger).attr('aria-label', $ariaLabel); // Add text to be displayed

    setTimeout(function() {

      $(e.trigger).removeClass($classes);
      $(e.trigger).removeAttr('aria-label');

    }, 2500); // Run after 2.5s

  });

});
