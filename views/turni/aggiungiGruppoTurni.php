<?php $p=$var_in_view['progetto']; ?>

<link href="<?=SERV_URL?>dist/bootstrap-toggle/bootstrap-toggle.min.css" rel="stylesheet">
<script src="<?=SERV_URL?>dist/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?></h1>
</div>
<ol class="breadcrumb">
<li><a href="<?=SERV_URL?>turni">Turni</a></li>
<?php if(!$p):?>
<li class="active">Aggiungi</li>
<?php else:?>
<li><a href="<?=SERV_URL?>turni/visualizza/<?=$p->id?>"><?=$p->nome?></a></li>
<li class="active">Modifica</li>
<?php endif; ?>
</ol>
<div id="ajaxSubmit1" class="alert" style="display: none"></div>
<form role="form" id="ajaxForm1" style="max-width:500px">
			     			      				
                	<div id="nome_container" class="form-group required ">
                    	<label class="control-label">Nome </label>
                    	<input value="<?=$p->nome ?>" id="nome" class="form-control" type="text" required />
                	</div>
                	<div id="id_cliente_container" class="form-group required ">
                    	<label class="control-label">Cliente</label>
                    	<select class="form-control selectpicker"  data-size=6 data-live-search="true" id="codice_cliente">
                    	<option value="">Seleziona</option>
                    	<?php foreach($var_in_view['clienti'] as $c):?>
                    		<option value="<?=$c->codice_cliente?>" <?=($c->codice_cliente == $var_in_view['id_cliente'] ? "selected" : "")?>><?=$c->codice_cliente?> - <?=$c->nome ?></option>
                    	<?php endforeach;?>
                    	</select>
                	</div>
                	<div id="id_progetto_container" class="form-group required ">
                    	<label class="control-label">Progetto</label>
                    	<select class="form-control selectpicker"  data-size=6 data-live-search="true" id="id_progetto">
                    	</select>
                	</div>
                	<div id="team_container" class="form-group required ">
                    	<label class="control-label">Team</label>
                    	<select class="form-control selectpicker"  data-size=6 data-live-search="true" id="id_team">
                    	</select>
                	</div>
                	<div id="tipo_turni_container" class="form-group required ">
                    	<label class="control-label">Tipo turni</label>
                    	<select class="form-control" id="tipo_turni">
                    		<option <?=$p->tipo_turni == "ATOMICI" ? "selected='selected'" : "" ?> value="ATOMICI">Atomici</option>
                    		<option <?=$p->tipo_turni == "COMPOSTI" ? "selected='selected'" : "" ?> value="COMPOSTI">Composti</option>
                    	</select>
                	</div>
					<div id="giorno_inizio_container" class="form-group required ">
                    	<label class="control-label">Giorno di inizio</label>
                    	<select class="form-control" id="giorno_inizio">
                    		<?php 
                    		$giorni=Array("","Luned&igrave","Marted&igrave;","Mercoled&igrave;","Gioved&igrave;","Venerd&igrave;","Sabato","Domenica");
                    		for($i=1;$i<8;$i++):?>
                    			<option value="<?=$i?>"><?=$giorni[$i]?></option>
                    		<?php endfor;?>
                    	</select>
                	</div>
                	<div  id="alloca_utente_container" class="form-group">
                    	<label class="control-label">Alloca utente?</label>
                    	<input type="checkbox" id="alloca_utente" checked data-toggle="toggle" data-on="s&igrave;" data-off="no"  data-size="small" >
                	</div>
			</form>
			<button type="button" id="btnaggiungigruppoturni" class="btn btn-primary"><?=($p->id ? "Salva" : "Aggiungi")?></button>
<?php 


ajaxSubmit ( 1, "turni", "aggiungiGruppoTurno", Array (
    "id"=>($p->id ?: 0),
    "nome" => "$('#nome').val()",
    "id_progetto" => "$('#id_progetto').val()",
    "id_team" => "$('#id_team').val()",
    "tipo_turni" => "$('#tipo_turni').val()",
    "giorno_inizio" => "$('#giorno_inizio').val()",
    "alloca_utente"=>"$('#alloca_utente').prop('checked')",
    ), "btnaggiungigruppoturni", "location.href='".SERV_URL."turni/visualizza/'+obj.id"
    );


?><script>

$(document).ready(function(){
<?php if($p->codice_cliente):?>
getCommesseCliente('<?=$p->codice_cliente?>');
<?php endif;?>
$('#codice_cliente').selectpicker();
$('#id_calendario').selectpicker();
$('#referente').selectpicker();
});

$("#codice_cliente").on("change",function(){
	getProgettiCliente($("#codice_cliente").val());
});

$("#id_progetto").on("change",function(){
	getTeamProgetto($("#id_progetto").val());
});
</script>


<?php 

ajaxFunction(1, "cliente", "getProgettiCliente", Array("id"), "getProgettiCliente", "
        $('#id_progetto')
            .find('option')
            .remove()
            .end()
        ;
        $('#id_progetto').append($('<option>', {
                value: '',
                text: 'Seleziona'
            }));
        jQuery.each(obj.progetti, function(id, val)  {
        $('#id_progetto').append($('<option>', {
                value: val.id,
                text: decodeEntities(val.nome)+ ' ('+val.id+')'
            }));
    
        });
        if(id_progetto!=0)
            $('#id_progetto').val(id_progetto);
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('render');
    ");
ajaxFunction(1, "progetto", "getTeamProgetto", Array("id"), "getTeamProgetto", "
        $('#id_team')
            .find('option')
            .remove()
            .end()
        ;
         $('#id_team').append($('<option>', {
                value: '',
                text: 'Seleziona'
            }));
        jQuery.each(obj.team, function(id, val)  {
        $('#id_team').append($('<option>', {
                value: val.id,
                text: decodeEntities(val.nome)
            }));
    
        });
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('render');
    ");

?>