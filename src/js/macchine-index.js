/// <reference path="../../dist/js/jquery-3.3.1.min.js" />
/// <reference path="elements.js" />
/**
 * Functions needed to dynamically update the UI of the page 'macchine/index.php'.
 *
 * @author David Henry Francis Wicker.
 * Contact davidwickerhf@gmail.com for information.
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License.
 * @requires jquery.
 * @see /controller/macchine.php Containes the names of the functions below.
 */

/**
 * Function called when updating dropdown button selection of the
 *  header component in macchine/index.php
 * @param  {array} contents Valye-Key Array
 * @return {null}
 */
function indexUpdateSede(contents) {
  console.log("UI Function: ", "indexUpdateSede");
}

/**
 * Function called when updating dropdown button selection of the
 *  Prenotazioni section of the banner in macchine/index.php
 * @param  {string} state Can either be `prossime` or `incorso`;
 *  If the value is `prossime`, the UI will show future reservations
 *  If the value is `incorso`, the UI will show the current open reservation.
 * @param  {array} data Valye-Key Array of the reservation(s) to show in the UI.
 *  May contain HTML content to inject
 * @return {null}
 */
function indexUpdatePrenotazioni(contents) {
  console.log("UI Function: ", "indexUpdatePrenotazione");
  // Find and replace spinner
  $("#bannerPrenotazioni").replaceWith(contents.html);
}

/**
 * Function called when updating dropdown button selection of the
 *  Statistics section of the banner in macchine/index.php
 * @param  {string} state Can either be `mensilmente` or `annualmente`;
 *  If the value is `mensilmente`, the Graph will show data for a period of 7 years
 *  If the value is `annualmente`, the Graph will show data for a period of 7 months
 * @param  {array} data Valye-Key Array of the stats(s) to show in the UI.
 * @return {null}
 */
function indexUpdateStatistiche(contents) {
  console.log("UI Function: ", "indexUpdateStatistiche");
  // Find and replace graph
  $("#bannerGraph").replaceWith(contents.html);
}

/**
 * Function called when updating dropdown button selection of the
 *  Disponibilita section of the banner in macchine/index.php
 * @param  {array} contents Valye-Key Array
 * @return {null}
 */
function indexUpdateDisponibilita(contents) {
  console.log("UI Function: ", "indexUpdateDisponibilita");

  // Inject values into buttons
  // Disponibili
  $("#macchineDisponibiliBtn")
    .find(".cbutton__content")
    .html(contents.disponibili + " Macchine disponibili");

  // Prenotate
  $("#macchinePrenotateBtn")
    .find(".cbutton__content")
    .html(contents.prenotate + " Macchine prenotate");
  return;
}

/**
 * Function called when refreshing macchine/index.php
 * @param  {array} contents Valye-Key Array
 * @return {null}
 */
function indexLoadData(contents) {
  console.log("UI Function: ", "indexLoadData");

  // UPDATE PRENOTAZIONI
  if (contents.indexUpdatePrenotazioni !== undefined) {
    var tcontents = contents.indexUpdatePrenotazioni;
    indexUpdatePrenotazioni(tcontents);
  }

  // UPDATE STATISTICHE
  if (contents.indexUpdateStatistiche !== undefined) {
    var tcontents = contents.indexUpdateStatistiche;
    indexUpdateStatistiche(tcontents);
  }

  // UPDATE DISPONIBILITA
  if (contents.indexUpdateDisponibilita !== undefined) {
    var tcontents = contents.indexUpdateDisponibilita;
    indexUpdateDisponibilita(tcontents);
  }

  // UPDATE CALENDARIO
  if (contents.indexUpdateCalendario !== undefined) {
    var tcontents = contents.indexUpdateCalendario;
  }

  return;
}
