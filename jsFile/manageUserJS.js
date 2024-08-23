$(document).ready(function () {
    let sportsData = [];
    let selectedCoachEmails = []; // Array to store selected coach emails

// Function to handle the response and update all dropdowns
    function updateSportDropdowns(data) {
        var reqSportInput = $('#reqSportInput');
        var inactiveUserSportInput = $('#inactiveUserSportInput');
        var athleteSportInput = $('#athleteSportInput');
        var coachSportInput = $('#coachSportInput');

        reqSportInput.empty(); // Clear existing options
        reqSportInput.append('<option value="--">-- Select Sport --</option>'); // Default option

        inactiveUserSportInput.empty(); // Clear existing options
        inactiveUserSportInput.append('<option value="--">-- Select Sport --</option>'); // Default option
        

        athleteSportInput.empty(); // Clear existing options
        athleteSportInput.append('<option value="--">-- Select Sport --</option>'); // Default option

        coachSportInput.empty(); // Clear existing options
        coachSportInput.append('<option value="--">-- Select Sport --</option>'); // Default option

        data.forEach(function(sport) {
            var option = $('<option>', { value: sport, text: sport });

            reqSportInput.append(option.clone());
            athleteSportInput.append(option.clone());
            coachSportInput.append(option.clone());
            inactiveUserSportInput.append(option.clone());
        });

        sportsData = data;
    }

    // Combined AJAX request
    $.ajax({
        url: '../onloadFunction/getSports.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            updateSportDropdowns(data);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching sports data: ", error);
        }
    });

    // Function to fetch and display coach data
    function fetchCoaches(sport, coachLastName = '') {
        $.ajax({
            url: '../onloadFunction/getCoaches.php',
            type: 'GET',
            data: { sport: sport, coach_lname: coachLastName },
            dataType: 'json',
            success: function (data) {
                
                let tableBody = $('#coachTableBody');
                tableBody.empty(); // Clear existing table rows

                data.forEach(function (coach) {
                    let isChecked = selectedCoachEmails.includes(coach.coach_email) ? 'checked' : '';
                    let row = `<tr>
                        <td>
                            <input type="checkbox" class="select-coach" title = "${coach.coach_lname}" data-id="${coach.coach_email}" data-name="${coach.coach_fname} ${coach.coach_lname}" data-sport="${coach.coach_sport}" ${isChecked}>
                        </td>
                        <td class="editable" title="Double click to Edit" data-field="coach_fname" data-id="${coach.coach_email}">${coach.coach_fname}</td>
                        <td class="editable" title="Double click to Edit" data-field="coach_lname" data-id="${coach.coach_email}">${coach.coach_lname}</td>
                        <td class="editable" title="Double click to Edit" data-field="coach_email" data-id="${coach.coach_email}">${coach.coach_email}</td>
                        <td class="editable" title="Double click to Edit" data-field="coach_pass" data-id="${coach.coach_email}">${coach.coach_pass}</td>
                        <td class="editable-sport" title="Double click to Edit" data-field="coach_sport" data-id="${coach.coach_email}">${coach.coach_sport}</td>
                        <td class="editable-position-coach" title="Double click to Edit" data-field="coach_position" data-id="${coach.coach_email}">${coach.coach_position}</td>
                        <td class="editable-status-coach" title="Double click to Edit" data-field="STATUS" data-id="${coach.coach_email}">${coach.STATUS}</td>
                        <td>
                            <button class="btn btn-info view-image" data-id="${coach.coach_email}" data-image="${coach.coach_img}"><i class="fa-solid fa-eye"></i></button>
                        </td>
                        
                    </tr>`;
                    tableBody.append(row);
                });

                // Bind events after updating the table
                $('.select-coach').on('change', updateSelectedCoaches);
                $('.editable').on('dblclick', handleEditableDoubleClick);
                $('.editable-sport').on('dblclick', handleEditableSportDoubleClick);
                $('.editable-position-coach').on('dblclick', handleEditablePositionDoubleClickCoach);
                $('.editable-status-coach').on('dblclick', handleEditableStatusDoubleClickCoach);
                $('.view-image').on('click', handleViewImageClick);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching coaches data: ", error);
            }
        });
    }

    // Initial fetch for default view (show all coaches)
    fetchCoaches('--');

    // Handle changes in the sport dropdown
    $('#coachSportInput').on('change', function () {
        var selectedSport = $(this).val();
        var coachLastName = $('#searchCoach').val().trim();
        fetchCoaches(selectedSport, coachLastName);
    });

    // Handle input in the search field
    $('#searchCoach').on('input', function () {
        var coachLastName = $(this).val().trim();
        var selectedSport = $('#coachSportInput').val();

        // Only fetch coaches if the search field is empty
        if (coachLastName === '') {
            fetchCoaches(selectedSport);
        }
    });

    // Handle search button click
    $('#searchCoachButton').on('click', function () {
        var coachLastName = $('#searchCoach').val().trim();
        var selectedSport = $('#coachSportInput').val();
        fetchCoaches(selectedSport, coachLastName);
    });

    // Function to update the selected coaches list
    function updateSelectedCoaches() {
        selectedCoachEmails = []; // Clear the array before updating

        let selectedCoaches = [];
        $('.select-coach:checked').each(function () {
            let coachName = $(this).data('name');
            let coachSport = $(this).data('sport');
            selectedCoachEmails.push($(this).data('id')); // Add the email to the array
            selectedCoaches.push(`<span class="selected-coach-item">${coachName} (${coachSport}) <span class="remove-coach" data-name="${coachName}" data-sport="${coachSport}">X</span></span>`);
        });

        $('#selectedCoaches').html(selectedCoaches.length > 0 ? selectedCoaches.join(' ') : '');
    }

    $('#deactivateCoach').on('click', function () {
        let selectedCoach = [];
    
        $('.select-coach:checked').each(function () {
            let coachEmail = $(this).data('id');
            let coachSport = $(this).data('sport');
            selectedCoach.push({ email: coachEmail, sport: coachSport });
        });
    
        if (selectedCoach.length === 0) {
            alert("No coaches selected.");
            return;
        }
    
        if (!confirm("Are you sure you want to deactivate the selected coaches?")) {
            return;
        }
    
        $.ajax({
            url: '../buttonFunction/deactivateSelectedCoachButton.php',
            type: 'POST',
            data: { selectedCoach: selectedCoach },
            success: function (response) {
                alert("Selected coaches deactivated successfully.");
                
                // Clear the selectedCoachEmails array
                selectedCoachEmails = [];
    
                // Fetch and update the coaches list
                fetchCoaches($('#coachSportInput').val(), $('#searchCoach').val().trim());
                fetchInactive();
                
                // Reset the inactive user type dropdown to 'COACH'
                document.getElementById('inactiveUserTypeInput').value = 'COACH';
                
                // Clear the selected coaches display
                $('#selectedCoaches').empty();
            },
            error: function (xhr, status, error) {
                console.error("Error deactivating coaches: ", error);
                alert("An error occurred while deactivating the selected coaches.");
            }
        });
    });
    

    $('#deleteSelectedCoach').on('click', function () {
        let selectedCoachEmails = [];
        $('.select-coach:checked').each(function () {
            let coachEmail = $(this).data('id');
            selectedCoachEmails.push(coachEmail);
        });

        if (selectedCoachEmails.length === 0) {
            alert("No coaches selected.");
            return;
        }

        if (!confirm("Are you sure you want to delete the selected coaches?")) {
            return;
        }

        $.ajax({
            url: '../buttonFunction/deleteSelectedCoachButton.php',
            type: 'POST',
            data: { coachEmails: selectedCoachEmails },
            success: function (response) {
                alert("Selected coaches deleted successfully.");
                // Clear the selectedCoachEmails array
                selectedCoachEmails = [];
                // Fetch and update the coaches list
                fetchCoaches($('#coachSportInput').val(), $('#searchCoach').val().trim());
                // Clear the selected coaches display
                $('#selectedCoaches').empty();
            },
            error: function (xhr, status, error) {
                console.error("Error deleting coaches: ", error);
                alert("An error occurred while deleting the selected coaches.");
            }
        });
    });

    $('#deleteAllCoach').on('click', function () {
        let firstConfirmation = confirm("Do you want to delete all the coaches?");
        
        if (!firstConfirmation) {
            return;
        }
    
        let secondConfirmation = confirm("All coaches will be deleted...");
        
        if (!secondConfirmation) {
            return;
        }
    
        $.ajax({
            url: '../buttonFunction/deleteAllCoachButton.php',
            type: 'POST',
            success: function (response) {
                alert("All coaches deleted successfully.");
                // Clear the selectedCoachEmails array
                selectedCoachEmails = [];
                fetchCoaches($('#coachSportInput').val(), $('#searchCoach').val().trim());
            },
            error: function (xhr, status, error) {
                console.error("Error deleting coaches: ", error);
                alert("An error occurred while deleting the coaches.");
            }
        });
    });

    $(document).on('click', '.remove-coach', function () {
        let coachName = $(this).data('name');
        let coachSport = $(this).data('sport');
        $(`.select-coach[data-name="${coachName}"][data-sport="${coachSport}"]`).prop('checked', false).trigger('change');
    });

    function handleEditableDoubleClick() {
        let currentElement = $(this);
        let currentValue = currentElement.text();
        let inputField = $('<input>', {
            type: 'text',
            value: currentValue,
            class: 'form-control'
        });

        currentElement.html(inputField);
        inputField.focus();

        inputField.on('blur', function () {
            let newValue = inputField.val();
            let field = currentElement.data('field');
            let id = currentElement.data('id');

            currentElement.text(newValue);

            $.ajax({
                url: '../buttonFunction/updateCoachButton.php',
                type: 'POST',
                data: { field: field, value: newValue, id: id },
                success: function (response) {
                    console.log(response); // Log success or error message
                }
            });
        });
    }

    function handleEditableSportDoubleClick() {
        let currentElement = $(this);
        let currentValue = currentElement.text();
        let dropdown = $('<select>', { class: 'form-control' });

        sportsData.forEach(function (sport) {
            let option = $('<option>', { value: sport, text: sport });
            if (sport === currentValue) {
                option.prop('selected', true);
            }
            dropdown.append(option);
        });

        currentElement.html(dropdown);
        dropdown.focus();

        dropdown.on('blur change', function () {
            let newValue = dropdown.val();
            let field = currentElement.data('field');
            let id = currentElement.data('id');

            currentElement.text(newValue);

            $.ajax({
                url: '../buttonFunction/updateCoachButton.php',
                type: 'POST',
                data: { field: field, value: newValue, id: id },
                success: function (response) {
                    console.log(response); // Log success or error message
                }
            });
        });
    }

    

    function handleEditablePositionDoubleClickCoach() {

        let cell = $(this);
        let currentPosition = cell.text();
        let field = cell.data('field');
        let id = cell.data('id');
        let type = "COACH";
    
        // Make an AJAX request to fetch the positions based on the athlete's sport
        $.ajax({
            url: '../onloadFunction/getPositionCoach.php', // The PHP file that fetches position data
            type: 'GET',
            data: { type: type},
            dataType: 'json',
            success: function (positionData) {
                let select = $('<select class="form-control"></select>');
                positionData.forEach(function(position) {
                    select.append(`<option value="${position}" ${position === currentPosition ? 'selected' : ''}>${position}</option>`);
                });
                cell.html(select);
                select.focus().on('blur', function () {
                    let newPosition = $(this).val();
                    if (newPosition !== currentPosition) {
                        $.ajax({
                            url: '../buttonFunction/updateCoachButton.php',
                            type: 'POST',
                            data: { field: field, value: newPosition, id: id},
                            success: function (response) {
                                cell.html(newPosition);
                            },
                            error: function (xhr, status, error) {
                                console.error("Error updating Coach position: ", error);
                                cell.html(currentPosition);
                            }
                        });
                    } else {
                        cell.html(currentPosition);
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching positions: ", error);
                cell.html(currentPosition); // Revert to the original text if there's an error
            }
        });
    }

    function handleEditableStatusDoubleClickCoach() {
        let currentElement = $(this);
        let currentValue = currentElement.text().trim();
        let dropdown = $('<select>', { class: 'form-control' });
    
        // Define the status options
        let statusOptions = ['active', 'inactive'];
    
        // Create dropdown options for "Active" and "Inactive"
        statusOptions.forEach(function (status) {
            let option = $('<option>', { value: status, text: status });
            if (status === currentValue) {
                option.prop('selected', true);
            }
            dropdown.append(option);
        });
    
        currentElement.html(dropdown);
        dropdown.focus();
    
        dropdown.on('blur change', function () {
            let newValue = dropdown.val();
            let field = currentElement.data('field');
            let id = currentElement.data('id');
    
            currentElement.text(newValue);
    
            $.ajax({
                url: '../buttonFunction/updateCoachButton.php',
                type: 'POST',
                data: { field: field, value: newValue, id: id },
                success: function (response) {
                    fetchCoaches('--');
                }
            });
        });
    }
    

    function handleViewImageClick() {
        let image = $(this).data('image');
        let email = $(this).data('id');
        $('#coachImage').attr('src', '../../images/profile/' + image);
        $('#coachEmailForImage').val(email);
        $('#imageModal2').modal('show');
    }

    $('#newImage').on('change', function (e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function (event) {
                $('#coachImage').attr('src', event.target.result);
            }
            
            reader.readAsDataURL(file);
        }
    });

    $('#updatePhotoForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let fileInput = $('#newImage').get(0);

        if (fileInput.files.length === 0) {
            alert('No photo selected. Please choose an image before updating.');
            return;
        }

        $.ajax({
            url: '../buttonFunction/updateProfileCoachButton.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                alert('Profile image updated successfully!');
                console.log(response); // Log success or error message
                $('#imageModal2').modal('hide');
                location.reload();
            }
        });
    });

    $('#filterCoach').on('click', function () {
        $('#coachFilterModal').modal('show');
    });

    

    // athlete part --------------------------------------------------------------------------------------------------------------


    let selectedAthleteEmails = []; // Array to store selected athlete emails


    // Function to fetch and display athlete data
    function fetchAthletes(sport, athleteLastName = '') {
        $.ajax({
            url: '../onloadFunction/getAthletes.php',
            type: 'GET',
            data: { sport: sport, ath_last: athleteLastName },
            dataType: 'json',
            success: function (data) {
                let tableBody = $('#athleteTableBody');
                tableBody.empty(); // Clear existing table rows

                data.forEach(function (athlete) {
                    let isChecked = selectedAthleteEmails.includes(athlete.ath_email) ? 'checked' : '';
                    let row = `<tr>
                        <td>
                            <input type="checkbox" class="select-athlete" title = "${athlete.ath_last}" data-id="${athlete.ath_email}" data-name="${athlete.ath_first} ${athlete.ath_last}" data-sport="${athlete.ath_sport}" ${isChecked}>
                        </td>
                        <td class="editable-athlete" title="Double click to Edit" data-field="ath_first" data-id="${athlete.ath_email}">${athlete.ath_first}</td>
                        <td class="editable-athlete" title="Double click to Edit" data-field="ath_last" data-id="${athlete.ath_email}">${athlete.ath_last}</td>
                        <td class="editable-athlete" title="Double click to Edit" data-field="ath_email" data-id="${athlete.ath_email}">${athlete.ath_email}</td>
                        <td class="editable-athlete" title="Double click to Edit" data-field="ath_pass" data-id="${athlete.ath_email}">${athlete.ath_pass}</td>
                        <td class="editable-sport-athlete" title="Double click to Edit" data-field="ath_sport" data-position="${athlete.ath_position}" data-id="${athlete.ath_email}">${athlete.ath_sport}</td>
                        <td class="editable-position" title="Double click to Edit" data-field="ath_position" data-sport="${athlete.ath_sport}" data-id="${athlete.ath_email}">${athlete.ath_position}</td>
                        <td class="editable-status-athlete" title="Double click to Edit" data-field="STATUS" data-id="${athlete.ath_email}">${athlete.STATUS}</td>
                        <td>
                            <button class="btn btn-info view-image-athlete" data-id="${athlete.ath_email}" data-image="${athlete.ath_img}"><i class="fa-solid fa-eye"></i></button>
                        </td>
                        
                    </tr>`;
                    tableBody.append(row);
                });

                // Bind events after updating the table
                $('.select-athlete').on('change', updateSelectedAthletes);
                $('.editable-athlete').on('dblclick', handleEditableDoubleClickAthlete);
                $('.editable-sport-athlete').on('dblclick', handleEditableSportDoubleClickAthlete);
                $('.editable-position').on('dblclick', handleEditablePositionDoubleClickAthlete);
                $('.editable-status-athlete').on('dblclick', handleEditableStatusDoubleClickAthlete);
                $('.view-image-athlete').on('click', handleViewImageClickAthlete);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching athletes data: ", error);
            }
        });
    }

    // Initial fetch for default view (show all athletes)
    fetchAthletes('--');

    // Handle changes in the sport dropdown
    $('#athleteSportInput').on('change', function () {
        var selectedSport = $(this).val();
        var athleteLastName = $('#searchAthlete').val().trim();
        fetchAthletes(selectedSport, athleteLastName);
    });

    // Handle input in the search field
    $('#searchAthlete').on('input', function () {
        var athleteLastName = $(this).val().trim();
        var selectedSport = $('#athleteSportInput').val();

        // Only fetch athletes if the search field is empty
        if (athleteLastName === '') {
            fetchAthletes(selectedSport);
        }
    });

    // Handle search button click
    $('#searchAthleteButton').on('click', function () {
        var athleteLastName = $('#searchAthlete').val().trim();
        var selectedSport = $('#athleteSportInput').val();
        fetchAthletes(selectedSport, athleteLastName);
    });

    // Function to update the selected athletes list
    function updateSelectedAthletes() {
        selectedAthleteEmails = []; // Clear the array before updating

        let selectedAthletes = [];
        $('.select-athlete:checked').each(function () {
            let athleteName = $(this).data('name');
            let athleteSport = $(this).data('sport');
            selectedAthleteEmails.push($(this).data('id')); // Add the email to the array
            selectedAthletes.push(`<span class="selected-athlete-item">${athleteName} (${athleteSport}) <span class="remove-athlete" data-name="${athleteName}" data-sport="${athleteSport}">X</span></span>`);
        });

        $('#selectedAthletes').html(selectedAthletes.length > 0 ? selectedAthletes.join(' ') : '');
    }

    $(document).on('click', '.remove-athlete', function () {
        let athleteName = $(this).data('name');
        let athleteSport = $(this).data('sport');
        $(`.select-athlete[data-name="${athleteName}"][data-sport="${athleteSport}"]`).prop('checked', false).trigger('change');
    });

    // Handle delete selected athletes button click
    $('#deleteSelectedAthlete').on('click', function () {
        let selectedAthleteEmails = [];
        $('.select-athlete:checked').each(function () {
            let athleteEmail = $(this).data('id');
            selectedAthleteEmails.push(athleteEmail);
        });

        if (selectedAthleteEmails.length === 0) {
            alert("No athletes selected.");
            return;
        }

        if (!confirm("Are you sure you want to delete the selected athletes?")) {
            return;
        }

        $.ajax({
            url: '../buttonFunction/deleteSelectedAthleteButton.php',
            type: 'POST',
            data: { athleteEmails: selectedAthleteEmails },
            success: function (response) {
                alert("Selected athletes deleted successfully.");
                // Clear the selectedAthleteEmails array
                selectedAthleteEmails = [];
                // Fetch and update the athletes list
                fetchAthletes($('#athleteSportInput').val(), $('#searchAthlete').val().trim());
                // Clear the selected athletes display
                $('#selectedAthletes').empty();
            },
            error: function (xhr, status, error) {
                console.error("Error deleting athletes: ", error);
                alert("An error occurred while deleting the selected athletes.");
            }
        });
    });

    $('#deactivateAthlete').on('click', function () {
        let selectedAthletes = [];
        $('.select-athlete:checked').each(function () {
            let athleteEmail = $(this).data('id');
            let athleteSport = $(this).data('sport');
            selectedAthletes.push({ email: athleteEmail, sport: athleteSport });
        });
    
        if (selectedAthletes.length === 0) {
            alert("No athletes selected.");
            return;
        }
    
        if (!confirm("Are you sure you want to deactivate the selected athletes?")) {
            return;
        }
    
        $.ajax({
            url: '../buttonFunction/deactivateSelectedAthleteButton.php',
            type: 'POST',
            data: { selectedAthletes: selectedAthletes },
            success: function (response) {
                alert("Selected athletes deactivated successfully.");
                // Clear the selected athletes array
                selectedAthletes = [];
                // Fetch and update the athletes list
                fetchAthletes($('#athleteSportInput').val(), $('#searchAthlete').val().trim());
                fetchInactive();
                selectedAthleteEmails = []; // Clear the array before updating
                document.getElementById('inactiveUserTypeInput').value = 'COACH';
                // Clear the selected athletes display
                $('#selectedAthletes').empty();
                
            },
            error: function (xhr, status, error) {
                console.error("Error deactivating athletes: ", error);
                alert("An error occurred while deactivating the selected athletes.");
            }
        });
    });
    

    

    // Handle delete all athletes button click
    $('#deleteAllAthlete').on('click', function () {
        let firstConfirmation = confirm("Do you want to delete all the athletes?");
        
        if (!firstConfirmation) {
            return;
        }
    
        let secondConfirmation = confirm("This action cannot be undone. Are you sure you want to delete all athletes?");
        
        if (!secondConfirmation) {
            return;
        }
    
        $.ajax({
            url: '../buttonFunction/deleteAllAthleteButton.php',
            type: 'POST',
            success: function (response) {
                alert("All athletes have been deleted successfully.");
                // Fetch and update the athletes list
                fetchAthletes($('#athleteSportInput').val(), $('#searchAthlete').val().trim());
                // Clear the selected athletes display
                $('#selectedAthletes').empty();
                updateSportDropdowns(data);
            },
            error: function (xhr, status, error) {
                console.error("Error deleting all athletes: ", error);
                alert("An error occurred while deleting all athletes.");
            }
        });
    });
    
    // Handle editable cell double-click
    function handleEditableDoubleClickAthlete() {
        let cell = $(this);
        let currentText = cell.text();
        let field = cell.data('field');
        let id = cell.data('id');
    
        cell.html(`<input type="text" class="form-control" value="${currentText}">`);
        cell.find('input').focus().on('blur', function () {
            let newText = $(this).val();
            if (newText !== currentText) {
                $.ajax({
                    url: '../buttonFunction/updateAthleteButton.php',
                    type: 'POST',
                    data: { field: field, value: newText, id: id },
                    success: function (response) {
                        cell.html(newText);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error updating athlete: ", error);
                        cell.html(currentText);
                    }
                });
            } else {
                cell.html(currentText);
            }
        });
    }
    
    // Handle editable sport cell double-click
    function handleEditableSportDoubleClickAthlete() {
        let cell = $(this);
        let currentSport = cell.text();
        let field = cell.data('field');
        let id = cell.data('id');
        let position = '--';
    
        let select = $('<select class="form-control"></select>');
        sportsData.forEach(function(sport) {
            select.append(`<option value="${sport}" ${sport === currentSport ? 'selected' : ''}>${sport}</option>`);
        });
        cell.html(select);
        select.focus().on('blur', function () {
            let newSport = $(this).val();
            if (newSport !== currentSport) {
                $.ajax({
                    url: '../buttonFunction/updateAthleteSports.php',
                    type: 'POST',
                    data: { field: field, value: newSport, id: id, position:position },
                    success: function (response) {
                        cell.html(newSport);
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error("Error updating athlete sport: ", error);
                        cell.html(currentSport);

                    }
                });
            } else {
                cell.html(currentSport);
            }
        });
    }


    function handleEditablePositionDoubleClickAthlete() {
        let cell = $(this);
        let currentPosition = cell.text();
        let field = cell.data('field');
        let id = cell.data('id');
        let athleteSport = cell.data('sport');
    
        // Make an AJAX request to fetch the positions based on the athlete's sport
        $.ajax({
            url: '../onloadFunction/getPosition.php', // The PHP file that fetches position data
            type: 'GET',
            data: { sport: athleteSport },
            dataType: 'json',
            success: function (positionData) {
                let select = $('<select class="form-control"></select>');
                positionData.forEach(function(position) {
                    select.append(`<option value="${position}" ${position === currentPosition ? 'selected' : ''}>${position}</option>`);
                });
                cell.html(select);
                select.focus().on('blur', function () {
                    let newPosition = $(this).val();
                    if (newPosition !== currentPosition) {
                        $.ajax({
                            url: '../buttonFunction/updateAthleteButton.php',
                            type: 'POST',
                            data: { field: field, value: newPosition, id: id, sport: athleteSport },
                            success: function (response) {
                                cell.html(newPosition);
                            },
                            error: function (xhr, status, error) {
                                console.error("Error updating athlete position: ", error);
                                cell.html(currentPosition);
                            }
                        });
                    } else {
                        cell.html(currentPosition);
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching positions: ", error);
                cell.html(currentPosition); // Revert to the original text if there's an error
            }
        });
    }
    
    function handleEditableStatusDoubleClickAthlete() {
        let currentElement = $(this);
        let currentValue = currentElement.text().trim();
        let dropdown = $('<select>', { class: 'form-control' });
    
        // Define the status options
        let statusOptions = ['active', 'inactive'];
    
        // Create dropdown options for "Active" and "Inactive"
        statusOptions.forEach(function (status) {
            let option = $('<option>', { value: status, text: status });
            if (status === currentValue) {
                option.prop('selected', true);
            }
            dropdown.append(option);
        });
    
        currentElement.html(dropdown);
        dropdown.focus();
    
        dropdown.on('blur change', function () {
            let newValue = dropdown.val();
            let field = currentElement.data('field');
            let id = currentElement.data('id');
    
            currentElement.text(newValue);
    
            $.ajax({
                url: '../buttonFunction/updateAthleteButton.php',
                type: 'POST',
                data: { field: field, value: newValue, id: id },
                success: function (response) {
                    fetchAthletes($('#athleteSportInput').val(), $('#searchAthlete').val().trim());
                }
            });
        });
    }
    
    function handleViewImageClickAthlete() {
        let image = $(this).data('image');
        let email = $(this).data('id');
        $('#athleteImage').attr('src', '../../images/profile/' + image);
        $('#athleteEmailForImage').val(email);
        $('#imageModal3').modal('show');
    }
    
    $('#newAthleteImage').on('change', function (e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function (event) {
                $('#athleteImage').attr('src', event.target.result);
            }
            
            reader.readAsDataURL(file);
        }
    });

    
    // Handle update image button click
    $('#updatePhotoFormAthlete').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let fileInput = $('#newAthleteImage').get(0);

        if (fileInput.files.length === 0) {
            alert('No photo selected. Please choose an image before updating.');
            return;
        }

        $.ajax({
            url: '../buttonFunction/updateProfileAthleteButton.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                alert('Profile image updated successfully!');
                console.log(response); // Log success or error message
                $('#imageModal3').modal('hide');
                location.reload();
            }
        });
    });
    
    $('#filterAthlete').on('click', function () {
        $('#athleteFilterModal').modal('show');
    });

    // Request Sign Up part -----------------------------------------------------------------------------------------
    selectedReqEmail = [];
    

    function fetchRequest(sport, type) {
        $.ajax({
            url: '../onloadFunction/getRequest.php',
            type: 'GET',
            data: { sport: sport, type: type },
            dataType: 'json',
            success: function (data) {
                let tableBody = $('#requestSignUpBody');
                tableBody.empty(); // Clear existing table rows

                data.forEach(function (request) {
                    let isChecked = selectedReqEmail.includes(request.req_email) ? 'checked' : '';
                    let row = `<tr>
                        <td>
                            <input type="checkbox" class="select-request" title = "${request.req_lname}" data-type="${request.req_user_type}" data-id="${request.req_email}" data-name="${request.req_fname} ${request.req_lname}" data-sport="${request.req_sport}" ${isChecked}>
                        </td>
                        
                        <td class="editable"  data-field="req_fname" data-id="${request.req_email}">${request.req_fname}</td>
                        <td class="editable"  data-field="req_lname" data-id="${request.req_email}">${request.req_lname}</td>
                        <td class="editable-sport-request"  data-field="req_sport" data-id="${request.req_email}">${request.req_email}</td>
                        <td class="editable"  data-field="req_user_type" data-id="${request.req_email}">${request.req_user_type}</td>
                        <td class="editable"  data-field="req_email" data-id="${request.req_email}">${request.req_sport}</td>
                        <td class="editable" data-field="req_position" data-id="${request.req_email}">${request.req_position}</td>
                        
                    </tr>`;
                    tableBody.append(row);
                });

                // Bind events after updating the table
                $('.select-request').on('change', updateSelectedRequest);
                // $('.editable').on('dblclick', handleEditableDoubleClick);
                // $('.editable-sport-request').on('dblclick', handleEditableSportDoubleClick);
                
            },
            error: function (xhr, status, error) {
                console.error("Error fetching coaches data: ", error);
            }
        });
    }

    // Initial fetch for default view (show all coaches)
    

    // Handle changes in the sport dropdown
    $('#reqSportInput').on('change', function () {
        var selectedSport = $(this).val();
        var selectedUser = $('#userTypeInput').val().trim();
        fetchRequest(selectedSport, selectedUser);
    });

    $('#userTypeInput').on('change', function () {
        var selectedUser = $(this).val();
        var selectedSport = $('#reqSportInput').val();
        
        fetchRequest(selectedSport, selectedUser);
    });


    // Function to update the selected coaches list
    function updateSelectedRequest() {
        selectedReqEmail = []; // Clear the array before updating

        let selectedRequest = [];
        $('.select-request:checked').each(function () {
            let requestName = $(this).data('name');
            let requestType = $(this).data('type');
            selectedReqEmail.push($(this).data('id')); // Add the email to the array
            selectedRequest.push(`<span class="selected-request-item">${requestName} (${requestType}) <span class="remove-request" data-name="${requestName}" data-type="${requestType}">X</span></span>`);
        });

        $('#selectedRequest').html(selectedRequest.length > 0 ? selectedRequest.join(' ') : '');
    }

    $(document).on('click', '.remove-request', function () {
        let requestName = $(this).data('name');
        let requestType = $(this).data('type');
        $(`.select-request[data-name="${requestName}"][data-type="${requestType}"]`).prop('checked', false).trigger('change');

        console.log(requestName, requestType);
    });

    $('#requestSignUpButton').on('click', function () {
        $('#requestSignUpModal').modal('show');
        fetchRequest('--');
    });

    $('#acceptButton').on('click', function () {
        if (selectedReqEmail.length === 0) {
            alert("No requests selected.");
            return;
        }
    
        // Confirm the action
        if (!confirm("Are you sure you want to ACCEPT the selected requests?")) {
            return;
        }
    
        // Send selected requests to the server
        $.ajax({
            url: '../buttonFunction/acceptRequestButton.php',
            type: 'POST',
            data: { selectedEmails: selectedReqEmail },
            success: function (response) {
                alert("Selected requests accepted successfully.");
                // Optionally refresh the request list or clear the selected requests display
                fetchRequest($('#reqSportInput').val(), $('#userTypeInput').val());
                $('#selectedRequest').empty();
                fetchCoaches('--');
                fetchAthletes($('#athleteSportInput').val(), $('#searchAthlete').val().trim());
                updateRequestCount();
                
            },
            error: function (xhr, status, error) {
                console.error("Error accepting requests: ", error);
                alert("An error occurred while accepting the selected requests.");
            }
        });
    });

    $('#declineButton').on('click', function () {
        if (selectedReqEmail.length === 0) {
            alert("No requests selected.");
            return;
        }
    
        // Confirm the action
        if (!confirm("Are you sure you want to DECLINE the selected requests?")) {
            return;
        }
    
        // Send selected requests to the server
        $.ajax({
            url: '../buttonFunction/declineRequestButton.php',
            type: 'POST',
            data: { selectedEmails: selectedReqEmail },
            success: function (response) {
                alert("Selected requests DECLINED successfully.");
                // Optionally refresh the request list or clear the selected requests display
                fetchRequest($('#reqSportInput').val(), $('#userTypeInput').val());
                $('#selectedRequest').empty();
                updateRequestCount();
                
            },
            error: function (xhr, status, error) {
                console.error("Error accepting requests: ", error);
                alert("An error occurred while accepting the selected requests.");
            }
        });
    });

    function updateRequestCount() {
        $.ajax({
            url: '../onloadFunction/countRequest.php', // Update with the correct path
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#requestSignUpButton .badge').remove(); // Remove existing badge if any
                if (data.count > 0) {
                    $('#requestSignUpButton').append(`<span class="badge badge-light">${data.count}</span>`);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching request count: ", error);
            }
        });
    }
    
    
    updateRequestCount();

    // inactive user part ---------------------------------------------------------------------------------------------

    $('#inactiveUserButton').on('click', function () {
        $('#inactiveUserModal').modal('show');
        fetchInactive('--', 'COACH');
        document.getElementById('inactiveUserTypeInput').value = 'COACH';
    });

    selectedInactiveEmail = [];
    

    function fetchInactive(sport, type) {
        $.ajax({
            url: '../onloadFunction/getInactive.php',
            type: 'GET',
            data: { sport: sport, type: type },
            dataType: 'json',
            success: function (data) {

                if (type === "COACH"){
                    console.log("show COACH")
                    let tableBody = $('#inactiveUserBody');
                    tableBody.empty(); // Clear existing table rows

                    data.forEach(function (inactive) {
                        let isChecked = selectedInactiveEmail.includes(inactive.coach_email) ? 'checked' : '';
                        let row = `<tr>
                            <td>
                                <input type="checkbox" class="select-inactive" title = "${inactive.coach_lname}" data-type="${inactive.STATUS}" data-id="${inactive.coach_email}" data-name="${inactive.coach_fname} ${inactive.coach_lname}" data-sport="${inactive.coach_sport}" ${isChecked}>
                            </td>

                            <td class="editable"  data-field="id" data-id="${inactive.coach_email}">${inactive.id}</td>
                            <td class="editable"  data-field="coach_fname" data-id="${inactive.coach_email}">${inactive.coach_fname}</td>
                            <td class="editable"  data-field="coach_lname" data-id="${inactive.coach_email}">${inactive.coach_lname}</td>
                            <td class="editable-sport-inactive"  data-field="coach_sport" data-id="${inactive.coach_email}">${inactive.coach_email}</td>
                            <td class="editable"  data-field="coach_email" data-id="${inactive.coach_email}">${inactive.coach_sport}</td>
                            <td class="editable" data-field="coach_position" data-id="${inactive.coach_email}">${inactive.coach_position}</td>
                            <td class="editable" data-field="STATUS" data-id="${inactive.coach_email}">${inactive.STATUS}</td>
                            
                        </tr>`;
                        tableBody.append(row);
                    });
                } else if (type == "ATHLETE"){
                    console.log("show Athlete")
                    let tableBody = $('#inactiveUserBody');
                    tableBody.empty(); // Clear existing table rows

                    data.forEach(function (inactive) {
                        let isChecked = selectedInactiveEmail.includes(inactive.ath_email) ? 'checked' : '';
                        let row = `<tr>
                            <td>
                                <input type="checkbox" class="select-inactive" title = "${inactive.ath_last}" data-type="${inactive.STATUS}" data-id="${inactive.ath_email}" data-name="${inactive.ath_first} ${inactive.ath_last}" data-sport="${inactive.ath_sport}" ${isChecked}>
                            </td>
                            <td class="editable"  data-field="ath_num" data-id="${inactive.ath_email}">${inactive.ath_num}</td>
                            <td class="editable"  data-field="ath_first" data-id="${inactive.ath_email}">${inactive.ath_first}</td>
                            <td class="editable"  data-field="ath_last" data-id="${inactive.ath_email}">${inactive.ath_last}</td>
                            <td class="editable-sport-inactive"  data-field="ath_sport" data-id="${inactive.ath_email}">${inactive.ath_email}</td>
                            <td class="editable"  data-field="ath_email" data-id="${inactive.ath_email}">${inactive.ath_sport}</td>
                            <td class="editable" data-field="ath_position" data-id="${inactive.ath_email}">${inactive.ath_position}</td>
                            <td class="editable" data-field="STATUS" data-id="${inactive.ath_email}">${inactive.STATUS}</td>
                            
                        </tr>`;
                        tableBody.append(row);
                    });
                }

                

                // Bind events after updating the table
                $('.select-inactive').on('change', updateSelectedInactive);
                // $('.editable').on('dblclick', handleEditableDoubleClick);
                // $('.editable-sport-inactive').on('dblclick', handleEditableSportDoubleClick);
                
            },
            error: function (xhr, status, error) {
                console.error("Error fetching coaches data: ", error);
            }
        });
    }

    // Initial fetch for default view (show all coaches)
    

    // Handle changes in the sport dropdown
    $('#inactiveUserSportInput').on('change', function () {
        var selectedSport = $(this).val();
        var selectedUser = $('#inactiveUserTypeInput').val().trim();
        fetchInactive(selectedSport, selectedUser);
    });

    $('#inactiveUserTypeInput').on('change', function () {
        var selectedUser = $(this).val();
        var selectedSport = $('#inactiveUserSportInput').val();
        
        fetchInactive(selectedSport, selectedUser);
    });


    // Function to update the selected coaches list
    function updateSelectedInactive() {
        selectedInactiveEmail = []; // Clear the array before updating

        let selectedInactive = [];
        $('.select-inactive:checked').each(function () {
            let inactiveName = $(this).data('name');
            let inactiveType = $(this).data('type');
            selectedInactiveEmail.push($(this).data('id')); // Add the email to the array
            selectedInactive.push(`<span class="selected-inactive-item">${inactiveName} (${inactiveType}) <span class="remove-inactive" data-name="${inactiveName}" data-type="${inactiveType}">X</span></span>`);
        });

        $('#selectedInactive').html(selectedInactive.length > 0 ? selectedInactive.join(' ') : '');
    }

    $(document).on('click', '.remove-inactive', function () {
        let inactiveName = $(this).data('name');
        let inactiveType = $(this).data('type');
        $(`.select-inactive[data-name="${inactiveName}"][data-type="${inactiveType}"]`).prop('checked', false).trigger('change');

        console.log(inactiveName, inactiveType);
    });

    function activeButtonFunction() {
        if (selectedInactiveEmail.length === 0) {
            alert("No requests selected.");
            return;
        }
    
        let type = $('#inactiveUserTypeInput').val();
    
        // Confirm the action
        if (!confirm("Are you sure you want to ACTIVATE the selected requests?")) {
            return;
        }
    
        // Send selected requests to the server
        $.ajax({
            url: '../buttonFunction/activateUserButton.php',
            type: 'POST',
            data: { selectedEmails: selectedInactiveEmail, type: type },
            success: function (response) {
                let result = JSON.parse(response);
                if (result.success) {
                    alert("Selected Users Activated successfully.");
                    // Refresh the data or update the UI
                    fetchInactive($('#inactiveUserSportInput').val(), $('#inactiveUserTypeInput').val());
                    $('#selectedInactive').empty();
                    fetchCoaches('--');
                    fetchAthletes($('#athleteSportInput').val(), $('#searchAthlete').val().trim());
                } else {
                    alert("An error occurred: " + result.error);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error accepting requests: ", error);
                alert("An error occurred while accepting the selected requests.");
            }
        });
    }
    
    $('#activeButton').on('click', function () {
        activeButtonFunction();
    });
    



});




