<?php

include("../inc/Config.php");
$SQL = new SQLConnect();

include_once(MODEL_PATH ."/ExchangeRateModel.php");
include_once(MODEL_PATH ."/../CurlClass.php");

$ch = new CURL();
$ch->httpHeader(false);
$File = $ch->getUrl("http://www.tcmb.gov.tr/kurlar/today.xml");
$ch->dieCurl();

$Data = simplexml_load_string($File);

$Currency = Array();
$Currency["Dolar"] = $Data->Currency[0];
$Currency["Euro"]  = $Data->Currency[11];


foreach($Currency as $Kur){
	 $Simge = (string) $Kur->attributes()->CurrencyCode;
	 $Fiyat = (float) $Kur->BanknoteSelling;
	 
	 $RateMdl = new ExchangeRateModel();
	 $RateMdl->setCurrency($Simge);
	 $RateMdl->setPrice($Fiyat);
	 $RateMdl->save();
}

?>