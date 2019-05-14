<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Claimhis extends \PHPixie\ORM\Model {

    public $table = 'glr_trnsp_pnt_claim_hst';
    public $id_field = 'TRNSP_PNT_CLAIM_HST_ID';
    protected $belongs_to = array(
        'pnt' => array(
            'model' => 'pnt',
            'key' => 'TRNSP_PNT_ID'
        ),
    );

}
