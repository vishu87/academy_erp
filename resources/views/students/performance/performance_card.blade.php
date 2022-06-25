<div class="portlet">

  <div class="portlet-head">
    <div class="table-div full">

      <div >
        <ul class="menu">
          <li class="active">
            <a href="#">@{{ studentRecord.student_name }}</a>
          </li>
        </ul>
      </div>
      <div class="text-right">
          <a href="{{ url('performance-pdf') }}/@{{ studentRecord.uuid }}" ng-if="studentRecord.uuid" target="_blank">Download</a>
          <button class="btn btn-primary" ng-if="studentRecord.uuid" ng-click="sendEmail()" ng-disabled="processing_mail">Send Email <span ng-show="processing_mail" class="spinner-border spinner-border-sm"></span></button>
      </div>

    </div>
  </div>

  <div class="portlet-body">

    <div ng-repeat = "category in studentRecord.skill_categories" class="single-category">
        <div class="skill-header" ng-click="openHeader(category)">
            <div class="row-table">
                <div class="cell middle skill-name" ng-bind="category.category_name">
                </div>
                <div class="cell middle " ng-show="category.disable_rating == 0">
                    <div class="circle-small right">
                        <div ng-circles value="category.rating" max-value="5" colors="['#DDD', '#ffcc33']" width="12" duration="300"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="skill-body" ng-class="open_category_id == category.id ? '' : 'no-height' ">
            <div ng-repeat="attribute in category.attributes" class="single-rating">
                <div class="row">
                    <div class="col-md-3">
                        <span ng-bind="attribute.name"></span>
                    </div>
                    <div class="col-md-9 rating">
                        <div ng-if="attribute.type == 1">

                            <div class="rate" ng-class="attribute.value == 1 ? 'selected' : '' " ng-click="ratingMarked(attribute,1)">
                                <span></span> <span>1</span>
                            </div>

                            <div class="rate" ng-class="attribute.value == 2 ? 'selected' : '' " ng-click="ratingMarked(attribute,2)">
                                <span></span> <span>2</span>
                            </div>

                            <div class="rate" ng-class="attribute.value == 3 ? 'selected' : '' " ng-click="ratingMarked(attribute,3)">
                                <span></span> <span>3</span>
                            </div>

                            <div class="rate" ng-class="attribute.value == 4 ? 'selected' : '' " ng-click="ratingMarked(attribute,4)">
                                <span></span> <span>4</span>
                            </div>

                            <div class="rate" ng-class="attribute.value == 5 ? 'selected' : '' " ng-click="ratingMarked(attribute,5)">
                                <span></span> <span>5</span>
                            </div>
                           
                        </div>


                        <div class="form-group" ng-if="attribute.type == 2">

                            <textarea ng-model="attribute.remarks" placeholder="please enter your comments"></textarea>
                           
                        </div>

                    </div>
                </div>
            </div>

            <div style="clear:both"></div>
        </div>
    </div>

    <div>
      <div class="table-div full">
        <div>
            <button class="btn btn-info" ng-click="submitRecord(1)" ng-disabled="processing">
                Save <span ng-show="processing" class="spinner-border spinner-border-sm"></span>
            </button>
        </div>
        <div class="text-right">
          <!-- <button class="btn btn-success" ng-click="submitRecord(2)">Freeze</button> -->
        </div>
      </div>
    </div>

  </div>

</div>