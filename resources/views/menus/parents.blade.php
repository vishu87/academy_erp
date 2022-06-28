<?php $user_students = Session::get("user_students"); ?>

@foreach($user_students as $user_student)
<li class="parent @if( Session::get('user_student_id') == $user_student->id ) active @endif ">
    <a href="{{url('/parents/switch-student/'.$user_student->id)}}">
        <div style="text-align:center;">
            <img src="{{ $user_student->pic }}" style="width: 48px; height: 48px; border-radius: 50%" />
        </div>
        {{ $user_student->name }}
    </a>
</li>
@endforeach