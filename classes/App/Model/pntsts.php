<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Pntsts extends \PHPixie\ORM\Model {

    public $table = 'glr_trnsp_pnt_sts_type';
    public $id_field = 'TRNSP_PNT_STS_TYPE_CD';
    

}
