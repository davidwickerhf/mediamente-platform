<?php $p=$var_in_view['calendario']; ?>
<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?></h1>
</div>
<ol class="breadcrumb">
<li><a href="<?=SERV_URL?>calendario/visualizzaUtente/<?=getMyUsername()?>">Calendario personale</a></li>
<li><a href="<?=SERV_URL?>calendario/importExport">Import/Export calendari</a></li>
<?php if(!$p):?>
<li class="active">Aggiungi</li>
<?php else:?>
<li class="active">Modifica</li>
<?php endif; ?>
</ol>
<div id="ajaxSubmit1" class="alert" style="display: none"></div>
<div style="max-width:500px" class="alert alert-info">
<strong>Tip!</strong> Vai a <a target="_blank" href="https://outlook.office365.com/mail/options/calendar/SharedCalendars/publishedCalendars">questo link</a> per trovare l'URL del calendario di outlook in ICS.
<br/>Nella sezione <strong>Publish calendar</strong> seleziona il tuo calendario e <strong>Can view all the details</strong> poi copia l'URL ICS.<br/>
Puoi importare qualsiasi calendario che pubblica un URL in formato ICS.<br/>
<strong>Puoi sincronizzare questi calendari quando vuoi tramite il relativo tasto, ma questa operazione viene effettuata automaticamente con cadenza periodica.</strong>
</div>
<form role="form" method="POST" action="#" id="ajaxForm1" style="max-width:500px">
			     			      				
                	<div id="nome_container" class="form-group required ">
                    	<label class="control-label">Nome </label>
                    	<input value="<?=$p->nome ?>" id="nome" class="form-control" type="text" required />
                	</div>
                	<div id="url_container" class="form-group required ">
                    	<label class="control-label">URL ICS</label>
                    	<input value="<?=$p->url ?>" id="url" class="form-control" type="text" required />
                	</div>
			
			</form>
			<button type="submit" id="btnaggiungicalendario" class="btn btn-primary"><?=($p->id ? "Salva" : "Aggiungi")?></button>
			
<script>
	$("#colore").on('change',function(){
		if($("#colore").val()!='')
			$("#colore").css("color",$("#colore").val());
		else
			$("#colore").css("color",'#000000');
	});
</script>

<?php 


ajaxSubmit ( 1, "calendario", "aggiungiCalendarioEsterno", Array (
    "id"=>($p->id ?: 0),
    "nome" => "$('#nome').val()",
    "url"=>"$('#url').val()"
    ), "btnaggiungicalendario", "location.href='".SERV_URL."calendario/importExport'"
    );

?>