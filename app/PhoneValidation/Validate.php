<?php

namespace PhoneValidation;

class Validate
{
    public static function validate()
    {
        //TODO: Implement validation patterns
		if(isset($_FILES['fileToUpload'])){
			$fileChunks = pathinfo($_FILES['fileToUpload']['name']);
			//Use strtolower() to convert all characters to lowercase
			$fileExtension = strtolower($fileChunks['extension']);
			if($fileExtension != 'csv'){
				//Server-side validation for invalid extension
				return 'invalid_extension';
			}else{
				//Moving an uploaded file to "uploads" directory; Naming the file as "phone_numbers.csv"
				$uploadedFile = 'phone_numbers.csv';
				move_uploaded_file($_FILES['fileToUpload']['tmp_name'], 'uploads/'.$uploadedFile);
				
				if ($file = fopen("uploads/".$uploadedFile,'r')){
					$firstline = fgets($file, 4096);
					//Gets the number of fields and column titles in the CSV file
					$num = strlen($firstline) - strlen(str_replace(";", "", $firstline));

					//Stores column titles in 'fields' array
					$fields = array();
					$fields = explode(";", $firstline, ($num+1));

					$phone_field = explode(',', $fields[0]);
					//Trims all column titles
					$phone_field = array_map('trim', $phone_field);
					//Searches for column title - 'phone'
					$phone_column = array_search('phone', $phone_field);
					//If 'phone' column is available, it returns its key; So, it traces 'phone' column throughout all titles and not just at the first column 
					if(is_numeric($phone_column)){
						$line = array();
						$i = 0;
						$validPhoneNumber = array();
						$invalidPhoneNumber = array();
						//$records is an two dimensional array saving the records e.g. $records[number_of_record][number_of_cell]
						while($line[$i] = fgets($file, 4096)){
							$records[$i] = array();
							$records[$i] = explode(";", $line[$i], ($num+1));
							$phoneData = explode(',', $records[$i][0]);
							$phoneNumber = $phoneData[$phone_column];
							//Validate with regular expression match if each phone number is a valid Canadian phone number or not; Separate valid and invalid phone numbers in two arrays
							if(preg_match("/^\([0-9]{3}\) [0-9]{3} [0-9]{4}$/", $phoneNumber)) {
								$phoneNumber = str_replace(array('(', ')', ' '),'',$phoneNumber);
								$phoneNumber = '+1'.$phoneNumber;
								$validPhoneNumber[] = $phoneNumber;
							}else{
								$invalidPhoneNumber[] = $phoneNumber;
							}
							$i++;
						}
						
						//Create a Table structure using HTML and CSS to display valid and invalid phone numbers in Green and Red colored titles respectively on main page
						$table = '';
						$table .= "<table id='phonenumbers'>";
						$table .= "<tr>";
						$table .= "<td class='numberdisplay'></td>";
						
						/* Valid phone numbers column - Start */
						$table .= "<td class='numberdisplay'>";
						$table .= "<table border='1' id='valid'>";
						$table .= "<tr>";
						$table .= "<th>Valid phone numbers</th>";
						$table .= "</tr>";
						foreach($validPhoneNumber as $validPhone){
							$table .= "<tr>";
							$table .= "<td>".$validPhone."</td>";
							$table .= "</tr>";
						}
						$table .= "</table>";
						$table .= "</td>";
						/* Valid phone numbers column - End */
						
						/* Invalid phone numbers column - Start */
						$table .= "<td class='numberdisplay'>";
						$table .= "<table border='1' id='invalid'>";
						$table .= "<tr>";
						$table .= "<th>Invalid phone numbers</th>";
						$table .= "</tr>";
						foreach($invalidPhoneNumber as $invalidPhone){
							$table .= "<tr>";
							$table .= "<td>".$invalidPhone."</td>";
							$table .= "</tr>";
						}
						$table .= "</table>";
						$table .= "</td>";
						/* Invalid phone numbers column - End */
						
						$table .= "<td class='numberdisplay'></td>";
						$table .= "</tr>";
						$table .= "</table>";
						return $table;
					}else{
						//If 'phone' column is not found, return Server-side validation for no phone column
						return 'no_phone_column';
					}
				}
			}
		}
    }
}