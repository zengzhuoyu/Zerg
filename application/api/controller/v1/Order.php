<?php
/**
 * Created by 七月.
 * Author: 七月
 * 微信公号：小楼昨夜又秋风
 * 知乎ID: 七月在夏天
 * Date: 2017/2/22
 * Time: 21:52
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\OrderPlace;

class Order extends BaseController
{

    protected $beforeActionList = [

        'checkExclusiveScope' => ['only' => 'placeOrder']
    ];

    public function placeOrder()
    {
        (new OrderPlace())->goCheck();
    }

}






















