<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Contact extends \PHPixie\ORM\Model {

    public $table = 'glr_cntct';
    public $id_field = 'CNTCT_ID';
}
