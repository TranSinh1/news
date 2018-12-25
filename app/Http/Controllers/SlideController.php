<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\slide;
class SlideController extends Controller
{
    public function getDanhSach(){
    	$slide=slide::all();
    	return view('admin/slide/danhsach',['slide'=>$slide]);
    }
    public function getThem(){
    	return view('admin/slide/them');
    }
    public function postThem(Request $request){
    	$this->validate($request,
    		[
    			'Ten'=>'required',
    			'NoiDung'=>'required'
    		],
    		[
    			'Ten.required'=>'Bạn chưa nhập Tên',
    			'NoiDung.required'=>'Bạn chưa nhập nội dung'
    		]);
    	$slide=new slide;
    	$slide->Ten=$request->Ten;
    	$slide->NoiDung=$request->NoiDung;
    	if($request->has('link'))
    		{
    			$slide->link=$request->link;
    		}
    	if($request->hasFile('Hinh')){
    		$file=$request->Hinh;
    		$name=$file->getClientOriginalName();
    		$duoi=$file->getClientOriginalExtension();
    		if($duoi!='jpg'&&$duoi!='png'&&$duoi!='jpeg'){
    			return redirect("admin/tintuc/them")->with("loi","Bạn chỉ được chọn file đuôi jpg,png,jpeg");
    		}
    		$Hinh=str_random(4)."_".$name;
    		while(file_exists("upload/slide/".$Hinh)){
    			$Hinh=str_random(4)."_".$name;
    		}
    		$file->move("upload/slide",$Hinh);
    		$slide->Hinh=$Hinh;
    		
    	}
    	else{
    		$slide->Hinh="";
    	}
    	$slide->save();
    	return redirect("admin/slide/them")->with("thongbao","Bạn đã thêm thành công");
    }
    public function getSua($id){
    	$slide=slide::find($id);
    	return view("admin.slide.sua",['slide'=>$slide]);
    }
    public function postSua(Request $request,$id){
    	$this->validate($request,
    		[
    			'Ten'=>'required',
    			'NoiDung'=>'required'
    		],
    		[
    			'Ten.required'=>'Bạn chưa nhập Tên',
    			'NoiDung.required'=>'Bạn chưa nhập nội dung'
    		]);
    	$slide=slide::find($id);
    	$slide->Ten=$request->Ten;
    	$slide->NoiDung=$request->NoiDung;
    	
    	if($request->has('link'))
    		{
    			$slide->link=$request->link;
    		}
    	if($request->hasFile('Hinh')){
    		$file=$request->Hinh;
    		$name=$file->getClientOriginalName();
    		$duoi=$file->getClientOriginalExtension();
    		if($duoi!='jpg'&&$duoi!='png'&&$duoi!='jpeg'){
    			return redirect("admin/tintuc/them")->with("loi","Bạn chỉ được chọn file đuôi jpg,png,jpeg");
    		}
    		$Hinh=str_random(4)."_".$name;
    		while(file_exists("upload/slide/".$Hinh)){
    			$Hinh=str_random(4)."_".$name;
    		}
    		$file->move("upload/slide",$Hinh);
    		unlink("upload/slide/".$slide->Hinh);
    		$slide->Hinh=$Hinh;
    		
    	}
    	
    	$slide->save();
    	return redirect("admin/slide/them")->with("thongbao","Bạn đã sửa thành công");
    }
    public function getXoa($id){
    	$slide=slide::find($id);
    	$slide->delete();
    	unlink("upload/slide/".$slide->Hinh);
    	return redirect("admin/slide/danhsach")->with("thongbao","Bạn đã xóa thành công");
    }
}
