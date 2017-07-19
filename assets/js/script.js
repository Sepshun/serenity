// -------------------------------------
// SERENITY SCRIPT ---------------------
// Written by Sepshun & JeremyJaydan ---

// Sections I've edited are marked with -=-=- for ease 

$(document).ready(function() {
	// CONTAINS FUNCTION EXTENSION
	String.prototype.contains = function(it) { return this.indexOf(it) != -1; };

	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	// New log func | ability to disable logs
	const log = function(){
		if(!log.disableLogs) return console.log.apply(null, arguments);
	};

	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	// getInputs functionality
	const getInputs = function(inputNames, queryType){
		let inputs = {};
		inputNames.forEach(function(input){inputs[input] = $("#" + queryType + "-modal input[name='" + input + "']").val()});
		return inputs;
	};

	log.disableLogs = false;

	// ------------------------------------
	// ACCOUNT PANEL | OPENING/CLOSING
	$('#header-account').click(function() { $(this).toggleClass('open'); });

	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	// CONTEXT MENU
	// Bind on context menu click
	var c = "";
	c.id = 0;
	$('#container table tbody tr').bind('contextmenu', function(event) {

		// Define attributes to look for
		let attributes = ["id", "display", "name", "sub", "genres", "facebook", "soundcloud", "youtube", "instagram", "twitter", "webpage", "flags"];

		c         = $(event.target);
		c.parent  = c.parent();

		// Get attributes and set values
		attributes.forEach(function(attribute){
			c[attribute] = c.parent.attr(attribute);
		});
		// Note: since this is a for loop now, 
		// "fb, sc, yt, ig" etc are now the full names "facebook, soundcloud ..."
		
		// Make sure it's not an anchor tag
		if (c.prop('nodeName') !== 'A') {
			event.preventDefault();

			// Add selected class
			$('#container table tbody tr').removeClass('selected');
			c.parent.addClass('selected');

			// Close any opened table dropdowns
			$('.table-dropdown-cell').removeClass('open');

			// Open and position menu
			$('#context-menu').finish().toggle(100).css({
				top: event.pageY + 1 +'px',
				left: event.pageX + 1 +'px'
			});
		}
	});

	// Bind on mousedown
	$(document).bind('mousedown', function(e) {
		if (!$(e.target).parents('#context-menu').length > 0) {
			$('#context-menu').hide(100);
			$('#container table tbody tr').removeClass('selected');
			// log(e.target);
		}
	});

	// Click menu option -=-=-=-=-=-=-=-
	$('#context-menu ul li').click(function() {
		$('#context-menu').hide(100);
		$('.table-dropdown-cell').removeClass('open');
		var target = $(this).attr('target');

		// Changed the list of variables to just "c"
		updateModal(target, c);
		// You can then just call variables from 
		// the c within the updateModal function
	});


	// ------------------------------------
	// SPLIT FLAGS FUNCTION
	var allVal = new Array();
	function split(type, val) {
		if (type === 'flag') {
			var splitVal = val.split(',');
			for (let i=0; i < splitVal.length; i++) {
				var valSplit = splitVal[i].split('|');
				allVal.push(valSplit);
			}
		} else if (type === 'reason') {
			var allVal = val.split('|');
		}
		return allVal;
	}


	// ------------------------------------
	// MODALS
	// Open Modals
	$('.modal-btn').click(function() {
		var targetModal = $(this).attr('target');
		$('.modal').removeClass('show');
		$('#'+targetModal+'-modal, #black-underlay').addClass('show');
	});
	// Close Modals
	$('.close-btn, #black-underlay').click(function() {
		$('.modal, #black-underlay').removeClass('show');
	});

	// ------------------------------------
	// MODAL TOP NAME CHANGE
	$('.pr-input-box input[name="pr-name"]').keyup(function() {
		var val = $(this).val();
		
		// Check values to make sure they exist and don't start with a space
		if (val != "" && val.search(' ') != 0) {$(this).parent().parent().parent().find('.modal-title').text(val);}
		else {$(this).parent().parent().parent().find('.modal-title').text('Untitled');}

		$.post('check-data.php', {type: 'promo', input: val}, function(res) {
			// Figure out how to display this message... dunno yet
			if (res) {
				// If the entry already exists
			} else {
				// If it doesn't
			}
		});
	});

	// ------------------------------------
	// DISPLAY BUTTON AND STYLES
	$('.pr-reason').hide();
	$('.pr-display-span').click(function(evt) {
		$(this).parent().parent().toggleClass('pr-display pr-no-display');
		// Determine whether checked or not and display the reason box
		if ($(this).parent().parent().hasClass('pr-display')) {
			$(this).parent().parent().find('.pr-reason').hide(200);
		} else {
			$(this).parent().parent().find('.pr-reason').show(200);
		}
	});

	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	// SUBMISSION TYPE DETERMINATION
	const subContainChecks = {
		"https://soundcloud.com/": "soundcloud",
		// soundcloud entry must be first to skip web and the rest.
		"http://": "web",
		"https://": "web",
		"@": "email"
	}

	function subDetermine(target, sub) {
		// Removing all classes first
		target.removeClass("sub-web sub-email sub-sc");

		for(let key in subContainChecks){
			let check = typeof subContainChecks[key] === "object" ? subContainChecks[key] : [subContainChecks[key]];
			if(sub.contains(key)){
				let name = check[1] || check[0];
				// class: sub-name
				target.addClass("sub-" + name);
				// text: name.toUpperCase()
				target.find('span').text(check[0].toUpperCase());
				return;
			}
		}

		// None of the above
		target.find('span').text('N/A');

	}

	$('.pr-input-box input[name="sub"]').keyup(function() {
		var val = $(this).val();
		var target = $(this).parent();
		subDetermine(target, val);
	});

	// ------------------------------------
	// SOCIAL LINK CONFIRMATION
	function socialCheck(target, val, contains) {
		if (val.contains(contains)) { target.addClass('active'); }
		else { target.removeClass('active'); }
	}

	$('.social-input input').keyup(function() {
		var target = $(this);
		var val = $(this).val();
		var contains = $(this).attr('contains');
		socialCheck(target, val, contains);
	});

	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
	// SUBMIT BUTTON

	$('.submit-btn:not(.scan-btn)').click(function() {
		var queryType = $(this).attr('query-type'), display, name, genres, inputs, flags;

		name = $('#'+queryType+'-modal input[name="pr-name"]').val();
		inputs = getInputs(["sub", "facebook", "soundcloud", "youtube", "instagram", "twitter", "webpage"], queryType);
		genres = "";

		// STORE ALL THE INFO
		if (!$('#'+queryType+'-modal input[name="display"]:checked').length > 0) {display = 'false|' + $('#'+queryType+'-modal select').val() + "|" + $('#'+queryType+'-modal input[name="inactive-info"]').val();}
		else {display = 'true';}
		$('#'+queryType+'-modal input[name="genre"]:checked').each(function(){ genres += $(this).val() + "," });
		genres = genres.replace(/,\s*$/, "");
		flags = "";

		// Everything inside the postObj
		// note: if the key is the same as the variable name, you can just pass 1 argument into an object
		let postObj = Object.assign({ type:queryType, id:c.id, display, name, genres, flags }, inputs);

		if (queryType === 'remove' && $('#remove-modal input[name="remove"]').val() === 'REMOVE' || queryType !== 'remove') {
			$.post('query.php', postObj, function(){location.reload()});
		}
	});

	// ------------------------------------
	// UPDATE MODAL FUNCTIONS
	
	// The flag system isn't currently working
	//log(split('flag', "user|submitted by user: Sepshun,display|promoter is inactive"));
	

	function updateModal(target, c) {
		if (target === 'edit') {
			let modal = $('#'+target+'-modal'),
			
			// If you're referencing the same string a lot,
			// it's always good to keep it at the top/in one spot

			name = {
				title: "#edit-modal .modal-title",
				pr: "#edit-modal input[name='pr-name']"
			},

			display = {
				input: "#edit-modal input[name='display']",
				pr: "#edit-modal .pr-input-box:nth-child(1)"
			};

			// NAME
			$(name.title).text(c.name);
			$(name.pr).val(c.name);
			
			// DISPLAY
			if (c.display === 'true') {
				$(display.input).prop('checked', true);
				$(display.pr).removeClass('pr-no-display');
				$(display.pr).addClass('pr-display');
				$(display.pr).find('.pr-reason').hide(200);
			} else {
				$(display.input).prop('checked', false);
				$(display.pr).addClass('pr-no-display');
				$(display.pr).removeClass('pr-display');
				$(display.pr).find('.pr-reason').show(200);
				
				var reason = split('reason', c.display);
				$('#edit-modal .pr-reason select').val(reason[1]);
				$('#edit-modal .pr-reason input').val(reason[2]);
			}

			// SUBMISSION
			$('#edit-modal input[name="sub"]').val(c.sub);
			subDetermine($('#edit-modal input[name="sub"]').parent(), c.sub);

			// GENRES
			var genres = c.genres.split(',');
			$('#edit-modal .genre-input input[type="checkbox"]').prop('checked', false);
			for (var i = 0; i < genres.length; i++) {
				$('#edit-modal input[value="'+genres[i]+'"]').prop('checked', true);
			}

			// SOCIAL
			$('#edit-modal .social-input input').val('');

			// There must be a faster and more convieniant way of doing this
			// I'm thinking some kind of for loop that'll reuse the value
			// 'facebook', 'soundcloud', etc, since there's obviously a pattern
			$('#edit-modal input[name="facebook"]').val(c.facebook);
			$('#edit-modal input[name="soundcloud"]').val(c.soundcloud);
			$('#edit-modal input[name="youtube"]').val(c.youtube);
			$('#edit-modal input[name="instagram"]').val(c.instagram);
			$('#edit-modal input[name="twitter"]').val(c.twitter);
			$('#edit-modal input[name="webpage"]').val(c.webpage);
			
			// Check if active
			$('#edit-modal .social-input input').each(function() {
				$(this).removeClass('active');
				var target = $(this);
				var val = $(this).val();
				var contains = $(this).attr('contains');
				socialCheck(target, val, contains);
			});


			$('#edit-modal input[name=""]').val();
		}
		if (target === 'remove') {
			var target =$('#remove-modal');
			// NAME
			$('#remove-modal .modal-title').text(c.name);
		}
	}

	// ------------------------------------
	// FLAGS
	$('.flags-btn').click(function() {
		// Treat this like the table dropdowns
		var target = $(this).parent().find('.cell-dropdown');
		if (target.hasClass('open')) {
			target.removeClass('open');
		} else {
			$('.cell-dropdown').removeClass('open');
			target.toggleClass('open');
		}
	});
	

	// ------------------------------------
	// TABLE DROPDOWN MENU
	$('.table-dropdown-cell i').click(function() {
		// Check if class already exists
		if ($(this).parent().hasClass('open')) {
			// If so, toggle it
			$(this).parent().toggleClass('open');
		} else {
			// Else, remove all and add to this
			$('.table-dropdown-cell').removeClass('open');
			$(this).parent().addClass('open');
		}
	});
});