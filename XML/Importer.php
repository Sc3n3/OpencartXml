<?php

function responser($Text, $Status = true){
	echo json_encode(array("Message" => $Text, "Status" => $Status));
	die;
}

if($_SERVER["REQUEST_METHOD"] != "POST") die;
if(!$_POST["Data"]) die;
if(!is_array($Data = json_decode(base64_decode($_POST["Data"]), true))) die;

set_time_limit(0);

function ImageDownload($URL, $Path){

    $ch = curl_init($URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER , TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; tr; rv:1.9.2) Gecko/20100115 Firefox/3.6');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	$File = curl_exec($ch);
	
	$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($retcode == "200"){
		$fp = fopen($Path, 'w+');
		fwrite($fp, $File);
		fclose($fp);
		curl_close($ch);
		
		return true;
	} else {
		curl_close($ch);
		return false;
	}
}

function getTaxRateID($Tax){
	$Query = mysql_query("SELECT tax_rate_id AS id, rate FROM ".DB_PREFIX."tax_rate");
	
	$Return = "";
	if(mysql_num_rows($Query) > 0){
		while($Data = mysql_fetch_assoc($Query)){
			if($Data["rate"] == $Tax){
				$Return = $Data["id"];
				break;
			}
		}
	}
	
	if($Return != ""){
		$Query = mysql_query("SELECT tax_class_id AS id FROM ".DB_PREFIX."tax_rule WHERE tax_rate_id = '". $Return ."' AND priority = '0' LIMIT 1");
		if(mysql_num_rows($Query) > 0){
			$Data = mysql_fetch_assoc($Query);
			$Return = $Data["id"];
		} else {
			$Return = "";
		}
	}
	
	return $Return;
}

function getCatDirName($ID){
	$Query = mysql_query("SELECT name FROM ".DB_PREFIX."category_description WHERE category_id = '". mysql_real_escape_string($ID) ."' AND language_id = '1'");

	$Return = "no_category";
	if(mysql_num_rows($Query) > 0){
		$Data = mysql_fetch_assoc($Query);
		$Return = $Data["name"];
	}
	
	return strtolower(iconv("UTF-8", "ISO-8859-1//IGNORE", preg_replace("/[^a-z]/i", "", $Return)));
}

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

function UpdateProduct($Item){
	$SQL  = "UPDATE ".DB_PREFIX."product SET ";
	$SQL .= "model = '". $Item["StockCode"] ."' AND ";
	$SQL .= "quantity = '". $Item["Stock"] ."' AND ";
	$SQL .= "price = '". $Item["Price"] ."' AND ";
	$SQL .= "status = '1' ";
	$SQL .= "WHERE product_id = '". $Item["ProductID"] ."'";

	$Query  = mysql_query($SQL);
	$Query2 = mysql_query("UPDATE ".DB_PREFIX."product_description SET language_id = '1' AND name = '". mysql_real_escape_string($Item["Name"]) ."' AND description = '". mysql_real_escape_string($Item["Info"]) ."' WHERE product_id = '". $Item["ProductID"] ."'");
	
	if($Query && $Query2){
		return true;
	} else {
		return false;
	}
}

function addImage($Product, $Name, $Image, $Category, $Cover){
	if($Image == "" && $Cover == true){
		$Query = mysql_query("UPDATE ".DB_PREFIX."product SET image = '". mysql_real_escape_string("data/yok.jpg") ."' WHERE product_id = '". $Product ."'") or die(mysql_error());
	
		return;
	}
	
	if($Image == "" && $Cover == false) return; 
	
	if(!is_dir(DIR_IMAGE ."data/". $Category)) mkdir(DIR_IMAGE ."data/". $Category, 0777);
	
	$Path = pathinfo(DIR_IMAGE ."data/". $Category ."/". basename($Image));
	$FileName = strtolower(iconv("UTF-8", "ISO-8859-1//IGNORE", preg_replace("/[^a-z]/i", "",$Name)));
	
	$File = "data/". $Category ."/". $FileName ."_". time() .".". $Path["extension"];
	if(!ImageDownload($Image, DIR_IMAGE . $File)){
		if($Cover == false) return false;
		
		$File = "data/yok.jpg";
	}
	
	if($Cover == true){
		$Query = mysql_query("UPDATE ".DB_PREFIX."product SET image = '". mysql_real_escape_string($File) ."' WHERE product_id = '". $Product ."'") or die(mysql_error());
	} else {
		$Query = mysql_query("INSERT INTO ".DB_PREFIX."product_image ( product_id , image ) VALUES ( '". $Product ."' , '". mysql_real_escape_string($File) ."' )") or die(mysql_error());
	}
}

require_once("../config.php");
if(!defined("DB_HOSTNAME")) responser("OpenCart MySQL Data not Found!", "false");

mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or responser("Could not connect to MySQL.", "false");
mysql_select_db(DB_DATABASE) or responser("Could not find database.", "false");
mysql_query("SET NAMES 'utf8'");

if(isset($Data["ProductID"])){
	$ItemID = $Data["ProductID"];
	$Update = UpdateProduct($Data);
	
	if(!$Update) responser("Could not update.", "false");
} else {
	$TaxID = getTaxRateID($Data["Tax"]);
	$CatDirName = getCatDirName($Data["CatID"]);
	$ItemID = InsertProduct($Data, $TaxID);

	$i = 0;
	foreach($Data["Image"] as $Key => $Image){
		if($i == 0){
			$Cover = true;
			$i = 1;
		} else {
			$Cover = false;
		}
		
		addImage($ItemID, $Data["Name"], $Image, $CatDirName, $Cover);
	}
}

responser($ItemID, "true");

?>