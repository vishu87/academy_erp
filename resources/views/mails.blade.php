@if($type == "register")
    
    <p>Dear {{$user->name}},</p>

    <p>Following are your login credentials - </p>

    <p>
        URL : {{url('/')}}<br>
        Username : {{$user->username}}<br>
        Password : {{$password}}<br>
    </p>

    <p >
        Thanks.
    </p>

@endif