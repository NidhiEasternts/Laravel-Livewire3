<?php

namespace App\Livewire;

use App\Models\Posts;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Post extends Component
{
    use WithPagination;

    public $posts = [];

    public $title;

    public $description;

    public $postId;

    public $updatePost = false;

    public $addPost = false;

    public $list = false;

    public $search = '';

    protected $listeners = [
        'deletePostListner' => 'deletePost',
    ];

    protected $rules = [
        'title' => 'required',
        'description' => 'required',
    ];

    public function resetFields()
    {
        $this->title = '';
        $this->description = '';
    }

    public function indexPost()
    {
        $this->list = true;
    }

    public function render()
    {
        return view('livewire.post', [
            'post_data' => Posts::where('user_id', Auth::user()->id)->paginate(10),
        ]);
    }

    public function addPostData()
    {
        $this->resetFields();
        $this->addPost = true;
        $this->updatePost = false;
    }

    public function storePost()
    {
        $this->validate();
        try {
            Posts::create([
                'title' => $this->title,
                'description' => $this->description,
                'user_id' => Auth::user()->id,
            ]);
            session()->flash('success', 'Post Created Successfully!!');
            $this->resetFields();
            $this->addPost = false;
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }

    public function editPost($id)
    {
        try {
            $post = Posts::findOrFail($id);
            if (! $post) {
                session()->flash('error', 'Post not found');
            } else {
                $this->title = $post->title;
                $this->description = $post->description;
                $this->postId = $post->id;
                $this->updatePost = true;
                $this->addPost = false;
            }
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }

    }

    public function updatePostData()
    {
        $this->validate();
        try {
            Posts::whereId($this->postId)->update([
                'title' => $this->title,
                'description' => $this->description,
                'user_id' => Auth::user()->id,
            ]);
            session()->flash('success', 'Post Updated Successfully!!');
            $this->resetFields();
            $this->updatePost = false;
        } catch (\Exception $ex) {
            session()->flash('success', 'Something goes wrong!!');
        }
    }

    public function cancelPost()
    {
        $this->addPost = false;
        $this->updatePost = false;
        $this->resetFields();
    }

    public function deletePost($id)
    {
        try {
            Posts::find($id)->delete();
            session()->flash('success', 'Post Deleted Successfully!!');
        } catch (\Exception $e) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }
}
