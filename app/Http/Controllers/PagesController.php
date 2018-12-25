<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\theloai;
use App\slide;
use App\loaitin;
use App\tintuc;
use App\comment;
use App\user;
use Illuminate\Support\Facades\Auth;
class PagesController extends Controller
{
	function __construct(){
		$theloai=theloai::all();
		$slide=slide::all();
		view()->share('theloai',$theloai);
		view()->share('slide',$slide);
	}
    function trangchu(){
    	return view('pages.trangchu');
    }
    function lienhe(){
    	return view('pages.lienhe');
    }
    function loaitin($id){
    	$loaitin=loaitin::find($id);
    	$tintuc=tintuc::where('idLoaiTin',$id)->paginate(5);
    	return view('pages.loaitin',['loaitin'=>$loaitin,'tintuc'=>$tintuc]);
    }
    function tintuc($id){
    	$tintuc=tintuc::find($id);
    	$comment=comment::where('idTinTuc',$id)->orderBy('id','desc')->get();
    	$tinnoibat=tintuc::where('NoiBat',1)->take(4)->get();
    	$tinlienquan=tintuc::where('idLoaiTin',$tintuc->idLoaiTin)->take(4)->get();  	
    	return view('pages.tintuc',['tintuc'=>$tintuc,'tinnoibat'=>$tinnoibat,'tinlienquan'=>$tinlienquan,'comment'=>$comment]);
    }
    function getDangnhap(){
    	return view('pages.dangnhap');
    }
    function postDangnhap(Request $request){
    	$this->validate($request,
    		[
    			'email'=>'required',
    			'password'=>'required|min:3|max:32'
    		],
    		[
    			'email.required'=>'Bạn chưa nhập email',
    			'password.required'=>'Bạn chưa nhập password',
    			'password.min'=>'Password không được nhỏ hơn 3 ký tự',
    			'password.max'=>'Password không được lớn hơn 32 ký tự'
    		]);
    	if(Auth::attempt(['email'=>$request->email,'password'=>$request->password,'quyen'=>0])){
    		return redirect('trangchu');
    	}else{
    		return redirect('dangnhap')->with('thongbao','Đang nhập không thành công');
    	}
    }
    function getDangxuat(){
    	Auth::logout();
    	return redirect('trangchu');
    }
    function getNguoidung(){
    	$user=Auth::user();
    	return view('pages.nguoidung',['nguoidung'=>$user]);
    }
    function postNguoidung(Request $request){
    	$this->validate($request,
    		[
    			'name'=>'required|min:3',
    		],
    		[
    			'name.required'=>'Bạn chưa nhập tên',
    			'name.min'=>'Tên có ít nhất 3 ký tự',
    		]);
    	$user=Auth::user();
    	$user->name=$request->name;

    	if($request->changePassword == "on"){
    		$this->validate($request,
    		[
    			'password'=>'required|min:3|max:32',
    			'passwordAgain'=>'required|same:password'
    		],
    		[
    			'password.required'=>'Bạn chưa nhập password',
    			'password.min'=>'Password phải có ít nhất 3 ký tự',
    			'password.max'=>'Password có nhiều nhất 32 ký tự',
    			'passwordAgain.required'=>'Bạn chưa nhập lại password',
    			'passwordAgain.same'=>'NHập lại password không chính xác'
    		]);
    		$user->password=bcrypt($request->password);
    	}
    	$user->save();
    	return redirect('nguoidung')->with('thongbao','Bạn đã sửa thành công');
    }
    function getDangky(){
        return view('pages.dangky');
    }
    function postDangky(Request $request){
        $this->validate($request,
            [
                'name'=>'required|min:3',
                'email'=>'required|email|unique:users,email',
                'password'=>'required|min:3|max:32',
                'passwordAgain'=>'required|same:password'
            ],
            [
                'name.required'=>'Bạn chưa nhập tên',
                'name.min'=>'Tên có ít nhất 3 ký tự',
                'email.required'=>'Bạn chưa nhập email',
                'email.email'=>'Bạn nhập chưa đúng định danh email',
                'email.unique'=>'Email đã tồn tại',
                'password.required'=>'Bạn chưa nhập password',
                'password.min'=>'Password phải có ít nhất 3 ký tự',
                'password.max'=>'Password có nhiều nhất 32 ký tự',
                'passwordAgain.required'=>'Bạn chưa nhập lại password',
                'passwordAgain.same'=>'Nhập lại password không chính xác'
            ]);
        $user=new user;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->quyen=0;
        $user->save();
        return redirect('dangky')->with('thongbao','Đăng ký tài khoản thành công');
    }
    function timkiem(Request $request){
        $tukhoa=$request->tukhoa;
        $tintuc=tintuc::where('TieuDe','like',"%$tukhoa%")->orwhere('TomTat','like',"%$tukhoa%")->orwhere('NoiDung','like',"%$tukhoa%")->take(30)->paginate(5);
        return view('pages.timkiem',['tintuc'=>$tintuc,'tukhoa'=>$tukhoa]);
    }
    function getGioithieu(){
        return view('pages.gioithieu');
    }
}
