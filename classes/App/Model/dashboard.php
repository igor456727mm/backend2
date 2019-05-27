<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Dashboard extends \PHPixie\ORM\Model {

    public $table = 'glr_dashboard';
    public $id_field = 'DASHBOARD_ID';
}
