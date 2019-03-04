<?php

namespace App\Model;

//PHPixie will guess the name of the table
//from the class name
class User extends \PHPixie\ORM\Model {

    public $table = 'mst_user_tab';
    public $id_field = 'ID';
    protected $belongs_to = array(
        'org' => array('model' => 'org', 'key' => 'org_id'),
        'sts' => array('model' => 'usersts', 'key' => 'user_sts_type_cd')
    );
    protected $has_one = array(
        'userapi' => array(
            'model' => 'userapi',
            'key' => 'tmp1'
        ),
        'usersettings' => array(
            'model' => 'usersettings',
            'key' => 'user_id'
        )
    );
    protected $has_many = array(
        'roles' => array(
            'model' => 'role',
            'through' => 'mst_user_role_tab',
            'key' => 'user_id',
            'foreign_key' => 'role_code'
        ),
        'eulas' => array(
            'model' => 'eula',
            'through' => 'mst_user_eula_tab',
            'key' => 'user_id',
            'foreign_key' => 'eula_id'
        ),
        'apitokens' => array(
            'model' => 'apitoken',
            'key' => 'user_id'
        ),
        'usertokens' => array(
            'model' => 'usertoken',
            'key' => 'user_id'
        ),
        'stshis' => array(
            'model' => 'userstshis',
            'key' => 'user_id'
        ),
    );

    public function setstatus($status) {
        $this->user_sts_type_cd = $status;
        $this->sts_dttm = date("Y-m-d H:i:s");
        $this->save();
        $userStatus = $this->stshis->
                        where('user_sts_to', 'is', $this->pixie->db->expr('NULL'))->find();
        if (!$userStatus->loaded()) {
            $userStatus = $this->pixie->orm->get('userstshis');
            $userStatus->user_sts_from = date("Y-m-d H:i:s");
            $userStatus->user_sts_type_cd = $status;
            $userStatus->user_id = $this->id();
            $userStatus->save();
        } elseif ($userStatus->user_sts_type_cd != $status) {
            $userStatus->user_sts_to = date("Y-m-d H:i:s");
            $userStatus->save();
            $userStatus = $this->pixie->orm->get('userstshis');
            $userStatus->user_sts_from = date("Y-m-d H:i:s");
            $userStatus->user_sts_type_cd = $status;
            $userStatus->user_id = $this->id();
            $userStatus->save();
        }
    }

    public function get_usersbyrole($role) {
        $qry = "select u.* from mst_user_tab u, mst_user_role_tab r where r.user_id=u.id and r.role_code='$role';";
        $res = $this->conn->execute($qry);
        return $res;
    }

    public function eula_agreed($TYPE_CODE) {
        date_default_timezone_set('Europe/Dublin');
        $now = date('YYYY-mm-dd');
        $cnt = $this->eulas->where('DATE_ON', '<', $now)->
                where('and', array('DATE_OFF', '>', $now))->
                where('and', array('TYPE_CODE', $TYPE_CODE))->
                count_all();

        if ($cnt > 0)
            return true;
        else
            return false;
    }

    public function eula_toagree($TYPE_CODE) {
        date_default_timezone_set('Europe/Dublin');
        $now = date('YYYY-mm-dd');
        $eula = $this->pixie->orm->get('eula')->where('DATE_ON', '<', $now)->
                where('and', array('DATE_OFF', '>', $now))->
                where('and', array('TYPE_CODE', $TYPE_CODE))->
                find();
        return $eula;
    }

}
