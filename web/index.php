<?php
/* Get Website URL for locating custom made css file under 'css' directory - Start */
if(isset($_SERVER['HTTPS'])){
    $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
}else{
    $protocol = 'http';
}
$websiteUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
/* Get Website URL for locating custom made css file under 'css' directory - End */

require __DIR__.'/../vendor/autoload.php';

//TODO: Implement validation logic
/* 'result' fetches server-side validation errors as well as successful outputs */
$result = \PhoneValidation\Validate::validate();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!-- Included custom made css file -->
  <link rel="stylesheet" href="<?php echo $websiteUrl;?>css/style.css">
  <title>Phone Number Validation</title>
</head>
<body>
<div class="container">
  <div class="py-5 text-center">
    <h2>Phone Number Validation</h2>
    <p class="lead">Validating phone numbers since 2018'</p>
  </div>
  <div class="row">
    <div class="col-md-12">
      <form class="needs-validation" novalidate method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-12">
            <label for="fileToUpload">Select a file to upload. Allowed filed types are: CSV</label>
			<!-- Included other input tag elements - accept(to show only CSV files on browse) and onchange(to validate file format on browse itself) -->
            <input type="file" class="form-control" id="fileToUpload" name="fileToUpload" accept=".csv" onchange="validateExtension(this)" placeholder="" value="" required>
			<?php /* Invalid file extension and No phone column server-side error messages - Start */ ?>
			<?php if($result == 'invalid_extension'){?>
				<div class="invalid_extension">Please upload file with CSV format only!</div>
			<?php }elseif($result == 'no_phone_column'){?>
				<div class="invalid_extension">There is no 'phone' column in the CSV file uploaded!</div>
			<?php }?>
			<?php /* Invalid file extension and No phone column server-side error messages - End */ ?>
          </div>
        </div>
        <hr class="mb-4">
        <button class="btn btn-primary btn-lg btn-block" type="submit">Upload file</button>
      </form>
    </div>
  </div>
  <div>&nbsp;</div>
  <?php /* Output with Valid and Invalid phone numbers in 2 separate lists. Also, deleting the uploaded CSV file from 'uploads' directory after it has been processed - Start */ ?>
  <?php if($result != '' && $result != 'invalid_extension' && $result != 'no_phone_column'){?>
  <div>
	<?php 
		echo $result;
		@unlink(__DIR__.'\uploads\phone_numbers.csv');
	?>
  </div>
  <?php }?>
  <?php /* Output with Valid and Invalid phone numbers in 2 separate lists. Also, deleting the uploaded CSV file from 'uploads' directory after it has been processed - End */ ?>
  <footer class="my-5 pt-5 text-muted text-center text-small">
    <p class="mb-1">&copy; <?php echo date('Y'); ?></p>
  </footer>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- onchange function to validate file format on browse itself - Start -->
<script type="text/javascript">
var regExp = new RegExp("(.*?)\.(csv)$");
function validateExtension(e){
  if(!(regExp.test(e.value.toLowerCase()))){
    e.value = '';
    alert('Please upload file with CSV format only!');
  }
}
</script>
<!-- onchange function to validate file format on browse itself - End -->
</body>
</html>