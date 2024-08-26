<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <!-- BS LINKS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


    <!-- Page CSS and JS -->
    <link rel="stylesheet" href="../../cssFile/manageUserCSS.css">

    <style>
        * {
            box-sizing: border-box;
            /* Include padding and borders in width/height */
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #000000;
            background-image: url('../../images/homepage/bott_home.png')
        }

        /* Profile Image Styles */
        #userProfile {
            display: flex;
            align-items: center;
            padding: 20px 0 0 17px;
            /* Adjust padding to fit your design */
        }

        #profileImage {
            width: 50px;
            /* Adjust size as needed */
            height: 50px;
            /* Ensure it is a square */
            border-radius: 10%;
            /* Optional: makes the image round */
            object-fit: cover;
            /* Ensures the image covers the area */
        }

        /* Sidebar styles */
        .sidebar {
            height: 100%;
            width: 80px;
            position: fixed;
            top: 0;
            left: 0;
            /* Ensure it stays at the left edge */
            background: #121212;
            /* background: -moz-linear-gradient(45deg, #FFE3E3 0%, #B5B5B5 51%, #C0C0C0 100%);
            background: -webkit-linear-gradient(45deg, #FFE3E3 0%, #B5B5B5 51%, #C0C0C0 100%);
            background: linear-gradient(45deg, #FFE3E3 0%, #B5B5B5 51%, #C0C0C0 100%); */
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 60px;
            z-index: 9000;
            /* Ensure the sidebar is on top of other content */
        }

        .sidebar a {
            padding: 30px 8px 8px 80px;
            /* Adjust padding to make space for icons */
            text-decoration: none;
            font-size: 23px;
            color: #333333;
            display: block;
            transition: 0.3s;
            position: relative;
            font-family: helvetica;
            width: 50vh;
            /* Ensure icons are positioned correctly */
        }

        .sidebar a i {
            position: absolute;
            /* Fix the icon's position */
            left: 10px;
            /* Align the icon within the sidebar */
            top: 50%;
            /* Center the icon vertically */
            padding-top: 30px;
            padding-left: 17px;
            transform: translateY(-50%);
        }

        .sidebar h1,
        .sidebar h2 {
            text-decoration: none;
            color: rgb(34, 34, 34);
            display: block;
            transition: 0.3s;
            margin-left:20px;
            font-family: helvetica;
        }

        .sidebar h1 {
            font-size: 35px;
            color:white;
        }

        .sidebar h2 {
            font-size: 15px;
            color: #818181;
        }

        .sidebar a:hover {
            color: #ffffff;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 36px;
            margin-left: 50px;
            cursor: pointer;
        }

        /* Main Content */
        #main {
            margin-left: 80px;
            /* Space for sidebar */
            transition: margin-left .3s;
            /* Smooth transition for content shift */
            position: relative;
            /* Keeps it in the flow of the document */
            overflow: auto;
            /* Prevent overflow affecting layout */
            padding: 30px;
            width: 100%;
        }

        #imageModal{
            z-index: 10000;
        }

        .active {
            color: white;
        }

        #loadingScreen {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            z-index: 10000; /* Ensure it's above other content */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #loadingScreen img {
            width: 300px; /* Adjust size as needed */
            height: 200px; /* Adjust size as needed */
        }

        /* Responsive Table */
        .table-responsive {
            overflow-x: auto;
        }

        .search{
            color:white;
        }

        .searching {
            background-color:black;
        }
    </style>
</head>
<body>
    <div id="loadingScreen" style="display: none;">
        <img src="../../images/loadingAnimation.gif" alt="Loading...">
    </div>

    <div id="mySidebar" class="sidebar"  onmouseover="openNav()" onmouseleave="closeNav()" onclick="toggleNav()">
    <div style="margin-top:-45px; height: 70px; color: white; align-content: center;"> <img src="../../images/homepage/natakbo.png" style="padding-left: 18px;" height="40" > </div>
        <div id="userProfile" class="d-flex align-items-center">
            <img title="Click to View or Edit" src="" alt="Profile Image" id="profileImage" class="mr-3">
            <input type="file" id="fileInput"  accept="image/*" style="display: none;" />

            <div>
                <h1 id="userName"></h1>
                <h2 id="userPosition"></h2>
            </div>
        </div>

        
        <a href="admin.php" class="loading-link" title="Home Page"><i class="fa-solid fa-house"></i>Home</a>
        <a href="manageUser.php" class="loading-link" title="Manage Profiles"><i class="fa-solid fa-user active"></i>Manage Users</a>
        
        <a href="adminContact.php" class="loading-link"><i class="fas fa-envelope mr-2"></i>Contacts</a>
        <a href="../buttonFunction/logOutButton.php" class="loading-link"><i class="fa-solid fa-right-from-bracket"></i>Log_Out</a>
    </div>

    <div id="main">

        <div class="row">
            <!-- <div class="col-md-3" style="text-align: center; ">
                <button type="button" class="btn btnWidth headbtn" id="requestSignUpButton">Request Sign Up</button>
            </div>
            <div class="col-md-3" style="text-align: center;">
                <button type="button" class="btn headbtn btnWidth" id="inactiveUserButton">Inactive Users</button>
            </div>
            <div class="col-md-3" style="text-align: center;">
                <button type="button" class="btn headbtn btnWidth" id="requestSignUpButton">Request Sign Up</button>
            </div> -->
            <div class="col-md-12" style="text-align: right">
            <button type="button" id="notificationButton" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bell"></i>
            </button>
            <ul class="dropdown-menu">
                <!-- Dropdown menu links -->
                 <li><a class="dropdown-item" id="requestSignUpButton">Request Sign Up</a></li>
                 <li><a class="dropdown-item" id="inactiveUserButton">Inactive Users</a></li>
            </ul>
        </div>
        </div>

        

        <div class="row">
            <div class="col-md-6"> <!-- Adjust the col size as needed -->
                <div class="container-coach mt-5 ">
                    <div class="row">
                        <div class="col-md-5">
                            <h2 style="padding-left:10px" ><b>Coach Details</b></h2>
                            
                        </div>
                        <div class="col-md-1">
                            
                        </div>
                        <div class="col-md-5" style="align-items: center; justify-content:center;">
                            <div class="input-group mb-2">
                                <input type="text" id="searchCoach" class="form-control" placeholder="Coach Last Name" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <button id="searchCoachButton" class="btn btn-outline-secondary searching" type="button" id="button-addon2"><i class="fa-solid fa-magnifying-glass search"></i></button>
                                
                            </div>
                            
                        </div>
                        <div class="col-md-1 filters">
                            <h4><i class="fa-solid fa-filter" id="filterCoach"></i></h4>
                        </div>
                    </div>
                    <div id="container-coaches" style="width: 100%; max-width: 600px; height:10vh; overflow: auto;">
                        <div id="selectedCoaches"></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Given</th>
                                    <th>Surname</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Sport</th>
                                    <th>Position</th>
                                    <th>STATUS</th>
                                    <th>IMG</th>
                                    
                                </tr>
                            </thead>
                            <tbody id="coachTableBody">
                                <!-- Data will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="fixed-buttons d-flex justify-content-between flex-wrap">
                        <button id="deleteSelectedCoach" class="btn mb-2 delete" title="Delete Selected Coach">
                            <i class="fa-solid fa-trash"></i> Delete Selected
                        </button>
                        <button id="deleteAllCoach" class="btn mb-2 delete" title="Delete All Coach">
                            <i class="fa-solid fa-dumpster"></i> Delete All
                        </button>
                        <button id="deactivateCoach" class="btn btn-warning mb-2" title="Deactivate Selected Coach">
                        <i class="fa-solid fa-circle-xmark"></i> Deactivate Selected Coach
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6"> <!-- Adjust the col size as needed -->
                <div class="container-athlete mt-5 ">
                    <div class="row">
                        <div class="col-md-5">
                        <h2 style="padding-left:10px" ><b>Athlete Details</b></h2>
                            
                        </div>
                        <div class="col-md-1">
                            
                        </div>
                        <div class="col-md-5" style="align-items: center; justify-content:center;">
                            <div class="input-group mb-2">
                                <input type="text" id="searchAthlete" class="form-control" placeholder="Athlete Last Name" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <button id="searchAthleteButton" class="btn btn-outline-secondary searching" type="button" id="button-addon2"><i class="fa-solid fa-magnifying-glass search"></i></button>
                                
                            </div>
                            
                        </div>
                        <div class="col-md-1 filters">
                            <h4><i class="fa-solid fa-filter" id="filterAthlete"></i></h4>
                        </div>
                    </div>
                    <div id="container-Athletes" style="width: 100%; max-width: 100%; height:10vh; overflow: auto;">
                        <div id="selectedAthletes"></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Given</th>
                                    <th>Surname</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Sport</th>
                                    <th>Position</th>
                                    <th>Height</th>
                                    <th>Weight</th>
                                    <th>STATUS</th>
                                    <th>IMG</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody id="athleteTableBody">
                                <!-- Data will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="fixed-buttons d-flex justify-content-between flex-wrap">
                        <button id="deleteSelectedAthlete" class="btn mb-2 delete" title="Delete Selected Athlete">
                            <i class="fa-solid fa-trash"></i> Delete Selected
                        </button>
                        <button id="deleteAllAthlete" class="btn mb-2 delete" title="Delete All Athlete">
                            <i class="fa-solid fa-dumpster"></i> Delete All
                        </button>
                        <button id="deactivateAthlete" class="btn btn-warning mb-2" title="Deactivate Selected Athlete">
                        <i class="fa-solid fa-circle-xmark"></i></i> Deactivate Selected Athlete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        
        
    </div>


    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Profile Image" style="width:100%; height:auto;">
                </div>
                <div class="modal-footer">
                    <button type="button" title="Change Profile Photo" class="btn btn-success loading-link" id="updatePhoto">Change Profile Picture</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="coachFilterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                <select id="coachSportInput" name="athSport">
                        <option value="--">-- Select Sport --</option>
                </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="requestSignUpModal" tabindex="-1" role="dialog" aria-labelledby="requestSignUpModal" aria-hidden="true">
        <div class="modal-dialog wider-modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6" style="text-align: center;">
                            <select id="userTypeInput" name="userTypeInput">
                                <option value="--">-- Select User Type --</option>
                                <option value="COACH">COACH</option>
                                <option value="ATHLETE">ATHLETE</option>
                            </select>
                        </div>
                        <div class="col-md-6" style="text-align: center;"> 
                            <select id="reqSportInput" name="reqSportInput">
                                <option value="--">-- Select Sport --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <div id="container-Request" style="width: 100%; max-width: 100%; height:10vh; overflow: auto;">
                            <div id="selectedRequest"></div>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            
                                            
                                            <th>Given</th>
                                            <th>Surname</th>
                                            <th>Email</th>
                                            <th>User Type</th>
                                            <th>Sport</th>
                                            <th>Position</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody id="requestSignUpBody">
                                        <!-- Data will be dynamically inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    

                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <button type="button" class="btn btn-success mr-2" id="acceptButton" style="width: 20vh;">ACCEPT</button>
                            <button type="button" class="btn btn-danger mr-2" id="declineButton" style="width: 20vh;">DECLINE</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="inactiveUserModal" tabindex="-1" role="dialog" aria-labelledby="inactiveUserModal" aria-hidden="true">
        <div class="modal-dialog wider-modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6" style="text-align: center;">
                            <select id="inactiveUserTypeInput" name="inactiveUserTypeInput">
                                <option value="COACH">COACH</option>
                                <option value="ATHLETE">ATHLETE</option>
                            </select>
                        </div>
                        <div class="col-md-6" style="text-align: center;"> 
                            <select id="inactiveUserSportInput" name="inactiveUserSportInput">
                                <option value="--">-- Select Sport --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <div id="container-inactive" style="width: 100%; max-width: 100%; height:10vh; overflow: auto;">
                            <div id="selectedInactive"></div>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            
                                            <th>ID</th>
                                            <th>Given</th>
                                            <th>Surname</th>
                                            <th>Email</th>
                                            <th>Sport</th>
                                            <th>Position</th>
                                            <th>STATUS</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody id="inactiveUserBody">
                                        <!-- Data will be dynamically inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    

                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <button type="button" class="btn btn-success mr-2" id="activeButton" style="width: 20vh;">MAKE ACTIVE</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="athleteFilterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                <select id="athleteSportInput" name="athSport">
                        <option value="--">-- Select Sport --</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="imageModal2" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Coach Image</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="coachImage" src=""  alt="Coach Image" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <form id="updatePhotoForm" enctype="multipart/form-data">
                        <input type="hidden" id="coachEmailForImage" name="coachEmail">
                        <input type="file" accept="image/*" name="newImage" id="newImage" accept="image/*">
                        <button type="submit" class="btn btn-primary">Update Photo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal3" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Coach Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="athleteImage" src=""  alt="Athlete Image" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <form id="updatePhotoFormAthlete" enctype="multipart/form-data">
                        <input type="hidden" id="athleteEmailForImage" name="athleteEmail">
                        <input type="file" accept="image/*" name="newAthleteImage" id="newAthleteImage" accept="image/*">
                        <button type="submit" class="btn btn-primary">Update Photo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- AJAX link -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  
    <script src="../../jsFile/manageUserJS.js"></script>
    <script>
    // JS for the loading page
        function showLoadingScreen() {
            document.getElementById('loadingScreen').style.display = 'flex';
        }

        function hideLoadingScreen() {
            document.getElementById('loadingScreen').style.display = 'none';
        }

        window.addEventListener('load', hideLoadingScreen);

        window.addEventListener('beforeunload', showLoadingScreen);

        // $(document).ajaxStart(function() {
        //     showLoadingScreen();
        // });

        // $(document).ajaxComplete(function() {
        //     hideLoadingScreen();
        // });

        // $('form').on('submit', function() {
        //     showLoadingScreen();
        // });

        // Apply the loading screen to links with the loading-link class
        document.querySelectorAll('a.loading-link').forEach(function(anchor) {
            anchor.addEventListener('click', function() {
                showLoadingScreen();
            });
        });

    // JS for the Side Bar ------------------------------------------------------

        let navOpen = false;

        function toggleNav() {
            if (navOpen) {
                closeNav();
            } else {
                openNav();
            }
            navOpen = !navOpen;
        }

        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("mySidebar").style.left = "0";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "80px";
        }

        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "../onloadFunction/getLoggedIn.php",
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#userName').text(response.loggedInUserData.admin_email);
                        $('#userPosition').text("ADMIN");
                        $('#profileImage').attr('src', '../../images/profile/' + response.loggedInUserData.admin_img);
                    } else {
                        alert(response.message);
                        window.location.href = '../../index.php';
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });

            $('.dropdown-item').on('click', function() {
                var date = $(this).data('date');
                $('.date-page').hide(); // Hide all date pages
                $('#' + date).show(); // Show selected date page
            });

            $('#updatePhoto').click(function() {
                document.getElementById('fileInput').click();
            });

            // Handle file input change event to upload the image
            document.getElementById('fileInput').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const formData = new FormData();
                    formData.append('profileImage', file);

                    // Generate a random filename
                    const randomFileName = `${Math.floor(Math.random() * 10000000000)}_${file.name}`;
                    formData.append('fileName', randomFileName);

                    $.ajax({
                        url: '../buttonFunction/uploadProfileButton.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response === 'success') {
                                alert('Profile image updated successfully!');
                                $('#profileImage').attr('src', '../../images/profile/' + randomFileName);
                                $('#imageModal').modal('hide');
                            } else {
                                alert('Failed to upload image.');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('An error occurred: ' + textStatus + ' - ' + errorThrown);
                        }
                    });
                }
            });

            // Handle single-click to display the image in a modal
            document.getElementById('profileImage').addEventListener('click', function() {
                const imgSrc = this.src;
                document.getElementById('modalImage').src = imgSrc;
                $('#imageModal').modal('show');
            });
        });
    </script>
</body>
</html>
