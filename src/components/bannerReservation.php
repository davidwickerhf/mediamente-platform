<?php

/**
 * Banner Reservation Component
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

require_once ROOT_PATH . 'controller/macchine.php';

/**
 * Render Banner Reservation html and javascript
 * 
 * @param string prenotazioniState state of the prenotazioni dropdown.
 * @param string statisticheState state of the statistiche dropdown.
 * @param string disponibilitaState state of the statistiche dropdown.
 */
function renderBannerReservation(CPrenotazione $reservation, CMacchina $car): string

{
    ob_start();
?>
<!-- Single Reservation  -->
<div class="sreservation">

    <div class="sreservation__top">
        <div class="sreservation__date">
            <div>
                <?php
                    $df = new IntlDateFormatter('it_IT', IntlDateFormatter::SHORT, IntlDateFormatter::NONE);
                    $df->setPattern('d MMM');
                    $date = ucwords(strtolower($df->format($reservation->from_date)));
                    echo $date;
                    ?>
            </div>
            <div class="sreservation__line"></div>
            <span class="sreservation__duration">
                <i class="bx bx-time"></i>
                <?php
                    $from_date = $reservation->from_date->getTimestamp();
                    $to_date = $reservation->to_date->getTimestamp();
                    $diff = $to_date - $from_date;
                    $diff = round($diff / 86400) + 1;

                    if ($diff == 1) {
                        echo $diff . ' giornata';
                    } else {
                        echo $diff . ' giornate';
                    }
                    ?>
            </span>
            <div class="sreservation__line"></div>
            <div>
                <?php
                    $df = new IntlDateFormatter('it_IT', IntlDateFormatter::SHORT, IntlDateFormatter::NONE);
                    $df->setPattern('d MMM');
                    $date = ucwords(strtolower($df->format($reservation->to_date)));
                    echo $date;
                    ?>
            </div>
        </div>
        <div class="sreservation__details">
            <div class="sreservation__ditem">
                <i class="bx bx-map"></i>
                <span><?php echo 'Sede di ' . Macchine::SEDE_STATES[$car->sede] ?></span>
            </div>
            <div class="sreservation__ditem">
                <i class="bx bxs-business"></i>
                <span><?php echo 'Motivazione ' . ucwords($reservation->motivazione) ?></span>
            </div>
        </div>
    </div>

    <div class="sreservation__bottom">
        <div class="sreservation__carinfo">
            <div class="sreservation__car">
                <?php echo $car->modello; ?>
            </div>
            <div class="sreservation__brand">
                <?php echo $car->marca; ?>
            </div>
        </div>

        <div class="sreservation__edit"></div>
    </div>
    <div class="clip clip--left">
        <div class="clip__circle"></div>
    </div>
    <div class="sreservation__dotted"></div>
    <div class="clip clip--right">
        <div class="clip__circle"></div>
    </div>

</div>

<?php return ob_get_clean();
} ?>