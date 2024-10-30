(function($)
{
    $.validator.addMethod(
        'one-required',
        function(value, element)
        {
            // Determine the field ID
            var id = $(element).attr('id').match(/^field_([0-9]+)_[0-9]+$/)[1]

            // Find all inputs with the proper class that are checked.
            return $('input.ccms-field-' + id + ':checked').length;
        },
        'Check at least one'
    );

})(jQuery);
