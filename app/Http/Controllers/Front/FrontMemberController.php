<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberWishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontMemberController extends Controller
{
    public function getWishlist()
    {
        $mwi = new MemberWishlistItem();
        $memberId = session()->get('memberId');
        $list = array();

        $list = $mwi->getListByMemberId($memberId);
        return view('Front.member.wishlist', ['list' => $list]);
    }

    public function deleteFromWishlist(Request $req)
    {
        $mwi = new MemberWishlistItem();
        // update member_wishlist_item set active = '' where id = 'id'
        $item = $mwi::find($req->id);

        if (isset($item)) {
            $item->active = '';
            $item->save();
            Session::flash('message', '刪除成功');
            return response()->json([
                'success' => true,
                'message' => '操作成功',
                'data' => $item->id
            ], 200);
        } else {
            Session::flash('errorMessage', '刪除失敗');
            return response()->json([
                'success' => false,
                'message' => '操作失敗',
                'data' => $item->id
            ], 200);
        }
    }

    public function getProfile()
    {
        $member = new Member();
        $memberId = session()->get('memberId');
        $data = $member->getById($memberId);
        return view('Front.member.profile', ['data' => $data]);
    }

    public function updateProfile() {}
    public function updatePassword(Request $req)
    {
        $member = new Member();
        $name = session()->get('member');

        $password = $req->password;
        $newPassword = $req->newPassword;
        $m = $member->getMember($name, $password);
        if (!isset($m)) {
            Session::flash('errorMessage', '密碼更新失敗');
            return back()->withInput()->withErrors(['errorF02' => '密碼錯誤']);
            exit;
        }

        if ($member->updatePassword($name, $newPassword)) {
            Session::flash('message', '密碼更新成功');
            return redirect('/member/profile');
        } else {
            Session::flash('errorMessage', '密碼更新失敗');
            return back()->withInput()->withErrors(['errorF02' => '更新失敗，請洽系統管理員']);
            exit;
        }
    }
}
