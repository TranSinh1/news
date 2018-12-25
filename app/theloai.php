<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class theloai extends Model
{
    //
    protected $table="theloai";
    public function loaitin(){
    	return $this->hasMany('App\loaitin','idTheLoai','id');
    }
    public function tintuc(){
    	return $this->hasManyThrough('App\tintuc','App\loaitin','idTheLoai','idLoaiTin','id');
    }
}
