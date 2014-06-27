<?php
ob_start();
session_start();
header("Content-Type: text/html; charset=UTF-8");

$mtime = microtime(true);
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$start_time = $mtime;

$Pass = "!1905!gs";
if(!$_SESSION["OK"]){
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["q"] == $Pass){
		$_SESSION["OK"] = true;
		header("Location: ?");
	} else {
		echo "<form method=\"post\">";
		echo "<input type=\"text\" name=\"q\" />";
		echo "<input type=\"submit\" value=\"Gönder\" />";
		echo "</form>";
	}
	die;
}

include("inc/Config.php");
$SQL = new SQLConnect();
include("inc/Theme.php");

$sayfa = $_GET["page"];
$do    = $_GET["file"]; 

if(empty($sayfa)){
	$sayfa = "index";
}
if(isset($do)){
	if(!is_file("Interface/pages/$sayfa/$do.php") || preg_match("/[.\/]/", $sayfa) || preg_match("/[.\/]/", $do)){
		die("<center>Hatalı Adres.Geri Dönüp Tekrar Deneyin.</center>");
	} else {
		include("Interface/pages/$sayfa/$do.php");
	}
} else {
	if(!is_file("Interface/pages/$sayfa/page.php") || preg_match("/[.\/]/", $sayfa)){
		die("<center>Hatalı Adres.Geri Dönüp Tekrar Deneyin.</center>");
	} else {
		include("Interface/pages/$sayfa/page.php");
	}
}

mysql_close();

$mtime = microtime(true);
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$end_time = $mtime;

$total_time = ($end_time - $start_time);
echo "<!-- Total Time: ". $total_time ." sn. -->";

?>