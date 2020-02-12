<?php
declare (strict_types=1);

namespace app\model;

use app\service\RedLock;
use EasyWeChat\Payment\Application;
use think\Db;
use think\helper\Str;
use think\Model;

/**
 * @mixin think\Model
 */
class PayOrderModel extends Model
{

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;
    /**
     * @var RedLock
     */
    protected $redlock;

    /**
     * @var Application
     */
    protected $wechatPay;

    protected $readonly = [
        'out_trade_no','body','trade_type','pay_type','total_fee','create_time'
    ];


    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->app = app();
        $this->redlock = $this->app->redlock;
        $this->wechatPay = $this->app->wechatPay;
    }

    public static function onBeforeInsert(self $model)
    {

        $model->generateOutTradeNo();
    }

    /**
     * 构造支付订单,并保存到模型属性中
     * @return string
     */
    public function generateOutTradeNo()
    {
        $key = 'PayOutTradeNoLock';
        $unlockKey = $this->redlock->lock($key, 5000);
        $out_trade_no = date('YmdHis') . Str::random(18, 1);
        // 保存到属性中
        $this->setAttr('out_trade_no', $out_trade_no);
        $this->redlock->unlock($unlockKey);
        return $out_trade_no;
    }
}
