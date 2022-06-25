<div class="modal" id="paymentModal" role="dialog"  style="overflow: scroll;">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title">Payment</h4>
          <button type="button" class="close" ng-click="hide_data_modal('paymentModal')"><i class="icons icon-close"></i></button>
        </div>

        <div class="modal-body">
          <form method="POST" name="PaymentForm" ng-submit="savePayment(PaymentForm.$valid)" novalidate="novalidate">

            <div class="row">
              <div class="form-group col-md-2 col-xs-6">
                  <label>Invoice Date <span class="required">*</span></label>
                  <input type="text" class="form-control datepicker" ng-model="payment.invoice_date" required />
              </div>
              <div class="form-group col-md-2 col-xs-6">
                  <label>Payment Mode <span class="required">*</span></label>
                  <select class="form-control" ng-model="payment.p_mode" required>
                    <option value="">Select</option>
                    <option ng-value="mode.id" ng-repeat="mode in payModes">@{{mode.mode}}</option>
                  </select>
              </div>
              <div class="form-group col-md-2 col-xs-6" ng-if="payment.p_mode != 6">
                  <label>Payment Date <span class="required">*</span></label>
                  <input type="text" class="form-control datepicker" ng-model="payment.payment_date" required />
              </div>
              <div class="form-group col-md-2 col-xs-6">
                  <label>Ref No</label>
                  <input type="text" class="form-control" ng-model="payment.reference_no" />
              </div>
              <div class="form-group col-md-4 col-xs-6">
                  <label>Remarks</label>
                  <input type="text" class="form-control" ng-model="payment.p_remark" />
              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
        
                <div style="background: #EEE; padding: 15px;">
                  <b>Payment Items</b>
                  <div class="form-group mt-2">
                    <select class="form-control" ng-model="item.category_id">
                      <option value="">Select Category</option>
                      <option ng-value="cat.id"  ng-repeat="cat in payTypeCat">
                        @{{cat.category_name}}
                      </option>
                    </select>
                  </div>

                  <div class="form-group">
                    <select class="form-control" ng-model="item.type_id" ng-change='getAmount()'>
                      <option value="">Select Type</option>
                      <option ng-repeat="type in payType" ng-value="type.id" ng-if="item.category_id == type.category_id">@{{type.name}}</option>
                    </select>
                  </div>

                  <div class="text-right">
                    <button type="button" class="btn btn-primary" ng-click="addPaymentItem()" ng-disabled="!item.type_id || getting_amount">Add <i class="icons chevron-right"></i></button>
                  </div>

                </div>

                <div style="background: #CCC; padding: 15px;">
                  
                  <div class="form-group mt-2">
                    <label>Apply Coupon</label>
                    <select class="form-control" ng-model="item.coupon_id">
                      <option value="">NA</option>
                      <option ng-value="coupon.id"  ng-repeat="coupon in coupons">
                        @{{coupon.code}}
                      </option>
                    </select>
                    <div class="text-right mt-2">
                      <button type="button" class="btn btn-primary" ng-click="applyCoupon()">Apply</button>
                    </div>
                  </div>

                </div>

              </div>

              <div class="col-md-9">
                <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>SN</th>
                          <th>Type</th>
                          <th style="min-width: 100px;">Start Date</th>
                          <th style="min-width: 100px;">Amount</th>
                          <th style="min-width: 70px;">Discount</th>
                          <th style="min-width: 100px;" class="text-center">Taxable Amount</th> 
                          <th class="text-center">Tax</th>
                          <th class="text-center">Total</th>
                          <th></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr ng-repeat="pay_item in payment.items track by $index">
                          <td>@{{ $index + 1}}</td>
                          <td>
                            @{{ pay_item.category }} - @{{ pay_item.type }}
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control datepicker" ng-model="pay_item.start_date" ng-if="pay_item.category_id == 2" required>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control" ng-model="pay_item.amount" ng-keyup="applyTax(pay_item)" required>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control" ng-model="pay_item.discount" ng-keyup="applyTax(pay_item)" required>
                              <span>@{{pay_item.discount_code}}</span>
                            </div>
                          </td>
                          <td class="text-center">
                            @{{pay_item.taxable_amount | INR}}
                          </td>
                          <td class="text-center">
                            @{{pay_item.tax | INR}} (@{{pay_item.tax_perc}}%)
                          </td>
                          <td class="text-center">
                             @{{pay_item.total_amount | INR}}
                          </td>
                          <td>
                            <button type="button" class="btn btn-danger" style="margin-top: 5px;" 
                              ng-click="removePaymentItem($index)"><i class="icons icon-close"></i></button>
                          </td>
                        </tr>
                        <tr>
                          <th colspan="5">
                            
                          </th>
                          <th colspan="1" class="text-center">
                            @{{ payment.amount | INR }}
                          </th>
                          <th colspan="" class="text-center">
                            @{{ payment.tax | INR }}
                          </th>
                          <th colspan="" class="text-center">@{{ payment.total_amount | INR }}</th>
                          <th></th>
                        </tr>
                      </tbody>
                    </table>
                  </div>    
              </div>
            </div>

            <hr>
            <div class="text-right">
              <button type="submit" class="btn btn-primary" ng-disabled="processing">
                @{{payment.edit?"Update":"Submit"}} <span ng-show="processing" class="spinner-border spinner-border-sm"></span>
              </button>
              <button type="button" class="btn btn-secondary" ng-click="hide_data_modal('paymentModal')">Close</button>
            </div>
          </form>
        </div>    
      
    </div>
  </div>
</div>