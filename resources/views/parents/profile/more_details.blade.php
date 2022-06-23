<div class="row mt-3">
  <div class="col-md-6" style="padding-right: 5px;">
    <div class="br-block">
      @{{ student.status }}
      <span>Status</span>
    </div>
  </div>
  <div class="col-md-6" style="padding-left: 5px;">
    <div class="br-block">
      @{{ student.doe ? student.doe : '-' }}
      <span>End Date</span>
    </div>
  </div>
</div>