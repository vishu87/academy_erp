@if($type == "register")
    
    <p>Dear {{$user->name}},</p>

    <p>Following are your login credentials - </p>

    <p>
        URL : {{url('/')}}<br>
        Username : {{$user->username}}<br>
        Password : {{$password}}<br>
    </p>

    <p>
        Thanks.
    </p>
@endif

@if($type == "password_reset")
    <p>
        Dear {{$user->name}},
    </p>

    <p>
        Your password has been reset successfully, <b>{{$user->password_check}}</b> is your new password , <a target="_blank" href="{{url('/')}}">Click here </a> to login to your account
    </p>
    
    <p>
        Thanks.
    </p>
@endif