<div ng-if="switchContent == 'payments'">
    <div class="text-right mob-mt-40">
      <button class="btn btn-primary" ng-click="addPayment()"><i class="icons icon-plus"></i> Add Payment</button>
    </div>
    <div class="table-responsive mt-2">
      <table class="table">
        <thead>
          <tr>
            <th>Code</th>
            <th>Invoice Date</th>
            <th>Payment Date</th>
            <th>Amount</th>
            <th>Tax</th>
            <th>Total Amount</th>
            <th class="text-right">#</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="payment in student.payments">
            <td>
              <a href="#" ng-click="viewPayment(payment.id)">@{{ payment.code }}</a>
              <span class="save-tag red" ng-if="payment.p_mode == 6">Unpaid</span>
            </td>
            <td>@{{ payment.invoice_date }}</td>
            <td>@{{ payment.payment_date }}</td>
            <td>@{{ payment.amount | INR}}</td>
            <td>@{{ payment.tax | INR }}</td>
            <td>@{{ payment.total_amount | INR }}</td>
            <td class="text-right">
              <button class="btn btn-light btn-sm" ng-click="editPayment(payment.history_id)">
              Edit</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
</div>