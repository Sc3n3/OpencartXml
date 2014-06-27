<?php
set_time_limit(0);

if(!$_GET["spid"]) die;
$SuppID = $_GET["spid"];

include("../inc/Config.php");
$Supp = new SQLConnect();

$DirName = $Supp->getSupplierDir($SuppID);
$SuppFile = "../inc/xml_files/". $DirName ."/Processor.php";

if(!file_exists($SuppFile)){
	die("Supplier File Not Found.");
}

include($SuppFile);
include("../inc/Class/CurlClass.php");
include(MODEL_PATH ."/CategoryConnectorModel.php");
include(MODEL_PATH ."/UserDataModel.php");
include(MODEL_PATH ."/ProductModel.php");

$ConnectMdl = new CategoryConnectorModel();
$List = $ConnectMdl->getSuppConnectedCategoryList($SuppID);
$List = $List[$SuppID];

if(!is_array($List)) die;

$Supp = new Supplier();

foreach($List as $Data){
	$Supp->getSupplierCategoryProducts($Data["DestID"], $Data["CatID"]);
}

?>