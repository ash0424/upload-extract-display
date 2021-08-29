<?php
error_reporting(0);  //prevent any notices/warnings to pop up on the website 

 if(isset($_POST["btn_zip"])) //check if user clicked on submit button or not
 {  
      $output = '';  
      if($_FILES['zip_file']['name'] != '')  //check if file is selected or not in input type and if name is blank or not
      {  
           $file_name = $_FILES['zip_file']['name'];  
           $array = explode(".", $file_name); //breaking the string into an array, in this case separating the file name from the file extension
           $name = $array[0]; //store file name
           $ext = $array[1]; //store file extension
           if($ext == 'zip') //check if the uploaded file is a zip file or not
           {  
                $path = 'uploads/'; //determining the path pf the uploaded zip file
                $location = $path . $file_name; //uploads the zip file to the path with its respective file name
                if(move_uploaded_file($_FILES['zip_file']['tmp_name'], $location))  
                {  
                     $zip = new ZipArchive; //creating a zip object from ZipArchive
                     if($zip->open($location)) //open the zip file
                     {  
                          $zip->extractTo($path); //extract the file contents to the specified path 
                          $zip->close(); //close the ZipArchive once file contents have been extracted to the specified path
                     }  
                     $files = scandir($path . $name); //get all files from the extracted folder to the uploads folder
 
                     foreach($files as $file) //check if files are images or not one by one 
                     {  
                          $file_ext = end(explode(".", $file)); //store extension of file from extracted folder
                          $allowed_ext = array('jpg', 'png'); //determining what kind of extensions are allowed, in this case jpg and png
                          if(in_array($file_ext, $allowed_ext)) //check if the file is in the proper format
                          {  
                               $new_name = md5(rand()).'.' . $file_ext;  
                               $output .= '<img src="uploads/'.$new_name.'" width="400" height="400" />'; //store uploaded and extracted images and display them with specified width and height 
                               copy($path.$name.'/'.$file, $path . $new_name); //copy all images from extracted folder to uploads folder 
                               unlink($path.$name.'/'.$file);  //remove file from extracted folder after copying 
                          }       
                     }  
                     unlink($location); //delete zip file from uploads folder
                     rmdir($path . $name); //remove an empty directory like extracted folder   
                }  
           }  
      }  
 }  
 ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Engineering Internship Assessment</title>
    <meta name="description" content="The HTML5 Herald" />
    <meta name="author" content="Digi-X Internship Committee" />

    <link rel="stylesheet" href="style.css?v=1.0" />
    <link rel="stylesheet" href="custom.css?v=1.0" />

</head>

<body>

    <div class="top-wrapper">
        <img src="https://assets.website-files.com/5cd4f29af95bc7d8af794e0e/5cfe060171000aa66754447a_n-digi-x-logo-white-yellow-standard.svg"
            alt="digi-x logo" height="70" />
        <h1>Engineering Internship Assessment</h1>
    </div>

    <!-- DO NO REMOVE CODE STARTING HERE -->
    <div class="display-wrapper">
        <div class="append-images-here">
        <!-- <p>No image found. Your extracted images should be here.</p> -->
            <!-- THE IMAGES SHOULD BE DISPLAYED INSIDE HERE -->
            <form method="post" enctype="multipart/form-data"> <!-- allows user to upload a file via a form through POST -->
                <label>Please Select Zip File</label>
                <input type="file" name="zip_file" /> <!-- upload the zip file to the upload directory -->
                <br />
                <input type="submit" name="btn_zip" class="btn btn-info" value="Upload" /> <!-- submit button called Upload to upload chosen file -->
            </form>
            <br />
    <h2 style="margin-top:30px;">My Images</h2>
    <?php  
    if(isset($output)) //check if output variable has some value that will display images on the webpage
    {  
        echo $output; //display images on webpage
    }  
    ?>
        </div>
    </div>
    <!-- DO NO REMOVE CODE UNTIL HERE -->

</body>

</html>