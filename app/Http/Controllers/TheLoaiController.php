<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\theloai;
use App\loaitin;

class TheLoaiController extends Controller
{
    public function getDanhSach(){
    	$theloai=theloai::all();
    	return view('admin.theloai.danhsach',['theloai'=>$theloai]);
    }
     public function getSua($id){
    	$theloai=theloai::find($id);
    	return view('admin.theloai.sua',['theloai'=>$theloai]);
    }
    public function postSua(Request $request,$id){
    	$theloai=theloai::find($id);
    	$this->validate($request,
    		[
    			'Ten'=>'required|unique:theloai,Ten|min:3|max:100'
    		],
    		[
    			'Ten.required'=>'Bạn chưa nhập tên thể loại',
    			'Ten.unique'=>'Tên thể loại đã tồn tại',
    			'Ten.min'=>'Tên thể loại phải có độ dài từ 3-100 ký tự',
    			'Ten.max'=>'Tên thể loại phải có độ dài từ 3-100 ký tự',
    		]);
    	$theloai->Ten=$request->Ten;
    	$theloai->TenKhongDau=changeTitle($request->Ten);
    	$theloai->save();
    	return redirect('admin/theloai/sua/'.$id)->with('thongbao','Sửa thành công');
    }
     public function getThem(){
    	return view('admin.theloai.them');
    }
    public function postThem(Request $request){
    	$this->validate($request,
    		[
    			'Ten'=>'required|min:3|max:100'
    		],
    		[
    			'Ten.required'=>'Bạn chưa nhập tên thể loại',
    			'Ten.min'=>'Tên thể loại có độ dài từ 3-100 ký tự',
    			'Ten.max'=>'Tên thể loại có độ dài từ 3-100 ký tự',
    		]);
    	$theloai=new theloai;
    	$theloai->Ten=$request->Ten;
    	$theloai->TenKhongDau=changeTitle($request->Ten);
    	$theloai->save();
    	return redirect('admin/theloai/them')->with('thongbao','Thêm thành công');
    }
    public function getXoa($id){
    	$theloai=theloai::find($id);
        $loaitin=loaitin::where('idTheLoai',$id);
        $loaitin->delete();
    	$theloai->delete();
    	return redirect('admin/theloai/danhsach')->with('thongbao','Bạn đã xóa thành công');
    }
}
