<?php
require ROOT_PATH . "libraries/emailfunctions.php";

function throw_error($text)
{
	header("HTTP/1.0 500 Internal Server Error");
	die("<h1>ERRORE FATALE</h1><p>" . $text . "</p>");
}
function throw_403($text = "")
{
	global $CONTROLLER;
	global $ACTION;
	file_put_contents(ROOT_PATH . "logs/errors.log", date("Y-m-d H:i:s", time()) . " - HTTPError 403 - " . getMyUsername() . " - " . $CONTROLLER . "/" . $ACTION . "\n", FILE_APPEND);
	include ROOT_PATH . "errorPages/403.php";
	die();
}
function throw_404($text = "")
{
	global $CONTROLLER;
	global $ACTION;
	header("HTTP/1.0 404 Not Found");
	file_put_contents(ROOT_PATH . "logs/errors.log", date("Y-m-d H:i:s", time()) . " - HTTPError 404 - " . getMyUsername() . " - " . $CONTROLLER . "/" . $ACTION . "\n", FILE_APPEND);
	include ROOT_PATH . "errorPages/404.php";
	die();
}
function throw_500($text = "")
{
	global $CONTROLLER;
	global $ACTION;
	header("HTTP/1.0 500 Internal Server Error");
	file_put_contents(ROOT_PATH . "logs/errors.log", date("Y-m-d H:i:s", time()) . " - HTTPError 500 - " . getMyUsername() . " - " . $CONTROLLER . "/" . $ACTION . "\n", FILE_APPEND);
	include ROOT_PATH . "errorPages/500.php";
	die();
}
function throw_changeServer($text = "")
{
	include ROOT_PATH . "errorPages/changeServer.php";
	die();
}
function exitWithError($errorNumber, $readableMsg)
{
	global $CONTROLLER;
	global $ACTION;
	disconnectDB();
	header("HTTP/1.0 400 Bad Request");
	header("Content-Type: application/json", true);
	if ($errorNumber != "U16") //non loggo errori captcha
		file_put_contents(ROOT_PATH . "logs/errors.log", date("Y-m-d H:i:s", time()) . " - Errore " . $errorNumber . " - " . getMyUsername() . " - " . $readableMsg . "\n", FILE_APPEND);
	die(json_encode(array(
		"error" => $errorNumber,
		"readableMsg" => $readableMsg
	)));
}


function logError($type, $text = "")
{
	global $CONTROLLER;
	global $ACTION;
	file_put_contents(ROOT_PATH . "logs/errors.log", date("Y-m-d H:i:s", time()) . " - $type - " . getMyUsername() . " - " . $CONTROLLER . "/" . $ACTION . " - $text\n", FILE_APPEND);
}

// STARTUP AND DATABASE
function startup()
{

	global $db;
	global $orcldb;
	// INIZIALIZZAZIONE DB
	$db = new ezSQL_mysqli();
	$connectionResult = $db->quick_connect(DB_USERNAME, DB_PASSWORD, DB_NAME, DB_HOST);

	if (!$connectionResult)
		throw_error("Connessione al DB fallita");


	$db->hide_errors();
	// ////////////////////////////////////////////////////

	// INIZIALIZZAZIONE ORACLE
	// Initialise database object and establish a connection
	// at the same time - db_user / db_password / db_name
	//TODO $orcldb = new ezSQL_oracle8_9(ORACLE_DBUSER,ORACLE_DBPASS,ORACLE_DBHOST.'/'.ORACLE_DBNAME);

	//file_put_contents(ROOT_PATH."logs/errors.log",date("Y-m-d H:i:s",time())." - Can't connect to AMS Oracle DB ".$orcldb->get_var("SELECT * FROM dual;")."\n",FILE_APPEND);

	// INIZIALIZZAZIONE SESSIONE
	if (!session_id())
		@session_start();
}

function disconnectDB()
{
	global $db;
	@$db->disconnect();
}

// VIEWS FUNCTIONS
function generate_view($view, $layout, $var_in_view)
{
	include $layout;
}

// LOGIN FUNCTIONS
function requireLogin($controller = null, $action = null)
{
	global $CONTROLLER;
	global $ACTION;
	if (isset($_SESSION['obbligoCambioPassword']) && ($CONTROLLER != "utenti" || $ACTION != "modificapassword")) {
		header("Location:" . SERV_URL . "utenti/modificapassword");
		die();
	}


	if (!hasLoggedIn()) {
		$_SESSION['return'] = $_SERVER['REQUEST_URI'];
		header("Location:" . SERV_URL . "utenti/login");
		die();
	}

	if (!$controller && !$action)
		return true;

	if (!ACLhasAccess($controller, $action))
		throw_403();
}

function requireLoginExt()
{
	if (!verifyExternalLogin($_SESSION['ext_nome_cliente'],  $_SESSION['ext_apikey_id'], $_SESSION['ext_apikey_token_given']))
		throw_403();
}
function hasLoggedIn()
{
	$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
	if (isset($username) && $username != null && $username != "")
		return true;

	if (isset($_COOKIE["username"]) && isset($_COOKIE["timestamp"]) && isset($_COOKIE["authtoken"])) {
		if (doSHAWithSalt($_COOKIE["timestamp"] . SECURITY_SALT . "login//" . $_COOKIE["username"]) == $_COOKIE["authtoken"]) {
			$_SESSION['username'] = $_COOKIE["username"];
			return true;
		}
	}

	return false;
}
function getMyUsername()
{
	$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
	if (!isset($username) || $username == "" || $username == null)
		return false;

	return $username;
}

function getCognomeNome($username)
{
	global $db;
	return $db->get_var("SELECT CONCAT(cognome,' ',nome) FROM utenti WHERE username='$username'");
}

function getMyName()
{
	$name = isset($_SESSION['name']) ? $_SESSION['name'] : null;
	if (!isset($name) || $name == "" || $name == null)
		return false;

	return $name;
}

function getMyRole()
{
	global $db;
	$username = getMyUsername();
	return $db->get_var("SELECT ruolo FROM utenti WHERE username='" . $username . "'");
}

function ACLhasAccess($controller, $action)
{
	require_once "libraries/acl.php";
	$ACL = new ACL();
	return $ACL->hasAccess($controller, $action);
}


function doSHAWithSalt($password)
{
	return hash("sha256", SECURITY_SALT . $password);
}
function doUserLogin($username, $name)
{
	$_SESSION['username'] = $username;
	$_SESSION['name'] = $name;
	$time = time();
	setcookie("username", $username, $time + (3600 * 24 * 7), "/");  /* expire in 7 days */
	setcookie("timestamp", $time, $time + (3600 * 24 * 7), "/");  /* expire in 7 days */
	setcookie("authtoken", doSHAWithSalt($time . SECURITY_SALT . "login//" . $username), $time + (3600 * 24 * 7), "/");  /* expire in 7 days */
}
function doUserLogout($redirect = true)
{
	unset($_SESSION['obbligoCambioPassword']);
	$_SESSION['username'] = null;
	unset($_SESSION['username']);
	session_destroy();

	setcookie("username", "", $time - 1, "/");  /* expire now */
	setcookie("timestamp", "", $time - 1, "/");  /* expire  now */
	setcookie("authtoken", "", $time - 1, "/");  /* expire now */

	if (!$redirect)
		return;
	header("Location:" . SERV_URL . "utenti/login");
	die();
}

function v_getPostVar($name, $addQuotes = true)
{
	if (!isset($_POST[$name])) {
		if (!$addQuotes)
			return null;
		else
			return "''";
	}
	$input = html_entity_decode($_POST[$name], ENT_QUOTES, 'UTF-8');
	$text = "";
	$text .= $addQuotes ? "'" : "";
	$text .= addslashes(filter_var(htmlentities($input), FILTER_SANITIZE_STRING));
	$text .= $addQuotes ? "'" : "";
	return $text;
}
function randomPassword()
{
	$alphabet = "ABCDEFGHJKLMNOPQRSTUWXYZ0123456789";
	$pass = array(); // remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; // put the length -1 in cache
	for ($i = 0; $i < 10; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); // turn the array into a string
}

/**
 * Returns an encrypted & utf8-encoded
 */
function encrypt($pure_string, $encryption_key = SECURITY_SALT)
{
	$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
	return $encrypted_string;
}

/**
 * Returns decrypted original string
 */
function decrypt($encrypted_string, $encryption_key = SECURITY_SALT)
{
	$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
	return $decrypted_string;
}
// / END LOGIN FUNCTIONS
function setFlash($text)
{
	$_SESSION['flash'] = $text;
}
function printFlash()
{
	if (!isset($_SESSION['flash']))
		return;
	echo $_SESSION['flash'];
	$_SESSION['flash'] = "";
}
function array_push_assoc($array, $key, $value)
{
	$array[$key] = $value;
	return $array;
}
function generateUniqueId($maxLength = null)
{
	$entropy = '';

	// try ssl first
	if (function_exists('openssl_random_pseudo_bytes')) {
		$entropy = openssl_random_pseudo_bytes(64, $strong);
		// skip ssl since it wasn't using the strong algo
		if ($strong !== true) {
			$entropy = '';
		}
	}

	// add some basic mt_rand/uniqid combo
	$entropy .= uniqid(mt_rand(), true);


	$hash = hash('whirlpool', $entropy);
	if ($maxLength) {
		return substr($hash, 0, $maxLength);
	}
	return $hash;
}


// AJAX FUNCTIONS
function ajaxSubmit($id, $model, $action, $params, $firedBy, $success = null, $error = null, $askConfirm = null)
{
	$csrfToken = sha1(SECURITY_SALT . $model . $action . generateUniqueId());
	$csrfTokenID = generateUniqueId();
	$_SESSION['csrfToken' . $csrfTokenID] = $csrfToken;
	echo '<script>
		$("#' . $firedBy . '").on("click",function(){
			$("#ajaxSubmit' . $id . '").hide();
		    var foundMissing=false;	
			$("form#ajaxForm' . $id . ' :input").each(function(){
			 var input = $(this); // This is the jquery object of the input, do what you will
			 var attr = input.attr(\'required\');

					if (typeof attr !== typeof undefined && attr !== false) {
					    if(!input.val() || input.val().trim()=="")
					    {
					    	foundMissing=true;
					    	$("#"+input.attr("id")+"_container").addClass("has-error");
					    }
					    else
					    	$("#"+input.attr("id")+"_container").removeClass("has-error");	
					}
			});
			if(foundMissing==true)
			{
				$("#ajaxSubmit' . $id . '").removeClass("alert-success").removeClass("alert-danger").addClass("alert-warning");
				$("#ajaxSubmit' . $id . '").html("<strong>Ops!</strong> Compila tutti i campi richiesti.");
				$("#ajaxSubmit' . $id . '").show();
				return;
			}
			 
			';
	if ($askConfirm != null)
		echo 'if(!confirm("' . $askConfirm . '")) return;';

	echo '$("#' . $firedBy . '").attr("disabled","disabled");
		  	//Show the loader
    		$("#ajax_loader").show();
			
			$.ajax({
			      type: "POST",
			      dataType: "json",
			      url: "' . SERV_URL . 'model/' . $model . '.php",
			      timeout: 30000,
			      cache:false,
			      statusCode: {
			        500: function() {
			          $("#ajaxSubmit' . $id . '").html("<strong>Errore.</strong> Errore interno del server.</strong>");
			          $("#ajaxSubmit' . $id . '").show();
			        }
			      },
			      data: {';
	$vars = "";
	foreach ($params as $key => $value) {
		$vars .= $key . ":" . $value . ",";
	}
	$vars .= "action:'" . $action . "',";
	$vars .= "csrfToken:'" . $csrfToken . "',";
	$vars .= "csrfTokenID:'" . $csrfTokenID . "'";
	echo $vars;
	echo '},
			      success: function(msg){
						var obj = msg;
						$("#ajax_loader").hide();
			      		$("#' . $firedBy . '").attr("disabled","");
						$("#' . $firedBy . '").removeAttr("disabled","");
						$("#ajaxSubmit' . $id . '").removeClass("alert-danger").removeClass("alert-warning").addClass("alert-success");
						$("#ajaxSubmit' . $id . '").html(obj.readableMsg);
						if(obj.readableMsg.trim()!="")
							$("#ajaxSubmit' . $id . '").show();
						' . $success . '
								
						
			      },
			      error: function(msg,textStatus) {
						var obj = msg.responseJSON;
						$("#ajax_loader").hide();
			      		$("#' . $firedBy . '").attr("disabled","");
						$("#' . $firedBy . '").removeAttr("disabled","");
						$("#ajaxSubmit' . $id . '").removeClass("alert-success").removeClass("alert-warning").addClass("alert-danger");
						if(obj && obj.readableMsg)
							$("#ajaxSubmit' . $id . '").html("<strong>Errore.</strong> "+obj.readableMsg + ". <strong>Codice errore: "+obj.error+"</strong>");
						else if(msg.readyState ==0)
							$("#ajaxSubmit' . $id . '").html("<strong>Errore.</strong> Timeout/errore connessione.");
						else
							$("#ajaxSubmit' . $id . '").html("<strong>Errore.</strong> "+textStatus);
			      		$("#ajaxSubmit' . $id . '").show();
						 ' . $error . '
						 		
						
			      }
			});
		});

        $("form#ajaxForm' . $id . '" ).on( "keydown", function(event) {
            if(event.which == 13) 
                {
                        event.preventDefault();
                }
        });
		</script>';
}

function ajaxFunction($id, $model, $action, $params, $function, $success = null, $error = null, $askConfirm = null, $hideLoader = false)
{
	$csrfToken = sha1(SECURITY_SALT . $model . $action . generateUniqueId());
	$csrfTokenID = generateUniqueId();
	$_SESSION['csrfToken' . $csrfTokenID] = $csrfToken;
	echo '<script>
		function ' . $function . '(';
	$vars = "";
	foreach ($params as $key) {
		$vars .= $key . ",";
	}
	$vars = rtrim($vars, ",");
	echo $vars;
	echo '){
			$("#ajaxSubmit' . $id . '").hide();
			 
			';
	if ($askConfirm != null)
		echo 'if(!confirm("' . $askConfirm . '")) return;';

	if (!$hideLoader)
		echo '	//Show the loader
        		$("#ajax_loader").show();
     ';
	echo '	
			$.ajax({
			      type: "POST",
			      dataType: "json",
			      url: "' . SERV_URL . 'model/' . $model . '.php",
			      timeout: 30000,
			      cache:false,
			      statusCode: {
			        500: function() {
			          $("#ajaxSubmit' . $id . '").html("<strong>Errore.</strong> Errore interno del server.</strong>");
			          $("#ajaxSubmit' . $id . '").show();
			        }
			      },
			      data: {';
	$vars = "";
	foreach ($params as $key) {
		$vars .= $key . ":" . $key  . ",";
	}
	$vars .= "action:'" . $action . "',";
	$vars .= "csrfToken:'" . $csrfToken . "',";
	$vars .= "csrfTokenID:'" . $csrfTokenID . "'";
	echo $vars;
	echo '},
			      success: function(msg){
						var obj = msg;
						$("#ajax_loader").hide();

						$("#ajaxSubmit' . $id . '").removeClass("alert-danger").removeClass("alert-warning").addClass("alert-success");
						$("#ajaxSubmit' . $id . '").html(obj.readableMsg);
						if(obj.readableMsg.trim()!="")
							$("#ajaxSubmit' . $id . '").show();
						' . $success . '
								
						
			      },
			      error: function(msg,textStatus) {
						var obj = msg.responseJSON;
						$("#ajax_loader").hide();
						$("#ajaxSubmit' . $id . '").removeClass("alert-success").removeClass("alert-warning").addClass("alert-danger");
						if(obj && obj.readableMsg)
							$("#ajaxSubmit' . $id . '").html("<strong>Errore.</strong> "+obj.readableMsg + ". <strong>Codice errore: "+obj.error+"</strong>");
						else if(msg.readyState ==0)
							$("#ajaxSubmit' . $id . '").html("<strong>Errore.</strong> Timeout/errore connessione.");
						else
							$("#ajaxSubmit' . $id . '").html("<strong>Errore.</strong> "+textStatus);
			      		$("#ajaxSubmit' . $id . '").show();
						 ' . $error . '
						 		
						
			      }
			});
		};
		</script>';
}

function ajaxSearch($id, $model, $action, $searchTerm, $firedBy, $success = null, $error = null)
{
	$csrfToken = sha1(SECURITY_SALT . $model . $action . generateUniqueId());
	$csrfTokenID = generateUniqueId();
	$_SESSION['csrfToken' . $csrfTokenID] = $csrfToken;
	echo '<script>
		var isSearching=null;
		$("#' . $firedBy . '").on("keydown",function(){
				
			if(' . $searchTerm . '.length<3)
				{
				$(".rowSearch").show();
				$("#ajaxLoader' . $id . '").hide();
				$("#ajaxTable' . $id . '").addClass("table-striped");
				clearTimeout(isSearching);
				return;
				}
			else
				{
				$("#ajaxLoader' . $id . '").show();
				$("#ajaxTable' . $id . '").removeClass("table-striped");
				$(".rowSearch").hide();
				}
					
			clearTimeout(isSearching);
			isSearching=setTimeout("doSearch()",500);

		});
			';

	echo '
	function doSearch(){
			$.ajax({
			      type: "POST",
			      dataType: "json",
			      url: "' . SERV_URL . 'model/' . $model . '.php",
			      timeout: 30000,
			      cache:false,
			      data: {';
	$vars = "cerca:" . $searchTerm . ",";
	$vars .= "action:'" . $action . "',";
	$vars .= "csrfToken:'" . $csrfToken . "',";
	$vars .= "csrfTokenID:'" . $csrfTokenID . "'";
	echo $vars;
	echo '},
			      success: function(msg){
						var obj = msg;
						
						if(obj.ids!=null)
						obj.ids.forEach(function(entry) {
							//console.log("#row_"+entry.id);
						    $("#row_"+entry.id).show();
						})
						' . $success . '
						
			      },
			      error: function(msg) {
						var obj = msg.responseJSON;
						 ' . $error . '
			      },
			      complete: function(){
						$("#ajaxLoader' . $id . '").hide();
			      }
			});
		}
			
		</script>';
}
function ajaxFileUpload($id, $model, $action, $params, $requiredTypeArr, $requiredExtArr, $success = null, $error = null)
{
	$csrfToken = sha1(SECURITY_SALT . $model . $action . generateUniqueId());
	$csrfTokenID = generateUniqueId();
	$_SESSION['csrfToken' . $csrfTokenID] = $csrfToken;

	if (!is_array($requiredTypeArr)) {
		$requiredType = '!files[i].type.match(\'' . $requiredTypeArr . '\')';
		$requiredExt = '*.' . $requiredExtArr;
	} else {
		$requiredType = '!files[i].type.match(\'' . $requiredTypeArr[0] . '\')';
		$requiredExt = '*.' . $requiredExtArr[0];
		for ($i = 1; $i < count($requiredTypeArr); $i++) {
			$requiredType .= '&& !files[i].type.match(\'' . $requiredTypeArr[$i] . '\')';
			$requiredExt .= ',*.' . $requiredExtArr[$i];
		}
	}

	echo '<script>
			window.URL = window.URL || window.webkitURL;
			var uploaded' . $id . '=0;
			
			var fileSelect = document.getElementById("fileSelect"),
			    fileElem = document.getElementById("fileElem' . $id . '"),
			    fileList = document.getElementById("fileList");
			
			fileSelect.addEventListener("click", function (e) {
			  if (fileElem) {
			    fileElem.click();
			  }
			  e.preventDefault(); // prevent navigation to "#"
			}, false);
			
			function handleFiles(files) {
			  if (files.length) {
			    var list = document.createElement("ul");
			    for (var i = 0; i < files.length; i++) {
				  //if (' . $requiredType . ')
				  	//	{
				  	//	alert("Tipo file non corretto. Tipi di file accettati: ' . $requiredExt . '. Formato rilevato: "+files[i].type);
				  	//	return;
				  	//	}
				  uploaded' . $id . '++;
			      var li = document.createElement("li");
				  li.setAttribute("id", "file_"+uploaded' . $id . ');
			      fileList.appendChild(li);
			      
			      var info = document.createElement("span");
			      info.innerHTML = files[i].name + ": " + files[i].size + " bytes";
			      li.appendChild(info);
				  		
				  var icon = document.createElement("span");
				  icon.setAttribute("id", "icon_"+uploaded' . $id . ');
				  icon.setAttribute("class", "flaticon-uploads1");
				  $("#ajax_loader").show();
				  li.appendChild(icon);
			      sendFile(files[i]);
			    }
			  }
			}
			
			function sendFiles() {
				  var fls = document.querySelectorAll(".obj");
				  
				  for (var i = 0; i < fls.length; i++) {
				    new FileUpload(fls[i], fls[i].file);
				  }
				}
			
			function sendFile(file) {
			    var uri = "' . SERV_URL . 'model/' . $model . '.php";
			    var xhr = new XMLHttpRequest();
			    var fd = new FormData();
			    
			    xhr.open("POST", uri, true);
			    xhr.onreadystatechange = function() {
			    	$("#ajax_loader").hide();
			        if (xhr.readyState == 4 && xhr.status == 200) {
			            // Handle response.
			            $("#file_"+uploaded' . $id . ').addClass("bg-success");
			            $("#icon_"+uploaded' . $id . ').removeClass("flaticon-uploads1").addClass("flaticon-check19");
                        ' . $success . '
			        }
			        else{
			            obj=jQuery.parseJSON(xhr.responseText);
			           	$("#file_"+uploaded' . $id . ').addClass("bg-danger");
			           	$("#file_"+uploaded' . $id . ').html("<span style=\'width:16px;height:16px\' class=\'flaticon-close12\'></span>"+obj.readableMsg);
			           	
			            $("#fileElem' . $id . '").replaceWith($("#fileElem' . $id . '").val(\'\').clone(true));
			      
			        }
			    };';
	$vars = "";
	foreach ($params as $key => $value) {
		$vars .= "fd.append('" . $key . "'," . $value . ");";
	}
	echo $vars;
	echo 'fd.append(\'myFile\', file);
			    fd.append(\'csrfToken\', "' . $csrfToken . '");
			    fd.append(\'csrfTokenID\', "' . $csrfTokenID . '");
			    fd.append(\'action\', "' . $action . '");
			    // Initiate a multipart/form-data upload
			    xhr.send(fd);
			}
			
			</script>';
}

function ajaxSelezionaComune()
{
	$csrfToken = sha1(SECURITY_SALT . $model . $action . generateUniqueId());
	$csrfTokenID = generateUniqueId();
	$_SESSION['csrfToken' . $csrfTokenID] = $csrfToken;
	echo '<script>';

	echo '
	$("#geo_regione").on("change",function(){
		$("#geo_comune").find("option").remove().end();
		$("#geo_comune").append("<option value=\"\">Seleziona prima la provincia</option>");
		geografia("getProvinceRegione",$("#geo_regione").val(),$("#geo_provincia"));
	});
	$("#geo_provincia").on("change",function(){
		geografia("getComuniProvincia",$("#geo_provincia").val(),$("#geo_comune"));
	});
			
	function geografia(action,data,apply_to){
			$("#ajax_loader").show();
			$.ajax({
			      type: "POST",
			      dataType: "json",
			      url: "' . SERV_URL . 'model/geografia.php",
			      timeout: 30000,
			      cache:false,
			      data: {';
	$vars = "data:data,";
	$vars .= "action:action,";
	$vars .= "csrfToken:'" . $csrfToken . "',";
	$vars .= "csrfTokenID:'" . $csrfTokenID . "'";
	echo $vars;
	echo '},
			      success: function(msg){
						var obj = msg;
						
						apply_to.find("option").remove().end();
						apply_to.append("<option value=\"\">Seleziona</option>");
						for(var i=0; i<obj.results.length;i++)
							apply_to.append("<option value=\""+obj.results[i].id+"\">"+obj.results[i].name+"</option>");
						
			      },
				  complete:function(){
				  $("#ajax_loader").hide();
				  }
			});
		}
			
		</script>';
}
// END AJAX FUNCTIONS
function outputPDFFromImage($image, $output)
{
	// Include the main TCPDF library (search for installation path).
	require_once('helpers/tcpdf/tcpdf.php');

	// create new PDF document
	$pdf = new TCPDF("P", "mm", "A4", true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator("Mediamente");
	$pdf->SetAuthor('Mediamente');
	$pdf->SetTitle('Documenti');

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	// -------------------------------------------------------------------

	// add a page
	$pdf->AddPage();

	$pageDimensions = $pdf->getPageDimensions();
	$yOffset = ceil($pdf->GetY());
	$beginFooter = floor($pageDimensions['hk'] - 15);
	$maxImgHeight = floor($beginFooter - $yOffset);
	$maxImgWidth = ceil($pdf->GetX());

	$html = '<img src="' . $image . '" style="max-width:' . $maxImgWidth . '; max-height:' . $maxImgHeight . '" />';

	// output the HTML content
	$pdf->writeHTML($html, true, false, true, false, '');

	// -------------------------------------------------------------------

	// Close and output PDF document
	$pdf->Output($output, 'F');
}

function checkUploadFileType($file, $allowedTypes = null)
{
	if (!$allowedTypes)
		$allowedTypes = array(
			"application/pdf",
			"image/jpeg"
		);

	$finfo = new finfo();
	$fileMimeType = $finfo->file($file, FILEINFO_MIME_TYPE);

	return in_array($fileMimeType, $allowedTypes);
}

// DATA E ORA
function dataitaliana($data)
{
	if (!$data)
		return "N/D";
	return strftime("%d/%m/%Y", strtotime($data));
}
function giornoedata($data)
{

	if (strftime("%d%m%Y", strtotime($data)) == date("dmY", time()))
		return "oggi";

	if (strftime("%d%m%Y", strtotime($data)) == date("dmY", strtotime(date("Y-m-d") . "+ 1 day")))
		return "domani";

	if (strftime("%d%m%Y", strtotime($data)) == date("dmY", strtotime(date("Y-m-d") . "- 1 day")))
		return "ieri";

	return  strftime("%A %d/%m/%Y", strtotime($data));
}
function giorno($data)
{

	if (strftime("%d%m%Y", strtotime($data)) == date("dmY", time()))
		return "oggi";

	if (strftime("%d%m%Y", strtotime($data)) == date("dmY", strtotime(date("Y-m-d") . "+ 1 day")))
		return "domani";

	if (strftime("%d%m%Y", strtotime($data)) == date("dmY", strtotime(date("Y-m-d") . "- 1 day")))
		return "ieri";

	return  strftime("%A", strtotime($data));
}
function giornodataeora($data, $ore = "")
{

	if (strftime("%d%m%Y", strtotime($data)) == date("dmY", time()))
		return "oggi " . $ore .  strftime("%H:%M", strtotime($data));

	if (strftime("%d%m%Y", strtotime($data)) == date("dmY", strtotime(date("Y-m-d") . "+ 1 day")))
		return "domani " . $ore .  strftime("%H:%M", strtotime($data));

	if (strftime("%d%m%Y", strtotime($data)) == date("dmY", strtotime(date("Y-m-d") . "- 1 day")))
		return "ieri " . $ore .  strftime("%H:%M", strtotime($data));

	return  strftime("%A %d/%m/%Y %H:%M", strtotime($data));
}
function dataeora($data)
{
	return utf8_encode(strftime("%d/%m/%Y %H:%M", strtotime($data)));
}
function meseAnno($data)
{
	return utf8_encode(strftime("%B %Y", strtotime($data)));
}
function sessoDaCF($cf)
{
	if (isset($cf[9]) && isset($cf[10])) {
		if ((int) ($cf[9] . $cf[10]) > 31)
			return "f";
		return "m";
	}
	return null;
}

function percentuale($val1, $val2)
{
	if ($val2 == 0)
		return 0;
	return round($val1 / $val2 * 100, 0);
}

function percentualePeriodo($data)
{
	$giorno = date("d", strtotime($data));
	$fine = date("t", strtotime($data));
	return floor($giorno / $fine * 100);
}

function progressBar($perc, $min_red = 0, $max_red = 79, $min_yell = 80, $max_yell = 99, $min_green = 100, $max_green = 100)
{
	$perc = floor($perc);
	$perc_real = $perc > 100 ? 100 : $perc;

	if ($perc_real >= $min_red && $perc_real <= $max_red)
		$class = "progress-bar-danger";
	elseif ($perc_real >= $min_yell && $perc_real <= $max_yell)
		$class = "progress-bar-warning";
	elseif ($perc_real >= $min_green && $perc_real <= $max_green)
		$class = "progress-bar-success";
	else
		$class = "";

	return '<div class="progress">
	  <div class="progress-bar ' . $class . '" role="progressbar" aria-valuenow="' . $perc_real . '" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: ' . $perc_real . '%;">
	    ' . $perc . '%
	  </div>
	</div>';
}


function controlloCodiceFiscale($cf)
{

	if ($cf == '')
		return false;

	if (strlen($cf) != 16)
		return false;

	$cf = strtoupper($cf);
	if (!preg_match("/[A-Z0-9]+$/", $cf))
		return false;

	$s = 0;

	for ($i = 1; $i <= 13; $i += 2) {
		$c = $cf[$i];
		if ('0' <= $c and $c <= '9')
			$s += ord($c) - ord('0');
		else
			$s += ord($c) - ord('A');
	}

	for ($i = 0; $i <= 14; $i += 2) {
		$c = $cf[$i];
		switch ($c) {
			case '0':
				$s += 1;
				break;
			case '1':
				$s += 0;
				break;
			case '2':
				$s += 5;
				break;
			case '3':
				$s += 7;
				break;
			case '4':
				$s += 9;
				break;
			case '5':
				$s += 13;
				break;
			case '6':
				$s += 15;
				break;
			case '7':
				$s += 17;
				break;
			case '8':
				$s += 19;
				break;
			case '9':
				$s += 21;
				break;
			case 'A':
				$s += 1;
				break;
			case 'B':
				$s += 0;
				break;
			case 'C':
				$s += 5;
				break;
			case 'D':
				$s += 7;
				break;
			case 'E':
				$s += 9;
				break;
			case 'F':
				$s += 13;
				break;
			case 'G':
				$s += 15;
				break;
			case 'H':
				$s += 17;
				break;
			case 'I':
				$s += 19;
				break;
			case 'J':
				$s += 21;
				break;
			case 'K':
				$s += 2;
				break;
			case 'L':
				$s += 4;
				break;
			case 'M':
				$s += 18;
				break;
			case 'N':
				$s += 20;
				break;
			case 'O':
				$s += 11;
				break;
			case 'P':
				$s += 3;
				break;
			case 'Q':
				$s += 6;
				break;
			case 'R':
				$s += 8;
				break;
			case 'S':
				$s += 12;
				break;
			case 'T':
				$s += 14;
				break;
			case 'U':
				$s += 16;
				break;
			case 'V':
				$s += 10;
				break;
			case 'W':
				$s += 22;
				break;
			case 'X':
				$s += 25;
				break;
			case 'Y':
				$s += 24;
				break;
			case 'Z':
				$s += 23;
				break;
		}
	}

	if (chr($s % 26 + ord('A')) != $cf[15])
		return false;

	return true;
}

function getBrowser($u_agent = null)
{
	if (!$u_agent)
		return null;

	$bname = 'Unknown';
	$platform = 'Unknown';
	$version = "";

	//First get the platform?
	if (preg_match('/linux/i', $u_agent)) {
		$platform = 'linux';
	} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		$platform = 'mac';
	} elseif (preg_match('/windows|win32/i', $u_agent)) {
		$platform = 'windows';
	}

	// Next get the name of the useragent yes seperately and for good reason
	if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
		$bname = 'Internet Explorer';
		$ub = "MSIE";
	} elseif (preg_match('/Firefox/i', $u_agent)) {
		$bname = 'Mozilla Firefox';
		$ub = "Firefox";
	} elseif (preg_match('/Chrome/i', $u_agent)) {
		$bname = 'Google Chrome';
		$ub = "Chrome";
	} elseif (preg_match('/Safari/i', $u_agent)) {
		$bname = 'Apple Safari';
		$ub = "Safari";
	} elseif (preg_match('/Opera/i', $u_agent)) {
		$bname = 'Opera';
		$ub = "Opera";
	} elseif (preg_match('/Netscape/i', $u_agent)) {
		$bname = 'Netscape';
		$ub = "Netscape";
	}

	// finally get the correct version number
	$known = array('Version', $ub, 'other');
	$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if (!preg_match_all($pattern, $u_agent, $matches)) {
		// we have no matching number just continue
	}

	// see how many we have
	$i = count($matches['browser']);
	if ($i != 1) {
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
			$version = $matches['version'][0];
		} else {
			$version = $matches['version'][1];
		}
	} else {
		$version = $matches['version'][0];
	}

	// check if we have a number
	if ($version == null || $version == "") {
		$version = "?";
	}

	return array(
		'userAgent' => $u_agent,
		'name'      => $bname,
		'version'   => $version,
		'platform'  => $platform,
		'pattern'    => $pattern
	);
}


function grafico($dati, $id, $tipo, $options = "")
{


	$labels = "";
	foreach ($dati as $dato) :
		$labels .= '"' . $dato->nome . '",';
	endforeach;
	$labels = rtrim($labels, ",");


	$data = "";
	foreach ($dati as $dato) :
		$data .= $dato->numero . ',';
	endforeach;
	$data = rtrim($data, ",");

	$bgcolor = "";
	foreach ($dati as $dato) :
		$bgcolor .= "'" . rand_color() . "',";
	endforeach;
	$bgcolor = rtrim($bgcolor, ",");


	return '
	var ' . $id . ' = new Chart(document.getElementById("' . $id . '"), {
		type: \'' . $tipo . '\',
		data: {
			labels: [
				' . $labels . '
			
			],
			datasets: [{
				data: [
					' . $data . '
				],
				
				backgroundColor: [
                ' . $bgcolor . '
				],
			}]
		}
		' . ($options != '' ? ',' . $options : '') . '
	});';
}




function rand_color()
{
	return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}




function escapeJsonString($value)
{ # list from www.json.org: (\b backspace, \f formfeed)
	$escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
	$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
	$result = str_replace($escapers, $replacements, $value);
	return $result;
}

function festivita($data)
{
	require_once ROOT_PATH . 'classes/calendario.class.php';
	$calendario = new Calendario();
	$festivita = $calendario->giorniFestivi(date("Y", strtotime($data)));
	if ($festivita[$data])
		return $festivita[$data];
	return false;
}

function dateSovrapposte($data, $start, $end)
{
	if (strtotime($start) <= strtotime($data) && strtotime($data) <= strtotime($end)) //compreso
		return true;
	return false;
}