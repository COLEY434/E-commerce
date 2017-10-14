<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../styles/main.css">

<script type="text/javascript" src="../bootstrap/js/jquery-3.2.1.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
	<title>Administrator</title>
</head>
<body>
<div class="container-fluid">
<script type="text/javascript">

	function removeLastComma(str) {
       return str.replace(/,(\s+)?$/, '');
}
	function updateSizes() {
	var sizeString = '';

	for (var i=1; i <=5; i++) {
		if(jQuery('#size'+i).val() != ''){
			sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+':'+jQuery('#threshold'+i).val()+',';

		}

	}
	var trimmedSizeString = removeLastComma(sizeString);

	jQuery('#sizes').val(trimmedSizeString);
}
</script>
