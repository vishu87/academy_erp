<div class="form-group">
    <label>{{$label}} @if($required) <span class="text-danger">*</span> @endif</label>
    <div class="row">
        <div class="col">
            <select class="form-control" ng-model="{{$name}}.date" @if($required) required @endif>
                <option value="">DD</option>
                <?php for ($i=1; $i <= 31 ; $i++) { 
                    $j = ($i < 10) ? "0".$i : $i;
                ?>
                    <option value="<?php echo $j ?>"><?php echo $j ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col">
            <?php 
                $months = [
                    "01" => "Jan",
                    "02" => "Feb",
                    "03" => "Mar",
                    "04" => "Apr",
                    "05" => "May",
                    "06" => "Jun",
                    "07" => "Jul",
                    "08" => "Aug",
                    "09" => "Sep",
                    "10" => "Oct",
                    "11" => "Nov",
                    "12" => "Dec"
                ]
            ?>
            <select class="form-control" ng-model="{{$name}}.month" @if($required) required @endif>
                <option value="">MM</option>
                <?php foreach ($months as $key => $value) { ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col">
            <select class="form-control" ng-model="{{$name}}.year" @if($required) required @endif>
                <option value="">YYYY</option>
                <?php for ($i = 2015; $i >= $year ; $i--) {
                ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>