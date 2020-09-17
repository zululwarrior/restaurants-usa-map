function initialise() {
  //a function that fetches data from the database and loads the map
  function loadMap(state, category, name) {
    //fetch data
    $.ajax({
      type: 'POST',
      url: './mapdata.php',
      data: { state, category, name },
      success: (data) => {
        //if fetches data successfully, update data on the map
        myData = JSON.parse(data);
        updateMarkers();
      },
    });
  }

  //a function that fetches data from the database and loads the miscellaneous information (top 5 restaurants)
  function loadMisc(state, category, name) {
    $.ajax({
      type: 'POST',
      url: './misc_data.php',
      data: { state, category, name },
      success: (data) => {
        miscData = JSON.parse(data);
        updateMisc();
      },
    });
  }

  //create the tile layer with correct attribution
  var osmUrl =
    'https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}.png';
  var osmAttrib =
    'Map tiles &copy; <a href="http://bl.ocks.org/Xatpy/raw/854297419bd7eb3421d0/"> Carto</a>, under CC BY 3.0. Data by <a href="https://www.openstreetmap.org/">OpenStreetMap</a>, under ODbL';

  var osm = new L.TileLayer(osmUrl, {
    minZoom: 5,
    maxZoom: 16,
    attribution: osmAttrib,
    noWrap: true,
  });

  //initialise map
  map = new L.Map('mapid');

  map.setView(new L.LatLng(40.5, -74.0), 8);
  map.addLayer(osm);

  //variable that stores the current markers on the map
  var markers = [];

  //a function that clears all the markers on the map
  function clearMarkers() {
    for (i = 0; i < markers.length; i++) {
      map.removeLayer(markers[i]);
    }
  }

  //a function that updates the marker on the map
  function updateMarkers() {
    clearMarkers();
    //count amount of markers
    counter = 0;
    //count amount of showing markers
    showingCounter = 0;
    //reset the current markers on the map
    markers = [];

    //loop adds markers on the map with popup
    for (item in myData) {
      //only show amount of marker indicated by the range slider. initial value is 500
      if (counter < $('#range_slider').val()) {
        marker = L.circleMarker(
          [myData[item].latitude, myData[item].longitude],
          {
            color: '#ababab',
            radius: 2,
            title: 'test',
          }
        ).bindPopup(
          '<div style="text-align:center;"><b>' +
            myData[item].name +
            '</b></div><hr><b>Address:</b> ' +
            myData[item].address +
            '<br><b>Postal Code: </b>' +
            myData[item].postalcode +
            '<br><b>Categories: </b>' +
            myData[item].categories +
            '<div style="text-align:center;"><a href="' +
            myData[item].url +
            '"> Website</a></div>'
        );

        //marker should only be placed if it is within the frame
        if (map.getBounds().contains(marker.getLatLng())) {
          markers.push(marker);
          map.addLayer(marker);
          //increment showing marker if shown on map
          showingCounter++;
        }
      } else {
        //break out of the loop the amount of markers reaches the value of the range slider
        break;
      }
      //increment counter
      counter++;
    }
    $('#returned_result').empty();
    $('#returned_result').append(
      'Query returned ' +
        counter +
        ' results. Currently showing ' +
        showingCounter +
        ' results.'
    );
  }

  //updates the miscellaneous information
  function updateMisc() {
    //define the maximum amount of results to show
    var max = 5;

    //empty the div element
    $('#query_result').empty();

    //only show the data if the miscData isnt null
    if (miscData) {
      //loop through all values in miscData
      for (item in miscData) {
        //only append to the div element if the amount of item is less than max
        if (item < max) {
          $('#query_result').append(
            '<p>' + miscData[item].name + ': ' + miscData[item].count + '<p>'
          );
        } else {
          //break out of loop if item is equal to or more than max
          break;
        }
      }
    } else {
      //show "No results shown" if there miscData is empty
      $('#query_result').append('<h2> No results </h2>');
    }
  }

  //update markers when the user zooms or moves
  map.on('moveend', updateMarkers);
  map.on('zoomend', updateMarkers);

  //a function that updates the map and the miscellaneous section when you change the state value
  $('#filter_state').on('change', function (e) {
    e.preventDefault();
    loadMap(
      $('#filter_state').val(),
      $('#filter_category').val(),
      $('#filter_name').val()
    );
    loadMisc(
      $('#filter_state').val(),
      $('#filter_category').val(),
      $('#filter_name').val()
    );
  });

  //a function that updates the map and the miscellaneous section when you type in the input
  $('.filter_typing').on('keyup', function (e) {
    e.preventDefault();
    loadMap(
      $('#filter_state').val(),
      $('#filter_category').val(),
      $('#filter_name').val()
    );
    loadMisc(
      $('#filter_state').val(),
      $('#filter_category').val(),
      $('#filter_name').val()
    );
  });

  //a function that updates the map and the miscellaneous section when you change the slider value
  $('#range_slider').on('change', function (e) {
    e.preventDefault();
    $('#result').html(
      '<p>Number of results: ' + $('#range_slider').val() + '</p>'
    );
    updateMarkers();
  });

  //load and update map with values "All" since it is the default value
  loadMap('All');
  loadMisc('All');
}
