<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Orgtype extends \PHPixie\ORM\Model {

    public $table = 'glr_org_type';
    public $id_field = 'ORG_TYPE_CD';

}
