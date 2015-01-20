<!DOCTYPE html>
<?php


define('FILE', 'address_book.csv');

require_once '../classes/AddressDataStore.php';

$addressData = new AddressDataStore(FILE);

$addressData->addressBook = $addressData->readMe();


if(!empty($_POST)){
	$newEntry = [];
	
	// settting a varriable that is dependent on input
	$error = false;

	foreach ($_POST as $key => $value) {
		//when there is an empty value change varriable so we can get different output
		if(empty($value)){
			$error = true;
		//if there is not an empty value, continue	
		}else{
			$newEntry[$key] = $addressData->cleanInput($value);		
		}
	}
	// when the error is true, spit out error
	if ($error) {
		$message = "You left one or more fields empty!";
	//when the error is false continue
	} else {
		array_push($addressData->addressBook, $newEntry);
		$addressData->saveFile($addressData->addressBook);
	}
}

if(isset($_GET['remove'])){
		$key = $_GET['remove'];
        // Remove from array
        unset($addressData->addressBook[$key]);
        $addressData->addressBook = array_values($addressData->addressBook);
		$addressData->saveFile($addressData->addressBook);
	}

if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
    // Set the destination directory for uploads
    $uploadDir = '/vagrant/sites/planner.dev/public/uploads/';

    // Grab the filename from the uploaded file by using basename

    $uploadfile = basename($_FILES['file1']['name']);

    // Create the saved filename using the file's original name and our upload directory
    $savedFilename = $uploadDir . $uploadfile;

    $uploadedAddressData = new AddressDataStore($savedFilename);
    if(substr($uploadfile, -3) == 'csv'){
	    // Move the file from the temp location to our uploads directory
	    move_uploaded_file($_FILES['file1']['tmp_name'], $savedFilename);
	    $addressData->addressBook = array_merge($addressData->addressBook, $uploadedAddressData->readMe());
	    $addressData->saveFile($addressData->addressBook);
	}else{
		echo "There was an error in processing your file, please use 'csv' file type.";
	}
}

?>
<html>
<head>
	<title>Address Book</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../address_book/css/stylesheet.css">
    <link rel="javascript" type="text/css" href="../bootstrap/js/bootstrap.min.js">
    <link href="../font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<table>
	<tr>
		<th>Location</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>Zip</th>


	</tr>
	<? foreach ($addressData->addressBook as $key => $contact): ?>		
	<tr>
		<? foreach ($contact as $info): ?>
			<td>
				<?= $info ?>
			</td>
			<? endforeach; ?>
			<td>
				<a href="../address_book/address_book.php?remove=<?=$key?>"> 
					<i class='fa fa-times fa-lg'></i>
				</a>
			</td>
				
	</tr>	
	<? endforeach; ?>

</table>

<? if(isset($message)): ?>
	<?= $message ?>
<? endif ?>	

<form method="POST" action ="../address_book/address_book.php">
	<h3>Enter Items</h3>
	<p>
		<label for="location">New Location:</label>
		<input id="location" name="location" type= "text" placeholder = "Location">
	</p>
	<p>
		<label for="address">New Address:</label>
		<input id="address" name="address" type= "text" placeholder = "Address">
	</p>
	<p>
		<label for="city">New City:</label>
		<input id="city" name="city" type= "text" placeholder = "City">
	</p>
	<p>
		<label for="state">New State:</label>
		<input id="state" name="state" type= "text" placeholder = "State">
	</p>
	<p>
		<label for="zip">New Zip:</label>
		<input id="zip" name="zip" type= "text" placeholder = "Zip">
	</p>	
	<button type = "submit"> Add New Contact</button>
	</form>

	<h1>Upload File</h1>

	<? if (isset($savedFilename)): ?>
        <!-- If we did, show a link to the uploaded file -->
        <p> You can download your file <a href='../uploads/<?= $uploadfile ?>'>here</a>.</p>
   
    <? endif; ?>

	<form method="POST" enctype="multipart/form-data" action="../address_book/address_book.php">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>

</body>
</html>