<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Usersts extends \PHPixie\ORM\Model {

    public $table = 'mst_user_sts';
    public $id_field = 'user_sts_type_cd';
    

}
