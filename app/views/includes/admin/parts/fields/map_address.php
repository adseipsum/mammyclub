<script type="text/javascript" src="<?=site_js("jquery/jquery.autocomplete.js");?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=ru"></script>

<div class="map" id="map" style="height: 300px;
                                 width: 600px;
                                 padding: 0px;">
</div>

<input type="hidden" name="place_lat" value="" id="place_lat">
<input type="hidden" name="place_lng" value="" id="place_lng">

<script>
  $(document).ready(function() {

    $(".js-input-address").closest(".group").hide();
    
    $('#region').change(function() {
      var rId = $(this).val();
      if (rId == '') {
        return false;
      }
      $.get("<?=admin_site_url('brvalue/ajax_region');?>", {rId: rId})
        .done(function(data) {
          var citiesArr = $.parseJSON(data);
          if(citiesArr.length > 0) {
            var html = '<option value=""><?=lang('doctor.additional_information.please_choose_city')?></option>';
            for (var i = 0; i < citiesArr.length; ++i) {
              html += '<option value="' + citiesArr[i][0] + '">' + citiesArr[i][1] + '</option>';
            }
            
            $("#city").html(html);
            $("#city").val('').trigger("liszt:updated");
          }

      }, "json");
    });

    $('#city').change(function() {
      $(".js-input-address").closest(".group").show();
    });
    
    // Initialize autocomplete
    initAutocomplete = function() {
      var autocomplete = $('#address').ajaxautocomplete({serviceUrl: '<?=site_url('get-addresses');?>',
                                                  deferRequestBy: 300});

      autocomplete.setOptions({ width: "300px" });
      
      $('#city').change(function() {
        var $option = $(this).find("option:selected");
        
        autocomplete.setParams('city', $option.html());
     });
    };

    // Map option
    var geocoder = new google.maps.Geocoder();
    var mapOptions = {
      zoom: 13,
      center: new google.maps.LatLng(50.455100, 30.516876)
    };

    var map;
    
    // Initialization map
    initMap = function() {
      map = new google.maps.Map(document.getElementById('map'),
          mapOptions);
      marker = new google.maps.Marker({
        map: map
      });
      infowindows = new google.maps.InfoWindow();

      google.maps.event.addListener(map, 'click', function(event) {


        var lat = event.latLng.lat();
        var lng = event.latLng.lng();
        var latlng = new google.maps.LatLng(lat, lng);
        
        geocoder.geocode( {'latLng': latlng}, function(results, status) {

          var res = results[0];


          numb = '';
          street = '';
          city = '';

//           console.log(res.address_components);

          // foreach res.address_components
          
          res.address_components.forEach(function(element, index, array) {
            
            element.types.forEach(function(type) {
              
              if (type == 'route') {
                street = element.long_name;
              }
              if (type == 'street_number') {
                numb = element.long_name;
              }
              if (type == 'locality') {
                city = element.long_name;
              }
            });
            
          });
          
          var cityId = $('#city option').filter(function () { return $(this).html() == city; }).val();

          $.get("<?=admin_site_url('brvalue/ajax_region_by_city');?>", {cityId: cityId})
          .done(function(data) {
            var region = $.parseJSON(data);
            
            if(region.regionId.length > 0) {
              $("#region").val(region.regionId).trigger("liszt:updated");
            }

          }, "json");
          
          $('#address').val(street);
          $('#house').val(numb);
          $("#city").val(cityId).trigger("liszt:updated");
          $("#city").change();
          
        });
         
        
      });
    };




    // Update map
    updateMap = function() {
      infowindows.close();
      marker.setVisible(false);
      var place = '';
      
      var address = '';
      var $cityId = $('#city');

      address = $cityId.find("option:selected").html() + ',';

      workStreet = $('#address').val();
      
      if ( workStreet != null ) address = address + workStreet;

      workHouse = $('#house').val();
      if ( workHouse != null ) address = address + ',' + workHouse;

      address = 'Украина, ' + address;
      
      geocoder.geocode( {'address': address, 'language': 'ru'}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          place = results[0];
        }
        
        if (!place.geometry) {
          return;
        }

        $('#place_lat').val(place.geometry.location.lat());
        $('#place_lng').val(place.geometry.location.lng());
        
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
          map.fitBounds(place.geometry.viewport);
        } else {
          map.setCenter(place.geometry.location);
          map.setZoom(17);  // Why 17? Because it looks good.
        }
        
        marker.setIcon(/** @type {google.maps.Icon} */({
          url: '',
//           // This marker is 20 pixels wide by 32 pixels tall.
//           size: new google.maps.Size(20, 32),
//           // The origin for this image is 0,0.
//           origin: new google.maps.Point(0,0),
//           // The anchor for this image is the base of the flagpole at 0,32.
//           anchor: new google.maps.Point(0, 32)
        }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
 
        var address = '';
        if (place.address_components) {
          address = [
            (place.address_components[1] && place.address_components[1].short_name || ''),
            (place.address_components[0] && place.address_components[0].short_name || ''),
            (place.address_components[2] && place.address_components[2].short_name || '')
          ].join(', ');
        }

        infowindows.setContent('<div>' + address);
        infowindows.open(map, marker);
      });

    };

    $(window).keydown(function(event) {
      if(event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });

    $("#city").change(function() {
      updateMap();
    });

    $("#house").keyup(function() {
      updateMap();
    });
    
    initAutocomplete();
    initMap();

    if ($("#city").val() != '') {
      updateMap();
      $(".js-input-address").closest(".group").show();
    }
  });
</script>