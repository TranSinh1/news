<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\loaitin;
use App\tintuc;
use App\theloai;
use App\comment;
class TinTucController extends Controller
{
    public function getDanhSach(){
    	$tintuc=tintuc::orderBy('id','DESC')->get();
    	return view('admin.tintuc.danhsach',['tintuc'=>$tintuc]);
    }
    public function getThem(){
    	$theloai=theloai::all();
    	$loaitin=loaitin::all();
    	return view('admin.tintuc.them',['theloai'=>$theloai,'loaitin'=>$loaitin]);
    }
    public function postThem(Request $request){
    	$this->validate($request,
    		[
    			'idLoaiTin'=>'required',
    			'TieuDe'=>'required|min:3|unique:tintuc,TieuDe',
    			'TomTat'=>'required',
    			'NoiDung'=>'required',
    		],
    		[
    			'LoaiTin.required'=>'Bạn chưa chọn loại tin',
    			'TieuDe.required'=>'Bạn chưa nhạp tiêu đề',
    			'TieuDe.min'=>'Tiêu đề phải có ít nhất 3 kí tự',
    			'TieuDe.unique'=>'Tiêu đề đã tồn tại',
    			'NoiDung.required'=>'Bạn chưa nhập nội dung',
    		]);
    	$tintuc=new tintuc;
    	$tintuc->TieuDe=$request->TieuDe;
    	$tintuc->TieuDeKhongDau=changeTitle($request->TieuDe);
    	$tintuc->TomTat=$request->TomTat;
    	$tintuc->NoiDung=$request->NoiDung;
    	$tintuc->NoiBat=$request->NoiBat;
    	$tintuc->idLoaiTin=$request->idLoaiTin;
    	$tintuc->SoLuotXem=0;
    	if($request->hasFile('Hinh')){
    		$file=$request->file('Hinh');
    		$name=$file->getClientOriginalName();
    		$duoi=$file->getClientOriginalExtension();
    		if($duoi!="jpg"&&$duoi!="png"&&$duoi!="jpeg"){
    			return redirect("admin/tintuc/them")->with("loi","Bạn chỉ được chọn file đuôi jpg,png,jpeg");
    		}
    		$Hinh=str_random(4)."_".$name;
    		while(file_exists("upload/tintuc/".$Hinh)){
    			$Hinh=str_random(4)."_".$name;
    		}
    		
    		$file->move("upload/tintuc",$Hinh);
    		$tintuc->Hinh=$Hinh;
    	}
    	else{
    		$tintuc->Hinh="";
    	}
    	$tintuc->save();
    	return redirect("admin/tintuc/them")->with("thongbao","Bạn đã thêm thành công");
    }
    public function getSua($id){
    	$tintuc=tintuc::find($id);
    	$theloai=theloai::all();
    	$loaitin=loaitin::all();
    	return view("admin.tintuc.sua",['tintuc'=>$tintuc,'theloai'=>$theloai,'loaitin'=>$loaitin]);
    }
    public function postSua(Request $request,$id){
    	$this->validate($request,
    		[
    			'idLoaiTin'=>'required',
    			'TieuDe'=>'required|min:3|unique:tintuc,TieuDe',
    			'TomTat'=>'required',
    			'NoiDung'=>'required',
    		],
    		[
    			'LoaiTin.required'=>'Bạn chưa chọn loại tin',
    			'TieuDe.required'=>'Bạn chưa nhạp tiêu đề',
    			'TieuDe.min'=>'Tiêu đề phải có ít nhất 3 kí tự',
    			'TieuDe.unique'=>'Tiêu đề đã tồn tại',
    			'NoiDung.required'=>'Bạn chưa nhập nội dung',
    		]);
    	$tintuc=tintuc::find($id);
    	$tintuc->TieuDe=$request->TieuDe;
    	$tintuc->TieuDeKhongDau=changeTitle($request->TieuDe);
    	$tintuc->TomTat=$request->TomTat;
    	$tintuc->NoiDung=$request->NoiDung;
    	$tintuc->NoiBat=$request->NoiBat;
    	$tintuc->idLoaiTin=$request->idLoaiTin;
    	if($request->hasFile('Hinh')){
    		$file=$request->file('Hinh');
    		$name=$file->getClientOriginalName();
    		$duoi=$file->getClientOriginalExtension();
    		if($duoi!="jpg"&&$duoi!="png"&&$duoi!="jpeg"){
    			return redirect("admin/tintuc/them")->with("loi","Bạn chỉ được chọn file đuôi jpg,png,jpeg");
    		}
    		$Hinh=str_random(4)."_".$name;
    		while(file_exists("upload/tintuc/".$Hinh)){
    			$Hinh=str_random(4)."_".$name;
    		}
    		$file->move("upload/tintuc",$Hinh);
    		unlink("upload/tintuc/".$tintuc->Hinh);
    		$tintuc->Hinh=$Hinh;
    	}
    	$tintuc->save();
    	return redirect("admin/tintuc/sua/".$id)->with("thongbao","Bạn đã sửa thành công");
    }
    public function getXoa($id){
    	$tintuc=tintuc::find($id);
        $comment=comment::where('idTinTuc',$id);
        $comment->delete();
    	$tintuc->delete();
    	unlink("upload/tintuc/".$tintuc->Hinh);
    	return redirect('admin/tintuc/danhsach')->with("thongbao","Bạn đã xóa thành công");
    }
}
