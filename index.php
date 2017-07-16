<?php
	session_start();
	require_once('connect.php');

	$username = $_SESSION['username'];

	$fetch_usertype = mysqli_query($conn, "SELECT usertype FROM `userlogin` WHERE username='$username'");
	$res = mysqli_fetch_assoc($fetch_usertype);
	$usertype = $res['usertype'];

	$avatar_check = file_exists("assets/images/avatars/$username.png");
	if ($avatar_check) {$avatar = lcfirst($username);}
	else {$avatar = "default";}
	
	// REDIRECT NOT LOGGED IN VISITORS
	if(!isset($_SESSION['username'])) {header('location: login.php');}

	$genres_array = array('future','dubstep','house','electro','melbourne','dnb','neuro','glitch-hop','trap','hybrid','chill','hardstyle');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Serenity - Home</title>

		<!-- Stylesheets                     -->
		<link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
		<!-- Scripts                         -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script type="text/javascript" src="assets/js/script.js"></script>
	</head>
	<body>
		<!-- =============================== -->
		<!-- HEADER                          -->
		<!-- =============================== -->
		<div id="header">
			
			<!-- =============================== -->
			<!-- HEADER - TITLE                  -->
			<!-- =============================== -->
			<p id="header-title">Serenity</p>
			<!-- HEADER - LEFT GRADIENT -->
			<div id="header-left-gradient"></div>

			<!-- =============================== -->
			<!-- HEADER - BUTTONS                -->
			<!-- =============================== -->
			<div id="header-buttons">
				<div class="modal-btn" target="add">Add</div>
				<div class="header-btn-divider"></div>
				<?php
					if ($usertype === 'admin' || $usertype === 'mod') {
						echo '<div class="modal-btn" target="blacklist">Blacklist</div>';
						echo '<div class="modal-btn" target="flags">Flags</div>';
						echo '<div class="modal-btn" target="scan">Scan</div>';
					} else if ($usertype === 'user') {
						
					}
				?>
			</div>

			<!-- =============================== -->
			<!-- HEADER - ACCOUNT PANEL          -->
			<!-- =============================== -->
			<div id="header-account" class="<?php echo $usertype ?>">

				<!-- ACCOUNT PANEL - AVATAR          -->
				<img src="assets/images/avatars/<?php echo $avatar ?>.png" class="avatar-small">

				<!-- ACCOUNT PANEL - USERNAME        -->
				<div id="header-username">
					<p><?php echo $username ?>
						<span> (<?php echo $usertype ?>)</span>
					</p>
				</div>
				<i class="material-icons">keyboard_arrow_down</i>

				<!-- ACCOUNT PANEL - DROPDOWN        -->
				<div id="account-dropdown">
					<ul>
						<li><a href="account.php">Account Settings</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</div>

				<!-- ACCOUNT PANEL - GRADIENT        -->
				<div id="header-right-gradient"></div>
			</div>
		</div>


		<!-- =============================== -->
		<!-- MODALS                          -->
		<!-- =============================== -->
		<div id="black-underlay"></div>
		
		<!-- =============================== -->
		<!-- ADD MODAL                       -->
		<div id="add-modal" class="modal">
			<!-- =============================== -->
			<!-- Title                           -->
			<p class="modal-title">Untitled</p>
			<p class="modal-name">Add</p>

			<!-- =============================== -->
			<!-- Close Button                    -->
			<div class="close-btn"><i class="material-icons">close</i></div>

			<!-- =============================== -->
			<!-- Input                           -->
			<div class="input-cont">
				<!-- =============================== -->
				<!-- NAME & SUBMISSION               -->
				<div class="pr-input-box pr-display">
					<input type="text" name="pr-name" placeholder="Promoter Name">
					<label class="pr-display-toggle">
						<input type="checkbox" name="display" checked>
						<span class="pr-display-span"></span>
					</label>
					<div class="pr-reason">
						<select>
							<option value="0">Select Reason</option>
							<option value="inactive">Inactive</option>
							<option value="requested">Requested</option>
							<option value="no-sub">No Submission</option>
						</select>
						<input type="text" name="inactive-info" placeholder="Optional info">
					</div>
				</div>
				<div class="pr-input-box">
					<input type="text" name="sub" placeholder="Submission">
					<span>N/A</span>
				</div>


				<!-- =============================== -->
				<!-- SIDE BY SIDE INPUTS             -->
				<div class="genre-input sbs-input">
					<p class="modal-name">GENRES</p>
					<ul>
					<?php
						for ($g=0; $g < sizeof($genres_array); $g++) { 
							if ($g < 6) {
								echo "<li class='checkbox'><label>$genres_array[$g]<input type='checkbox' name='genre' value='$genres_array[$g]'><span></span></label></li>";
							}
						}
					?>
					</ul>
					<ul>
					<?php
						for ($g=0; $g < sizeof($genres_array); $g++) { 
							if ($g > 5) {
								echo "<li class='checkbox'><label>$genres_array[$g]<input type='checkbox' name='genre' value='$genres_array[$g]'><span></span></label></li>";
							}
						}
					?>	
					</ul>
				</div>

				<div class="social-input sbs-input">
					<p class="modal-name">SOCIAL LINKS</p>
					<input type="text" name="facebook" placeholder="Facebook" contains="facebook.com/">
					<input type="text" name="soundcloud" placeholder="Soundcloud" contains="soundcloud.com/">
					<input type="text" name="youtube" placeholder="Youtube" contains="youtube.com/">
					<input type="text" name="instagram" placeholder="Instagram" contains="instagram.com/">
					<input type="text" name="twitter" placeholder="Twitter" contains="twitter.com/">
					<input type="text" name="webpage" placeholder="Webpage" contains="http">
				</div>


				<!-- =============================== -->
				<!-- SUBMIT BUTTON                   -->
				<div class="submit-btn" query-type="add"><button>SUBMIT</button></div>
			</div>
		</div>

		<!-- =============================== -->
		<!-- EDIT MODAL                      -->
		<div id="edit-modal" class="modal">
			<!-- =============================== -->
			<!-- Title                           -->
			<p class="modal-title">Untitled</p>
			<p class="modal-name">Edit</p>

			<!-- =============================== -->
			<!-- Close Button                    -->
			<div class="close-btn"><i class="material-icons">close</i></div>

			<!-- =============================== -->
			<!-- Input                           -->
			<div class="input-cont">
				<!-- =============================== -->
				<!-- NAME & SUBMISSION               -->
				<div class="pr-input-box pr-display">
					<input type="text" name="pr-name" placeholder="Promoter Name">
					<label class="pr-display-toggle">
						<input type="checkbox" name="display" checked>
						<span class="pr-display-span"></span>
					</label>
					<div class="pr-reason">
						<select>
							<option value="0">Select Reason</option>
							<option value="inactive">Inactive</option>
							<option value="requested">Requested</option>
							<option value="no-sub">No Submission</option>
						</select>
						<input type="text" name="inactive-info" placeholder="Optional info">
					</div>
				</div>
				<div class="pr-input-box">
					<input type="text" name="sub" placeholder="Submission">
					<span>N/A</span>
				</div>


				<!-- =============================== -->
				<!-- SIDE BY SIDE INPUTS             -->
				<div class="genre-input sbs-input">
					<p class="modal-name">GENRES</p>
					<ul>
					<?php
						for ($g=0; $g < sizeof($genres_array); $g++) { 
							if ($g < 6) {
								echo "<li class='checkbox'><label>$genres_array[$g]<input type='checkbox' name='genre' value='$genres_array[$g]'><span></span></label></li>";
							}
						}
					?>
					</ul>
					<ul>
					<?php
						for ($g=0; $g < sizeof($genres_array); $g++) { 
							if ($g > 5) {
								echo "<li class='checkbox'><label>$genres_array[$g]<input type='checkbox' name='genre' value='$genres_array[$g]'><span></span></label></li>";
							}
						}
					?>	
					</ul>
				</div>

				<div class="social-input sbs-input">
					<p class="modal-name">SOCIAL LINKS</p>
					<input type="text" name="facebook" placeholder="Facebook" contains="facebook.com/">
					<input type="text" name="soundcloud" placeholder="Soundcloud" contains="soundcloud.com/">
					<input type="text" name="youtube" placeholder="Youtube" contains="youtube.com/">
					<input type="text" name="instagram" placeholder="Instagram" contains="instagram.com/">
					<input type="text" name="twitter" placeholder="Twitter" contains="twitter.com/">
					<input type="text" name="webpage" placeholder="Webpage" contains="http">
				</div>


				<!-- =============================== -->
				<!-- SUBMIT BUTTON                   -->
				<div class="submit-btn" query-type="edit"><button>SUBMIT</button></div>
			</div>
		</div>

		<!-- =============================== -->
		<!-- REMOVE MODAL                      -->
		<div id="remove-modal" class="modal">
			
			<!-- =============================== -->
			<!-- Title                           -->
			<p class="modal-title">Untitled</p>
			<p class="modal-name">Remove</p>

			<!-- =============================== -->
			<!-- Close Button                    -->
			<div class="close-btn"><i class="material-icons">close</i></div>

			<div class="input-cont">
				<p class="para-remove">Enter the word <span style="color: #FF5D5D;">"REMOVE"</span> to confirm.</p>
				<input type="text" name="remove">

				<!-- =============================== -->
				<!-- SUBMIT BUTTON                   -->
				<div class="submit-btn" query-type="remove"><button>SUBMIT</button></div>
			</div>
		</div>

		<!-- =============================== -->
		<!-- BLACKLIST MODAL                 -->
		<div id="blacklist-modal" class="modal">
			<!-- =============================== -->
			<!-- Title                           -->
			<p class="modal-title">Blacklist</p>

			<!-- =============================== -->
			<!-- Close Button                    -->
			<div class="close-btn"><i class="material-icons">close</i></div>
		</div>

		<!-- =============================== -->
		<!-- FLAGS MODAL                     -->
		<div id="flags-modal" class="modal">
			<!-- =============================== -->
			<!-- Title                           -->
			<p class="modal-title">Flags</p>

			<!-- =============================== -->
			<!-- Close Button                    -->
			<div class="close-btn"><i class="material-icons">close</i></div>
		</div>

		<!-- =============================== -->
		<!-- SCAN MODAL                      -->
		<div id="scan-modal" class="modal">
			<!-- =============================== -->
			<!-- Title                           -->
			<p class="modal-title">Scan</p>

			<!-- =============================== -->
			<!-- Close Button                    -->
			<div class="close-btn"><i class="material-icons">close</i></div>



			<!-- =============================== -->
			<!-- INPUT CONTAINER                 -->
			<div class="input-cont">
				<!-- =============================== -->
				<!-- TYPE SELECTION                  -->
				<form class="scan-radios">
					<label>Full Scan
						<input type="radio" name="scan-type" value="full" checked>
						<span></span>
					</label>
					<label>Single Scan
						<input type="radio" name="scan-type" value="single">
						<span></span>
					</label>
				</form>

				<!-- =============================== -->
				<!-- DISPLAY CONTAINER               -->
				<div class="scan-display">
					<div class="scan-target">
						
					</div>
					<div class="row">
						<div class="cell">Inverse Network</div>
						<div class="cell flags-btn">5</div>

						<div class="cell-dropdown flags">
							<div>Inactive - 142 days</div>
						</div>
					</div>
					<div class="row">
						<div class="cell">Inverse Network</div>
						<div class="cell flags-btn">5</div>

						<div class="cell-dropdown flags">
							<div>Inactive - 142 days</div>
						</div>
					</div>
					<div class="row">
						<div class="cell">Inverse Network</div>
						<div class="cell flags-btn">5</div>

						<div class="cell-dropdown flags">
							<div>Inactive - 142 days</div>
						</div>
					</div>
				</div>


				<!-- =============================== -->
				<!-- SCAN BUTTON                     -->
				<div class="submit-btn scan-btn" query-type="scan"><button>SCAN</button></div>

				<!-- =============================== -->
				<!-- SUBMIT BUTTON                   -->
				<div class="submit-btn" query-type="flags" style="display: none;"><button>SUBMIT</button></div>
			</div>
		</div>


		<!-- =============================== -->
		<!-- CONTEXT MENU                    -->
		<!-- =============================== -->
		<div id="context-menu" style="display:none;">
			<ul>
				<li class="modal-btn" target="edit">Edit Entry</li>
				<li class="modal-btn" target="remove">Remove Entry</li>
				<li class="divider"></li>
				<li class="modal-btn" target="blacklist">Add to Blacklist</li>
				<li class="modal-btn" target="flags">View Flags</li>
				<li class="modal-btn" target="scan">Scan Entry</li>
			</ul>
		</div>


		<!-- =============================== -->
		<!-- DATABASE CONTAINER              -->
		<!-- =============================== -->
		<div id="container">
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Submission</th>
						<th>Genres</th>
						<th>Social</th>
					</tr>
				</thead>
				<tbody>
					<?php
						// GET DATA FROM SERVER DB
						$select = "SELECT id, display, name, sub, genres, facebook, soundcloud, youtube, instagram, twitter, webpage, flags FROM `promoters` ORDER BY id ASC";
						$res = mysqli_query($conn, $select);

						// IF ROWS ARE MORE THAN ONE
						if (mysqli_num_rows($res) > 0) {
							// LOOP THROUGH EACH PROMOTER
							while ($row = mysqli_fetch_assoc($res)) {
								// DETERMINE SUB TYPE
								$submission = "";
								if (strpos($row['sub'], 'http://') !== false || strpos($row['sub'], 'https://') !== false && !strpos($row['sub'], 'https://soundcloud.com/') === false) {
									$submission = "<a href='".$row['sub']."'>Web Form</a>";
								} elseif (strpos($row['sub'], '@') !== false) {
									$submission = $row['sub'];
								} elseif (strpos($row['sub'], 'https://soundcloud.com/') !== false) {
									$submission = "<a href='".$row['sub']."'>Soundcloud Submission</a>";
								}

								// CREATE GENRES
								$genres = "";
								foreach (explode(',', $row['genres']) as $genre) { $genres .= "<div><p>".$genre."</p></div>"; }

								// CREATE SOCIAL
								$social_types = array('facebook', 'soundcloud', 'youtube', 'instagram', 'twitter', 'webpage');
								$social = "";
								foreach ($social_types as $type) {
									if ($row[$type] != "") {
										$social .= "<div class='link'><a href='".$row[$type]."' class='".$type."'>".ucfirst($type)."</a></div>";
									}
								}

								// CREATE ROW
								echo "<tr
									id='".$row['id']."'
									display='".$row['display']."'
									name='".$row['name']."'
									sub='".$row['sub']."'
									genres='".$row['genres']."'
									facebook='".$row['facebook']."'
									soundcloud='".$row['soundcloud']."'
									youtube='".$row['youtube']."'
									instagram='".$row['instagram']."'
									twitter='".$row['twitter']."'
									webpage='".$row['webpage']."'
									flags='".$row['flags']."'
								>
									<td>".$row['id']."</td>
									<td>".$row['name']."</td>
									<td>".$submission."</td>
									<td class='table-dropdown-cell'>Genres
										<i class='material-icons'>keyboard_arrow_down</i>
										<div class='table-dropdown-content'>
											".$genres."
										</div>
									</td>
									<td class='table-dropdown-cell'>Social Links
										<i class='material-icons'>keyboard_arrow_down</i>
										<div class='table-dropdown-content'>
											".$social."
										</div>
									</td>
								</tr>";
							}
						}
					?>
				</tbody>
			</table>
		</div>
	</body>
</html>