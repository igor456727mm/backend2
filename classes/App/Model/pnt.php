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
    
    public function deleteoldclaims($mark_type, $user_id) {
        $claims=$this->claims->
                where('MARK_TYPE_CD', $mark_type)->
                find_all();
        foreach ($claims as $claim) {
            $claim->delete();
        }
        $claim_hst = $this->claimhis->
                where('TRNSP_PNT_CLAIM_TYPE_TO', 'is', $this->pixie->db->expr('NULL'))->
                where('and', array('MARK_TYPE_CD', $mark_type))->
                find_all();
        foreach ($claim_hst as $rec) {
            $rec->TRNSP_PNT_CLAIM_TYPE_TO=date("Y-m-d H:i:s");
            $rec->save();
        }
    }

    public function addclaim($mark_type, $claim_type_cd, $user_id) {
        $claim_type = $this->claims->
                where('CLAIM_TYPE_CD', $claim_type_cd)->
                where('and', array('MARK_TYPE_CD', $mark_type))->
                find();
        if ($claim_type->loaded()) {
            return false;
        } else {
            $claim_type->CLAIM_TYPE_CD = $claim_type_cd;
            $claim_type->CLAIM_DTTM = date("Y-m-d H:i:s");
            $claim_type->MARK_TYPE_CD = $mark_type;
            $claim_type->TRNSP_PNT_ID = $this->id();
            $claim_type->USER_ID = $user_id;
            $claim_type->save();
        }
        $claim_type_hst = $this->claimhis->
                where('TRNSP_PNT_CLAIM_TYPE_TO', 'is', $this->pixie->db->expr('NULL'))->
                where('and', array('MARK_TYPE_CD', $mark_type))->
                where('and', array('CLAIM_TYPE_CD', $claim_type_cd))->
                find();
        if (!$claim_type_hst->loaded()) {
            $claim_type_hst = $this->pixie->orm->get('claimhis');
            $claim_type_hst->TRNSP_PNT_CLAIM_TYPE_FROM = date("Y-m-d H:i:s");
            $claim_type_hst->CLAIM_TYPE_CD = $claim_type_cd;
            $claim_type_hst->USER_ID = $user_id;
            $claim_type_hst->MARK_TYPE_CD = $mark_type;
            $claim_type_hst->TRNSP_PNT_ID = $this->id();
            $claim_type_hst->save();
        } else {
            $claim_type_hst->TRNSP_PNT_CLAIM_TYPE_TO = date("Y-m-d H:i:s");
            $claim_type_hst->save();
            $claim_type_hst = $this->pixie->orm->get('claimhis');
            $claim_type_hst->TRNSP_PNT_CLAIM_TYPE_FROM = date("Y-m-d H:i:s");
            $claim_type_hst->CLAIM_TYPE_CD = $claim_type_cd;
            $claim_type_hst->USER_ID = $user_id;
            $claim_type_hst->MARK_TYPE_CD = $mark_type;
            $claim_type_hst->TRNSP_PNT_ID = $this->id();
            $claim_type_hst->save();
        }
    }

    public function setmark($mark_type, $shop_mark, $shop_comment, $user_id) {
        //$this->mark->CLAIM_TYPE_CD = $claim_type_cd;
        $mark = $this->marks->where('MARK_TYPE_CD', $mark_type)->find();
        $mark->MARK = $shop_mark;
        $mark->MARK_DTTM = date("Y-m-d H:i:s");
        $mark->MARK_TYPE_CD = $mark_type;
        $mark->MARK_COMMENT = $shop_comment;
        $mark->TRNSP_PNT_ID=$this->id();
        $mark->USER_ID = $user_id;
        $mark->save();

        $pntMark = $this->markhis->
                where('TRNSP_PNT_MARK_TO', 'is', $this->pixie->db->expr('NULL'))->
                where('and', array('MARK_TYPE_CD', $mark_type))->
                find();
        if (!$pntMark->loaded()) {
            $pntMark = $this->pixie->orm->get('pntmarkhis');
            $pntMark->TRNSP_PNT_MARK_FROM = date("Y-m-d H:i:s");
            //$pntMark->CLAIM_TYPE_CD = $claim_type_cd;
            $pntMark->MARK = $shop_mark;
            $pntMark->MARK_TYPE_CD = $mark_type;
            $pntMark->MARK_COMMENT = $shop_comment;
            $pntMark->USER_ID = $user_id;
            $pntMark->TRNSP_PNT_ID = $this->id();
            $pntMark->save();
        } else {
            $pntMark->TRNSP_PNT_MARK_TO = date("Y-m-d H:i:s");
            $pntMark->save();
            $pntMark = $this->pixie->orm->get('pntmarkhis');
            $pntMark->TRNSP_PNT_MARK_FROM = date("Y-m-d H:i:s");
            //$pntMark->CLAIM_TYPE_CD = $claim_type_cd;
            $pntMark->MARK = $shop_mark;
            $pntMark->MARK_TYPE_CD = $mark_type;
            $pntMark->MARK_COMMENT = $shop_comment;
            $pntMark->USER_ID = $user_id;
            $pntMark->TRNSP_PNT_ID = $this->id();
            $pntMark->save();
        }
    }

    public function setstatus($status, $d, $user_id, $timezone = null, $dttm = null) {
        $this->TRNSP_PNT_STS_TYPE_CD = $status;
        if ($dttm == null) {
            $dttm = date("Y-m-d H:i:s");
        }
        $dttm = new DateTime($dttm);

        if (is_object($timezone)) {
            $dttm->setTimezone($timezone);
            $this->STS_TIMEZONE = $timezone->getName();
        }
        if ($status == 'RELEASED') {
            $this->REL_STS_DTTM = $dttm->format('Y-m-d H:i:s');
        } else {
            $this->STS_DTTM = $dttm->format('Y-m-d H:i:s');
        }

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
