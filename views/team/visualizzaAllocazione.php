<?php 
$mese=$var_in_view['mese'];
$anno=$var_in_view['anno'];
$giorniMese=date("t",strtotime($anno."-".$mese."-01"));
$utenti=$var_in_view['utenti'];
$idteam=$var_in_view['id_team'];
?>
<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?> <?=meseAnno($anno."-".$mese."-01")?></h1>
		<div class="btn-group" role="group" >
			<a class="btn btn-default" href="<?=SERV_URL?>team/visualizzaAllocazione/<?=$var_in_view['id_team']?>/<?=($mese>1 ? $anno: $anno-1)?>/<?=($mese>1 ? $mese-1 : 12)?>"><span class="glyphicon glyphicon-chevron-left"></span></a>
			<a class="btn btn-default" href="<?=SERV_URL?>team/visualizzaAllocazione/<?=$var_in_view['id_team']?>">Oggi</a>
			<a class="btn btn-default" href="<?=SERV_URL?>team/visualizzaAllocazione/<?=$var_in_view['id_team']?>/<?=($mese<12 ? $anno: $anno+1)?>/<?=($mese<12 ? $mese+1 : 1)?>"><span class="glyphicon glyphicon-chevron-right"></span></a>
		</div>
</div>

<div id="ajaxSubmit1" class="alert" style="display:none; max-width:200px"></div>
<ol class="breadcrumb">
<li><a href="<?=SERV_URL?>team">Team</a></li>
<li><a href="<?=SERV_URL?>team/<?=($idteam=="0" ? "seleziona" : "visualizza/".$idteam)?>"><?=$var_in_view['nameTeam']?></a></li>
<li class="active">Allocazione</li>
</ol>

<?php
if($var_in_view['id_team']==0){
?>
	<form role="form" id="ajaxForm1" style="max-width:500px">
	  <div id="id_utente_container" class="form-group">
		<label class="control-label">Utenti</label>
		<select class="form-control selectpicker"  data-size=6 data-live-search="true" id="codice_utente" multiple>
		<?php 
		foreach($var_in_view['allUtenti'] as $t):
			$selected = '';
			if(in_array($t->username, explode(",",$var_in_view['utenti']))){
				$selected = 'selected';
			}
		?>
			<option value="<?=$t->username?>" <?=$selected?>><?=$t->nome . " " . $t->cognome?></option>
		<?php endforeach;?>
		</select>
	  </div>
	</form>
	<button type="button" id="btn" class="btn btn-primary" style="margin-bottom:50px;">Seleziona Team</button>
<?php
}
ajaxSubmit ( 1, "team", "seleziona", Array (
  "codice_utente" => "$('#codice_utente').val()",
  ), "btn", "location.href='".SERV_URL."team/visualizzaAllocazione/0';"
  );
?>
<style>
    .nomeUtente
    {
    vertical-align:middle !important;
    text-align:right;
    }
    .dayCell{
        border-left:1px solid #ccc;
        border-right:1px solid #ccc;
        width:46px !important;
        text-align:center;
        padding:0px !important;
        height:46px;
    }
    .thickborder{
        border-bottom:2px solid #ccc;
    }
    
    .occupazioneGiorno{
        width:46px;
        height:23px;
        cursor:pointer;
    }
    
    #legenda {
        width:200px;
    }
    
    #legenda * {
        padding:0px;
    }
</style>

<?php 
$commessaInterna="bg-warning";
$progetto="bg-danger";
$nonConfermato="bg-info";
$altroProgetto="bg-primary";
?>



<table id="legenda" class="table table-striped">
    <thead>
        <tr><th scope="col">Legenda</th></tr>
    </thead>
    <tbody>
        <tr><td><div class="<?=$commessaInterna?>">Commessa Interna / Ferie</div></td></tr>
        <tr><td><div class="<?=$progetto?>">Progetto</div></td></tr>
        <tr><td><div class="<?=$altroProgetto?>">Altro Progetto</div></td></tr>
        <tr><td><div class="<?=$nonConfermato?>">Non Confermato</div></td></tr>
    </tbody>
</table>

<table id="allocazione" class="table table-striped">
	<thead>
			<tr>
				<th>Username</th>
				<?php for($i=1;$i<=$giorniMese;$i++):
				$data_short=$mese."-".($i<10?"0".$i:$i);#$data_short=($mese < 10 ? "0".$mese : $mese)."-".($i<10?"0".$i:$i);
				$data=$anno."-".$data_short;
				?>
				<th class="dayCell <?=((date("N",strtotime($data))>=6||festivita($data)) ? "bg-danger":"")?>"><?=$i ?><br/><?=strftime("%a", strtotime( $data ))?></th>
				<?php endfor; ?>
				
			</tr>
	</thead>
	<tbody>
		<?php foreach($var_in_view['utentiTeam'] as $t):?>
			<tr>
				<td class="nomeUtente thickborder"><a href="<?=SERV_URL?>calendario/visualizzaUtente/<?=$t->username?>"><?=$t->cognome?> <?=$t->nome?></a></td>
				<?php for($i=1; $i<=$giorniMese;$i++):
				$data_short=$mese."-".($i<10?"0".$i:$i);
				$data=$anno."-".$data_short;
				?>
				<td class="dayCell thickborder">
					<div class="occupazioneGiorno" data-username="<?=$t->username?>" data-date="<?=$data?>" data-type="Mattina" data-events="" style="border-bottom:1px dotted #ccc" id="<?=$t->username.$data."M"?>"></div>
					<div class="occupazioneGiorno" data-username="<?=$t->username?>" data-date="<?=$data?>" data-type="Pomeriggio" data-events="" id="<?=$t->username.$data."P"?>"></div>
				</td>
				<?php endfor;?>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>
<div class="modal fade" id="listaEventiModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="listaEventiLabel"></h4>
      </div>
      <div class="modal-body">
			<div id="events-list"></div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
        <button type="button" id="modalneweventbtn" class="btn btn-primary" onClick="$('#listaEventiModal').modal('hide');apriNuovoEvento($(this).data('dataInizio'),$(this).data('dataFine'))">Nuovo evento</button>
      </div>
    </div>
  </div>
</div>
<?php 
ajaxFunction(1, "calendario", "getOccupazione", Array("idteam","mese", "utenti"), "getOccupazione", '

jQuery.each(obj.users, function(username, data)  {
    jQuery.each(data, function(data, val)  {
    if(val.M!="false")
    {
        var hours=0;
       $("#"+username+data+"M").removeClass("bg-success").removeClass("bg-warning").removeClass("bg-danger");
        jQuery.each(val.M, function(key, event)  {
            $("#"+username+data+"M").data("events", $("#"+username+data+"M").data("events")+"<li>"+event.title+" dal "+event.inizio+" al "+event.fine+"</li>");
            hours=hours+event.hours;
            if(event.id_calendario == 4 && !$("#"+username+data+"M").hasClass("'.$progetto.'").length)
                $("#"+username+data+"M").attr("class","'.$commessaInterna.' occupazioneGiorno");
            else if(event.bloccante == 0 && !$("#"+username+data+"M").hasClass("'.$progetto.'").length)
                $("#"+username+data+"M").attr("class","'.$nonConfermato.' occupazioneGiorno");
            else if(event.id_calendario != '.(isset($idteam) ? strval($idteam) : '""' ).' && !$("#"+username+data+"M").hasClass("'.$progetto.'").length)
                $("#"+username+data+"M").attr("class","'.$altroProgetto.' occupazioneGiorno");
            else
                $("#"+username+data+"M").attr("class","'.$progetto.' occupazioneGiorno");
        });
        

        if(hours>4) {
            hours=">4";
        }    
   
         $("#"+username+data+"M").html(hours);

    }
    else
    {
        $("#"+username+data+"M").addClass("bg-success occupazioneGiorno").removeClass("'.$progetto.'");
        $("#"+username+data+"M").html("");
    }

    if(val.P!="false")
    {
        $("#"+username+data+"P").removeClass("bg-success").removeClass("bg-warning").removeClass("bg-danger");
        var hours=0;
        jQuery.each(val.P, function(key, event)  {
            $("#"+username+data+"P").data("events", $("#"+username+data+"P").data("events")+"<li>"+event.title+" dal "+event.inizio+" al "+event.fine+"</li>");
            hours=hours+event.hours;
            if(event.id_calendario == 4 && !$("#"+username+data+"M").hasClass("'.$progetto.'").length)
                $("#"+username+data+"P").attr("class","'.$commessaInterna.' occupazioneGiorno");
            else if(event.bloccante == 0 && !$("#"+username+data+"M").hasClass("'.$progetto.'").length)
                $("#"+username+data+"P").attr("class","'.$nonConfermato.' occupazioneGiorno");
            else if(event.id_calendario != '.(isset($idteam) ? strval($idteam) : '""' ).' && !$("#"+username+data+"M").hasClass("'.$progetto.'").length)
                $("#"+username+data+"P").attr("class","'.$altroProgetto.' occupazioneGiorno");
            else
                $("#"+username+data+"P").attr("class","'.$progetto.' occupazioneGiorno");
        });

        //Metto una X al mattino se loperatore fa gia piu di 4 ore nel pomeriggio
        if(hours>4 && $.trim($("#"+username+data+"M").html())=="") {
            $("#"+username+data+"M").html("X");
        }

        if(hours>4) {
            hours=">4";
        }

        $("#"+username+data+"P").html(hours);
    }
    else
    {
        $("#"+username+data+"P").addClass("bg-success").removeClass("'.$progetto.'");
        $("#"+username+data+"P").html("");


        //Metto una X al pom se loperatore fa gia piu di 4 ore nel mat
        if($("#"+username+data+"M").html()=="&gt;4") {
            $("#"+username+data+"P").html("X");
        }

    }
    });
});
');
//ajaxFunction(2, "team", "aggiungiUtente", Array("id_team","username"), "aggiungiUtente", 'location.reload(1);');
?>
<script>

function gestisciSalvaEvento(evento)
{
	location.reload(1);
}

$(".occupazioneGiorno").on("click",function(){
	username=$(this).data('username');
	
	if($(this).data('type')=='Mattina')
	{
		var dataInizio=$(this).data('date')+" 09:00";
		var dataFine=$(this).data('date')+" 13:00";
	}
	if($(this).data('type')=='Pomeriggio')
	{
		var dataInizio=$(this).data('date')+" 14:00";
		var dataFine=$(this).data('date')+" 18:00";
	}
	
	if($(this).data('events')=="")
	{	
		apriNuovoEvento(dataInizio,dataFine);
	}
	else
	{
	$("#listaEventiLabel").html("Occupazione "+$(this).data('type'));
	$("#events-list").html("<ul>"+$(this).data('events')+"</ul>");
	$("#listaEventiModal").modal("show");
	$("#modalneweventbtn").data('dataInizio',dataInizio);
	$("#modalneweventbtn").data('dataFine',dataFine);
	}
	
});
$(document).ready(function(){
<?php
echo 'getOccupazione('.$idteam.',"'.$anno."-".$mese.'","'. $utenti .'");';
?>
});

$(document).ready(function(){
	datatab=$('#allocazione').DataTable(
    		{
    			fixedHeader: {
    		        headerOffset: 50
    		    },
    		    rowReorder: false,
    		    ordering: false,
    		    searching:false,
    		    paging:false,
    		    autoWidth: false
    		    
    		    
    		    
    		}
    	    );
	  datatab.draw();
});

</script>
<?php require_once ROOT_PATH."views/calendario/modalCalendario.php"; ?>