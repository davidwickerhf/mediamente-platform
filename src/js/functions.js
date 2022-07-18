/**
 * Utility functions for dynamic components
 *
 * @author David Henry Francis Wicker.
 * Contact davidwickerhf@gmail.com for information.
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License.
 * @requires jquery.
 */

/**
 * Utility function to start a ajax request to update a dynamic component.
 * @param {string} url URL to which the ajax request will be sent to.
 * @param  {string} action Action called.
 * @param {string} state State of the element.
 * @param  {object} data Valye-Key Array params sent to the server.
 * @param {object} token Value-key array with the csrf token and csrf token ID.
 * @return {null}
 */
function updateComponent(url, action, state, token, data = {}) {
  $.ajax(url, {
    type: "POST",
    data: {
      csrfToken: token.csrfToken,
      csrfTokenID: token.csrfTokenID,
      action: action,
      state: state,
      data: data,
    },
    success: function (data, status, jqxhr) {
      // Call js function in javascript file for UI update
      console.log(data);
      var data = JSON.parse(data);

      console.log("SEVER RESPONDED TO ACTION: " + action);
      console.log(data["state"]);
      window[action](action, data.state, data.data);
      return;
    },
    error: function (data, status, jqxhr) {
      alert("Error in ajax request");
      return;
    },
  });
}
