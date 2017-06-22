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

use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;

class Order
{
    //订单的商品列表，也就是客户端传递过来的products参数
    protected $oProducts;

    //真实的商品信息(包括库存量)
    protected $products;

    protected $uid;

    //检测库存量 & 用户下单
    //主方法
    public function place($uid,$oProducts)
    {
        //oProducts(订单商品，二维数组，product_id和count) 和 products(从数据库中查询出来) 做对比
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();

        if (!$status['pass']) {
            $status['order_id'] = -1;
            return $status;
        }

        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);

        $order['pass'] = true;

        return $order;
    }

    private function createOrder($snap)
    {
        //事务开始
        Db::startTrans();

        try{

            $orderNo = $this->makeOrderNo();
            $order = new \app\api\model\Order();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);

            $order->save();

            $orderID = $order->id;
            $create_time = $order->create_time;

            foreach($this->oProducts as &$p){//& 才可以对数组中的值做赋值操作
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);//saveAll 保存的是一个数组

            //数据提交
            Db::commit();

            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time//下单时间
            ];
        }catch (Exception $ex){

            //中途出现异常，数据回滚
            Db::rollback();
            throw $ex;
        }
    }

    // 预检测并生成订单快照
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => ''
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());

        //将第一个商品的信息作为订单的头号信息
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];

        if(count($this->products) > 1){
            $snap['snapName'] .= '等';
        }

        return $snap;
    }

    //生成订单号
    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));

        return $orderSn;
    }

    private function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id','=',$this->uid)
            ->find();

        if(!$userAddress){
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        }

        return $userAddress->toArray();
    }

    // 根据订单查找真实商品
    private function getProductsByOrder($oProducts)
    {
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }

        // 为了避免 循环查询数据库
        $products = Product::all($oPIDs)
            //想要显示的字段
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();

        return $products;
    }

    //订单状态
    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,//订单总价
            'totalCount' => 0,
            'pStatusArray' => []
        ];
        foreach ($this->oProducts as $oProduct) {

            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);

            if (!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'], $pStatus);
        }

        return $status;
    }

    //订单中各个商品的状态
    private function getProductStatus($oPID, $oCount, $products)
    {
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0//订单中单个商品的总价
        ];

        for ($i = 0; $i < count($products); $i++) {
            if ($oPID == $products[$i]['id']) {
                $pIndex = $i;
            }
        }

        if ($pIndex == -1) {

            // 客户端传递的productid有可能根本不存在
            throw new OrderException(
                [
                    'msg' => 'id为' . $oPID . '的商品不存在，订单创建失败'
                ]);
        } else {

            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $product['price'] * $oCount;

            if ($product['stock'] - $oCount >= 0) {
                $pStatus['haveStock'] = true;
            }
        }

        return $pStatus;
    }

    public function checkOrderStack($orderID)
    {
        $oProducts = OrderProduct::where('order_id','=',$orderID)
            ->select();

        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);

        $status = $this->getOrderStatus();
        return $status;
    }
}