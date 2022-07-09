<?php


function login()
{
    // GENERO VIEW
    $var_in_view['pageTitle'] = "Login";
    $view = ROOT_PATH . "views/utenti/login.php"; // includo la view
    $layout = ROOT_PATH . "views/inc/login.php"; // includo il layout

    $var_in_view['server'] = "(localhost)"; //".$_SERVER['SERVER_ADDR']."

    generate_view($view, $layout, $var_in_view);
}


function logout()
{
    doUserLogout();
}


function associaTelegram($params)
{
    requireLogin(); // Richiedo login


    global $db; // uso il database
    // Inizio corpo della funzione //////

    $chatIDraw =  addslashes(filter_var(htmlentities($params[0]), FILTER_SANITIZE_STRING));
    $token = addslashes(filter_var(htmlentities($params[1]), FILTER_SANITIZE_STRING));
    $unixTimestamp = addslashes(filter_var(htmlentities($params[2]), FILTER_SANITIZE_NUMBER_INT));

    $expectedToken = hash("sha256", SECURITY_SALT . $chatIDraw . $unixTimestamp);


    if ((time() - $unixTimestamp) > 3600 * 24 || $token != $expectedToken) {
        //Token non trovato o scaduto
        setFlash("<div class='alert alert-warning'>Impossibile identificare il token selezionato. Potrebbe essere scaduto o inesistente</div>");
    } else {
        //Token trovato        
        //Lo inserisco nella tabella telegram_chats
        $db->query("INSERT INTO telegram_chats(chat_id, type, name)
                    VALUES('" . $chatIDraw . "','private', '" . getMyUsername() . "')
                    ON DUPLICATE KEY UPDATE name='" . getMyUsername() . "'");


        setFlash("<div class='alert alert-success'><strong>OK!</strong> Accesso eseguito correttamente. Puoi iniziare a usare la chat di Telegram</div>");

        $messaggio = "Accesso eseguito\nOra puoi usare il bot AMS!";
        sendTelegramMessage($chatIDraw, $messaggio, $edit_message_id = null);
    }

    if (count($db->captured_errors)) {
        exitWithError("T01", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
    } else {
        //Redirect alla hompage
        header("Location:" . SERV_URL);
        die();
    }
}