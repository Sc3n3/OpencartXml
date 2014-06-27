<?php

include_once(MODEL_PATH. "/SupplierModel.php");
include_once(MODEL_PATH. "/SchemaModel.php");
if(!$_GET["action"]){
	$SuppMdl = new SupplierModel();
	$SchemaMdl = new SchemaModel();
	
	echo "<div id=\"CategoryExchangeTable\">";
	
		echo "<div id=\"Supplier\">";
			$Suppliers = $SuppMdl->getSupplierList("Supplier");	
			echo "Tedarikçi<br /><select id=\"Supplier\">";
			echo "<option value=\"0\">Tedarikçi Seçin</option>";
			foreach($Suppliers as $ID => $Name){
				echo "<option value=\"". $ID ."\">". $Name ."</option>";
			}
			echo "</select>";
			echo "<br style=\"clear:both;\" />";
			echo "<div id=\"Content\"></div>";
		echo "</div>";

		echo "<div id=\"Seller\">";
			$Sellers = $SuppMdl->getSupplierList("Seller");
			echo "Satıcı<br /><select id=\"Seller\">";
			echo "<option value=\"0\">Satıcı Seçin</option>";
			foreach($Sellers as $ID => $Name){
				echo "<option value=\"". $ID ."\">". $Name ."</option>";
			}
			echo "</select>";
			echo "<br style=\"clear:both;\" />";
			echo "<div id=\"Content\"></div>";
		echo "</div>";		
		
		echo "<br style=\"clear:both;\" />";
		echo "<div id=\"Schemas\">";
		
		echo "Satış Şablonu<br />";
		echo "<select id=\"Schema\">";
		echo "<option value=\"0\">Şablon Seçin</option>";
		foreach($SchemaMdl->getSchemas() as $ID => $Name){
			echo "<option value=\"". $ID ."\">". $Name ."</option>";	
		}
		echo "</select>";
		
		echo "</div>";
		echo "<div id=\"ConfirmDiv\"><input type=\"button\" id=\"GetList\" value=\"Kategorileri Getir\"></div>";
		echo "<div id=\"TextDiv\"></div>";
	echo "</div>";
	
} else {
	$File = dirname(__FILE__) ."/files/". str_replace(array("../", "./", "/"), "", $_GET["action"].".php");
	if(is_file($File)){
		include_once($File);
	}
}