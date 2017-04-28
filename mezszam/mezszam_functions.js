    var playersData;
    var playersDataLoaded;
    var seasonsData;
    var seasonsDataLoaded;

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    function arrayValue(array, id) {
      for( var i=0; i<array.length; ++i ) {
        if( array[i][0] == id ) {
	  return array[i][1];
        }
      }
      return "Ismeretlen";
    }

    function flattenShirts(shirtsData) {
      var shirts = [];
      for( var i=0; i<shirtsData.length; ++i ) {
        var ss=shirtsData[i][1].split('-');
        if( ss.length == 1 ) {
          shirts.push( [shirtsData[i][0], shirtsData[i][1], shirtsData[i][2]] );
	} else {
          for( var j=ss[0]; j<=ss[1]; ++j ) {
            shirts.push( [shirtsData[i][0], j, shirtsData[i][2]] );
          }
        }
      }
      return shirts;
    }

    function shirtFilter(shirtsData, shirtNumber, searchIndex, sortIndex) {
      var shirts = [];
      for( var i=0; i<shirtsData.length; ++i ) {
        if( shirtsData[i][searchIndex]==shirtNumber) {          
          shirts.push( [shirtsData[i][0],shirtsData[i][1], shirtsData[i][2]] );
        }
      }
      return shirts.sort(function(a,b,c) {
        return a[sortIndex]-b[sortIndex]
      });
    }

    // http://stackoverflow.com/a/14991797/21348
    function parseCSV(str) {
    var arr = [];
    var quote = false;  // true means we're inside a quoted field

    // iterate over each character, keep track of current row and column (of the returned array)
    for (var row = col = c = 0; c < str.length; c++) {
        var cc = str[c], nc = str[c+1];        // current character, next character
        arr[row] = arr[row] || [];             // create a new row if necessary
        arr[row][col] = arr[row][col] || '';   // create a new column (start with empty string) if necessary

        // If the current character is a quotation mark, and we're inside a
        // quoted field, and the next character is also a quotation mark,
        // add a quotation mark to the current column and skip the next character
        if (cc == '"' && quote && nc == '"') { arr[row][col] += cc; ++c; continue; }  

        // If it's just one quotation mark, begin/end quoted field
        if (cc == '"') { quote = !quote; continue; }

        // If it's a comma and we're not in a quoted field, move on to the next column
        if (cc == ',' && !quote) { ++col; continue; }

        // If it's a newline and we're not in a quoted field, move on to the next
        // row and move to column 0 of that new row
        if (cc == '\n' && !quote) { ++row; col = 0; continue; }

        // Otherwise, append the current character to the current column
        arr[row][col] += cc;
    }
    return arr;
    }    

$(document).ready(function() {
//    console.log('mf ready');
    var fload = $("footer").load("mezszam_footer.html");
    var hload = $("header").load("mezszam_header.html");
    $.when(fload, hload).done(function() {
	var playersName = [];
	playersDataLoaded = $.get('players.csv', function(data) {
	    playersData = parseCSV(data);
	});
	seasonsDataLoaded = $.get('seasons.csv', function(data) {
	    seasonsData = parseCSV(data);
	});

	$.when(playersDataLoaded).done( function(data) {
	    playersData = parseCSV(data);
	    playersData = playersData.sort(function(a,b) {
		return a[1] > b[1] ? 1 : 0;
	    });
//	    console.log('mf playersData: '+playersData);
	    for( var i=0; i<playersData.length; ++i ) {
		playersName.push( { label: playersData[i][1], value: playersData[i][1], id: playersData[i][0] } );
	    }
	    $("#tags").autocomplete({
		source: playersName,
		select: function( event, ui ) { console.log('select' + ui.item.id);window.location.href='player.html?pid='+ui.item.id}
	    });
	});
	$.when(seasonsDataLoaded).done( function(data) {
	    seasonsData = parseCSV(data);
//	    playersData = playersData.sort(function(a,b) {
//		return a[1] > b[1] ? 1 : 0;
//	    });
	});	
    });

});
