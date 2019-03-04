<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Log extends \PHPixie\ORM\Model {

    public $table = 'glr_log';
    public $id_field = 'log_id';

}
