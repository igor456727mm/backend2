<?php
namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Usertoken extends \PHPixie\ORM\Model {

    public $table = 'mst_user_token_tab';
    public $id_field = 'ID';
    
        protected $belongs_to=array(
        'user'=>array(
            'model'=>'user',
            'key'=>'USER_ID'
        )
    );
 
}
