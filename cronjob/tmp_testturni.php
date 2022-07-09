<?php

set_time_limit(60);
include "/var/www/vhosts/apps.mmonline.it/app.config.php";
include "/var/www/vhosts/apps.mmonline.it/libraries/ctions.php";
include "/var/www/vhosts/apps.mmonline.it/helpers/ez_sql_core.php";
include "/var/www/vhosts/apps.mmonline.it/helpers/ez_sql_mysqli.php";

startup();


require_once CLASS_PATH . 'turnistica.class.php';


/*
 * 
 *   $this->bozza->id= $array['id_bozza'] ?  $array['id_bozza'] : null;
        $this->settimana= $array['settimana'] ?  $array['settimana'] : null;
        $this->anno= $array['anno'] ?  $array['anno'] : null;
        $this->id_progetto= $array['id_progetto'] ?  $array['id_progetto'] : null;
 */
$turnistica = new Turnistica(array());

$turnistica->enableDebug();

$turnistica->aggiungiBozza();

$turnistica->setProgetto(7);

$turnistica->setSettimana(5, 2021);

$turnistica->pianificaSettimana();

$turnistica->setSettimana(6, 2021);

$turnistica->pianificaSettimana();

$turnistica->setSettimana(7, 2021);

$turnistica->pianificaSettimana();

$turnistica->setSettimana(8, 2021);

$turnistica->pianificaSettimana();

$turnistica->setSettimana(9, 2021);

$turnistica->pianificaSettimana();


$turnistica->approvaBozza();