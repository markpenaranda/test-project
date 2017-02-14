<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>
	Type User ID here:
	<input type="text" id="userId" />
	<button id="loginBtn">Login</button>


	<br><br><br>
	<p>After clicking login select the page.:</p>

	<ul>
		<li>
			<a href="/discover-jobs.php">Discover Jobs</a>
		</li>
		<li>
			<a href="/my-openday.php">My Openday</a>
		</li>
	</ul>

<?php include 'include/js.php'; ?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#loginBtn").on('click', function() {
			localStorage.setItem('userId', $("#userId").val());
			alert("Logged In!");
		});
	});
</script>
</body>

</html>