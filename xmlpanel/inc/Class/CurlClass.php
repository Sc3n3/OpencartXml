<?php

class CURL {
	private $ch;
	private $TimeOutTime;
	private $html;
	private $LastURL;
	private $Charset;
	private $Debug = false;
	private $Data = Array();
	private $Cookies = Array();

	function __construct(){
		$this->setTimeOut(60);
		$this->setHeader(array());
		$this->setAgent("Mozilla/5.0 (Windows; U; Windows NT 6.1; en; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.22");
		$this->httpHeader(true);
		$this->newCurl();
	}

	function __destruct(){
		$this->dieCurl();
	}

	private function TimeOut(){
		$returnTime = $this->TimeOutTime - time();
		if($returnTime < "1"){
			die("CURL Error: Timeout.");
		}
		return $returnTime;
	}

	private function getCookie(){
		return implode(";", $this->Cookies);
	}

	private function Exec(){
		if($this->Data["IP"] != ""){
			curl_setopt($this->ch, CURLOPT_INTERFACE, $this->Data["IP"]);
		}

		$this->Data["Header"] += array("Expect" => "Expect:");
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->Data["Header"]);
		curl_setopt($this->ch, CURLOPT_USERAGENT, $this->Data["UserAgent"]);
		curl_setopt($this->ch, CURLOPT_COOKIE, $this->getCookie());
		curl_setopt($this->ch, CURLOPT_TIMEOUT,  $this->TimeOut());
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->TimeOut());
		curl_setopt($this->ch, CURLOPT_HEADER, $this->Data["HttpHeader"]);
		$this->html = curl_exec($this->ch);
		$Info = curl_getinfo($this->ch);

		if(curl_errno($this->ch) != "0"){
			$Message = curl_error($this->ch);
			$this->dieCurl();
			die("CURL Error: ". $Message);
		}

		$this->parseCookies($this->html);
		$this->LastURL = $Info["url"];
		$this->Charset = preg_replace("/.+?charset=(.+?)/i", "$1", $Info["content_type"]);

		if($this->Debug){
			echo "<hr>". $Info["total_time"] ." - ". $this->TimeOut() ."<hr>";
			echo "<pre>";
			print_r($this->Data);
			print_r($this->Cookies);
			print_r($Info);
			echo "</pre>";
		}

		return $this->html;
	}

	public function getLastURL(){
		return $this->LastURL;
	}

	public function setData($Data){
		$this->setCookieText($Data["Cookies"]);
		$this->Data = (array) $Data;
	}

	private function Debug($Param){
		if($Param){
			$this->Debug = true;
		} else {
			$this->Debug = false;
		}
	}

	public function parseCookies($html){
		preg_match_all("/Set-Cookie: (.+?)=(.*?);/i", $html, $Cookies);
		foreach($Cookies[1] as $index => $CookieName){
			$CookieValue = $Cookies[2][$index];
			$this->Cookies[$CookieName] = $CookieName ."=". $CookieValue;
		}
	}

	public function setCookie($Cookie, $Value){
		$this->Cookies[$Cookie] = urlencode($Cookie)."=".urlencode($Value);
	}

	public function setHeader($Data){
		$this->Data["Header"] = $Data;
		
	}
	
	public function httpHeader($value){
		$this->Data["HttpHeader"] = $value;
	}
	
	public function setAgent($Agent){
		$this->Data["UserAgent"] = $Agent;
	}
	
	public function setCookieText($Cookie){
		$Cookies = explode(";", $Cookie);
		foreach($Cookies as $Cookie){
			if($Cookie != ""){
				$Detail = explode("=", $Cookie);
				$this->Cookies[$Detail[0]] = $Cookie;
			}
		}
	}

	public function setCookieArray($Array){
		if(is_array($Array)){
			foreach($Array as $Cookie => $Value){
				$this->Cookies[$Cookie] = urlencode($Cookie)."=".urlencode($Value);
			}
		}
	}

	public function SaveCookies(){
		return true;
	}

	public function setTimeOut($TimeOut){
		$this->TimeOutTime = time() + $TimeOut;
	}

	public function newCurl(){
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($this->ch, CURLOPT_FAILONERROR, TRUE);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($this->ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, dirname(__FILE__) ."/.temp");
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	}

	public function getUrl($Url){
		curl_setopt($this->ch, CURLOPT_POST, FALSE);
		curl_setopt($this->ch, CURLOPT_URL, str_replace("&amp;", "&", $Url));
		return $this->Exec();
	}

	public function postUrl($Url, $Post){
		if(is_array($Post) && $this->Charset != "UTF-8"){
			$PostData = array();
			foreach($Post as $Key => $Value){
				if(substr($Value, 0, 1) != "@"){
					$PostData[$Key] = iconv("UTF-8", "ISO-8859-1//TRANSLIT//IGNORE", $Value);
				}
			}
		} else {
			$PostData = $Post;
		}

		curl_setopt($this->ch, CURLOPT_POST, TRUE);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $PostData);
		curl_setopt($this->ch, CURLOPT_URL, str_replace("&amp;", "&", $Url));
		return $this->Exec();
	}

	public function dieCurl(){
		@curl_close($this->ch);
		unset($this->ch);
	}
}

?>