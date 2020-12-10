@extends('layouts.app')

@section('content')
<div class="flex justify-center">
    {{-- <div class="w-8/12 bg-white p-6 rounded-lg"> --}}

    <div class="w-8/12">

        <div class="p-6">
            
        <div class="flex items-center">
            <h1 class="text-2xl font-medium mb-1 mr-2">{{ $user->name }}</h1>
            @auth
                @if ($user->id !== auth()->user()->id)

                    @if (!$user->followedBy(auth()->user()))
                        <form action="{{ route('users.follow', $user) }}" method="post" class="mr-1">
                            @csrf<button type="submit" class="text-blue-500">follow</button>
                        </form>
                    @else
                        <form action="{{ route('users.unfollow', $user) }}" method="post" class="mr-1">
                            @method('DELETE')
                            @csrf<button type="submit" class="text-blue-500">following</button>
                        </form>
                    @endif

                @endif
            @endauth
            
        </div>
        
            <p>Posted {{ $posts->count() }} {{ Str::plural('post', $posts->count()) }} and received {{ $user->receivedLikes->count() }} {{ Str::plural('like', $user->receivedLikes->count()) }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg">
            @if ($posts->count())
                @foreach ($posts as $post)
                    <x-post :post="$post" />
                @endforeach
            @else
                <p>{{ $user->name }} does not have any posts</p>
            @endif
        </div>
    </div>
</div>
@endsection