<?php

namespace App;
use App\User;
// use App\PublicMsg;
use App\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PublicNotify extends Model
{
    // 論理削除を行う
    use SoftDeletes;
    protected $table = 'public_notifies';
    //
    protected $fillable = ['public_board_id', 'user_id', 'read_flg'];

    // public function public_msg()
    // {
    //   return $this->belongsTo('App\PublicMsg');
    // }
    public function projects()
    {
      return $this->belongsTo('App\PublicMsg');
      // return $this->belongsTo('App\PublicMsg')->withTrashed();
    }
    public function user()
    {
      return $this->belongsTo('App\User');
      // return $this->belongsTo('App\User')->withTrashed();
    }

}
//
