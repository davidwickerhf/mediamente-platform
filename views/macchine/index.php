<?php

/**
 * Pagina Prenotazione Macchine Aziendali 
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (github/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

// Load Components Libraries
foreach (glob("views/macchine/components/*.php") as $filename) {
    require_once $filename;
}

// Load Scripts
foreach (glob('views/macchine/scripts/*.php') as $filename) {
    require_once $filename;
}

?>
<!-- Page Content: Macchine / Calendario  -->
<div class="macchine-calendario">

    <!-- Page Heading  -->
    <h1 class="page-heading">
        Macchine Aziendali
    </h1>

    <!-- Banner -->
    <?php renderBanner() ?>

    <!-- Banner -->
    <?php renderCalendar() ?>
</div>