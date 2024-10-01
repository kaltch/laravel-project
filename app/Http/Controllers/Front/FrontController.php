<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller
{
    public function home() {
        return view('Front.home');
    }

    public function login() {
        return view('Front.login');
    }

    public function doLogin(Request $req) {
        if (captcha_check($req->code) == false)
        {
            return back()->withInput()->withErrors(["code" => "驗證碼錯誤"]);
            exit;
        }

        $member = (new Member())->getMember($req->memberId, $req->pwd);

        if (empty($member))
        {
            return back()->withInput()->withErrors(["error" => "帳號或密碼錯誤"]);
            exit;
        }
        session()->put("member", $member->name); // 帳號
        session()->put("memberId", $member->id);
        session()->save();
        return redirect("/");

    }

    public function signup() {
        return view('Front.signup');
    }

    public function doSignup(Request $req) {
        $member = new Member();
        $member->email = $req->email;
        $member->member_id = $req->memberId;
        $member->password = bcrypt($req->pwd);
        $result = $member->save();

        if($result) {
            Session::flash("message", "註冊成功");
            return redirect("/");
        } else {
            return back()->withInput()->withErrors(["error" => "註冊失敗"]);
            exit;
        }
    }

    public function logout() {
        session()->forget('member');
        session()->forget('memberId');
        session()->save();
        return redirect("/");
    }
    
    public function error404() {
        return view('Front.error404');
    }

    public function error400() {
        return view('Front.error400');
    }
}
