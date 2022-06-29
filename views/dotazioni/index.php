<div class="page-header">
				<h1><?=$var_in_view['pageTitle']  ?></h1>
	
</div>
<h2>I tuoi PC</h2>
<div class="alert alert-info" style="max-width:700px">
In questa sezione vedi le informazioni sul/i PC aziendali in tua dotazione.<br/>
In caso di incongruenze ti preghiamo di segnalarle a support_security@mediamenteconsulting.it.<br/>
</div>
<?php 
if(count($var_in_view['dotazioni'])):
?>
<ol>

<?php 
    foreach($var_in_view['dotazioni'] as $d):
?>
	<li><?=$d->name ?> (asset tag: <?=$d->asset_tag ?>, serial number: <?=$d->serial?>)</li>
	<?php endforeach; ?>
</ol>

<?php 
else:?>

<div class="alert alert-warning">Non risultano PC assegnati a te.</div>

<?php endif;?>
<hr/>

<h2>Antivirus</h2>
<?php if(count($var_in_view['dotazioni'])):
$d=$var_in_view['dotazioni'][0];
?>

<h3>Stato installazione</h3>
<?php if(in_array(substr(getMyUsername(),0,10).$d->asset_tag, $var_in_view['antivirusInstallati'])):
$install_ok=true;
?>
<div class="alert alert-success"  style="max-width:700px">L'antivirus risulta installato correttamente su <?=$d->name ?> (asset tag: <?=$d->asset_tag ?>, serial number: <?=$d->serial?>).</div>

<?php else:
$install_ok=false;
?>
<div class="alert alert-danger"  style="max-width:700px">L'antivirus non risulta installato correttamente. Segui le istruzioni sotto e ritorna qui un'ora dopo l'installazione per verificare.</div>
<?php endif;?>


<?php if(!$install_ok || getMyUsername()=='gbartolomucci'):?>
<h3>Istruzioni</h3>
<?php if(count($var_in_view['dotazioni'])>1):?>
<p>Hai pi&ugrave; di un PC aziendale. Ti chiediamo di installare l'antivirus sul tuo PC aziendale pi&ugrave; recente e di restituire il PC aziendale vecchio non appena possibile.</p>
<?php endif;?>
<p>L'antivirus sar&agrave; installato sul tuo PC <strong><?=$d->name ?> (asset tag: <?=$d->asset_tag ?>, serial number: <?=$d->serial?>)</strong></p>
<p>Abbiamo un numero di licenze limitato per cui se hai necessit&agrave; di installare l'antivirus su altri sistemi ti chiediamo di contattarci.</p>


<h3>Istruzioni (Microsoft Windows)</h3>
<div class="alert alert-warning"  style="max-width:700px"><span class="glyphicon glyphicon-warning-sign"></span> &Egrave; importante seguire scrupolosamente tutte le istruzioni di seguito riportate. In caso di dubbi scrivi a support_security@mediamenteconsulting.it</div>
<ol>
	<li>Inizia questa procedura quando puoi riavviare il tuo PC.</li>
	<li>Disinstalla tutti i software antivirus precedentemente installati sul tuo PC (es.: AVG, Avast, Avira, HP Sure Click, HP Client Security Manager ecc.)</li>
	<li>Riavvia il computer</li>
	<li>Apri il menu <em>Start</em>, cerca <em>Prompt dei comandi</em>, fai click destro e scegli <em>Apri come amministratore</em></li>
	<li>Incolla la seguente stringa all'interno del Prompt dei comandi (finestra con sfondo nero):
	<pre>WMIC computersystem where caption='%computername%' rename <?=substr(getMyUsername(),0,10).$d->asset_tag?></pre></li>
	<li>Se l'operazione &egrave; riuscita correttamente dovresti vedere:
	<pre>...
Esecuzione del metodo riuscita.
Parametri Out:
instance of __PARAMETERS
{
        ReturnValue = 0;
};</pre><br/>
<strong>Se ReturnValue=5 assicurati di avere aperto il prompt dei comandi come amministratore!</strong>
	</li>
	<li>Riavvia il computer</li>
	<li><a href="https://cloudgz.gravityzone.bitdefender.com/Packages/BSTWIN/0/setupdownloader_[aHR0cHM6Ly9jbG91ZGd6LWVjcy5ncmF2aXR5em9uZS5iaXRkZWZlbmRlci5jb20vUGFja2FnZXMvQlNUV0lOLzAvZjU4LUdOL2luc3RhbGxlci54bWw-bGFuZz1pdC1JVA==].exe">Scarica questo file di installazione</a></li>
	<li>Apri il file scaricato e segui le istruzioni.</li>
</ol>
<?php endif;?>
<?php else:?>
<div class="alert alert-warning">Non risultano PC assegnati a te.
<ul>
<li>Se hai un PC personale che utilizzi sulla rete aziendale per favore scrivi a support_security@mediamenteconsulting.it per ricevere istruzioni puntuali per installare l'antivirus.</li>
<li>Se sei in attesa di ricevere un PC aziendale torna qui non appena hai ricevuto il PC e comunicato il serial number per il censimento.</li>
<li>Se hai gi&agrave; il PC aziendale e non risulta censito inviaci marca, modello e serial number a support_security@mediamenteconsulting.it</li>
<li>Se non sei un dipendente di Mediamente l'installazione di un Antivirus &egrave; tua responsabilit&agrave;</li>
</ul>
</div>
<?php endif;?>

<?php ?>


<?php if(getMyUsername()=="dchiarello" || getMyUsername()=="vscinicariello")
{ echo "<pre>"; print_r( $var_in_view['antivirusInstallati'] ); echo "</pre>"; }
    ?>