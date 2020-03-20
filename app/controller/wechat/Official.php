<?php


namespace app\controller\wechat;


use app\exceptions\ControllerException;
use app\model\UserModel;
use app\UserController;
use EasyWeChat\Factory;
use think\helper\Str;

class Official extends UserController
{
    protected $official;

    protected function initialize()
    {
        $this->official = Factory::officialAccount(config('wechat.official'));
    }

    /**
     * 服务器认证
     * 参考文档：https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Access_Overview.html
     * @return mixed
     */
    public function serverValidate()
    {
        $response = $this->official->server->serve();
        return $response->send();
    }


    /**
     * 请求授权
     * @return mixed
     */
    public function auth()
    {

        return $this->official->oauth
            ->scopes([config('wechat.official.scope', 'snsapi_base')])
            ->redirect($this->request->domain().'/wechat/official/callback')->send();
    }

    /**
     * 授权回调
     * @return \think\response\Redirect
     * @throws ControllerException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function callback()
    {
        if ($user = $this->official->oauth->user()) {
            $isBaseInfo = config('wechat.official.scope', 'snsapi_base') == 'snsapi_base';
            $userModel = UserModel::where('wx_openid',$user->getId())->find();
            if (!$userModel){
                // 创建微信用户
                $userModel = new UserModel();
                $userModel->wx_openid = $user->getId();
                $userModel->username = 'wx_' . Str::random(3) . time();
                $userModel->password = Str::random(8);
            }
            // 更新已存在用户或新建用户的昵称和头像
            if (!$isBaseInfo){
                $userModel->nickname = $user->getNickname();
                $userModel->avatar = $user->getAvatar();
            }
            $userModel->save(); // 保存到数据库
            // 保存登陆状态
            $token = $this->auth->saveLogin($userModel);
            $targetUrl = config('wechat.official.target_url',false);
            $targetUrl = $targetUrl.'?token=' .$token;
            if (!$targetUrl) throw new ControllerException('未定义回调目标url');
            return redirect($targetUrl);
        } else {
            throw new ControllerException('授权用户不存在');
        }
    }
}