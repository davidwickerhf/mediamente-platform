<div class="page-header">
		<h1><?=$var_in_view['pageTitle']  ?></h1>
</div>
<ol class="breadcrumb">
<li><a href="<?=SERV_URL?>team">Team</a></li>
<li class="active">Seleziona Team on Fly</li>
</ol>
<div id="ajaxSubmit1" class="alert" style="display: none"></div>
<form role="form" id="ajaxForm1" style="max-width:500px">
  <div id="id_utente_container" class="form-group">
    <label class="control-label">Utenti</label>
    <select class="form-control selectpicker"  data-size=6 data-live-search="true" id="codice_utente" multiple>
    <?php foreach($var_in_view['utenti'] as $t):?>
      <option value="<?=$t->username?>" ><?=$t->nome . " " . $t->cognome?></option>
    <?php endforeach;?>
    </select>
  </div>
</form>
<button type="button" id="btn" class="btn btn-primary">Seleziona Team</button>
<?php 
ajaxSubmit ( 1, "team", "seleziona", Array (
  "codice_utente" => "$('#codice_utente').val()",
  ), "btn", "location.href='".SERV_URL."team/visualizzaAllocazione/0';"
  );
?>