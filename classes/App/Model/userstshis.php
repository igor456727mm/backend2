<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Userstshis extends \PHPixie\ORM\Model {

    public $table = 'mst_user_sts_his';
    public $id_field = 'user_sts_his_id';
    protected $belongs_to = array(
        'user' => array(
            'model' => 'user',
            'key' => 'user_id'
        ),
    );

}
