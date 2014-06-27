<?php

if(!$_GET["sellid"]) die;

set_time_limit(0);

include("../inc/Config.php");
$SQL = new SQLConnect();

include_once(MODEL_PATH ."/CategoryConnectorModel.php");
include_once(MODEL_PATH ."/ProductModel.php");
include_once(MODEL_PATH ."/SchemaModel.php");
include_once(MODEL_PATH ."/UserDataModel.php");
include_once("../inc/Class/CurlClass.php");

$DirName = $SQL->getSupplierDir($_GET["sellid"]);
$SuppFile = "../inc/xml_files/". $DirName ."/Processor.php";

if(!is_file($SuppFile)) die;
include($SuppFile);

$ConMdl = new CategoryConnectorModel();
$AllList = $ConMdl->getSellConnectedCategoryList($_GET["sellid"]);
$AllList = $AllList[$_GET["sellid"]];

if(count($AllList) < 1) die;

foreach($AllList as $Cats){
	$ProductMdl = new ProductModel();
	$Products = $ProductMdl->setCatID($Cats["SuppCatID"])->getCategoryProducts();

	if(count($Products) < 1) continue;
	
	$PriceSchema = $Cats["SchemaID"];
	
	foreach($Products as $ProductID){
		if(!$ProductMdl->getProduct($ProductID)) continue;

		if($ProductMdl->getOnSale() == "0") continue;

		$SchemaMdl = new SchemaModel();
		$SchemaMdl->getSchema($PriceSchema);
		$NewPrice = $SchemaMdl->calculatePrice($ProductMdl->getPrice());

		if($SchemaMdl->getMinSellPrice() > $NewPrice) continue;
		if($SchemaMdl->getMinSellStock() > $ProductMdl->getStock()) continue;
		
		$ProductMdl->debug();
		
		$Supp = new Supplier();
		$Supp->setCatID($Cats["DestID"]);
		$Supp->setPrice($NewPrice);
		$Supp->setName($ProductMdl->getName());
		$Supp->setInfo($ProductMdl->getInfo());
		$Supp->setStock($ProductMdl->getStock());
		$Supp->setTax($ProductMdl->getTax());
		$Supp->setImage($ProductMdl->getImage());
		$Supp->setStockCode($ProductMdl->getStockID());

		$AddedID = $ProductMdl->isAdded($_GET["sellid"]);
		if($AddedID != false){
			$Supp->setProductID($AddedID);
		}	
		
		$SellerItemID = $Supp->sendProduct();
		
		if($SellerItemID != false){
			$ProductMdl->saveAdded($ProductID, $_GET["sellid"], $SellerItemID);
		}
	}
	
}

?>