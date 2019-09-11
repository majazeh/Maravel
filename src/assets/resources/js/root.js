$.ajaxSetup(
    {
        headers:
        {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    }
);
$(document).ready(function () {
    $(document).trigger('statio:global:renderResponse', [$(document)]);
});
$(document).on('statio:global:renderResponse', function (event, base, context) {
    base.each(function () {
        require('./lijax').call(this, event, base, context);
        require('./select2').call(this, event, base, context);
    });
});
