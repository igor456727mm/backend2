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
protected $has_many = array(
        'stshis' => array(
            'model' => 'pntstshis',
            'key' => 'TRNSP_PNT_ID'
        ),
        'markhis' => array(
            'model' => 'pntmarkhis',
            'key' => 'TRNSP_PNT_ID'
        ),
        'claimhis' => array(
            'model' => 'claimhis',
            'key' => 'TRNSP_PNT_ID'
        ),
        'claims' => array(
            'model' => 'claim',
            'key' => 'TRNSP_PNT_ID'
        ),
        'marks' => array(
            'model' => 'mark',
            'key' => 'TRNSP_PNT_ID'
        ),
    );
}
