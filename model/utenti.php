<?php
include "../app.config.php";
include "../commonfunctions.php";
include "../helpers/ez_sql_core.php";
include "../helpers/ez_sql_mysqli.php";
include_once "../helpers/ez_sql_oracle8_9.php";
require_once "../acl.php";

$ACL=new ACL();
$db = null;
startup ();

//ini_set("display_errors",1);


if($_POST ['action']!="login" && $_POST ['csrfToken']!=$_SESSION['csrfToken'.$_POST['csrfTokenID']])
	exitWithError("U02","CSRF Attack - Sessione scaduta, aggiorna la pagina");

switch ($_POST ['action']) {
	

    case "login" :
		
            if(isset($_POST['recaptchaResponse']) && !empty($_POST['recaptchaResponse'])) {
                //get verify response data
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.RECAPTCHA_SECRET_KEY.'&response='.$_POST['recaptchaResponse']);
                $responseData = json_decode($verifyResponse);
                
                if(!$responseData->success):
                exitWithError ( "U16", "Captcha errato");
                endif;
            }
            else {
                exitWithError ( "U16", "Captcha errato");
            }
        
		    $adServer = "LDAP://192.168.2.4:389";
	
			$ldap = ldap_connect($adServer);
			$username = $_POST['username'];
			$md5username=md5($username);
			$password = $_POST['password'];
			
			//if($db->get_var("SELECT COUNT(*) FROM utenti WHERE MD5(username)='$md5username'")==0)
			  //  exitWithError ( "U03", "Username o Password non validi" );

			$ldaprdn = 'mmonline' . "\\" . $username;

			ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

			$bind = @ldap_bind($ldap, $ldaprdn, $password);


			if ($bind) {
			    $filter="(sAMAccountName=$username)";
			    $result = ldap_search($ldap,"dc=MMONLINE,dc=LOCAL",$filter);
			    ldap_sort($ldap,$result,"sn");
			    $info = ldap_get_entries($ldap, $result);
			    

				if(!$info["count"])
				{
				    @ldap_close($ldap);
				    exitWithError ( "U03", "Username o Password non validi" );
				}
				
				//CN=GroupMMI,CN=Users,DC=mmonline,DC=local
				
				if(    !in_array("CN=GroupMMI,CN=Users,DC=mmonline,DC=local", $info[0]["memberof"])
				    && !in_array("CN=GroupMMBI,CN=Users,DC=mmonline,DC=local", $info[0]["memberof"])
				    && !in_array("CN=GroupMMK,CN=Users,DC=mmonline,DC=local", $info[0]["memberof"])
				    && !in_array("CN=GroupMM,CN=Users,DC=mmonline,DC=local", $info[0]["memberof"])
				  )
				    exitWithError ( "U10", "Non sei autorizzato ad accedere" );

			    doUserLogin ( $info[0]["samaccountname"][0],$info[0]["givenname"][0]." ". $info[0]["sn"][0]  );
			    
			    @ldap_close($ldap);
			    
			    disconnectDB();
			    die ( json_encode ( Array (
			        "error" => "0",
			        "readableMsg" => ""
			        ) ) );
				
			} else {
				@ldap_close($ldap);
				exitWithError ( "U03", "Username o Password non validi" );
			}
		
		break;
		

	default :
		utenti_requireLogin ();
		
	
}
function utenti_requireLogin($action="index") {
		global $ACL;
		if (! $ACL->hasAccess("utenti",$action))
			exitWithError ( "U01", "Utente non autenticato" );
}


?>