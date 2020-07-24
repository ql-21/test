<?php

namespace App\Http\Controllers;

use App\Events\AddRead;
use App\Handlers\ImageUploadHandles;
use App\Models\Category;
use App\Models\Topic;
use App\Policies\TopicPolicy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request)
	{
        $topics = Topic::withOrder($request->order)->with('user','category')->paginate(10);
//        $topics = Topic::paginate(100);
		return view('topics.index', compact('topics'));
	}

    public function show(Request $request,Topic $topic)
    {
        //强制矫正seo链接
        if(!empty($topic->slug)&&$topic->slug!=$request->slug){
            return redirect(route_topics_slug($topic),'301');
        }

        event(new AddRead($topic));

        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
	    $categories=Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function store(TopicRequest $request,Topic $topic)
	{
	    $topic->fill($request->all());
	    $topic->user_id = Auth::id();
	    $topic->save();
		return redirect()->to(route_topics_slug($topic))->with('success', 'Created successfully.');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories=Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
//		$topic->user_id=Auth::id();
		$topic->update($request->all());

		return redirect()->to(route_topics_slug($topic))->with('success', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success','删除成功！');
	}

	public function uploadImage(Request $request,ImageUploadHandles $uploadHandles)
    {
        $data=[
            'success'=>'false',
            'msg'=>'图片保存失败',
            'file_path'=>''
        ];
        if($request->upload_file){
            $result=$uploadHandles->save($request->upload_file,'topic',Auth::id());
            if($result){
                $data['success']=true;
                $data['msg']='图片保存成功';
                $data['file_path']=$result['path'];
            }
        }
        return $data;
    }
}