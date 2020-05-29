<?php
/**
 * Author: mark m /
 * Date:5/19/2020 4:57 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\model\UserAddress;
use app\api\service\BaseToken;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;
use app\lib\tencent\Sms;
use think\Exception;
use think\Request;
use \app\api\model\User as UserModel;

class User extends BaseController
{
    /**
     * @auth('会员列表','会员管理')
     */
    public function getUsersPaginate()
    {
        $params = Request::instance()->get();
        $users = UserModel::getUsersPaginate($params);
        if ($users['total_nums'] === 0) {
            throw new UserException([
                'code' => 404,
                'msg' => '未查询到会员相关信息',
                'errorCode' => 70013
            ]);
        }
        return $users;
    }

    public function updateUserInfo(){
        $userInfo = Request::instance()->post();
        $uid = (new BaseToken())->getUidByToken();
        try{
            (new UserModel())->where('id','=',$uid)->update(['nickname'=>$userInfo['nickname'],'extend'=>$userInfo['extend']]);
        }
        catch (Exception $ex){
            throw new UserException(['msg'=>'update user info fail']);
        }

        return json(new SuccessMessage(['msg'=>'update user info success']),201);
    }

    /**
     * 发送活动上线短信提醒
     * @auth('活动短信发送','会员管理')
     * @return array
     */
    public function sendActivityOnlineSms()
    {
        // 接收前端提交过来ids参数，由,分隔的用户id组成的字符串
        $ids = Request::instance()->post('ids');
        // 利用explode() PHP内置函数以,格式化字符串为数组
        $userIds = explode(',', $ids);
        // 为数组的每个元素作用一个函数，并返回每个函数作用的结果
        $result = array_map(function ($uid) {
            // 根据uid查询用户表中的记录
//            $user = (new UserModel())->field('nickname,tel')->find($uid);
            $user = (new UserAddress())->field('name,mobile')->where('user_id','=',$uid)->find();
            // 如果查询到用户记录，调用封装的短信发送类库并返回发送结果。
//            if ($user) return (new Sms())->send($user->tel, [$user->nickname]);
            if ($user) return (new Sms())->send([$user->name],$user->mobile );
        }, $userIds);

        return $result;
    }
}