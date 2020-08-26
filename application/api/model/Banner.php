<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/7/22
 * Time: 15:46
 */

namespace app\api\model;


use think\Model;

class Banner extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

}