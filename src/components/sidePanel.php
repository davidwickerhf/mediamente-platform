<?php

/**
 * Banner Graph Component
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

require_once ROOT_PATH . 'controller/macchine.php';

/**
 * Render Side Panel html and javascript
 * 
 * @param string state State of the Stats section of the banner.
 * @param array contents Array with the contents of the graph:
 *  `contents: {columns: {{value: int, name: string}}, rows: {int}}`
 *  Rows values must be in descending order!
 */
function renderSidePanel(): string

{
    ob_start();
?>

<div class="cpanel">
    <div class="cpanel__header">
        <div class="cpanel__heading">Modifica</div>
        <div class="cpanel__close">
            <?php echo renderButton('Close', 'x', 'trasparent', null, null, 'closePanel', null, true, true) ?>
        </div>

    </div>
    <div class="cpanel__content">
        <div id="sidePanel"></div>
    </div>
</div>

<?php return ob_get_clean();
} ?>