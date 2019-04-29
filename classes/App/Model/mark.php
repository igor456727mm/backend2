<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Mark extends \PHPixie\ORM\Model {

    public $table = 'glr_trnsp_pnt_mark';
    public $id_field = 'TRNSP_PNT_MARK_ID';
}
