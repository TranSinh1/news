<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\loaitin;
use App\theloai;
class LoaiTinController extends Controller
{
    public function getDanhSach(){
    	$loaitin=loaitin::all();
    	return view('admin/loaitin/danhsach',['loaitin'=>$loaitin]);
    }
    public function getXoa($id){
    	$loaitin=loaitin::find($id);
    	$loaitin->delete();
    	return redirect('admin/loaitin/danhsach')->with('thongbao','Bạn đã xóa thành công');
    }
    public function getThem(){
    	$theloai=theloai::all();
    	return view('admin.loaitin.them',['theloai'=>$theloai]);
    }
    public function postThem(Request $request){
    	$this->validate($request,
    		[
    			"Ten"=>"required|unique:loaitin,Ten|min:1|max:100",
    			"TheLoai"=>"required",
    		],
    		[
    			"Ten.required"=>"Bạn chưa nhập tên loại tin",
    			"Ten.unique"=>"Tên đã tồn tại",
    			"Ten.min"=>"Tên loại tin có độ dài từ 1-100 ký tự",
    			"Ten.max"=>"Tên loại tin có độ dài từ 1-100 ký tự",
    			"TheLoai.required"=>"Bạn chưa chọn thể loại",
    		]);
    	$loaitin=new loaitin;
    	$loaitin->Ten=$request->Ten;
    	$loaitin->idTheLoai=$request->TheLoai;
    	$loaitin->TenKhongDau=changeTitle($request->Ten);
    	$loaitin->save();
    	return redirect('admin/loaitin/them')->with('thongbao','Thêm thành công');
    }
    public function getSua($id){
    	$theloai=theloai::all();
    	$loaitin=loaitin::find($id);
    	return view("admin.loaitin.sua",['theloai'=>$theloai,'loaitin'=>$loaitin]);
    }
    public function postSua(Request $request,$id){
    	$this->validate($request,
    		[
    			"Ten"=>"required|unique:loaitin,Ten|min:1|max:100",
    			"TheLoai"=>"required",
    		],
    		[
    			"Ten.required"=>"Bạn chưa nhập tên loại tin",
    			"Ten.unique"=>"Tên đã tồn tại",
    			"Ten.min"=>"Tên loại tin có độ dài từ 1-100 ký tự",
    			"Ten.max"=>"Tên loại tin có độ dài từ 1-100 ký tự",
    			"TheLoai.required"=>"Bạn chưa chọn thể loại",
    		]);
    	$loaitin=loaitin::find($id);
    	$loaitin->Ten=$request->Ten;
    	$loaitin->idTheLoai=$request->TheLoai;
    	$loaitin->TenKhongDau=changeTitle($request->Ten);
    	$loaitin->save();
    	return redirect('admin/loaitin/sua/'.$id)->with('thongbao','Sửa thành công');
    }
}
