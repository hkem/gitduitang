<?php
/**
 * Created by PhpStorm.
 * User: nyf
 * Date: 2020/8/3
 * Time: 13:45
 */

namespace app\api\model;


use think\Model;

class News extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

}