<?php   $calendario=$var_in_view['calendario'];
        $readonly=$var_in_view['readonly'];
?>

<div class="page-header">
				<h1><?=$var_in_view['pageTitle']  ?> <span id="externalTitle"></span></h1>
	<?php if(!$readonly): ?>
		<div class="dropdown" style="margin-left: 10px">
		
		<button class="btn btn-default dropdown-toggle" type="button"
			id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
			aria-expanded="true">
			<span class="glyphicon glyphicon-option-vertical"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
		<?php if($calendario->tipo !='personale'):?>
			<li class="<?=(ACLhasAccess("calendario", "aggiungi") ?"" : "disabled")?>"><a href="<?=(ACLhasAccess("calendario", "aggiungi") ? SERV_URL."calendario/aggiungi/".$calendario->id : "#")?> "><span class="glyphicon glyphicon-pencil"></span> Modifica calendario</a></li>
			<li class="<?=(ACLhasAccess("calendario", "rimuovi") ?"" : "disabled")?>"><a <?=(ACLhasAccess("calendario", "rimuovi") ?"id='btnrimuovicalendario'" : "")?> style="cursor:pointer" class="bg-danger" ><span
					class="glyphicon glyphicon-remove"></span> Rimuovi calendario</a></li>
		<?php else:?>
			<li><a href="<?=SERV_URL?>calendario/importExport" ><span class="glyphicon glyphicon-import"></span> Import/Export calendari</a></li>
			<li><a style="cursor:pointer" onClick="sincronizzaCalendariEsterni()"><span class="glyphicon glyphicon-refresh"></span> Sincronizza calendari esterni</a></li>
		<?php endif;?>
		</ul>
		<div class="btn-group" role="group" >
		<button class="btn btn-default" onClick="calendar.changeView('dayGridMonth')">Mese</button>
		<button class="btn btn-default" onClick="calendar.changeView('timeGridWeek')">Settimana</button>
		<button class="btn btn-default" onClick="calendar.changeView('dayGridDay')">Giorno</button>
		<button class="btn btn-default" onClick="calendar.changeView('listWeek')">Agenda</button>

		</div>
		<div class="btn-group" role="group" >
			<button class="btn btn-default" onClick="calendar.prev()"><span class="glyphicon glyphicon-chevron-left"></span></button>
			<button class="btn btn-default" onClick="calendar.today()">Oggi</button>
			<button class="btn btn-default" onClick="calendar.next()"><span class="glyphicon glyphicon-chevron-right"></span></button>
		</div>
		<div class="btn-group" role="group" >
			<button  class="btn btn-default" data-toggle="modal" data-target="#aggiungiCalendarioModal" ><span class="glyphicon glyphicon-plus"></span> Aggiungi calendario</button>
		</div>
		
		<div class="btn-group" role="group" >
			<button <?=(ACLhasAccess("calendario", "aggiungiEvento") ?"" : "disabled")?> class="btn btn-primary" onClick="apriNuovoEvento()"><span class="glyphicon glyphicon-plus"></span> Nuovo evento</button>
		</div>

		<div class="btn-group" role="group" >
			<label id="extCal" class="btn btn-primary">Nascondi Calendario Esterno</label>
		</div>
		<div class="btn-group" role="group" >
			<?php if($calendario->id==1 && ACLhasAccess("turni", "generaBozzaTurni")) :?><a href="<?=SERV_URL."turni/visualizza/2/generaBozza"?>" class="btn btn-primary"><span class="glyphicon glyphicon-dashboard"></span> Genera bozza turni</a><?php endif;?>
		</div>
		
	</div>
   <?php endif; ?>
</div>
<style>
.fc-event{
    cursor: pointer;
}

</style>
<div class="alert alert-danger" style="display:none" id="calendarAlert"></div>
<link href='<?=SERV_URL?>dist/js/calendar/main.css' rel='stylesheet' />
    <script src='<?=SERV_URL?>dist/js/calendar/main.js'></script>
    <script src='<?=SERV_URL?>dist/js/calendar/locales/it.js'></script>
    
    <script>
	var calendar;
    window.mobilecheck = function() {
		  var check = false;
		  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
		  return check;
		};
		
    <?php
    $csrfToken = sha1 ( SECURITY_SALT . $model . $action . generateUniqueId () );
    $csrfTokenID = generateUniqueId ();
    $_SESSION ['csrfToken' . $csrfTokenID] = $csrfToken;
    ?>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
       	  height: 700,
          initialView: window.mobilecheck() ? "listWeek":"dayGridMonth" ,
          locale: 'it',
          businessHours: {
        	  // days of week. an array of zero-based day of week integers (0=Sunday)
        	  daysOfWeek: [ 1, 2, 3, 4, 5 ], // Monday - Thursday

        	  startTime: '09:00', // a start time (10am in this example)
        	  endTime: '18:00', // an end time (6pm in this example)
        	},
          headerToolbar: false,
            navLinks: true, // can click day/week names to navigate views
            nowIndicator: true,
            eventTextColor:'#000000',
            weekNumbers: true,
            weekNumberCalculation: 'ISO',
            dayCellDidMount: function(){
          	  var view = calendar.view;
        	  $("#externalTitle").html(view.title);
            },
          eventSources: [

        	    // your event source
        	    {
            	  id: '<?php if($var_in_view['id']): echo $var_in_view['id'];  endif; 
            	  if($var_in_view['username']): echo $var_in_view['username'];  endif; ?>',
        	      url: '<?=SERV_URL?>model/calendario.php',
        	      method: 'POST',
        	      extraParams: {
        	        action: 'getTurni',
        	        <?php if($var_in_view['id']): ?>id: <?=$var_in_view['id']?>, <?php endif; ?>
        	        csrfToken:'<?=$csrfToken?>',
        	        csrfTokenID:'<?=$csrfTokenID?>',
        	      },
        	      failure: function() {
        	        $('#calendarAlert').html("Errore durante il caricamento dati").show();
        	      },
        	      textColor: 'black' // a non-ajax option
        	    },
        	    {
              	  id: 'festivita-italiane',
          	      url: '<?=SERV_URL?>model/calendario.php',
          	      method: 'POST',
          	      extraParams: {
          	        action: 'getFestivita',
          	        csrfToken:'<?=$csrfToken?>',
          	        csrfTokenID:'<?=$csrfTokenID?>',
          	      },
          	      textColor: 'white' // a non-ajax option
          	    }

        	    // any other sources...

        	  ],
        	  eventClick: function(info) {
        		    apriEvento(info);
        		  },
    		  dateClick: function(info) {
    			    apriNuovoEvento(info.dateStr);
    			  }
        		        	  
        });
        calendar.render();

  
      });

      function gestisciSalvaEvento(event)
      {
    	  if(id_evento!=0) 
              calendar.getEventById( id_evento ).remove();
           calendar.addEvent(event,<?=$calendario->id ?>); 
      }
      function gestisciRimuoviEvento(id_evento)
      {
    	  calendar.getEventById( id_evento ).remove();
      }
    </script>
    <div id='calendar'></div>
    
    
    <!-- Modal aggiunta calendario -->
<div class="modal fade" id="aggiungiCalendarioModal" tabindex="-1" role="dialog" aria-labelledby="aggiungiCalendarioLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="aggiungiCalendarioLabel">Aggiungi calendario alla visualizzazione</h4>
      </div>
      <div class="modal-body">
			<form role="form">
			       			      				
                	<div class="form-group required">
                    	<label class="control-label">Calendario</label>
                    	<select class="form-control  selectpicker"  data-size=6 data-live-search="true"  id="aggiungi_id_calendario">
                    	<?php foreach($var_in_view['calendari'] as $c):?>
                    		<option value="<?=$c->id?>"><?=$c->nome ?></option>
                    	<?php endforeach;?>
                    	</select>
                	</div>
			
			</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
        <button type="button" class="btn btn-primary" onClick="aggiungiCalendario($('#aggiungi_id_calendario').val())">Aggiungi</button>
      </div>
    </div>
  </div>
</div>
<script src="/dist/bootstrap-select/js/bootstrap-select.min.js"></script>
<script>

$('#aggiungi_id_calendario').selectpicker();

	function aggiungiCalendario(id)
	{
		$("#aggiungiCalendarioModal").modal("hide");
		var exists=false;

		 jQuery.each(calendar.getEventSources(), function(key, val)  {
			if(String(val.id)==String(id))
				exists=true;
		 });

		if(exists)
			return;
		
		calendar.addEventSource({
      	  id: id,
	      url: '<?=SERV_URL?>model/calendario.php',
	      method: 'POST',
	      extraParams: {
	        action: 'getTurni',
	        id:id,
	        csrfToken:'<?=$csrfToken?>',
	        csrfTokenID:'<?=$csrfTokenID?>',
	      },
	      failure: function() {
	        $('#calendarAlert').html("Errore durante il caricamento dati").show();
	      },
	      textColor: 'black' // a non-ajax option
	    });
	}

function _x(STR_XPATH) {
    var xresult = document.evaluate(STR_XPATH, document, null, XPathResult.ANY_TYPE, null);
    var xnodes = [];
    var xres;
    while (xres = xresult.iterateNext()) {
        xnodes.push(xres);
    }

    return xnodes;
}

$('#extCal').click(function(){
	if($('#extCal').text()=='Nascondi Calendario Esterno'){
		$('#extCal').text('Visualizza Calendario Esterno');
		$(_x("/html/.//div[contains(text(),'EXT')]/parent::a/parent::div")).hide();
		$(_x("/html/.//div[contains(text(),'EXT')]/parent::div/parent::div/parent::div/parent::a/parent::div")).hide();
	}else{
		$('#extCal').text('Nascondi Calendario Esterno');
		$(_x("/html/.//div[contains(text(),'EXT')]/parent::a/parent::div")).show();
		$(_x("/html/.//div[contains(text(),'EXT')]/parent::div/parent::div/parent::div/parent::a/parent::div")).show();
	}
});

</script>
<?php 
        
    ajaxSubmit ( 1, "calendario", "rimuoviCalendario", Array (
        "id"=>$calendario->id
        ), "btnrimuovicalendario", "location.href='".SERV_URL."calendario'",
        null,
        "Rimuovere il calendario?"
        );

    require_once ROOT_PATH."views/calendario/modalCalendario.php";
    
    ajaxFunction(1, "calendario", "sincronizzaCalendariEsterni", Array(), "sincronizzaCalendariEsterni","location.reload(1);");
?>