<div>
    <div class="col-md-8 mb-2">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('success') }}
            </div>
        @endif                
        @if(session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        @endif
        @if($addPost)
            @include('livewire.create')
        @endif            
        @if($updatePost)
            @include('livewire.update')
        @endif
    </div>    
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @if(!$addPost)
                <button wire:click="addPostData()" class="btn btn-primary btn-sm float-right">Add New Post</button>
                @endif
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($post_data) > 0)
                                @foreach ($post_data as $post)
                                    <tr>
                                        <td>
                                            {{$post->title}}
                                        </td>
                                        <td>
                                            {{$post->description}}
                                        </td>
                                        <td>
                                            <button wire:click="editPost({{$post->id}})" class="btn btn-primary btn-sm">Edit</button>
                                            <button wire:click="deletePost({{$post->id}})" wire:confirm="Are you sure you want to delete this post?" class="btn btn-danger btn-sm">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" align="center">
                                        No Posts Found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>