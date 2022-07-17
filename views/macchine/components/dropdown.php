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
 * @param callback name of the javascript callback function that will
 *  be called in order to update the UI
 */
function renderDropdown(string $title, string $controller, string $method, string $action, array $items)
{ ?>
<div class="dropdown" id="<?= $action ?>">
    <div tabindex="0" class="dropdown__button">
        <div class="dropdown__title">
            <?= $title  ?>
        </div>
        <div class="dropdown__icon-wrapper">
            <i class="fa fa-solid fa-angle-down dropdown__icon"></i>
        </div>
    </div>
    <div class="dropdown__content">
        <?php
            foreach ($items as $state => $name) {
            ?>
        <a class="dropdown__item">
            <?= $name  ?>
        </a><?php
                } ?>
    </div>
</div>
<script>
$('#<?= $action ?>').find('.dropdown__item').click(function() {
    var state = $(this).text().toLowerCase().replace(/\s+/g, '');

    // Get csrf token
    token = <?php
                    $token = generateDynamicComponentToken($controller, $action);
                    echo json_encode($token);
                    ?>;
    data = {};
    data.items = <?php echo json_encode($items) ?>;
    // launch ajax request
    updateComponent("<?= SERV_URL . $controller . '/' . $method ?>", "<?= $action ?>", state, token, data);
    $(this).blur();
});
</script>
<?php
} ?>