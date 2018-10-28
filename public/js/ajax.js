$(document).ready(function() {

  var timeout;

  $('.name-input').on('input', function() {

      $('.loading-wrapper').hide();
      $('.status-wrapper').hide();

      // Default submits button are hidden
      $('.submit-load').prop('disabled', true);
      $('.submit-create').prop('disabled', true);

      // Get value from inputs
      var nameInput = $('.name-input').val();

      // Add new regular expression variable
      var pattern = new RegExp('^[a-z0-9_-]{3,10}$');

      // Restart timeout countdown on keyup
      clearTimeout(timeout);

      // Check if input is valid
      if(pattern.test(nameInput) === true) {

        // Runs 1s after user stops typing
        timeout = setTimeout(function() {

          $('.loading-wrapper').show();
          $('.status-wrapper').hide();

          setTimeout(function() {

            // Ajax post
            $.post('../file/check-name', {name:nameInput}, function(data) {

              var status;

              if(data === 'true') {

                status = '<i class="match-status fa fa-check" aria-hidden="true"><span>Match found</span></i>';
                $('.submit-create').prop('disabled', true);
                $('.submit-load').prop('disabled', false);

              } else if(data === 'false') {

                status = '<i class="no-match-status fa fa-times" aria-hidden="true"><span>No match found</span></i>';
                $('.submit-create').prop('disabled', false);
                $('.submit-load').prop('disabled', true);

              }

              $('.loading-wrapper').hide();
              $('.status-wrapper').html(status).show();

            });

          }, 500);

        }, 1000);

      }

  });

});

function jqAjaxUpdateText() {

  var timeout;

    // Restart timeout countdown on keyup
    clearTimeout(timeout);

    // Get value from inputs
    var nameInput = $('.update-name-input').val();
    var textInput = $('.text-input').val();

    // Runs 2s after user stops typing
    timeout = setTimeout(function() {

      $('.loading-wrapper').show();

      setTimeout(function() {

        // Ajax post
        $.post('../update-text', {name:nameInput, text:textInput}, function() {

          var savedStatus = '<i class="saved-status fa fa-check-circle-o fa-2x" aria-hidden="true"><span>Saved</span></i>';

          $('.loading-wrapper').hide();
          $('.status-wrapper').html(savedStatus).show().fadeOut(2000);

        });

      }, 500);

    }, 2000);

}
