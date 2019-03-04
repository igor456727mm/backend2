<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class Shop extends \PHPixie\ORM\Model {

    public $table = 'glr_shop_view';
    public $id_field = 'LOC_ID';

}
