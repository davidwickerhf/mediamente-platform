<div class="page-header">
		<h1 ><?=$var_in_view['pageTitle']  ?></h1>
		<div class="dropdown" style=" margin-left:10px">
          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-option-vertical"></span>
          </button>
    	  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
              <li><a href="<?=SERV_URL?>turni/aggiungiGruppoTurni" ><span class="glyphicon glyphicon-plus"></span>Aggiungi progetto turni</a></li>
          </ul>
        </div>
</div>
<div class="table-responsive" style="max-width:1200px">
	<table id="turni" class="table table-striped">
		<thead>
			<tr>
                <th>Nome Team</th>
				<th>Codice Cliente</th>
				<th>Id Commessa</th>
				<th>Nome Progetto</th>
				<th>Nome Progetto Turni</th>
				<th>Priorita</th>
				<th>Tipo Turni</th>
				<th>Giorno Inizio</th>
				<th>Alloca Utente</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
		<?php foreach($var_in_view['turni'] as $t):?>
			<tr id="row<?=$t->id?>">
                <td id="row_<?=$t->id?>_nome_team"><?=$t->nome_team?></td>
		  		<td id="row_<?=$t->id?>_codice_cliente"><?=$t->codice_cliente?></td>
				<td id="row_<?=$t->id?>_id_commessa"><?=$t->id_commessa?></td>
				<td id="row_<?=$t->id?>_nome_progetto"><?=$t->nome_progetto?></td>
				<td id="row_<?=$t->id?>_nome_progretto_turni">
					<a target="_self" href="/turni/visualizza/<?=$t->id?>">
					<?=$t->nome_progretto_turni?>
					</a>
				</td>
				<td id="row_<?=$t->id?>_priorita"><?=$t->priorita?></td>
				<td id="row_<?=$t->id?>_tipo_turni"><?=$t->tipo_turni?></td>
				<td id="row_<?=$t->id?>_giorno_inizio"><?=$t->giorno_inizio?></td>
				<td id="row_<?=$t->id?>_alloca_utente"><?=$t->alloca_utente?></td>
				<td><button style="margin-top:0px" class="btn btn-danger btn-xs" onClick="rimuoviturni(<?=$t->id?>)"><span class="glyphicon glyphicon-remove"></span></button></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>

<!-- Modal aggiunta progetto turni -->
<div class="modal fade" id="aggiungiModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Aggiungi Progetto Turni</h4>
      </div>
      <div class="modal-body">
			<div id="ajaxSubmitaggiungiturniModal" class="alert" style="display: none"></div>
			<form role="form" id="ajaxFormaggiungiturniModal">
                	<div  id="nome_container" class="form-group required">
                    	<label class="control-label">Nome Progetto</label>
                    	<input id="nome" class="form-control" type="text" required />
                	</div>
					<div id="progetto_container" class="form-group required">
    					<label class="control-label">Progetto</label>
    					<select id="progetto" required class="form-control">
    						<option value="">Seleziona Progetto</option>
    						<?php foreach($var_in_view['lista_progetti'] as $r): ?>
    							<option value="<?=$r->id?>" id="progetto-<?=$r->id?>"><?=$r->nome?></option>
    						<?php endforeach;?>
    					</select>
    				</div>
					<div id="team_container" class="form-group required">
    					<label class="control-label">Team</label>
    					<select id="team" required class="form-control">
    						<option value="">Seleziona Team</option>
    						<?php foreach($var_in_view['lista_team'] as $r): ?>
    							<option value="<?=$r->id?>" id="team-<?=$r->id?>"><?=$r->nome?></option>
    						<?php endforeach;?>
    					</select>
    				</div>
					<div  id="priorita_container" class="form-group required">
                    	<label class="control-label">Priorità</label>
                    	<input id="priorita" class="form-control" type="number" min="0" />
                	</div>
					<div  id="tipo_turni_container" class="form-group required">
                    	<label class="control-label">Tipo Turni</label>
                    	<select id="tipo_turni" required class="form-control">
    						<option value="">Seleziona Tipo Turni</option>
    						<option value="ATOMICI" id="tipo_turni_ATOMICI">ATOMICI</option>
							<option value="COMPOSTI" id="tipo_turni_COMPOSTI">COMPOSTI</option>
    					</select>
                	</div>
					<div  id="giorni_inizio_container" class="form-group required">
                    	<label class="control-label">Giorno Inizio</label>
                    	<input id="giorno_inizio" class="form-control" type="number" min="1" max="7"/>
                	</div>
					<div  id="alloca_utente_container" class="form-group required">
                    	<label class="control-label">Alloca Utente</label>
                    	<input id="alloca_utente" style="margin-left:15px;" type="checkbox" checked />
                	</div>
			</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
        <button type="button" id="btnaggiungiturni" class="btn btn-primary" >Aggiungi</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).keypress(function(e) {
	  if ($("#aggiungiturniModal").hasClass('in') && (e.keycode == 13 || e.which == 13)) {
		  e.preventDefault();
		  $("#btnaggiungiturni").click();
	  }
	});
$('#aggiungiturniModal').on('shown.bs.modal', function () {
    $('#nome').focus();
})  
</script>
<script>
$(document).ready(function(){
	datatab=$('#turni').DataTable(
    		{
    			lengthMenu: [[20, 50, 100, 200], [20, 50, 100, 200]],
    			select: false,
    			searching:true,
    			"language": {
    	            "lengthMenu": "Visualizza _MENU_ record per pagina",
    	            "zeroRecords": "Nessun record trovato",
    	            "info": "Pagina _PAGE_ di _PAGES_",
    	            "infoEmpty": "Nessuna record trovato",
    	            "infoFiltered": "(filtrati da un totale di _MAX_ record)"
    	        },
    	        "order": [[ 1, "asc" ]],
    		}
    	    );
	  datatab.draw();
});

</script>
<?php 
ajaxFunction(1, "turni", "rimuoviGruppoTurno", Array("id_turno"), "rimuoviGruppoTurno", '$("#row"+id_turno).hide();',null,"Rimuovere il turno?");
ajaxSubmit("aggiungiturniModal", "turni", "aggiungiGruppoTurno", Array(
    "nome"=>"$('#nome').val()",
	"progetto"=>"$('#progetto').val()",
	"team"=>"$('#team').val()",
	"priorita"=>"$('#priorita').val()",
	"tipo_turni"=>"$('#tipo_turni').val()",
	"giorno_inizio"=>"$('#giorno_inizio').val()",
	"alloca_utente"=>"$('#alloca_utente').val()"
    ), "btnaggiungiturni", 'location.reload(1);');
?>