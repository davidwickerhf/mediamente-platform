<?php

/**
 * Banner Component
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

require_once ROOT_PATH . 'controller/macchine.php';

/**
 * Render Spinner html and javascript
 * @param string id Corresponds to the id of the loading widget
 */
function renderSpinner(string $id): string

{
    ob_start();
?>
<div id="<?= $id ?>">
    <div class="cloader"></div>
</div>

<?php return ob_get_clean();
} ?>