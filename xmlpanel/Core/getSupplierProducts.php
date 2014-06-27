<?php

set_time_limit(0);

include("../inc/Config.php");
$SQL = new SQLConnect();

include_once(MODEL_PATH ."/CategoryConnectorModel.php");
include_once(MODEL_PATH ."/ProductModel.php");
include_once(MODEL_PATH ."/../RollingCurl.php");

$ConMdl = new CategoryConnectorModel();
$AllList = $ConMdl->getSuppConnectedCategoryList();
$Suppliers = array_keys($AllList);

$PostURL = "http://". $_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['PHP_SELF']), "", $_SERVER['PHP_SELF']) ."getSingleSupplierProducts.php";

$rc = new RollingCurl();
$rc->window_size = 4;

foreach($Suppliers as $SuppID){
	$URL = $PostURL ."?spid=". $SuppID;
	$request = new RollingCurlRequest($URL);
	$rc->add($request);
}

$rc->execute();

?>