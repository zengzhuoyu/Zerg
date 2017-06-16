<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/7
 * Time: 9:46
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;
//use think\Exception;

class Banner
{

    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @$id banner的id号
     */
    public function getBanner($id)
    {
        (new IDMustBePositiveInt())->goCheck();

//        $banner = BannerModel::with(['items','items.img'])->find($id);

        $banner = BannerModel::getBannerById($id);
//        $banner->hidden(['update_time','delete_time']);
//        $banner->visible(['id']);

        if(!$banner){
            throw new BannerMissException();
//            throw new Exception('内部错误');
        }

        return $banner;

    }
}