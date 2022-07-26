<?php

/**
 * Pagina Prenotazione Macchine Aziendali / Macchine
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (github/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

// Load Components Libraries
foreach (glob("src/components/*.php") as $filename) {
    require_once $filename;
}

?>
<!-- Page Content: Macchine / Calendario  -->
<div class="macchine-macchine">

    <!-- Page Heading  -->
    <?php echo renderHeader('Macchine Aziendali', $data, array(
        'title' => 'Prenota',
        'controller' => 'macchine',
        'method' => 'header',
        'action' => 'reserve',
    ), 'macchine'); ?>

</div>