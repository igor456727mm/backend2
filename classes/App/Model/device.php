<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Device extends \PHPixie\ORM\Model {

    public $table = 'icm_dev';
    public $id_field = 'dev_id';

   

}
