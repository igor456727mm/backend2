<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Pntmarkhis extends \PHPixie\ORM\Model {

    public $table = 'glr_trnsp_pnt_mark_hst';
    public $id_field = 'TRNSP_PNT_MARK_HST_ID';
    protected $belongs_to = array(
        'pnt' => array(
            'model' => 'pnt',
            'key' => 'TRNSP_PNT_ID'
        ),
        'user' => array(
            'model' => 'user',
            'key' => 'USER_ID'
        )
    );

}
