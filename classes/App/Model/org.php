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
        'loc'=> array(
            'model' => 'loc',
            'key' => 'org_id'
        ),
    );

}
