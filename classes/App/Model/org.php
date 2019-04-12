<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Org extends \PHPixie\ORM\Model {

    public $table = 'glr_org';
    public $id_field = 'ORG_ID';
    protected $has_many = array(
        'contacts' => array(
            'model' => 'contact',
            'key' => 'org_id'
        ),
    );
    protected $has_one = array(
        'loc' => array(
            'model' => 'loc',
            'key' => 'org_id'
        ),
    );

    public function getallpoints($date_from,$date_to) {
        $org_id = $this->id();
        if ($date_from!='') {
            $date_filter=" and LOC_PLAN_DTTM>='$date_from'";
        }
        if ($date_to!='') {
            $date_filter=$date_filter." and LOC_PLAN_DTTM<='$date_to'";
        }
        $qry = "select * from glr_allpoints a where a.TRNSP_ID in (select TRNSP_ID from glr_allpoints a1 where a1.ORG_TGT_ID=$org_id) and (ORG_TGT_ID=$org_id or LOC_TGT_TYPE_CD='RC')".$date_filter;
        $res = $this->conn->execute($qry);
        //die($qry);
        return $res;
    }

}
