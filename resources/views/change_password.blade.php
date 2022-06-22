@extends('layout')

@section('sub_header')
  <div class="sub-header">
    <div class="table-div">
      <div>
        <h4 class="fs-18 bold" style="margin:0;">Profile</h4>
      </div>
      <div class="text-right">
      </div>
    </div>
  </div>
@endsection

@section('content')

    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-ban-circle"></i><strong>Success!</strong> {{Session::get('success')}}
        </div>
    @endif
    @if(Session::has('failure'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
        </div>
    @endif

  <div class="row">
    <div class="col-md-3">
      <div class="portlet">
        <div class="portlet-body">
          <div class="filters">
            {{ Form::open(array('url' => '/update-password', 'method'=>'POST',"autocomplete"=>"off","class"=>"form-horizontal")) }}
              <div class="row">
                  <div class="col-md-12 form-group">
                    <label class="label-control">Old Password</label>
                    {{Form::password('old_password',["class"=>"form-control" , "required"=>true])}}
                    <span class="errors">{{$errors->first('old_password')}}</span>
                  </div>

                  <div class="col-md-12 form-group">
                    <label class="label-control">New Password</label>
                    {{Form::password('new_password',["class"=>"form-control" , "required"=>true])}}
                    <span class="errors">{{$errors->first('new_password')}}</span>
                  </div>

                  <div class="col-md-12 form-group">
                    <label class="label-control">Confirm Password</label>
                    {{Form::password('confirm_password',["class"=>"form-control" , "required"=>true])}}
                    <span class="errors">{{$errors->first('confirm_password')}}</span>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
              </div>
            {{Form::close()}}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
