<div ng-if="switchContent == 'documents'">
      
  <div class="text-right" ng-if="student.edit_access">
    <button class="btn btn-primary" ng-click="addDocument()"><i class="icons icon-plus"></i> Add Document</button>
  </div>

  <div class="table-responsive mt-3" ng-if="student.documents.length > 0">
      <table class="table"> 
        <thead>
          <tr>
            <th>SN</th>
            <th>Doc Type</th>
            <th>Doc No.</th>
            <th>View</th>
            <th ng-if="student.edit_access" class="text-right">#</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="doc in student.documents track by $index">
            <td>@{{$index+1}}</td>
            <td>@{{doc.type}}</td>
            <td>@{{doc.document_no}}</td>
            <td><a href="{{url('/')}}/@{{doc.document_url}}" target="_blank">View</a></td>
            <td ng-if="student.edit_access" class="text-right">
              <button class="btn btn-sm btn-danger" ng-click="deleteDocuement(doc.id, $index)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
  </div>

  <div class="alert alert-warning mt-2" ng-if="student.documents.length == 0">
    No documents are available
  </div>

</div>