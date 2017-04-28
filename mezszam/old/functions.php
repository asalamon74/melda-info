<?

include "csvutils.php";

// List of players 
function players( $colNum ) {
  echo "<h1>Játékosok</h1>";
  $players = aaReadCsv("../players.csv");
  asort($players);
//  echo tableHead();
  echo "<table class='zte'><tr>";
  $col = 0;
  foreach ($players as $pid => $name) {
    if( ++$col > $colNum ) {
      echo "</tr><tr>";
      $col = 1;
    }
    echo "<td><a href='mezszam.php?FUNC=PL&amp;pid=" . $pid . "'>" . $name . "</a></td>";
  }
  while( ++$col <= $colNum ) {
    echo "<td>&nbsp;</td>";
  }
  echo "</tr></table>";
//  echo tableTail();
}

// Link to player data
function playerLink($pid, $pname) {
  return "<a href='mezszam.php?FUNC=PL&amp;pid=" . $pid . "'>" . $pname . "</a>";
}

// Link to season data
function seasonLink($sid, $sname) {
  return "<a href='mezszam.php?FUNC=YR&amp;sid=" . $sid . "'>" . $sname . "</a>";
}

// Link to season data
function shirtLink($num) {
  return "<a href='mezszam.php?FUNC=NUM&amp;num=" . $num . "'>" . $num . "</a>";
}


// Data of a single player
function player( $pid ) {
  $players = aaReadCsv("../players.csv");
  $shirts = read_csv("../shirts.csv");
  $seasons = aaReadCsv("../seasons.csv");
  echo "<h1>" . $players[$pid] . "</h1>";
  
  for( $i = 0; $i < count($shirts); ++$i ) {
    if( $shirts[$i][0] == $pid ) {
      $pshirts[$shirts[$i][1]] = $shirts[$i][2];
      
    }
  }
  ksort( $pshirts, SORT_NUMERIC);
  echo "<table class='zte'>";
  foreach( $pshirts as $sid => $num ) {
      echo "<tr><td>" . seasonLink( $sid, $seasons[ $sid ]) . "</td><td>" . shirtLink( $num ) . "</td></tr>\n";
  }
  echo "</table>";
}

// Returns all the shirt numbers 
function getShirts( ) {
  $shirts = read_csv("../shirts.csv");
  $asnum = 0;
  for( $i = 0; $i < count($shirts); ++$i ) {
      $activeShirts[$asnum++] = $shirts[$i][2];
  }
  $uqActiveShirts = array_unique($activeShirts);
  asort($uqActiveShirts, SORT_NUMERIC);
  return array_values($uqActiveShirts);
}

// List of shirt numbers
function shirts($colNum) {
  echo "<h1>Mezszámok</h1>\n";
  $shirts = getShirts();
  echo "<table class='zte'><tr>";
  $col = 0;
  foreach( $shirts as $num ) {
    $num = str_replace("\n", "", $num);
    if( ++$col > $colNum ) {
      echo "</tr><tr>";
      $col = 1;
    }
    echo "<td>" . shirtLink( $num ) . "</td>\n";
  }
  for( ; $col< $colNum; ++$col ) {
    echo "<td>&nbsp;</td>";
  }
  echo "</tr></table>\n";
}

function shirt( $num ) {
  echo "<h1
  style=\"height:101px;color:white;background:url(tshirt.png)
  no-repeat center;\"><p style=\"position:relative;top:0.3em\">$num</p></h1>\n";
  $seasons = aaReadCsv("../seasons.csv");
  $players = aaReadCsv("../players.csv");
  echo "<table class='zte'>";
  foreach( $seasons as $sid => $sname ) {
    $pids = getPlayerWithShirtNumber( $sid, intval($num)  );
    foreach( $pids as $pid ) {
      echo "<tr><td>" . seasonLink($sid, $seasons[$sid]) . "</td><td>" . playerLink($pid, $players[$pid]) . "</td></tr>\n";
    }
  }
  echo "</table>";
}

// Data of a single season
function season( $sid ) {
  $players = aaReadCsv("../players.csv");
  $shirts = read_csv("../shirts.csv");
  $seasons = aaReadCsv("../seasons.csv");
  echo "<h1>" . seasonLink( $sid-1, "&lt;  ") . $seasons[ $sid ]
  . seasonLink( $sid+1, "   >") . "</h1>";
  
  echo "<table>";
  for( $i = 0; $i < count($shirts); ++$i ) {
    if( $shirts[$i][1] == $sid ) {
      $yrShirts[$shirts[$i][0]] = $shirts[$i][2];
    }
  }
  if( $yrShirts > 0 ) {
    asort( $yrShirts, SORT_NUMERIC );
    foreach( $yrShirts as $pid => $num ) {
      echo "<tr><td align='right'>" . shirtLink( $num ) . "</td><td>" . playerLink( $pid, $players[$pid] ) . "</td></tr>";
    }
  }
  echo "</table>";
}

// List of seasons
function seasons() {
  echo "<h1>Szezonok</h1>";
  $seasons = aaReadCsv("../seasons.csv");
  echo "<table class='zte'>";
  foreach( $seasons as $sid => $sname ) {
    echo "<tr><td><a href='mezszam.php?FUNC=YR&amp;sid=" . $sid . "'>" . $sname . "</a></td></tr>";
  }
  echo "</table>";
}

$shirtFile;

function initShirtFile() {
  global $shirtFile;
  if( count($shirtFile) == 0 ) {
    $shirtFile = read_csv("../shirts.csv");
  }
}


// Get the shirt number of a player in a seasn
function getShirtNumber( $pid, $sid ) {
  global $shirtFile;
  initShirtFile();
  $shirts = $shirtFile;
  for( $i=0; $i < count($shirts); ++$i ) {
    if( $shirts[$i][0] == $pid && 
	$shirts[$i][1] == $sid ) {
      //      return $hirts[$i][1];
      $num = $shirts[$i][2];
      return $num;
    }
  }
}

// Find the player with the specified shirt number in a season
// may return more than one player
function getPlayerWithShirtNumber( $sid, $num ) {
  global $shirtFile;
  initShirtFile();
  //$shirts = read_csv("../shirts.csv");
  $shirts = $shirtFile;
  $ret_array = array();
  for( $i=0; $i < count($shirts); ++$i ) {
    if( $shirts[$i][1] == $sid && 
	$shirts[$i][2] == $num ) {
      array_push( $ret_array, $shirts[$i][0] );
//      return $shirts[$i][0];
    }
  }
  return $ret_array;
}

/**
 * Simple function to replicate PHP 5 behaviour
 */
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

// Player/Season table
function playerSeasonTable() {
//  $time_start = microtime_float();
  echo "<h1>Játékos-Szezon táblázat</h1>";
  $players = aaReadCsv("../players.csv");
  asort($players);
  $seasons = aaReadCsv("../seasons.csv");
//  echo tableHead();
  echo "<table class='zte'><tr><td>&nbsp;</td>";
  foreach( $seasons as $sid => $sname ) {
    echo "<td>" . seasonLink($sid, $sname) . "</td>\n";
  }
  echo "</tr>";
  foreach( $players as $pid => $pname ) {
    echo "<tr><td>" . playerLink($pid, $pname)  . "</td>";
    foreach( $seasons as $sid => $sname ) {
      $num = getShirtNumber( $pid, $sid );
      if( isset( $num ) ) {
	$tdvalue = shirtLink($num);
      } else {
	$tdvalue = "&nbsp;";
      }
      echo "<td>$tdvalue</td>\n";

    }
    echo "</tr>";    

  }
  echo "</table>";
//  echo tableTail();
//  $time_end = microtime_float();
//  $time = 1000*($time_end - $time_start);
//  echo "last time: $time\n";
}

// Shirt number / Season table
function numberSeasonTable() {
  echo "<h1>Mezszám-Szezon táblázat</h1>";
  $players = aaReadCsv("../players.csv");
  $seasons = aaReadCsv("../seasons.csv");
  echo "<table class='zte'><tr><td></td>";
  foreach( $seasons as $sid => $sname ) {
    echo "<td>" . seasonLink( $sid, $sname) . "</td>\n";
  }
  echo "</tr>";
  $activeShirts = getShirts( );
  foreach( $activeShirts as $num ) {
    echo "<tr><td>" . shirtLink($num)  . "</td>";
    foreach( $seasons as $sid => $sname ) {
      $pids = getPlayerWithShirtNumber( $sid, $num );
      $tdvalue = "";
      foreach( $pids as $pid ) {
	$tdvalue = $tdvalue . (empty($tdvalue) ? "" : ", ") . playerLink ($pid, $players[$pid]  );
      }
      echo "<td>$tdvalue</td>\n";
    }
    echo "</tr>";    
  }
  echo "</table>";
}

?>
