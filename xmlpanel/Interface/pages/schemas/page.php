<?php

$PageTitle = "Şablon İşlemleri";
getHeader();

?>
<script type="text/javascript">

$(document).ready(function(){
	$("#NewSchema").click(function(){  $.get('?page=schemas&file=newschema',  function(data){ $('#pageMain').html(data); });});
	$("#EditSchema").click(function(){ $.get('?page=schemas&file=editschema', function(data){ $('#pageMain').html(data); });});
});

</script>
<style type="text/css">
<!--
.MainTable td {
	background-color:#FEFDF0;
}
.InnerHead {
	padding-left:40px;
}
-->
</style>
<script type="text/javascript">
<!--

$(document).ready(function(){
	$("#AddCargoCompany").live("click", function(){
		$(this).parent().parent().parent().append("<tr>"+ $(this).parent().parent().html() +"</tr>");
		$(this).attr("id","RemoveCargoCompany").html("<center><b>-</b></center>");
	});

	$("#RemoveCargoCompany").live("click", function(){
		$(this).parent().parent().remove();
	});
	
	$("#AddComission").live("click", function(){
		$(this).parent().parent().parent().append("<tr>"+ $(this).parent().parent().html() +"</tr>");
		$(this).attr("id","RemoveComission").html("<center><b>-</b></center>");
	});	

	$("#RemoveComission").live("click", function(){
		$(this).parent().parent().remove();
	});
	
	$("#ConfirmButton").live("click", function(){
		FormData = $("#NewSchemaForm").serialize();

		$.post("?page=schemas&file=newschema", FormData, function(data){
			alert(data);
		});
	});
});

//-->
</script>
<div style="text-align: center;">
	<a id="NewSchema" href="javascript:;">Şablon Ekle</a> | <a id="EditSchema" href="javascript:;">Şablonları Düzenle</a>
</div>
<br>
<div id="pageMain">
	<h3 style="text-align: center; padding-top: 25px;">Bu bölümden ilan şablonlarını yönetebilirsiniz.</h3>
</div>
<br style="clear:both;" />
<?php

getFooter();

?>