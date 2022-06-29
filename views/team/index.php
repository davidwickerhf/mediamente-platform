<div class="page-header">
		<h1 ><?=$var_in_view['pageTitle']  ?></h1>
		<div class="dropdown" style=" margin-left:10px">
          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-option-vertical"></span>
          </button>
    	  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
              <li><a style="cursor:pointer" data-toggle="modal" data-target="#aggiungiTeamModal" ><span class="glyphicon glyphicon-plus"></span> Aggiungi team</a></li>
			  <li><a href="<?=SERV_URL?>team/seleziona"><span class="glyphicon glyphicon-sunglasses"></span> Seleziona team on fly</a></li>
          </ul>
        </div>
</div>
<div class="table-responsive" style="max-width:500px">
	<table id="team" class="table table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
		<?php foreach($var_in_view['team'] as $t):?>
			<tr id="row<?=$t->id?>">
				<td><?=$t->id?></td>
				<td><a href="<?=SERV_URL?>team/visualizza/<?=$t->id?>"><?=$t->nome?></a></td>
				<td><button style="margin-top:0px" class="btn btn-danger btn-xs" onClick="rimuoviTeam(<?=$t->id?>)"><span class="glyphicon glyphicon-remove"></span></button></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>

<!-- Modal aggiunta utente -->
<div class="modal fade" id="aggiungiTeamModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Aggiungi utente</h4>
      </div>
      <div class="modal-body">
			<div id="ajaxSubmitaggiungiTeamModal" class="alert" style="display: none"></div>
			<form role="form" id="ajaxFormaggiungiTeamModal">
			       			      				
                	<div  id="nome_container" class="form-group required">
                    	<label class="control-label">Nome</label>
                    	<input id="nome" class="form-control" type="text" required />
                	</div>
			
			</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
        <button type="button" id="btnaggiungiTeam" class="btn btn-primary" >Aggiungi</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).keypress(function(e) {
	  if ($("#aggiungiTeamModal").hasClass('in') && (e.keycode == 13 || e.which == 13)) {
		  e.preventDefault();
		  $("#btnaggiungiTeam").click();
	  }
	});
$('#aggiungiTeamModal').on('shown.bs.modal', function () {
    $('#nome').focus();
})  
</script>
<script>
$(document).ready(function(){
	datatab=$('#team').DataTable(
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
ajaxFunction(1, "team", "rimuoviTeam", Array("id_team"), "rimuoviTeam", '$("#row"+id_team).hide();',null,"Rimuovere il team?");
ajaxSubmit("aggiungiTeamModal", "team", "aggiungiTeam", Array(
    "nome"=>"$('#nome').val()"
    ), "btnaggiungiTeam", 'location.reload(1);');
?>