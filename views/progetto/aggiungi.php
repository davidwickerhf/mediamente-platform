<?php $p=$var_in_view['progetto']; ?>


<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?></h1>
</div>
<ol class="breadcrumb">
<li><a href="<?=SERV_URL?>progetto">Progetti</a></li>
<?php if(!$p):?>
<li class="active">Aggiungi</li>
<?php else:?>
<li><a href="<?=SERV_URL?>progetto/visualizza/<?=$p->id?>"><?=$p->nome?></a></li>
<li class="active">Modifica</li>
<?php endif; ?>
</ol>
<div id="ajaxSubmit1" class="alert" style="display: none"></div>
<form role="form" id="ajaxForm1" style="max-width:500px">
			     			      				
                	<div id="nome_container" class="form-group required ">
                    	<label class="control-label">Nome </label>
                    	<input value="<?=$p->nome ?>" id="nome" class="form-control" type="text" required />
                	</div>
                	<div id="id_calendario_container" class="form-group required ">
                    	<label class="control-label">Calendario</label>
                    	<select class="form-control selectpicker"  data-size=6 data-live-search="true" id="id_calendario">
                    	<option value="">Seleziona</option>
                    	<?php foreach($var_in_view['calendari'] as $c):?>
                    		<option value="<?=$c->id?>" <?=($p->id_calendario==$c->id ? "selected" : "")?>><?=$c->nome ?></option>
                    	<?php endforeach;?>
                    	</select>
                	</div>
                	<div id="referente_container" class="form-group required ">
                    	<label class="control-label">Referente</label>
                    	<select class="form-control selectpicker"  data-size=6 data-live-search="true" id="referente">
                    	<?php foreach($var_in_view['utenti'] as $u):?>
                    		<option <?=(!$p->referente && $u->username==getMyUsername() ? 'selected' : '')?> <?=($p->referente==$u->username ? "selected" : "")?> value="<?=$u->username?>"><?=$u->cognome?> <?=$u->nome?> (<?=$u->username?>)</option>
                    	<?php endforeach;?>
                    	</select>
                	</div>
                	<div id="codice_cliente_container" class="form-group required ">
                    	<label class="control-label">Cliente</label>
                    	<select class="form-control selectpicker"  data-size=6 data-live-search="true" id="codice_cliente">
                    	<option value="">Seleziona</option>
                    	<?php foreach($var_in_view['clienti'] as $c):?>
                    		<option <?=($p->codice_cliente==$c->codice_cliente ? "selected" : "")?> value="<?=$c->codice_cliente?>"><?=$c->codice_cliente?> <?=htmlentities($c->nome)?></option>
                    	<?php endforeach;?>
                    	</select>
                	</div>
                	<div id="id_commessa_container" class="form-group ">
                    	<label class="control-label">Commessa</label>
                    	<select class="form-control selectpicker"  data-size=6 data-live-search="true" id="id_commessa">
                    	</select>
                	</div>
                	<div id="data_inizio_container" class="form-group required ">
                    	<label class="control-label">Data inizio</label>
                    	<input value="<?=$p->data_inizio ?>" id="data_inizio" class="form-control" type="text" placeholder="yyyy-mm-dd" required />
                	</div>
                	<div id="data_fine_container" class="form-group required ">
                    	<label class="control-label">Data fine</label>
                    	<input value="<?=$p->data_fine ?>" id="data_fine" class="form-control" type="text" placeholder="yyyy-mm-dd" required />
                	</div>
                	<div style="display:none" id="priorita_container" class="form-group required ">
                    	<label class="control-label">Priorit&agrave;</label>
                    	<input value="<?=($p->priorita ? $p->priorita : "0") ?>" id="priorita" class="form-control" placeholder="0-100" type="number" required />
                	</div>
			
			</form>
			<button type="button" id="btnaggiungiprogetto" class="btn btn-primary"><?=($p->id ? "Salva" : "Aggiungi")?></button>
<?php 


ajaxSubmit ( 1, "progetto", "aggiungiProgetto", Array (
    "id"=>($p->id ?: 0),
    "nome" => "$('#nome').val()",
    "id_calendario" => "$('#id_calendario').val()",
    "id_commessa" => "$('#id_commessa').val()",
    "referente" => "$('#referente').val()",
    "codice_cliente" => "$('#codice_cliente').val()",
    "data_inizio" => "$('#data_inizio').val()",
    "data_fine" => "$('#data_fine').val()",
    "priorita" => "$('#priorita').val()",
    ), "btnaggiungiprogetto", "location.href='".SERV_URL."progetto/visualizza/'+obj.id"
    );

ajaxFunction(1, "cliente", "getCommesseCliente", Array("id"), "getCommesseCliente", "
        
        $('#id_commessa')
            .find('option')
            .remove()
            .end()
        ;
         $('#id_commessa').append($('<option>', {
                value: null,
                text: 'Seleziona'
            }));
        jQuery.each(obj.commesse, function(id, val)  {
        $('#id_commessa').append($('<option>', {
                value: val.id,
                text: val.nome
            }));
        ".($p->id_commessa ? " $('#id_commessa').val(".$p->id_commessa.")": "" )."
        });
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('render');
    ");
?><script>

$("#codice_cliente").on("change",function(){

	getCommesseCliente($("#codice_cliente").val());
	
	
});

$(document).ready(function(){
<?php if($p->codice_cliente):?>
getCommesseCliente('<?=$p->codice_cliente?>');
<?php endif;?>
$('#codice_cliente').selectpicker();
$('#id_calendario').selectpicker();
$('#referente').selectpicker();
});
</script>