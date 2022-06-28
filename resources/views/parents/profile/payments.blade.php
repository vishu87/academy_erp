<div ng-if="switchContent == 'payments'">
    <div class="table-responsive mt-2">
      <table class="table" ng-if="student.payments.length > 0">
        <thead>
          <tr>
            <th>Code</th>
            <th>Invoice Date</th>
            <th>Payment Date</th>
            <th>Amount</th>
            <th>Tax</th>
            <th>Total Amount</th>
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
              <a href="{{ url('/') }}/payments/@{{payment.unique_id}}" target="_blank" class="btn btn-primary" style="color:#fff" ng-if="payment.p_mode == 6">Pay Now</a>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="alert alert-warning mt-2" ng-if="student.payments.length == 0">
        No payments are available
      </div>

    </div>
</div>