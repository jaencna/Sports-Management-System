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
    <link rel="stylesheet" href="../../cssFile/coachingCSS.css">

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
            font-family: helvetica;
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

        
        <a href="coaching.php" class="loading-link" title="Home Page"><i class="fa-solid fa-house active"></i>Home</a>
        <hr style="width: 100vh; font-weight:bold;">
        <div class="teamManager" style="height: 33vh;">
            <a href="manageTeamBball.php" class="loading-link" title="Manage Team"><i class="fa-solid fa-people-group"></i>Manage Team</a>
            <a href="managePlayerBball.php" class="loading-link"><i class="fa-solid fa-users"></i></i>Manage Player</a>
            <a href="createMatchBball.php" class="loading-link"><i class="fa-solid fa-basketball"></i>Create Match</a>  
            <a href="rangking.php" class="loading-link"><i class="fa-solid fa-ranking-star"></i>Rankings</a>
            <a href="rangking.php" class="loading-link"><i class="fa-solid fa-ranking-star"></i>Rankings</a>              
        </div>
       <hr style="width: 100vh; font-weight:bold;">
        <a href="contacts.php" class="loading-link"><i class="fas fa-envelope mr-2"></i>Contact</a>
        <a href="../buttonFunction/logOutButton.php" class="loading-link"><i class="fa-solid fa-right-from-bracket"></i>Log Out</a>

    </div>

    <div id="main" style="background-color: black; height: 2419px; background-image: url('../../images/homepage/TERRAFORM.png');background-size: cover; background-position: center; height: 100vh; background-repeat: no-repeat;overflow-x: hidden; padding-top:20vh;">

      <div class = "row">
        <div id="play" class = "col-sm-5" style="height: 150px; color: white; font-size: 90px; padding-top: 40px; padding-left: 100px; font-family: arial;"> <b> <p>Play.
          Score.
          Win.</p></b></div>
        <div class = "col-sm-7" style="height: 150px;"> <img src="../../images/homepage/basketbolero.png" class="img-responsive;" style="max-width: 100%; height: auto;"></div>
      </div>

<!--About-->

        <div style="background-image: url('../../images/homepage/BOTT_HOME.png');background-size: cover; margin-top: 420px;">
          <div class="row" style="margin-top: 40px; padding-left: 50px;">
            <div class="col-sm-5"><img src="../../images/homepage/LOGO_CIRCLE.png" class="img-responsive;" style="max-width: 100%; height: auto;"></div>
            <div class="col-sm-7"><p style="font-size: 56pt;color: white; font-family: Arial, Helvetica, sans-serif; margin-top: 50px; padding-left: 100px;"> 
              <b>About</b>
              </p>
              <p style="color: white; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding-left: 100px; font-size: 15pt; margin-right: 100px; text-align: justify; line-height: 1.2;">
      This is the SportsManager Pro. Welcome! Our objective is to simplify and improve the efficiency of administering sports teams, events, and activities. <br><br>
      Our platform gives clubs, educational institutions, sports leagues, and individual athletes the tools and resources they need to run their organizations
      more efficiently. We believe that sports have the power to unite people and inspire greatness. Our goal is to help coaches and athletes by offering
      a wide range of management tools. Whether you're in charge of a community event, a league, or a competitive team, our approach addresses your unique
      challenges.<br><br>
      
      Our user-friendly platform facilitates clear communication, performance monitoring, and scheduling. Join us as we transform sports activity
      management to improve efficiency and enjoyment for all.

              </p>
  
            </div>
    
          </div>

          <div class = "row">
            <div class = "col-sm-6" style="height: 150px; padding-top: 10px; padding-left: 120px;"> 

<!--Services-->

              <b><p style="color: white; font-size: 56pt; ">Services</p></b>

              <p style="color: white; font-size: 20px;  text-align: justify; line-height: 1.1;">
                Our SportManager Pro offers comprehensive tools to streamline sports team operations and athlete
                management. Key features includes</p>
              
<!--Scoring-->
              <div class="row">

                <div class="col-sm-5" style=" padding-top: 65px"><img src="../../images/homepage/scoring.png" class="img-responsive;" style="max-width: 100%; height: auto;"></div>

                <div class="col-sm-7">
                  <p style="color: white;font-family: Arial, Helvetica, sans-serif; font-size: 24pt; margin-top: 50px; padding-left: 50px;line-height: 1.1;"><b>Scoring: </b>  </p>
                  <p style="color: white; font-size: 20px; text-align: justify; padding-left: 50px;line-height: 1.1;"> Track and analyze game scores in real-time, 
                    providing detailed statistics and performance metrics for teams and individual athletes.</p>
                </div>
              </div>  
              
<!--Coach Management-->

              <div class="row">
                <div class="col-sm-5" style=" padding-top: 40px"><img src="../../images/homepage/coachmng.png" class="img-responsive;" style="max-width: 100%; height: auto;">
                </div>

                <div class="col-sm-7">
                  <p style="color: white;font-family: Arial, Helvetica, sans-serif; font-size: 24pt; padding-top: 20px; padding-left: 50px;line-height: 1.1;"><b>Coach Management: </b>  </p>
                  <p style="color: white; font-size: 20px; text-align: justify; padding-left: 50px;line-height: 1.1;"> Efficiently manage coaching staff, including scheduling, performance tracking,
                     and communication tools to enhance team coordination and development.</p>
                </div>
              </div>

<!--Team-->

              <div class="row">
                <div class="col-sm-5" style=" padding-top: 40px"><img src="../../images/homepage/team.png" class="img-responsive;" style="max-width: 100%; height: auto;">
                </div>

                <div class="col-sm-7">
                  <p style="color: white;font-family: Arial, Helvetica, sans-serif; font-size: 24pt; padding-top: 20px; padding-left: 50px;line-height: 1.1;"><b>Team and Athlete Data:</b>  </p>
                  <p style="color: white; font-size: 20px; text-align: justify; padding-left: 50px;line-height: 1.1;"> Maintain detailed profiles for teams and athletes, including personal information, performance stats,
                     training schedules, and medical records, ensuring all necessary data is easily accessible.</p>
                </div>


              </div>

            </div>  
            
            
                
                <div class = "col-sm-6" style="padding-left: 85px; padding-top: 250px;">
                  <img src="../../images/homepage/bolz.png" class="img-responsive;" style="max-width: 100%; height: auto;"></div>
                

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