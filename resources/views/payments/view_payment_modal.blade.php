<div class="modal" id="viewPaymentModal" role="dialog"  style="overflow: scroll;">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title">Payment</h4>
          <button type="button" class="close" class="close" data-dismiss="modal" aria-label="Close">
            <i class="icons icon-close"></i>
          </button>
        </div>

        <div class="modal-body">
          <form method="POST" name="PaymentForm" ng-submit="savePayment(PaymentForm.$valid)" novalidate="novalidate">

            <div class="row">
              <div class="form-group col-md-2 col-xs-6">
                  <div class="static-info">
                    <span><i class="icons icon-calendar"></i> Invoice Date</span> @{{ payment.invoice_date }}
                  </div>
              </div>

              <div class="form-group col-md-2 col-xs-6">
                  <div class="static-info">
                    <span><i class="icons icon-credit-card"></i> Payment Mode</span> @{{ payment.mode }}
                  </div>
              </div>

              <div class="form-group col-md-2 col-xs-6">
                  <div class="static-info">
                    <span><i class="icons icon-calendar"></i> Payment Date</span> @{{ payment.payment_date }}
                  </div>
              </div>

              <div class="form-group col-md-2 col-xs-6">
                  <div class="static-info">
                    <span><i class="icons icon-pencil"></i> Ref. No</span> @{{ payment.reference_no }}
                  </div>
              </div>

              <div class="form-group col-md-4 col-xs-6">
                  <div class="static-info">
                    <span><i class="icons icon-note"></i> Remarks</span> @{{ payment.p_remark }}
                  </div>
              </div>

            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>SN</th>
                          <th>Type</th>
                          <th>Start Date</th>
                          <th>Amount</th>
                          <th>Discount</th>
                          <th>Tax</th>
                          <th>Total Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr ng-repeat="pay_item in payment.items track by $index">
                          <td>@{{ $index + 1}}</td>
                          <td>@{{ pay_item.category }} - @{{ pay_item.type }}</td>
                          <td>@{{ pay_item.start_date }}</td>
                          <td>@{{ pay_item.amount | INR }}</td>
                          <td>@{{ pay_item.discount | INR }} <span>@{{pay_item.discount_code}}</span></td>
                          <td>@{{pay_item.tax | INR}} (@{{pay_item.tax_perc}}%)</td>
                          <td>@{{pay_item.total_amount | INR}}</td>
                        </tr>
                        <tr>
                          <th colspan="3"></th>
                          <th colspan="2">
                            @{{ payment.amount | INR }}
                          </th>
                          <th colspan="">
                            @{{ payment.tax | INR}}
                          </th>
                          <th colspan="">@{{ payment.total_amount | INR }}</th>
                          <th></th>
                        </tr>
                      </tbody>
                    </table>
                  </div>    
              </div>
            </div>

            <hr>
            <div class="text-right">
              <button type="button" class="btn btn-secondary" class="close" data-dismiss="modal" aria-label="Close">Close</button>
            </div>
          </form>
        </div>    
    </div>
  </div>
</div>