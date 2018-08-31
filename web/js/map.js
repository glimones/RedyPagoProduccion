var map;
$(document).ready(function(){
  map = new GMaps({
    div: '#mapa_puntos',
    lat: -1.366163279696882,
    lng: -78.29461203363576,
    zoom: 7,
	  minZoom: 7,
	  maxZoom: 19
  });

  // google.maps.event.addListenerOnce(map.map, 'idle', function () {
  //        var currentCenter = map.map.getCenter();
  //        google.maps.event.trigger(map.map, 'resize');
  //        map.map.setCenter(currentCenter);

  //        map.map.setZoom(15);
  //    });

	

	$('#ModalMapa').on('show.bs.modal', function (e) {
		var base_url = $("#base_url").val();
	    google.maps.event.trigger(map.map, 'resize');
    	$.ajax({
        	type: "post",
          	url: base_url+"/ajax/localizaciones",
          	dataType: 'json',
          	data:{'id':$(this).val()},
          	beforeSend: function(){
            
          	},
          	success: function(data){
          		var markers_data = [];
          		$.each(data, function(){
          			markers_data.push({
	    		        lat: this.lat,
	    		        lng: this.lon,
	    		        // icon: iconMarker,
	    		        infoWindow: {
	    		          content: "<h2>"+this.nombre+"</h2><p>"+this.direccion+"</p>"
	    		        }
	    		    });
          		});
          		map.addMarkers(markers_data);
          	},
          	async: false,
          	cache: false
      	});
	});

});