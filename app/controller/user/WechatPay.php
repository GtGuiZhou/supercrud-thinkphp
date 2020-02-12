<?php


namespace app\controller\user;


use app\UserController;
use EasyWeChat\Payment\Application;
use think\facade\Db;

class WechatPay extends UserController
{

    public function unify(Application $payment,$trade_type)
    {
        $data = $this->request->post();
        $data = $this->validate($data,[
            'total_fee' => 'require|number|min:0.01',
            'trade_type' => 'require', // 支付类型
            'body' => 'require',
            'openid' => 'require' // 由于小程序,公众号的openid不相同,因此由前端提交openid
        ]);
        $res = Db::transaction(function () use ($payment,$data){
            // 在数据库中创建订单
            $payOrder = $this->user->payOrder()->save([
                'body' => $data['body'],
                'total_fee' => $data['total_fee'],
                'trade_type' => $data['trade_type'],
                'pay_type' => 'wechat'
            ]);
            // 发送支付申请
           return $payment->order->unify([
                'body' => $data['body'],
                'out_trade_no' => $payOrder['out_trade_no'],
                'total_fee' => $data['total_fee'],
                'spbill_create_ip' => $this->request->ip(), // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
                'notify_url' => url('user/WechatPayNotify/paid',[],false,true), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => $data['trade_type'], // 请对应换成你的支付方式对应的值类型
                'openid' => $data['openid'],
            ]);
        });

        return json($res);
    }


}