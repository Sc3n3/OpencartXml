<?php

class Supplier {
	private $_Added;
	private $_User;
	
	public function __construct(){
		$this->_User = new UserDataModel();
		$this->_User->setSuppID(2)->getUserAllData();
	}
	
	public function getSupplierCategories(){

		$Url = "http://www.tesaniletisim.com/websrvtestSite/responsekat.aspx?userkod=". $this->_User->getData("UserCode") ."&username=". $this->_User->getData("UserName") ."&userpass=". $this->_User->getData("Password");
		
		$ch = new CURL();
		$ch->newCurl();
		$ch->httpHeader(false);
		$XML = $ch->getUrl($Url);
		$ch->dieCurl();

		$Data = simplexml_load_string($XML, 'SimpleXMLElement', LIBXML_NOCDATA);

		$AllCategories = Array();
		if(count($Data->KATEGORI) > 1){
			foreach($Data->KATEGORI as $CatData){
				$KatID = (string) $CatData->KATID;
				$KatKod = (string) $CatData->KODU;
				$Name = (string) $CatData->ADI;

				$AllCategories[$KatKod] = Array("CatID" => $KatID, "Name" => $Name);
			}
		}

		ksort($AllCategories);

		$List = Array();
		foreach($AllCategories as $KatKod => $Data){
			$SubCats = explode(".", $KatKod);
			$RealCatCode = $SubCats[0];

			if($SubCats[0] == "3" && $SubCats[1] == "02"){
				$Debug[$KatKod] = $Data;
			}

			$Text = "'CatCode' => '". $KatKod ."',";
			foreach($Data as $Key => $Value){
				$Text .= "'". $Key ."' => '". $Value ."',";
			}

			$tempSubCats = $SubCats;
			if(count($tempSubCats) > 1){
				unset($tempSubCats[count($tempSubCats)-1]);
			}

			$Text .= "'ParentCat' => '". implode(".", $tempSubCats) ."',";
			$Text .= substr($Text, 0, -1);

			$Cats = "[\"".implode("\"][\"SubCats\"][\"", $SubCats)."\"]";
			eval("\$List".$Cats." = array();");
			eval("\$List".$Cats."[\"Data\"] = array(". $Text .");");
		}

		$this->_Added = Array();
		foreach($List as $Code => $Data){

			$DB = new CategoryModel();
			$DB->setSupplierID(2);
			$DB->setName($Data["Data"]["Name"]);
			$DB->setData($Data["Data"]);
			$DB->setDestID($Data["Data"]["CatCode"]);
			$DB->save();

			if(!isset($this->_Added[$Data["Data"]["ParentCat"]])){
				$this->_Added[$Data["Data"]["CatCode"]] = $DB->getID();
			}
			if(isset($Data["SubCats"])){
				$this->getSubCats($Data);
			}
		}

		return true;
	}

	private function getSubCats($List){

		$ReturnList = Array();
		foreach($List["SubCats"] as $Key => $Data){

			$DB = new CategoryModel();
			$DB->setSupplierID(2);
			$DB->setName($Data["Data"]["Name"]);
			$DB->setChild($this->_Added[$Data["Data"]["ParentCat"]]);
			$DB->setDestID($Data["Data"]["CatCode"]);
			$DB->setData($Data["Data"]);
			$DB->save();

			if(!isset($this->_Added[$Data["Data"]["CatCode"]])){
				$this->_Added[$Data["Data"]["CatCode"]] = $DB->getID();
			}
			if(isset($Data["SubCats"])){
				$this->getSubCats($Data);
			}
		}
	}

	public function getSupplierCategoryProducts($SuppCatID, $SystemCatID){

		$Url = "http://www.tesaniletisim.com/websrvtestSite/response.aspx?userkod=". $this->_User->getData("UserCode") ."&username=". $this->_User->getData("UserName") ."&userpass=". $this->_User->getData("Password") ."&katkod=". $SuppCatID;

		$ch = new CURL();
		$ch->newCurl();
		$ch->httpHeader(false);
		$XML = $ch->getUrl($Url);
		$ch->dieCurl();

		$Data = simplexml_load_string($XML, 'SimpleXMLElement', LIBXML_NOCDATA);

		$ReturnData = Array();
		if(count($Data->PRODUCT) > 1){
			foreach($Data->PRODUCT as $CatData){
				$ID = (string) $CatData->STOCKID;

				$ProductMdl = new ProductModel();
				$ProductMdl->setCatID($SystemCatID);
				$ProductMdl->setSuppID(2);
				$ProductMdl->setName((string) $CatData->PRODUCTNAME ." ". (string) $CatData->VARIATION);
				$ProductMdl->setTax((float) $CatData->KDV);
				$ProductMdl->setCurrency((string) $CatData->OZELFIYATPBR);
				$ProductMdl->setStockID((string) $CatData->STOCKCODE);
				$ProductMdl->setStock((integer) $CatData->ADET);
				$ProductMdl->setPrice((float) $CatData->OZELFIYAT);
				$ProductMdl->setInfo((string) $CatData->ACIKLAMA);
				$ProductMdl->setImage((string) $CatData->IMAGES);
				$ProductMdl->setOnSale(1);
				$ProductMdl->save();

				$ReturnData[$ID] = $CatData;
			}
		}
		return $ReturnData;
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