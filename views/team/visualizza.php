<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?></h1>
		<div class="dropdown" style=" margin-left:10px">
          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-option-vertical"></span>
          </button>
          <a class="btn btn-default" href="<?=SERV_URL?>team/visualizzaAllocazione/<?=$var_in_view['team']->{'id'}?>">
            <span class="glyphicon glyphicon-dashboard"></span> Allocazione
          </a>
    	  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
              <li><a style="cursor:pointer" data-toggle="modal" data-target="#aggiungiUtenteModal" ><span class="glyphicon glyphicon-plus"></span> Aggiungi utente</a></li>
          </ul>
        </div>
</div>

<div id="ajaxSubmit1" class="alert" style="display:none; max-width:200px"></div>
<ol class="breadcrumb">
<li><a href="<?=SERV_URL?>team">Team</a></li>
<li class="active"><?=$var_in_view['team']->{'nome'}?></li>
</ol>
<hr>
<div class="table-responsive">
	<table class="table table-striped" style="max-width:500px">
		<thead>
			<tr>
				<th>Username</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
		<?php foreach($var_in_view['utentiTeam'] as $t):?>
			<tr id="row<?=$t->username?>">
				<td><?=$t->cognome?> <?=$t->nome?> (<?=$t->username?>)</td>
				<td><button style="margin-top:0px" class="btn btn-danger btn-xs" onClick="rimuoviUtente(<?=$t->id_team?>,'<?=$t->username?>')"><span class="glyphicon glyphicon-remove"></span></button></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>
<!-- Modal aggiunta utente -->
<div class="modal fade" id="aggiungiUtenteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Aggiungi utente</h4>
      </div>
      <div class="modal-body">
			<div id="ajaxSubmitaggiungiUtenteModal" class="alert" style="display: none"></div>
			<form role="form">
			       			      				
                	<div class="form-group required">
                    	<label class="control-label">Utente</label>
                    	<select class="form-control" id="username">
                    	<?php foreach($var_in_view['utenti'] as $u):?>
                    		<option value="<?=$u->username?>"><?=$u->cognome?> <?=$u->nome?> (<?=$u->username?>)</option>
                    	<?php endforeach;?>
                    	</select>
                	</div>
			
			</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
        <button type="button" id="btnaggiungiUtente" class="btn btn-primary" onClick="aggiungiUtente(<?=$var_in_view['team']->{'id'}?>,$('#username').val())">Aggiungi</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).keypress(function(e) {
	  if ($("#aggiungiUtenteModal").hasClass('in') && (e.keycode == 13 || e.which == 13)) {
		  e.preventDefault();
		  aggiungiUtente(<?=$var_in_view['team']->{'id'}?>,$('#username').val())
	  }
	});
$('#aggiungiUtenteModal').on('shown.bs.modal', function () {
    $('#username').focus();
})  
</script>
<?php 
ajaxFunction(1, "team", "rimuoviUtente", Array("id_team","username"), "rimuoviUtente", '$("#row"+username).hide();', null,'Rimuovere l\'utente "+username+"?');
ajaxFunction(2, "team", "aggiungiUtente", Array("id_team","username"), "aggiungiUtente", 'location.reload(1);');
?>