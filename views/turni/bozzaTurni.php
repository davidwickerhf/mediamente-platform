<link href="<?=SERV_URL?>dist/bootstrap-toggle/bootstrap-toggle.min.css" rel="stylesheet">
<script src="<?=SERV_URL?>dist/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?></h1>
</div>
<ol class="breadcrumb">
<li><a href="<?=SERV_URL?>turni">Turni</a></li>
</ol>

<button type="button" id="btnRimuoviBozzaTurni" class="btn btn-primary">Rimuovi Bozza</button>

<button type="button" id="btnApprovaBozzaTurni" class="btn btn-primary">Approva Bozza</button>

<br /><br />


<pre>
<?php echo $var_in_view['debugLog']; ?>
</pre>



<?php 

$location = SERV_URL.'turni';

ajaxSubmit ( 1, "turni", "approvaBozzaTurni", Array (
    "uniqsessid_bozza"=>"'".$var_in_view['uniqsessid_bozza']."'"
), "btnApprovaBozzaTurni", "location.href='$location'"
    );


ajaxSubmit ( 1, "turni", "rimuoviBozzaTurni", Array (
    "uniqsessid_bozza"=>"'".$var_in_view['uniqsessid_bozza']."'"
), "btnRimuoviBozzaTurni", "location.href='$location'"
    );

?>
