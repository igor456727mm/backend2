<?php
namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Role extends \PHPixie\ORM\Model {

    public $table = 'mst_role_tab';
    public $id_field = 'CODE';
 
}
