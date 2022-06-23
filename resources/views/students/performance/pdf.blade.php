<html>
  <head>
    <style type="text/css">
    @page { margin: 100px 25px; }
    body{
      font-family: helvetica;
    }
    .head{
      text-align: center;
    }
    header { 
      font-family: helvetica;
      position: fixed; 
      top: -90px; 
      left: 0px; 
      right: 0px; 
      height: 120px; 
    }
      footer { 
        position: fixed; 
        bottom: -60px; 
        left: 0px; 
        right: 0px; 
        height: 60px; 
      }
    header {
      height: 100px;
    }
    header img.bfc{
      height: 80px;
      width: auto;
    }
    header img.soccer{
      height: 100px;
      width: auto;
      float: right;
      margin-top: -10px;
    }
    .head h1{
      font-size: 30px;
      letter-spacing: 2px;
    }
    .head h2{
      font-size: 25px;
      letter-spacing: 2px;
    }
    div.left{
      width: 67%;
      float: left;
    }
    div.right{
      padding-top: 15px;
      width: 31%;
      float: right;
      margin-bottom: 20px;
      text-align: right;
    }
    .clear{
      clear: both;
    }
    .uppercase{
      text-transform: uppercase;
    }
    .spacing-big tr td{
      padding-bottom: 25px;
      font-weight: bold;
      font-size: 16px;
    }
    .sec-td tr td:nth-child(2){
      /*border-bottom: 1px solid #f00;*/
    }
    .100-w{
      width: 100%;
    }
    .border-big{
      border: 3px solid #000;
      border-radius: 10px 10px 10px 10px;
      padding: 0 20px;
    }
    .border-big h1{
      font-size: 20px;
    }
    .small-font{
      font-size: 12px;
      font-weight: bold;
    }
    .big-td{
      text-align: left;
      border-right: 3px solid #000;
    }
    div.break-page {page-break-before:always;}
    
    .stat{
      border: 3px solid #000;
      border-radius: 10px 10px 10px 10px;
      padding: 2px 0px 2px 20px;
      margin-bottom: 15px;
    }

    .stat thead td{
      font-size: 12px;
      font-weight: bold;
    }
    .align-c{
      text-align: center;
    }
    .stat td{
      padding: 4px 5px;
      font-size: 14px;
      font-weight: bold;
    }
    .param{
      border-right: 3px solid #000;
    }
    .stat td.cat-name{
      font-size: 16px;
    }
    .param li {
      margin-left: 10px;
    }
    .check{
      border: 2px solid #000;
      display: inline;
      padding: 3px 10px;
    }
    .field-box{
      position: relative;
      width: 200px;
      float: right;
      text-align: right;
    }
    .field-image{
      width: 100%;
    }
    .bullet{
      position: absolute;
      width: 16px;
      height: 16px;
      margin-left: -8px;
      margin-top: -8px;
      z-index: 999;
    }
    .bullet img{
      width: 100%;
      height: auto;
    }
    .small-td {
      color: #888;
      font-size: 13px !important;
    }
    main {
      font-family: helvetica;
    }
    </style>
  </head>
  <body>
    <header>
      <img src="http://soccerschools.bengalurufc.com/appsoft/sysadmin/evaluation/bfc_logo.png" class="bfc">
      <img src="http://soccerschools.bengalurufc.com/appsoft/sysadmin/evaluation/soccer_logo.jpg" class="soccer">
    </header>

    <footer>
      <div>
        <table class="small-font 100-w" style="margin-top:20px;">
          <tr>
            <td width="36%" style="text-align:left">FC Bengaluru United: Grassroots Development</td>
            <td style="text-align:right">C. JOHN KENNETH RAJ</td>
          </tr>
        </table>
      </div>
    </footer>
    <main>
      <div class="back">
        <div style="text-align:center">
          <h1 style="text-transform: uppercase;">FC Bengaluru United</h1>
          <h2>PLAYER DEVELOPMENT EVALUATION</h2>
        </div>
        <div>
          <div>
            <table class="uppercase 100-w spacing-big sec-td" style="margin-top:30px;">
              <tr>
                <td class="small-td" style="width:150px;">Player Name:</td>
                <td>{{ $student->name }}</td>
                <td class="small-td" style="width:100px;">DOB:</td>
                <td>{{ date("d-m-Y",strtotime($student->dob)) }}</td>
              </tr>
              <tr>
                <td class="small-td">Age Group:</td>
                <td>{{ $student->group_name}}</td>
                <td class="small-td">ID Number:</td>
                <td>{{ $student->id}}</td>
              </tr>
              <tr>
                <td class="small-td">Assessment Period:</td>
                <td>{{ $performance->session_name}}</td>
                <td class="small-td">Center:</td>
                <td style="font-size: 14px;">
                  {{ $student->center_name}}<br>
                  {{ $student->city_name}}    
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="clear"></div>
      </div>

      <?php $count = 0; ?>
      @foreach($categories as $category)
        <div class="stat">
          <table class="100-w" style="font-size:12px;" cellspacing="0" >
            <thead>
              <tr>
                <td width="45%" class="big-td cat-name">
                  {{ strtoupper($category->category_name) }}
                </td>
                <td width="11%" class="align-c">EXCELLENT</td>
                <td width="11%" class="align-c">ABOVE AVERAGE</td>
                <td width="11%" class="align-c">AVERAGE</td>
                <td width="11%" class="align-c">BELOW AVERAGE</td>
                <td width="11%" class="align-c">POOR</td>
              </tr>
            </thead>
            @foreach($category->attributes as $attribute)
              @if($attribute->type == 1)
                <tr>
                  <td class="param">                
                    {{$attribute->attribute_name}}
                  </td>
                  @for($index = 1; $index <= 5; $index++)
                    @if($index == $attribute->value)
                      <td class="align-c">
                        <div class="check">{{$index}}</div>
                      </td>
                    @else
                      <td class="align-c">{{$index}}</td>
                    @endif
                  @endfor
                </tr>
              @endif

              @if($attribute->type == 2)
                <tr>
                  <td class="param">                
                    {{$attribute->attribute_name}}
                  </td>
                  <td colspan="5" style="font-style: normal; text-align: center;">{{ $attribute->remarks }}</td>
                </tr>
              @endif

            @endforeach
          </table>
        </div>
      <?php $count++; ?>
      @endforeach


      <div style="margin-top:10px">
        <div class="">
          <table class="uppercase 100-w spacing-big sec-td" style="margin-top:30px;">
            <tr>
              <td>Date: {{ date("d-m-Y") }}</td>
              <td style=""></td>
            </tr>
          </table>
        </div>
        <div class="right">
            <img src="http://soccerschools.bengalurufc.com/appsoft/sysadmin/evaluation/john_sign.png" class="bfc">
            <b>C. JOHN KENNETH RAJ</b><br>
            Technical Head<br>
            <span style="font-size:12px">Grassroots &amp; BFC Soccer Schools</span>
        </div>
      </div>
    </main>
  </body>
</html>