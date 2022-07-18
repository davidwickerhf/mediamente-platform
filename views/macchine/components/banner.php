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
 * Render Banner html and javascript
 * 
 * @param string prenotazioniState state of the prenotazioni dropdown.
 * @param string statisticheState state of the statistiche dropdown.
 */
function renderBanner($prenotazioniState, $statisticheState)

{
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
                    $items = array('prossime' => 'Prossime', 'incorso' => 'In corso');
                    renderDropdown($items[$prenotazioniState], 'macchine', Macchine::INDEX, Macchine::INDEX_UPDATE_PRENOTAZIONI, $items);
                    ?>
            </div>
        </div>


        <!-- Section Statistiche  -->
        <div class="banner__section">
            <div class="section">
                <div class="section__head">
                    <h2 class="section-heading">Prenotazioni</h2>

                    <?php
                        $items = array('mensilmente' => 'Mensilmente', 'annualmente' => 'Annualmente');
                        renderDropdown($items[$statisticheState], 'macchine', Macchine::INDEX, Macchine::INDEX_UPDATE_STATISTICHE, $items);
                        ?>
                </div>
            </div>
        </div>


        <!-- Section Disponibilita -->
        <div class="banner__section banner__section--no-growth">
            <div class="section">
                <div class="section__head section__head--manual-wide">
                    <h2 class="section-heading">Disponibilita'</h2>
                </div>


            </div>
        </div>
    </div>
</div>
<?php } ?>