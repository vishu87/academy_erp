var app = angular.module('app', [
  'ngFileUpload',
  'jcs-autoValidate',
  'uiCropper',
  'ngSanitize'
]);

angular.module('jcs-autoValidate')
    .run([
    'defaultErrorMessageResolver',
    function (defaultErrorMessageResolver) {
        defaultErrorMessageResolver.getErrorMessages().then(function (errorMessages) {
          errorMessages['PatternMobile'] = 'Please fill a valid mobile number';
          errorMessages['PatternPin'] = 'Please fill a valid pin code';
          errorMessages['patternInt'] = 'Please fill a numeric value';
          errorMessages['patternFloat'] = 'Please fill a numeric/decimal value';
          errorMessages['patternDate'] = 'Please fill a valid date in DD-MM-YYYY format';
          errorMessages['PatternUsername'] = 'This field allows alphanumeric and . only between 4 to 20 chars';
        });
    }
]);

app.directive('convertToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(val) {
        return val != null ? parseInt(val, 10) : null;
      });
      ngModel.$formatters.push(function(val) {
        return val != null ? '' + val : null;
      });
    }
  };
});

app.directive('capitalize', function() {
    return {
      require: 'ngModel',
      link: function(scope, element, attrs, modelCtrl) {
        var capitalize = function(inputValue) {
          if (inputValue == undefined) inputValue = '';
          var capitalized = inputValue.toUpperCase();
          if (capitalized !== inputValue) {
            // see where the cursor is before the update so that we can set it back
            var selection = element[0].selectionStart;
            modelCtrl.$setViewValue(capitalized);
            modelCtrl.$render();
            // set back the cursor after rendering
            element[0].selectionStart = selection;
            element[0].selectionEnd = selection;
          }
          return capitalized;
        }
        modelCtrl.$parsers.push(capitalize);
        capitalize(scope[attrs.ngModel]); // capitalize initial value
      }
    };
});

app.directive('tablePaginate', ['$compile', function ($compile) {
    return {
      restrict: 'EA',
      template: '<div class="row">\
        <div class="col-md-6" >\
        <div class="total-count" ng-if="filter.max_page > 0">Showing <span>{{filter.max_per_page*(filter.page_no-1) + 1}} - {{filter.max_per_page*filter.page_no < total ? filter.max_per_page*filter.page_no : total}}</span> of <span>{{total}}</span></div>\
        </div>\
        <div class="col-md-6" style="text-align: right;">\
          <button class="btn fl-btn" ng-click="filter.show = (filter.show ? false:true )" ng-class=" filter.show ? \'open\' :\'\' "><i class="icons icon-grid"></i> Filter</button>\
          <ul class="pagination" ng-if="filter.max_page > 1">\
            <li class="page-item">\
              <a class="page-link" href="javascript:;" ng-click="setPage(1)"> << </a>\
            </li>\
            <li class="page-item">\
              <a class="page-link" href="javascript:;" ng-click="setPage(filter.page_no - 1)"> < </a>\
            </li>\
            <li class="page-item" ng-repeat="page in pages">\
              <a class="page-link" href="javascript:;" ng-click="setPage(page)" ng-class="page == filter.page_no ? \'active\' : \'\' ">{{page}}</a>\
            </li>\
            <li class="page-item">\
              <a class="page-link" href="javascript:;" ng-click="setPage(filter.page_no + 1)"> > </a>\
            </li>\
            <li class="page-item">\
              <a class="page-link" href="javascript:;" ng-click="setPage(filter.max_page)"> >> </a>\
            </li>\
          </ul>\
      </div>\
      </div>',
      link: function (scope, element, attrs) {
          
          scope.setPagination = function(){
            var pages = [];
            if(scope.filter.page_no == 1){
                pages.push(1);    
                pages.push(2);    
                if(scope.filter.max_page > 2) pages.push(3);    
            } else {
                if(scope.filter.max_page == scope.filter.page_no && scope.filter.max_page > 2){
                    pages.push(scope.filter.page_no - 2);
                }
                pages.push(scope.filter.page_no - 1);    
                pages.push(scope.filter.page_no);
                if(scope.filter.max_page != scope.filter.page_no){
                    pages.push(scope.filter.page_no + 1);
                }
            }
            scope.pages = pages;
          }

          scope.setPagination();

          scope.setPage = function(page_number){
            if(page_number < 1 || page_number > scope.filter.max_page || page_number == scope.page_number){
              return;
            }
            scope.filter.page_no = page_number;
            scope.setPagination();
            scope.getList();
          }
      }
    }
}]);

app.directive('thSort', ['$compile', function ($compile) {
    return {
      restrict: 'EA',
      template: function(element,attrs){
        return '<span style="display:block; cursor:pointer" ng-click="setOrder(\''+attrs.columnId+'\')">' + attrs.columnName + ' <span ng-if="filter.order_by == \''+ attrs.columnId +'\'"><i class="icons icon-arrow-up" ng-if="filter.order_type == \'ASC\'"></i><i class="icons icon-arrow-down" ng-if="filter.order_type == \'DESC\'"></i></span></span>'
      },
      link: function (scope, element, attrs) {
        scope.setOrder = function(column){
          if(column == scope.filter.order_by){
            scope.filter.order_type = (scope.filter.order_type == 'ASC') ? 'DESC' : 'ASC';
          } else {
            scope.filter.order_by = column;
            scope.filter.order_type = 'ASC';
          }
          scope.filter.page_no = 1;
          scope.getList();
        }
      }
    }
}]);

app.directive('fileUpload', ['$compile', function ($compile) {
    return {
      restrict: 'EA',
      template: function(element,attrs){
        console.log(attrs);
        return '<button type="button" class="button btn btn-primary" ngf-select="uploadFile($file, \''+attrs.fieldName+'\', '+attrs.objectName+' )" ladda="uploading" ng-if="!formData.'+attrs.fieldName+'">Upload</button> \
          <a type="button" class="btn btn-primary" href="{{ formData.'+attrs.fieldName+'_url}}" ng-if="formData.'+attrs.fieldName+'" target="_blank">View</a> \
          <a type="button" class="btn btn-danger" ng-click="removeFile(\''+attrs.fieldName+'\', '+attrs.objectName+')" ng-if="formData.'+attrs.fieldName+'">Remove</a>'
      },
      link: function (scope, element, attrs) {

        scope.uploadFile = function (file, field, obj) {
          scope.uploading = true;
          var url = base_url+'/upload/file';
            scope.upload.upload({
                url: url,
                data: {
                  file: file
                }
            }).then(function (resp) {
                if(resp.data.success){
                  obj[field] = resp.data.file;
                  obj[field+'_url'] = resp.data.file_url;
                } else {
                  alert(resp.data.message);
                }
                scope.uploading = false;
            }, function (resp) {
                console.log('Error status: ' + resp.status);
                scope.uploading = false;
            }, function (evt) {
                scope.uploading_percentage = parseInt(100.0 * evt.loaded / evt.total) + '%';
            });
        }

        scope.removeFile = function(field, obj){
          obj[field] = "";
          obj[field+'_url'] = "";
        }

      }
    }
}]);

app.directive('eBarChart', ['$compile', function ($compile) {
    return {
      restrict: 'EA',
      template: '<div class="chartarea" style="width:550px; height:300px"></div>',
      link: function (scope, element, attrs) {
          var regions = element[0].querySelectorAll('.chartarea');
          console.log(attrs);
          var division_id = attrs.dataid;
          var data_link = attrs.datagraph;
          var data = scope[data_link];

          angular.forEach(regions, function (path, key) {
            var regionElement = angular.element(path);
            regionElement.attr("id", division_id);
          });

          var labels = [];
          var values = [];

          for (var i = 0; i < data.length; i++) {
            labels.push(data[i].label);
            values.push(data[i].value);
          }
          
          var labelOption = {
              normal: {
                  show: true,
                  position: 'top',
                  distance: 15,
                  align: 'left',
                  verticalAlign: 'middle',
                  rotate: 90,
                  formatter: '{c}',
                  formatter: function (params) {
                      var value = params.data;
                      if(value < 1000) return params.data;
                      else if (value < 100000) return (params.data/1000).toFixed(1)+" K";
                      else return (params.data/100000).toFixed(1)+" L";
                  },
                  fontSize: 12,
                  rich: {
                      name: {
                          textBorderColor: '#fff'
                      }
                  }
              }
          };

          options = {
              grid: {
                  left: '3%',
                  right: '4%',
                  bottom: '3%',
                  containLabel: true
              },
              xAxis: {
                  type: 'category',
                  data: labels
              },
              yAxis: {
                  type: 'value'
              },
              tooltip: {
                  trigger: 'item',
                  formatter: "{b} : {c}"
              },
              series: [{
                  data: values,
                  type: 'bar',
                  label: labelOption,
              }]
          };

          setTimeout(function(){
            var myChart = echarts.init(document.getElementById(division_id));
            myChart.setOption(options);
          }, 2000);
      }
    }
}]);

app.directive('eLineChart', ['$compile', function ($compile) {
    return {
      restrict: 'EA',
      template: '<div class="chartarea" style="width:100%; height:250px;"></div>',
      link: function (scope, element, attrs) {
          var regions = element[0].querySelectorAll('.chartarea');
          var division_id = attrs.dataid;
          var data_link = attrs.datagraph;
          var data = scope[data_link];

          angular.forEach(regions, function (path, key) {
            var regionElement = angular.element(path);
            regionElement.attr("id", division_id);
          });

          var labels = data.labels;
          var series = [];

          var legends = data.legends;

          for (var i = 0; i < data.values.length; i++) {
            series.push({
                name: legends[i],
                data: data.values[i],
                type: 'line'
            });
          }

          console.log(series);

          options = {
              darkMode: 'decal',
              grid: {
                  top: '3%',
                  left: '3%',
                  right: '4%',
                  bottom: '15%',
                  containLabel: true
              },
              legend: {
                data: legends,
                bottom: -200
              },
              xAxis: {
                  type: 'category',
                  data: labels
              },
              yAxis: {
                  type: 'value'
              },
              tooltip: {
                  trigger: 'axis'
              },
              series: series
          };

          setTimeout(function(){
            var myChart = echarts.init(document.getElementById(division_id));
            myChart.setOption(options);
          }, 2000);
      }
    }
}]);

app.filter('INR', function () {        
    return function (input) {
        if (! isNaN(input)) {
            if(currency_format == "INR"){
              // var currencySymbol = '₹ ';  
              var currencySymbol = "";  
            } else {
              var currencySymbol = "";  
            }
            //var output = Number(input).toLocaleString('en-IN');   <-- This method is not working fine in all browsers!           
            var result = input.toString().split('.');

            var lastThree = result[0].substring(result[0].length - 3);
            var otherNumbers = result[0].substring(0, result[0].length - 3);
            if (otherNumbers != '')
                lastThree = ',' + lastThree;
            var output = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
            
            if (result.length > 1) {
                output += "." + result[1];
            }            

            return currencySymbol + output;
        }
    }
});

app.directive('ckeditor', Directive);
function Directive($rootScope) {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModel) {

            var id = attr.id;

            var ckeditor = ClassicEditor.create( document.querySelector( '#editor_data' ) ).then(editor => {
                window.editor = editor;
                editor.setData(scope.editor_data);
            });
        }
    };
}


app.run(function($rootScope, DBService) {
  
  $rootScope.studentDetail = function(studentId){
    DBService.getCall("/api/student/get-info/"+studentId).then(function(data){
        if (data.success) {
          $rootScope.student = data.student;
          $("#student_personal_detail").modal('show');
        } else {
          alert(data.message);
        }
    });
  }

  $rootScope.trimText = function(text, number){
    if(text.length > number) {
      return text.substr(0,number)+'...';
    } else {
      return text;
    }
  }

  $rootScope.showInfo = function(info, type){
    $rootScope.modal_info = info;
    $rootScope.modal_type = type;
    $("#infoModal").modal("show");
  }

  $rootScope.viewPayment = function(payment_id){
    DBService.getCall("/api/student/payment/view-payment/"+payment_id)
      .then(function(data){
        $rootScope.payment = data.payment;
       $("#viewPaymentModal").modal("show"); 
    })
  }

  $rootScope.sendPaymentEmail = function(payment_id){
    DBService.getCall("/api/student/payment/view-payment/"+payment_id)
      .then(function(data){
        $rootScope.payment = data.payment;
       $("#viewPaymentModal").modal("show"); 
    })
  }

  $rootScope.sendPaymentEmail =  function(payment_id){
    
    bootbox.confirm("Are you sure to mail payment receipt?",(check)=> {
        if (check) {
            $rootScope.processing_pay_mail = true;
            DBService.postCall({ payment_id : payment_id },"/api/student/payment-email")
            .then(function(data){
                bootbox.alert(data.message);
                $rootScope.processing_pay_mail = false;
            });
        }
    });
  }

});