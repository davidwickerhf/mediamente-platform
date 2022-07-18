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
 * @param {string} action The name of the called function, for debug purposes
 * @param  {string} state Selected item of the dropdown list.
 * @param  {array} data Valye-Key Array of the stats(s) to show in the UI.
 *  Must contain `items`.
 * @return {null}
 */
function indexUpdateSede(action, state, data) {
  console.log("UI Function: ", action);
  // Update Button Title
  updateDropdownState(action, state, data.items);
}

/**
 * Function called when updating dropdown button selection of the
 *  Prenotazioni section of the banner in macchine/index.php
 * @param {string} action The name of the called function, for debug purposes
 * @param  {string} state Can either be `prossime` or `incorso`;
 *  If the value is `prossime`, the UI will show future reservations
 *  If the value is `incorso`, the UI will show the current open reservation.
 * @param  {array} data Valye-Key Array of the reservation(s) to show in the UI.
 *  Must contain `items`.
 * @return {null}
 */
function indexUpdatePrenotazioni(action, state, data) {
  console.log("UI Function: ", action);
  // Update Button Title
  updateDropdownState(action, state, data.items);
  if (state == "prossime") {
  } else {
  }
}

/**
 * Function called when updating dropdown button selection of the
 *  Statistics section of the banner in macchine/index.php
 * @param {string} action The name of the called function, for debug purposes
 * @param  {string} state Can either be `mensilmente` or `annualmente`;
 *  If the value is `mensilmente`, the Graph will show data for a period of 7 years
 *  If the value is `annualmente`, the Graph will show data for a period of 7 months
 * @param  {array} data Valye-Key Array of the stats(s) to show in the UI.
 *  Must contain `items`.
 * @return {null}
 */
function indexUpdateStatistiche(action, state, data) {
  console.log("UI Function: ", action);
  // Update Button Title
  updateDropdownState(action, state, data.items);
  if (state == "prossime") {
  } else {
  }
}
