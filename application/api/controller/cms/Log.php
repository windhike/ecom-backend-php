<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/4/26
 * Time: 22:20
 */

namespace app\api\controller\cms;

use LinCmsTp5\admin\model\LinLog;
use think\Request;

class Log
{

    /**
     * @auth('查询所有日志','日志')
     * @param Request $request
     * @return array
     * @throws \LinCmsTp5\admin\exception\logger\LoggerException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getLogs(Request $request)
    {
        $params = $request->get();

        $result = LinLog::getLogs($params);
        return $result;
    }

    /**
     * @auth('搜索日志','日志')
     * @param Request $request
     * @return array
     * @throws \LinCmsTp5\admin\exception\logger\LoggerException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserLogs(Request $request)
    {
        $params = $request->get();

        $result = LinLog::getLogs($params);
        return $result;
    }

    /**
     * @auth('查询日志记录的用户','日志')
     * @return array
     */
    public function getUsers()
    {
        $users = LinLog::column('user_name');
        $result = array_unique($users);
        return $result;
    }
}