<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?></h1>
		<div class="dropdown" style=" margin-left:10px">
          <button class="btn  btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-option-vertical"></span>
          </button>
          <a href="<?=SERV_URL?>calendario/visualizzaUtente/<?=getMyUsername()?>" class="btn btn-default "><span class="glyphicon glyphicon-calendar"></span> Calendario personale</a>
    	  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
              <li class="<?=(ACLhasAccess("calendario", "aggiungi") ?"" : "disabled")?>"><a href="<?=(ACLhasAccess("calendario", "aggiungi") ? SERV_URL."calendario/aggiungi" : "#")?> "><span class="glyphicon glyphicon-plus"></span> Aggiungi calendario</a></li>
			  <li><a href="<?=SERV_URL?>calendario/esporta"><span class="glyphicon glyphicon-export"></span> Esporta calendari</a></li>
          </ul>
        </div>
        
        
</div>

<h3>Calendari generali</h3>
<div class="table-responsive" style="max-width:900px" >
	<table class="table table-striped" id="calendari">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
			</tr>
		</thead>

		<tbody>
		<?php
		foreach($var_in_view['calendari'] as $t):

		?>
			<tr id="row<?=$t->id?>">
				<td><?=$t->id?></td>
				<td><a href="<?=SERV_URL?>calendario/visualizza/<?=$t->id?>"><?=$t->nome?></a></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>
<hr/>
<h3>Calendari personali</h3>
<div class="table-responsive" style="max-width:900px">
	<table class="table table-striped" id="calendari_personali">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
			</tr>
		</thead>

		<tbody>
		<?php
		foreach($var_in_view['calendari_personali'] as $t):

		?>
			<tr id="row<?=$t->id?>">
				<td><?=$t->id?></td>
				<td><a href="<?=SERV_URL?>calendario/visualizza/<?=$t->id?>"><?=$t->nome?></a></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>

<?php 

if($var_in_view['getStarted']==1):
?>
<script>
$(document).ready(function(){
	$("#getStartedModal").modal("show");
});
</script>
<?php endif;?>

<div class="modal fade" id="getStartedModal" tabindex="-1" role="dialog" aria-labelledby="getStartedModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="getStartedModalLabel"><span class="glyphicon glyphicon-flash"></span> Configurazione iniziale</h4>
      </div>
      <div class="modal-body">
			<strong>Iniziamo!</strong> Ricordati di importare gli eventi del tuo calendario di outlook.<br/>
			Nella sezione "Calendario Personale" apri il men&ugrave; identificato dal tasto 
			<button class="btn btn-xs"><span class="glyphicon glyphicon-option-vertical"></span></button> e scegli<br/>
			 <span class="glyphicon glyphicon-import"></span> <strong>Import/Export calendari</strong><br/><br/>
			Puoi aggiungere tutti i calendari esterni che hai, ma almeno uno &egrave; fondamentale: il calendario di outlook.<br/><br/>
			Nella sezione <strong>Import</strong> apri nuovamente il men&ugrave; <button class="btn btn-xs"><span class="glyphicon glyphicon-option-vertical"></span></button>
			e scegli <br/><span class="glyphicon glyphicon-plus"></span> <strong>Aggiungi calendario esterno</strong>.<br/><br/>
			Segui le istruzioni l&igrave; riportate per aggiungere il calendario di outlook.
			
	</div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">OK!</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
	datatab=$('#calendari').DataTable(
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

	  datatab_pers=$('#calendari_personali').DataTable(
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
	   datatab_pers.draw();
});

</script>
