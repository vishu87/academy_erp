<div class="pic-box">
    <img class="round" ng-src="@{{student.pic}}" ng-if="student.pic" style="border: 4px solid @{{student.color}}">    
    <a ng-if="student.edit_access" href="#" class="change-pic" onclick="$('#ChangeplayerPhoto').modal('show'); $('#fileInput').trigger('click')" style="background-color: @{{student.color}}">Change</a>
</div>