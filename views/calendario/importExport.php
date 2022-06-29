<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?></h1>
       
</div>
<ol class="breadcrumb">
<li><a href="<?=SERV_URL?>calendario/visualizzaUtente/<?=getMyUsername()?>">Calendario personale</a></li>
<li class="active">Import/Export calendari</li>
</ol>

<h3>Import</h3>
<div class="dropdown" style=" margin-left:10px; margin-bottom:10px">
          <button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-option-vertical"></span>
          </button>
          <button onClick="sincronizzaCalendariEsterni()" class="btn btn-xs btn-default "><span class="glyphicon glyphicon-refresh"></span> Sincronizza ora</button> 
    	  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
              <li><a  href="<?=SERV_URL?>calendario/aggiungiCalendarioEsterno"><span class="glyphicon glyphicon-plus"></span> Aggiungi calendario esterno</a></li>
          </ul>
</div>
<div class="table-responsive" style="max-width:900px" >
	<table class="table table-striped" id="calendari">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
		<?php
		foreach($var_in_view['calendari'] as $t):

		?>
			<tr id="row<?=$t->id?>">
				<td><?=$t->id?></td>
				<td><a href="<?=SERV_URL?>calendario/aggiungiCalendarioEsterno/<?=$t->id?>"><?=$t->nome?></a></td>
				<td><button style="margin-top:0px" class="btn btn-danger btn-xs" onClick="rimuoviCalendarioEsterno(<?=$t->id?>)"><span class="glyphicon glyphicon-remove"></span></button></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>
<hr/>
<h3>Export</h3>
<p><strong>Link ICS (tutti gli eventi):</strong> <?=EMAIL_SERV_URL?>calendario/ICS/<?=getMyUsername() ?>/<?=$var_in_view['tokenEsportazione']?></p>
<p><strong>Link ICS (solo eventi interni):</strong> <?=EMAIL_SERV_URL?>calendario/ICS/<?=getMyUsername() ?>/<?=$var_in_view['tokenEsportazione']?>/interni</p>

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

});

</script>
<?php 
ajaxFunction(1, "calendario", "rimuoviCalendarioEsterno", Array("id"), "rimuoviCalendarioEsterno", '$("#row"+id).hide();',null,"Rimuovere il calendario?");
ajaxFunction(1, "calendario", "sincronizzaCalendariEsterni", Array(), "sincronizzaCalendariEsterni");
?>
