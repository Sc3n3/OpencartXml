<?php

class Supplier {
	private $_Added;
	private $_User;
	private $_Temp;
	
	public function __construct(){
		$this->_User = new UserDataModel();
		$this->_User->setSuppID(6)->getUserAllData();
	}
	
	public function getSupplierCategories(){

		$Url = "http://shop.avm.gen.tr/xml/CategoryListXML.php";
		
		$ch = new CURL();
		$ch->newCurl();
		$ch->httpHeader(false);
		$XML = $ch->getUrl($Url);
		$ch->dieCurl();

		$List = simplexml_load_string($XML, 'SimpleXMLElement', LIBXML_NOCDATA);

		$this->_Added = Array();
		foreach($List as $Code => $Data){

			$ID = (string) $Data->attributes()->id;
			$Name = (string) $Data->name;
			$Parent = (string) $Data->attributes()->parent;
			
			$DB = new CategoryModel();
			$DB->setSupplierID(6);
			$DB->setName($Name);
			$DB->setData(array("CatCode" => $ID));
			$DB->setDestID($ID);
			$DB->save();

			if(!isset($this->_Added[$ID])){
				$this->_Added[$ID] = $DB->getID();
			}
			if(count($Data->subcategories->category) > 0){
				$this->getSubCats($Data);
			}
		}

		return true;
	}

	private function getSubCats($List){

		foreach($List->subcategories->category as $Key => $Data){

			$ID = (string) $Data->attributes()->id;
			$Name = (string) $Data->name;
			$Parent = (string) $Data->attributes()->parent;
			
			$DB = new CategoryModel();
			$DB->setSupplierID(6);
			$DB->setName($Name);
			$DB->setChild($this->_Added[$Parent]);
			$DB->setData(array("CatCode" => $ID));
			$DB->setDestID($ID);
			$DB->save();

			if(!isset($this->_Added[$ID])){
				$this->_Added[$ID] = $DB->getID();
			}
			if(count($Data->subcategories->category) > 0){
				$this->getSubCats($Data);
			}
		}
	}

	public function getSupplierCategoryProducts($SuppCatID, $SystemCatID){	}

	public function setName($Name)   { $this->_Temp["Name"]      = $Name; }
	public function setPrice($Price) { $this->_Temp["Price"]     = $Price; }
	public function setStock($Stock) { $this->_Temp["Stock"]     = $Stock; }
	public function setTax($Tax)     { $this->_Temp["Tax"]       = $Tax; }
	public function setInfo($Info)   { $this->_Temp["Info"]      = $Info; }
	public function setCatID($CatID) { $this->_Temp["CatID"]     = $CatID; }
	public function setImage($Image) { $this->_Temp["Image"]     = $Image; }
	public function setStockCode($ID){ $this->_Temp["StockCode"] = $ID; }
	public function setProductID($ID){ $this->_Temp["ProductID"] = $ID; }
	
	public function sendProduct(){
		$Tool = new Tools();

		$Url = "http://shop.avm.gen.tr/xml/Importer.php";
		$SendData = base64_encode($Tool->MergeArray($this->_Temp));
		
		$ch = new CURL();
		$ch->newCurl();
		$ch->httpHeader(false);
		$Data = $ch->postUrl($Url, array("Data" => $SendData));
		$ch->dieCurl();

		$Response = json_decode($Data, true);
		
		if($Response["Status"] == "false"){
			return false;
		} else {
			return $Response["Message"];
		}
	}
	
	public function addCatList($Data){
		return array("ID" => $Data["ID"], "Name" => $Data["Name"]);
	}

	public function getProductName($Data){
		return (string) $Data->PRODUCTNAME;
	}

	public function getStockID($Data){
		return (string) $Data->STOCKID;
	}
}

?>