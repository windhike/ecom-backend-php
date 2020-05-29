<?php
/**
 * Author: mark m /
 * Date:5/25/2020 2:31 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\model\Image;
use app\lib\exception\FileException;
use think\Request;

class ImageFile extends BaseController
{
    public function imageUpload()
    {
        // 获取表单上传文件，單文件上载数组
//        $files = request()->file('image');
        $files = Request::instance()->file();
        $file = $files['file'];
        $result = [];
        $storeDir = 'images';
        $host = config('myConfig.image_prefix');
//        foreach ($files as $key => $file) {

            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . $storeDir);
            if ($info) {
                $path = str_replace('\\', '/', $info->getSaveName());
            } else {
                throw new FileException([
                    'msg' => $info->getError(),
                    'errorCode' => 60001
                ]);
            }

            $image = Image::create([
                'url' => '/' . $path,
                'from' => 1,
            ]);
            array_push($result, [
                'id' => $image->id,
                'url' => $host . '/' . $path
            ]);
//        }
        return json($result);
    }
}