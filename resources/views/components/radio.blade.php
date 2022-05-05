<div class="form-group">
    <label>{{$label}} @if($required) <span class="text-danger">*</span> @endif</label><br>
    @foreach($options as $value => $option)
        <input type="radio" id="item_{{$value}}" ng-model="{{$name}}" value="{{$value}}">
        <label for="item_{{$value}}">Male</label>&nbsp;&nbsp;
    @endforeach
</div>