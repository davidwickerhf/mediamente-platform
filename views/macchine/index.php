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
    <div class="cheader">
        <h1 class="page-heading">
            Macchine Aziendali
        </h1>
        <div class="cheader__buttons">
            <?php
            $items = array(
                'torino' => 'Torino',
                'milano' => 'Milano',
                'empoli' => 'Empoli',
                'bologna' => 'Bologna',
                'tuttelesedi' => 'Tutte le sedi'
            );
            renderDropdown($items[$data['indexSedeState']], 'macchine', Macchine::INDEX, Macchine::INDEX_UPDATE_SEDE, $items); ?>

        </div>
    </div>


    <!-- Banner -->
    <?php renderBanner($data['indexPrenotazioniState'], $data['indexStatisticheState']) ?>

    <!-- Banner -->
    <?php renderCalendar() ?>
</div>