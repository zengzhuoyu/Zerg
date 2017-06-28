<?php
/**
 * Created by 七月.
 * Author: 七月
 * 微信公号：小楼昨夜又秋风
 * 知乎ID: 七月在夏天
 * Date: 2017/2/23
 * Time: 1:48
 */

namespace app\api\service;

use app\lib\enum\OrderStatusEnum;
use think\Loader;
use think\Db;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\model\Product;
use think\Exception;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($data,&$msg)
    {
        if ($data['result_code'] == 'SUCCESS') {
            $orderNo = $data['out_trade_no'];

            Db::startTrans();

            try {
                $order = OrderModel::where('order_no', '=', $orderNo)
                    ->lock(true)
                    ->find();

                if ($order->status == OrderStatusEnum::UNPAID) {

                    $service = new OrderService();
                    $stockStatus = $service->checkOrderStock($order->id);
                    if ($stockStatus['pass']) {//如果通过库存量检测
                        $this->updateOrderStatus($order->id, true);
                        $this->reduceStock($stockStatus);
                    } else {
                        $this->updateOrderStatus($order->id, false);
                    }

                    Db::commit();
                }
                
            } catch (Exception $ex) {

                Db::rollback();
                Log::error($ex);
                // 如果出现异常，向微信返回false，请求重新发送通知
                return false;
            }
        }

        return true;
    }

    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['pStatusArray'] as $singlePStatus) {
            Product::where('id', '=', $singlePStatus['id'])
                ->setDec('stock', $singlePStatus['count']);
        }
    }

    private function updateOrderStatus($orderID, $success)
    {
        $status = $success ?
            OrderStatusEnum::PAID :
            OrderStatusEnum::PAID_BUT_OUT_OF;

        OrderModel::where('id', '=', $orderID)
            ->update(['status' => $status]);
    }
}