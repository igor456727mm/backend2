<?php

namespace App\Model;

use DateTime;
use DateTimeZone;

//PHPixie will guess the name of the table
//from the class name
class Pnt extends \PHPixie\ORM\Model {

    public $table = 'glr_trnsp_pnt';
    public $id_field = 'TRNSP_PNT_ID';
    protected $belongs_to = array(
        'loctgt' => array(
            'model' => 'loc',
            'key' => 'LOC_TGT_ID'
        ),
        'locsrc' => array(
            'model' => 'loc',
            'key' => 'LOC_SRC_ID'
        ),
        'transp' => array(
            'model' => 'transp',
            'key' => 'TRNSP_ID'
        ),
        'sts' => array(
            'model' => 'pntsts',
            'key' => 'TRNSP_PNT_STS_TYPE_CD'
        ),
    );
    protected $has_many = array(
        'stshis' => array(
            'model' => 'pntstshis',
            'key' => 'TRNSP_PNT_ID'
        ),
    );

    public function setstatus($status, $d, $user_id, $timezone = null, $dttm = null) {
        $this->TRNSP_PNT_STS_TYPE_CD = $status;
        if ($dttm == null) {
             $dttm= date("Y-m-d H:i:s");
        }
        $dttm = new DateTime($dttm);
        
        if (is_object($timezone)) {
            $dttm->setTimezone($timezone);
            $this->STS_TIMEZONE = $timezone->getName();
        }
        $this->STS_DTTM=$dttm->format('Y-m-d H:i:s');
        
        $this->save();
        $pntStatus = $this->stshis->
                        where('TRNSP_PNT_STS_TO', 'is', $this->pixie->db->expr('NULL'))->find();
        if (!$pntStatus->loaded()) {
            $pntStatus = $this->pixie->orm->get('pntstshis');
            $pntStatus->TRNSP_PNT_STS_FROM = date("Y-m-d H:i:s");
            $pntStatus->TRNSP_PNT_STS_TYPE_CD = $status;
            $pntStatus->USER_ID = $user_id;
            $pntStatus->STS_DTTM = $dttm->format('Y-m-d H:i:s');
            $pntStatus->TRNSP_PNT_ID = $this->id();
            $pntStatus->DISTANCE = $d;
            $pntStatus->save();
        } else {
            $pntStatus->TRNSP_PNT_STS_TO = date("Y-m-d H:i:s");
            $pntStatus->save();
            $pntStatus = $this->pixie->orm->get('pntstshis');
            $pntStatus->TRNSP_PNT_STS_FROM = date("Y-m-d H:i:s");
            $pntStatus->TRNSP_PNT_STS_TYPE_CD = $status;
            $pntStatus->DISTANCE = $d;
            $pntStatus->USER_ID = $user_id;
            $pntStatus->STS_DTTM = $dttm->format('Y-m-d H:i:s');
            $pntStatus->TRNSP_PNT_ID = $this->id();
            $pntStatus->save();
        }
    }

}
