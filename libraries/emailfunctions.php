<?php 
//EMAILS
function sendEmail($to,$subject,$body,$cc=null,$replyTo=null,$attachments=null)
{
	//if(getMyUsername()=="sleoni")
		//return;
		
	require_once INCLUDE_PATH."helpers/PHPMailer-master/PHPMailerAutoload.php";
	
	$templateEmail=file_get_contents(INCLUDE_PATH."helpers/template_email.htm");
	$templateEmail=str_replace("%testoemail%",$body,$templateEmail);
	$templateEmail=str_replace("%SERV_URL%",EMAIL_SERV_URL,$templateEmail);
	$templateEmail=str_replace("%urlSito%",EMAIL_SERV_URL,$templateEmail);
	
	$email = new PHPMailer(true);
	try {
	$email->CharSet = 'UTF-8';
	$email->Subject   = $subject;
	$email->Body      = $templateEmail;
	$email->AltBody = $body;

	//$email->IsSMTP(); // telling the class to use SMTP
	$email->Host       = "192.168.2.111"; // SMTP server
	
	for($i=0;$i<count($to);$i++)
		{
			if(trim($to[$i])!="")
			{
				$email->AddAddress( trim($to[$i]));
			}
				
		}
	
	if(isset($cc))
	{
		for($i=0;$i<count($cc);$i++)
		{
			if(trim($cc[$i])!="")
				$email->AddCC( trim($cc[$i]));
		}
	}
	
	if(isset($replyTo))
	{
		for($i=0;$i<count($replyTo);$i++)
		{
			if(trim($replyTo[$i])!="")
				$email->AddReplyTo( trim($replyTo[$i]));
		}
	}
	
	if(isset($attachments))
	{
		for($i=0;$i<count($attachments);$i++)
		{
		$email->AddAttachment( $attachments[$i]);
		}
	}
	
	$email->Sender      = 'no-reply@mediamenteconsulting.it';
	$email->From      = 'no-reply@mediamenteconsulting.it';
	$email->FromName  = 'Pannello AMS';
		
	$email->IsHTML(true);
	

	return $email->Send();
	} catch (phpmailerException $e) {
		file_put_contents(ROOT_PATH."logs/errors.log",date("Y-m-d H:i:s",time())." - EMAIL ".print_r($to,true)." ".$e->errorMessage(),FILE_APPEND);
		//echo  //Pretty error messages from PHPMailer
	} catch (Exception $e) {
		file_put_contents(ROOT_PATH."logs/errors.log",date("Y-m-d H:i:s",time())." - EMAIL ".print_r($to,true)." ".$e->getMessage(),FILE_APPEND);
		//echo ; //Boring error messages from anything else!
	}
}

function email_allineamenti($id_richiesta,$tipoEmail)
{
	include INCLUDE_PATH."helpers/testi.php";
	global $db;
	
	$dati=$db->get_row("SELECT nome_db,dataora FROM richieste WHERE id=".$id_richiesta);
	
	
	$variabili=Array(
			"%nome_db%"=>$dati->nome_db,
			"%dataora%"=>giornodataeora($dati->dataora,"ore "),
	);
	
	$tipoEmail=$$tipoEmail;

	$body= strtr($tipoEmail['testo'],$variabili);
	$subject= strtr($tipoEmail['oggetto'],$variabili);
	
	$cc=Array();

	$emails=explode(",",$db->get_var("SELECT e.destinatari FROM destinatari_email e,db_allineabili d WHERE d.id_destinatari_email=e.id AND d.nome_db='".$dati->nome_db."'"));
	
	$replyTo=Array();
	
	$amministratori=$db->get_results("SELECT email FROM amministratori");
	
	for($i=0;$i<count($amministratori);$i++)
	{
		array_push($replyTo,$amministratori[$i]->{'email'});
	}

		
	sendEmail($emails,$subject,$body,$cc,$replyTo);
}

function email_richiesta_allineamento($id_richiesta)
{
	include INCLUDE_PATH."helpers/testi.php";
	global $db;
	
	$dati=$db->get_row("SELECT nome_db,dataora FROM richieste WHERE id=".$id_richiesta);
	
	
	$variabili=Array(
			"%nome_db%"=>$dati->nome_db,
			"%dataora%"=>giornodataeora($dati->dataora,"ore "),
	);
	
	$tipoEmail=$EMAIL_NUOVA_RICHIESTA_ALLINEAMENTO;

	$body= strtr($tipoEmail['testo'],$variabili);
	$subject= strtr($tipoEmail['oggetto'],$variabili);


	$emails=Array();
	$amministratori=$db->get_results("SELECT email FROM amministratori");
	
	for($i=0;$i<count($amministratori);$i++)
	{
		array_push($emails,$amministratori[$i]->{'email'});
	}
	
		
	sendEmail($emails,$subject,$body);
}

function email_richiesta_blocco($id_richiesta)
{
	include INCLUDE_PATH."helpers/testi.php";
	global $db;
	
	$dati=$db->get_row("SELECT nome_db FROM blocchi WHERE id=".$id_richiesta);
	
	
	$variabili=Array(
			"%nome_db%"=>$dati->nome_db
	);
	
	$tipoEmail=$EMAIL_NUOVA_RICHIESTA_BLOCCO;

	$body= strtr($tipoEmail['testo'],$variabili);
	$subject= strtr($tipoEmail['oggetto'],$variabili);


	$emails=Array();
	$amministratori=$db->get_results("SELECT email FROM amministratori");
	
	for($i=0;$i<count($amministratori);$i++)
	{
		array_push($emails,$amministratori[$i]->{'email'});
	}

		
	sendEmail($emails,$subject,$body);
}

function email_supporto($id_richiesta,$tipoEmail)
{
	include INCLUDE_PATH."helpers/testi.php";
	global $db;
	
	$dati=$db->get_row("SELECT nome_db,dataora FROM richieste WHERE id=".$id_richiesta);
	
	
	$variabili=Array(
			"%nome_db%"=>$dati->nome_db,
			"%dataora%"=>giornodataeora($dati->dataora,"ore "),
	);
	
	$tipoEmail=$$tipoEmail;

	$body= strtr($tipoEmail['testo'],$variabili);
	$subject= strtr($tipoEmail['oggetto'],$variabili);
	
	$cc=Array();
		
	sendEmail(Array("support_oracle@mediamenteconsulting.it"),$subject,$body,$cc,Array("support_oracle@mediamenteconsulting"));
}


?>
