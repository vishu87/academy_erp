$(document).on("click",".datepicker",function(){
	$(this).datepicker({
    	format:"dd-mm-yyyy",
    	todayHighlight:true,
    	autoclose: true,
    });
	$(this).datepicker("show");
});

$(function() {

  $('li.parent').on('mouseover', function() {
    var $menuItem = $(this),
        $submenuWrapper = $('> ul', $menuItem);
    
    var menuItemPos = $menuItem.position();
    
    $submenuWrapper.css({
      top: menuItemPos.top,
      // left: menuItemPos.left + Math.round($menuItem.outerWidth() * 0.95)
      left: 92
    });

  });
});

$(document).on("click",".toggle-menu",function(){
	$('.page-menu').toggleClass("open");
	$(this).toggleClass("open-menu");
})

// $(".check_form").validate();
// $(".check_form_2").validate();

// var datatable = $("#datatable").DataTable( {
//     orderCellsTop: true,
//     fixedHeader: true,
//     pageLength:50
// } );
// $(document).on("change",'#organization_id',function(){
//     var val = $(this).val();
    
//     if (val > 0 || !val) {
//     	$('#org-name').css("display","none");
//     }else{
//     	$('#org-name').css("display","block");
//     }
//   });

// $(".check-form").validate();

// $.validator.addMethod('groupno', function(value, element) {
//     return /^([0-9,]+)$/.test(value)
// }, "Allows only numbers and comma");

// $.validator.addMethod('eod', function(value, element) {
// 	var extension = value.substr( (value.lastIndexOf('.') +1) ).toLowerCase();
//     return this.optional(element) || (extension == 'eod') 
// }, "Please select a valid EOD file");

// $.validator.addMethod('password', function(value, element) {
// 	return /^(?=.*\d)(?=.*[A-Z])(?=.*[~!@#$%&_^*]).{8,}$/.test(value);
// }, "Password must be atleast 8 characters long. It must contain atleast one Uppercase letter (A-Z), one special charaters ( ! @ # $ % _ ^ * & ~ ) ,and one number(0-9)");

// $.validator.addMethod("date_en", function(value, element) {
//     return this.optional(element) || /^\d{2}-\d{2}-\d{4}$/.test(value);
//   }, "Please select a valid date");

// $.validator.addMethod('pdf_jpg', function(value, element) {
// 	var extension = value.substr( (value.lastIndexOf('.') +1) ).toLowerCase();
// 	// console.log(extension);
//     return this.optional(element) || (extension == 'pdf' || extension == 'jpg' || extension == 'jpeg') 
// }, "Please select a valid pdf/jpeg file file");

// $.validator.addMethod('pdf', function(value, element) {
// 	var extension = value.substr( (value.lastIndexOf('.') +1) ).toLowerCase();
// 	// console.log(extension);
//     return this.optional(element) || (extension == 'pdf') 
// }, "Please select a valid pdf file");

// $.validator.addMethod('jpg', function(value, element) {
// 	var extension = value.substr( (value.lastIndexOf('.') +1) ).toLowerCase();
// 	// console.log(extension);
//     return this.optional(element) || (extension == 'jpg' || extension=='jpeg') 
// }, "Please select a valid jpg/jpeg file");

// $.validator.addMethod('png', function(value, element) {
// 	var extension = value.substr( (value.lastIndexOf('.') +1) ).toLowerCase();
// 	// console.log(extension);
//     return this.optional(element) || (extension == 'png') 
// }, "Please select a valid png file");

// $.validator.addMethod('filesize', function(value, element) {
//     return this.optional(element) || (element.files[0].size <= 4194304) 
// }, "Please select file less than 4 MB");

// $.validator.addMethod('filesize_img', function(value, element) {
//     return this.optional(element) || (element.files[0].size <= 2097152) 
// }, "Please select file less than 2 MB");

// $.validator.addMethod('alphanospace', function(value, element) {
//     return /^([a-zA-z]+)$/.test(value)
// }, "Only A-Z allowed. No spaces allowed");

// $.validator.addMethod('alphanospacecomma', function(value, element) {
//     return /^([a-zA-z\']+)$/.test(value)
// }, "Only A-Z and ' allowed. No spaces allowed");


// $.validator.addMethod('numeric', function(value, element) {
//     return /^\d*$/.test(value)
// }, "Only 0-9 integer allowed");

// $(document).on("click",".details-page",function(){
// 	$("#detailsModal").modal("show");
// 	var title = $(this).attr("data-title");
// 	var action = $(this).attr("action");

// 	$("#detailsModal .modal-title").html(title);
// 	$("#detailsModal .modal-body").html("Loading....");

// 	var formAction = base_url+"/"+action;
// 	// console.log(action);

// 	$.ajax({
// 	    type: "GET",
// 	    url : formAction,
// 	    success : function(data){
	    	
// 	    	if(!data.success) bootbox.alert(data.message);
// 	    	else {
// 	    		$("#detailsModal .modal-body").html(data.message);
// 	    	}
// 	    }
// 	},"json");
// });

// $(document).on("click", ".delete-div", function() {
// 	var btn = $(this);

// 	bootbox.confirm("Are you sure to delete?", function(result) {
//       if(result) {
      	
// 			var initial_html = btn.html();
// 			btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
// 			var deleteDiv = btn.attr('div-id');
			
// 			var formAction = base_url+'/'+btn.attr('action');

// 			$.ajax({
// 			    type: "DELETE",
// 			    data: {
// 			    	_token : CSRF_TOKEN
// 			    },
// 			    url : formAction,
// 			    success : function(data){
// 			    	data = JSON.parse(data);
// 			    	if(!data.success) bootbox.alert(data.message);
// 			    	else {
			    		
// 			    		$("#"+deleteDiv).hide('500', function(){
// 			    			$("#"+deleteDiv).remove();
// 				    	});
				    	
// 			    	}
// 			    	btn.html(initial_html);

// 			    }
// 			},"json");
//       	}
//     });
// });

// $(".upload-select").change(function(e){
// 	var val = $(this).val();
	
// 	$(".upload-div").hide();

// 	$("#div_"+val).show();
// });

// $(".check-all").click(function(e){

// 	if($(this).is(":checked")){
// 		$("input[type=checkbox]").prop("checked",true);
// 	} else {
// 		$("input[type=checkbox]").prop("checked",false);
// 	}
	

// });

// $(".mark-all-attendance").click(function(e){
// 	var pid = $(this).attr('pid');
// 	var check_attendance = "pattendance_"+pid;
// 	if ($(this).is(":checked")) {
// 		$("input[type=checkbox]."+check_attendance).prop("checked",true);
// 	}else{
// 		$("input[type=checkbox]."+check_attendance).prop("checked",false);
// 	}
// });

// $('.selectize').selectize({
//     maxItems: 10
// });


// $(document).on('click',"input[name=has_only_fn]",function(e){
// 	var val = $(this).val();
// 	if(val == 2){
// 		$(".last_name").show();
// 	} else {
// 		$(".last_name").hide();
// 		$(".last_name").find("input").val("");
// 	}
// });
// //Save Ajax Form
// $(document).on('click','form.ajax_update button[type=submit]', function(e){
//     e.preventDefault();
//     if($(".ajax_check_form").valid()){
//     	var btn = $(this);
//     	var initial_html = btn.html();
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
//     	var form = jQuery(this).parents("form:first");
// 		var dataString = form.serialize();
// 		var formAction = form.attr('action');

// 		var reload = btn.attr('reload');
// 		// console.log(dataString);
// 		$.ajax({
// 		    type: "PUT",
// 		    url : formAction,
// 		    data : dataString,
// 		    success : function(data){
// 		    	// console.log(data);
// 		    	data = JSON.parse(data);
// 		    	if(data.success){
		    		
// 		    		if(reload == 'reload'){
// 		    			location.reload();
// 		    		}

// 		    	} else {
// 		    		bootbox.alert(data.message);
// 		    	}
// 		    	btn.html(initial_html);
// 		    }
// 		},"json");
//     }
// });
// $(document).on("click", ".edit-div", function() {
//     var btn = $(this);
// 	$(".modal-body").html('Loading');
//     $("#detailsModal").modal('show');
    
// 	var initial_html = btn.html();
// 	editDiv = btn.attr('div-id');
// 	var title = btn.attr('modal-title');
// 	count = btn.attr('count');
// 	var formAction = base_url+'/'+btn.attr('action');
// 	$(".modal-title").html(title);
// 	$.ajax({
// 	    type: "GET",
// 	    url : formAction,
// 	    success : function(data){
// 	    	$(".modal-body").html(data);
// 	    	initialize();
// 	    }
// 	},"json");

// });

// $(document).on('click','form.ajax_update_pop button[type=submit]', function(e){
//     e.preventDefault();
//     if($(".ajax_check_form").valid()){
//     	var btn = $(this);
//     	var initial_html = btn.html();
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
//     	var form = jQuery(this).parents("form:first");
// 		var dataString = form.serialize();
// 		dataString = dataString+'&count='+count;
// 		var formAction = form.attr('action');
// 		$.ajax({
// 		    type: "PUT",
// 		    url : formAction,
// 		    data : dataString,
// 		    success : function(data){
// 		    	data = JSON.parse(data);
// 		    	if(data.success){
// 		    		if(data.message == 'remove'){
// 		    			$("#"+editDiv).remove();
// 		    		} else {

// 		    			$("#"+editDiv).replaceWith(data.message);
// 		    		}
// 			    	$(".modal").modal("hide");
// 		    	} else {
// 		    		bootbox.alert(data.message);
// 		    	}
// 			    btn.html(initial_html);
// 		    }
// 		},"json");
//     }
// });

// $(document).on('click','form.ajax_add_pop button[type=submit]', function(e){
//     e.preventDefault();
//     if($(".ajax_check_form").valid()){
//     	var btn = $(this);
//     	var initial_html = btn.html();
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
//     	var form = jQuery(this).parents("form:first");
// 		var dataString = form.serialize();
// 		var formAction = form.attr('action');
// 		var modalHide = form.attr('modal-hide');
// 		$.ajax({
// 		    type: "POST",
// 		    url : formAction,
// 		    data : dataString,
// 		    success : function(data){
// 		    	data = JSON.parse(data);
// 		    	if(data.success){
// 		    		$("#"+addDiv).append(data.message);
// 		    		if(!modalHide) $(".modal").modal("hide");
// 		    	} else {
// 		    		bootbox.alert(data.message);
// 		    	}
// 			    btn.html(initial_html);
// 		    }
// 		},"json");
//     }
// });

// $(document).on('click','form.ajax_add button[type=submit]', function(e){
//     e.preventDefault();
   
//     var btn = $(this);
// 	var addDiv = $(this).attr("div-id");
// 	var initial_html = btn.html();
// 	var form = $(this).parents("form:first");
// 	var data_prepend = $(this).attr("data-prepend");
// 	//if events
// 	// if(addDiv == 'events_1' || addDiv == 'events_2'){
// 	// 	var type = form.find('select[name=type]').eq(0);
// 	// 	if(type){
// 	// 		addDiv = addDiv + '_' + type.val();
// 	// 	}
// 	// }
	
// 	var dataString = form.serialize();
// 	var formAction = form.attr('action');
//     if(form.valid()){
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
// 		$.ajax({
// 		    type: "POST",
// 		    url : formAction,
// 		    data : dataString,
// 		    success : function(data){
// 		    	data = JSON.parse(data);

// 		    	if(data.success){
// 		    		if(data_prepend == 1){
// 		    			$("#"+addDiv).prepend(data.message);
// 		    		} else {
// 		    			$("#"+addDiv).append(data.message);
// 		    		}
// 		    		form.trigger('reset');
// 		    	} else {
// 		    		bootbox.alert(data.message);
// 		    	}
// 			    btn.html(initial_html);
// 		    }
// 		},"json");
//     }
// });

// //Added 
// $(document).on("click", ".add-div", function() {
//     var btn = $(this);
// 	$(".modal-body").html('Loading');
//     $("#detailsModal").modal('show');
// 	var initial_html = btn.html();
// 	addDiv = btn.attr('div-id');
// 	var title = btn.attr('modal-title');
// 	var formAction = base_url+'/'+btn.attr('action');
// 	$(".modal-title").html(title);
// 	$.ajax({
// 	    type: "GET",
// 	    url : formAction,
// 	    success : function(data){
// 	    	$(".modal-body").html(data);
// 			initialize();
// 	    }
// 	},"json");
// });


// //Tournament Teams
// $(document).on('click','#search_team', function(e){
//     e.preventDefault();
//     if($(".ajax_check_form").valid()){
//     	var btn = $(this);
//     	var initial_html = btn.html();
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
//     	var form = jQuery(this).parents("form:first");
// 		var dataString = form.serialize();
// 		var formAction = base_url + '/search_teams';
// 		$.ajax({
// 		    type: "POST",
// 		    url : formAction,
// 		    data : dataString,
// 		    success : function(data){
// 		    	$("#teamSearchResults").html(data);
//     			btn.html(initial_html);
// 		    }
// 		},"json");
//     }
// });
// $(document).on('click','.add_team', function(e){
// 	e.preventDefault();
// 	var btn = $(this);
// 	var initial_html = btn.html();
// 	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
// 	var team_id = $(this).attr('team-id');
// 	var tournament_id = $(this).attr('tournament-id');
// 	var email = $(this).parent().parent().find("input.form-control").val();
// 	if (email){
// 		if(email.match(/([\w\-]+\@[\w\-]+\.[\w\-]+)/) == null){

// 			bootbox.alert('Please provide a valid email');
// 			btn.html(initial_html);
// 		}
// 	} else {
// 		var formAction = base_url + '/tournaments/teams/add/'+tournament_id;
// 		$.ajax({
// 		    type: "POST",
// 		    url : formAction,
// 		    data : {team_id:team_id, email:email,_token : CSRF_TOKEN},
// 		    success : function(data){
		    	
// 		    	data = JSON.parse(data);
// 		    	if(data.success){
// 		    		$("#sortable1").append(data.message);
// 		    		btn.html('Added');
// 		    		// $(".modal").modal("hide");
// 		    	} else {
// 		    		bootbox.alert(data.message);
// 					btn.html('Added');
// 		    	}
// 		    }
// 		},"json");
// 	}
	
// });
// function initialize(){	
	
// }

// $(document).on("change","#address_state",function(){
// 	var btnVal = $(this).val();
// 	if(btnVal == 0){
// 		$(".address_state ").show();
		
// 	}else{
// 		$(".address_state").hide();

// 	}

// });



// $(document).on('click','#search_player', function(e){
//     e.preventDefault();
//     if($(".ajax_check_form").valid()){
//     	var btn = $(this);
//     	var initial_html = btn.html();
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
//     	var form = jQuery(this).parents("form:first");
// 		var dataString = form.serialize();
// 		var formAction = base_url + '/search_player';
// 		$.ajax({
// 		    type: "POST",
// 		    url : formAction,
// 		    data : dataString,
// 		    success : function(data){
// 		    	$("#PlayerSearchResults").html(data);
//     			btn.html(initial_html);
// 		    }
// 		},"json");
//     }
// });

// $(document).on('click','.add_player', function(e){
//     e.preventDefault();
//     	var btn = $(this);
//     	var initial_html = btn.html();
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
//     	var player_id = $(this).attr('player-id');
//     	var tournament_id = $(this).attr('tournament-id');
//     	var initial_html = btn.html();
// 		var formAction = base_url + '/teams/players/add/'+tournament_id;
// 		$.ajax({
// 		    type: "POST",
// 		    url : formAction,
// 		    data : {player_id:player_id,_token:CSRF_TOKEN},
// 		    success : function(data){
// 		    	data = JSON.parse(data);
// 		    	if(data.success){
// 		    		$("#players").append(data.message);
// 		    		$("input[name=player_name]").val('');
// 		    		btn.html('Add to Tournament');
// 		    	} else {
// 		    		bootbox.alert(data.message);
//     				btn.html('Add to Tournament');
// 		    	}
// 		    }
// 		},"json");
// });

// $(document).on("submit", ".search_player", function(e) {
// 	e.preventDefault();
//     var btn = $(this);
//     $(".modal").modal('show');
	
// 	var initial_html = btn.html();
	
// 	editDivID = btn.attr('div-id');
// 	var editDiv = $("#"+editDivID);
// 	editDiv.html('<i class="fa fa-spin fa-spinner"></i> Loading...');

// 	var title = btn.attr('modal-title');
// 	count = btn.attr('count');
// 	var formAction = btn.attr('action');

// 	var dataString = $(this).serialize();

// 	$(".modal-title").html(title);
// 	$.ajax({
// 	    type: "POST",
// 	    url : formAction,
// 	    data : dataString,
// 	    success : function(data){
	    	
//     		editDiv.html(data);
//     		initialize();
    	
// 	    	btn.html(initial_html);
// 	    }
// 	},"json");

// });

// $(document).on("click",".addPlayer",function (e) {
// 	e.preventDefault();
// 	var btn = $(this);
// 	var playerId = btn.attr('player-id');
// 	var campId = btn.attr('camp-id');
	
// 	$.ajax({
// 	    type: "GET",
// 	    url : base_url + '/control/national-camp/add-player-in-list/'+playerId+'/'+campId,

// 	    success : function(data){
// 	    	data = JSON.parse(data);
// 	    	if(data.success){
// 		    	$("#playersList").append(data.message);
	    		
// 	    		initialize();
// 	    	}else{
// 	    		bootbox.alert(data.message);
// 	    	}

// 	    }
// 	},"json");

// });

// $(document).on("click",".removeTempRow",function(){
// 	var btn = $(this);
// 	var divId = btn.attr('div-id');
// 	var national_camp_player_id = btn.attr('national_camp_player_id');
// 	bootbox.confirm("Are you sure?", function(result) {
//       if(result) {
    	
// 		$.ajax({
// 		    type: "DELETE",
// 		    data: {
// 			    	_token : CSRF_TOKEN
// 			    },
// 		    url : base_url + '/control/national-camp/remove-player-from-list/'+national_camp_player_id,

// 		    success : function(data){
// 		    	data = JSON.parse(data);
// 		    	if(data.success){
// 					$("#"+divId).hide(500,function(){
// 						$("#"+divId).remove();
// 					});
// 		    	}else{
// 		    		bootbox.alert(data.message);
// 		    	}
	    		
// 	    		initialize();

// 		    }
// 		},"json");

//       }
//     });

	
// });

// $(document).on("submit", ".search_official", function(e) {
// 	e.preventDefault();
//     var btn = $(this);
//     $(".modal").modal('show');
	
// 	var initial_html = btn.html();
	
// 	editDivID = btn.attr('div-id');
// 	var editDiv = $("#"+editDivID);
// 	editDiv.html('<i class="fa fa-spin fa-spinner"></i> Loading...');

// 	var title = btn.attr('modal-title');
// 	count = btn.attr('count');
// 	var formAction = btn.attr('action');

// 	var dataString = $(this).serialize();

// 	$(".modal-title").html(title);
// 	$.ajax({
// 	    type: "POST",
// 	    url : formAction,
// 	    data : dataString,
// 	    success : function(data){
	    	
//     		editDiv.html(data);
//     		initialize();
    	
// 	    	btn.html(initial_html);
// 	    }
// 	},"json");

// });


// $(document).on("click",".addOfficial",function (e) {
// 	e.preventDefault();
// 	var btn = $(this);
// 	var officialId = btn.attr('official-id');
// 	var campId = btn.attr('camp-id');
// 	$.ajax({
// 	    type: "GET",
// 	    url : base_url + '/control/national-camp/add-official-in-list/'+officialId+'/'+campId,

// 	    success : function(data){
// 	    	data = JSON.parse(data);
// 	    	if(data.success){
// 		    	$("#officialsList").append(data.message);
	    		
// 	    		initialize();
// 	    	}else{
// 	    		bootbox.alert(data.message);
// 	    	}

// 	    }
// 	},"json");

// });

// $(document).on("click",".removeTempRowOfficial",function(){
// 	var btn = $(this);
// 	var divId = btn.attr('div-id');
// 	var national_camp_offcial_id = btn.attr('national_camp_official_id');
// 	bootbox.confirm("Are you sure?", function(result) {
//       if(result) {
    	
// 		$.ajax({
// 		    type: "DELETE",
// 		    data:{
// 		    	_token:CSRF_TOKEN
// 		    },
// 		    url : base_url + '/control/national-camp/remove-official-from-list/'+national_camp_offcial_id,

// 		    success : function(data){
// 		    	data = JSON.parse(data);
// 		    	if(data.success){
// 					$("#"+divId).hide(500,function(){
// 						$("#"+divId).remove();
// 					});
// 		    	}else{
// 		    		bootbox.alert(data.message);
// 		    	}
	    		
// 	    		initialize();

// 		    }
// 		},"json");

//       }
//     });

	
// });

// $(document).on('click','form.ajax_edit_pop button[type=submit]', function(e){
//     e.preventDefault();
//     if($(".ajax_edit_pop").valid()){
//     	var btn = $(this);
//     	var initial_html = btn.html();
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
//     	var form = jQuery(this).parents("form:first");
// 		var dataString = form.serialize();
// 		dataString = dataString + "&count=" + count;
// 		var formAction = form.attr('action');
// 		$.ajax({
// 		    type: "PUT",
// 		    url : formAction,
// 		    data : dataString,
// 		    success : function(data){
// 		    	data = JSON.parse(data);

// 		    	if(data.success){

// 		    		$("#"+editDiv).replaceWith(data.message);
// 			    	$(".modal").modal("hide");
// 					if(data.confirm){bootbox.alert(data.message);}
// 		    	} else {
// 		    		bootbox.alert(data.message);
// 		    	}
// 			    btn.html(initial_html);
// 		    }
// 		},"json");
//     }
// });

// $(document).on('click','.showApprovals',function(e){
// 	e.preventDefault();
// 	var btn = $(this);
// 	var toggleDiv = btn.attr('div-id');
// 	$('#'+toggleDiv).toggle();
	
// });

// $(document).on("change","#emp_status",function(){
// 	var btnVal = $(this).val();
// 	if(btnVal == 3){
// 		$("#organization").hide();
// 		$("#date_since_emp").hide();
// 		$("#end_date").hide();
// 		$("#present_emp_copy").hide();
// 		$("#organization_fields").addClass("hiddenDiv");
// 		initialize();
		
// 	}else{
// 		$("#organization_fields").removeClass("hiddenDiv");
// 		$("#organization").show();
// 		$("#date_since_emp").show();
// 		$("#end_date").show();
// 		$("#present_emp_copy").show();
// 		initialize();

// 	}

// 	if(btnVal == 3){
// 		$(".emp_validate ").parent().hide();
// 		$(".emp_validate ").parent().addClass('hiddenDiv');
// 		initialize();
		
// 	}else{
// 		$(".emp_validate").parent().show();
// 		$(".emp_validate").parent().removeClass('hiddenDiv');
// 		initialize();

// 	}

// });

// $(document).on("change","#designation_id",function(){
// 	var btnVal = $(this).val();
// 	if(btnVal == 0){
// 		$("#designation_name").show();
		
// 	}else{
// 		$("#designation_name").hide();
// 	}

// });


// $(document).on("change","#domicile_state",function(){
// 	var btnVal = $(this).val();
// 	if(btnVal == 37){
// 		$(".domicile_state ").show();
		
// 	}else{
// 		$(".domicile_state").hide();

// 	}

// });

// $(document).on("change","#address_state",function(){
// 	var btnVal = $(this).val();
// 	if(btnVal == 37){
// 		$(".address_state ").show();
		
// 	}else{
// 		$(".address_state").hide();

// 	}

// });

// $(document).on('click','.doc_toggle', function(e){
// 	$('.document_box').slideUp();
// 	if(!$(this).hasClass('opened')){
// 		$(this).parent().find('.document_box').slideToggle()
// 		$(".doc_toggle").removeClass('opened');
// 		$(this).addClass('opened');
// 	} else {
// 		$(this).removeClass('opened');
// 	}
// });

// $(document).on('click','#search_official', function(e){
//     e.preventDefault();
//     if($(".ajax_check_form").valid()){
//     	var btn = $(this);
//     	var initial_html = btn.html();
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
//     	var form = jQuery(this).parents("form:first");
// 		var dataString = form.serialize();
// 		var formAction = base_url + '/teams/officials/search';
// 		$.ajax({
// 		    type: "get",
// 		    url : formAction,
// 		    data : dataString,
// 		    success : function(data){
// 		    	data = JSON.parse(data);
// 		    	if(data.success){
// 		    		$("#OfficialSearchResults").html(data.message);
// 		    	} else {
// 		    		bootbox.alert(data.message);
// 		    	}
//     			btn.html(initial_html);
// 		    }
// 		},"json");
//     }
// });

// $(document).on('click','.add_official', function(e){
//     e.preventDefault();
//     	var btn = $(this);
//     	var initial_html = btn.html();
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
//     	var official_id = $(this).attr('official-id');
//     	var tournament_id = $(this).attr('tournament-id');
//     	var initial_html = btn.html();
// 		var formAction = base_url + '/teams/officials/add/search/'+tournament_id;
// 		$.ajax({
// 		    type: "POST",
// 		    url : formAction,
// 		    data : {official_id:official_id,_token:CSRF_TOKEN},
// 		    success : function(data){

// 		    	data = JSON.parse(data);
// 		    	// console.log(data);
// 		    	if(data.success){
// 		    		$("#officials").append(data.message);
// 		    		$("input[name=official_name]").val('');
// 		    		btn.html('Add to Tournament');
// 		    	} else {
// 		    		bootbox.alert(data.message);
//     				btn.html('Add to Tournament');
// 		    	}
// 		    }
// 		},"json");
// });

// $(document).on("click", ".verify_toggle", function() {
//     var btn = $(this);
// 	var initial_html = btn.html();
//     btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
// 	addDiv = btn.attr('div-id');
// 	var formAction = base_url+'/'+btn.attr('action');
// 	$.ajax({
// 	    type: "PUT",
// 	    url : formAction,
// 	    data:{_token:CSRF_TOKEN},
// 	    success : function(data){
// 	    	data = JSON.parse(data);
// 	    	if(data.success){
// 	    		$("#"+addDiv).find('.verify_text').html(data.message);
// 	    		btn.addClass(data.color).removeClass(data.colorrem);
// 	    		btn.html(data.text);
// 	    	} else {
// 	    		bootbox.alert(data.message);
// 	    		btn.html(initial_html);
// 	    	}
// 	    }
// 	},"json");
// });

// $(document).on("change", ".official-type", function(e) {
// 	var val = $(this).find("option:selected").val();

// 	if( val == 3 ){
// 		$(this).parent().find("input.official-role").show().attr("required","true");
// 	} else {
// 		$(this).parent().find("input.official-role").hide().removeAttr("required");
// 		$(this).parent().find("input.official-role").val('');
// 	}
// });


// $( function() {
    
//     $( "#organization_dd" ).autocomplete({
//           source: function( request, response ) {
//             // console.log(request);
//             $.ajax( {
//               url: base_url+"/check-organization",
//               method: "POST",
//               data: {
//                 term: request.term,
//                 _token:CSRF_TOKEN
//               },
//               success: function( data ) {
//                 response( data );
//               }
//             } );
//           },
//           minLength: 3,
//           select: function( event, ui ) {
//             // console.log( "Selected: " + ui.item.value + " aka " + ui.item.id + " type " + ui.item.type );
//             $("input[name=organization_id]").val(ui.item.id);
//             $("input[name=organization_type]").val(ui.item.type);
//           }
//         });

// } );

// $(document).on('click','.upload', function(e){
//     e.preventDefault();
// 	var btn = $(this);
// 	var initial_html = btn.html();
//     var form = jQuery(this).parents("form:first");
//     if($(form).valid()){
// 		btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
// 		form.submit();
//     }

//     // var form = jQuery(this).parents("form:first");
//     // form.submit();	
// });



// $(document).on("click", ".participation1", function() {
// 	var btn = $(this);
// 	var msg = '';
// 	if(btn.attr('data-value') == 1){
// 		msg = 'Are you sure <b><u>YOU WANT TO</u></b> participate?';
// 	}else{
// 		msg = "Are you sure <b><u>YOU DO NOT WANT TO</u></b> participate?";
// 	}
// 	bootbox.confirm(msg, function(result) {
//       if(result) {
      	
// 			var initial_html = btn.html();
// 			// btn.attr("disabled","disabled");
// 			btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
// 			var st_val = btn.attr('st-val');
// 			var formAction = base_url+'/'+btn.attr('action');
// 			$.ajax({
// 			    type: "POST",
// 			    data: {
// 			    	_token : CSRF_TOKEN
// 			    },
// 			    url : formAction,
// 			    success : function(data){
// 			    	data = JSON.parse(data);
// 			    	if(!data.success) bootbox.alert(data.message);
// 			    	else {
// 			    		location.reload(true);
// 			    	}
// 			    	btn.html(initial_html);

// 			    }
// 			},"json");
//       	}
//     });
// });

// $(document).on("click", ".participation", function() {
// 	var btn = $(this);
// 	var msg = '<input type=checkbox value=1 id=confirm-participation/>';
// 	if(btn.attr('data-value') == 1){
// 		msg += 'Are you sure <b><u>YOU WANT TO</u></b> participate?';
// 	}else{
// 		msg += "Are you sure <b><u>YOU DO NOT WANT TO</u></b> participate?";
// 	}

// 	var initial_html = btn.html();
// 	addDiv = btn.attr('div-id');
// 	var formAction = base_url+'/'+btn.attr('action');
// 	$(".modal-title").html("Actions to be taken");
// 	$.ajax({
// 	    type: "GET",
// 	    url : formAction,
// 	    success : function(data){
//     		$("#smallModal").modal('show');
// 	    	$(".modal-body").html(data);
// 			initialize();
// 	    }
// 	},"json");

// });

// $(document).on("click",'.confirm_participation',function(e){
//    	e.preventDefault();
//     var btn = $(this);
// 	var initial_html = btn.html();
// 	var tournament_id = btn.attr('tournament-id');
// 	var data_value = btn.attr('data-value');
// 	var formAction = base_url + '/teams/confirm-participation/'+tournament_id+'/'+data_value;

// 	if($("input[type=checkbox]#confirmation").is(":checked")){
// 		// console.log("checked");
// 		var initial_html = btn.html();
// 		btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
// 		btn.attr("disabled","disabled");
// 		$.ajax({
// 		    type: "POST",
// 		    data: {
// 		    	_token : CSRF_TOKEN
// 		    },
// 		    url : formAction,
// 		    success : function(data){
// 		    	data = JSON.parse(data);
// 		    	if(!data.success){
// 	    		 	bootbox.alert(data.message);
// 	    		 	$("#smallModal").modal('hide');
// 		    	}
// 		    	else {
// 		    		location.href = base_url+'/teams/tournaments?tm_id='+tournament_id+'&show_popup=1';
// 		    		btn.removeAttr("disabled");
// 		    	}
// 		    	btn.html(initial_html);

// 		    }
// 		},"json");
// 	}else{
// 		// console.log("unchecked");

// 		bootbox.alert("Please check the confirmation");
// 	}
    
// });




// var resize = $('#upload-demo').croppie({

//     viewport: { // Default { width: 100, height: 100, type: 'square' } 
//         width: 300,
//         height: 200,
//         // type: 'circle' //square
//     },
//     boundary: {
//         width: 400,
//         height: 300
//     },
//     enableOrientation: true,
// 	enableResize: true
// });

// $(document).on("click",".upload-image",function(e){
// 	e.preventDefault();
// 	$("#cropperModal").modal("show");
// 	var target_input = $(this).attr('target-input');
// 	$("#cropperModal").attr("target-input",target_input);
	
// 	$("#image_file").trigger("click");

// });

// $('#image_file').on('change', function () { 
//   var reader = new FileReader();
//     reader.onload = function (e) {
//       resize.croppie('bind',{
//         url: e.target.result
//       }).then(function(){
//         // console.log('jQuery bind complete');
//       });
//     }
//     reader.readAsDataURL(this.files[0]);
// });

// $('.upload-image-cropped').on('click', function (ev) {
// 	  resize.croppie('result', {
// 	    type: 'canvas',
// 	    size: 'viewport',
// 	    enableOrientation: true,
// 	    enableResize: true
// 	  }).then(function (img) {
	  	
// 	    html = '<img src="' + img + '" />';
// 	    if(html != ''){
// 	    	$("#preview-crop-image").html(html);
// 	    }

//     	var dataString = {"image":img,_token : CSRF_TOKEN};
      	
//       	if(img){

// 			$.ajax({
// 		      	url: base_url + "/upload-image",
// 		      	type: "POST",
// 		      	data: dataString,
// 		      	success: function (data) {
// 		      		// console.log(data);
// 			        if(data.success){
// 						var target_input = $("#cropperModal").attr("target-input");
// 			        	var imge = '<img src="'+base_url+'/' + data.image + '" style="max-width:100%; height: auto" />';
// 			        	$("#"+target_input).html(imge);

// 			        	// $("#"+target_input).attr('href',base_url+'/'+data.image);
// 			        	// $("#"+target_input + " a").html('View file');
// 			        	$("input[name="+target_input+"]").val(data.image);
			        	
// 			        	$("#cropperModal").modal("hide");
// 			        }else{
// 			        	bootbox.alert(data.message);
// 			        }
// 		      	}
// 		    });
//       	}

//   	});
// });


// $("input[name=status_tr]").change(function(){
// 	$("#approveBox").toggle();
// });


// $(document).on("click", ".toggle-imp-doc", function() {
// 	var btn = $(this);

// 	bootbox.prompt("Leave a remark for this action", function(remarks) {
//       if(remarks) {
//       		// console.log(remarks);
//       		if(!remarks) {
//       			alert("Please enter remarks");
//       			return;
//       		}

// 			var initial_html = btn.html();
// 			btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
// 			var deleteDiv = btn.attr('div-id');
			
// 			var formAction = base_url+'/'+btn.attr('action');

// 			$.ajax({
// 			    type: "DELETE",
// 			    data: {
// 			    	_token : CSRF_TOKEN,
// 			    	remarks : remarks
// 			    },
// 			    url : formAction,
// 			    success : function(data){
// 			    	data = JSON.parse(data);
// 			    	if(!data.success) bootbox.alert(data.message);
// 			    	else {
			    		
// 			    		$("#"+deleteDiv).hide('500', function(){
// 			    			$("#"+deleteDiv).remove();
// 				    	});
				    	
// 			    	}
// 			    	btn.html(initial_html);

// 			    }
// 			},"json");
//       	}
//     });
// });




// $(document).on("click",".entry_form_eligiblity_check",function(){
// 	var btn = $(this);
// 	var initial_html = btn.html();
// 	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
// 	var association_id = btn.attr("association-id");
// 	var title = btn.attr("data-title");
// 	var redirect_url = btn.attr("action");

// 	$("#detailsModal .modal-title").html(title);
// 	$("#detailsModal .modal-body").html("Loading....");

// 	var formAction = base_url+"/teams/entry_form_eligiblity_check/"+association_id;
// 	$.ajax({
// 	    type: "GET",
// 	    url : formAction,
// 	    success : function(data){
	    	
// 	    	if(data.success){
// 	    		window.location.href = redirect_url;
// 	    	}
// 	    	else {
// 	    		$("#detailsModal").modal("show");
// 	    		$("#detailsModal .modal-body").html(data.message);
// 	    	}
// 	    	btn.html(initial_html);
// 	    }
// 	},"json");
// });

// $(document).ready(function(){
// 	if(entry_form_eligiblity_check == 1){
		
// 		var formAction = base_url+"/teams/entry_form_eligiblity_check/"+association_id;
// 		$.ajax({
// 		    type: "GET",
// 		    url : formAction,
// 		    success : function(data){
		    	
// 		    	if(!data.success){
		    		
// 		    		$("#detailsModal").modal("show");
// 		    		$("#detailsModal .modal-title").html("Required Documents for participation in National Championships");
// 		    		$("#detailsModal .modal-body").html(data.message);
// 		    	}
// 		    }
// 		},"json");
// 	}
// })

// $(document).on("change",".show_reports",function(){
// 	var document_type = $(this).val();
// 	if(document_type){
// 		if(document_type == 4){
// 			$("#report_list").show();

// 		}else{
// 			$("#report_list").hide("");
// 			$(".report_list").val("");
// 		}
// 	}
// });

// $(document).ready(function() {
//   $(".only-numeric").bind("keypress", function (e) {
//   	var target_id = $(this).attr('targe-id');
//   	// console.log(target_id);
//       var keyCode = e.which ? e.which : e.keyCode
           
//       if (!(keyCode >= 48 && keyCode <= 57)) {
//         // $("#"+target_id).css("display", "inline");
        
//         return false;
//       }else{
//         // $("#"+target_id).css("display", "none");
//       }
//   });
// });

// $(".reg_form button").click(function(e){

// 	$(this).attr("disabled","disabled");
// 	$(".reg_form").submit();

// });

// $(document).on("change","#payment_mode",function(){
// 	var val = $(this).val();

// 	console.log(val);
// 	if(val == 6){
// 		$("input[name=attachment]").attr('required',false);
// 		$("#payment-span").html("");
// 	}else{
// 		$("input[name=attachment]").attr('required',true);
// 		$("#payment-span").html("*");

// 	}
// });


// $('#navTabs a').click(function (e) {
//   e.preventDefault()
//   $(this).tab('show')
// });

// $(document).on('click','#search_participants', function(e){
//     e.preventDefault();
//     if($(".ajax_check_form").valid()){
//     	var btn = $(this);
//     	var initial_html = btn.html();
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
//     	var form = jQuery(this).parents("form:first");
// 		var dataString = form.serialize();
// 		var formAction = base_url + '/courses/search_participants';
// 		$.ajax({
// 		    type: "POST",
// 		    url : formAction,
// 		    data : dataString,
// 		    success : function(data){
// 		    	data = JSON.parse(data);
// 		    	if(data.success){
// 		    		$("#OfficialSearchResults").html(data.message);
// 		    	} else {
// 		    		bootbox.alert(data.message);
// 		    	}
//     			btn.html(initial_html);
// 		    }
// 		},"json");
//     }
// });

// $(document).on('click','.add_participant', function(e){
//     e.preventDefault();
//     	var btn = $(this);
//     	var initial_html = btn.html();
//     	btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
//     	var official_id = $(this).attr('participant-id');
//     	var course_id = $(this).attr('course-id');
//     	var user_type = $(this).attr('user-type');
//     	var participant_name = $(this).attr('participant-name');
//     	var association_id = $(this).attr('association-id');
//     	var initial_html = btn.html();
// 		var formAction = base_url + '/courses/add_participant';
// 		$.ajax({
// 		    type: "POST",
// 		    url : formAction,
// 		    data : {
// 		    	official_id:official_id,
// 		    	course_id:course_id,
// 		    	user_type:user_type,
// 		    	official_id:official_id,
// 		    	association_id:association_id,
// 		    	participant_name:participant_name,
// 		    	_token:CSRF_TOKEN},
// 		    success : function(data){

// 		    	data = JSON.parse(data);
// 		    	// console.log(data);
// 		    	if(data.success){
// 		    		$("#participants").append(data.message);
// 		    		$("input[name=official_name]").val('');
// 		    		btn.html('Add');
// 		    	} else {
// 		    		bootbox.alert(data.message);
//     				btn.html('Add');
// 		    	}
// 		    }
// 		},"json");
// });

// $(document).on("click", ".toggleApplicationStatus", function() {
// 	var btn = $(this);

// 	bootbox.prompt("Leave a remarks for this action?", function(remarks) {
//       if(remarks) {
      	
// 			var initial_html = btn.html();
// 			btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');
// 			var divId = btn.attr('div-id');
// 			var count = btn.attr('count');
// 			var action = btn.attr('action');
// 			var app_id = btn.attr('application-id');
			
// 			var formAction = base_url+'/courses/toggleApplicationStatus/'+app_id;

// 			$.ajax({
// 			    type: "post",
// 			    data: {
// 			    	action:action,
// 			    	remarks:remarks,
// 			    	count:count,
// 			    	_token : CSRF_TOKEN
// 			    },
// 			    url : formAction,
// 			    success : function(data){
// 			    	data = JSON.parse(data);
// 			    	if(!data.success) bootbox.alert(data.message);

// 			    	else {
			    		
// 			    		$("#"+divId).replaceWith(data.message);
				    	
// 			    	}
// 			    	btn.html(initial_html);

// 			    }
// 			},"json");
//       	}
//     });
// });

// $(document).ready(function(){
// 	$('.time').mask('00:00:00');
// });

// $(document).on("click", ".reject-noc", function() {
// 	var btn = $(this);

// 	bootbox.prompt("Are you sure to REJECT the transfer?", function(remarks) {
//       if(remarks) {
//       		// console.log(remarks);
//       		if(!remarks) {
//       			alert("Please enter remarks");
//       			return;
//       		}

// 			var initial_html = btn.html();
// 			btn.html(initial_html+' <i class="fa fa-spin fa-spinner"></i>');			
// 			var formAction = btn.attr('action');

// 			$.ajax({
// 			    type: "GET",
// 			    data: {
// 			    	_token : CSRF_TOKEN,
// 			    	remarks : remarks,
// 			    	response_type:'json'
// 			    },
// 			    url : formAction,
// 			    success : function(data){
// 			    	data = JSON.parse(data);
// 			    	if(!data.success) bootbox.alert(data.message);
// 			    	else {
// 			    		bootbox.alert(data.message,function(){

// 			    			location.reload();
// 			    		});
				    	
// 			    	}
// 			    	btn.html(initial_html);

// 			    }
// 			},"json");
//       	}
//     });
// });

// $(document).on("click","#export_photos",function(e){
// 	e.preventDefault();
// 	var form = $(this).parents("form:first");
// 	var dataString = form.serialize();
// 	var action = $(this).attr('action');
// 	location.href = base_url+action + '?'+dataString;
// });


// $(".checkall").click(function(e){

// 	var value = $(this).val();
// 	console.log(value);

// 	if($(this).is(":checked")){
// 		$(".doc_"+value).prop("checked",true);
// 	} else {
// 		$(".doc_"+value).prop("checked",false);
// 	}
	

// });