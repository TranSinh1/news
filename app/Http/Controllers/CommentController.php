<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\comment;
use App\tintuc;
use Illuminate\Support\Facades\Auth;
class CommentController extends Controller
{
    public function getXoa($id,$idTinTuc){
    	$comment=comment::find($id);
    	$comment->delete();
    	return redirect('admin/tintuc/sua/'.$idTinTuc)->with('thongbao','Bạn đã xóa comment thành công');
    }
    public function postComment($id,Request $request){
    	$idTinTuc=$id;
    	$tintuc=tintuc::find($id);
    	$comment=new comment;
    	$comment->idTinTuc=$idTinTuc;
    	$comment->idUser=Auth::user()->id;
    	$comment->NoiDung=$request->NoiDung;
    	$comment->save();
    	return redirect("tintuc/".$id."/".$tintuc->TieuDeKhongDau.".html")->with('thongbao','Bạn vừa comment');
       
    }
}

