<html lang="ar">
	<head>
	<meta charset="utf-8">
		<title>Example</title>
	</head>
<body >

<div <?php echo (($_GET['lang']=='ar') ? 'dir=rtl' : 'dir=ltr');?>>
<input type="text" placeholder="Enter1">
</div>
<div <?php echo (($_GET['lang']=='ar') ? 'dir=ltr' : 'dir=rtl');?>>
<input type="text" placeholder="Enter2">
</div>

</body>
	</html>