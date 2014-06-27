<?php

class CategoryConnectorModel {
	private $_Details;

	public function __construct(){
		$this->_Details = Array();
	}

	public function debug(){
		echo "<pre>";
		print_r($this->_Details);
		echo "</pre>";
	}

	//--------------------------------------------------

	public function getID(){
		return $this->_Details["ID"];
	}

	public function getSupplierID(){
		return $this->_Details["SuppID"];
	}

	public function getSupplierCatID(){
		return $this->_Details["SuppCatID"];
	}

	public function getSellerID(){
		return $this->_Details["SellID"];
	}

	public function getSellerCatID(){
		return $this->_Details["SellCatID"];
	}

	public function getSchemaID(){
		return $this->_Details["SchemaID"];
	}

	public function getOnSale(){
		return $this->_Details["OnSale"];
	}

	//--------------------------------------------------

	public function setID($ID){
		$this->_Details["ID"] = (integer) $ID;
		return $this;
	}

	public function setSupplierID($ID){
		$this->_Details["SuppID"] = (integer) $ID;
		return $this;
	}

	public function setSupplierCatID($ID){
		$this->_Details["SuppCatID"] = (integer) $ID;
		return $this;
	}

	public function setSellerID($ID){
		$this->_Details["SellID"] = (integer) $ID;
		return $this;
	}

	public function setSellerCatID($ID){
		$this->_Details["SellCatID"] = (integer) $ID;
		return $this;
	}

	public function setSchemaID($ID){
		$this->_Details["SchemaID"] = (integer) $ID;
		return $this;
	}

	public function setOnSale($Value){
		$this->_Details["OnSale"] = $Value;
		return $this;
	}

	//--------------------------------------------------

	public function save(){
		$Tool = new Tools();
		$Query = mysql_query("REPLACE INTO kategori_bagla ( id , tedarikci_id , tedarikci_cat_id , satici_id , satici_cat_id , schema_id , on_sale ) VALUES ( '". $Tool->clear($this->_Details["ID"]) ."' , '". $Tool->clear($this->_Details["SuppID"]) ."' , '". $Tool->clear($this->_Details["SuppCatID"]) ."' , '". $Tool->clear($this->_Details["SellID"]) ."' , '". $Tool->clear($this->_Details["SellCatID"]) ."' , '". $Tool->clear($this->_Details["SchemaID"]) ."' , '". $Tool->clear($this->_Details["OnSale"]) ."' )");

		if($Query){
			return true;
		} else {
			return false;
		}
	}

	public function getSingle(){
		$Tool = new Tools();
		$Query = mysql_query("SELECT * FROM kategori_bagla WHERE id = '". $Tool->clear($this->_Details["ID"]) ."'");

		if($Query){
			$Data = mysql_fetch_assoc($Query);
			$this->setID($Data["id"]);
			$this->setSellerID($Data["satici_id"]);
			$this->setSellerCatID($Data["satici_cat_id"]);
			$this->setSupplierID($Data["tedarikci_id"]);
			$this->setSupplierCatID($Data["tedarikci_cat_id"]);
			$this->setSchemaID($Data["schema_id"]);
			$this->setOnSale($Data["on_sale"]);

			return true;
		} else {
			return false;
		}
	}

	public function getConnectedCategoryList(){
		$SQL  = "SELECT ";
		$SQL .= "a.id, a.tedarikci_id, a.satici_id, a.on_sale, a.schema_id, a.satici_cat_id, a.tedarikci_cat_id,";
		$SQL .= "(SELECT name FROM kategoriler WHERE id = a.tedarikci_cat_id) AS suppcatname,";
		$SQL .= "(SELECT name FROM kategoriler WHERE id = a.satici_cat_id) AS sellcatname,";
		$SQL .= "(SELECT name FROM tedarikci WHERE id = a.satici_id) AS sellname,";
		$SQL .= "(SELECT name FROM tedarikci WHERE id = a.tedarikci_id) AS suppname,";
		$SQL .= "(SELECT name FROM sell_schemas WHERE id = a.schema_id) AS schemaname ";
		$SQL .= "FROM kategori_bagla AS a WHERE 1=1 ";
		$SQL .= "ORDER BY a.id DESC";

		$Query = mysql_query($SQL) or die(mysql_error());

		$ReturnArray = Array();
		if(mysql_num_rows($Query) > 0){
			while($Data = mysql_fetch_assoc($Query)){
				$ReturnArray[$Data["id"]] = $Data;
			}
		}

		return $ReturnArray;
	}

	public function delCatConn(){
		$Tool = new Tools();
		$Query = mysql_query("DELETE FROM kategori_bagla WHERE id = '". $Tool->clear($this->_Details["ID"]) ."'");

		if($Query){
			return true;
		} else {
			return false;
		}
	}

	public function getSupplierConnectedCats($SuppID, $SellID){
		$ReturnArray = Array();

		if($SuppID != "" || $SellID != ""){
			$Tool = new Tools();
				
			$SQL = "SELECT tedarikci_cat_id FROM kategori_bagla WHERE tedarikci_id = '". $Tool->clear($SuppID) ."' AND satici_id = '". $Tool->clear($SellID) ."'";
			$Query = mysql_query($SQL);
				
			if(mysql_num_rows($Query) > 0){
				while($Data = mysql_fetch_assoc($Query)){
					$ReturnArray[] = $Data["tedarikci_cat_id"];
				}
			}
		}

		return $ReturnArray;
	}

	public function getSuppConnectedCategoryList($ID = "0"){
		$ReturnArray = Array();

		if($ID == "0"){
			$Query = mysql_query("SELECT a.tedarikci_id , a.tedarikci_cat_id , (SELECT dest_id FROM kategoriler WHERE id = a.tedarikci_cat_id ) AS dest_id FROM kategori_bagla AS a ORDER BY id ASC");
		} else {
			$Tool = new Tools();
			$Query = mysql_query("SELECT a.tedarikci_id , a.tedarikci_cat_id , (SELECT dest_id FROM kategoriler WHERE id = a.tedarikci_cat_id ) AS dest_id FROM kategori_bagla AS a WHERE a.tedarikci_id = '". $Tool->clear($ID) ."' ORDER BY id ASC");
		}
		
		if(mysql_num_rows($Query) > 0){
			while($Data = mysql_fetch_assoc($Query)){
				$ReturnArray[$Data["tedarikci_id"]][] = Array("CatID" => $Data["tedarikci_cat_id"] , "DestID" => $Data["dest_id"]);
			}
		}
		
		return $ReturnArray;
	}
	
	public function getSellConnectedCategoryList($ID = "0"){
		$ReturnArray = Array();

		if($ID == "0"){
			$Query = mysql_query("SELECT a.satici_id , a.satici_cat_id , (SELECT dest_id FROM kategoriler WHERE id = a.satici_cat_id) AS dest_id , a.tedarikci_cat_id , a.schema_id FROM kategori_bagla AS a WHERE on_sale = '1' ORDER BY id ASC");
		} else {
			$Tool = new Tools();
			$Query = mysql_query("SELECT a.satici_id , a.satici_cat_id , (SELECT dest_id FROM kategoriler WHERE id = a.satici_cat_id) AS dest_id , a.tedarikci_cat_id , a.schema_id FROM kategori_bagla AS a WHERE a.on_sale = '1' AND a.satici_id = '". $Tool->clear($ID) ."' ORDER BY id ASC");
		}
		
		if(mysql_num_rows($Query) > 0){
			while($Data = mysql_fetch_assoc($Query)){
				$ReturnArray[$Data["satici_id"]][] = Array("SuppCatID" => $Data["tedarikci_cat_id"] , "SellCatID" => $Data["satici_cat_id"], "SchemaID" => $Data["schema_id"], "DestID" => $Data["dest_id"]);
			}
		}
		
		return $ReturnArray;
	}	
}

?>