<?php 

set_time_limit(60);
include "/var/www/vhosts/apps.mmonline.it/app.config.php";
include "/var/www/vhosts/apps.mmonline.it/commonfunctions.php";
include "/var/www/vhosts/apps.mmonline.it/helpers/ez_sql_core.php";
include "/var/www/vhosts/apps.mmonline.it/helpers/ez_sql_mysqli.php";
include_once "/var/www/vhosts/apps.mmonline.it/helpers/ez_sql_oracle8_9.php";

startup ();


require_once CLASS_PATH.'calendario.class.php';

$calendario=new Calendario();

$calendario->calendarioAMSSync();

?>