<?php

namespace App\Http\Controllers\Api;

use  Illuminate\Support\Str;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Requests\Api\CaptchaRequest;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-'.Str::random(15);
        $phone = $request->phone;

        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);
        \Cache::put($key, ['phone' => $phone, 'code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline(),
        ];
        return response()->json($result)->setStatusCode(201);
    }
}

https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxbcf29fa8f0748796&redirect_uri=http://larabbs.test&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect

https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxbcf29fa8f0748796&secret=a0bb97d1bc63c0786170402152a64d1e&code=021fYGnZ1HBnwW0ijDoZ158HnZ1fYGn2&grant_type=authorization_code

https://api.weixin.qq.com/sns/userinfo?access_token=35_LpHctyfgOb6h38gv6yIQJy8w-_7eReLlDQs2YQfDOrXPXWSXBN92UwYo5lFw3a8YyzvwUlSk49sR-0DTzGPamQ&openid=opwtp02GAfgxkKeukm9OHUmh1RCc&lang=zh_CN

$code = '021fr35r0pKhRk1S283r0Mz35r0fr35c';
$driver = Socialite::driver('weixin');
$response = $driver->getAccessTokenResponse($code);
$driver->setOpenId($response['openid']);
$oauthUser = $driver->userFromToken($response['access_token']);
