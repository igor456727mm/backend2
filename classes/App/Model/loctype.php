<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Loctype extends \PHPixie\ORM\Model {

    public $table = 'glr_loc_type';
    public $id_field = 'LOC_TYPE_CODE';
}
