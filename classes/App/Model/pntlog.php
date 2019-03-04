<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Pntlog extends \PHPixie\ORM\Model {

    public $table = 'glr_trnsp_pnt_log';
    public $id_field = 'trnps_pnt_log_id';

}
