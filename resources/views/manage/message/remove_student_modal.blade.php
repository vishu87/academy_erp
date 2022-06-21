<div class="modal fade in" id="studentList" role="dialog" >
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
	        <div class="modal-header">
	            <h4 class="modal-title">Removed Students</h4>
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons icon-close"></i></button>
	        </div>
	        <div class="modal-body">
	            <span  class="btn btn-default" style="margin-right: 5px;margin-top: 5px; border: 1px solid #EEE;" ng-repeat="student in removed_students">@{{student.name}} &nbsp;&nbsp;<button ng-click="addStudentToList(student,$index)" class="btn btn-light btn-sm">+</button></span>
	        </div>
	    </div>
	</div>
</div>