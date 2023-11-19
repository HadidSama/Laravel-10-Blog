<x-profile :sharedData=$sharedData title="{{$sharedData['user']->username}}'s Followers Profile">
  <div class="list-group">
    @foreach ($followers as $follow)
        <a href="/profile/{{$follow->following->username}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$follow->following->avatar}}" />
            <strong>{{$follow->following->username}}</strong>
        </a>
    @endforeach
  </div>
</x-profile>