<?php

/**
 * Banner Component
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

require_once ROOT_PATH . 'controller/macchine.php';

/**
 * Generate banner html
 */
function renderBanner()

{
    require_once ROOT_PATH . 'views/macchine/components/dropdown.php';
?>
<div class="banner">
    <!-- Section Prossime Prenotazioni -->
    <div class="banner__wrapper">
        <div class="banner__section">
            <div class="section__head">
                <h2 class="section-heading">
                    Le tue prenotazioni
                </h2>
                <?php
                    renderDropdown('Prossime', 'macchine', Macchine::INDEX, Macchine::INDEX_UPDATE_PRENOTAZIONI, array('prossime' => 'Prossime', 'incorso' => 'In corso'));
                    ?>
            </div>

        </div>


        <!-- Section Statistiche  -->
        <div class="banner__section">
            <div class="section">
                <div class="section__head">
                    <h2 class="section-heading">Prenotazioni</h2>

                    <?php
                        renderDropdown('Mensilmente', 'macchine', Macchine::INDEX, Macchine::INDEX_UPDATE_STATISTICHE, array('mensilmente' => 'Mensilmente', 'annualmente' => 'Annualmente'));
                        ?>
                </div>
            </div>
        </div>


        <!-- Section Disponibilita -->
        <div class="banner__section">
            <div class="section">
                <div class="section__head section__head--manual-wide">
                    <h2 class="section-heading">Disponibilita'</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>