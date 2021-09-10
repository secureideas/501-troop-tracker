// Wait for the DOM to be ready
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("form[name='createEventForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      eventName: "required",
      eventVenue: "required",
      location: "required",
      datepicker: "date",
      datepicker2: "date",
      numberOfAttend: {
        required: true,
        digits: true
      },
      requestedNumber: {
        required: true,
        digits: true
      },
      squadm: {
        required: true,
        range: [0, 5]
      },
      secure: {
        required: true,
        range: [0, 1]
      },
      blasters: {
        required: true,
        range: [0, 1]
      },
      lightsabers: {
        required: true,
        range: [0, 1]
      },
      parking: {
        required: true,
        range: [0, 1]
      },
      mobility: {
        required: true,
        range: [0, 1]
      },
      label: {
        required: true,
        range: [0, 7]
      },
      limitedEvent: {
        required: true,
        range: [0, 1]
      },
      limit501st: {
        required: false,
        digits: true
      },
      limitDroid: {
        required: false,
        digits: true
      },
      limitTotal: {
        required: false,
        digits: true
      },
      limitRebels: {
        required: false,
        digits: true
      },
      limitMando: {
        required: false,
        digits: true
      }
    },
    // Specify validation error messages
    messages: {
      eventName: "Please enter the event name.",
      eventVenue: "Please enter the event venue.",
      location: "Please enter the location.",
	  squadm: "Please select a squad.",
      datepicker: "Please enter a proper date/time.",
      datepicker2: "Please enter a proper date/time.",
      numberOfAttend: "Please enter a valid number.",
      requestedNumber: "Please enter a valid number.",
      secure: "Please enter a valid option.",
      blasters: "Please enter a valid option.",
      lightsabers: "Please enter a valid option.",
      parking: "Please enter a valid option.",
      mobility: "Please enter a valid option.",
      label: "Please enter a valid option.",
      limitedEvent: "Please enter a valid option."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
     var r = confirm("Are you sure you want to create this event?");

      if (r == true)
      {
        $.ajax({
          type: "POST",
          url: form.action,
          data: $(form).serialize() + "&submitEvent=1",
          success: function(data)
          {
            var json = JSON.parse(data);

            // If success
            if(json.success == "success")
            {
              // Clear Form
              $("#eventName").val("");
              $("#eventVenue").val("");
              $("#location").val("");
			  $("#squadm").val("null");
              $("#datepicker").val("");
              $("#datepicker2").val("");
              $("#website").val("");
              $("#numberOfAttend").val("");
              $("#requestedNumber").val("");
              $("#requestedCharacter").val("");
              $("#secure").val("null");
              $("#blasters").val("null");
              $("#lightsabers").val("null");
              $("#parking").val("null");
              $("#mobility").val("null");
              $("#amenities").val("");
              $("#comments").val("");
              $("#label").val("null");
              $("#limitedEvent").val("null");
              $("#era").val("4");
              $("#limit501st").val("500");
              $("#limitRebels").val("500");
              $("#limitMando").val("500");
              $("#limitDroid").val("500");
			  $("#limitTotal").val("500");
              $("#referred").val("");
            }

            // Alert to success
            alert(json.data);
          }
        });
      }
    }
  });

  $("form[name='editUserForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      user: "required",
	  forumid: "required",
      email: {
        required: true,
        email: true
      },
      squad: {
        required: true,
        range: [1, 9]
      },
      permissions: {
        required: true,
        range: [0, 2]
      },
      tkid: {
        required: true,
        digits: true
      }
    },
    // Specify validation error messages
    messages: {
      user: "Please enter a name.",
      squad: "Please enter a squad.",
      permissions: "Please enter permissions.",
      tkid: "Please enter a TKID.",
	  forumid: "Please enter a forum ID."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      var r = confirm("Are you sure you want to edit this user?");

      if (r == true)
      {
        $.ajax({
          type: "POST",
          url: form.action,
          data: $(form).serialize() + "&submitUserEdit=1",
          success: function(data)
          {
            var json = JSON.parse(data);

            // Hide interface
            $("#submitEditUser").val("Edit");
            $("#editUserInfo").hide();

            // Fix text when changing the title
            $("#userID option:selected").text($("#user").val());

            // Alert to success
            alert(json.data);
          }
        });
      }
    }
  });

  $("form[name='editEventForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      eventName: "required",
      eventVenue: "required",
      location: "required",
      datepicker: "date",
      datepicker2: "date",
      numberOfAttend: {
        required: true,
        digits: true
      },
      requestedNumber: {
        required: true,
        digits: true
      },
      squadm: {
        required: true,
        range: [0, 5]
      },
      secure: {
        required: true,
        range: [0, 1]
      },
      blasters: {
        required: true,
        range: [0, 1]
      },
      lightsabers: {
        required: true,
        range: [0, 1]
      },
      parking: {
        required: true,
        range: [0, 1]
      },
      mobility: {
        required: true,
        range: [0, 1]
      },
      label: {
        required: true,
        range: [0, 7]
      },
      limitedEvent: {
        required: true,
        range: [0, 1]
      },
      limit501st: {
        required: false,
        digits: true
      },
      limitDroid: {
        required: false,
        digits: true
      },
      limitTotal: {
        required: false,
        digits: true
      },
      limitRebels: {
        required: false,
        digits: true
      },
      limitMando: {
        required: false,
        digits: true
      }
    },
    // Specify validation error messages
    messages: {
      eventName: "Please enter the event name.",
      eventVenue: "Please enter the event venue.",
      location: "Please enter the location.",
	  squadm: "Please select a squad.",
      datepicker: "Please enter a proper date/time.",
      datepicker2: "Please enter a proper date/time.",
      numberOfAttend: "Please enter a valid number.",
      requestedNumber: "Please enter a valid number.",
      secure: "Please enter a valid option.",
      blasters: "Please enter a valid option.",
      lightsabers: "Please enter a valid option.",
      parking: "Please enter a valid option.",
      mobility: "Please enter a valid option.",
      label: "Please enter a valid option.",
      limitedEvent: "Please enter a valid option."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      var r = confirm("Are you sure you want to edit this event?");

      if (r == true)
      {
        $.ajax({
          type: "POST",
          url: form.action,
          data: $(form).serialize() + "&submitEventEdit=1",
          success: function(data)
          {
            var json = JSON.parse(data);

            // Hide interface
            $("#submitEdit").val("Edit");
            $("#editEventInfo").hide();

            // Fix text when changing the title
            $("#eventId option:selected").text($("#eventName").val());

            // Alert to success
            alert(json.data);
          }
        });
      }
    }
  });

  $("form[name='loginForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      tkid: "required",
      password: "required"
    },
    // Specify validation error messages
    messages: {
      tkid: "Please enter your TKID.",
      password: "Please enter your password."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });

  $("form[name='changeEmailForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      email: {
        required: true,
        email: true
      }
    },
    // Specify validation error messages
    messages: {
      email: "Please enter a valid e-mail."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      var form = $("#changeEmailForm");
      var url = form.attr("action");

      $.ajax({
        type: "POST",
        url: url,
        data: form.serialize() + "&emailButton=1",
        success: function(data)
        {
          alert(data);
        }
      });
    }
  });

  $("form[name='changePhoneForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      //phone: "required"
    },
    // Specify validation error messages
    messages: {
      //phone: "Please enter a phone number."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      var form = $("#changePhoneForm");
      var url = form.attr("action");

      $.ajax({
        type: "POST",
        url: url,
        data: form.serialize() + "&phoneButton=1",
        success: function(data)
        {
          alert(data);
        }
      });
    }
  });

  $("form[name='changeNameForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      name: "required"
    },
    // Specify validation error messages
    messages: {
      name: "Please enter a name."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      var form = $("#changeNameForm");
      var url = form.attr("action");

      $.ajax({
        type: "POST",
        url: url,
        data: form.serialize() + "&nameButton=1",
        success: function(data)
        {
          alert(data);
        }
      });
    }
  });

  $("form[name='requestAccessForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      tkid: {
        required: true,
        digits: true,
        maxlength: 11
      },
      email: {
        required: true,
        email: true
      },
	  forumid: "required",
      password: "required",
      passwordC: "required",
      name: "required"
    },
    // Specify validation error messages
    messages: {
      tkid: "Please enter your TKID and make sure it is no more than eleven (11) characters.",
	  forumid: "Please enter your FL 501st Forum Username.",
      password: "Please enter your password.",
      passwordC: "Please re-enter your password to confirm it.",
      email: "Please enter a valid e-mail.",
      name: "Please enter your name."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
        $.ajax({
          type: "POST",
          url: form.action,
          data: $(form).serialize() + "&submitRequest=1",
          success: function(data)
          {
            // Hide Form
            $("#requestAccessFormArea").hide();

            // Show data
            $("#requestAccessFormArea2").html(data);
          }
        });
    }
  });

  $("form[name='signupForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      costume: {
        required: true,
        digits: true
      },
      status: {
        required: true,
        range: [0, 5]
      }
    },
    // Specify validation error messages
    messages: {
      costume: "Please choose a costume.",
      status: "Please choose your status."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
        $.ajax({
          type: "POST",
          url: form.action,
          data: $(form).serialize() + "&submitSignUp=1",
          success: function(data)
          {
            $("#signeduparea").show();
            $("#signuparea").hide();
          }
        });
    }
  });

  $("form[name='signupForm2']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      costume: {
        required: true,
        digits: true
      },
      status: {
        required: true,
        range: [0, 5]
      }
    },
    // Specify validation error messages
    messages: {
      costume: "Please choose a costume.",
      status: "Please choose your status."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
        $.ajax({
          type: "POST",
          url: form.action,
          data: $(form).serialize() + "&submitSignUp=1",
          success: function(data)
          {
            // Get JSON Data
            var json = JSON.parse(data);

            if(json.success == "failed")
            {
              alert(json.data);
            }
            else
            {
              // Put data in html
              $("#signuparea").html(json.data);

              // Do the rest...
              $("#signeduparea").show();
			  $("#signuparea1").hide();
			  $("#hr1").hide();
              $("#rosterTableNoData").hide();
            }
          }
        });
    }
  });

  $("form[name='cancelForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
    },
    // Specify validation error messages
    messages: {
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {

      var r = confirm("Are you sure you want to cancel this troop?");

      var id = $("#myId").val();

      if (r == true)
      {
        $.ajax({
          type: "POST",
          url: form.action,
          data: $(form).serialize() + "&submitCancelTroop=1&troopidC=" + $('#troopidC').val() + "&myId=" + $('#myId').val() + "&limitedevent=" + $("#limitedEventCancel").val(),
          success: function(data)
          {
      			var html = `
      			<div class="cancelFormArea">
      			<p>
      				<b>You have canceled this troop.</b>
      			</p>
      			
      			<form action="process.php?do=undocancel" method="POST" name="undoCancelForm" id="undoCancelForm">
      				<input type="submit" name="undoCancelButton" id="undoCancelButton" value="I changed my mind" />
      			</form>
      			</div>`;
			
      			// Change to undo cancel
            $("#signeduparea").html(html);
			
      			// Change select options
      			$("#trooperRosterCostume").html($("#modifysignupFormCostume option:selected").text());
      			$("#trooperRosterBackup").html($("#modiftybackupcostumeForm option:selected").text());
            $("#" + id + "Status").html("<div name='trooperRosterStatus' id='trooperRosterStatus'>Canceled</div>");
          }
        });
      }
    }
  });

  $("form[name='createUserForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      name: "required",
	  forumid: "required",
      email: {
        required: true,
        email: true
      },
      squad: {
        required: true,
        range: [0, 1]
      },
      tkid: {
        required: true,
        digits: true
      },
      password: {
        required: true,
        minlength: 6
      }
    },
    // Specify validation error messages
    messages: {
      name: "Please enter a name.",
	  forumid: "Please enter the FL 501st Username.",
      email: "Please enter a valid e-mail address.",
      squad: "Please choose a squad.",
      tkid: "Please enter a valid ID.",
	  password: "Please enter a valid password."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
     var r = confirm("Are you sure you want to create this event?");

      if (r == true)
      {
        $.ajax({
          type: "POST",
          url: form.action,
          data: $(form).serialize() + "&submitEvent=1",
          success: function(data)
          {
            var json = JSON.parse(data);

            // If success
            if(json.success == "success")
            {
              // Clear Form
              $("#name").val("");
              $("#email").val("");
			  $("#forumid").val("");
              $("#phone").val("");
              $("#squad").val("0");
              $("#permissions").val("0");
              $("#tkid").val("");
			  $("#password").val("");
            }

            // Alert to success
            alert(json.data);
          }
        });
      }
    }
  });

  $("form[name='commentForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      comment: "required",
      important: {
        required: true,
        range: [0, 1]
      }
    },
    // Specify validation error messages
    messages: {
      comment: "Please enter a comment.",
      important: "Please pick an importance."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      $.ajax({
        type: "POST",
        url: "process.php?do=postcomment",
        data: $(form).serialize() + "&submitComment=1",
        success: function(data)
        {
          var json = JSON.parse(data);

          // Update HTML
          $("#commentArea").html(json.data);

          // Return vars to default
          $("#comment").val("");
          $("#important").val("0");
        }
      });
    }
  });

  $("form[name='registerForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      tkid: "required",
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 6
      },
      password2: {
        required: true,
        minlength: 6
      }
    },
    // Specify validation error messages
    messages: {
      tkid: "Please enter your TKID.",
      password: "Please enter a six (6) character password.",
      password2: "Please enter a matching six (6) character password.",
      email: "Please enter a valid e-mail address."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });

  $("body").on("click", "#troopRosterFormAdd", function(e) {
    $("form[name='troopRosterFormAdd']").validate({
      // Specify validation rules
      rules: {
        // The key name on the left side is the name attribute
        // of an input field. Validation rules are defined
        // on the right side
        trooperSelect: "required",
        costume: {
          required: true,
          range: [0, 9999]
        },
        status: {
          required: true,
          range: [0, 4]
        }
      },
      // Specify validation error messages
      messages: {
         costume: "Please select a costume.",
         status: "Please select a status."
      },
      // Make sure the form is submitted to the destination defined
      // in the "action" attribute of the form when valid
      submitHandler: function(form) {
		// Make user pick a costume
		if($("#status").val() ==  3 && $("#attendedcostume").val() == 0)
		{
			alert("Please pick a costume.");
		}
		else
		{
			$.ajax({
			  type: "POST",
			  url: form.action,
			  data: $(form).serialize() + "&troopRosterFormAdd=1",
			  success: function(data)
			  {
				//$("#submitRoster").val("Roster");
				//$("#rosterInfo").html("");
				//$("#rosterInfo").hide();
				
				alert("Trooper added to roster!");
				
				// Show table if form is hidden
				if($("#troopRosterForm").is(":hidden"))
				{
					$("#troopRosterForm").show();
				}
				
				// Costume One
				var string1 = '<select name="costumeValSelect' + $("#trooperSelect").val() + '">';
				
				for(var i = 0; i <= jArray1.length - 1; i++)
				{
					if($("#costume").val() == jArray2[i])
					{
						string1 += '<option value="' + jArray2[i] + '" SELECTED>' + jArray1[i] + '</option>';
					}
					else
					{
						string1 += '<option value="' + jArray2[i] + '">' + jArray1[i] + '</option>';
					}
				}
				
				string1 += '</select>';
				
				// Costume Two
				var string2 = '<select name="costumeVal' + $("#trooperSelect").val() + '" id="costumeVal' + $("#trooperSelect").val() + '">';
				
				for(var i = 0; i <= jArray1.length - 1; i++)
				{
					if($("#costumebackup").val() == jArray2[i])
					{
						string2 += '<option value="' + jArray2[i] + '" SELECTED>' + jArray1[i] + '</option>';
					}
					else
					{
						string2 += '<option value="' + jArray2[i] + '">' + jArray1[i] + '</option>';
					}
				}
				
				string2 += '</select>';
				
				// Costume Three
				var string3 = '<select name="attendcostumeVal' + $("#trooperSelect").val() + '" id="attendcostumeVal' + $("#trooperSelect").val() + '">';
				
				for(var i = 0; i <= jArray1.length - 1; i++)
				{
					if($("#attendedcostume").val() == jArray2[i])
					{
						string3 += '<option value="' + jArray2[i] + '" SELECTED>' + jArray1[i] + '</option>';
					}
					else
					{
						string3 += '<option value="' + jArray2[i] + '">' + jArray1[i] + '</option>';
					}
				}
				
				string3 += '</select>';
				
				// Show form / table
				$('#rosterTable').append('<tr id="roster_' + $("#trooperSelect").val() + '" name="roster_' + $("#trooperSelect").val() + '">');
				$('#rosterTable').append('<td><input type="hidden" name="eventId" id="eventId" value = "' + $("#troopid").val() + '" /><input type="radio" name="trooperSelectEdit" id="trooperSelectEdit" value="' + $("#trooperSelect").val() + '" /></td>');
				$('#rosterTable').append('<td><div name="tknumber1' + $("#trooperSelect").val() + '" id="tknumber1' + $("#trooperSelect").val() + '">TK NUMBER HERE</div></td>');
				$('#rosterTable').append('<td><div name="costume1' + $("#trooperSelect").val() + '" id="costume1' + $("#trooperSelect").val() + '">' + $("#costume option:selected").text() + '</div><div name="costume2' + $("#trooperSelect").val() + '" id="costume2' + $("#trooperSelect").val() + '" style="display:none;">' + string1 + '</div></td>');
				$('#rosterTable').append('<td><div name="backup1' + $("#trooperSelect").val() + '" id="backup1' + $("#trooperSelect").val() + '">IF EMPTY COSTUME BACK UP</div><div name="backup2' + $("#trooperSelect").val() + '" id="backup2' + $("#trooperSelect").val() + '" style="display:none;">' + string2 + '</div></td>');
				$('#rosterTable').append('<td><div name="status1' + $("#trooperSelect").val() + '" id="status1' + $("#trooperSelect").val() + '">GET STATUS</div><div name="status2' + $("#trooperSelect").val() + '" id="status2' + $("#trooperSelect").val() + '" style="display:none;">SELECT</div></td>');
				$('#rosterTable').append('<td><div name="reason1' + $("#trooperSelect").val() + '" id="reason1' + $("#trooperSelect").val() + '">IF EMPTY</div><div name="reason2' + $("#trooperSelect").val() + '" id="reason2' + $("#trooperSelect").val() + '" style="display:none;"><input type="text" id="reasonVal' + $("#trooperSelect").val() + '" name="reasonVal' + $("#trooperSelect").val() + '" value="' + $("#reason").val() + '" /></div></td>');
				$('#rosterTable').append('<td><div name="attend1' + $("#trooperSelect").val() + '" id="attend1' + $("#trooperSelect").val() + '">DID ATTEND</div><div name="attend2' + $("#trooperSelect").val() + '" id="attend2' + $("#trooperSelect").val() + '" style="display:none;"><select name="attendVal' + $("#trooperSelect").val() + '"><option value="0" ECHO SELECT>Did not attend</option><option value="1" ECHO SELECT>Attended</option></select></div></td>');
				$('#rosterTable').append('<td><div name="attendcostume1' + $("#trooperSelect").val() + '" id="attendcostume1' + $("#trooperSelect").val() + '">IF EMPTY</div><div name="attendcostume2' + $("#trooperSelect").val() + '" id="attendcostume2' + $("#trooperSelect").val() + '" style="display:none;">' + string3 + '</div></td>');
				
				// Reset
				$("#costume").val("null");
				$("#costumebackup").val("0");
				$("#status").val("null");
				$("#reasonBlock").hide();
				$("#reason").val("");
				$("#attendBlock").hide();
				$("#attendedcostume").val("0");
				$("input[id='trooperSearch']").val("").trigger("input");
			  }
			});
		}
      }
    });
  });

  $("form[name='changePasswordForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      oldpassword: "required",
      newpassword: {
        required: true,
        minlength: 6
      },
      newpassword2: {
        required: true,
        minlength: 6
      }
    },
    // Specify validation error messages
    messages: {
      oldpassword: "Please enter your old password.",
      newpassword: "Please enter a six (6) character password.",
      newpassword2: "Please enter a matching six (6) character password."
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      var form = $("#changePasswordForm");
      var url = form.attr("action");

      $.ajax({
        type: "POST",
        url: url,
        data: form.serialize() + "&changePasswordSend=1",
        success: function(data)
        {
          alert(data);
        }
      });
    }
  });

  $("form[name='forgotPasswordForm']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      tkid: "required",
      email: {
        required: true,
        email: true
      }
    },
    // Specify validation error messages
    messages: {
      tkid: "Please enter your TK ID.",
      email: "Please enter a valid e-mail address.",
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(data)
        {
          //alert(data); // show response from the php script.
        }
    });
      form.submit();
    }
  });
});