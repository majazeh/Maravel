(function(){
    var _event, _base, _context, init, ready, start;

    function draw()
    {
        _base.each(function () {
            require('./mapCall').call(this, _event, _base, _context);
        });
        start = false;
    }
    window.initMap = function(){
        init = true;
        if(ready && !start)
        {
            start = true;
            draw();
        }
    }
    window.readyMap = function (event, base, context) {
        _event = event;
        _base = base;
        _context = context;
        ready = true;
        if (init && !start) {
            start = true;
            draw();
        }
    }
})();
