<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use app\portal\model\PortalCategoryModel;
use app\portal\model\AuctionProjectModel;
use app\portal\model\AuctionCourtRegistrationModel;
use app\portal\model\AuctionNationwideCourtModel;
use app\portal\service\CourtService;
use think\Validate;
use think\Db;

class CourtController extends HomeBaseController
{
    public function index()
    {
        $courtService = new CourtService();
        $province='河南';
        $data= $courtService->GetCourtsByProvince($province);
        $this->assign('courts', $data);
    	
        return $this->fetch("/court");
    }
    
    public function GetProCourts($pro)
    {
        $auctionNationwideCourtModel = new AuctionNationwideCourtModel();
	    $field = 'a.*';
	    $where = [
	        'a.province' => ['=', trim($pro)],
	        'a.level' => ['=', 2]
	        
	    ];
	    
	    $courts = $auctionNationwideCourtModel->alias('a')->field($field)
	    ->where($where)	   
	    ->select();
	    
	    return $courts;
    }
	
	  
    public function add()
    {
    	if ($this->request->isPost()) {
    		$validate = new Validate([
    				'name'  => 'require',
    				'contact_person' => 'require',
    				'phone' => 'require',
    		]);
    		$validate->message([
    				'name.require' => '法院名称不能为空',
    				'contact_person.require' => '联系人不能为空',
    				'phone.require'  => '联系电话不能为空',
    		]);
    		
    		$data = $this->request->post();
    		if (!$validate->check($data)) {
    			$this->error($validate->getError());
    		}
    		
    		$name = $data['name'];
    		$contact_person = $data['contact_person'];
    		$phone = $data['phone'];
    		
    		$findCourtCount = Db::name("auction_court_registration")->where([
    				'name'  => $name
    		])->count();
    		
    		if ($findCourtCount > 0) {
    			$this->error("您已申请过啦!");
    		}
    		
    		Db::name("auction_court_registration")->insert([
    				'name'     => $name,
    				'contact_person'       => $contact_person,
    				'phone' => $phone,
    				'created_time' => time()
    		]);
    		
    		$this->success('已提交申请。');
    		
    	}
    }
}
