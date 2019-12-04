module.exports = function (event, base, context) {
    $('.filtqu', this).each(function () {
        if ($(this).is('.tf-changed'))
        {
            $(this).data('defaultValue', $(this).val());
        }
        var _self = this
        var fire = false;
        $(this).attr('class').split(' ').forEach(function (_class) {
            var trigger = _class.match(/^ft-(.*)$/);
            if (trigger)
            {
                fire = true;
                if (/^\d+$/.test(trigger[1]))
                {
                    var TimeOut = null;
                    $(_self).on('keyup', function(){
                        if (TimeOut)
                            clearTimeout(TimeOut);
                        TimeOut = setTimeout(function () {
                            filtqu.call(_self);
                        }, parseInt(trigger[1]));
                    });
                }
                else
                {
                    $(_self).on(trigger[1], filtqu);
                }
            }
        });
        if(!fire)
        {
            $(_self).on('change', filtqu);
        }
    });

    function filtqu()
    {
        if ($(this).data('defaultValue') != undefined && $(this).data('defaultValue') == $(this).val()){
            return true;
        }
        if ($(this).is('.tf-changed'))
        {
            $(this).data('defaultValue', $(this).val());
        }
        var vals = {};
        $(".filtqu").each(function () {
            var def = $(this).attr('data-default');
            if(!def){
                if ($(this).is('select'))
                {
                    def = $('option', this).eq(0).val();
                }
            }
            if (def != $(this).val())
            {
                vals[$(this).attr('name')] = $(this).val();
            }
        });
        var loc = location.pathname;
        var qu = [];
        for (var key in vals) {
            qu.push(key + '=' +vals[key]);
        }
        new Statio({
            url: loc + (qu.length ? '?' + qu.join('&') : ''),
            context: $(this),
            ajax : {
                headers:
                {
                    'Data-Xhr-Base': 'filter',
                }
            }
        });
    }
}
