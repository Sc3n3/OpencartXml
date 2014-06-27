<?php

$PageTitle = "Kategori İşlemleri";
getHeader();

?>
<style type="text/css">
#CategoryExchangeTable {
	width: 700px;
	margin:0 auto;
}
#CategoryExchangeTable div#Seller, #CategoryExchangeTable div#Supplier {
	float:left;
	width:49%;
	text-align:center;
}
#CategoryExchangeTable div#Supplier {
	border-right:1px solid #C9C9C9;
}
#CategoryExchangeTable div#Seller {
	margin-left: 12px;
}
div#Content {
	height: 250px;
	background-color:#FFF;
	border:1px solid #C9C9C9;
	margin-top: 10px;
	width:90%;
	text-align:left;
	padding:10px;
	overflow:auto;
}
div#ConfirmDiv {
	width:100%;
	margin-top:15px;
	text-align:center;
}
input#Confirm, input#GetList {
	width:150px;
}
div#Categories {
	border:1px solid transparent;
	margin-bottom:3px;
	padding:2px;
}
div#Categories:hover {
	border:1px solid #C9C9C9;
	cursor:pointer;
}
.Upper {
	background-color:#EFEFEF;
}
.Selected {
	color:red;
	font-weight:bold;
}
#TextDiv {
	text-align:center;
	color: #000;
	font-size:22px;
	font-weight:bold;
	margin-top:20px;
	display:none;
}
#CatListTable {
	width:700px;
	text-align:center;
	margin:0 auto;
	border-color:#C9C9C9;
}
#CatListTable table {
	background-color:#C9C9C9;
}
.ListItem {
	background-color:#FEFDF0;
}
#CatListTable td {
	text-align:left;
	padding:5px;
}
#CatListTable .TableHead td {
	text-align:center;
	background-color:#EFEFEF;
}
.ListItem:hover {
	background-color:#E5E4DA;
}
#Schemas {
	display:none;
	text-align:center;
	margin:10px;
}
select {
	min-width:200px;
}
#MainCatList .SelectedPath {
	color: red;
}
#MainCatList li {
	margin-bottom:1px;
	padding:2px;
	color: black;
	z-index:3;
}
#MainCatList li:hover {
	color: red;
	cursor: pointer;
}
#MainCatList ul {
	margin-left:40px;
	padding:3px;
	display:none;
	position:absolute;
	background-color:#FEFDF0;
	border:1px solid #C9C9C9;
	z-index:3;
	min-width:250px;
	margin-top:-5px;
}

</style>
<script type="text/javascript">
var SuppID = "0";
var SellID = "0";

var SuppCatID = "0";
var SellCatID = "0";

$(document).ready(function(){
	$("#CategoryChooser").click(function(){ $.get('?page=category&file=category_chooser', function(data){ $('#pageMain').html(data); });});
	$("#CategoryUpdater").click(function(){ $.get('?page=category&file=category_updater', function(data){ $('#pageMain').html(data); });});
	$("#CategoryList").click(function()   { $.get('?page=category&file=category_list',    function(data){ $('#pageMain').html(data); });});

	$("select#Seller").live('change', function(){
		SellCatID = "0";
		SellID = $(this).attr('value');

		$("input#Confirm").removeAttr("disabled").attr("id","GetList").val("Kategorileri Getir");
		$("div#Schemas").hide();
		$("div#Supplier #Content").html("");
		$("div#Seller #Content").html("");
	});

	$("select#Supplier").live('change', function(){
		SuppCatID = "0";
		SuppID = $(this).attr('value');

		$("input#Confirm").removeAttr("disabled").attr("id","GetList").val("Kategorileri Getir");
		$("div#Schemas").hide();
		$("div#Supplier #Content").html("");
		$("div#Seller #Content").html("");
	});

	$("input#GetList").live("click", function(){
		if(SuppID == "0"){
			alert("Tedarikçi Seçiminde Hata!");
			return false;
		}

		if(SellID == "0"){
			alert("Satıcı Seçiminde Hata!");
			return false;
		}

		
		$("input#GetList").attr("id","Confirm").val("Seçimi Kaydet");
		$("div#Seller #Content").html('<center style="margin-top:95px;"><img src="Interface/images/loading_ajax_white.gif" /></center>');
		$("div#Supplier #Content").html('<center style="margin-top:95px;"><img src="Interface/images/loading_ajax_white.gif" /></center>');
		$("#Schemas").show();

		$.post("?page=category&file=category_chooser&action=CategoryList&type=0", {"DestID":SellID,"CrossID":SuppID}, function(data){
			$("div#Seller #Content").html(data);
		});

		$.post("?page=category&file=category_chooser&action=CategoryList&type=1", {"DestID":SuppID,"CrossID":SellID}, function(data){
			$("div#Supplier #Content").html(data);
		});
	});

	//-------------------------------------
	
	$("ul#MainCatList li").live("mouseenter", function(){
		$(this).find("ul:first").show();
	});

	$("ul#MainCatList li").live("mouseleave", function(){
		if($(this).find(".Selected").length < 1){
			$(this).find("ul:first").hide();
		}
	});

	$("ul#MainCatList a").live("click", function(){
		$("input#Confirm").removeAttr("disabled");
	});
	
	$("div#Supplier ul#MainCatList a").live("click", function(){
		SuppCatID = "0";
		$("div#Supplier ul#MainCatList .Selected").parentsUntil("ul#MainCatList").removeClass("SelectedPath").find("ul").css("z-index","3").hide();
		$("div#Supplier ul#MainCatList .Selected").removeClass("Selected");
		
		if($(this).parent().find("ul").prop("nodeName") != undefined){
			return false;
		}
		
		$(this).parentsUntil("ul#MainCatList").addClass("SelectedPath").find("ul").css("z-index","2");
		$(this).parentsUntil("ul#MainCatList").show();
		$(this).addClass("Selected");
		SuppCatID = $(this).attr("itemid");
	});

	$("div#Seller ul#MainCatList a").live("click", function(){
		SellCatID = "0";
		$("div#Seller ul#MainCatList .Selected").parentsUntil("ul#MainCatList").removeClass("SelectedPath").find("ul").css("z-index","3").hide();
		$("div#Seller ul#MainCatList .Selected").removeClass("Selected");
		
		if($(this).parent().find("ul").prop("nodeName") != undefined){
			return false;
		}
		
		$(this).parentsUntil("ul#MainCatList").addClass("SelectedPath").find("ul").css("z-index","2");
		$(this).parentsUntil("ul#MainCatList").show();
		$(this).addClass("Selected");
		SellCatID = $(this).attr("itemid");
	});
	
	//-------------------------------------
	
	$("input#Confirm").live('click', function(){
		$("input#Confirm").attr("disabled", "disabled");
		
		SellItem = $("div#Seller .Selected").attr("itemid");
		SuppItem = $("div#Supplier .Selected").attr("itemid");
		Schema   = $("select#Schema").val();

		if(SuppID == undefined && SellItem == undefined){
			alert("Kategoriler Doğru Seçilmedi!");
			return false;
		}
		
		if(SuppItem == undefined){
			alert("Tedarikçi Kategorisi Doğru Seçilmedi!");
			return false;
		}

		if(SellItem == undefined){
			alert("Satıcı Kategorisi Doğru Seçilmedi!");
			return false;
		}

		if(Schema == "0"){
			alert("Satış Şablonu Doğru Seçilmedi!");
			return false;
		}

		$.post("?page=category&file=category_chooser&action=SaveIt", {"SuppID":SuppID, "SellID":SellID, "SuppCatID":SuppItem, "SellCatID":SellItem, "SchemaID":Schema}, function(data){
			$("input#Confirm").attr("disabled", "disabled");

			if($("div#Supplier .Selected").children().prop("nodeName") != "S"){
				$("div#Supplier .Selected").html("<s>"+ $("div#Supplier .Selected").html() +"</s>");
			}
			
			$("div#TextDiv").html(data).fadeIn(1000).fadeOut(1000);
		});
	});
	
	$("#DeleteCat").live("click", function(){
		if(confirm("Silmek İstediğinize Emin Misiniz?")){
			DelItem = $(this).attr("itemid");
			$.post("?page=category&file=category_list", {"action":"delete", "itemID":DelItem}, function(data){
				if(data == "true"){
					$("tr.ListItem[itemid="+ DelItem +"]").fadeOut(200);
				}
				else {
					alert("Silme İşlemi Sırasında Bir Hata Oluştu!");
				}
			});
		}	
	});

	$("#StatusCat").live("click", function(){
		Item = $(this).attr("itemid");
		Status = $(this).attr("status");
		obj = $(this);

		if(Status == "1"){
			Status = "0";
			StatusText = "Kapalı";
		}
		else {
			Status = "1";
			StatusText = "Açık";
		}	
		
		$.post("?page=category&file=category_list", {"action":"changestatus", "itemID":Item ,"status":Status}, function(data){
			if(data == "true"){
				$(obj).attr("status", Status).html(StatusText);
			}
			else {
				alert("İşlem Sırasında Hata Oluştu!");
			}
		});
	});
});

</script>

<div style="text-align: center;">
	<a id="CategoryChooser" href="javascript:;">Kategori Eşleme</a> | <a id="CategoryUpdater" href="javascript:;">Kategori Liste Güncelleme</a> | <a id="CategoryList" href="javascript:;">Eşlenmiş Kategoriler</a>
</div>
<br>
<div id="pageMain">
	<h3 style="text-align: center; padding-top: 25px;">Bu bölümden kategorilerinizi yönetebilirsiniz.</h3>
</div>
<br style="clear:both;" />
<?php

getFooter();

?>