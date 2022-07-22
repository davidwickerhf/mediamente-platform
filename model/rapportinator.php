<?php
include "../app.config.php";
include "../commonfunctions.php";
include "../helpers/ez_sql_core.php";
include "../helpers/ez_sql_mysqli.php";
require_once "../acl.php";
require_once(INCLUDE_PATH . "helpers/phpspreadsheet/vendor/autoload.php");



use PhpOffice\PhpSpreadsheet\Reader\Xls;

$ACL = new ACL();
$db = null;
startup();

//ini_set("display_errors",1);


if ($_POST['action'] != "login" && $_POST['csrfToken'] != $_SESSION['csrfToken' . $_POST['csrfTokenID']])
    exitWithError("U02", "CSRF Attack - Sessione scaduta, aggiorna la pagina");

switch ($_POST['action']) {


    case "elabora":
        rapportinator_requireLogin();


        if (isset($_FILES['myFile'])) {

            $finfo = new finfo();
            $fileMimeType = $finfo->file($_FILES['myFile']['tmp_name'], FILEINFO_MIME_TYPE);


            if ($fileMimeType != 'application/vnd.ms-office')
                exitWithError("U36", "Tipo di file non corretto:" . $fileMimeType);

            $finfo = new finfo();
            $fileMimeType = $finfo->file($_FILES['myFile']['tmp_name'], FILEINFO_MIME_TYPE);

            if (filesize($_FILES['myFile']['tmp_name']) > 5120 * 1024)
                exitWithError("U38", "Il file supera 5MB di dimensioni.");

            $uniqueid = v_getPostVar('uniqueid', false);

            if (!$uniqueid || $uniqueid == "")
                exitWithError("U39", "No file");

            $tempDirID = ROOT_PATH . "/documenti/" . $uniqueid;
            mkdir($tempDirID);
            $inputFileName = ROOT_PATH . "documenti/" . $uniqueid . "/INTERVENTI.xls";

            move_uploaded_file($_FILES['myFile']['tmp_name'], $inputFileName);
        } else
            exitWithError("U40", "No file");


        $reader = new Xls();
        $spreadsheet = $reader->load($inputFileName);

        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $colonne = array(
            "consulente_codice" => "A",
            "consulente_nome" => "B",
            "cliente_codice" => "C",
            "cliente_ragsoc" => "D",
            "attivita_codice" => "E",
            "attivita_descrizione" => "F",
            "commessa_anno" => "L",
            "commessa_numero" => "M",
            "commessa_descrizione" => "N",
            "sottocommessa_numero" => "O",
            "sottocommessa_descrizione" => "P",
            "data" => "Q",
            "ore" => "S",
            "commenti" => "AM",
        );



        for ($i = 4; $i < count($sheetData); $i++) {
            $rapportino = null;
            $rapportino_key = $sheetData[$i][$colonne['consulente_codice']] . "-" . $sheetData[$i][$colonne['commessa_anno']] . "-" . $sheetData[$i][$colonne['commessa_numero']] . "-" . $sheetData[$i][$colonne['sottocommessa_numero']];

            if (round($sheetData[$i][$colonne['cliente_codice']]) == 44179)
                $rapportino_key = $sheetData[$i][$colonne['consulente_codice']] . "-" . $sheetData[$i][$colonne['commessa_anno']] . "-" . $sheetData[$i][$colonne['commessa_numero']] . "-" . $sheetData[$i][$colonne['sottocommessa_numero']] . "-" . $rapportino_key = $sheetData[$i][$colonne['consulente_codice']] . "-" . $sheetData[$i][$colonne['commessa_anno']] . "-" . $sheetData[$i][$colonne['commessa_numero']] . "-" . str_replace("/", "", $sheetData[$i][$colonne['data']]);


            if (!is_array($datiRapportini[$sheetData[$i][$colonne['cliente_codice']]])) // inizializzazione Array
                $datiRapportini[$sheetData[$i][$colonne['cliente_codice']]] = array();

            if (!is_array($datiRapportini[$sheetData[$i][$colonne['cliente_codice']]][$rapportino_key])) // inizializzazione Array
                $datiRapportini[$sheetData[$i][$colonne['cliente_codice']]][$rapportino_key] = array();

            foreach ($colonne as $key => $val) {
                $rapportino[$key] = xmlEntities(htmlentities($sheetData[$i][$colonne[$key]]));
            }

            array_push($datiRapportini[$sheetData[$i][$colonne['cliente_codice']]][$rapportino_key], $rapportino);
        }



        require_once(INCLUDE_PATH . "helpers/phpword/vendor/autoload.php");




        foreach ($datiRapportini as $rapportinoCliente) {
            foreach ($rapportinoCliente as $rapportino_key => $rapportino) {
                $clienteRapportinoDir = $tempDirID . "/" . $rapportino[0]['cliente_codice'] . "-" . preg_replace("/[^A-Za-z0-9]/", '',  $rapportino[0]['cliente_ragsoc']);

                mkdir($clienteRapportinoDir);

                $IDRapportino = $rapportino[0]['cliente_codice'] . "_" . preg_replace("/[^A-Za-z0-9]/", '',  $rapportino[0]['cliente_ragsoc']) . "_" . $rapportino_key . "_" . date("Ymd");

                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(INCLUDE_PATH . 'templates/template_rapportino.docx');

                $templateProcessor->cloneRow('rigadata', count($rapportino));

                $totaleore = 0;

                $mindata = 999999999999;
                $maxdata = 0;

                for ($i = 0; $i < count($rapportino); $i++) {
                    if (unxtst($rapportino[$i]['data']) > 0 && unxtst($rapportino[$i]['data']) < $mindata)
                        $mindata = unxtst($rapportino[$i]['data']);
                    if (unxtst($rapportino[$i]['data']) > $maxdata)
                        $maxdata = unxtst($rapportino[$i]['data']);

                    $templateProcessor->setValue('rigadata#' . ($i + 1), $rapportino[$i]['data']);
                    $templateProcessor->setValue('rigaore#' . ($i + 1), $rapportino[$i]['ore']);
                    $templateProcessor->setValue('rigaoggetto#' . ($i + 1), $rapportino[$i]['commenti']);

                    $totaleore += str_replace(",", ".",  $rapportino[$i]['ore']);
                }

                if ($mindata == $maxdata)
                    $periodoIntervento = date("d/m/Y", $mindata);
                else
                    $periodoIntervento = "dal " . date("d/m/Y", $mindata) . " al " . date("d/m/Y", $maxdata);

                if (
                    $mindata == 999999999999
                    && $maxdata == 0
                )
                    $periodoIntervento = "N/D";
                // Variables on different parts of document
                $templateProcessor->setValue('periodoIntervento', $periodoIntervento);
                $templateProcessor->setValue('data', date("d/m/Y", $maxdata));
                $templateProcessor->setValue('IDRapportino', $IDRapportino);

                $templateProcessor->setValue('totaleore', $totaleore);

                foreach ($colonne as $key => $val) {
                    $templateProcessor->setValue($key, $rapportino[0][$key]);
                }

                $templateProcessor->saveAs($clienteRapportinoDir . "/" . $IDRapportino . '.docx');
            }
        }

        unlink($inputFileName);


        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open(ROOT_PATH . 'documenti/' . getMyUsername() . '-' . $uniqueid . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($tempDirID),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tempDirID) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();

        system('rm -rf -- ' . escapeshellarg($tempDirID), $retval);

        sleep(2);

        break;


    default:
        rapportinator_requireLogin();
}
function rapportinator_requireLogin($action = "index")
{
    global $ACL;
    if (!$ACL->hasAccess("rapportinator", $action))
        exitWithError("U01", "Utente non autenticato");
}

function xmlEntities($str)
{
    $xml = array('&#34;', '&#38;', '&#38;', '&#60;', '&#62;', '&#160;', '&#161;', '&#162;', '&#163;', '&#164;', '&#165;', '&#166;', '&#167;', '&#168;', '&#169;', '&#170;', '&#171;', '&#172;', '&#173;', '&#174;', '&#175;', '&#176;', '&#177;', '&#178;', '&#179;', '&#180;', '&#181;', '&#182;', '&#183;', '&#184;', '&#185;', '&#186;', '&#187;', '&#188;', '&#189;', '&#190;', '&#191;', '&#192;', '&#193;', '&#194;', '&#195;', '&#196;', '&#197;', '&#198;', '&#199;', '&#200;', '&#201;', '&#202;', '&#203;', '&#204;', '&#205;', '&#206;', '&#207;', '&#208;', '&#209;', '&#210;', '&#211;', '&#212;', '&#213;', '&#214;', '&#215;', '&#216;', '&#217;', '&#218;', '&#219;', '&#220;', '&#221;', '&#222;', '&#223;', '&#224;', '&#225;', '&#226;', '&#227;', '&#228;', '&#229;', '&#230;', '&#231;', '&#232;', '&#233;', '&#234;', '&#235;', '&#236;', '&#237;', '&#238;', '&#239;', '&#240;', '&#241;', '&#242;', '&#243;', '&#244;', '&#245;', '&#246;', '&#247;', '&#248;', '&#249;', '&#250;', '&#251;', '&#252;', '&#253;', '&#254;', '&#255;');
    $html = array('&quot;', '&amp;', '&amp;', '&lt;', '&gt;', '&nbsp;', '&iexcl;', '&cent;', '&pound;', '&curren;', '&yen;', '&brvbar;', '&sect;', '&uml;', '&copy;', '&ordf;', '&laquo;', '&not;', '&shy;', '&reg;', '&macr;', '&deg;', '&plusmn;', '&sup2;', '&sup3;', '&acute;', '&micro;', '&para;', '&middot;', '&cedil;', '&sup1;', '&ordm;', '&raquo;', '&frac14;', '&frac12;', '&frac34;', '&iquest;', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;', '&Auml;', '&Aring;', '&AElig;', '&Ccedil;', '&Egrave;', '&Eacute;', '&Ecirc;', '&Euml;', '&Igrave;', '&Iacute;', '&Icirc;', '&Iuml;', '&ETH;', '&Ntilde;', '&Ograve;', '&Oacute;', '&Ocirc;', '&Otilde;', '&Ouml;', '&times;', '&Oslash;', '&Ugrave;', '&Uacute;', '&Ucirc;', '&Uuml;', '&Yacute;', '&THORN;', '&szlig;', '&agrave;', '&aacute;', '&acirc;', '&atilde;', '&auml;', '&aring;', '&aelig;', '&ccedil;', '&egrave;', '&eacute;', '&ecirc;', '&euml;', '&igrave;', '&iacute;', '&icirc;', '&iuml;', '&eth;', '&ntilde;', '&ograve;', '&oacute;', '&ocirc;', '&otilde;', '&ouml;', '&divide;', '&oslash;', '&ugrave;', '&uacute;', '&ucirc;', '&uuml;', '&yacute;', '&thorn;', '&yuml;');
    $str = str_replace($html, $xml, $str);
    $str = str_ireplace($html, $xml, $str);
    return $str;
}

function unxtst($data)
{
    $date = DateTime::createFromFormat('d/m/y', $data);
    if (!$date)
        return 0;
    return $date->format("U");
}
