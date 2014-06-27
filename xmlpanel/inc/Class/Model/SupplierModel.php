<?php

class SupplierModel {
	private $_Details;

	public function __construct(){}

	public function setID($ID){
		$this->_Details["ID"] = (integer) $ID;
	}
	public function setName($Name){
		$this->_Details["Name"] = $Name;
	}
	public function setSupplierType($Type){
		switch ($Type){
			case "Supplier":
				$Code = 1;
				break;
			case "Seller":
				$Code = 0;
				break;
			default:
				die("Undefined Supplier Type.");
				break;
		}
		$this->_Details["SuppType"] = $Code;
	}

	public function getID(){
		return $this->_Details["ID"];
	}
	public function getName(){
		return $this->_Details["Name"];
	}
	public function getSupplierType(){
		switch ($this->_Details["SuppType"]){
			case "1":
				$Code = "Supplier";
				break;
			case "0":
				$Code = "Seller";
				break;
			default:
				die("Undefined Supplier Type.");
				break;
		}
		return $Code;
	}

	public function getSupplierList($Type = ""){
		if($Type == ""){
			$Type = "Supplier";
		}

		switch ($Type){
			case "Supplier":
				$Code = 1;
				break;
			case "Seller":
				$Code = 0;
				break;
			default:
				die("Undefined Supplier Type.");
				break;
		}

		$Tool = new Tools();
		$Query = mysql_query("SELECT tedarikci.id, tedarikci.name FROM tedarikci, kullanici WHERE tedarikci.type = '". $Tool->clear($Code) ."' AND tedarikci.id = kullanici.tedarikci_id ");

		$ReturnArray = Array();
		if(mysql_num_rows($Query) > 0){
			while($Data = mysql_fetch_assoc($Query)){
				$ReturnArray[$Data["id"]] = $Data["name"];
			}
		}
		return $ReturnArray;
	}

	public function getData(){
		$Tool = new Tools();
		$Query = mysql_query("SELECT id, name, type FROM tedarikci WHERE id = '". $Tool->clear($this->_Details["ID"]) ."'");

		if(mysql_num_rows($Query) > 0){
			$Data = mysql_fetch_assoc($Query);
				
			$this->setID($Data["id"]);
			$this->setName($Data["name"]);
			
			$Types = Array("0" => "Seller", "1" => "Supplier");
			$this->setSupplierType($Types[$Data["type"]]);
				
			return true;
		} else {
			return false;
		}
	}
}

?>