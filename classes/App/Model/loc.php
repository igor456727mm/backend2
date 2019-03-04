<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Loc extends \PHPixie\ORM\Model {

    public $table = 'glr_loc';
    public $id_field = 'LOC_ID';
    protected $belongs_to = array(
        'org' => array('model' => 'org', 'key' => 'org_id')
    );
    protected $has_many = array(
        'pnts' => array(
            'model' => 'pnt',
            'key' => 'LOC_TGT_ID'
        ),
    );

    public function setstatus($status) {
        $this->LOC_STS_TYPE_CD = $status;
        $dttm = date("Y-m-d H:i:s");
        $this->STS_DTTM = $dttm;
        $this->save();
    }

}
