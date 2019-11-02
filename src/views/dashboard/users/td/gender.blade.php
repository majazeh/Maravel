@if ($user->gender == 'male')
    <span class="fas fa-male fs-20"></span>
@elseif($user->gender == 'female')
    <span class="fas fa-female fs-20"></span>
@else
    <span class="fas fa-genderless"></span>
@endif
