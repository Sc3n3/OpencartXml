<?php

include_once(MODEL_PATH. "/CategoryModel.php");
$Destination = Array("Seller", "Supplier");

$CatMdl = new CategoryModel();
$CatMdl->setSupplierID((integer) $_POST["DestID"]);

$SuppConntectedList = Array();
include_once(MODEL_PATH. "/CategoryConnectorModel.php");
$ConnMdl = new CategoryConnectorModel();
$SuppConntectedList = $ConnMdl->getSupplierConnectedCats($_POST["DestID"], $_POST["CrossID"]);

function getSubCats($ID){
	global $CatMdl, $SuppConntectedList;
	
	$CatList = $CatMdl->getChildCategory((integer) $ID);
	
	if(count($CatList) < 1){
		return false;
	}
	
	echo "<ul>";
	foreach($CatList as $ID => $Cat){
		echo "<li><a itemid=\"". $ID ."\">";
		if(in_array($ID, $SuppConntectedList)) echo "<s>";
		echo $Cat["Name"];
		if(in_array($ID, $SuppConntectedList)) echo "</s>";
		echo "</a>";
		getSubCats($Cat["ID"]);
		echo "</li>";
	}
	echo "</ul>";
}

$CatList = $CatMdl->getMainCategoryList();
if(count($CatList) < 1){
	die("Kategori Bulunamadı.");
}
	
echo "<ul id=\"MainCatList\">";
foreach($CatList as $ID => $Cat){
	echo "<li><a itemid=\"". $ID ."\">";
	if(in_array($ID, $SuppConntectedList)) echo "<s>";
	echo $Cat["Name"];
	if(in_array($ID, $SuppConntectedList)) echo "</s>";
	echo "</a>";
	getSubCats($Cat["ID"]);
	echo "</li>";
}
echo "</ul>";

?>