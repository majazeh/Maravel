module.exports = function (event, base, context) {
    $(".select2-select", this).each(function () {
        var title = $(this).attr('data-title') || 'title';
        var _self = this;
        var options = {
            amdLanguageBase: '/vendor/js/i18n/',
            language: 'ar',
            minimumInputLength: 0,
            allowClear: $(this).is('[data-allowClear]'),
            dir: "rtl",
            ajax: {
                delay: 250,
                url: $(this).attr('data-url'),
                dataType: 'json',
                quietMillis: 250,
                data: function (params) {
                    return {
                        q: params.term || ''
                    };
                },
                processResults: function (data) {
                    data = data.data || data;
                    var id_property = $(_self).attr('data-id') || 'id';
                    var title_property = $(_self).attr('data-title') || 'title';
                    var result = { results: [] };
                    if ($(_self).is('[data-allowClear]')) {
                        result.results.push({
                            id: '',
                            text: '-',
                            all: null
                        });
                    }
                    for (var i = 0; i < data.length; i++) {
                        result.results.push({
                            id: data[i][id_property],
                            text: data[i][title_property],
                            all: data[i]
                        });
                    }
                    return result;
                },
                cache: false
            }
        };
        $(this).select2(options);
    });
}
