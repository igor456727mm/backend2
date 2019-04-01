<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Pntall extends \PHPixie\ORM\Model {

    public $table = 'glr_allpoints';
    public $id_field = 'TRNSP_PNT_ID';
    protected $belongs_to = array(
        'transp' => array(
            'model' => 'transp',
            'key' => 'TRNSP_ID'
        ),
    );

}
