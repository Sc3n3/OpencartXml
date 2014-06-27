<?php

set_time_limit(0);

include("../inc/Config.php");
$SQL = new SQLConnect();

include_once(MODEL_PATH ."/CategoryConnectorModel.php");
include_once(MODEL_PATH ."/ProductModel.php");
include_once(MODEL_PATH ."/../RollingCurl.php");

$ConMdl = new CategoryConnectorModel();
$AllList = $ConMdl->getSellConnectedCategoryList();

$Sellers = array_keys($AllList);

$PostURL = "http://". $_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['PHP_SELF']), "", $_SERVER['PHP_SELF']) ."updateSingleSellerProducts.php";

$rc = new RollingCurl();
$rc->window_size = 4;

foreach($Sellers as $SellerID){
	$URL = $PostURL ."?sellid=". $SellerID;
	$request = new RollingCurlRequest($URL);
	$rc->add($request);
}

$rc->execute();

?>