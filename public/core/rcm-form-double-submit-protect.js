/**
 * This ensures forms can only be submitted once and shows that it is loading via css
 */
$().ready(function () {
    $('body').delegate(
        'form',
        'submit',
        function () {
            var form = $(this);

            // Ignore form without action, like angular forms
            if(!form.attr('action')){
                return true;
            }

            if (form.hasClass('processing')) {
                return false;
            }

            form.addClass('processing');
            return true;
        }
    );
});