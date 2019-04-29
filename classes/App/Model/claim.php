<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Claim extends \PHPixie\ORM\Model {

    public $table = 'glr_trnsp_pnt_claim';
    public $id_field = 'TRNSP_PNT_CLAIM_ID';
}
