<div class="mt-5 mb-5">

	<div class="text-center" style="font-size: 80px; color: #AAA">
		@if(isset($pay_history))
			<i class='fab fa-cc-amazon-pay'></i>
		@endif

		@if(isset($student))
			<i class="icon-people icons "></i>
		@endif
	</div>

	<div class="text-center" style="color: #888; font-size:20px;">
		@if(isset($message))
			{{$message}}
		@endif
	</div>

</div>