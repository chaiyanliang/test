<?php
namespace app\portal\controller;

use think\Validate;
use cmf\controller\AdminBaseController;
use cmf\controller\HomeBaseController;
use app\portal\model\AuctionCourtRegistrationModel;
use app\portal\service\CourtRegistrationService;
use think\Db;
use app\admin\model\ThemeModel;

class AdminCourtRegistrationController extends AdminBaseController
{
	/**
	 * 法院列表
	 * @adminMenu(
	 *     'name'   => '法院入驻申请',
	 *     'parent' => 'portal/AdminIndex/default',
	 *     'display'=> true,
	 *     'hasView'=> true,
	 *     'order'  => 10000,
	 *     'icon'   => '',
	 *     'remark' => '法院管理',
	 *     'param'  => ''
	 * )
	 */
	public function index()
	{
		$param = $this->request->param();
		
		
		$CourtRegistrationService = new CourtRegistrationService();
		
		$data        = $CourtRegistrationService->adminCourtRegistrationList($param);
		
		$data->appends($param);
		
		$this->assign('courts', $data->items());
		$this->assign('page', $data->render());
		return $this->fetch();
	}
	
	/**
	 * 用户收藏
	 */
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
	
	
	
	/**
	 * 文章发布
	 * @adminMenu(
	 *     'name'   => '文章发布',
	 *     'parent' => 'index',
	 *     'display'=> false,
	 *     'hasView'=> false,
	 *     'order'  => 10000,
	 *     'icon'   => '',
	 *     'remark' => '文章发布',
	 *     'param'  => ''
	 * )
	 */
	public function publish()
	{
		$param           = $this->request->param();
		$AuctionSettledCourtModel = new AuctionSettledCourtModel();
		
		if (isset($param['ids']) && isset($param["yes"])) {
			$ids = $this->request->param('ids/a');
			
			$AuctionSettledCourtModel->where(['id' => ['in', $ids]])->update(['post_status' => 1, 'published_time' => time()]);
			
			$this->success("发布成功！", '');
		}
		
		if (isset($param['ids']) && isset($param["no"])) {
			$ids = $this->request->param('ids/a');
			
			$AuctionSettledCourtModel->where(['id' => ['in', $ids]])->update(['post_status' => 0]);
			
			$this->success("取消发布成功！", '');
		}
		
	}	
}
