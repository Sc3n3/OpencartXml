<?php

include_once( MODEL_PATH ."/CategoryConnectorModel.php");
$ConMdl = new CategoryConnectorModel();

if($_POST["action"] == "delete"){
	$ConMdl->setID((integer) $_POST["itemID"]);
	
	if($ConMdl->delCatConn()){
		echo "true";
	} else {
		echo "false";
	}
	die;
}

if($_POST["action"] == "changestatus"){
	$ConMdl->setID((integer) $_POST["itemID"])->getSingle();
	$ConMdl->setOnSale((integer) $_POST["status"]);
	
	if($ConMdl->save()){
		echo "true";
	} else {
		echo "false";
	}
	die;
}

$List = $ConMdl->getConnectedCategoryList();

if(count($List) < 1){
	echo "<div style=\"text-align:center\"><h1>Eşleştrilmiş Kategori Bulunamadı!</h1></div>";
	die;
}
?>
<div id="CatListTable">
<table width="100%" border="0" cellspacing="1" cellpadding="2"> 
<tr class="TableHead">
	<td>###</td>
	<td>Tedarikçi</td>
	<td>Tedarikçi Kat.</td>
	<td>Satıcı</td>
	<td>Satıcı Kat.</td>
	<td>Şablon</td>
	<td>Durum</td>
	<td>İşlem</td>
</tr>
<?php
foreach($List as $ID => $Data){
	echo "<tr class=\"ListItem\" itemid=\"". $ID ."\" suppid=\"". $Data["tedarikci_id"] ."\" sellid=\"". $Data["satici_id"] ."\" suppcatid=\"". $Data["tedarikci_cat_id"] ."\" sellcatid=\"". $Data["satici_cat_id"] ."\">";
	echo "<td style=\"text-align:center\">". $ID ."</td>";
	echo "<td>". $Data["suppname"] ."</td>";
	echo "<td>". $Data["suppcatname"] ."</td>";
	echo "<td>". $Data["sellname"] ."</td>";
	echo "<td>". $Data["sellcatname"] ."</td>";
	echo "<td>". $Data["schemaname"] ."</td>";
	
	if($Data["on_sale"] == "1"){
		$Durum = "Açık";
	} else {
		$Durum = "Kapalı";
	}
	
	echo "<td style=\"text-align:center\"><a href=\"javascript:;\" itemid=\"". $ID ."\" id=\"StatusCat\" status=\"". $Data["on_sale"] ."\">". $Durum ."</a></td>";
	echo "<td style=\"text-align:center\"><a href=\"javascript:;\" id=\"DeleteCat\" itemid=\"". $ID ."\">Sil</a></td>";
	echo "</tr>";
}

?>
</table>
</div>