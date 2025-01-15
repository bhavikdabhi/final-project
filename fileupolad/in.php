<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "files";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to display files as buttons
function displayFiles($conn) {
    // Update SQL query to select enrollment number as well
    $sql = "SELECT id, filename, enrollment_no FROM file_uploads";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $filename = htmlspecialchars($row['filename']); // Prevent XSS
            $enrollment_no = htmlspecialchars($row['enrollment_no']); // Prevent XSS

            // Display filename with enrollment number
            echo "<div class='row'>
                    <button onclick='openFile($id)'><i class='fas fa-file-alt'></i> $filename <span class='enrollment'>$enrollment_no</span></button>
                  </div>";
        }
    } else {
        echo "<div class='no-files'>No files found.</div>";
    }

    mysqli_free_result($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Uploader and Viewer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
*{
    margin: 0;
    padding:0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}
:root{
    --back-color: #4A90E2;
}
body{
  background:linear-gradient(65deg, #242830 60%, #4A90E2 40%);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;  /* for height */
}

::selection{
    color: #fff;
    background-color: var(--back-color);
}
.wrapper{
    width: 430px;
    background-color: #2b2b2c;
    border-radius: 5px;
    padding: 30px;
    box-shadow: 7px 7px 12px rgba(0,0,0,0.05);
}
.wrapper header{
    color: white;
    font-size: 27px;
    font-weight: 600;
    text-align: center;
} 
.wrapper form{
    height: 167px;
    display: flex;
    cursor: pointer;
    margin: 30px 0;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    border-radius: 5px;
    border: 2px dashed white; 
}
form :where(p)
{
    color: white;
}
form i{
    font-size: 50px;
    color: var(--back-color);
}
form p{
    margin-top: 15px;
    font-size: 16px;
}
section .row{
    margin-bottom: 10px;
    background-color: #e9f0ff;
    list-style: none;
    padding: 15px 20px;
   border-radius: 5px;
   display: flex;
   align-items: center;
   justify-content: space-between; 
}

section .row i{
    color : var(--back-color);
    font-size:30px;
}
section .details span{
    font-size: 14px;
}
.progress-area .row .content{
  width: 100%;
  margin-left: 15px;
}
.progress-area .details{
    display: flex;
    align-items: center;
    margin-bottom: 7px;
    justify-content: space-between;
}

.progress-area .content .progress-bar{
    height: 6px;
    width: 100%;
    margin-bottom: 4px;
    background: #fff;
    border-radius: 30px;
  }
  .content .progress-bar .progress{
    height: 100%;
    width: 0%;
    background: #6990F2;
    border-radius: inherit;
  }
  .uploaded-area{
    max-height: 232px;
    overflow-y: scroll;
  }
  .uploaded-area.onprogress{
    max-height: 150px;
  }
  .uploaded-area::-webkit-scrollbar{
    width: 0px;
  }
  .uploaded-area .row .content{
    display: flex;
    align-items: center;
  }
  .uploaded-area .row .details{
    display: flex;
    margin-left: 15px;
    flex-direction: column;
  }
  .uploaded-area .row .details .size{
    color: #404040;
    font-size: 11px;
  }
  .uploaded-area i.fa-check{
    font-size: 16px;
  }
  
    </style>
    <script>
        function openFile(fileId) {
            window.location.href = "open_file.php?id=" + fileId;
        }
    </script>
     <script src="https://cdn.tailwindcss.com"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>
<button 
    onclick="history.back()" 
    class="absolute top-4 left-4 bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 focus:outline-none">
    ← Back
  </button>
<div class="wrapper">
<header>File Uploader JavaScript</header>

        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="files[]"  class="file-input" id="file"  hidden multiple>
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Browse File to Upload</p>
          </form>
        <section class="progress-area"></section>
    <section class="uploaded-area"></section>       
    </div>

    <script>

const form = document.querySelector("form"),
  fileInput = document.querySelector(".file-input"),
  progressArea = document.querySelector(".progress-area"),
  uploadedArea = document.querySelector(".uploaded-area");

// Prevent default form submission
form.addEventListener("submit", (event) => {
  event.preventDefault(); // Stop the form from submitting traditionally
});

// Form click event to trigger file selection
form.addEventListener("click", () => {
  fileInput.click();
});

fileInput.onchange = ({ target }) => {
  const files = target.files; // Get all selected files
  if (files.length > 0) {
    for (let i = 0; i < files.length; i++) {
      uploadFile(files[i]); // Call uploadFile for each selected file
    }
  }
};

function uploadFile(file) {
  const xhr = new XMLHttpRequest(); // Create new XMLHttpRequest (AJAX)
  xhr.open("POST", "upload.php"); // Sending POST request to upload.php

  // File uploading progress event
  xhr.upload.addEventListener("progress", ({ loaded, total }) => {
    const fileLoaded = Math.floor((loaded / total) * 100); // Calculate percentage of loaded file
    const fileTotal = Math.floor(total / 1024); // Convert total size to KB
    let fileSize;
    fileTotal < 1024
      ? (fileSize = fileTotal + " KB")
      : (fileSize = (total / (1024 * 1024)).toFixed(2) + " MB");

    const progressHTML = `<li class="row">
                            <i class="fas fa-file-alt"></i>
                            <div class="content">
                              <div class="details">
                                <span class="name">${file.name} • Uploading</span>
                                <span class="percent">${fileLoaded}%</span>
                              </div>
                              <div class="progress-bar">
                                <div class="progress" style="width: ${fileLoaded}%"></div>
                              </div>
                            </div>
                          </li>`;
    progressArea.innerHTML = progressHTML;

    if (loaded === total) {
      progressArea.innerHTML = ""; // Clear progress
      const uploadedHTML = `<li class="row">
                              <div class="content upload">
                                <i class="fas fa-file-alt"></i>
                                <div class="details">
                                  <span class="name">${file.name} • Uploaded</span>
                                  <span class="size">${fileSize}</span>
                                </div>
                              </div>
                              <i class="fas fa-check"></i>
                            </li>`;
      uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML); // Add to uploaded files list
    }
  });

  const data = new FormData(); // Create FormData object
  data.append("files[]", file); // Append file to FormData object
  xhr.send(data); // Send FormData via AJAX
}


    </script>
    
 

</body>

</html>
