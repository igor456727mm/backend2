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

    public function getallpoints() {
        $org_id = $this->id();
        $qry = "select * from glr_allpoints a where a.TRNSP_ID in (select TRNSP_ID from glr_allpoints a1 where a1.ORG_TGT_ID=$org_id) and (ORG_TGT_ID=$org_id or LOC_TGT_TYPE_CD='RC')";
        $res = $this->conn->execute($qry);
        return $res;
    }

}
