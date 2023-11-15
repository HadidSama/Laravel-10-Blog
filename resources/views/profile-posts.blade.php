<x-layout>
    <div class="container py-md-5 container--narrow">
        <h2>
          <img class="avatar-small" src="{{$user->avatar}}" /> {{$user->username}}
          @auth
            @if (!$currentlyFollowing AND auth()->user()->username != $user->username)
              <form class="ml-2 d-inline" action="/create-follow/{{$user->username}}" method="POST">
                @csrf
                <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
              </form>
            @endif
            @if ($currentlyFollowing)
              <form class="ml-2 d-inline" action="/delete-follow/{{$user->username}}" method="POST">
                @csrf
                <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>
              </form>
            @endif  
          @endauth
          @if (auth()->user()->username == $user->username)
            <a href="/manage-avatar" class="btn btn-secondary btn-sm">Manage Avatar</a>              
          @endif
          
        </h2>
  
        <div class="profile-nav nav nav-tabs pt-2 mb-4">
          <a href="#" class="profile-nav-link nav-item nav-link active">Posts: {{$user->posts()->count()}}</a>
          <a href="#" class="profile-nav-link nav-item nav-link">Followers: 3</a>
          <a href="#" class="profile-nav-link nav-item nav-link">Following: 2</a>
        </div>
  
        <div class="list-group">
            @foreach ($user->posts()->get() as $post)
                <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
                    <img class="avatar-tiny" src="{{$user->avatar}}" />
                    <strong>{{$post->title}}</strong> on {{$post->created_at->format('n/j/Y')}}
                </a>
            @endforeach
        </div>
    </div>
</x-layout>