<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Project;
use App\PublicMsg;
use App\DirectMsgsBoard;
use App\DirectMsgs;
use App\PublicNotify;
use App\DirectNotify;
// use App\EmailReset;
use Illuminate\Support\Facades\Auth;
// use App\Http\Requests\StoreProjectPost;
// use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\StoreProfileRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;

class ChangeProfileController extends Controller
{
    //

    public function profile_edit_form($id){
      if(!ctype_digit($id)){
        return redirect('/projects/all')->with('flash_message', __('Invalid operation was performed.'));
      }
      // プロフィール編集画面を表示
      $user = User::find($id);

      return view('users.profile_edit_form', compact('user'));
    }

    public function profile_edit_post(StoreProfileRequest $request, $id){
      if(!ctype_digit($id)){
        return redirect('/projects/all')->with('flash_message', __('Invalid operation was performed.'));
        }
        // ユーザーのプロフィール更新処理
        $user = User::find($id);
        // バリデーション
        $request->validated();

        // 更新処理ーーーー
        $user->name = $request->name;
        $user->self_introduction = $request->self_introduction;
        $user->updated_at = Carbon::now();
        // 元の画像を削除
        $path_prev = $user->profile_icon;
        $pathdel = storage_path() . '/app/public/avatar/'.$path_prev;
        \File::delete($pathdel);
        // 新しい画像はstorage配下へ保存
        $path = $request->profile_icon->store('public/avatar');
        $user->profile_icon = basename($path);
        $user->save();
        // ーーーーーーーー

        // profile画面へ遷移させる
        return view('users.profile', compact('user','file'))->with('flash_message', __('Registered.'));
    }
}
