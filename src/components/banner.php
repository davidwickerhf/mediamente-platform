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
 * @param string disponibilitaState state of the statistiche dropdown.
 */
function renderBanner($prenotazioniState, $statisticheState, $disponibilitaState): string

{
    ob_start();
?>
    <div class="banner">
        <!-- Section Prossime Prenotazioni -->
        <div class="banner__wrapper">
            <div class="banner__section">
                <div class="section">
                    <div class="section__head">
                        <h2 class="section-heading">
                            Le tue prenotazioni
                        </h2>
                        <?php
                        echo renderDropdown(Macchine::INDEX_PRENOTAZIONI_STATES[$prenotazioniState], 'macchine', Macchine::INDEX, Macchine::INDEX_UPDATE_PRENOTAZIONI, Macchine::INDEX_PRENOTAZIONI_STATES);
                        ?>
                    </div>
                    <div class="section__content">
                        <div class="section__content-wrapper">
                            <?php echo renderSpinner('bannerPrenotazioni'); ?>
                        </div>
                    </div>
                    <div class="section__bottom">
                        <?php echo renderTextButton('Vedi tutte le prenotazioni', 'macchine', 'prenotazioni')
                        ?>
                    </div>
                </div>

            </div>


            <!-- Section Statistiche  -->
            <div class="banner__section">
                <div class="section">
                    <div class="section__head">
                        <h2 class="section-heading">Prenotazioni</h2>

                        <?php
                        echo renderDropdown(Macchine::INDEX_STATISTICHE_STATES[$statisticheState], 'macchine', Macchine::INDEX, Macchine::INDEX_UPDATE_STATISTICHE, Macchine::INDEX_STATISTICHE_STATES);
                        ?>
                    </div>
                    <div class="section__content">
                        <div class="section__content-wrapper">
                            <?php //  echo renderSpinner('bannerStatistiche'); 
                            $mockcontents = array(
                                'columns' => array(
                                    array('value' => 0, 'name' => 'Gen'),
                                    array('value' => 0, 'name' => 'Feb'),
                                    array('value' => 0, 'name' => 'Mar'),
                                    array('value' => 0, 'name' => 'Apr'),
                                    array('value' => 0, 'name' => 'Giu'),
                                    array('value' => 0, 'name' => 'Lug'),
                                    array('value' => 0, 'name' => 'Ago'),
                                ),
                                'rows' => array('25', '20', '15', '10', '5'),
                            );
                            echo renderBannerGraph($mockcontents);
                            ?>
                        </div>
                    </div>
                    <div class="section__bottom">
                        <?php echo renderTextButton('Vedi tutte le statistiche', 'macchine', 'statistiche')
                        ?>
                    </div>
                </div>
            </div>


            <!-- Section Disponibilita -->
            <div class="banner__section banner__section--no-growth">
                <div class="section">
                    <div class="section__head">
                        <h2 class="section-heading">Disponibilita'</h2>
                        <?php
                        echo renderDropdown(Macchine::INDEX_DISPONIBILITA_STATES[$disponibilitaState], 'macchine', Macchine::INDEX, Macchine::INDEX_UPDATE_DISPONIBILITA, Macchine::INDEX_DISPONIBILITA_STATES);
                        ?>
                    </div>

                    <!-- Buttons Section  -->
                    <div class="section__content ">
                        <div class="section__content-wrapper section__content-wrapper--buttons">
                            <div class="banner__button-wrapper">
                                <?php echo renderButton('Macchine disponibili', 'check', 'grey', null, null, 'macchineDisponibiliBtn', SERV_URL . 'macchine/macchine'); ?>
                            </div>
                            <div class="banner__button-wrapper">
                                <?php echo renderButton('Macchine prenotate', 'x', 'grey', null, null, 'macchinePrenotateBtn', SERV_URL . 'macchine/macchine'); ?>
                            </div>
                            <div class="banner__button-wrapper">
                                <?php echo renderButton('Prenota', 'plus', 'blue', null, null, Macchine::RESERVE, SERV_URL . 'macchine/macchine'); ?>
                            </div>
                        </div>

                    </div>

                    <div class="section__bottom">
                        <?php echo renderTextButton('Vedi tutte le macchine', 'macchine', 'macchine')
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php return ob_get_clean();
} ?>