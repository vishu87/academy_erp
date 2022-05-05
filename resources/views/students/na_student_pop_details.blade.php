<div class="item-details" ng-class="$index > 4 ? 'bottom' : '' " >
	<div class="arrow"></div>
	<div class="loading-info" ng-if="!student.info_loaded">
		<div class="spinner-grow text-light" role="status">
		  	<span class="sr-only">Loading...</span>
		</div>
	</div>
	<ul class="info">
		<li>
			<label>Student Contact</label>
			@{{student.details.mobile}}
		</li>
		<li>
			<label>Father</label>
			@{{student.father}}
		</li>
		</li>
		<li>
			<label>Mother</label>
			@{{student.mother}}
		</li>
		<li>
			<label>School</label>
			@{{student.school}}
		</li>
		<li>
			<label>Last Subscription</label>
			<span>23-10-2020 to 23-10-2020, Amount - Rs.2000, Ajustment - 0</span>
		</li>
	</ul>
</div>