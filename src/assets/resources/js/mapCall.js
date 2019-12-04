module.exports = function (event, base, context){
    $("[data-map]", this).each(function () {
        var map_type = $(this).attr('data-type');
        if ($(this).attr('data-map') == 'span') {
            var center = $(this).attr('data-center');
            center = center.split(',');
            var mapCor = { lat: parseFloat(center[0]), lng: parseFloat(center[1]) };
            var location_lists = $('span', this);
        }
        else {
            var mapCor = { lat: parseFloat($('#location').attr('data-lat')), lng: parseFloat($('#location').attr('data-lng')) }
        }

        var icon = {
            url: "http://maps.google.com/mapfiles/ms/micons/blue-dot.png",
            size: new google.maps.Size(32, 32)
        }
        var map = new google.maps.Map(this, {
            center: mapCor,
            zoom: parseInt($(this).attr('data-zoom')) || 13,
            disableDefaultUI: true,
        })

        if ($(this).attr('data-map') == 'span') {
            heatmapData = [];
            location_lists.each(function () {
                var loc = $(this).attr('data-location');
                loc = loc.split(',');
                if (map_type == 'heatmap') {
                    heatmapData.push({
                        location: new google.maps.LatLng(parseFloat(loc[0]), parseFloat(loc[1])),
                        // weight: $(this).attr('data-weight') || 1
                    });
                    return;
                }
                var xicon = icon;
                if ($(this).attr('data-icon')) {
                    var sicon = $(this).attr('data-sicon').split(',');
                    xicon = {
                        url: $(this).attr('data-icon'),
                        size: new google.maps.Size(parseInt(sicon[0]), parseInt(sicon[1]))
                    }
                }
                var marker = new google.maps.Marker({
                    position: { lat: parseFloat(loc[0]), lng: parseFloat(loc[1]) },
                    map: map,
                    icon: xicon
                });
                var infoWindow = new google.maps.InfoWindow({
                    content: $(this).html()
                });

                google.maps.event.addListener(marker, 'click', function () {
                    infoWindow.open(map, marker);
                });
            });
            if (map_type == 'heatmap') {
                var heatmap = new google.maps.visualization.HeatmapLayer({
                    data: heatmapData,
                    radius: 20
                });
                heatmap.setMap(map);
            }
        }
        else {
            var markerCor = { lat: parseFloat($('#location').attr('data-lat')), lng: parseFloat($('#location').attr('data-lng')) };
            var marker = new google.maps.Marker({
                position: markerCor,
                map: map,
                draggable: true,
                icon: icon
            });
            marker.addListener('dragend', function () {
                $('#location').val(marker.getPosition().lat() + "," + marker.getPosition().lng());
            });
            $('#location').val(marker.getPosition().lat() + "," + marker.getPosition().lng());
        }
    });
}
