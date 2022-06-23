<?php $user_students = Session::get("user_students"); ?>

@foreach($user_students as $user_student)
<li class="parent @if($sidebar == 'city') active @endif ">
    <a href="{{url('/city')}}">
        {{ $user_student->name }}
    </a>
</li>
@endforeach