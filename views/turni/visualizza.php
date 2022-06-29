<div class="page-header">
		<h1 ><?=$var_in_view['pageTitle']  ?></h1>
		<div class="dropdown" style=" margin-left:10px">
          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-option-vertical"></span>
          </button>
    	  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
              <li><a style="cursor:pointer" data-toggle="modal" data-target="#aggiungiModal" ><span class="glyphicon glyphicon-plus"></span>Aggiungi turno</a></li>
              <li><a style="cursor:pointer" data-toggle="modal" data-target="#generaBozzaTurniModal" ><span class="glyphicon glyphicon-dashboard"></span>Genera calendario turni</a></li>
          </ul>
        </div>
</div> 
<div class="table-responsive" style="max-width:1200px">
	<table id="turni" class="table table-striped">
		<thead>
			<tr>
                <th>Nome Turno</th>
				<th>Giorno</th>
				<th>Inizio</th>
				<th>Fine</th>
				<th>Inizio Festivo</th>
				<th>Fine Festivo</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
		<?php foreach($var_in_view['turni'] as $t):?>
			<tr id="row<?=$t->id?>">
                <td id="row_<?=$t->id?>_nome_turno"><?=$t->nome?></td>
		  		<td id="row_<?=$t->id?>_giorno"><?=$t->giorno?></td>
				<td id="row_<?=$t->id?>_inizio"><?=$t->inizio?></td>
				<td id="row_<?=$t->id?>_fine"><?=$t->fine?></td>
				<td id="row_<?=$t->id?>_inizio_festivo"><?=$t->inizio_festivo?></td>
				<td id="row_<?=$t->id?>_fine_festivo"><?=$t->fine_festivo?></td>
				<td><button style="margin-top:0px" class="btn btn-danger btn-xs" onClick="rimuoviTurno(<?=$t->id?>)"><span class="glyphicon glyphicon-remove"></span></button></td>
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
                    	<label class="control-label">Nome Turno</label>
                    	<input id="nome" class="form-control" type="text" placeholder="Es. Turno / Presidio" required />
                	</div>
					<div  id="giorno_container" class="form-group required">
                    	<label class="control-label">Giorno</label>
                    	<input id="giorno" class="form-control" type="number" min="1" max="7"/>
                	</div>
					<div  id="inizio_container" class="form-group required">
                    	<label class="control-label">Inizio</label>
                        <input id="inizio" placeholder="Seleziona inizio" type="time" class="form-control">
                	</div>
					<div  id="fine_container" class="form-group required">
                    	<label class="control-label">Fine</label>
                        <input id="fine" placeholder="Seleziona fine" type="time" class="form-control">
                	</div>
					<div  id="inizio_festivo_container" class="form-group required">
                    	<label class="control-label">Inizio Festivo</label>
                        <input id="inizio_festivo" placeholder="Seleziona inizio festivo" type="time" class="form-control">
                	</div>
					<div  id="fine_festivo_container" class="form-group required">
                    	<label class="control-label">Fine Festivo</label>
                        <input id="fine_festivo" placeholder="Seleziona fine festivo" type="time" class="form-control">
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



<!-- Modal generazione turni -->
<div class="modal fade" id="generaBozzaTurniModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Genera bozza calendario turni</h4>
      </div>
      <div class="modal-body">
			<div id="ajaxSubmitgeneraBozzaTurniModal" class="alert" style="display: none"></div>
			<form role="form" id="ajaxFormgeneraBozzaTurniModal">
                	<div  id="nome_progetto_container" class="form-group required">
                    	<label class="control-label">Nome Progetto</label>
                    	<input id="nome" class="form-control" type="text" placeholder="<?=$var_in_view ['nomeGruppoTurno']?>" disabled />
                	</div>
					<div  id="prima_settimana_container" class="form-group required">
                    	<label class="control-label">Dalla settimana</label>
                        <input id="prima_settimana" class="form-control" placeholder="Es. 25" type="number" min="1" max="150"/>
                	</div>
					<div  id="ultima_settimana_container" class="form-group ">
                    	<label class="control-label">Alla settimana</label>
                        <input id="ultima_settimana" class="form-control" placeholder="Es. 28 - Lasciare vuoto se si vuole considerare solo una settimana" type="number" min="1" max="150"/>
                	</div>
			</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
        <button type="button" id="btngeneraturni" class="btn btn-primary" >Genera bozza calendario</button>
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

<?php if($var_in_view['openBozzaModal']): ?>
    <script>
    $(document).ready(function(){
    	$('#generaBozzaTurniModal').modal('show');
    });
    </script>
<?php endif;?>

<?php 
ajaxFunction(1, "turni", "rimuoviTurno", Array("id_turno"), "rimuoviTurno", '$("#row"+id_turno).hide();',null,"Rimuovere il turno?");
ajaxSubmit("aggiungiturniModal", "turni", "aggiungiTurno", Array(
    "idGruppoTurno"=>$var_in_view ['idGruppoTurno'],
	"nome"=>"$('#nome').val()",
	"giorno"=>"$('#giorno').val()",
	"inizio"=>"$('#inizio').val()",
	"fine"=>"$('#fine').val()",
	"inizio_festivo"=>"$('#inizio_festivo').val()",
	"fine_festivo"=>"$('#fine_festivo').val()"
    ), "btnaggiungiturni", 'location.reload(1);');


$location = SERV_URL.'turni/bozzaTurni/'.$var_in_view ["uniqsessid"];
ajaxSubmit("generaBozzaTurniModal", "turni", "generaBozzaTurni", Array(
    "idGruppoTurno"=>$var_in_view ['idProgetto'],
    "prima_settimana"=>"$('#prima_settimana').val()",
    "ultima_settimana"=>"$('#ultima_settimana').val()",
    "uniqsessid" => "'".$var_in_view ["uniqsessid"]."'"
    ), "btngeneraturni", "location.href='$location'"  );
?>