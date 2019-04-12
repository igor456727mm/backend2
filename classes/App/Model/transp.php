<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Transp extends \PHPixie\ORM\Model {

    public $table = 'glr_trnsp';
    public $id_field = 'TRNSP_ID';
     protected $belongs_to = array(
        'org' => array('model' => 'org', 'key' => 'org_id')
    );
    protected $has_many = array(
        'locs' => array(
            'model' => 'loc',
            'through' => 'glr_trnsp_pnt',
            'key' => 'TRNSP_ID',
            'foreign_key' => 'LOC_TGT_ID'
        ),
        'pnts' => array(
            'model' => 'pnt',
            'key' => 'trnsp_id'
        ),
        'pntall' => array(
            'model' => 'pntall',
            'key' => 'trnsp_id'
        ),
    );

}
