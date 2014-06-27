<?php

	header("Content-Type: text/html; charset=UTF-8");

    $ch = curl_init();
	curl_setopt($ch , CURLOPT_HTTPHEADER , array("Host: www.arabamaraba.com"));
    curl_setopt($ch , CURLOPT_RETURNTRANSFER , TRUE);
    curl_setopt($ch , CURLOPT_FOLLOWLOCATION, FALSE);
	curl_setopt($ch , CURLOPT_INTERFACE, "174.137.191.25");
    curl_setopt($ch , CURLOPT_URL, 'http://www.arabamaraba.com/xmlkatalog/katalog2/katalog.xml');
    curl_setopt($ch , CURLOPT_POST , FALSE);
    $exec = curl_exec($ch);
	curl_close($ch);
	unset($ch);

	$fp = fopen("Urun.xml", "w+");
	fwrite($fp, $exec);
	fclose($fp);
	die;
	
	$xml = simplexml_load_string($exec);
	
	echo "<pre>";
	if(isset($xml->urun_gruplari->grup)){
		foreach($xml->urun_gruplari->grup as $Grup){
			print_r($Grup);
			die;
		}
	}
	
	echo "<pre>";
	//print_r($xml->urun_gruplari);
	echo "<hr>";
	//print_r($xml->urunler);
	
?>