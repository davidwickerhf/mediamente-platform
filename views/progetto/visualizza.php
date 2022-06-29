<?php 

$progetto=$var_in_view['progetto'];
$calendario=$var_in_view['calendario'];
$commessa=$var_in_view['commessa'];
$cliente=$var_in_view['cliente'];

?>
<div class="page-header">
				<h1><?=$var_in_view['pageTitle']  ?> <span id="externalTitle"></span></h1>
	<div class="dropdown" style="margin-left: 10px">
		<button class="btn  btn-default dropdown-toggle" type="button"
			id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
			aria-expanded="true">
			<span class="glyphicon glyphicon-option-vertical"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			<li><a href="<?=SERV_URL?>progetto/aggiungi/<?=$progetto->id?>" ><span class="glyphicon glyphicon-pencil"></span> Modifica progetto</a></li>
			<li><a id="btnrimuoviprogetto" style="cursor:pointer" class="bg-danger" ><span
					class="glyphicon glyphicon-remove"></span> Rimuovi progetto</a></li>
		</ul>
	</div>
</div>
<ol class="breadcrumb">
<li><a href="<?=SERV_URL?>progetto">Progetti</a></li>
<li class="active"><?=$var_in_view['progetto']->{'nome'}?></li>
</ol>
<div id="ajaxSubmit1" class="alert" style="display: none"></div>
<h3>Dati progetto</h3>
<p><strong>Nome: </strong><?=$progetto->nome?></p>
<p><strong>Referente: </strong><?=$progetto->referente?></p>
<p><strong>Calendario: </strong><a href="<?=SERV_URL?>calendario/visualizza/<?=$calendario->id?>"><?=$calendario->nome?></a></p>
<p><strong>Cliente: </strong><?=$cliente->codice_cliente?> <?=$cliente->nome?></p>
<p><strong>Commessa: </strong><?=$commessa->nome?></p>
<p><strong>Data inizio: </strong><?=$progetto->data_inizio?></p>
<p><strong>Data fine: </strong><?=$progetto->data_fine?></p>
<hr/>
<h3  >Team assegnati </h3>
	<div class="dropdown" style=" margin-left: 10px">
		<button class="btn btn-xs btn-default dropdown-toggle" type="button"
			id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true"
			aria-expanded="true">
			<span class="glyphicon glyphicon-option-vertical"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
			<li><a style="cursor:pointer" data-toggle="modal" data-target="#aggiungiTeamModal" ><span class="glyphicon glyphicon-plus"></span> Aggiungi team</a></li>
		</ul>
	</div>
<div class="table-responsive">
	<table class="table table-striped" style="max-width:500px">
		<thead>
			<tr>
				<th>Nome</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
		<?php foreach($var_in_view['progetto_team'] as $t):?>
			<tr id="row<?=$t->id?>">
				<td><?=$t->nome ?></td>
				<td><button style="margin-top:0px" class="btn btn-danger btn-xs" onClick="rimuoviTeam(<?=$progetto->id?>,<?=$t->id?>)"><span class="glyphicon glyphicon-remove"></span></button></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>
<div class="modal fade" id="aggiungiTeamModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Aggiungi team</h4>
      </div>
      <div class="modal-body">
			<div id="ajaxSubmitaggiungiTeamModal" class="alert" style="display: none"></div>
			<form role="form">
			       			      				
                	<div class="form-group required">
                    	<label class="control-label">Team</label>
                    	<select class="form-control" id="id_team">
                    	<?php foreach($var_in_view['team'] as $t):?>
                    		<option value="<?=$t->id?>"><?=$t->nome?></option>
                    	<?php endforeach;?>
                    	</select>
                	</div>
			
			</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
        <button type="button" id="btnaggiungiUtente" class="btn btn-primary" onClick="aggiungiTeam(<?=$progetto->id?>,$('#id_team').val())">Aggiungi</button>
      </div>
    </div>
  </div>
</div>
<?php 
ajaxSubmit ( 1, "progetto", "rimuoviProgetto", Array (
    "id"=>$progetto->id
    ), "btnrimuoviprogetto", "location.href='".SERV_URL."progetto'",
    null,
    "Rimuovere il progetto?"
    );
ajaxFunction(1, "progetto", "rimuoviTeam", Array("id","id_team"), "rimuoviTeam", '$("#row"+id_team).hide();', null,'Rimuovere il team ?');
ajaxFunction('aggiungiTeamModal', "progetto", "aggiungiTeam", Array("id","id_team"), "aggiungiTeam", 'location.reload(1);');
?>