<?php

/**
 * Banner Graph Component
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

require_once ROOT_PATH . 'controller/macchine.php';

/**
 * Render Banner html and javascript
 * 
 * @param string state State of the Stats section of the banner.
 * @param array contents Array with the contents of the graph:
 *  `contents: {columns: {{value: int, name: string}}, rows: {int}}`
 *  Rows values must be in descending order!
 */
function renderBannerGraph(array $contents): string

{
    ob_start();
?>

    <div class="bannergraph" id="bannerGraph">

        <div class="bannergraph__columns">
            <div class="bannergraph__ycolumn">

                <?php foreach ($contents['rows'] as $row) : ?>
                    <div class="bannergraph__row">
                        <?= $row ?>
                    </div>
                <?php endforeach; ?>
                <div class="bannergraph__row bannergraph__row--zero">

                    <!-- Zero Row -->
                </div>
            </div>
            <?php foreach ($contents['columns'] as $column) : ?>
                <div class="bannergraph__column">
                    <div class="ccolumn">
                        <div class="ccolumn__value">
                            <?php echo $column['value']; ?>
                        </div>
                        <div class="ccolumn__back">
                            <?php $height = round(150 / $contents['rows'][0] * $column['value']); ?>
                            <div class="ccolumn__front" style="height: <?= $height ?>px"></div>
                        </div>
                        <div class="ccolumn__name">
                            <?php echo $column['name']; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php return ob_get_clean();
} ?>