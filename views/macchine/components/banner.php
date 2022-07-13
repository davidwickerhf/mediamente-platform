<?php

/**
 * Banner Component
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */


/**
 * Generate banner html
 */
function renderBanner()
{ ?>

<div class="banner">
    <!-- Section Prossime Prenotazioni -->
    <div class="banner__section">
        <div class="section__head">
            <h2 class="section-heading">
                Le tue prenotazioni
            </h2>
            <div class="dropdown">
                <div class="dropdown__button">
                    <h2 class="dropdown__title">
                        Prossime
                    </h2>
                    <i class="fa fa-solid fa-angle-down dropdown__icon"></i>
                </div>
                <div class="dropdown__content">
                    <a href="#" class="dropdown__item">
                        Prossime
                    </a>
                    <a href="#" class="dropdown__item">
                        In corso
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="banner__divider"></div>

    <!-- Section Statistiche  -->
    <div class="banner__section">
        <div class="section">
            <div class="section__head">
                <h2 class="section-heading">Prenotazioni</h2>
                <div class="button-dropdown">
                    <div class="dropdown__button">
                        <h2 class="dropdown__title">
                            Mesi
                        </h2>
                        <i class="fa fa-solid fa-angle-down dropdown__icon"></i>
                    </div>
                    <div class="dropdown__content">
                        <a href="#" class="dropdown__item">
                            Mesi
                        </a>
                        <a href="#" class="dropdown__item">
                            Anni
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="banner__divider"></div>

    <!-- Section Disponibilita -->
    <div class="banner__section">
        <div class="section">
            <div class="section__head">
                <h2 class="section-heading">Disponibilita'</h2>
            </div>
        </div>
    </div>
</div>
<?php } ?>