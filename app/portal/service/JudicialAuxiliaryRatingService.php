<?php
namespace app\portal\service;

use app\portal\model\AuctionJudicialAuxiliaryRatingModel;

use app\portal\model\ProvinceModel;
use app\portal\model\CityModel;

class JudicialAuxiliaryRatingService
{
	public function allJudicialAuxiliaryRatingList($filter, $isPage = false)
	{
		$judicialAuxiliaryRatingModel = new AuctionJudicialAuxiliaryRatingModel();
		$field = 'a.*';
		$where = [
				'a.id' => ['>=', 0]
				
		];
		
		$courts = $judicialAuxiliaryRatingModel->alias('a')->field($field)
		->where($where)
		->paginate(10000);
		
		return $courts;
	}
	
}