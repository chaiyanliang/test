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
use app\user\model\AuctionMyRecordModel;
use app\user\service\UserService;
use think\Db;

class RecordController extends UserBaseController
{

    /**
     * 个人中心我的收藏列表
     */
	public function index()
	{
		$param = $this->request->param();
		
		
		$userService = new UserService();
		$user              = cmf_get_current_user();
		$data        = $userService->userRecordList($param);
		
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
        $myRecordModel = new AuctionMyRecordModel();
        $data              = $myRecordModel->deleteRecord($id);
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
        $result = $this->validate($data, 'Record');

        if ($result !== true) {
            $this->error($result);
        }

        $id    = $this->request->param('id', 0, 'intval');
        $table = $this->request->param('table');


        $findFavoriteCount = Db::name("auction_my_record")->where([
            'project_id'  => $id,
            'table_name' => $table,
            'user_id'    => cmf_get_current_user_id()
        ])->count();

        if ($findFavoriteCount > 0) {
            $this->error("您已收藏过啦");
        }


        $title       = base64_decode($this->request->param('title'));
        $url         = $this->request->param('url');
        $url         = base64_decode($url);
        $description = $this->request->param('description', '', 'base64_decode');
        $description = empty($description) ? $title : $description;
        Db::name("user_favorite")->insert([
            'user_id'     => cmf_get_current_user_id(),
            'title'       => $title,
            'description' => $description,
            'url'         => $url,
            'object_id'   => $id,
            'table_name'  => $table,
            'create_time' => time()
        ]);

        $this->success('收藏成功');

    }
}