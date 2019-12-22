module.exports = function (event, base, context) {
    $(".select2-select", this).each(function () {
        var options = {
            width: '100%',
            amdLanguageBase: '/vendor/js/i18n/',
            language: 'ar',
            minimumInputLength: 0,
            allowClear: $(this).is('[data-allowClear]') || $(this).is('.has-clear'),
            dir: "rtl",
            tags: $(this).is('.tag-type')
        };
        if (options.allowClear) {
            options.placeholder = {};
            options.placeholder.text = $('option', this).first().text();
            options.placeholder.id = $('option', this).first().attr('value');
        }
        if ($(this).is('[data-url]')) {
            var title = $(this).attr('data-title') || 'title';
            var _self = this;
            options.ajax = {
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
                        var sub_title_property = title_property;
                        if (sub_title_property.indexOf(' ') >= 0) {
                            var sub_title_properties = sub_title_property.split(' ');
                            for (var is = 0; is < sub_title_properties.length; is++) {
                                if (data[i][sub_title_properties[is]]) {
                                    sub_title_property = sub_title_properties[is];
                                    break;
                                }

                            }
                        }
                        result.results.push({
                            id: data[i][id_property],
                            text: data[i][sub_title_property],
                            all: data[i]
                        });
                    }
                    return result;
                },
                cache: false
            };
        }
        $(this).select2(options);
    });
}
