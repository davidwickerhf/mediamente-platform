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
 * Generate button html and javascript
 * 
 * @param string title set the title of the button;
 * @param string icon Icon URL.
 * @param string color Can be either `transparent`, `blue`, `grey`.
 * @param ?string controller sets the controller that the  
 *  ajax function will be called
 * @param ?string method sets the method of the controller
 *  that will be called through the ajax function
 * @param string action identifies the desired action within the 
 *  controller, as well as the callback function for UI update.
 *  Also acts as id of the element.
 * @param ?string url Option, page to redirect on button click.

 */
function renderButton(string $title, string $icon, string $color, ?string $controller = null, ?string $method = null, string $action, ?string $url = null, bool $small = false, bool $iconRight = false)
{
    ob_start(); ?>
<div class="cbutton 
cbutton--<?= $color ?> 
cbutton--<?php echo ($small == true) ? 'small' : 'large' ?>" id="<?= $action ?>">
    <!-- Icon in the left -->
    <?php if ($iconRight == false) : ?>
    <div class="cbutton__icon">
        <i class='bx bx-<?= $icon ?>'></i>
    </div>
    <?php endif; ?>
    <!-- Button Title  -->
    <div class="cbutton__content">
        <?= $title ?>
    </div>
    <!-- Icon in the right -->
    <?php if ($iconRight == true) : ?>
    <div class="cbutton__icon">
        <i class='bx bx-<?= $icon ?>'></i>
    </div>
    <?php endif; ?>
</div>
<script>
<?php if (!is_null($url)) :  ?>
$('#<?= $action ?>').click(function(e) {
    window.location.href = "<?= $url ?>";
})
<?php else : ?>
$('#<?= $action ?>').click(function(e) {

})
<?php endif; ?>
</script>
<?php
    return ob_get_clean();
} ?>