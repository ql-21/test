<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandles;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',['except'=>['show']]);
    }

    /**
     * [show] 个人中心展示页面
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request,User $user)
    {
        $replies=[];
        $topics=[];
        $tab=$request->tab;
        if($tab=='replies'){
            $replies=$user->reply()->with('user','topic')->recent()->paginate(5);
        }else{
            $topics=$user->topic()->with('user','category')->recent()->paginate(10);
        }


        return view('users.show',compact('user','topics','replies','tab'));
    }

    /**
     * [edit] 个人编辑展示页面
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    /**
     * [update] 个人信息更新
     * @param UserRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request ,User $user,ImageUploadHandles $uploadHandles)
    {
        $this->authorize('update',$user);

        $data=$request->all();

        if($request->avatar){
            $result=$uploadHandles->save($request->avatar,'avatar',$user->id,208);
            if($result){
                $data['avatar']=$result['path'];
            }
        }


        $user->update($data);
        return redirect()->route('users.show',$user->id)->with('success','个人信息修改成功');
    }
}
