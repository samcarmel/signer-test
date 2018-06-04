$(document).ready(function() {
	// // get signature position 
	// $($('iframe')[0].contentWindow.document).mousemove(function(event){
	// var offset = $(event.target).offset();
	// console.log("pageX: " + parseInt(event.pageX - offset.left) + ", pageY: " + parseInt(event.pageY - offset.top));
	// })
	// get signature position 
	function signPositions(event) {
			var offset = $(".document-map").offset();
			var docLeft = parseInt(event.pageX - offset.left);
			var docTop = parseInt(event.pageY - offset.top);
			console.log("pageX: " + docLeft + ", pageY: " +docTop );
		}
		// put temporary signature  
	$(".document-map").click(function(event) {
	    
	    var  map = leftPosition = topPosition = signature = "";
	    
	    if($("body").hasClass("sign")){
			 map = $(".document-map").offset();
			 leftPosition = parseInt(event.pageX - 101);
			 topPosition = parseInt(event.pageY - 4);
			if ($("body").hasClass("request")) {
				 signature = "assets/images/mark.png";
			}else{
				 signature = "uploads/signatures/" + $(".data-holder").attr("signature");
			}
			
			$(`<img src="` + signature + `"  class="temporary-signature draggable" page="` + pageNum + `" style="left: ` + leftPosition + `px; top: ` + topPosition + `px;" data-left="` + parseInt(leftPosition - map.left + 29)+ `" data-top="` + parseInt(topPosition - map.top + 18) + `">`).appendTo(".temporary-signatures-holder").draggable({  
				drag: function() { 
					            var map = $(".document-map").offset();
					            var offset = $(this).offset();
					            var xPos = parseInt(offset.left - map.left + 29);
					            var yPos = parseInt(offset.top - map.top + 18);
						$(this).attr("data-top", yPos); 
						$(this).attr("data-left", xPos);  
				} 
			});
	    }else if($("body").hasClass("write")){
			 map = $(".document-map").offset();
			 leftPosition = parseInt(event.pageX + 8);
			 topPosition = parseInt(event.pageY - 4);
			if ($("body").hasClass("request")) {
				 signature = "assets/images/mark.png";
			}else{
				 signature = "uploads/signatures/" + $(".data-holder").attr("signature");
			}
			
			$(`<div class="writing-pad draggable" contenteditable="true"  spellcheck="false"  page="` + pageNum + `" style="left: ` + leftPosition + `px; top: ` + topPosition + `px;" data-left="` + parseInt(leftPosition - map.left + 29)+ `" data-top="` + parseInt(topPosition - map.top + 17) + `"></div>`).appendTo(".temporary-text-holder").draggable({  
				drag: function() { 
					            var map = $(".document-map").offset();
					            var offset = $(this).offset();
					            var xPos = parseInt(offset.left - map.left + 29);
					            var yPos = parseInt(offset.top - map.top + 18);
						$(this).attr("data-top", yPos); 
						$(this).attr("data-left", xPos);  
				} 
			});
			$('.temporary-text-holder').children().last().focus();
	        
	    }
		});
		
		
		// undo signing
	$(".undo-sign").click(function() {
		$('.temporary-signatures-holder').children().last().remove();
		$('.temporary-text-holder').children().last().remove();
	});

			var clipboard = new Clipboard('.copy-link');

		clipboard.on('success', function(e) {
			$('#share').modal('hide');
		    	toastr.success("Link copied to clipboard.", "Copied!");
		});

		clipboard.on('error', function(e) {
		    	toastr.error("Failed to copy, please try again.", "Oops!");
		});
});

	// when sign now is clicked
	$(".sign").click(function() {
			$("body").addClass("sign");
	});
	
	// when sign now is clicked
	$(".write").click(function() {
			$("body").addClass("write");
	});


// when request sign is clicked
$(".request-send-file").submit(function(event) {
	event.preventDefault();
	var restricted = $(".restricted"), form = $(this);
	$(this).parsley().validate();
	if (($(this).parsley().isValid())) {
		if(restricted.is(':checked')){
			$("#request-sign").modal('hide');
			swal({
				title: "Signing points",
				text:  "Click on points where the receiver will sign. The dotted signing point box will not appear on the document, it is for guidance only.",
				showCancelButton: true,
				confirmButtonText: "Start Selecting",
				closeOnConfirm: true
			}, function() {
				$("body").addClass("sign request");
			});

		}else{
			// send a signing request and allow signing anywhere
			sendNonRestrictedSigningRequest();
	}
}
})


		// close sign mode
	$(".close-overlay").click(function() {
		$("body").removeClass("sign");
		$("body").removeClass("write");
		$('.temporary-signatures-holder').empty();
		$('.temporary-text-holder').empty();
	})

	// draggable elements
$(".draggable").draggable();

$(".save-file").click(function(){

	if ($("body").hasClass("write")) {
		if($(".writing-pad").length == 0){
			swal({
				title: "Please write Something!",
				text: "Please write Something before saving.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Okay",
				closeOnConfirm: true
			});
		}else{
			var myArr = [];
			$('.temporary-text-holder').find(".writing-pad").each(function(index, value) {
				myArr.push({
					text: $(this).text(),
					pageNumber: $(this).attr('page'),
					xPosition: $(this).attr('data-left'),
					yPosition: $(this).attr('data-top')
				});
			});
			myJson = JSON.stringify(myArr);
			signDocument(myJson);
		  }
	}else if ($("body").hasClass("request")) {
		if($(".temporary-signature").length == 0){
			swal({
				title: "No Signing points!",
				text: "You have not selected any signing points, click continue to allow receiver to sign anywhere or cancel to select signing points",
				type: "warning",
				showCancelButton: false,
				confirmButtonText: "continue",
				closeOnConfirm: true
			}, function(){
				sendNonRestrictedSigningRequest();
			});
		}else{
			sendRestrictedSigningRequest();
		}
	}else{
		if($(".temporary-signature").length == 0){
			swal({
				title: "Please sign!",
				text: "Please sign before saving.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Okay",
				closeOnConfirm: true
			});
		}else{
			var myArr = [];
			$('.temporary-signatures-holder').find(".temporary-signature").each(function(index, value) {
				myArr.push({
					pageNumber: $(this).attr('page'),
					xPosition: $(this).attr('data-left'),
					yPosition: $(this).attr('data-top')
				});
			});
			myJson = JSON.stringify(myArr);
			signDocument(myJson);
		  }

	}

})

// send signing request and allow sign of specific points
function sendRestrictedSigningRequest() {
			$("body").addClass("loading");
			var myArr = [];
			$('.temporary-signatures-holder').find(".temporary-signature").each(function(index, value) {
				myArr.push({
					pageNumber: $(this).attr('page'),
					xPosition: $(this).attr('data-left'),
					yPosition: $(this).attr('data-top')
				});
			});
			myJson = JSON.stringify(myArr);
			var action = "signrequest",
			   positions = myJson,
			   form = $(".request-send-file"),
			   email = form.find(".receiver-email").val(),
			   note = form.find(".receiver-note").val(),
			   restricted = 1,
			   url = "files/sendemails.php";
			// Send the data using post
			var posting = $.post(url, { action: action, positions: positions, sharingKey: sharingKey, email: email, note: note, restricted: restricted });
			posting.done(function(data) {
				$("body").removeClass("loading");
				responseText = jQuery.parseJSON(data);
				if (responseText.status == 1) {
					restrictedReset()
					$(".request-send-file")[0].reset();
					$("body").removeClass("sign request");
					swal({
						title: "Request sent!",
						text: "Document signing request was successfully sent..",
						type: "success",
						showCancelButton: false
					});
				} else {
					swal({
						title: "Oops!",
						text: "Failed to send request, please try again.",
						type: "error",
						showCancelButton: false
					});
				}
			});
}

// send signing request and allow sign anywhere
function sendNonRestrictedSigningRequest() {
		
		   $("body").addClass("loading");
			var action = "signrequest",
				form = $(".request-send-file"),
			   email = form.find(".receiver-email").val(),
			   note = form.find(".receiver-note").val(),
			   restricted = 0,
			   positions = "",
			   url = "files/sendemails.php";
			// Send the data using post
			var posting = $.post(url, { action: action, positions: positions, sharingKey: sharingKey, email: email, note: note, restricted: restricted });
			posting.done(function(data) {
				$("body").removeClass("loading");
				responseText = jQuery.parseJSON(data);
				if (responseText.status == 1) {
					$("#request-sign").modal('hide');
					restrictedReset()
					$(".request-send-file")[0].reset();
					$("body").removeClass("sign request");
					swal({
						title: "Request sent!",
						text: "Document signing request was successfully sent..",
						type: "success",
						showCancelButton: false
					});
				} else {
					swal({
						title: "Oops!",
						text: "Failed to send request, please try again.",
						type: "error",
						showCancelButton: false
					});
				}

		})
}

function restrictedReset() {
	var restricted = $(".restricted");
		if(restricted.is(':checked')){
			$(".restricted").click();
		}
}

// decline signing invitation
$(".decline").click(function(){
		swal({
			title: "Are you sure!",
			text: "Are you sure you want to deline the signing invitation.",
			type: "error",
			showCancelButton: true,
			confirmButtonColor: "#ff1a1a",
			confirmButtonText: "Yes decline",
			closeOnConfirm: true
		}, function(){
		   $("body").addClass("loading");
			var action = "decline",
			   url = "files/sendemails.php";
			// Send the data using post
			var posting = $.post(url, { action: action, signingKey: signingKey, sharingKey: sharingKey });
			posting.done(function(data) {
				$("body").removeClass("loading");
				responseText = jQuery.parseJSON(data);
				if (responseText.status == 1) {
					swal({
						title: "Invitation declined!",
						text: "You have successfully declined the signing invitation.",
						type: "success",
						showCancelButton: false,
						confirmButtonText: "Go to dashboard",
						closeOnConfirm: false
					}, function(){
						window.location.href = "dashboard";
					})
				} else {
					swal({
						title: "Oops!",
						text: "Failed to complete request, please try again.",
						type: "error",
						showCancelButton: false
					});
				}

			})
		});
})

// accept signing invitation
$(".accept").click(function(){
	if (signRestricted == 1) {
		swal({
			title: "One click sign!",
			text: "The sender wants specific points signed, click sign now and we will sign for you.",
			type: "info",
			showCancelButton: true,
			confirmButtonText: "Sign now",
			closeOnConfirm: true
		}, function(){
			signDocument(signingPoints);
		})

	}else if(signRestricted == 0){
		$("body").addClass("sign");
	}
})

function signDocument(positions) {
	$("body").addClass("loading");
	
	var action = "sign",
	    title = "Document signed!",
	    activity = "signed";
		url = "files/ajaxProcesses.php";
		
		
	if ($("body").hasClass("write")) {
	    action = "write",
	    title = "Document updated!",
	    activity = "written";
	}

	var posting = $.post(url, { action: action, documentKey: sharingKey, signingKey: signingKey, positions: positions, signingMode: signingMode });
	posting.done(function(data) {
		$("body").removeClass("loading");
		responseText = jQuery.parseJSON(data);
		if (responseText.status == 1) {
			$("body").removeClass("sign");
			swal({
				title: title,
				text: "Your document has been successfully "+activity,
				type: "success",
				showCancelButton: false,
				confirmButtonText: "View "+activity+" Document",
				closeOnConfirm: true
			}, function() {
				$("body").addClass("loading");
				window.location.reload();
			});
		} else {
			swal({
				title: "Oops!",
				text: "Document signing failed, please try again.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Try again",
				closeOnConfirm: true
			});
		}
	});
}




