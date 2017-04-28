<?php
// CSV utils
// for very simple databases


// reads a csv file and returns an array (two-dimensional)
// if a field contains 1-5 it replaces with 5 rows
function read_csv($fileName) {
  $csvFile = file($fileName);
  $i = 0;
  $csvLines = array();
  foreach ( $csvFile as $line ){
    $unProcessedLine = explode(",", $line);
    $processedLines = processMultiLine( $unProcessedLine, $i);
    $csvLines = array_merge($csvLines, $processedLines);
    $i += count($processedLines);
  }
  return $csvLines;
}

function processMultiLine($line, $index) {
  $multi = explode("-", $line[1]);
  if( count($multi) > 1 ) {
    for( $i = $multi[0]; $i <= $multi[1]; $i++ ) {
      $processedLines[$index] = array($line[0], $i, $line[2]);
      $index++;					 		
    }
  } else{
    $processedLines[$index] = $line;
  }
  return $processedLines;
}

function aaReadCsv($fileName) {
  $csv_file = file($fileName);
  for ( $i = 0; $i < count($csv_file); $i++ ){
    $csv_lines[$i] = explode(",",$csv_file[$i]);
    $aaCsvLines[$csv_lines[$i][0]] = trim($csv_lines[$i][1]);
  }
  return $aaCsvLines;
}


function read_csv_table($fileName) {
  $players = read_csv($fileName);
  for ( $i = 0; $i < count($players); $i++ ) {
    for ( $j = 0; $j < count($players[$i]); $j++ ) {
      echo "<br />";
    }
  }
}

?>