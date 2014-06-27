<?php

class Supplier {
	private $_Added;
	private $_User;
	
	public function __construct(){
		$this->_User = new UserDataModel();
		$this->_User->setSuppID(5)->getUserAllData();
	}
	
	public function getSupplierCategories(){

		$Url = "http://www.yyithalat.com/webservices/products.php";
		
		$ch = new CURL();
		$ch->newCurl();
		$ch->httpHeader(false);
		$XML = $ch->getUrl($Url);
		$ch->dieCurl();

		$Data = simplexml_load_string($XML, 'SimpleXMLElement', LIBXML_NOCDATA);
		
		$AllCategories = Array();
		if(count($Data->product) > 1){
			foreach($Data->product as $Urun){
				$KatID = (string) $Urun->category_id;
				$Name = (string) $Urun->category;

				$AllCategories[$KatID] = $Name;
			}
		}

		foreach($AllCategories as $ID => $Name){
			$DB = new CategoryModel();
			$DB->setSupplierID(5);
			$DB->setName($Name);
			$DB->setData(array());
			$DB->setDestID($ID);
			$DB->save();
		}

		return true;
	}

	public function getSupplierCategoryProducts($SuppCatID, $SystemCatID){

		$Url = "http://www.yyithalat.com/webservices/products.php";
		
		$ch = new CURL();
		$ch->newCurl();
		$ch->httpHeader(false);
		$XML = $ch->getUrl($Url);
		$ch->dieCurl();

		$Data = simplexml_load_string($XML, 'SimpleXMLElement', LIBXML_NOCDATA);
echo "<pre>";
		$ReturnData = Array();
		if(count($Data->product) > 1){
			foreach($Data->product as $Urun){
				$CatID = (string) $Urun->category_id;
				if($CatID == $SuppCatID){
					
					$ID = (string) $Urun->productId;
	
					$ProductMdl = new ProductModel();
					$ProductMdl->setCatID($SystemCatID);
					$ProductMdl->setSuppID(5);
					$ProductMdl->setName((string) $Urun->title);
					$ProductMdl->setTax((float) $Urun->tax_rate);
					$ProductMdl->setCurrency("TL");
					$ProductMdl->setStockID("#YY-". (string) $Urun->productId);
					$ProductMdl->setStock((integer) $Urun->stock);
					$ProductMdl->setPrice((float) $Urun->price);
					$ProductMdl->setInfo((string) $Urun->description);
					$ProductMdl->setImage((string) $Urun->promoImage);
					
					if($Urun->images->count > 0){
						foreach($Urun->images->image as $Image){
							$ProductMdl->setImage((string) $Image);
						}
					}
					
					$ProductMdl->setOnSale(1);
					$ProductMdl->save();
	
					$ReturnData[$ID] = $CatData;
				}
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