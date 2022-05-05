@extends('layout_web')

@section('content')
	<div class="main" style="background: {{ $background  }}; min-height: 100%;">
		<div class="container">
			<div class="" style="text-align: center; padding: 20px;">
				<img src="{{ $logo_url }}" />
			</div>
			<div class="row">
				<div class="col-md-8" style="margin: 0 auto; background: #FFF">
					<div class="page-content" style="padding: 30px;">
						<h2 class="text-center">Demo Registration Form</h2>
						
						<div style="margin-top: 20px">
							<form name="addForm" class="add_form" novalidate="novalidate" autocomplete="off">
								<div >
									<div class="row">
										<div class="col-md-6 form-group">
											<label>Name</label>
											<input type="text" class="form-control" name="name" required="" value="">
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>DOB</label>
												<div class="row">
													<div class="col">
														<select class="form-control" name="date" required="">
															<option value="">DD</option>
															<?php for ($i=1; $i <= 31 ; $i++) { 
																$j = ($i < 10) ? "0".$i : $i;
															?>
																<option value="<?php echo $j ?>"><?php echo $j ?></option>
															<?php } ?>
														</select>
													</div>
													<div class="col">
														<?php 
															$months = [
																"01" => "Jan",
																"02" => "Feb",
																"03" => "Mar",
																"04" => "Apr",
																"05" => "May",
																"06" => "Jun",
																"07" => "Jul",
																"08" => "Aug",
																"09" => "Sep",
																"10" => "Oct",
																"11" => "Nov",
																"12" => "Dec"
															]
														?>
														<select class="form-control" name="month" required="">
															<option value="">MM</option>
															<?php foreach ($months as $key => $value) { ?>
																<option value="<?php echo $key ?>"><?php echo $value ?></option>
															<?php } ?>
														</select>
													</div>
													<div class="col">
														<select class="form-control" name="year" required="">
															<option value="">YYYY</option>
															<?php for ($i = 2000; $i <= 2015 ; $i++) {
															?>
																<option value="<?php echo $i ?>"><?php echo $i ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Mobile</label>
												<input type="text" class="form-control" name="mobile" required="" maxlength="10" minlength="10">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Email</label>
												<input type="email" class="form-control" name="email" required="" >
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Select City</label>
												<input type="email" class="form-control" name="sec_email" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Scholarship Type</label>
												<select class="form-control" name="sec_relation_to_student" required="">
													<option value="">Select</option>
													<option value="father">Father</option>
													<option value="mother">Mother</option>
													<option value="other">Self</option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
											
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Tell Us About Yourself </label>
												<textarea class="form-control"></textarea>
											</div>
										</div>
									</div>
									<div style="margin-top: 20px;">
										<button type="button" class="btn btn-block btn-primary" onclick="submit_form()" id="btn">Submit Details<div class="spinner-grow spinner-grow-sm ml-2" role="status"><span class="sr-only">Loading...</span></div></button>
									</div>
								</div>
							</form>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

@endsection


@section('footer_scripts')

@endsection