<?php



$EMAIL_RECUPERO_PASSWORD=Array(
		"oggetto"=>"Recupero username e password",
		"testo"=>"Hai richiesto il recupero dello username e della password.<br/><br/>
		<br/>Per accedere al tuo account <a href='%SERV_URL%'>clicca qui</a> o incolla il seguente collegamento nella barra degli indirizzi del tuo browser:<br/>
		%SERV_URL%<br/><br/>
		Per accedere utilizza le seguenti credenziali:<br/><br/>
		<b>Username</b>: %username%<br/>
		<b>Password</b>: %password%<br/><br/>"
);



$EMAIL_NUOVO_ALLINEAMENTO=Array(
		"oggetto"=>"Allineamento DB %nome_db% pianificato %dataora%",
		"testo"=>"La presente per avvisare che il database %nome_db% sar&agrave; allineato %dataora%.<br/>
				Il termine dell'allineamento sar&agrave; comunicato via email.
"
);

$EMAIL_ALLINEAMENTO_ANNULLATO=Array(
		"oggetto"=>"Allineamento DB %nome_db% pianificato %dataora% annullato",
		"testo"=>"La presente per avvisare che l'allineamento del database %nome_db% pianificato %dataora% &egrave; stato annullato.<br/>
"
);


$EMAIL_ALLINEAMENTO_RIUSCITO=Array(
		"oggetto"=>"Fine allineamento DB %nome_db% pianificato %dataora%",
		"testo"=>"Allineamento terminato con successo.
"
);

$EMAIL_ALLINEAMENTO_TERMINATO=Array(
                "oggetto"=>"Termine allineamento DB %nome_db% pianificato %dataora%",
                "testo"=>"Allineamento terminato, &egrave; possibile accedere al DB. Il controllo dei LOG sar&agrave; effettuato non appena possibile.
"
);

$EMAIL_ALLINEAMENTO_FALLITO=Array(
		"oggetto"=>"ALLINEAMENTO FALLITO DB %nome_db% pianificato %dataora%",
		"testo"=>"L'allineamento in oggetto &egrave; fallito. Sarete informati via email non appena il database sar&agrave; allineato con successo.
"
);

$EMAIL_ALLINEAMENTO_FALLITO_SUPPORT=Array(
		"oggetto"=>"AIMAG ALLINEAMENTO FALLITO DB %nome_db% pianificato %dataora%",
		"testo"=>"L'allineamento in oggetto &egrave; fallito. Collegarsi per verificare e una volta risolto il problema rimuovere i blocchi presenti nella relativa sezione.
"
);

$EMAIL_NUOVA_RICHIESTA_ALLINEAMENTO=Array(
		"oggetto"=>"Nuova richiesta di allineamento per %nome_db% %dataora%",
		"testo"=>"Visita %SERV_URL%allineamenti per approvare o negare la richiesta.
"
);

$EMAIL_NUOVA_RICHIESTA_BLOCCO=Array(
		"oggetto"=>"Nuovo blocco per %nome_db%",
		"testo"=>"E' stato inserito un nuovo blocco.
"
);
