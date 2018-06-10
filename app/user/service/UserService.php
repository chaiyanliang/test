<?php
namespace app\user\service;

use app\user\model\AuctionMyConcernModel;
use app\user\model\AuctionMyProjectModel;
use app\user\model\AuctionMyRecordModel;

class UserService
{
	public function userConcernList($filter, $isPage = false)
	{
		$userId        = cmf_get_current_user_id();
		
		$where = [
				'a.created_time' => ['>=', 0],
				'a.user_id' => $userId
		];
		
		$join = [
				['__AUCTION_PROJECT__ u', 'a.project_id = u.id'],
				['__PORTAL_CATEGORY_POST__ c', 'a.project_id = c.post_id']
		];

		$field = 'a.*, u.*,c.*';
		

		$auctionMyConcernModel = new AuctionMyConcernModel();
		$concerns        = $auctionMyConcernModel->alias('a')->field($field)
		->join($join)
		->where($where)
		->paginate(9);
		
		return $concerns;
		
	}	

	public function userProjectList($filter, $isPage = false)
	{
		$userId        = cmf_get_current_user_id();
		
		$where = [
				'a.created_time' => ['>=', 0],
				'a.user_id' => $userId
		];
		
		$join = [
				['__AUCTION_PROJECT__ u', 'a.project_id = u.id'],
		    ['__PORTAL_CATEGORY_POST__ c', 'a.project_id = c.post_id']
		];	
		
		
		$field = 'a.*, u.*,c.*';
		
		
		$auctionMyProjectModel = new AuctionMyProjectModel();
		$projects        = $auctionMyProjectModel->alias('a')->field($field)
		->join($join)
		->where($where)
		->paginate(9);
		
		return $projects;
		
	}
	
	public function userRecordList($filter, $isPage = false)
	{
		$userId        = cmf_get_current_user_id();
		
		$where = [
				'a.created_time' => ['>=', 0],
				'a.user_id' => $userId
		];
		
		$join = [
				['__AUCTION_PROJECT__ u', 'a.project_id = u.id'],
				['__PORTAL_CATEGORY_POST__ c', 'a.project_id = c.post_id']
		];
		
		$field = 'a.*, u.*,c.*';
		
		
		$auctionMyRecordModel = new AuctionMyRecordModel();
		$records        = $auctionMyRecordModel->alias('a')->field($field)
		->join($join)
		->where($where)
		->paginate(9);
		
		return $records;
		
	}
	
	

}