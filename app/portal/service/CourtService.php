<?php
namespace app\portal\service;

use app\portal\model\AuctionSettledCourtModel;

use app\portal\model\ProvinceModel;
use app\portal\model\CityModel;
use app\portal\model\AuctionNationwideCourtModel;
use app\portal\model\DistrictModel;

class CourtService
{
	
	public function adminArticleList($filter)
	{
		return $this->adminPostList($filter);
	}
	
	public function adminPageList($filter)
	{
		return $this->adminPostList($filter, true);
	}
	
	
	public function getProvince()
	{
		$ProvinceModel = new ProvinceModel();
		$field = 'a.*';
		$provinces        = $ProvinceModel->alias('a')->select();
		return $provinces;
	}
	
	public function getCity($pid)
	{
		$CityModel = new CityModel();
		$field = 'a.*';
		$where = [
				'a.pid' => ['=', $pid]	
		];
		$citys = $CityModel->alias('a')->field($field)
		->where($where)->select();
		return $citys ;
	}

	public function getDistrict($cid)
	{
		$districtModel = new DistrictModel();
		if(is_numeric($cid)){
			$field = 'a.*';
			$where = [
					'a.cid' => ['=', $cid]
			];
			$districts = $districtModel->alias('a')->field($field)
			->where($where)->select();
			return $districts;
		}else{
			$where = [
					'c.city' => ['=', $cid]
			];
			
			$join = [
					['__CITY__ c', 'a.cid = c.cid']
			];
			$field = 'a.*';
			$districts = $districtModel->alias('a')->field($field)
			->join($join)
			->where($where)
			->select();
			return $districts;
		}
		
	}
	
	public function allCourtList($filter, $isPage = false)
	{
		$auctionNationwideCourtModel = new AuctionNationwideCourtModel();
		$field = 'a.*';
		$where = [
				'a.id' => ['>=', 0]
				
		];
		
		$courts = $auctionNationwideCourtModel->alias('a')->field($field)
		->where($where)
		->order('state', 'DESC')
		->paginate(1000);
		
		return $courts;
	}
	
		public function GetCourtsByProvince($province, $isPage = false)
	{
	    $auctionNationwideCourtModel = new AuctionNationwideCourtModel();
	    $field = 'a.*';
	    $where = [
	        'a.province' => ['=', $province],
	        'a.level' => ['=', 2]
	        
	    ];
	    
	    $courts = $auctionNationwideCourtModel->alias('a')->field($field)
	    ->where($where)	   
	    ->paginate(1000);
	    
	    return $courts;
	}
	
	public function adminCourtList($filter, $isPage = false)
	{
		
		$where = [
				'a.created_time' => ['>=', 0]
				
		];
				
		
		$field = 'a.*';
		
// 		$category = empty($filter['category']) ? 0 : intval($filter['category']);
// 		if (!empty($category)) {
// 			$where['b.category_id'] = ['eq', $category];
// 			array_push($join, [
// 					'__PORTAL_CATEGORY_POST__ b', 'a.id = b.post_id'
// 			]);
// 			$field = 'a.*,b.id AS post_category_id,b.list_order,b.category_id,u.user_login,u.user_nickname,u.user_email';
// 		}
		
// 		$startTime = empty($filter['start_time']) ? 0 : strtotime($filter['start_time']);
// 		$endTime   = empty($filter['end_time']) ? 0 : strtotime($filter['end_time']);
// 		if (!empty($startTime) && !empty($endTime)) {
// 			$where['a.published_time'] = [['>= time', $startTime], ['<= time', $endTime]];
// 		} else {
// 			if (!empty($startTime)) {
// 				$where['a.published_time'] = ['>= time', $startTime];
// 			}
// 			if (!empty($endTime)) {
// 				$where['a.published_time'] = ['<= time', $endTime];
// 			}
// 		}
		
// 		$keyword = empty($filter['keyword']) ? '' : $filter['keyword'];
// 		if (!empty($keyword)) {
// 			$where['a.post_title'] = ['like', "%$keyword%"];
// 		}
		
// 		if ($isPage) {
// 			$where['a.post_type'] = 2;
// 		} else {
// 			$where['a.post_type'] = 1;
// 		}
		
		$AuctionSettledCourtModel = new AuctionSettledCourtModel();
		$articles        = $AuctionSettledCourtModel->alias('a')->field($field)		
		->where($where)
		->order('created_time', 'DESC')
		->paginate(20);
		
		return $articles;
		
	}		
	
}