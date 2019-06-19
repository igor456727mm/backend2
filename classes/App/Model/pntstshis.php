<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Pntstshis extends \PHPixie\ORM\Model {

    public $table = 'glr_trnsp_pnt_sts';
    public $id_field = 'TRNSP_PNT_STS_ID';
    protected $belongs_to = array(
        'pnt' => array(
            'model' => 'pnt',
            'key' => 'TRNSP_PNT_ID'
        ),
        'user' => array(
            'model' => 'user',
            'key' => 'USER_ID'
        ),
        'status' => array(
            'model' => 'pntsts',
            'key' => 'TRNSP_PNT_STS_TYPE_CD'
        )
    );

}
