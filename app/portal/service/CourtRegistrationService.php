<?php
namespace app\portal\service;

use app\portal\model\AuctionCourtRegistrationModel;

use app\portal\model\ProvinceModel;
use app\portal\model\CityModel;
use app\portal\model\DistrictModel;

class CourtRegistrationService
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
	
	
	public function adminCourtRegistrationList($filter, $isPage = false)
	{
		
		$where = [
				'a.created_time' => ['>=', 0]
				
		];
		
// 		$join = [
// 				['__USER__ u', 'a.userid = u.id']
// 		];
		
		//$field = 'a.*,u.user_login,u.user_nickname,u.user_email';
		$field = 'a.*';
		$AuctionCourtRegistrationModel = new AuctionCourtRegistrationModel();
		$articles        = $AuctionCourtRegistrationModel->alias('a')->field($field)
		->where($where)
		->order('created_time', 'DESC')
		->paginate(10);
		
		return $articles;
		
	}
	
}