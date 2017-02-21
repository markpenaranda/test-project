<!DOCTYPE html>
<html>
<head>
	<title>Login Admin</title>
</head>
<body>
	Type User ID here:
	<input type="text" id="userId" />
	<button id="loginBtn">Login</button>


	<br><br><br>
	<p>After clicking login select the page.:</p>

	<ul>
		<li>
			<a href="/create-room.php">Create Openday</a>
		</li>
		<li>
			<a href="/openday-candidates.php">My Openday</a>
		</li>
		<li>
			<a href="/room.php">Live Openday</a>
		</li>
	</ul>

<?php include 'include/js.php'; ?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#loginBtn").on('click', function() {
			localStorage.setItem('userId', $("#userId").val());
			$.get("/api/v1/public/index.php/users/" + $("#userId").val(), function(res) {
				console.log(res);
				alert("Logged In as "+ res.name +"!");
			});
		});
	});
</script>
</body>

</html>