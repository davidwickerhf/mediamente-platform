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
 * Generate dropdown html and javascript
 * 
 * @param string title set the title of the button;
 * @param string controller sets the controller that the  
 *  ajax function will be called
 * @param string method sets the method of the controller
 *  that will be called through the ajax function
 * @param string action identifies the desired action within the 
 *  controller, as well as the callback function for UI update.
 * @param array items list the dropdown menu.
 *  The array is a key-value array: 
 *  {$state => $name}. The title is the title of 
 *  the menu item; the callback is the argument that will be passed
 *  to the ajax function when called
 * @param bool global If true, this dropdown affects the entire page and 
 *  the content of the entire page will be refreshed.
 */
function renderDropdown(string $title, string $controller, string $method, string $action, array $items, bool $global = false): string
{
    ob_start(); ?>
<div class="cdropdown" id="<?= $action ?>">
    <div tabindex="0" class="cdropdown__button">
        <div class="cdropdown__title">
            <?= $title  ?>
        </div>
        <div class="cdropdown__icon-wrapper">
            <i class="bx bx-chevron-down cdropdown__icon"></i>
        </div>
    </div>
    <div class="cdropdown__content">
        <?php
            foreach ($items as $state => $name) {
            ?>
        <a class="cdropdown__item">
            <?= $name  ?>
        </a><?php
                } ?>
    </div>

</div>
<script>
// Make content the same width as the button
$(document).ready(function() {
    $('#<?= $action ?>').find('.cdropdown__content').css({
        'width': ($('#<?= $action ?>').width() + 'px')
    });
});


// Click reader
$('#<?= $action ?>').find('.cdropdown__item').click(function() {
    // Get chosen component state
    var state = $(this).text().toLowerCase().replace(/\s+/g, '');
    // Get csrf token
    token = <?php
                    $token = generateDynamicComponentToken($controller, $action);
                    echo json_encode($token);
                    ?>;

    // load data
    var items = <?php echo json_encode($items) ?>;

    // Update Dropdown Component
    updateDropdownState("<?= $action ?>", state, items);
    $(this).blur(); // Remove focus (closes dropdown)

    // Launch POST Ajax Request
    componentAjaxPost("<?= SERV_URL . $controller . '/' . $method ?>", "<?= $action ?>", state, token);

    // PAGFE REFRESH
    <?php if ($global == true) : ?>
    // Get csrf token
    token = <?php
                        $token = generateDynamicComponentToken($controller, $method . 'LoadData');
                        echo json_encode($token);
                        ?>;

    // Launch GET Ajax Request
    componentAjaxGet("<?= SERV_URL . $controller . '/' . $method ?>", "<?= $method . 'LoadData' ?>", token);
    <?php endif;  ?>

});
</script>
<?php
    return ob_get_clean();
} ?>