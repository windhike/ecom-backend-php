<?php
/**
 * Author: mark m /
 * Date:4/22/2020 11:26 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\model;


class User extends BaseModel
{
    private $id;

    protected $hidden=['delete_time','update_time','create_time','id'];
    protected $autoWriteTimestamp = true;

    public static function isUserExist($openId){
        $user=(new User)->where('openid','=',$openId)->find();
        return $user;
    }

    public function addressOfUser($id){
        $addr=$this->hasOne('UserAddress','user_id','id')->where('user_id','=',$id)->find();
        return $addr;
    }

    public function address(){
        $addr=$this->hasOne('UserAddress','user_id','id');
        return $addr;
    }

    public function userWithAddress(){
        return $this->with('address')->select(); //or find();
    }

    public static function getUsersPaginate($params)
    {
        $field = ['nickname'];
        $query = self::equalQuery($field, $params);

        list($start, $count) = paginate();
        // 应用条件查询
        $userList = self::where($query);
        // 调用模型的实例方法count计算该条件下会有多少条记录
        $totalNums = $userList->count();
        // 调用模型的limit方法对记录进行分页并获取查询结果
        $userList = $userList->limit($start, $count)
            ->order('create_time desc')
            ->select();
        // 组装返回结果
        $result = [
            'collection' => $userList,
            'total_nums' => $totalNums
        ];

        return $result;
    }

    public static function getUserStatisticsByDate($params,$format)
    {

        $dateRange = $params['start'].','.$params['end'];
        $user = (new User)->where('create_time', 'between time', $dateRange ) // 查询时间范围
        // 格式化create_time字段；做聚合查询
        ->field("FROM_UNIXTIME(create_time,'{$format}') as date,
                    count(*) as count")
            // 查询结果按date字段分组，注意这里因为在field()中给create_time字段起了别名date，所以用date
            ->group("date")
            ->select();

        return $user;
    }
}