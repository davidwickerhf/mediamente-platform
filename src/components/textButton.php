<?php

/**
 * Dropdown Component
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (github/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

require_once "./app.config.php";

/**
 * Generate TextButton html and javascript
 * 
 * @param string title set the title of the button;
 * @param ?string url Page to redirect on button click.
 */
function renderTextButton(string $title, string $controller, string $method)
{
    ob_start(); ?>
<a class="textbutton" href="<?= SERV_URL . $controller . '/' . $method ?>">
    <div class="textbutton__title">
        <?= $title ?>
    </div>
    <div class="textbutton__icon">
        <i class="bx bx-right-arrow-alt"></i>
    </div>
</a>
<?php
    return ob_get_clean();
} ?>