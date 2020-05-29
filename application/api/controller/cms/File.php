<?php
/*
* Created by DevilKing
* Date: 2019- 06-08
*Time: 16:26
*/

namespace app\api\controller\cms;

//use think\facade\Request;
use app\lib\file\LocalUploader;
use app\lib\exception\FileException;
use app\lib\file\ZergImageUploader;
use think\Request;

/**
 * Class File
 * @package app\api\controller\cms
 */
class File
{
    /**
     * @return mixed
     * @throws FileException
     * @throws FileException
     */
    public function postFile()
    {
        try {
            $request = Request::instance()->file();
        } catch (\Exception $e) {
            throw new FileException([
                'msg' => '字段中含有非法字符',
            ]);
        }
        $file = (new LocalUploader('$request'))->upload();
        return $file;
    }

    /**
     * 自定义图片上传方法
     */
    public function postCustomImage()
    {
        try {
            $request = Request::instance()->file();
        } catch (\Exception $e) {
            throw new FileException([
                'msg' => '字段中含有非法字符',
            ]);
        }
        $result = (new ZergImageUploader($request))->upload();

        return $result;
    }
}
