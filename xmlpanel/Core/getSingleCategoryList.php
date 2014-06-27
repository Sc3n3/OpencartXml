<?php

if(!$_GET["spid"]) die;
$SuppID = $_GET["spid"];

set_time_limit(0);

include("../inc/Config.php");
$Supp = new SQLConnect();
$DirName = $Supp->getSupplierDir($SuppID);
$SuppFile = "../inc/xml_files/". $DirName ."/Processor.php";

if(!file_exists($SuppFile)){
	die("Supplier File Not Found.");
}

include($SuppFile);
include("../inc/Class/CurlClass.php");
include("../inc/Class/Model/CategoryModel.php");
include("../inc/Class/Model/UserDataModel.php");

$Supp = new Supplier();
$Supp->getSupplierCategories();

?>