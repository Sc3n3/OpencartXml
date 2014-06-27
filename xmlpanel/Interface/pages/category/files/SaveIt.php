<?php

include_once( MODEL_PATH ."/CategoryConnectorModel.php");

$ConMdl = new CategoryConnectorModel();
$ConMdl->setSupplierID((integer) $_POST["SuppID"])->setSupplierCatID((integer) $_POST["SuppCatID"]);
$ConMdl->setSellerID((integer) $_POST["SellID"])->setSellerCatID((integer) $_POST["SellCatID"]);
$ConMdl->setOnSale(1)->setSchemaID((integer) $_POST["SchemaID"]);

if($ConMdl->save()){
	echo "Başarıyla Kaydedildi!";
} else {
	echo "Kayıt İşlemi Sırasında Hata Oluştu!";
}

?>