<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
	include_once(MODEL_PATH."/SchemaModel.php");
	
	$SchemaMdl = new SchemaModel();
	$SchemaMdl->setName($_POST["SchemaName"]);
	$SchemaMdl->setListDay((integer) $_POST["ListDay"]);
	$SchemaMdl->setExtraPrice((integer) $_POST["AddPrice"]);
	$SchemaMdl->setMinSellPrice((integer) $_POST["MinSellPrice"]);
	$SchemaMdl->setMinSellStock((integer) $_POST["MinSellStock"]);
	$SchemaMdl->setSellCity($_POST["CargoCity"]);
	
	//Kargo Fiyat ve Ödeyen eklenecek
	if(is_array($_POST["Commision"])){
		$Keys = array_keys($_POST["Commision"]["StartPrice"]);
		$Data = $_POST["Commision"];
		
		foreach($Keys as $Key){
			$StartPrice = (integer) $Data["StartPrice"][$Key];
			$EndPrice   = (integer) $Data["EndPrice"][$Key];
			$Commision  = $Data["Commision"][$Key];
			
			$SchemaMdl->setCommision($StartPrice, $EndPrice, $Commision);
		}
	}
	
	if(is_array($_POST["CargoCompany"])){
		$Data = array_unique($_POST["CargoCompany"]);
		foreach($Data as $Cargo){
			$SchemaMdl->setCargoCompany($Cargo);
		}
	}
	
	$SchemaMdl->setCargoInfo($_POST["CargoInfo"]);
	
	$SchemaMdl->setExtraInfo($_POST["ExtraInfo"]);
	$SchemaMdl->setExtraLink($_POST["LinkText"], $_POST["LinkHref"]);
	$SchemaMdl->setAddStockCode($_POST["AddStockCode"]);
	$SchemaMdl->setBoldTitle($_POST["BoldText"]);
	$SchemaMdl->setCatalogImage($_POST["CatalogImage"]);
	
	$SchemaMdl->save();
	die;
}

?>
<form id="NewSchemaForm">
<table style="width: 700px;" align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
<td bgcolor="#C9C9C9">
<table class="MainTable" style="width: 700px;" cellpadding="2" cellspacing="1" border="0" align="center">
	<tr>
		<td>Şablon Adı</td>
		<td><input type="text" name="SchemaName" /></td>
	</tr>
	<tr>
		<td>Listeleme Günü</td>
		<td><select name="ListDay">
			<option>30</option>
			<option>60</option>
		</select></td>
	</tr>
	<tr>
		<td>Tüm ürünlere fiyat ekle</td>
		<td><input type="text" name="AddPrice" /></td>
	</tr>
	<tr>
		<td colspan="2">Komisyonlar</td>
	</tr>
	<tr>
		<td colspan="2">
		<table id="Commision" cellspacing="1" cellpadding="2" border="0" width="100%">
			<thead>
				<tr>
					<td class="InnerHead">Başlangıç Fiyat</td>
					<td>Bitiş Fiyat</td>
					<td>Komisyon</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
				<tr id="CommisionsTr">
					<td class="InnerHead"><input type="text" name="Commision[StartPrice][]" /></td>
					<td><input type="text" name="Commision[EndPrice][]" /></td>
					<td><input type="text" name="Commision[Commision][]" /></td>
					<td><a href="javascript:;" id="AddComission"><center><b>+</b></center></a></td>
				</tr>
			</tbody>
		</table>
		</td>
	</tr>
	<tr>
		<td>KDV Dahil Min. Satış Fiyatı</td>
		<td><input type="text" name="MinSellPrice" /></td>
	</tr>
	<tr>
		<td>Min. Ürün Adedi</td>
		<td><input type="text" name="MinSellStock" /></td>
	</tr>
	<tr>
		<td colspan="2">Gönderim</td>
	</tr>
	<tr>
		<td colspan="2">
		<table cellspacing="0" cellpadding="2" border="0" width="100%">
			<tr>
				<td class="InnerHead">Gönderim Şehri</td>
				<td>
					<select id="CargoCity" name="CargoCity">
						<option value="1">Adana</option>
						<option value="2">Adıyaman</option>
						<option value="3">Afyon</option>
						<option value="4">Ağrı</option>
						<option value="68">Aksaray</option>
						<option value="5">Amasya</option>
						<option value="6">Ankara</option>
						<option value="7">Antalya</option>
						<option value="75">Ardahan</option>
						<option value="8">Artvin</option>
						<option value="9">Aydın</option>
						<option value="10">Balıkesir</option>
						<option value="74">Bartın</option>
						<option value="72">Batman</option>
						<option value="69">Bayburt</option>
						<option value="0">Belirtilmemiş</option>
						<option value="11">Bilecik</option>
						<option value="12">Bingöl</option>
						<option value="13">Bitlis</option>
						<option value="14">Bolu</option>
						<option value="15">Burdur</option>
						<option value="16">Bursa</option>
						<option value="17">Çanakkale</option>
						<option value="18">Çankırı</option>
						<option value="19">Çorum</option>
						<option value="20">Denizli</option>
						<option value="21">Diyarbakır</option>
						<option value="82">Düzce</option>
						<option value="22">Edirne</option>
						<option value="23">Elazığ</option>
						<option value="24">Erzincan</option>
						<option value="25">Erzurum</option>
						<option value="26">Eskişehir</option>
						<option value="27">Gaziantep</option>
						<option value="28">Giresun</option>
						<option value="29">Gümüşhane</option>
						<option value="30">Hakkari</option>
						<option value="31">Hatay</option>
						<option value="76">Iğdır</option>
						<option value="32">Isparta</option>
						<option value="34">İstanbul</option>
						<option value="35">İzmir</option>
						<option value="85">K.K.T.C.</option>
						<option value="46">K.Maraş</option>
						<option value="78">Karabük</option>
						<option value="70">Karaman</option>
						<option value="36">Kars</option>
						<option value="37">Kastamonu</option>
						<option value="38">Kayseri</option>
						<option value="79">Kilis</option>
						<option value="71">Kırıkkale</option>
						<option value="39">Kırklareli</option>
						<option value="40">Kırşehir</option>
						<option value="41">Kocaeli</option>
						<option value="42">Konya</option>
						<option value="43">Kütahya</option>
						<option value="44">Malatya</option>
						<option value="45">Manisa</option>
						<option value="47">Mardin</option>
						<option value="33">Mersin</option>
						<option value="48">Muğla</option>
						<option value="49">Muş</option>
						<option value="50">Nevşehir</option>
						<option value="51">Niğde</option>
						<option value="52">Ordu</option>
						<option value="80">Osmaniye</option>
						<option value="53">Rize</option>
						<option value="54">Sakarya</option>
						<option value="55">Samsun</option>
						<option value="63">Şanlıurfa</option>
						<option value="56">Siirt</option>
						<option value="57">Sinop</option>
						<option value="73">Şırnak</option>
						<option value="58">Sivas</option>
						<option value="59">Tekirdağ</option>
						<option value="60">Tokat</option>
						<option value="61">Trabzon</option>
						<option value="62">Tunceli</option>
						<option value="64">Uşak</option>
						<option value="65">Van</option>
						<option value="77">Yalova</option>
						<option value="66">Yozgat</option>
						<option value="81">Yurtdışı</option>
						<option value="67">Zonguldak</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="InnerHead">Kargo Ödeme</td>
				<td>
					<input type="text" name="MinCargoPrice" /> üzeri 
					<select id="CargoPayment" name="CargoPayment">
						<option value="B">Alıcı Öder</option>
						<option value="S">Satıcı Öder</option>
						<option value="D">Açıklamadaki geçerli</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="InnerHead">Kargo Açıklama</td>
				<td><textarea name="CargoInfo"></textarea></td>
			</tr>
		</table>
		<table id="Cargo" cellspacing="0" cellpadding="2" border="0" width="100%">
			<tr id="CargoCompanyTr">
				<td class="InnerHead">Kargo</td>
				<td>
					<select id="CargoCompany" name="CargoCompany[]">
                        <option value="aras">Aras Kargo</option>
                        <option value="dhl">DHL Kargo</option>
                        <option value="express">Fedex Kargo</option>
                        <option value="fillo">Fillo Kargo</option>
                        <option value="mng">MNG Kargo</option>
                        <option value="ptt">PTT Kargo</option>
                        <option value="surat">Sürat Kargo</option>
                        <option value="ups">UPS Kargo</option>
                        <option value="varan">Varan Kargo</option>
                        <option value="yurtici">Yurtiçi Kargo</option>
                        <option value="other">Diğer Kargo</option>
                    </select>
				</td>
				<td>
					<a href="javascript:;" id="AddCargoCompany"><center><b>+</b></center></a>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>Ek Açıklama</td>
		<td><textarea name="ExtraInfo"></textarea></td>
	</tr>
	<tr>
		<td>Link Metini</td>
		<td><input type="text" name="LinkText" /></td>
	</tr>
	<tr>
		<td>Link Adresi</td>
		<td><input type="text" name="LinkHref" /></td>
	</tr>
	<tr>
		<td>Stok Kodu Başlığa Eklensin</td>
		<td><input type="checkbox" name="AddStockCode" value="1" /></td>
	</tr>
	<tr>
		<td>Katalog Resmi</td>
		<td><input type="checkbox" name="CatalogImage" value="1" /></td>
	</tr>
	<tr>
		<td>Kalın Yazı</td>
		<td><input type="checkbox" name="BoldText" value="1" /></td>
	</tr>	
	<tr>
		<td colspan="2" align="center"><input type="button" id="ConfirmButton" value="Şablonu Kaydet" /></td>
	</tr>
</table>
</td>
</tr>
</table>
</form>
