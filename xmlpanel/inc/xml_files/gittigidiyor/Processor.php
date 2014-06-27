<?php

class Supplier {
	private $CatList;
	private $_UpdateProducts;
	private $_Temp;

	private function findSubCategories($KatKod){

		$ch = new CURL();
		$ch->httpHeader(false);
		$ch->setHeader(array("Authorization: Basic ".base64_encode($this->getUserDataField("LoginName").":".$this->getUserDataField("LoginPass"))));

		$Url = "http://dev.gittigidiyor.com:8080/listingapi/rlws/anonymous/category?method=getSubCategories&categoryCode=". $KatKod ."&outputCT=xml&startOffSet=0&rowCount=1&withSpecs=true&withDeepest=true&withCatalog=true&lang=tr";

		$XML = $ch->getUrl($Url);
		$ch->dieCurl();

		$Data = simplexml_load_string($XML);
		$TotalCategory = (integer) $Data->categoryCount;

		$ReturnData = Array();
		if($TotalCategory == "0"){
			return $ReturnData;
		}
		for($i = 0 ; $i < $TotalCategory ; $i = ($i + 100)){

			$Url = "http://dev.gittigidiyor.com:8080/listingapi/rlws/anonymous/category?method=getSubCategories&categoryCode=". $KatKod ."&outputCT=xml&startOffSet=". $i ."&rowCount=100&withSpecs=true&withDeepest=true&withCatalog=true&lang=tr";
			$ch = new CURL();
			$ch->httpHeader(false);
			$ch->setHeader(array("Authorization: Basic ".base64_encode($this->getUserDataField("LoginName").":".$this->getUserDataField("LoginPass"))));

			$XML = $ch->getUrl($Url);
			$ch->dieCurl();

			$Data = simplexml_load_string($XML);

			foreach($Data->categories->category as $Data){
				$SubKatKod = (string) $Data->categoryCode;
				$Name = (string) $Data->categoryName;

				$ReturnData[$SubKatKod]["Name"] = $Name;

				$DB = new CategoryModel();
				$DB->setSupplierID("1");
				$DB->setName($Name);
				$DB->setChild($this->CatList[$KatKod]);
				$DB->setData(array("CatCode" => $SubKatKod));
				$DB->setDestID($SubKatKod);
				$DB->save();

				$this->CatList[$SubKatKod] = $DB->getID();

				$ReturnData[$SubKatKod]["SubCategory"] = $this->findSubCategories($SubKatKod);
				if(count($ReturnData[$SubKatKod]["SubCategory"]) == "0"){ unset($ReturnData[$SubKatKod]["SubCategory"]); }
			}
		}

		return $ReturnData;
	}

	public function getSupplierCategories(){

		$Url = "http://dev.gittigidiyor.com:8080/listingapi/rlws/anonymous/category?method=getParentCategories&outputCT=xml&startOffSet=0&rowCount=1&withSpecs=true&withDeepest=true&withCatalog=true&lang=tr";

		echo "<pre>";
		$ch = new CURL();
		$ch->httpHeader(false);
		$ch->setHeader(array("Authorization: Basic ".base64_encode($this->getUserDataField("LoginName").":".$this->getUserDataField("LoginPass"))));

		$XML = $ch->getUrl($Url);
		$ch->dieCurl();

		$Data = simplexml_load_string($XML);
		$TotalCategory = (integer) $Data->categoryCount;

		$this->CatList = Array();

		$ReturnData = Array();
		for($i = 0 ; $i < $TotalCategory ; $i = ($i + 100)){
			$Url = "http://dev.gittigidiyor.com:8080/listingapi/rlws/anonymous/category?method=getParentCategories&outputCT=xml&startOffSet=".$i."&rowCount=100&withSpecs=true&withDeepest=true&withCatalog=true&lang=tr";

			$ch = new CURL();
			$ch->httpHeader(false);
			$ch->setHeader(array("Authorization: Basic ".base64_encode($this->getUserDataField("LoginName").":".$this->getUserDataField("LoginPass"))));

			$XML = $ch->getUrl($Url);
			$ch->dieCurl();

			$Data = simplexml_load_string($XML);

			foreach($Data->categories->category as $Data){
				$KatKod = (string) $Data->categoryCode;
				$Name = (string) $Data->categoryName;

				$ReturnData[$KatKod]["Name"] = $Name;

				$DB = new CategoryModel();
				$DB->setSupplierID("1");
				$DB->setName($Name);
				$DB->setData(array("CatCode" => $KatKod));
				$DB->setDestID($KatKod);
				$DB->save();

				$this->CatList[$KatKod] = $DB->getID();

				$ReturnData[$KatKod]["SubCategory"] = $this->findSubCategories($KatKod);
				if(count($ReturnData[$KatKod]["SubCategory"]) == "0"){ unset($ReturnData[$KatKod]["SubCategory"]); }
			}
		}

		return $ReturnData;
	}

	public function getSupplierCategoryProducts($CatID){
		return false;
	}

	public function addCatList($Data){
		return array("ID" => $Data["ID"], "Name" => $Data["Name"]);
	}
	
	public function setName($Name)   { $this->_Temp["Name"]  = $Name; }
	public function setPrice($Price) { $this->_Temp["Price"] = $Price; }
	public function setStock($Stock) { $this->_Temp["Stock"] = $Stock; }
	public function setTax($Tax)     { $this->_Temp["Tax"]   = $Tax; }
	public function setInfo($Info)   { $this->_Temp["Info"]  = $Info; }
	public function setCatID($CatID) { $this->_Temp["CatID"] = $CatID; }
	public function setImage($Image) { $this->_Temp["Image"] = $Image; }
	
	public function AddUpdateProduct(){
		$this->_UpdateProducts[] = $this->_Temp;
		$this->_Temp = Array();
	}
	
	public function UpdateDebug(){
		print_r($this->_UpdateProducts);
	}
}

?>