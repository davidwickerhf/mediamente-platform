<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?></h1>
</div>
<ol class="breadcrumb">
<li><a href="<?=SERV_URL?>calendario">Calendari</a></li>
<li class="active">Esporta</li>
</ol>
<div id="ajaxSubmit1" class="alert" style="display: none"></div>
<form role="form" id="ajaxForm1" style="max-width:500px">
  <div id="id_tipo_esportazione" class="form-group">
    <label class="control-label">Tipo Esportazione</label>
    <select class="form-control selectpicker" data-size=6 id="tipo_esportazione">
      <option value="0">Date in riga / User in Colonna</option>
      <option value="1">User in riga / Date in Colonna</option>
    </select>
  </div>
  <div id="id_calendario_container" class="form-group">
    <label class="control-label">Calendario</label>
    <select class="form-control selectpicker"  data-size=6 data-live-search="true" id="codice_calendario" multiple>
    <?php foreach($var_in_view['calendari'] as $t):?>
      <option value="<?=$t->id?>" ><?=$t->nome ?></option>
    <?php endforeach;?>
    </select>
  </div>
  <div id="id_utente_container" class="form-group">
    <label class="control-label">Utenti</label>
    <select class="form-control selectpicker"  data-size=6 data-live-search="true" id="codice_utente" multiple>
    <?php foreach($var_in_view['calendari_personali'] as $t):?>
      <option value="<?=$t->username?>" ><?=$t->nome ?></option>
    <?php endforeach;?>
    </select>
  </div>
  <div class="form-group required" id="data_inizio_container">
    <label for="date1">Data inizio</label>
    <input class="form-control it-date-datepicker" id="data_inizio" type="text" placeholder="yyyy-mm-dd" required />
  </div>
  <div id="data_fine_container" class="form-group required ">
      <label class="control-label">Data fine</label>
      <input id="data_fine" class="form-control it-date-datepicker" type="text" placeholder="yyyy-mm-dd" required />
  </div>
</form>
<button type="button" id="btnesportacalendari" class="btn btn-primary">Esporta</button>
<?php 
ajaxSubmit ( 1, "calendario", "esporta", Array (
  "tipo_esportazione" => "$('#tipo_esportazione').val()",
  "codice_calendario" => "$('#codice_calendario').val()",
  "codice_utente" => "$('#codice_utente').val()",
  "data_inizio" => "$('#data_inizio').val()",
  "data_fine" => "$('#data_fine').val()"
  ), "btnesportacalendari", "location.href='".SERV_URL."calendario/esporta';"
  );
?>
<!--
<script>
$(document).ready(function() {
    $('.it-date-datepicker').datepicker({
      inputFormat: ["yyyy-MM-dd"],
      outputFormat: 'yyyy-MM-dd',
    });
});
</script>
-->