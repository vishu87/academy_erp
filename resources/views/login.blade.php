@extends('layout_login')

@section('content')


    <div class="login-bg">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-md-10 h-100">
                    <div class="d-flex h-100 align-items-center">
                        <div class="w-50">
                            <div class="text-center mb-5">
                                <img src="{{url('/assets/images/Group-60782.png')}}">
                            </div>
                            @if(Session::has('failure'))
                                <div class="alert alert-danger">
                                  <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
                                </div>
                            @endif
                            {{ Form::open(array('url' => '/login', 'method'=>'POST',"autocomplete"=>"off","class"=>"form-horizontal")) }}
                                <div class="table-div mb-4">
                                    <div class="w-50">
                                        <h1 class="mb-0">Login</h1>
                                    </div>
                                    <div class="w-50">
                                        <p class="mb-0 text-right">
                                            <a href="{{url('/sign-up')}}" class="text-link">Parent Singup</a>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username">Username</label>

                                    {{Form::text("username","",["class"=>"form-control login-control", "placeholder"=>"Enter username"])}}
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>

                                    {{Form::password("password",["class"=>"form-control login-control","placeholder"=>"Enter Password"])}}
                                </div>
                                <div class="mt-4 mb-4">
                                    <p class="mb-0">
                                        <a href="{{url('forget-password')}}" class="text-link">Forgot password</a>
                                    </p>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="login-btn">Login</button>
                                    
                                </div>
                            {{Form::close()}}
                        </div>
                        <div class="h-100 w-50">
                            
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection


@section('footer_scripts')

@endsection