@extends('layout_login')

@section('content')
    <div style="height: 100%">

        <div class="d-flex flex-row" style="height: 100%">
            <div class="theme-back" style="width: 300px">
                &nbsp;
            </div>
            <div class="d-flex flex-fill justify-content-center align-items-center">
                <div style="width: 360px;">
                    <div class="text-center">
                        <!-- <img src="{{ url('assets/images/logo.png') }}" style="height: 150px; width: auto;" /> -->
                    </div>
                    <div class="p-3">
                            @if(Session::has('failure'))
                                <div class="alert alert-danger" style="margin-top: 10px;">
                                  <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
                                </div>
                            @endif
                            {{ Form::open(array('url' => '/login', 'method'=>'POST',"autocomplete"=>"off","class"=>"form-horizontal")) }}
                            <div class="form-group">
                                <label>Email</label>
                                {{Form::text("username","",["class"=>"form-control","style"=>"border-radius:8px;"])}}
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                {{Form::password("password",["class"=>"form-control","style"=>"border-radius:8px;"])}}
                            </div>

                            <button type="submit" class="btn btn-block btn-primary">Login</button>
                            <br>
                            <div class="text-right">
                                <div style="clear:both; text-align: center;">Forgot Password? <a href="{{url('forget-password')}}">Click here </a> to reset your password</div>
                            </div><br>
                            <div>
                                <a href="{{url('/sign-up')}}" class="btn btn-block btn-primary">Parent Sign-up</a>
                            </div>
                        {{Form::close()}}
                    </div>
                </div>  
            </div>
        </div>
    </div>
@endsection


@section('footer_scripts')

@endsection