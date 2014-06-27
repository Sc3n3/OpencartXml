<?php

function getHeader(){
	global $PageTitle, $LoadCSS, $LoadJS;

	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$PageTitle?></title>
<link href="Interface/themes/default/style.css" rel="stylesheet" type="text/css" />
<?php

if($LoadCSS != ""){
	if(!is_array($LoadCSS)){
		$CSS = $LoadCSS;
		$LoadCSS = Array($CSS);
	}
	
	foreach($LoadCSS as $File){
		if($File != ""){
			echo "<link href=\"Interface/themes/default/Css/". $File ."\" rel=\"stylesheet\" type=\"text/css\" />\n";
		}
	}
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<?php

if($LoadJS != ""){
	if(!is_array($LoadJS)){
		$JS = $LoadJS;
		$LoadJS = Array($JS);
	}
	
	foreach($LoadJS as $File){
		if($File != ""){
			echo "<script src=\"". $File ."\"></script>\n";
		}
	}
}

?>

</head>

<body>
<div id="topPan">
<ul>
	<li><a href="#">Giriş</a></li>
	<li class="register"><a href="#" class="register">resister</a></li>
</ul>
<a href="index.html"><img src="Interface/themes/default/images/logo.gif" alt="Business Events"
	width="281" height="56" border="0" class="logo" title="<?=$PageTitle?>" /></a>
</div>
<div id="headerPan">

<div id="headermiddlePan">
<div id="menuPan">
	<center>
	<div style="margin:0 auto; padding-top: 2px; width: 660px;">
		<ul>
			<li><a href="?">Anasayfa</a></li>
			<li><a href="?page=category">Kategoriler</a></li>
			<li><a href="?page=schemas">İlan Şablonları</a></li>
			<li><a href="#">Support</a></li>
			<li><a href="#">Testimonials</a></li>
			<li class="contact"><a href="#" class="contact">Contact</a></li>
		</ul>
	</div>
	</center>
</div>

</div>

</div>
<div id="bodyPan"><?php

}

function getFooter(){

	?></div>
<div id="bodybottomPan">
<!-- 
<div id="bottomleftPan">
<h2>about services <br />
<span>dapibus sit amet, aliquet</span></h2>
<ul>
	<li><a href="#">Dapibus vitae,vehicula vitaea</a></li>
	<li><a href="#">Anteftr congue vel,risus.</a></li>
	<li><a href="#">Pede.fringilla,quam utfacilisis</a></li>
	<li><a href="#">Consequat dtrer.</a></li>
</ul>
<p class="more"><a href="#">want to know more solutions</a></p>
</div>

<div id="bottomrightPan">
<h2>about services <br />
<span>dapibus sit amet, aliquet</span></h2>
<ul>
	<li><a href="#">Dapibus vitae,vehicula vitaea</a></li>
	<li><a href="#">Anteftr congue vel,risus.</a></li>
	<li><a href="#">Pede.fringilla,quam utfacilisis</a></li>
	<li><a href="#">Consequat dtrer.</a></li>
</ul>
<p class="more"><a href="#">want to know more solutions</a></p>
</div>  -->
</div>

<div id="footermainPan">
<div id="footerPan">
<ul>
	<li><a href="#">Home</a>|</li>
	<li><a href="#">About Us</a>|</li>
	<li><a href="#">Services</a>|</li>
	<li><a href="#">Support</a>|</li>
	<li><a href="#">Testimonials</a>|</li>
	<li><a href="#">Contact</a></li>
</ul>
<p class="copyright">©business events. All right reserved.</p>
<div id="footerPanhtml"><a
	href="http://validator.w3.org/check?uri=referer" target="_blank">XHTML</a></div>
<div id="footerPancss"><a
	href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank">css</a></div>
</div>
</div>
</body>
</html>
	<?php

}

?>