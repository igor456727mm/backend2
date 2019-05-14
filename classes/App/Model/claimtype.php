<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Claimtype extends \PHPixie\ORM\Model {

    public $table = 'glr_claim_type';
    public $id_field = 'CLAIM_TYPE_CD';
}
