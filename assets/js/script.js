$(document).ready(function() {
	// CONTAINS FUNCTION EXTENSION
	String.prototype.contains = function(it) { return this.indexOf(it) != -1; };
	const log = console.log;


	// ------------------------------------
	// ACCOUNT PANEL | OPENING/CLOSING
	$('#header-account').click(function() {$(this).toggleClass('open');});


	// ------------------------------------
	// CONTEXT MENU
	// Bind on context menu click
	var c = "";
	c.id = 0;
	$('#container table tbody tr').bind('contextmenu', function(event) {
		// Get values
		c         = $(event.target);
		c.parent  = c.parent();
		c.id      = c.parent.attr('id');
		c.display = c.parent.attr('display');
		c.name    = c.parent.attr('name');
		c.sub     = c.parent.attr('sub');
		c.genres  = c.parent.attr('genres');
		c.fb      = c.parent.attr('facebook');
		c.sc      = c.parent.attr('soundcloud');
		c.yt      = c.parent.attr('youtube');
		c.ig      = c.parent.attr('instagram');
		c.tw      = c.parent.attr('twitter');
		c.wp      = c.parent.attr('webpage');
		c.flags   = c.parent.attr('flags');
		
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
			//log(e.target);
		}
	});
	// Click menu option
	$('#context-menu ul li').click(function() {
		$('#context-menu').hide(100);
		$('.table-dropdown-cell').removeClass('open');
		var target = $(this).attr('target');
		updateModal(target,c.id,c.display,c.name,c.sub,c.genres,c.fb,c.sc,c.yt,c.ig,c.tw,c.wp,c.flags);
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

	// ------------------------------------
	// SUBMISSION TYPE DETERMINATION
	var subType;
	function subDetermine(target, sub) {
		if (sub.contains('http://') || sub.contains('https://') && !sub.contains('https://soundcloud.com/')) {
			// WEB
			target.addClass('sub-web');
			target.removeClass('sub-email sub-sc');
			target.find('span').text('WEB');
			subType = "web";
		} else if (sub.contains('@')) {
			// EMAIL
			target.addClass('sub-email');
			target.removeClass('sub-web sub-sc');
			target.find('span').text('EMAIL');
			subType = "email";
		} else if (sub.contains('https://soundcloud.com/')) {
			// SOUNDCLOUD
			target.addClass('sub-sc');
			target.removeClass('sub-web sub-email');
			target.find('span').text('SOUNDCLOUD');
			subType = "sc";
		} else {
			// NONE OF THE ABOVE
			target.removeClass('sub-web sub-email sub-sc');
			target.find('span').text('N/A');
			subType = "";
		}
	}
	$('.pr-input-box input[name="sub"]').keyup(function() {
		var val = $(this).val();
		var target = $(this).parent();
		subDetermine(target, val);
	});

	// ------------------------------------
	// SOCIAL LINK CONFIRMATION
	function socialCheck(target, val, contains) {
		if (val.contains(contains)) {
			target.addClass('active');
		} else {
			target.removeClass('active');
		}
	}
	$('.social-input input').keyup(function() {
		var target = $(this);
		var val = $(this).val();
		var contains = $(this).attr('contains');
		socialCheck(target, val, contains);
	});

	// ------------------------------------
	// SUBMIT BUTTON
	$('.submit-btn:not(.scan-btn)').click(function() {
		var queryType = $(this).attr('query-type');

		// STORE ALL THE INFO
		var display = '';
		if (!$('#'+queryType+'-modal input[name="display"]:checked').length > 0) {display = 'false|' + $('#'+queryType+'-modal select').val() + "|" + $('#'+queryType+'-modal input[name="inactive-info"]').val();}
		else {display = 'true';}

		var name = $('#'+queryType+'-modal input[name="pr-name"]').val();
		var sub = $('#'+queryType+'-modal input[name="sub"]').val();

		var genres = "";
		$('#'+queryType+'-modal input[name="genre"]:checked').each(function() {genres += $(this).val() + ",";});
		genres = genres.replace(/,\s*$/, "");

		var facebook   = $('#'+queryType+'-modal input[name="facebook"]').val();
		var soundcloud = $('#'+queryType+'-modal input[name="soundcloud"]').val();
		var youtube    = $('#'+queryType+'-modal input[name="youtube"]').val();
		var instagram  = $('#'+queryType+'-modal input[name="instagram"]').val();
		var twitter    = $('#'+queryType+'-modal input[name="twitter"]').val();
		var webpage    = $('#'+queryType+'-modal input[name="webpage"]').val();

		var flags = "";

		if (queryType === 'remove' && $('#remove-modal input[name="remove"]').val() === 'REMOVE' || queryType !== 'remove') {
			$.post('query.php', {type:queryType, id:c.id, display:display, name:name, sub:sub, genres:genres, fb:facebook, sc:soundcloud, yt:youtube, ig:instagram, tw:twitter, wp:webpage, flags:flags}, function(res) {
				location.reload();
			});
		}
	});

	// ------------------------------------
	// UPDATE MODAL FUNCTIONS
	
	// The flag system isn't currently working
	//log(split('flag', "user|submitted by user: Sepshun,display|promoter is inactive"));
	

	function updateModal(target,id,display,name,sub,genres,fb,sc,yt,ig,tw,wp,flags) {
		if (target === 'edit') {
			var modal = $('#'+target+'-modal');

			// NAME
			$('#edit-modal .modal-title').text(name);
			$('#edit-modal input[name="pr-name"]').val(name);
			
			// DISPLAY
			if (display === 'true') {
				$('#edit-modal input[name="display"]').prop('checked', true);

				$('#edit-modal .pr-input-box:nth-child(1)').removeClass('pr-no-display');
				$('#edit-modal .pr-input-box:nth-child(1)').addClass('pr-display');
				$('#edit-modal .pr-input-box:nth-child(1)').find('.pr-reason').hide(200);


			} else {
				$('#edit-modal input[name="display"]').prop('checked', false);

				$('#edit-modal .pr-input-box:nth-child(1)').addClass('pr-no-display');
				$('#edit-modal .pr-input-box:nth-child(1)').removeClass('pr-display');
				$('#edit-modal .pr-input-box:nth-child(1)').find('.pr-reason').show(200);

				var reason = split('reason', display);
				$('#edit-modal .pr-reason select').val(reason[1]);
				$('#edit-modal .pr-reason input').val(reason[2]);

			}

			// SUBMISSION
			$('#edit-modal input[name="sub"]').val(sub);
			subDetermine($('#edit-modal input[name="sub"]').parent(), sub);

			// GENRES
			var genres = genres.split(',');
			$('#edit-modal .genre-input input[type="checkbox"]').prop('checked', false);
			for (var i = 0; i < genres.length; i++) {
				$('#edit-modal input[value="'+genres[i]+'"]').prop('checked', true);
			}

			// SOCIAL
			$('#edit-modal .social-input input').val('');
			$('#edit-modal input[name="facebook"]').val(fb);
			$('#edit-modal input[name="soundcloud"]').val(sc);
			$('#edit-modal input[name="youtube"]').val(yt);
			$('#edit-modal input[name="instagram"]').val(ig);
			$('#edit-modal input[name="twitter"]').val(tw);
			$('#edit-modal input[name="webpage"]').val(wp);
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
			$('#remove-modal .modal-title').text(name);
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