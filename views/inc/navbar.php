<?php

/**
 * Navbar Component
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (github/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */


/**
 * Generate banner html
 */
function renderNavbar(string $controller, string $name = null, $surname = null, string $email = null)
{ ?>
<div class="cnavbar">
    <div class="cnavbar__bar">
        <img src="<?php ROOT_PATH ?>dist/images/logo_rosso.svg" alt="Logo" class="cnavbar__logo" id="websiteLink">
        <i class='bx bx-menu cnavbar__hamburger' id="navbtn"></i>
    </div>

    <div class="cnavbar__navlist-wrapper">
        <div class="cnavbar__background"> </div>
        <ul class="cnavbar__navlist">
            <li class="nav_item <?= $controller == "panoramica" ? 'li--active' : '' ?>">
                <a href="<?= SERV_URL ?>
                <?php
                if (isset($_SESSION['filtro_cliente']) && $_SESSION['filtro_cliente'] != "")
                    echo 'panoramica/index/' . $_SESSION['filtro_cliente'];
                ?>">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Panoramica</span>
                </a>

            </li>
            <li class="nav_item <?= $controller == "rapportinator" ? 'li--active' : '' ?>">
                <a href="<?= SERV_URL ?>rapportinator">
                    <i class='bx bx-bar-chart-alt'></i>
                    <span class="links_name">Rapportinator</span>
                </a>

            </li>
            <li class="nav_item <?= $controller == "calendario" ? 'li--active' : '' ?>">
                <a href="<?= SERV_URL ?>calendario">
                    <i class='bx bx-calendar'></i>
                    <span class="links_name">Calendario</span>
                </a>

            </li>
            <li class="nav_item <?= $controller == "progetti" ? 'li--active' : '' ?>">
                <a href="<?= SERV_URL ?>progetto">
                    <i class='bx bx-task'></i>
                    <span class="links_name">Progetti</span>
                </a>

            </li>
            <li class="nav_item <?= $controller == "team" ? 'li--active' : '' ?>">
                <a href="<?= SERV_URL ?>team">
                    <i class='bx bx-group'></i>
                    <span class="links_name">Team</span>
                </a>

            </li>
            <?php if (getMyUsername() == "dchiarello" or getMyUsername() == "mbrianda" or getMyUsername() == "sleoni") { ?>
            <li class="nav_item <?= $controller == "turni" ? 'li--active' : '' ?>">
                <a href="<?= SERV_URL ?>turni">
                    <i class='bx bx-file'></i>
                    <span class="links_name">Turni</span>
                </a>

            </li>
            <?php } ?>
            <li class="nav_item <?= $controller == "dotazioni" ? 'li--active' : '' ?>">
                <a href="<?= SERV_URL ?>dotazioni">
                    <i class='bx bx-devices'></i>
                    <span class="links_name">Dotazioni</span>
                </a>

            </li>
            <li class="nav_item <?= $controller == "macchine" ? 'li--active' : '' ?>">
                <a href="<?= SERV_URL ?>macchine">
                    <i class='bx bx-car'></i>
                    <span class="links_name">Macchine</span>
                </a>

            </li>

        </ul>
        <div class="cnavbar__profile">
            <div class="cnavbar__profile-details">
                <!--<img src="profile.jpg" alt="profileImg">-->
                <div class="profile__info">
                    <div class="profile__name"><?php $name ?></div>
                    <div class="profile__email"><?php $email ?></div>
                </div>
            </div>
            <i class='bx bx-log-out' id="nav_log_out"></i>
        </div>
    </div>

</div>
<script>
let navbar = document.querySelector(".cnavbar");
let navCloseBtn = document.querySelector("#navbtn");
let navLogoutBtn = document.querySelector("#nav_log_out");

navCloseBtn.addEventListener("click", () => {
    navbar.classList.toggle("open");
    navMenuBtnChange(); //calling the function(optional)
});

navLogoutBtn.addEventListener("click", () => {
    window.location.href = '<?= SERV_URL ?>utenti/logout';
})

// following are the code to change sidebar button(optional)
function navMenuBtnChange() {
    if (navbar.classList.contains("open")) {
        navCloseBtn.classList.replace("bx-menu", "bx-menu-alt-right"); //replacing the iocns class

    } else {
        navCloseBtn.classList.replace("bx-menu-alt-right", "bx-menu"); //replacing the iocns class

    }


}
</script>
<?php
} ?>