<?php
//
namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helper\Token;
use App\Models\v2\Member;
use App\Models\v2\RegFields;
use App\Models\v2\Configs;
use App\Models\v2\Features;
use Log;

class UserController extends Controller
{
    /**
     * POST /user/signin
     */
    public function signin()
    {
        $rules = [
            'username' => 'required|string',
            'password' => 'required|min:6|max:20'
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Member::login($this->validated);
        return $this->json($data);
    }

    /**
     * POST /user/signup-email
     */
    public function signupByEmail()
    {
        $rules = [
            'device_id'     => 'string',
            'username'      => 'required|min:3|max:25|alpha_num',
            'email'         => 'required|email',
            'password'      => 'required|min:6|max:20',
            'invite_code'   => 'integer', 
        ];

        if($res = Features::check('signup.default'))
        {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Member::createMember($this->validated);
        return $this->json($data);
    }

    /**
     * POST /user/signup-mobile
     */
    public function signupByMobile()
    {
        if($res = Features::check('signup.mobile'))
        {
            return $this->json($res);
        }

        $rules = [
            'device_id'     => 'string',
            'password'      => 'required|min:6|max:20',
            'mobile'        => 'required|string',
            'code'          => 'required|string|digits:6',
            'invite_code'   => 'integer', 
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Member::createMemberByMobile($this->validated);
        return $this->json($data);
    }

    /**
     * POST /user/verify-mobile
     */
    public function verifyMobile()
    {
        $rules = [
            'mobile' => 'required|string',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Member::verifyMobile($this->validated);
        return $this->json($data);
    }

    /**
     * POST /user/send-code
     */
    public function sendCode()
    {
        $rules = [
            'mobile' => 'required|string',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Member::sendCode($this->validated);
        return $this->json($data);
    }

    /**
     * POST /user/profile
     */
    public function profile()
    {
        $data = Member::getMemberByToken();
        return $this->json($data);
    }

    /**
     * POST /user/update-profile
     */
    public function updateProfile()
    {
        $rules = [
            'values'        => 'json',
            'nickname'      => 'string|max:25',
            'gender'        => 'integer|in:0,1,2',
            'avatar_url'    => 'string'
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Member::updateMember($this->validated);
        return $this->json($data);
    }

    /**
     * POST /user/update-password
     */
    public function updatePassword()
    {
        $rules = [
            'old_password' => 'required|min:6|max:20',
            'password'     => 'required|min:6|max:20'
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Member::updatePassword($this->validated);
        return $this->json($data);
    }

    /**
     * POST /user/reset-password-mobile
     */
    public function resetPasswordByMobile()
    {
        $rules = [
            'mobile'   => 'required|string',
            'code'     => 'required|string|digits:6',
            'password' => 'required|min:6|max:20'
        ];

        if($res = Features::check('findpass.default'))
        {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Member::updatePasswordByMobile($this->validated);
        return $this->json($data);
    }

    /**
     * POST /user/reset-password-email
     */
    public function resetPasswordByEmail()
    {
        $rules = [
            'email' => 'required|email'
        ];

        if($res = Features::check('findpass.default'))
        {
            return $this->json($res);
        }

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Member::resetPassword($this->validated);
        return $this->json($data);
    }

    /**
     * POST /user/auth
     */
    public function auth()
    {
        $rules = [
            'device_id'     => 'string',
            'vendor'        => 'required|integer|in:1,2,3,4,5',
            'access_token'  => 'string',
            'js_code'       => 'string',
            'open_id'       => 'string',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Member::auth($this->validated);
        return $this->json($data);
    }

    /**
     * POST /ecapi.user.profile.fields
     */
    public function fields()
    {
        $data = RegFields::findAll();
        return $this->json($data);
    }


    /**
     * GET /user/web
     */
    public function webOauth()
    {
        $rules = [
                    'vendor'        => 'required|integer|in:1,2,3,4',
                    'referer'       => 'required|url',
                 ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = Member::webOauth($this->validated);
        if (isset($data['error'])) {
            return $this->json($data);
        }
        return redirect($data);
    }
    /**
     * GET /ecapi.auth.web.callback/:vendor
     */
     public function webCallback($vendor)
     {
         $data = Member::webOauthCallback($vendor);
          if (isset($data['error'])) {
              return $this->json($data);
          }

          if (isset($_GET['referer'])) {
              return redirect(urldecode($_GET['referer']).'?token='.$data['token'].'&openid='.$data['openid']);
          }
          return $this->json(['token' => $data]);
     }

}
