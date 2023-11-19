<x-profile :sharedData=$sharedData title="{{$sharedData['user']->username}}'s Following Profile">
  <div class="list-group">
    @foreach ($followings as $following)
        <a href="/profile/{{$following->followed->username}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$following->followed->avatar}}" />
            <strong>{{$following->followed->username}}</strong>
        </a>
    @endforeach
  </div>
</x-profile>