/**
 * Functions needed to dynamically update specific components.
 *
 * @author David Henry Francis Wicker.
 * Contact davidwickerhf@gmail.com for information.
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License.
 * @requires jquery.
 * @see /views/macchine/components Containes the
 *  components the functions in this file are related to.
 */

/**
 * Updates a dropdown button's title
 * @param {string} action The name of the called function, for debug purposes
 * @param  {string} state Updated of the button
 * @param  {array} items Valye-Key Array of the dropdown list.
 * @return {null}
 */
function updateDropdownState(action, state, items) {
  var title;
  Object.keys(items).forEach(function (key) {
    if (state === key) {
      title = items[key];
    }
  });

  $("#".concat(action))
    .find(".dropdown__button")
    .find(".dropdown__title")
    .text(title);
}
