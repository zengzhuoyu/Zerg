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
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;

class Pay extends BaseController
{

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];

    //向微信发送请求
    //发送预订单
    //返回支付参数
    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        
        $pay = new PayService($id);

        return $pay->pay();
    }

    //支付回调通知 微信每隔一段时间就会来调用
    //通知频率为15/15/30/180/1800/1800/1800/1800/3600 单位：秒
    public function receiveNotify()
    {
        //1.检查库存量，超卖
        //2.更新这个订单的status状态
        //3.减库存
        //如果成功处理，向微信返回成功处理的消息（微信就会终止持续请求）
        //否则，向微信返回没有成功处理（微信将持续发来请求）

        //特点：post、xml格式、不会携带参数
        $notify = new WxNotify();
        $notify->handle();
    }
}






















