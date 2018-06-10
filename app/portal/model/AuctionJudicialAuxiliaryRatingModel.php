<?php

namespace app\portal\model;

use app\admin\model\RouteModel;
use think\Model;
use think\Db;

class AuctionJudicialAuxiliaryRatingModel extends Model
{
	
	protected $type = [
			'more' => 'array',
	];
	
	// 开启自动写入时间戳字段
	protected $autoWriteTimestamp = true;
	
	public function adminAddJudicialAuxiliaryRating($data)
	{
		
		$this->allowField(true)->data($data, true)->isUpdate(false)->save();
		
		
		return $this;
		
	}
	
	public function adminEditJudicialAuxiliaryRating($data)
	{
		
		unset($data['user_id']);
		
		
		$this->allowField(true)->isUpdate(true)->data($data, true)->save();
		
		return $this;
		
	}
}
