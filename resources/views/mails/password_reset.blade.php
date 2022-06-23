<p>
    Dear {{$user->name}},
</p>

<p>
    Your password has been reset successfully, <b>{{$user->password_check}}</b> is your new password , <a target="_blank" href="{{url('/')}}">Click here </a> to login to your account
</p>

<p>
    Thanks.
</p>