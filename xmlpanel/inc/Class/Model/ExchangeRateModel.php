<?php

class ExchangeRateModel {
	private $_Details;
	private $_IDs = Array();
	
	public function __construct(){
		$SQL = "SELECT id , birim FROM para_birimi";
		$Query = mysql_query($SQL);
		
		if(mysql_num_rows($Query) > 0){
			while($Data = mysql_fetch_assoc($Query)){
				$this->_IDs[$Data["birim"]] = $Data["id"];
			}
		}
	}
	
	public function debug(){
		echo "<pre>";
		print_r($this->_IDs);
		echo "<hr>";
		print_r($this->_Details);
		echo "</pre>";
	}
	
	//---------------------------------------------------
	
	public function setID($ID){
		$this->_Details["ID"] = (integer) $ID;
		return $this;
	}
	
	public function setCurrency($Currency){
		$this->_Details["Currency"] = $Currency;
		return $this;
	}
	
	public function setPrice($Price){
		$this->_Details["Price"] = (float) $Price;
		return $this;
	}

	//---------------------------------------------------
	
	public function getID(){
		return $this->_Details["ID"];
	}
	
	public function getCurrency(){
		return $this->_Details["Currency"];
	}
	
	public function getPrice(){
		return $this->_Details["Price"];
	}	
	
	public function getData(){
		$Tool = new Tools();
		$Query = mysql_query("SELECT birim , fiyat FROM para_birimi WHERE id = '". $Tool->clear($this->getID()) ."' LIMIT 1");
		
		if(mysql_num_rows($Query) > 0){
			$Data = mysql_fetch_assoc($Query);
			
			$this->setCurrency($Data["birim"]);
			$this->setPrice($Data["fiyat"]);
			
			return true;
		} else {
			return false;
		}
	}
	
	//---------------------------------------------------
	
	public function save(){
		$Tool = new Tools();
		
		if(!isset($this->_IDs[$this->_Details["Currency"]]) && !$this->_Details["ID"]){
			$SQL = "INSERT INTO para_birimi ( birim , fiyat ) VALUES ( '". $Tool->clear($this->_Details["Currency"]) ."' , '". $Tool->clear($this->_Details["Price"]) ."' )";
			$Query = mysql_query($SQL);
			if($Query){
				$this->setID(mysql_insert_id());
				return true;
			}
		} else {
			if(!$this->_Details["ID"]){
				$SQL = "UPDATE para_birimi SET birim = '". $Tool->clear($this->_Details["Currency"]) ."' AND fiyat = '". $Tool->clear($this->_Details["Price"]) ."' WHERE id = '". $Tool->clear($this->_IDs[$this->_Details["Currency"]]) ."' LIMIT 1";
			} else {
				$SQL = "UPDATE para_birimi SET birim = '". $Tool->clear($this->_Details["Currency"]) ."' AND fiyat = '". $Tool->clear($this->_Details["Price"]) ."' WHERE id = '". $Tool->clear($this->_Details["ID"]) ."' LIMIT 1";
			}
			
			$Query = mysql_query($SQL);
			if($Query){
				return true;
			} else {
				return false;
			}
		}
		
		return false;
	}
	
	//---------------------------------------------------
	
	public function getLira($Price){
		if(!isset($this->_IDs[$this->getCurrency()])){
			return false;
		}
		
		if($this->setID($this->_IDs[$this->getCurrency()])->getData()){
			return $Price * $this->getPrice();
		} else {
			return false;
		}
	}
	
}

?>