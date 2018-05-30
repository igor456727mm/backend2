<?php

namespace App;

/**
 * Pixie dependency container
 *
 * @property-read \PHPixie\DB $db Database module
 * @property-read \PHPixie\ORM $orm ORM module
 */
class Pixie extends \PHPixie\Pixie {

  protected $modules = array(
      'db' => '\PHPixie\DB',
      'orm' => '\PHPixie\ORM',
      'auth' => '\PHPixie\Auth',
      'email' => '\PHPixie\Email',
      'localization' => '\CodeOwners\Localization',
      
  );
  
 

  protected function after_bootstrap() {
    //Whatever code you want to run after bootstrap is done.		
    //   error_reporting(0);
      //  ini_set('display_errors', 0);
        date_default_timezone_set('Europe/Dublin');
  }

}
