<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Project;
use App\DirectMsgsBoard;
use App\DirectMsgs;
use App\DirectNotify;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreMessageRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ApplyForProjectController extends Controller
{
    public function applied(){
      // 応募済み案件一覧画面

      $auther_id = Auth::id();

      $direct_msgs = DirectMsgsBoard::where('sender_id', $auther_id)
                     ->whereNotNull('project_id')
                     ->with('project')
                     ->orderBy('updated_at','desc')
                     ->paginate(10);

      return view('mypages.applied', compact('direct_msgs'));

    }

    public function apply_form($id){
      // 応募フォーム
      // 数値でなかった場合
      if(!ctype_digit($id)){
        return back()->with('flash_message', __('Invalid operation was performed.'));
        }

        $project = Project::find($id);
        $user = $project->user;

        $projects = Project::with('user')->get();

      return view('projects.apply', compact('project', 'user'));
    }

    public function apply(StoreMessageRequest $request, $id){
      if(!ctype_digit($id)){
        return back()->with('flash_message', __('Invalid operation was performed.'));
        }

       // 応募フォームの内容をバリデーション
       $request->validated();

       $project = Project::find($id);
       $recruiter_id = $project->user_id;

         // DirectMsgsBoardの処理ーーーーーーー
         // メッセージを保管するボードを新規作成
         $fillDataBoard = array(
           'created_at'  => Carbon::now(),
           'updated_at'  => Carbon::now(),
           'reciever_id' => $recruiter_id,
           'sender_id' => Auth::id(),
           'project_id' => $id,
         );

         $direct_msgs_boards = new DirectMsgsBoard;
         $direct_msgs_boards->fill($fillDataBoard)->save();

         // DirectMsgの処理ーーーーーーー
         // 先ほど作ったボードにメッセージを登録

         $fillDataMsg = $request->all();
         $fillDataMsg += array(
           'send_date'  => Carbon::now(),
           'created_at' => Carbon::now(),
           'updated_at' => Carbon::now(),
           'sender_id' => Auth::id(),
           'board_id' => $direct_msgs_boards->id //直前にinsertしたDirectMsgsBoardのID
         );

         $direct_msgs = new DirectMsgs;
         $direct_msgs->fill($fillDataMsg)->save();

       // 既読・未読の処理ーーーーーーー
       // sender_idを既読・reciever_idを未読として登録
       // sender
       $direct_notify = new DirectNotify;
       $direct_notify->direct_board_id = $direct_msgs_boards->id; //直線に登録したボードのID
       $direct_notify->user_id = Auth::id();
       $direct_notify->read_flg = 1; //既読
       $direct_notify->save();

       // reciever
       $direct_notify = new DirectNotify;
       $direct_notify->direct_board_id = $direct_msgs_boards->id; //直線に登録したボードのID
       $direct_notify->user_id = $recruiter_id;
       $direct_notify->read_flg = 0; //未読
       $direct_notify->save();


       // 応募済み：project_idが入っている物だけを取得
       $auther_id = Auth::id();
       $direct_msgs = DirectMsgsBoard::where('sender_id', $auther_id)
                      ->whereNotNull('project_id')
                      ->orderBy('updated_at','desc')
                      ->with('project')
                      ->paginate(10);

       // 応募済み案件一覧へリダイレクトさせる
       Session::flash('flash_message', __('応募しました')); //session表示用
       return view('mypages.applied', compact('direct_msgs'));

    }
}
