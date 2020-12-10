@props(['post' => $post])


<div class="mb-4 ">
    <a href="{{ route('users.posts', $post->user) }}" class="font-bold">{{ $post->user->name }}</a>
    <span class="text-gray-600 text-sm">{{ $post->created_at->diffForHumans() }}</span>
    
    {{-- 'posts.show' --}}
    <p>
        <form action="{{ route('posts.show', $post) }}" method="post">
            @csrf<button type="submit" class="text-gray-500">{{ $post->body }}</button>
        </form>
    </p>

    <div class="flex items-center">
        @auth
            @can('delete', $post)
                <div>     
                <form action="{{ route('posts.destroy', $post) }}" method="post" class="mr-1">
                    @method('DELETE')
                    @csrf<button type="submit" class="text-blue-500">Delete</button>
                </form>
                </div>   
            @endcan  
    
            @if (!$post->likedBy(auth()->user()))
                <form action="{{ route('posts.likes', $post->id) }}" method="post" class="mr-1">
                    @csrf<button type="submit" class="text-blue-500">Like</button>
                </form>
            @else
                <form action="{{ route('posts.unlike', $post->id) }}" method="post" class="mr-1">
                    @method('DELETE')
                    @csrf<button type="submit" class="text-blue-500">Unlike</button>
                </form>
            @endif
        @endauth
        <span>{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</span>
    </div>

</div>