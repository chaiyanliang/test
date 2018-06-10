<?php

namespace app\portal\model;

use app\admin\model\RouteModel;
use think\Model;
use think\Db;

class AuctionNationwideCourtModel extends Model
{
	
	protected $type = [
			'more' => 'array',
	];
	
	// 开启自动写入时间戳字段
	protected $autoWriteTimestamp = true;
}
