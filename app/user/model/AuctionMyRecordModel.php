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
namespace app\user\model;

use think\Db;
use think\Model;

class AuctionMyRecordModel extends Model
{
	protected $type = [
			'more' => 'array',
	];
	
	public function records()
    {
        $userId        = cmf_get_current_user_id();
        $userQuery     = Db::name("AuctionMyRecord");
        $favorites     = $userQuery->where(['user_id' => $userId])->order('id desc')->paginate(10);
        $data['page']  = $favorites->render();
        $data['lists'] = $favorites->items();
        return $data;
    }

    public function deleteRecord($id)
    {
        $userId           = cmf_get_current_user_id();
        $userQuery        = Db::name("AuctionMyRecord");
        $where['id']      = $id;
        $where['user_id'] = $userId;
        $data             = $userQuery->where($where)->delete();
        return $data;
    }

}