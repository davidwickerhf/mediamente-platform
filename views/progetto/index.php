<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?></h1>
		<div class="dropdown" style="margin-left:10px">
          <button class="btn  btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-option-vertical"></span>
          </button>
    	  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
             <li class="<?=(ACLhasAccess("progetto", "aggiungi") ?"" : "disabled")?>"><a href="<?=(ACLhasAccess("progetto", "aggiungi") ? SERV_URL."progetto/aggiungi" : "#")?> "><span class="glyphicon glyphicon-plus"></span> Aggiungi progetto</a></li>
          </ul>
        </div>
</div>

<div class="table-responsive" style="max-width:900px">
	<table class="table table-striped" id="progetti">
		<thead>
			<tr>
				<th>ID</th>
				<th>Cliente</th>
				<th>Nome</th>
				<th>Referente</th>
				<th>Data inizio</th>
				<th>Data fine</th>
			</tr>
		</thead>

		<tbody>
		<?php foreach($var_in_view['progetti'] as $t):?>
			<tr id="row<?=$t->id?>">
				<td><?=$t->id?></td>
				<td><?=$t->cliente_nome?></td>
				<td><a href="<?=SERV_URL?>progetto/visualizza/<?=$t->id?>"><?=$t->nome?></a></td>
				<td><?=$t->referente?></td>
				<td><?=$t->data_inizio?></td>
				<td><?=$t->data_fine?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>
<script>
$(document).ready(function(){
	datatab=$('#progetti').DataTable(
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
