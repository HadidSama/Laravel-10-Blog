<x-layout :title="$title">
    <div class="container py-md-5 container--narrow">
        <h2>
          <img class="avatar-small" src="{{$sharedData['user']->avatar}}" /> {{$sharedData['user']->username}}
          @auth
            @if (!$sharedData['currentlyFollowing'] AND auth()->user()->username != $sharedData['user']->username)
              <form class="ml-2 d-inline" action="/create-follow/{{$sharedData['user']->username}}" method="POST">
                @csrf
                <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
              </form>
            @endif
            @if ($sharedData['currentlyFollowing'])
              <form class="ml-2 d-inline" action="/delete-follow/{{$sharedData['user']->username}}" method="POST">
                @csrf
                <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>
              </form>
            @endif  
          @endauth
          @if (auth()->user()->username == $sharedData['user']->username)
            <a href="/manage-avatar" class="btn btn-secondary btn-sm">Manage Avatar</a>              
          @endif
        </h2>
  
        <div class="profile-nav nav nav-tabs pt-2 mb-4">
          <a href="/profile/{{$sharedData['user']->username}}" class="profile-nav-link nav-item nav-link {{Request::segment(3) == "" ? "active":""}}">Posts: {{$sharedData['user']->posts()->count()}}</a>
          <a href="/profile/{{$sharedData['user']->username}}/followers" class="profile-nav-link nav-item nav-link {{Request::segment(3) == "followers" ? "active":""}}">Followers: {{$sharedData['user']->followers()->count()}}</a>
          <a href="/profile/{{$sharedData['user']->username}}/following" class="profile-nav-link nav-item nav-link {{Request::segment(3) == "following" ? "active":""}}">Following: {{$sharedData['user']->following()->count()}}</a>
        </div>

        <div class="profile-slot-content">
            {{$slot}}
        </div>
    </div>
</x-layout>