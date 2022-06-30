<div class="row mt-3">
  <div class="col-md-6 mob-mb-8 pr-2 mob-plr-2">
    <div class="br-block">
      @{{ student.status }}
      <span>Status</span>
    </div>
  </div>
  <div class="col-md-6 pl-2 mob-plr-2">
    <div class="br-block">
      @{{ student.doe ? student.doe : '-' }}
      <span>End Date</span>
    </div>
  </div>
</div>