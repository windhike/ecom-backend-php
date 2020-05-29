<?php
/**
 * Author: mark m /
 * Date:4/29/2020 1:11 AM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\model;


class OrderProduct extends  BaseModel
{
    protected $hidden = [];
    protected $autoWriteTimestamp = true;
    protected $createTime = false; //orderProduct 表没有create time， 设置autowrite后会报内部错误;要去掉createTime
}