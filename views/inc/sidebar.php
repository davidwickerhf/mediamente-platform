<?php

/**
 * Sidebar Component
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (github/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */


/**
 * Generate banner html
 */
function renderSidebar(string $controller, string $name = null, string $email = null)
{ ?>
<div class="sidebar">
    <div class="logo-details">
        <img src="<?php ROOT_PATH ?>dist/images/logo_rosso.svg" alt="Logo" class="icon" id="websiteLink">
        <i class='bx bx-menu' id="btn"></i>
    </div>
    <ul class="nav-list">
        <li>
            <a <?= $controller == "panoramica" ? 'class="a--active"' : '' ?> href="<?= SERV_URL ?>
                <?php
                if (isset($_SESSION['filtro_cliente']) && $_SESSION['filtro_cliente'] != "")
                    echo 'panoramica/index/' . $_SESSION['filtro_cliente'];
                ?>">
                <i class='bx bx-grid-alt'></i>
                <span class="links_name">Panoramica</span>
            </a>
            <span class="tooltip">Panoramica</span>
        </li>
        <li>
            <a <?= $controller == "rapportinator" ? 'class="a--active"' : '' ?> href="<?= SERV_URL ?>rapportinator">
                <i class='bx bx-bar-chart-alt'></i>
                <span class="links_name">Rapportinator</span>
            </a>
            <span class="tooltip">Rapportinator</span>
        </li>
        <li>
            <a <?= $controller == "calendario" ? 'class="a--active"' : '' ?> href="<?= SERV_URL ?>calendario">
                <i class='bx bx-calendar'></i>
                <span class="links_name">Calendario</span>
            </a>
            <span class="tooltip">Calendario</span>
        </li>
        <li>
            <a <?= $controller == "progetto" ? 'class="a--active"' : '' ?> href="<?= SERV_URL ?>progetto">
                <i class='bx bx-task'></i>
                <span class="links_name">Progetti</span>
            </a>
            <span class="tooltip">Progetti</span>
        </li>
        <li>
            <a <?= $controller == "team" ? 'class="a--active"' : '' ?> href="<?= SERV_URL ?>team">
                <i class='bx bx-group'></i>
                <span class="links_name">Team</span>
            </a>
            <span class="tooltip">Team</span>
        </li>
        <?php if (getMyUsername() == "dchiarello" or getMyUsername() == "mbrianda" or getMyUsername() == "sleoni") { ?>
        <li>
            <a <?= $controller == "turni" ? 'class="a--active"' : '' ?> href="<?= SERV_URL ?>turni">
                <i class='bx bx-file'></i>
                <span class="links_name">Turni</span>
            </a>
            <span class="tooltip">Turni</span>
        </li>
        <?php } ?>
        <li>
            <a <?= $controller == "dotazioni" ? 'class="a--active"' : '' ?> href="<?= SERV_URL ?>dotazioni">
                <i class='bx bx-devices'></i>
                <span class="links_name">Dotazioni</span>
            </a>
            <span class="tooltip">Dotazioni</span>
        </li>
        <li>
            <a <?= $controller == "macchine" ? 'class="a--active"' : '' ?> href="<?= SERV_URL ?>macchine">
                <i class='bx bx-car'></i>
                <span class="links_name">Macchine</span>
            </a>
            <span class="tooltip">Macchine</span>
        </li>
        <li class="profile">
            <div class="profile-details">
                <!--<img src="profile.jpg" alt="profileImg">-->
                <div class="name_job">
                    <div class="name"><?php $name ?></div>
                    <div class="email"><?php $email ?></div>
                </div>
            </div>
            <i class='bx bx-log-out' id="log_out"></i>
        </li>
    </ul>
</div>

<script>
let sidebar = document.querySelector(".sidebar");
let closeBtn = document.querySelector("#btn");
let websiteLink = document.querySelector("#websiteLink");
let logoutBtn = document.querySelector("#log_out");

// Load sidebar state when page is loaded
if (!(window.localStorage.getItem("sidebar-state") === null)) {
    if (window.localStorage.getItem("sidebar-state") === "open") {
        sidebar.classList.add('notransition');
        sidebar.classList.add("open");
        closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        sidebar.offsetHeight;
        sidebar.classList.remove('notransition');
    }
}

closeBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    menuBtnChange(); //calling the function(optional)
});

// Navigate to mediamente consulting website on logo click
websiteLink.addEventListener("click", () => {
    window.location.href = 'https://www.mediamenteconsulting.it';
})

logoutBtn.addEventListener("click", () => {
    window.location.href = '<?= SERV_URL ?>utenti/logout';
})

// following are the code to change sidebar button(optional)
function menuBtnChange() {
    if (sidebar.classList.contains("open")) {
        closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); //replacing the iocns class
        // Save sidebar state in local storage
        window.localStorage.setItem("sidebar-state", "open");
    } else {
        closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); //replacing the iocns class
        // Save sidebar state in local storage
        window.localStorage.setItem("sidebar-state", "closed");
    }


}
</script>
<?php
} ?>