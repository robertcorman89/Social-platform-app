<x-profile :sharedDate="$sharedData">
    <div class="list-group">
      @foreach ($following as $userFollowed)
      <a href="/profile/{{$userFollowed->userBeingFollowed->username}}" class="list-group-item list-group-item-action">
        <img class="avatar-tiny" src="{{$userFollowed->userBeingFollowed->avatar}}" />
        {{$userFollowed->userBeingFollowed->username}}
      </a>
      @endforeach
  </div>
  </x-profile>