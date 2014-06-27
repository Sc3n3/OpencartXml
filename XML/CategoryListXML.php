<?php
header("Content-Type: text/xml; charset=UTF-8");

include("../config.php");

mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die("Could not connect to MySQL.");
mysql_select_db(DB_DATABASE) or die("Could not find database.");
mysql_query("SET NAMES 'utf8'");

function getSubs($ID){
	$SQL = "SELECT a.category_id AS id , a.parent_id AS parent , (SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = a.category_id AND language_id = '1') AS name FROM ".DB_PREFIX."category AS a WHERE parent_id = '". $ID ."'";
	$Query = mysql_query($SQL);
	
	if(mysql_num_rows($Query) > 0){
		echo "<subcategories>\n";
		while($Data = mysql_fetch_assoc($Query)){
			echo "<category id=\"". $Data["id"] ."\" parent=\"". $ID ."\">\n";
			echo "<name>". $Data["name"] ."</name>\n";
			getSubs($Data["id"]);
			echo "</category>\n";
		}
		echo "</subcategories>\n";
	}
}

$SQL = "SELECT a.category_id AS id , a.parent_id AS parent , (SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = a.category_id AND language_id = '1') AS name FROM ".DB_PREFIX."category AS a WHERE parent_id = '0' ORDER BY id ASC";
$Query = mysql_query($SQL);

if(mysql_num_rows($Query) < 1) die;

$CatList = Array();
echo "<?xml version=\"1.0\" ?>\n";
echo "<categories>\n";
while($Data = mysql_fetch_assoc($Query)){
	echo "<category id=\"". $Data["id"] ."\" parent=\"0\">\n";
	echo "<name>". $Data["name"] ."</name>\n";
	getSubs($Data["id"]);
	echo "</category>\n";
}
echo "</categories>\n";
?>