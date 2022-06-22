<html>
<head>
<style>
div#top-title{text-align: center;font-weight: bold;border-bottom: 1px solid #000; padding:5px;}
div#top-title2{text-align: center;font-weight: bold; padding:15px; font-size:24px;}
table{width: 100%}
img{padding: 0 20px;}
div#logo{}
div#table-right{}
div#table-bottom{text-align: left;font-weight: bold; padding: 10px 0; }
table#logo tr td {
  border-top: 1px solid #000;
  border-left: 1px solid #000;
  padding: 5px;
}
table#logo tr:last-child td {
  border-bottom: 1px solid #000;
}
table#logo tr td:last-child {
  border-right: 1px solid #000;
}

table#table-right tr td {
  border-top: 1px solid #000;
  border-left: 1px solid #000;
  border-right: 1px solid #000;
  padding: 5px;
}
table#table-right tr:last-child td {
  border-bottom: 1px solid #000;
}

#table-details tr td {
  border-top: 1px solid #000;
  border-left: 1px solid #000;
  border-right: 1px solid #000;
  padding: 5px;
}
#table-details tr:last-child td {
  border-bottom: 1px solid #000;
}

</style>
</head>
<body>
  <div id="top-title">PAYMENT RECEIPT</div>
  <div id="top-title2">{{ $gst->name }}</div>
  <div>
    <div id="logo1" >
    <table border="0" cellpadding="0" cellspacing="0"><tr><td>
      <table id="logo" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td style="width:350px"><img src="{{url('/assets/images/logo.png')}}"></td>
          <td>
            <b> Company's PAN: </b>{{ $gst->pan_no }}<br>
            <b>Company's GST No: </b>{{ $gst->gst_id }}
          </td>
        </tr>
        <tr>
          <td>
              <b>{{ $gst->name }}</b><br>
              <b>Reg. Office: </b>{{ $gst->registered_office }} <br></td>
              <td><b>Contact Person:</b> {{ $gst->contact_person }}<br>
              <b>Contact No.:</b> {{ $gst->contact_name }}
          </td>
        </tr>
        <tr>
          <td colspan="2"><b>Receipt No.: </b>{{ $payment->code }}<br><b>Receipt Date: </b>{{ $payment->payment_date }}</td>
        </tr>
      </table>
    </div>
  </div>
  <div style="clear:both"></div>
  <div id="table-bottom">Received with thanks a sum of <u> Rs. {{ $payment->total_amount }} </u> from </div>

  <table id="table-details" border="0" cellpaddind="0" cellspacing="0">
    <tr>
      <td><b>Parent's Name:</b></td>
      <td colspan="9">{{$student->name}}</td>
    </tr>
    <tr>
      <td><b>Ward's Name:</b></td>
      <td colspan="9">{{$student->name}}</td>
    </tr>
    <tr>
      <td><b>D.O.B.:</b></td>
      <td colspan="9">{{ date("d - M - y",strtotime($student->dob)) }}</td>
    </tr>
    <tr>
      <td><b>Center</b></td>
      <td colspan="9">{{ $student->center_name }}</td>
    </tr>
    <tr>
      <td><b>Group/Batch: </b></td>
      <td colspan="9">{{ $student->group_name }}</td>
    </tr>
    <tr>
      <td colspan="10">Payment Details</td>
    </tr>
    <tr style="font-size: 12px;">
      <td><b>Type</b></td>
      <td><b>Amount</b></td>
      <td><b>Discount</b></td>
      <td><b>IGST %</b></td>
      <td><b>IGST</b></td>
      <td><b>SGST %</b></td>
      <td><b>SGST</b></td>
      <td><b>CGST %</b></td>
      <td><b>CGST</b></td>
      <td><b>Total Amount</b></td>
    </tr>
    <?php
      $total_amount = 0;
      $total_discount = 0;
      $total_igst = 0;
      $total_sgst = 0;
      $total_cgst = 0;
      $total_total_amount = 0;
    ?>
    @foreach($payment->items as $key=> $pay)
    <tr>
      <td>{{ $key+1 }}. {{ $pay->category }} - {{ $pay->type }}</td>
      <td style="text-align: center;">{{ $pay->amount }}</td>
      <td style="text-align: center;">{{ $pay->discount }} </td>
      <td style="text-align: center;">{{ $pay->igst_perc }}</td>
      <td style="text-align: center;">{{ $pay->igst }} </td>
      <td style="text-align: center;">{{ $pay->sgst_perc }}</td>
      <td style="text-align: center;">{{ $pay->sgst }} </td>
      <td style="text-align: center;">{{ $pay->cgst_perc }}</td>
      <td style="text-align: center;">{{ $pay->cgst }} </td>
      <td style="text-align: center;">{{ $pay->total_amount }}</td>
    </tr>
    <?php
      $total_amount += $pay->amount;
      if(is_numeric($pay->discount)) $total_discount += $pay->discount;
      if(is_numeric($pay->igst)) $total_igst += $pay->igst;
      if(is_numeric($pay->sgst)) $total_sgst += $pay->sgst;
      if(is_numeric($pay->cgst)) $total_cgst += $pay->cgst;

      $total_total_amount += $pay->total_amount;

    ?>
    @endforeach

    <tr>
      <td>Total</td>
      <td style="text-align: center;">{{ $total_amount }}</td>
      <td style="text-align: center;">{{ $total_discount }}</td>
      <td style="text-align: center;"></td>
      <td style="text-align: center;">{{ $total_igst }}</td>
      <td style="text-align: center;"></td>
      <td style="text-align: center;">{{ $total_sgst }}</td>
      <td style="text-align: center;"></td>
      <td style="text-align: center;">{{ $total_cgst }}</td>
      <td style="text-align: center;">{{ $total_total_amount }}</td>
    </tr>
    @if($payment->p_remark)
    <tr>
      <td colspan="10">{{ $payment->p_remark }}</td>
    </tr>
    @endif
  </table>
  <div>
    <br>
    <i>This is a computer generated receipt intimation, manual signature not required.</i><br><br>
    <i>This is a receipt intimation, not a receipt acknowledgement. The validity of this intimation stands negated if on a later date, Currency Notes are found to be fake / presented Cheque bounces owing to any reason / NEFT transaction fails.</i>
  </div>
</body>
</html>
