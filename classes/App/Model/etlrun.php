<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Etlrun extends \PHPixie\ORM\Model {

    public $table = 'etl_run';
    public $id_field = 'etl_run_id';
    protected $has_many = array(
        'pntlogs' => array(
            'model' => 'pntlog',
            'key' => 'etl_run_id'
        ),
    );

}
