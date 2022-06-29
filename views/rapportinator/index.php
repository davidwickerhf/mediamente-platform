<div class="page-header">
		<img src="<?=SERV_URL?>dist/images/rapportinator.svg" title="Rapportinator" style="height:50px"/>
</div>

<?php 


$filetype=Array("application/vnd.ms-excel");
$fileext=Array("xls");
$uniqueID=uniqid();

?>

<div style="max-width: 500px">
	<form id="ajaxForm1">
		<div id="ajaxSubmit1" class="alert" style="display: none"></div>
		<p>
		<input type="file" id="fileElem2" accept="<?=$filetype[0]?>" style="display:none" onchange="handleFiles(this.files)">
			<a href="#" class="btn btn-primary btn-file" id="fileSelect">1. Carica INTERVENTI.XLS</a><br/>
		<div id="fileList">
			</div>
		</p>
		<button onClick="location.href='<?=SERV_URL?>rapportinator/download/<?=$uniqueID?>'" class="btn btn-primary"  type="button" id="btnDownload" disabled="disabled">2. Download!</button>
	</form>
</div>

 <div id='ajax_loader' class="loading" style="opacity:.90; padding-left:40% !important">
		<div class="loading-inner">
			<img src="/dist/images/rapportinator.gif" />
		</div>
</div>

<?php



ajaxFileUpload(2,"rapportinator","elabora", Array (
    "uniqueid"=>"'".$uniqueID."'"
    ),$filetype,$fileext,"
            $('#fileSelect').attr('disabled','disabled'); 
            $('#fileUploadBtn').attr('disabled','disabled'); 
            $('#btnDownload').attr('disabled','').removeAttr('disabled');");

?>