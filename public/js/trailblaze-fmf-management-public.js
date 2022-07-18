gform.addFilter( 'gform_datepicker_options_pre_init', function( optionsObj, formId, fieldId ) {
	if ( formId == 1 && fieldId == 25 ) {
		optionsObj.minDate = '-19 Y';
		optionsObj.maxDate = '+2 W';
	}
	return optionsObj;
} );


jQuery(document).ready(function ($) {





jQuery(document).on('gform_post_render', function(event, formId){
	console.log('Form Rendered')
	fmf_form_submit()
})

function fmf_form_submit() {


	var pledge_referrer = $("#input_1_42").val()
	var pledge_keystone_eligible = $("#input_1_41").val()
	var pledge_redirect = false

	if(pledge_keystone_eligible){
		$("#pledge_keystone_eligible_success").show()
	}



	if( pledge_referrer.toLowerCase().includes("fundmyfuturepgh.org") && pledge_keystone_eligible ) {

		// pledge_redirect = "https://www.vistashare.com/p/keystone_account_program/keystone-scholars-portal/"
		pledge_redirect = "https://www.vistashare.com/p/keystone_account_program/keystone-scholars-portal/new_account.html"

	} else if( pledge_referrer.toLowerCase().includes("fundmyfuturepgh.org") && !pledge_keystone_eligible ) {


		$(".fmf_pledge_result_descriptor_county_allegheny").show()

		pledge_redirect = false

	} else if ( pledge_referrer.toLowerCase().includes("pa529.com/pasaves") && pledge_referrer.toLowerCase().indexOf("keystone") === -1 ) {

		pledge_redirect = "https://www.vistashare.com/p/keystone_account_program/keystone-scholars-portal/new_account.html"

	} else if ( pledge_referrer.toLowerCase().includes("vistashare.com/p/keystone_account")  ) {

		pledge_redirect = "https://pa529.com"

	}

	// Subscribe parent to simpletexting

	// MOVED TO PHP

	// if ($("#choice_1_35_1").is(':checked')) {
	//
	// 	console.log('Subscribing to simpletexting')
	// 	console.log($("#choice_1_35_1").is(':checked'))
	//
	// 	let cell_phone = $('#input_1_7').val()
	// 	if ($('#input_1_7').val() == '') {
	// 		cell_phone = $('#input_1_6').val()
	// 	}
	//
	// 	let settings = []
	// 	if ($('#input_1_19').val() != "Yes, they have savings accounts in their name.") {
	// 		settings = {
	// 			"async": true,
	// 			"dataType": 'jsonp',
	// 			"crossDomain": true,
	// 			"url": "https://app2.simpletexting.com/v1/group/contact/add?token=fedb608aaace69e3704e25715923766a&group=From%20Database%20(7-17)&phone=" + cell_phone + "&firstName=" + $('#input_1_4_3').val() + "&lastName=" + $('#input_1_4_6').val() + "&email=" + $('#input_1_3').val() + "&has_bank_account=Y",
	// 			"method": "POST",
	// 			"headers": {
	// 				"accept": "application/json",
	// 				"content-type": "application/x-www-form-urlencoded"
	// 			}
	// 		}
	// 	} else {
	// 		settings = {
	// 			"async": true,
	// 			"dataType": 'jsonp',
	// 			"crossDomain": true,
	// 			"url": "https://app2.simpletexting.com/v1/group/contact/add?token=fedb608aaace69e3704e25715923766a&group=From%20Database%20(7-17)&phone=" + cell_phone + "&firstName=" + $('#input_1_4_3').val() + "&lastName=" + $('#input_1_4_6').val() + "&email=" + $('#input_1_3').val() + "&has_bank_account=N",
	// 			"method": "POST",
	// 			"headers": {
	// 				"accept": "application/json",
	// 				"content-type": "application/x-www-form-urlencoded"
	// 			}
	// 		}
	// 	}
	//
	// 	$.ajax(settings).done(function (response) {
	// 		console.log(response)
	// 	})
	// }


}

})
