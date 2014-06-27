<?php

class ProductModel {
	private $_Details;
	
	public function __construct(){
		$this->_Details = Array();
		$this->_Details["Data"] = Array();
		$this->_Details["Data"]["Images"] = Array();
	}
	
	public function debug(){
		echo "<pre>";
		print_r($this->_Details);
		echo "</pre>";
	}
	
	//--------------------------------------------
	
	public function setID($ID){
		$this->_Details["ID"] = (integer) $ID;
		return $this;
	}

	public function setSuppID($ID){
		$this->_Details["SuppID"] = (integer) $ID;
		return $this;
	}	
	
	public function setCatID($ID){
		$this->_Details["CatID"] = (integer) $ID;
		return $this;
	}
	
	public function setStockID($ID){
		$this->_Details["StockID"] = $ID;
		return $this;
	}
	
	public function setName($Name){
		$this->_Details["Name"] = $Name;
		return $this;
	}
	
	public function setOnSale($Value){
		$this->_Details["OnSale"] = $Value;
		return $this;
	}
	
	public function setStock($Stock){
		$this->_Details["Stock"] = (integer) $Stock;
		return $this;
	}
	
	public function setPrice($Price){
		$this->_Details["Price"] = (float) $Price;
		return $this;
	}

	public function setCurrency($Currency){
		$this->_Details["Currency"] = $Currency;
		return $this;
	}
	
	public function setTax($Tax){
		$this->_Details["Tax"] = (float) $Tax;
		return $this;
	}
	
	public function setImage($Image){
		$this->_Details["Data"]["Images"][] = $Image;
		return $this;
	}
	
	public function setInfo($Info){
		$this->_Details["Data"]["Info"] = $Info;
		return $this;
	}
	
	public function setData($Name, $Value){
		$this->_Details["Data"][$Name] = $Value;
		return $this;
	}
	
	//--------------------------------------------
	
	public function getID(){
		return $this->_Details["ID"];
	}

	public function getSuppID(){
		return $this->_Details["SuppID"];
	}	
	
	public function getCatID(){
		return $this->_Details["CatID"];
	}
	
	public function getStockID(){
		return $this->_Details["StockID"];
	}
	
	public function getName(){
		return $this->_Details["Name"];
	}
	
	public function getOnSale(){
		return $this->_Details["OnSale"];
	}
	
	public function getStock(){
		return $this->_Details["Stock"];
	}
	
	public function getPrice(){
		return $this->_Details["Price"];
	}

	public function getCurrency(){
		return $this->_Details["Currency"];
	}	
	
	public function getTax(){
		return $this->_Details["Tax"];
	}
	
	public function getImage(){
		return $this->_Details["Data"]["Images"];
	}
	
	public function getInfo(){
		return $this->_Details["Data"]["Info"];
	}
	
	public function getData($Name){
		return $this->_Details["Data"][$Name];
	}	
	
	//--------------------------------------------
	
	public function getProduct($ID = ""){
		if($ID == "") $ID = $this->_Details["ID"];
		
		$Tool = new Tools();		
		$SQL = "SELECT * FROM urun WHERE id = '". $Tool->clear($ID)  ."'";
		$Query = mysql_query($SQL);
		
		if(mysql_num_rows($Query) < 1) return false;
		$Data = mysql_fetch_assoc($Query);
		
		$this->setID($Data["id"]);
		$this->setSuppID($Data["tedarikci_id"]);
		$this->setCatID($Data["kategori_id"]);
		$this->setStockID($Data["stok_id"]);
		$this->setCurrency("TL");
		$this->setName($Data["name"]);
		$this->setPrice($Data["price"]);
		$this->setTax($Data["tax"]);
		$this->setStock($Data["stock"]);
		$this->setOnSale($Data["on_sale"]);
		$this->_Details["Data"] = $Tool->Covert2Array($Data["data"]);
		
		return true;
	}
	
	public function getCategoryProducts($ID = ""){
		if($ID == "") $ID = $this->_Details["CatID"];
		
		$ReturnArray = Array();
		$Tool = new Tools();
		
		$SQL = "SELECT id FROM urun WHERE kategori_id = '". $Tool->clear($ID) ."' AND on_sale = '1'";
		$Query = mysql_query($SQL);
		
		if(mysql_num_rows($Query) > 0){
			while($Data = mysql_fetch_assoc($Query)){
				$ReturnArray[] = $Data["id"];
			}
		}
		
		return $ReturnArray;
	}
	
	//--------------------------------------------
	
	public function save(){
		$Tool = new Tools();

		if($this->_Details["Currency"] != "TL"){
			include_once(MODEL_PATH ."/ExchangeRateModel.php");
			
			$Currencies = Array();
			$Currencies["EUR"] = "EUR";
			$Currencies["EURO"] = "EUR";
			$Currencies["AVRO"] = "EUR";

			$Currencies["USD"] = "USD";
			$Currencies["DOLLAR"] = "USD";
			$Currencies["DOLAR"] = "EUR";
			
			if(!isset($Currencies[$this->getCurrency()])){
				$this->debug();
				echo "<hr>";
			}
						
			$ExchangeMdl = new ExchangeRateModel();
			$NewPrice = $ExchangeMdl->setCurrency($Currencies[$this->getCurrency()])->getLira($this->getPrice());

			if(!$NewPrice){
				return false;
			}
			
			$this->setPrice($NewPrice);
		}
		
		$SQL = "SELECT id FROM urun WHERE stok_id = '". $Tool->clear($this->_Details["StockID"]) ."' LIMIT 1";
		$Query = mysql_query($SQL);
		if(mysql_num_rows($Query) > 0){
			$Data = mysql_fetch_assoc($Query);
			$this->setID($Data["id"]);
		}
		
		$SQL  = "REPLACE INTO urun ( id , tedarikci_id , kategori_id , stok_id , name , price , tax , stock , data , on_sale ) ";
		$SQL .= "VALUES ";
		$SQL .= "( '". $Tool->clear($this->_Details["ID"]) ."' , '". $Tool->clear($this->_Details["SuppID"]) ."' , '". $Tool->clear($this->_Details["CatID"]) ."' , '". $Tool->clear($this->_Details["StockID"]) ."' , '". $Tool->clear($this->_Details["Name"]) ."' , '". $Tool->clear($this->_Details["Price"]) ."' , '". $Tool->clear($this->_Details["Tax"]) ."' , '". $Tool->clear($this->_Details["Stock"]) ."' , '". $Tool->clear($Tool->MergeArray($this->_Details["Data"])) ."' , '". $Tool->clear($this->_Details["OnSale"]) ."' )";
		
		$Query = mysql_query($SQL);
	}
	
	public function isAdded($SellerID, $ID = ""){
		if($ID == "") $ID = $this->_Details["ID"];
		
		$Tool = new Tools();
		$Query = mysql_query("SELECT satici_urun_id FROM eklenen WHERE urun_id = '". $Tool->clear($ID) ."' AND satici_id = '". $Tool->clear($SellerID) ."'");
		
		if(mysql_num_rows($Query) > 0){
			$Data = mysql_fetch_assoc($Query);
			return $Data["satici_urun_id"];
		} else {
			return false;
		}
	}
	
	public function saveAdded($ID, $SellerID, $SellerItemID){
		$Tool = new Tools();
		$Query = mysql_query("REPLACE INTO eklenen ( satici_id , urun_id , satici_urun_id ) VALUES ( '". $Tool->clear($SellerID) ."' , '". $Tool->clear($ID) ."' , '". $Tool->clear($SellerItemID) ."' )");
		
		if($Query){
			return true;
		} else {
			return false;
		}
	}
}

?>