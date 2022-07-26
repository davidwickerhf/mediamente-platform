<?php

/**
 * Pagina Prenotazione Macchine Aziendali 
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
<!-- Side Panel -->
<?php echo renderSidePanel() ?>
<!-- Page Content: Macchine / Calendario  -->
<div class="macchine">

    <!-- Page Heading  -->
    <?php echo renderHeader('Macchine Aziendali', $data, array(
        'title' => 'Prenota',
        'controller' => 'macchine',
        'method' => 'header',
        'action' => Macchine::RESERVE,
    )); ?>

    <!-- Banner -->
    <?php echo renderBanner($data['indexPrenotazioniState'], $data['indexStatisticheState'], $data['indexDisponibilitaState']) ?>

    <!-- Banner -->
    <?php echo renderCalendar() ?>
</div>
<script>
// INITIAL PAGE LOAD (indexLoadData)
$(document).ready(function() {
    // Get csrf token
    token = <?php
                $token = generateDynamicComponentToken('macchine', 'indexLoadData');
                echo json_encode($token);
                ?>;

    // Launch GET Ajax Request
    componentAjaxGet("<?= SERV_URL . 'macchine/index' ?>", "indexLoadData", token);
});
</script>