<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\user\controller;

use cmf\controller\UserBaseController;
use app\user\model\AuctionMyConcernModel;
use app\user\service\UserService;

use think\Db;

class ConcernController extends UserBaseController
{

    /**
     * 个人中心我的收藏列表
     */
    public function index()
    {
    	$param = $this->request->param();
    	
    	
    	$userService = new UserService();
    	$user              = cmf_get_current_user();
    	$data        = $userService->userConcernList($param);
    	
    	$data->appends($param);
    	$this->assign($user);
    	$this->assign('lists', $data->items());
    	$this->assign('page', $data->render());
    	return $this->fetch();
    }

    /**
     * 用户取消收藏
     */
    public function delete()
    {
        $id                = $this->request->param("id", 0, "intval");
        $userConcernModel = new AuctionMyConcernModel();
        $data              = $userConcernModel->deleteConcern($id);
        if ($data) {
            $this->success("取消关注成功！");
        } else {
            $this->error("取消关注失败！");
        }
    }

    /**
     * 用户收藏
     */
    public function add()
    {
        $data   = $this->request->param();
        $id    = $this->request->param('id', 0, 'intval');


        $findFavoriteCount = Db::name("auction_my_concern")->where([
            'project_id'  => $id,
            'user_id'    => cmf_get_current_user_id()
        ])->count();

        if ($findFavoriteCount > 0) {
            $this->error("您已关注过啦");
        }

        Db::name("auction_my_concern")->insert([
            'user_id'     => cmf_get_current_user_id(),
        	'project_id'       => $id,
            'created_time' => time()
        ]);

        $this->success('关注成功');

    }
}