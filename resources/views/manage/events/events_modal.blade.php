
<div class="modal fade in" id="showEvent" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="modal-title">@{{open_event.name}}</h4>
                        <a target="_blank" href="https://bbfootballschools.com/tournament?type=@{{open_event.code}}">https://bbfootballschools.com/tournament?type=@{{open_event.code}}</a>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" aria-hidden="true">X</button>
                    </div>
                </div>
            </div>
            <div class="modal-body small-form">
                
                <div class="row">
                	<div class="col-md-8">
                		<table class="table table-bordered">
                			<tr>
                				<th>Name</th>
                				<td>@{{open_event.name}}</td>
                				<th>Location</th>
                				<td>@{{open_event.city_name}}</td>
                			</tr>
                			<tr>
                				<th>Start Date</th>
                				<td>@{{open_event.start_date}}</td>
                				<th>End Date</th>
                				<td>@{{open_event.end_date}}</td>
                			</tr>
                			<tr>
                				<th>Latitude</th>
                				<td>@{{open_event.latitude}}</td>
                				<th>Longitude</th>
                				<td>@{{open_event.longitude}}</td>
                			</tr>
                			<tr>
                				<th>Min Dob</th>
                				<td>@{{open_event.min_dob}}</td>
                				<th>Max Dob</th>
                				<td>@{{open_event.max_dob}}</td>
                			</tr>
                			<tr>
                				<th>Description</th>
                				<td colspan="3">@{{open_event.description}}</td>
                				
                			</tr>
                			<tr>
                				<th>Address</th>
                				<td colspan="3">@{{open_event.address}}</td>
                				
                			</tr>
                		</table>
                	</div>
                	<div class="col-md-4">
                		<div>
                			<img src="@{{open_event.image}}" style="width: 100%;height: auto">
                		</div>
                	</div>
                </div>
                

            </div>
        </div>
    </div>
</div>