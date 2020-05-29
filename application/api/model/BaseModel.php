<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    protected function addImgPrefixToUrl($url,$data){

        if($data['from']==1){  //'from'==1 means img is stored in local server;
            $finalUrl = config('myConfig.image_prefix').$url; //merge img prefix & url;
        }
        else{
            $finalUrl=$url;
        }
        return $finalUrl;

    }

    /**
     * 构造条件为相等的数组查询条件
     * @param $field 要检索的参数名数组
     * @param $params 前端提交过来的所有GET参数数组
     * @return array 构造好后的查询条件
     */
    protected static function equalQuery($field, $params)
    {
        $query = [];
        foreach ($field as $value) {
            if (is_array($value)) {
                if (array_key_exists($value[0], $params)) {
                    $query[] = [$value[1], '=', $params[$value[0]]];
                }
            } else {
                if (array_key_exists($value, $params)) {
                    $query[] = [$value, '=', $params[$value]];
                }
            }
        }
        return $query;
    }

    /**
     * @param $startField 开始时间的参数名
     * @param $endField 结束时间的参数名
     * @param $params  前端提交过来的所有GET参数数组
     * @param string $dbField 要查询的表字段名，默认是create_time
     * @return array
     */
    protected static function betweenTimeQuery($startField, $endField, $params, $dbField = 'create_time')
    {
        $query = [];
        if (array_key_exists($startField, $params) && array_key_exists($endField, $params)) {
            if (!empty($params[$startField]) && !empty($params[$endField])) {
                $query = array($dbField, 'between time', array($params[$startField], $params[$endField]));
            }
        }
        return $query;
    }
}
