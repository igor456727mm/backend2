<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Usersettings extends \PHPixie\ORM\Model {

    public $table = 'krn_user';
    public $id_field = 'user_id';
    protected $belongs_to = array(
        'user' => array('model' => 'user', 'key' => 'user_id')
    );

}
