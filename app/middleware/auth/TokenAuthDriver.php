<?php


namespace app\middleware\auth;




use app\exceptions\MiddlewareException;
use think\facade\Cache;
use think\helper\Str;

class TokenAuthDriver extends AuthDriver
{

    /**
     * 登录凭证
     * @var string
     */
    protected $token = '';

    public function __construct($request, $prefix)
    {
        parent::__construct($request, $prefix);
        $this->token = $this->request->header('authorization');
    }

    /**
     * @inheritDoc
     */
    public function sessionUser()
    {
        $user = null;
        if ($this->token){
            $user = Cache::get($this->generateTokenName($this->token),null);
        }
        return $user;
    }

    private function generateTokenName($token){
        return $this->prefix.':'.$token;
    }

    /**
     * @inheritDoc
     */
    public function saveLogin(AuthUserModelInterface $authUserModel)
    {
        $token = sha1(Str::random(8).time());
        $key = $this->generateTokenName($token);
        if (Cache::has($key)){
            throw new MiddlewareException('生成token重复，请重试');
        }
        Cache::set($key,$authUserModel,config('session.expire'));

        return $token;
    }

    /**
     * @inheritDoc
     */
    public function logout()
    {
        if ($this->token){
            Cache::delete($this->generateTokenName($this->token));
        }
    }
}