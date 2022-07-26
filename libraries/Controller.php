<?php

/**
 * Base controller class. Loads models and views.
 *
 * @author  David Henry Francis Wicker @ Mediamente Consulting
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License.
 */
class Controller
{
  // Load model
  public function model($model)
  {
    // Require model file
    require_once ROOT_PATH . 'model/' . $model . '.php';

    // Instatiate model
    return new $model();
  }

  // Load view
  public function view($view, $data = [])
  {
    // Check for view file
    if (file_exists(ROOT_PATH . 'views/' . $view . '.php')) {
      $var_in_view = $data;
      $view = ROOT_PATH . 'views/' . $view . '.php';
      require_once ROOT_PATH . 'views/inc/default.php';
    } else {
      // View does not exist
      die('View does not exist');
    }
  }
}