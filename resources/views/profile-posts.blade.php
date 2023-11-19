<x-profile :sharedData=$sharedData title="{{$sharedData['user']->username}}'s Profile">
  <div class="list-group">
    @foreach ($user->posts()->get() as $post)
        <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$user->avatar}}" />
            <strong>{{$post->title}}</strong> on {{$post->created_at->format('n/j/Y')}}
        </a>
    @endforeach
  </div>
</x-profile>