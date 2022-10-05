<?php

/**
 * Calendar Component
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (github/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */


/**
 * Generate calendar html
 * 
 * `content: 
 *    { 'date', 'reservation' => { 'reservation', 'hasprevious', 'hasafter'}, 'wmoth' } `
 */
function renderCalendar($content, $data)
{
    ob_start(); ?>
<div class="ccalendar" id="indexCalendar">
    <div class="ccalendar__header">
        <div class="ccalendar__heading">
            <div class="ccalendar__title">Calendario Prenotazioni</div>
            <div class="ccalendar__month">
                <?php  // Format Month
                    $df = new IntlDateFormatter('it_IT', IntlDateFormatter::SHORT, IntlDateFormatter::NONE);
                    $df->setPattern('MMMM, yyyy');
                    $date = new DateTime($data['indexCalendarioMese']);
                    $name = ucwords($df->format($date));
                    echo $name; ?>
            </div>
        </div>
        <div class="ccalendar__buttons">
            <?php //echo renderDropdown() 
                ?>
            <!-- Switch month arrows -->
            <div class="ccalendar__arrows">
                <div class="ccalendar__icon" id="c-left">
                    <i class="bx bx-chevron-left"></i>
                </div>
                <div class="ccalendar__icon" id="c-right">
                    <i class="bx bx-chevron-right"></i>
                </div>
                <script>
                $('#c-right').click(function(e) {
                    // Launch POST Ajax Request
                    token = <?php
                                    $token = generateDynamicComponentToken('macchine', 'indexUpdateCalendario');
                                    echo json_encode($token);
                                    ?>;
                    componentAjaxPost("<?= SERV_URL . 'macchine/index' ?>", 'indexUpdateCalendario', 'right',
                        token);
                });

                $('#c-left').click(function(e) {
                    token = <?php
                                    $token = generateDynamicComponentToken('macchine', 'indexUpdateCalendario');
                                    echo json_encode($token);
                                    ?>;
                    componentAjaxPost("<?= SERV_URL . 'macchine/index' ?>", 'indexUpdateCalendario', 'left',
                        token);
                });
                </script>
            </div>

        </div>
    </div>

    <div class="ccalendar__scrollable">
        <div class="ccalendar__content">
            <div class="ccalendar__day">LUN</div>
            <div class="ccalendar__day">MAR</div>
            <div class="ccalendar__day">MER</div>
            <div class="ccalendar__day">GIO</div>
            <div class="ccalendar__day">VEN</div>
            <div class="ccalendar__day">SAB</div>
            <div class="ccalendar__day">DOM</div>

            <?php
                $index = 0;
                foreach (range(2, 7) as $row) {
                    foreach (range(1, 7) as $column) {
                        if (!empty($content))
                            echo renderCalendarCell($row, $column, $content[$index]['day'], $content[$index]['reservations'], $content[$index]['wmonth']);
                        else
                            echo renderCalendarCell($row, $column);
                        $index += 1;
                    }
                }
                ?>
        </div>
    </div>


</div>
<?php
    return ob_get_clean();
}



/**
 * Generate calendar cell html
 *
 * `content: {date: { {reservation, hasprevious, hasafter} } }
 */
function renderCalendarCell(int $row, int $column, DateTime $date = null, array $reservations = null, bool $wmonth = false): string
{
    $today = new DateTime('today');
    ob_start(); ?>

<div class="ccalendar__cell " style="<?php echo "grid-row: " . $row . "; grid-column: " . $column . ";" ?>">
    <div class="ccell <?php echo ($wmonth == true) ? 'ccell--wmonth' : '' ?> ">
        <?php if (!is_null($date) and !is_null($reservations)) :
                $istoday = ($today == $date); ?>
        <div class="ccell__date <?php echo ($istoday == true) ? 'ccell__date--today' : '' ?>">
            <?php echo $date->format('d') ?>
        </div>

        <?php foreach ($reservations as $reservationinfo) :
                    $cellstyle = '';
                    $contentstyle = '';
                    $reservation = $reservationinfo['reservation'];
                    $car = $reservationinfo['car'];
                    $utente = $reservationinfo['utente'];
                    if ($reservationinfo['hasprevious'] == true and $reservationinfo['hasafter'] == true) {
                        $cellstyle = 'ccell__prenotazione--both';
                        $contentstyle = 'ccell__pcontent--both';
                    } elseif ($reservationinfo['hasprevious'] == true) {
                        $cellstyle = 'ccell__prenotazione--left';
                        $contentstyle = 'ccell__pcontent--left';
                    } elseif ($reservationinfo['hasafter'] == true) {
                        $cellstyle = 'ccell__prenotazione--right';
                        $contentstyle = 'ccell__pcontent--right';
                    } ?>
        <div class="ccell__prenotazione <?= $cellstyle ?>" id="<?php echo $reservation->id ?>">

            <div class="ccell__pcontent <?= $contentstyle ?>">
                <?php // if ($reservationinfo['hasprevious'] != true) : 
                            ?>
                <i class="bx bx<?= ($reservation->motivazione == 'personale') ? '-user-circle' : 's-business' ?>"></i>
                <span><?php echo $utente->nome . ' ' . $utente->cognome ?></span>
                <span>(<?php echo $car->modello ?>)</span>
                <?php // endif; 
                            ?>
            </div>

        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php
    return ob_get_clean();
}