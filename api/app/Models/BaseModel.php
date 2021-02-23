<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model {

    protected $table;
    protected $primaryKey;

    public function getPrimaryKey() {
        return $this->primaryKey;
    }

    public function querySelect($query) {
        $data = DB::select($query);
        return $data;
    }

    public function getTableData($where = false, $tableName = null) {
        if(!$tableName)
            $tableName = $this->table;
        if(!$where)
           $data = DB::table($tableName)->select('*')->get();
        else {
           $data = DB::table($tableName)
                    ->where($where)
                    ->get();
        }
        return $this->inArray($data);
    }

    public function addItem($data, $tableName = null) {
        if(!$tableName)
            $tableName = $this->table;
        $r = DB::table($tableName)->insert($data);
        return $this->saveResponse($r);
    }

    public function updateItem($data, $where, $tableName = null) {
        if(!$tableName)
            $tableName = $this->table;
        $r = DB::table($tableName)
            ->where($where[0], $where[1])
            ->update($data);
        return $this->saveResponse($r);
    }

    public function deleteItem($where, $tableName = null) {
        if(!$tableName)
            $tableName = $this->table;
        $r = DB::table($tableName)
            ->where($where[0], $where[1], $where[2])
            ->delete();
        // DB::table('users')->where('votes', '>', 100)->delete();
        return $this->saveResponse($r);
    }

    protected function inArray($data, $one = false)  {
        $data = $data->toArray();
        if(empty($data))
            return array();
        if($one)
            $data = $data[0];
        return (array)$data;
    }

    protected function saveResponse($data = [], $error = []) {
        $status = (empty($error)) ? true : false;
        return [
            'save_result' => $data,
            'error'       => $error,
            'status'      => $status
        ];
    }

//    public function findUser($param, $fieldName = 'id') {
//        $data = DB::table('users')
//            ->select('users.id AS user_id', '*')
//            ->where('person.' .$fieldName, $param)->get();
//        return $this->inArray($data, true);
//    }

}

// $users = DB::select('select * from users where active = ?', [1]);
// DB::insert('insert into users (id, name) values (?, ?)', [1, 'Dayle']);
// $affected = DB::update('update users set votes = 100 where name = ?', ['John']);
// $deleted = DB::delete('delete from users');
// $users = DB::table('users')->select('name', 'email as user_email')->get();
