<?php $p=$var_in_view['calendario']; ?>
<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?></h1>
</div>
<ol class="breadcrumb">
<li><a href="<?=SERV_URL?>calendario">Calendari</a></li>
<?php if(!$p):?>
<li class="active">Aggiungi</li>
<?php else:?>
<li><a href="<?=SERV_URL?>calendario/visualizza/<?=$p->id?>"><?=$p->nome?></a></li>
<li class="active">Modifica</li>
<?php endif; ?>
</ol>
<script src="<?=SERV_URL?>dist/colorpicker/spectrum.min.js" ></script>
<link rel="stylesheet" type="text/css" href="<?=SERV_URL?>dist/colorpicker/spectrum.min.css" />
<div id="ajaxSubmit1" class="alert" style="display: none"></div>
<form role="form" method="POST" action="#" id="ajaxForm1" style="max-width:500px">
			     			      				
                	<div id="nome_container" class="form-group required ">
                    	<label class="control-label">Nome </label>
                    	<input value="<?=$p->nome ?>" id="nome" class="form-control" type="text" required />
                	</div>
                	<div id="colore_container" class="form-group required ">
                    	<label class="control-label">Colore </label>
                    	<input class="form-control" id="colore" value="<?=($p->colore ? $p->colore : '#ffffff')?>" />
                    	
                	</div>
                	<div id="colore_testo_container" class="form-group required ">
                    	<label class="control-label">Colore testo</label>
                    	<input class="form-control" id="colore_testo" value="<?=($p->colore_testo ? $p->colore_testo : '#000000')?>" />
                    	
                	</div>
			
			</form>
			<button type="submit" id="btnaggiungicalendario" class="btn btn-primary"><?=($p->id ? "Salva" : "Aggiungi")?></button>
			
<script>
	$('#colore').spectrum({
		  type: "component"
		});
	$('#colore_testo').spectrum({
		  type: "component"
		});
</script>

<?php 


ajaxSubmit ( 1, "calendario", "aggiungiCalendario", Array (
    "id"=>($p->id ?: 0),
    "nome" => "$('#nome').val()",
    "colore"=>"$('#colore').val()",
    "colore_testo"=>"$('#colore_testo').val()"
    ), "btnaggiungicalendario", "location.href='".SERV_URL."calendario'"
    );

?>