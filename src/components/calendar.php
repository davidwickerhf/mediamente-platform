<?php

/**
 * Calendar Component
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (github/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */


/**
 * Generate banner html
 */
function renderCalendar()
{
    ob_start(); ?>
<div class="calendar">

</div>
<?php
    return ob_get_clean();
} ?>