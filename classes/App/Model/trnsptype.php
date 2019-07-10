<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Trnsptype extends \PHPixie\ORM\Model {

    public $table = 'glr_trnsp_type';
    public $id_field = 'TRNSP_TYPE_CD';
}
