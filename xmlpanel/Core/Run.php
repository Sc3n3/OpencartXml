<?php

die;

set_time_limit(0);

include("../inc/Config.php");
include_once(MODEL_PATH ."/../CurlClass.php");

// Root URL
$BaseURL = "http://". $_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['PHP_SELF']), "", $_SERVER['PHP_SELF']);

// CURL oluşturduk
$ch = new CURL();

// Döviz kurlarını aldırdık
$ch->setTimeOut(600);
$ch->getUrl($BaseURL ."/getExchangeRates.php");
$ch->dieCurl();

// Eşleşen tedarikçilerin ve satıcıların kategorileri güncellendi
$ch->newCurl();
$ch->setTimeOut(600);
$ch->getUrl($BaseURL ."/getCategoryList.php");
$ch->dieCurl();

// Tedarikçinin ürünleri güncellendi
$ch->newCurl();
$ch->setTimeOut(600);
$ch->getUrl($BaseURL ."/getSupplierProducts.php");
$ch->dieCurl();

// Satıcının ürünleri güncellendi
$ch->newCurl();
$ch->setTimeOut(600);
$ch->getUrl($BaseURL ."/updateSellerProducts.php");
$ch->dieCurl();

?>