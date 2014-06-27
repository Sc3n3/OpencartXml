<?php

function getTopCategory($CatID){

	$Query = mysql_query("SELECT category_id , parent_id FROM ".DB_PREFIX."category WHERE category_id = '". mysql_real_escape_string($CatID) ."'") or die(mysql_error());
	if(mysql_num_rows($Query) > 0){
		$Data = mysql_fetch_assoc($Query);
		
		if($Data["parent_id"] > 0){
			$Return = $Data["category_id"] .",";
			$Return .= getTopCategory($Data["parent_id"]);
			return $Return;
		} else {
			$Return = $Data["category_id"];
			return $Return;
		}
	} else {
		return "";
	}
}

function InsertProduct($Item, $TaxCode){
	$Query = mysql_query("INSERT INTO ".DB_PREFIX."product ( model , quantity , stock_status_id ,  tax_class_id ,  price , date_available , status , date_added , date_modified  ) VALUES ( '". $Item["StockCode"] ."' , '". $Item["Stock"] ."' , '5' , '". mysql_real_escape_string($TaxCode) ."' , '". $Item["Price"] ."' , NOW() , '1' , NOW() , NOW() )");
	$ID = mysql_insert_id();
	
	$List = explode(",", getTopCategory($Item["CatID"]));
	foreach($List as $CatID){
		$Query = mysql_query("INSERT INTO ".DB_PREFIX."product_to_category ( product_id , category_id ) VALUES ( '". $ID ."', '". $CatID ."')");
	}
	
	$Query = mysql_query("INSERT INTO ".DB_PREFIX."product_to_store ( product_id , store_id ) VALUES ( '". $ID ."', '0')");
	$Query = mysql_query("INSERT INTO ".DB_PREFIX."product_description ( product_id , language_id , name , description ) VALUES ( '". $ID ."' , '1' , '". mysql_real_escape_string($Item["Name"]) ."' , '". mysql_real_escape_string($Item["Info"]) ."' )");

	return $ID;
}

require_once("../config.php");
if(!defined("DB_HOSTNAME")) responser("OpenCart MySQL Data not Found!", "false");

mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or responser("Could not connect to MySQL.", "false");
mysql_select_db(DB_DATABASE) or responser("Could not find database.", "false");
mysql_query("SET NAMES 'utf8'");

$SQL = "SELECT product_id , category_id FROM ".DB_PREFIX."product_to_category";
$Query = mysql_query($SQL);

if(mysql_num_rows($Query) > 0){
	$Array = Array();
	while($Data = mysql_fetch_assoc($Query)){
		$CatID = $Data["category_id"];
		$ProID = $Data["product_id"];
		
		$Array[] = Array("category_id" => $Data["category_id"], "product_id" => $Data["product_id"]);
	}
}

foreach($Array as $Data){
	$CatID = $Data["category_id"];
	$ProID = $Data["product_id"];
		
	$List = explode(",", getTopCategory($CatID));
	foreach($List as $AddCatID){
		$Query = mysql_query("INSERT INTO ".DB_PREFIX."product_to_category ( product_id , category_id ) VALUES ( '". $ProID ."', '". $AddCatID ."')");
	}		
}

?>