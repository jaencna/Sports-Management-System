<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>

    <!-- BS LINKS -->
    <!-- <link rel="stylesheet" href="../../bsCSS/bootstrap.css">
    <link rel="stylesheet" href="../../bsCSS/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    

    <!-- Font Awesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Page CSS and JS -->
    <link rel="stylesheet" href="../../cssFile/createMatchBballCSS.css">

    <style>
        * {
            box-sizing: border-box;
            /* Include padding and borders in width/height */
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
            background-color: darkslategray;
            display: flex;
            height: 100vh;
            font-family: georgia;
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
            background: rgba(18,18,18,255);
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
            padding: 30px 8px 5px 80px;
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
            font-family:helvetica;
        }

        .sidebar h1 {
            font-size: 35px;
            color: white;
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

        .activa {
            color:white;
        }

        hr{
            color:gray;
            
        }
    </style>

    

</head>
<body>
<div id="loadingScreen" style="display: none;">
        <img src="../../images/loadingAnimation.gif" alt="Loading...">
    </div>

    <div id="mySidebar" class="sidebar"  onmouseover="openNav()" onmouseleave="closeNav()" onclick="toggleNav()">
    <div style="margin-top:-59px; height: 70px; color: white; align-content: center;"> <img src="../../images/homepage/natakbo.png" style="padding-left: 18px;" height="40" > </div>
        <div id="userProfile" class="d-flex align-items-center">
            <img title="Click to View or Edit" src="" alt="Profile Image" id="profileImage" class="mr-3">
            <input type="file" id="fileInput"  accept="image/*" style="display: none;" />

            <div>
                <h1 id="userName"></h1>
                <h2 id="userPosition"></h2>
            </div>
        </div>

        
        <a href="coaching.php" class="loading-link" title="Home Page"><i class="fa-solid fa-house"></i>Home</a>
        <hr style="width: 100vh; font-weight:bold;">
        <div class="teamManager" style="height: 33vh;">
            <a href="manageTeamBball.php" class="loading-link" title="Manage Team"><i class="fa-solid fa-people-group"></i>Manage Team</a>
            <a href="managePlayerBball.php" class="loading-link"><i class="fa-solid fa-users"></i></i>Manage Players</a>
            <a href="createMatchBball.php" class="loading-link"><i class="fa-solid fa-basketball active"></i>Create Match</a>  
            <a href="rangking.php" class="loading-link"><i class="fa-solid fa-ranking-star"></i>Rankings</a>
            <a href="rangking.php" class="loading-link"><i class="fa-solid fa-ranking-star"></i>Rankings</a>              
        </div>
       <hr style="width: 100vh; font-weight:bold;">
        <a href="#" class="loading-link"><i class="fas fa-envelope mr-2"></i>Contact</a>
        <a href="../buttonFunction/logOutButton.php" class="loading-link"><i class="fa-solid fa-right-from-bracket"></i>Log Out</a>

    </div>

    <!-- Improved HTML Structure -->
    <div id="main">
        <div class="row top-drop">
            <select id="teamSelect">
                <option>TEAM-1</option>
                <option>TEAM-2</option>
            </select>
            
        </div>
        <div class="row top-main">
            <div id="selectedAthletes">
            
            </div>
        </div>

        <div class="row bottom-drop">
                <select id="positionSelect">
                    <option value="--">-- SELECT POSITION --</option>
                    <option>POINT GUARD</option>
                    <option>SHOOTING GUARD</option>
                    <option>FORWARD</option>
                    <option>CENTER</option>
                </select>

        </div>
        <div class="row bottom-main">
            <div class="col-md-12">
                <div id="athleteCardContainer"></div>
            </div>
        </div>

        <button id="createMatchOpenModal" type="button" class="btn btn-sucess">Create Match</button>
    </div>
    

    <div class="modal fade" id="createMatchModal" tabindex="-1" role="dialog" aria-labelledby="createMatchModal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document"> 
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Create Match</h5>
                    <div class="ms-auto d-flex">
                        <!-- Match Name Input -->
                        <div class="me-3">
                            <label for="matchName" class="form-label">Match Name:</label>
                            <input type="text" id="matchName" class="form-control" style="width: 250px;">
                        </div>
                        <!-- Match Date & Time Input -->
                        <div>
                            <label for="matchDateTime" class="form-label">Match Date & Time:</label>
                            <input type="datetime-local" id="matchDateTime" class="form-control" style="width: 250px;">
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h1>TEAM 1</h1>
                            <div class="teamOneContainer d-flex flex-wrap justify-content-center" id="teamOneContainer"></div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h1>TEAM 2</h1>
                            <div class="teamTwoContainer d-flex flex-wrap justify-content-center" id="teamTwoContainer"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <button type="button" class="btn btn-success mr-2" id="createMatchBtn" style="width: 20vh;">CREATE MATCH</button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                <button type="button" title="Change Profile Photo" class="btn btn-success" id="updatePhoto">Change Profile Picture</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    <!-- AJAX link -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../../jsFile/createMatchBballJS.js"></script>
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
    document.querySelector('.teamManager').style.overflowY = 'auto'; // Show overflow
    
}

function closeNav() {
    document.getElementById("mySidebar").style.width = "80px";
    document.querySelector('.teamManager').style.overflow = 'hidden'; // Hide overflow
    
}

        $(document).ready(function() {
            closeNav();
            $.ajax({
                type: "GET",
                url: "../onloadFunction/getLoggedIn.php",
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#userName').text(response.loggedInUserData.coach_lname);
                        $('#userPosition').text("COACH");
                        $('#profileImage').attr('src', '../../images/profile/' + response.loggedInUserData.coach_img);
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