<script type="text/javascript" src="<?=SERV_URL?>dist/js/moment-with-locales.js?v=<?=VERSION?>"></script>
<script type="text/javascript" src="<?=SERV_URL?>dist/js/bootstrap-datetimepicker.min.js?v=<?=VERSION?>"></script>
<link rel="stylesheet" href="<?=SERV_URL?>dist/css/bootstrap-datetimepicker.min.css?v=<?=VERSION?>" />
<link href="<?=SERV_URL?>dist/bootstrap-toggle/bootstrap-toggle.min.css" rel="stylesheet">
<script src="<?=SERV_URL?>dist/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<div class="modal fade" id="eventoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Evento</h4>
      </div>
      <div class="modal-body">
			<div id="ajaxSubmiteventoModal" class="alert" style="display: none"></div>
			<form role="form" id="ajaxFormeventoModal">
			       			      				
                	<div  id="titolo_container" class="form-group required">
                    	<label class="control-label">Titolo</label>
                    	<input id="titolo" class="form-control" type="text" required  />
                	</div>
                	<div  id="descrizione_container" class="form-group">
                    	<label class="control-label">Descrizione</label>
                    	<textarea class="form-control" id="descrizione" ></textarea>
                	</div>
                	<div  id="inizio_container" class="form-group">
                    	<label class="control-label">Tutto il giorno</label>
                    	<input type="checkbox" id="all-day" checked data-toggle="toggle" data-on="s&igrave;" data-off="no"  data-size="small" >
                	</div>
					<div class="form-group">
                    	<label class="control-label">Multi-Giorno</label>
                    	<input type="checkbox" id="multiday_container" data-toggle="toggle" data-on="s&igrave;" data-off="no"  data-size="small" >
						<div id="multiday_days" style="display:none">
							<input type="checkbox" id="md_1">
    						<label for="md_1">Lun</label>
							<input type="checkbox" id="md_2">
    						<label for="md_2">Mar</label>
							<input type="checkbox" id="md_3">
    						<label for="md_3">Mer</label>
							<input type="checkbox" id="md_4">
    						<label for="md_4">Gio</label>
							<input type="checkbox" id="md_5">
    						<label for="md_5">Ven</label>
							<input type="checkbox" id="md_6">
    						<label for="md_6">Sab</label>
							<input type="checkbox" id="md_7">
    						<label for="md_7">Dom</label>
						</div>
                	</div>
					<div  id="inizio_container" class="form-group required">
                    	<label class="control-label">Inizio</label>
                    	<div class='input-group date' id='datetimepickerInizio'>
                                <input id="inizio" required type='text' class="form-control" value=""  required  autocomplete="off" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </span>
                         </div>
                	</div>
                	
                	<div  id="fine_container" class="form-group required">
                    	<label class="control-label">Fine</label>
                    	<div class='input-group date' id='datetimepickerFine'>
                                <input id="fine" required type='text' class="form-control" value=""  required  autocomplete="off" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        </div>
                	</div>
                	<div  id="bloccante_container" class="form-group">
                    	<label class="control-label">Bloccante <span class="glyphicon glyphicon-question-sign" style="cursor:pointer" onClick="$('#bloccante_help').show()"></span></label>
                    	<input type="checkbox" id="bloccante" checked data-toggle="toggle" data-on="s&igrave;" data-off="no"  data-size="small" >
                    	<div id="bloccante_help" style="display:none" class="alert alert-info">Un evento <em>bloccante</em> &egrave; un evento che non pu&ograve; essere sovrapposto con un altro.<br/>Puoi attivare questa casella solo per gli eventi assegnati a un progetto.<br/>Gli eventi importati da calendari esterni possono essere associati a un progetto e quindi resi bloccanti.</div>
                	</div>
                	<div id="codice_cliente_container" class="form-group required ">
                    	<label class="control-label">Cliente</label>
                    	<select class="form-control selectpicker"  data-size=6 data-live-search="true" id="codice_cliente" required  >
                    	<option value="">Seleziona</option>
                    	<?php foreach($var_in_view['clienti'] as $p):?>
                    		<option  value="<?=$p->codice_cliente?>"><?=$p->nome ?> (<?=$p->codice_cliente?>)</option>
                    	<?php endforeach;?>
                    	</select>
                	</div>
                	<div id="id_progetto_container" style="display:none" class="form-group required ">
                    	<label class="control-label">Progetto <a  style="margin-top:0"  title="Vai al calendario progetto" onClick="redirectCalendarioProgetto()" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-calendar"></span></a></label>
                    	<select class="form-control selectpicker"  data-size=6 data-live-search="true" id="id_progetto" required >
                    	<option value="">Seleziona</option>
                    	</select>
                	</div>
                	<div id="username_container" style="display:none" class="form-group required ">
                    	<label class="control-label">Utente 
                    	<a style="margin-top:0" title="Vai al calendario utente" onClick="redirectCalendarioUtente()" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-calendar"></span></a>
                    	<!--  <input type="checkbox" data-width="100" data-height="22" id="username_onlyfree" data-toggle="toggle" data-on="Solo liberi" data-off="Tutti"  data-size="small"> -->
                    	</label>
                    	<select class="form-control selectpicker"  data-size=6 data-live-search="true" id="username" required  >
                    		<option value="">Seleziona</option>
                    	</select>
                	</div>
					<div>
						<label class="control-label">Inserito Da: <span id="insertBy"></span></label>
					</div>
			</form>
      </div>
      <div class="modal-footer">
  		<button <?=(ACLhasAccess("calendario", "rimuoviEvento") ?"" : "disabled='disabled'")?> type="button" id="btnrimuovievento" class="event-edit btn btn-danger" ><span class="glyphicon glyphicon-remove"></span> Cancella</button>
  		<button <?=(ACLhasAccess("calendario", "aggiungiEvento") ?"" : "disabled='disabled'")?> type="button" class="event-edit btn btn-default" onClick="clonaEvento()" ><span class="glyphicon glyphicon-duplicate"></span> Clona</button>
  		<span class="btn-separator"></span>
        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
        <button <?=(ACLhasAccess("calendario", "aggiungiEvento") ?"" : "disabled='disabled'")?> type="button" id="btnaggiungievento" class="btn btn-primary" ><span class="glyphicon glyphicon-floppy-disk"></span> Salva</button>
      </div>
    </div>
  </div>
</div>
<script>
var id_calendario=0;
var id_evento=0;
var id_progetto=0;
var username="";
var forza_inserimento=0;
var username_onlyfree=false;

$(document).ready(function(){
$('#codice_cliente').selectpicker();
$('#id_progetto').selectpicker();
$('#username').selectpicker();
});

function checkDateFormat()
{
	if($('#all-day').prop('checked')==true)
	  {
		  $('#datetimepickerInizio').data("DateTimePicker").format("YYYY-MM-DD");
		  $('#datetimepickerFine').data("DateTimePicker").format("YYYY-MM-DD");
	  }
	  else
	  {
		  $('#datetimepickerInizio').data("DateTimePicker").format("YYYY-MM-DD HH:mm");
		  $('#datetimepickerFine').data("DateTimePicker").format("YYYY-MM-DD HH:mm");
	  }
}

$('#all-day').change(function() {
	checkDateFormat();
  });

$('#username_onlyfree').change(function() {
	if($('#username_onlyfree').prop('checked')==true)
		username_onlyfree=true;
	else
		username_onlyfree=false;
	getUtentiProgetto($("#id_progetto").val(),$('#inizio').val(),$('#fine').val(),username_onlyfree);
	
  });

function redirectCalendarioUtente()
{
	if(username!="")
		location.href='<?=SERV_URL?>calendario/visualizzaUtente/'+username;
}

function redirectCalendarioProgetto()
{
	if(id_progetto!=0)
		location.href='<?=SERV_URL?>calendario/visualizzaProgetto/'+id_progetto;
}

function clonaEvento()
{
	$("#btnaggiungievento").html("Aggiungi");
	$(".event-edit").hide();
	id_evento=0;
}

function apriNuovoEvento(data,dataFine)
{
	<?=(ACLhasAccess("calendario", "aggiungiEvento") ?"" : "return;")?>
	
	$('#multiday_container').bootstrapToggle('off');
	for (let i = 0; i < 7; i++) {
		$("#md_" + ((i+1)%7)).prop("checked", false);
	}
	
	id_calendario=<?=$calendario->id ? $calendario->id : 0 ?>;
	id_evento=0;
	id_progetto=0;
	username="";
	forza_inserimento=0;
	$("#btnaggiungievento").html("Aggiungi");
	$("form#ajaxFormeventoModal :input").each(function(){
		$(this).val('');
	});

	$(".required").removeClass("has-error"); //tolgo classe di errore


	if(!dataFine)
	{
		$('#all-day').bootstrapToggle('on');
		dataFine=data;
	}
	else
		$('#all-day').bootstrapToggle('off');

	$('#bloccante').attr("disabled","").removeAttr("disabled");
	$('#bloccante').bootstrapToggle('on');
	
	checkDateFormat();
	
	if(data)
	{
		$("#datetimepickerInizio").data("DateTimePicker").date(data);
		$("#datetimepickerFine").data("DateTimePicker").date(dataFine);
	}
	$(".event-edit").hide();

	 $("#id_progetto_container").hide();
	 $("#username_container").hide();

	$('.selectpicker').selectpicker('refresh');
    $('.selectpicker').selectpicker('render');

    $("#ajaxSubmiteventoModal").hide();
    $("#eventoModal").modal("show");
	
}

function apriEvento(info)
{
	if(info.event.source.id=='festivita-italiane')
		return;

	$("form#ajaxFormeventoModal :input").each(function(){
		$(this).attr('disabled','').removeAttr('disabled');
	});   
	
	id_evento=info.event.id;
	props=info.event.extendedProps;
	id_calendario=props.id_calendario;
	id_progetto=props.id_progetto;
	username=props.username;
	forza_inserimento=0;
	$("#btnaggiungievento").html("Salva");

	if(props.bloccante==1)
		$('#bloccante').bootstrapToggle('on');
	else
		$('#bloccante').bootstrapToggle('off');

	if(!id_progetto)
		$('#bloccante').attr("disabled","disabled");
	else
		$('#bloccante').attr("disabled","").removeAttr("disabled");
		

	$(".required").removeClass("has-error"); //tolgo classe di errore
	
	if(info.event.allDay==true)
		$('#all-day').bootstrapToggle('on');
	else
	    $('#all-day').bootstrapToggle('off');

	checkDateFormat();

	$("#titolo").val(props.titolo);
	$("#descrizione").val(props.descrizione);
	$("#datetimepickerInizio").data("DateTimePicker").date(props.inizio);
	$("#datetimepickerFine").data("DateTimePicker").date(props.fine);

	$("#codice_cliente").val(props.codice_cliente);
	
	//alert(JSON.stringify(info.event, null, 4));
	if(props.ricorsivo != "0000000"){
		//alert("E' definito!");
		$("#multiday_container").bootstrapToggle('on');
		for (let i = 0; i < 7; i++) {
			if(props.ricorsivo[i]=="1"){
				$("#md_" + ((i+1)%7)).prop("checked", true);
			}else{
				$("#md_" + ((i+1)%7)).prop("checked", false);
			}
		}
	}else{
		//alert("Non è definito!");
		$("#multiday_container").bootstrapToggle('off');
		for (let i = 0; i < 7; i++) {
			$("#md_" + ((i+1)%7)).prop("checked", false);
		}
	}
	
	$("#insertBy").text(props.inserito_da);

	if(props.codice_cliente)
		getProgettiCliente(props.codice_cliente,id_calendario);
	if(props.id_progetto)
		getUtentiProgetto(props.id_progetto,props.inizio,props.fine,username_onlyfree);

	$('.selectpicker').selectpicker('refresh');
    $('.selectpicker').selectpicker('render');

    $(".event-edit").show();
    $("#ajaxSubmiteventoModal").hide();

    <?php if(!ACLhasAccess("calendario", "aggiungiEvento")): ?>
        	$("form#ajaxFormeventoModal :input").each(function(){
        		$(this).attr('disabled','disabled');
        	});        	
    <?php endif; ?>
	$("#eventoModal").modal("show");
}
$("#id_progetto").on("change",function(){

	$( "#id_progetto option:selected" ).each(function() {
		id_calendario=$(this).data('id_calendario');
	});
	$('#bloccante').attr("disabled","").removeAttr("disabled");

	getUtentiProgetto($("#id_progetto").val(),$('#inizio').val(),$('#fine').val(),username_onlyfree);
	
	
});

$("#multiday_container").on("change",function(){

	if($("#multiday_container").prop('checked')==true){
		$("#multiday_days").show();
	}else{
		$("#multiday_days").hide();
	};
	
});

$("#codice_cliente").on("change",function(){

	getProgettiCliente($("#codice_cliente").val(),id_calendario);
	
	
});
$(document).keypress(function(e) {
	  if ($("#aggiungiTeamModal").hasClass('in') && (e.keycode == 13 || e.which == 13)) {
		  e.preventDefault();
		  $("#btnaggiungiTeam").click();
	  }
	});
$('#aggiungiTeamModal').on('shown.bs.modal', function () {
    $('#nome').focus();
});


$('#datetimepickerInizio').datetimepicker({
	format: 'YYYY-MM-DD',
	locale: 'it',
	maxDate: '<?=date('Y-m-d', strtotime('+365 days', time()));?>',
	showTodayButton:true,
	stepping:30
    });
$('#datetimepickerFine').datetimepicker({
	format: 'YYYY-MM-DD',
	locale: 'it',
	maxDate: '<?=date('Y-m-d', strtotime('+365 days', time()));?>',
	showTodayButton:true,
	stepping:30
    });
$("#datetimepickerInizio").on("dp.change", function (e) {
	forza_inserimento=0;
	if(id_evento==0)
		$("#btnaggiungievento").html("Aggiungi");
	else
		$("#btnaggiungievento").html("Salva");
});
$("#datetimepickerFine").on("dp.change", function (e) {
	forza_inserimento=0;
	if(id_evento==0)
		$("#btnaggiungievento").html("Aggiungi");
	else
		$("#btnaggiungievento").html("Salva");
});
$('#all-day').bootstrapToggle();


</script>
<?php 

    ajaxFunction(1, "cliente", "getProgettiCliente", Array("id","id_calendario"), "getProgettiCliente", "
        $('#id_progetto_container').show();
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
                'data-id_calendario':val.id_calendario,
                text: decodeEntities(val.nome)+ ' ('+val.id+')'
            }));
    
        });
        if(id_progetto!=0)
            $('#id_progetto').val(id_progetto);
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('render');
    ");
    ajaxFunction(1, "progetto", "getUtentiProgetto", Array("id","inizio","fine","username_onlyfree"), "getUtentiProgetto", "
        $('#username_container').show();
        $('#username')
            .find('option')
            .remove()
            .end()
        ;
         $('#username').append($('<option>', {
                value: '',
                text: 'Seleziona' 
            }));
        jQuery.each(obj.users, function(id, val)  {
        $('#username').append($('<option>', {
                value: val.username,
                text: decodeEntities(val.cognome)+ ' ' + decodeEntities(val.nome)+' ('+val.username+')' 
            }));
    
        });
        ".($calendario->tipo=='personale' ? "$('#username').val('".$calendario->username."');" : '')."
         if(username!='')
            $('#username').val(username);
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('render');
    ");
    
    ajaxSubmit("eventoModal", "calendario", "aggiungiEvento", Array(
        "id"=>"id_evento",
        "id_calendario"=>"id_calendario",
        "titolo"=>"$('#titolo').val()",
        "descrizione"=>"$('#descrizione').val()",
        "inizio"=>"$('#inizio').val()",
        "fine"=>"$('#fine').val()",
        "username"=>"$('#username').val()",
        "id_progetto"=>"$('#id_progetto').val()",
        "bloccante"=>"$('#bloccante').prop('checked')",
		"ricorsivo"=>"$('#md_1').prop('checked').toString() + $('#md_2').prop('checked').toString() + $('#md_3').prop('checked').toString() + $('#md_4').prop('checked').toString() + $('#md_5').prop('checked').toString() + $('#md_6').prop('checked').toString() + $('#md_7').prop('checked').toString()",
        "forza_inserimento"=>"forza_inserimento"
        ), "btnaggiungievento", '
            gestisciSalvaEvento(obj.event);
            $("#eventoModal").modal("hide");',
        '
        if(obj.error=="C629")
        {
            $("#ajaxSubmiteventoModal").removeClass("alert-danger").addClass("alert-warning");
            forza_inserimento=1;
            $("#ajaxSubmiteventoModal").html(obj.readableMsg+"<br/><ul>");
            $("#btnaggiungievento").html("Ignora e procedi");
        
            jQuery.each(obj.sovrapposizioni, function(id, val)  {
                var liev="<li>";
                if(val.bloccante==1)
                    liev=liev+"<span style=\'color:#ff0000\'>BLOCCANTE!</span> ";
                liev=liev+val.titolo+": dal "+val.inizio+" al "+val.fine+"</li>";
                $("#ajaxSubmiteventoModal").append(liev);
            });
            jQuery.each(obj.festivita, function(id, val)  {
                $("#ajaxSubmiteventoModal").append("<li>"+val.titolo+"</li>");
            });
            $("#ajaxSubmiteventoModal").append("</ul>");
        }
        '
        
        );
    
    ajaxSubmit ( 1, "calendario", "rimuoviEvento", Array (
        "id"=>"id_evento"
        ), "btnrimuovievento", ' gestisciRimuoviEvento( id_evento ); $("#eventoModal").modal("hide"); ',
        null,
        "Rimuovere l'evento?"
        );
        
?>