<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tintuc extends Model
{
    //
    protected $table="tintuc";
    public function loaitin(){
    	return $this->belongsTo('App\loaitin','idLoaiTin','id');
    }
    public function comment(){
    	return $this->hasMany('App\comment','idTinTuc','id');
    }
}
