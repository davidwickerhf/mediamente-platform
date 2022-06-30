<?php

define("VERSION", "0.1.4");
define("VERSION_DATE", "30/11/2020");

//PRODUCTION
define("SERV_URL", "/");
define("EMAIL_SERV_URL", "http://localhost/");
define("URL_BASE_PATH", "/");
define("ROOT_PATH", "/users/davidwicker/Projects/mediamente/MediamentePlatform/mediamente-platform/");
define("INCLUDE_PATH", "/users/davidwicker/Projects/mediamente/MediamentePlatform/mediamente-platform/");
define("CLASS_PATH", "/users/davidwicker/Projects/mediamente/MediamentePlatform/mediamente-platform/classes/");


define("DB_USERNAME", "apps");
define("DB_PASSWORD", "nJe47P7RyirnXKBB");
define("DB_NAME", "apps");
define("DB_HOST", "127.0.0.1");

define('ORACLE_DBHOST', '192.168.2.38');
define('ORACLE_DBNAME', 'APE');
define('ORACLE_DBUSER', 'MMBI_TICKET');
define('ORACLE_DBPASS', 'mmbi');

define("LDAP_SERVER", "192.168.2.4:389");
define("LDAP_BASE", "dc=MMONLINE,dc=LOCAL");
define("LDAP_DOMAIN", "mmonline");
define("LDAP_USER", "ldapuser");
define("LDAP_PASSWORD", "ApeRegina01");


define('SUPPORT_EMAIL', 'support_oracle@mediamenteconsulting.it');
define('SUPPORT_PASSWORD', '8^A2#795c0X');


//Non necessario modificare quanto sotto

putenv('LANGUAGE=it_IT');
setlocale(LC_ALL, 'it_IT.UTF-8', 'it');


define("DEVELOPMENT_MODE", true);
define("DKIM_PRIVATE_KEY_PATH", "");
define("SECURITY_SALT", 'wdkSzNog5NERHt1AfeoDR1G8Gp8PGXYNCQWCAEADiyoZTkUpGQEO5YvqoWFbrHa1pqzN6lp8APRTTxAJtnTrcI5FF6vjYBwIQBW9');
define("RECAPTCHA_SITE_KEY", '6LcGdHwUAAAAAHyZdSda4zKkGsZQhH6c7gdwPRWg');
define("RECAPTCHA_SECRET_KEY", '6LcGdHwUAAAAALry9e0Hi35mJC7C1zVTq96ZnU_T');

define("TELEGRAM_BOT_API_KEY", "bot549310021:AAHNfMPJoFI9JGEcZyMO8uEx4pz_H-n0Nwg");
define("TELEGRAM_BOT2_API_KEY", "bot591371241:AAF5sVG0wbfxHRivDv8MbOV-VT4lWCQJQbs");
