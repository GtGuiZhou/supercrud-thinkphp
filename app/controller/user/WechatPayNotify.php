<?php


namespace app\controller\user;


use app\model\PayOrderModel;
use EasyWeChat\Payment\Application;

class WechatPayNotify
{
    public function paid(Application $payment)
    {
        $response = $payment->handlePaidNotify(function($message, $fail){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = PayOrderModel::where('out_trade_no',$message['out_trade_no'])->find();

            if (!$order || $order->status != 'unpaid') { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $order->paid_at = time(); // 更新支付时间为当前时间
                    $order->status = 'paid';

                    // 用户支付失败
                } elseif (array_get($message, 'result_code') === 'FAIL') {
                    $order->status = 'paid_fail';
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        $response->send(); // return $response;
    }
}