<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
      "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>ZTE mezszám</title>
  <link rel="Stylesheet" title="Default Stylesheet" media="Screen"
  href="zte.css" type="text/css"/>
</head>
<body>
<?

include "functions.php";


function init() {
  $seasons = read_csv("seasons.txt");
  $shirts = read_csv("shirts.txt");
}


?>
<? 
$func = $_GET["FUNC"];
if( $func == "PL" ) {
  if( isset($_GET["pid"]) ) {
    player($_GET["pid"]);
  } else {
    players(4);
  }
} else if( $func == "PST" ) {
  playerSeasonTable();
} else if( $func == "NST" ) {
  numberSeasonTable();
} else if( $func == "YR" ) {
  if( isset($_GET["sid"]) ) {
    season($_GET["sid"]);
  } else {
    seasons();
  }
} else if( $func == "NUM" ) {
  if( isset($_GET["num"]) ) {
    shirt($_GET["num"]);
  } else {
    shirts(4);
  }
} else {
?>
<p>Ez az adatbázis ZTE-játékosok mezszámát mutatja meg az elmúlt szezonokban. Két nagy kereszttáblázatot tartalmaz és 
lehet mezszám, játékos, szezon alapján is listázni.</p>
<p>Az adatbázis <a style="text-decoration: underline; color: blue" href="android/">letölthető</a> android telefonra/tabletre is</p>
<?
  echo "<ul>\n ";
  echo "<li><a href='mezszam.php?FUNC=YR'>Szezonok</a></li>\n";
  echo "<li><a href='mezszam.php?FUNC=PL'>Játékosok</a></li>\n";
  echo "<li><a href='mezszam.php?FUNC=NUM'>Mezszámok</a></li>\n";
  echo "<li><a href='mezszam.php?FUNC=PST'>Játékosok - Szezonok</a></li>\n";
  echo "<li><a href='mezszam.php?FUNC=NST'>Mezszámok - Szezonok</a></li>\n";
  echo "</ul>\n ";

}
?>
<hr/>
<p><i>Ez az adatbázis ZTE-játékosok mezszámát mutatja meg az elmúlt szezonokban. Ha
hibás/hiányzó adatot találsz kérlek <a style="text-decoration: underline; color: blue" href="mailto:andras.salamon@melda.info">értesíts</a>!
</i></p>
<p><i>Bár a táblázatokban néha szerepel 0-s mezszám, ez nyilván nem igazi mezszám. Ez azt jelenti, hogy a játékos az adott szezonban szerepelt, 
de nem tudom, hogy milyen mezszámmal. Hasonlóan nem igazi a negatív
    mezszám, ami azt jelenti, ellenőrizni kellene az adatot.</i></p>
<p><a href="http://validator.w3.org/check?uri=referer"><img
style="border: none;" src="http://www.w3.org/Icons/valid-xhtml11"
alt="Valid XHTML 1.1" height="31" width="88" /></a>
<!-- BEGIN WebSTAT Activation Code -->
<script type="text/javascript" src="http://hits.nextstat.com/cgi-bin/wsv2.cgi?39877"></script>
<noscript>
<p><a href="http://www.webstat.com">
<img src="http://hits.nextstat.com/scripts/wsb.php?ac=39877" style="border:0" alt="Website Metrics and Site Statistics by NextSTAT" /></a></p>
</noscript>
<!-- END WebSTAT Activation Code -->
</p>
</body>
</html>
