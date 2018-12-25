<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\comment;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function getDanhSach(){
    	$user=User::all();
    	return view("admin.user.danhsach",['user'=>$user]);
    }
    public function getThem(){
    	return view("admin.user.them");
    }
    public function postThem(Request $request){
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
    	$user=new User;
    	$user->name=$request->name;
    	$user->email=$request->email;
    	$user->password=bcrypt($request->password);
    	$user->quyen=$request->quyen;
    	$user->save();
    	return redirect('admin/user/them')->with('thongbao','Bạn đã thêm thành công');
    }
    public function getXoa($id){
    	$user=user::find($id);
    	$comment=comment::where('idUser',$id);
    	$comment->delete();
    	$user->delete();
    	return redirect("admin/user/danhsach")->with("thongbao","Bạn đã xóa người dùng thành công");
    }
    public function getSua($id){
    	$user=user::find($id);
    	return view("admin.user.sua",['user'=>$user]);
    }
    public function postSua(Request $request,$id){
    	$this->validate($request,
    		[
    			'name'=>'required|min:3',
    		],
    		[
    			'name.required'=>'Bạn chưa nhập tên',
    			'name.min'=>'Tên có ít nhất 3 ký tự',
    		]);
    	
    	$user= User::find($id);
    	$user->name=$request->name;
    	$user->quyen=$request->quyen;
    	if($request->changePassword=="on"){
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
    	return redirect("admin/user/sua/".$id)->with("thongbao","Bạn đã sửa thành công");
    }
    public function getDangnhapAdmin(){
    	return view('admin.login');
    }
    public function postDangnhapAdmin(Request $request){
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
    	if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
    		return redirect('admin/theloai/danhsach');
    	}
    	else{
    		return redirect('admin/dangnhap')->with('thongbao','Đang nhập không thành công');
    	}
    }
    public function getDangXuatAdmin(){
    	Auth::logout();
    	return redirect('admin/dangnhap');
    }
}
