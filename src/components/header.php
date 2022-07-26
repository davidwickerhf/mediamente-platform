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
 * @param string heading state of the prenotazioni dropdown.
 * @param string dropdown Title of the dropdown
 */
function renderHeader(string $heading, array $data, array $actionBtnArgs, ?string $subheading = null)
{
    ob_start(); ?>
<div class="cheader">
    <div class="cheader__headings">
        <a class="cheader__heading" href="/macchine">
            <?= $heading ?>
        </a>
        <?php if (!is_null($subheading)) : ?>
        <h2 class="cheader__subheading">
            / <?= $subheading ?>
        </h2>
        <?php endif; ?>
    </div>
    <div class="cheader__buttons">
        <?php
            echo renderDropdown(Macchine::SEDE_STATES[$data['indexSedeState']], 'macchine', Macchine::INDEX, Macchine::INDEX_UPDATE_SEDE, Macchine::SEDE_STATES, true, $data);

            $url = (isset($actionBtnArgs['url'])) ? $actionBtnArgs['url'] : null;

            echo call_user_func_array('renderButton', array(
                'title' => $actionBtnArgs['title'],
                'icon' => 'plus',
                'color' =>  'blue',
                'controller' => $actionBtnArgs['controller'],
                'method' => $actionBtnArgs['method'],
                'action' => $actionBtnArgs['action'],
                'url' => $url,
                'small' => true,
                'iconLeft' => true,
            ));
            ?>
    </div>
</div>
<?php return ob_get_clean();
} ?>