<?php

class CategoryModel {
	private $_Details;

	public function __construct(){
		$this->_Details = Array();
		$this->_Details["Data"] = Array();
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

	public function getName(){
		return $this->_Details["Name"];
	}

	public function getChild(){
		return $this->_Details["ChildCategory"];
	}

	public function getData(){
		return $this->_Details["Data"];
	}

	//--------------------------------------------------

	public function setID($ID){
		$this->_Details["ID"] = (integer) $ID;
	}

	public function setSupplierID($ID){
		$this->_Details["SuppID"] = (integer) $ID;
	}

	public function setDestID($ID){
		$this->_Details["DestID"] = $ID;
	}
	
	public function setName($Name){
		$this->_Details["Name"] = $Name;
	}

	public function setChild($ID){
		$this->_Details["ChildCategory"] = $ID;
	}

	public function setData($Data){
		$this->_Details["Data"] = $Data;
	}

	public function setAllData($Data){
		$Required = Array("ID","SuppID","Name","ChildCategory","Data");
		foreach($Data as $Key => $Value){
			if(in_array($Key, $Required)){
				$this->_Details[$Key] = $Value;
			}
		}
	}
	
	//--------------------------------------------------

	public function getSingle(){
		$Tool = new Tools();

		if($this->_Details["ID"] != ""){
			$Query = mysql_query("SELECT data FROM kategoriler WHERE id = '". $Tool->clear($this->_Details["ID"]) ."'");
			if(mysql_num_rows($Query) > 0){
				$Data = mysql_fetch_assoc($Query);
				$this->_Details = $Tool->Convert2Array($Data["data"]);
			} else {
				die("Kayıt bulunamadı.");
			}
		} else {
			die("ID tanımla.");
		}
	}

	public function save(){
		$Tool = new Tools();

		if($this->_Details["ChildCategory"] == ""){
			$this->_Details["ChildCategory"] = "0";
		}

		$Query = mysql_query("SELECT id FROM kategoriler WHERE tedarikci_id = '". $Tool->clear($this->_Details["SuppID"]) ."' AND dest_id = '". $Tool->clear($this->_Details["DestID"]) ."' LIMIT 1");
		if(mysql_num_rows($Query) > 0){
			$Data = mysql_fetch_assoc($Query);
			$this->setID($Data["id"]);
		}
		
		if($this->_Details["ID"] == ""){
			$Query = mysql_query("INSERT INTO kategoriler ( tedarikci_id , name , child , dest_id ) VALUES ( '". $Tool->clear($this->_Details["SuppID"]) ."' , '". $Tool->clear($this->_Details["Name"]) ."' , '". $Tool->clear($this->_Details["ChildCategory"]) ."' , '". $Tool->clear($this->_Details["DestID"]) ."' )");
			$this->setID(mysql_insert_id());

			$Query = mysql_query("UPDATE kategoriler SET data = '". $Tool->clear($Tool->MergeArray($this->_Details)) ."' WHERE id = '". $Tool->clear($this->_Details["ID"]) ."'");
		} else {
			$Query = mysql_query("UPDATE kategoriler SET name = '". $Tool->clear($this->_Details["Name"]) ."' AND data = '". $Tool->clear($Tool->MergeArray($this->_Details)) ."' WHERE id = '". $Tool->clear($this->_Details["ID"]) ."'");
		}
	}

	public function getMainCategoryList(){
		$ReturnArray = Array();
		if(is_integer($this->_Details["SuppID"])){
			$Tool = new Tools();
			$Query = mysql_query("SELECT id, name, data FROM kategoriler WHERE tedarikci_id = '". $Tool->clear($this->_Details["SuppID"]) ."' AND child = '0'");

			if(mysql_num_rows($Query) > 0){
				while($Data = mysql_fetch_assoc($Query)){
					$ReturnArray[$Data["id"]] = $Tool->Covert2Array($Data["data"]);
				}
			}
		}
		return $ReturnArray;
	}
	public function getChildCategory($ID){
		$ReturnArray = Array();
		if(is_integer($ID)){
			$Tool = new Tools();
			$Query = mysql_query("SELECT id, name, data FROM kategoriler WHERE tedarikci_id = '". $Tool->clear($this->_Details["SuppID"]) ."' AND child = '". $Tool->clear($ID) ."'");

			if(mysql_num_rows($Query) > 0){
				while($Data = mysql_fetch_assoc($Query)){
					$ReturnArray[$Data["id"]] = $Tool->Covert2Array($Data["data"]);
				}
			}
		}
		return $ReturnArray;
	}
}

?>