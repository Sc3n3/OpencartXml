<?php

class SchemaModel {
	private $_Details;

	public function __construct(){
		$this->_Details = Array();
		$this->_Details["Commisions"] = Array();
		$this->_Details["CargoList"] = Array();
	}

	public function debug(){
		echo "<pre>";
		print_r($this->_Details);
		echo "</pre>";
	}

	//------------------------------------------

	public function setID($ID){
		$this->_Details["ID"] = (integer) $ID;
		return $this;
	}
	
	public function setName($Name){
		$this->_Details["Name"] = $Name;
		return $this;
	}

	public function setListDay($Day){
		$this->_Details["Day"] = $Day;
		return $this;
	}

	public function setExtraPrice($Price){
		$this->_Details["ExtraPrice"] = $Price;
		return $this;
	}

	public function setMinSellPrice($Price){
		$this->_Details["MinSellPrice"] = $Price;
		return $this;
	}

	public function setMinSellStock($Stock){
		$this->_Details["MinSellStock"] = $Stock;
		return $this;
	}

	public function setSellCity($City){
		$this->_Details["SellCity"] = $City;
		return $this;
	}

	public function setCargoInfo($Info){
		$this->_Details["CargoInfo"] = $Info;
		return $this;
	}

	public function setCargoCompany($Cargo){
		$this->_Details["CargoList"][] = $Cargo;
		return $this;
	}

	public function setExtraInfo($Info){
		$this->_Details["ExtraInfo"] = $Info;
		return $this;
	}

	public function setExtraLink($Text, $Link){
		$this->_Details["ExtraLink"] = Array("Text" => $Text, "Link" => $Link);
		return $this;
	}

	public function setAddStockCode($Value){
		$this->_Details["StockCode"] = $Value;
		return $this;
	}

	public function setCatalogImage($Value){
		$this->_Details["CatalogImage"] = $Value;
		return $this;
	}

	public function setBoldTitle($Value){
		$this->_Details["BoldTitle"] = $Value;
		return $this;
	}

	public function setCommision($StartPrice, $EndPrice, $Commision){
		$this->_Details["Commisions"][] = Array("StartPrice" => $StartPrice, "EndPrice" => $EndPrice, "Commision" => $Commision);
		return $this;
	}
	
	//------------------------------------------
	
	public function getID(){
		return $this->_Details["ID"];
	}
	
	public function getName(){
		return $this->_Details["Name"];
	}

	public function getListDay(){
		return $this->_Details["Day"];
	}

	public function getExtraPrice(){
		return $this->_Details["ExtraPrice"];
	}

	public function getMinSellPrice(){
		return $this->_Details["MinSellPrice"];
	}

	public function getMinSellStock(){
		return $this->_Details["MinSellStock"];
	}

	public function getSellCity(){
		return $this->_Details["SellCity"];
	}

	public function getCargoInfo(){
		return $this->_Details["CargoInfo"];
	}

	public function getCargoCompany(){
		return $this->_Details["CargoList"];
	}

	public function getExtraInfo(){
		return $this->_Details["ExtraInfo"];
	}

	public function getExtraLink(){
		return $this->_Details["ExtraLink"];
	}

	public function getAddStockCode(){
		return $this->_Details["StockCode"];
	}

	public function getCatalogImage(){
		return $this->_Details["CatalogImage"];
	}

	public function getBoldTitle(){
		return $this->_Details["BoldTitle"];
	}

	public function getCommision(){
		return $this->_Details["Commisions"];
	}	
	
	//------------------------------------------
	
	public function save(){
		$Tool = new Tools();
		
		if($this->_Details["ID"] == ""){
			$SQL = "INSERT INTO sell_schemas ( name ) VALUES ( '". $Tool->clear($this->_Details["Name"]) ."' )";
			$Query = mysql_query($SQL);
			
			if(!$Query) return false;
			
			$this->setID(mysql_insert_id());
			$this->save();
		} else {
			$Query = mysql_query("REPLACE INTO sell_schemas ( id , name , data ) VALUES ( '". $this->_Details["ID"] ."' , '". $this->_Details["Name"] ."' , '". $Tool->clear($Tool->MergeArray($this->_Details)) ."' ) ");
		
			if($Query){
				return true;
			} else {
				return false;
			}
		}
	}
	
	public function getSchema($ID = ""){
		if($ID == "") $ID = $this->_Details["ID"];
		
		$Tool = new Tools();	
		$Query = mysql_query("SELECT data FROM sell_schemas WHERE id = '". $Tool->clear($ID) ."'");
		
		if(mysql_num_rows($Query) > 0){
			$Data = mysql_fetch_assoc($Query);
			$this->_Details = $Tool->Covert2Array($Data["data"]);
			
			return true;
		} else {
			return false;
		}
	}
	
	public function getSchemas(){
		$ReturnArray = Array();
		
		$Query = mysql_query("SELECT id , name FROM sell_schemas");
		if(mysql_num_rows($Query)){
			while($Data = mysql_fetch_assoc($Query)){
				$ReturnArray[$Data["id"]] = $Data["name"];
			}
		}
		
		return $ReturnArray;
	}
	
	public function calculatePrice($Price, $Tax = "1"){
		$RealPrice = (float) $Price;

		foreach($this->getCommision() as $Commision){
			if($Commision["StartPrice"] <= $RealPrice && $Commision["EndPrice"] >= $RealPrice){
				$Percent = (float) $Commision["Commision"];
				$RealPrice = (($RealPrice / 100) * $Percent) + $RealPrice;
				break;
			}
		}
		$RealPrice = $RealPrice + $this->_Details["ExtraPrice"];
		
		return $RealPrice;
	}
}

?>