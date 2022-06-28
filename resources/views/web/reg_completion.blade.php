<div  >
    <div class="text-center">
        <img src="{{ url('assets/images/checked.png') }}" style="width: 120px; height: 120px;" />
        <h4 style="font-size: 20px; margin-top: 20px">Thank you @{{student.name}} for registering with us. We are excited to see you on the field!</h4>
    </div>
    <table class="table" style="width: 100%; margin-top: 20px;">
        <tr>
            <td>Date & Time</td>
            <td>@{{ datetime }}</td>
        </tr>
        <tr>
            <td>Amount</td>
            <td>₹ @{{ total_amount | INR}}</td>
        </tr>
        <tr>
            <td>Order ID</td>
            <td>@{{ order_id }}</td>
        </tr>
        <tr>
            <td>Transaction ID</td>
            <td>@{{ transaction_id }}</td>
        </tr>
    </table>
    <div style="margin-bottom: 15px; font-size: 14px">
        {!! $params->param_36 !!}
    </div>
</div>