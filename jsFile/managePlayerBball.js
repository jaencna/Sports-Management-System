$(document).ready(function () {
    // Function to fetch and display athlete data
    function fetchAthletes(position) {
        $.ajax({
            url: '../onloadFunction/getAthletesCoach.php',
            type: 'GET',
            data: { position: position },
            dataType: 'json',
            success: function (data) {
                let cardContainer = $('#athleteCardContainer');
                cardContainer.empty(); // Clear existing cards

                let row = $('<div class="row"></div>'); // Create a row for the cards

                data.forEach(function (athlete) {
                    let imagePath = `../../images/profile/${athlete.ath_img}`;
                    
                    let card = `
                        <div class="col-sm-6 col-md-4 col-lg-2 mb-2 athlete-card">
                            <div class="card custom-card">
                                <img src="${imagePath}" class="card-img-top" alt="${athlete.ath_first} ${athlete.ath_last}">
                                <div class="card-body p-2">
                                    <h6 class="card-title editable" data-field="ath_first" data-id="${athlete.ath_id}">${athlete.ath_first}</h6>
                                    <h6 class="card-title editable" data-field="ath_last" data-id="${athlete.ath_id}">${athlete.ath_last}</h6>
                                    <p class="card-text editable" data-field="ath_position" data-id="${athlete.ath_id}">${athlete.ath_position}</p>
                                    <div class="athlete-info">
                                        <p class="card-text editable" data-field="ath_height" data-id="${athlete.ath_id}"><strong>${athlete.ath_height} CM</strong></p>
                                        <p class="card-text editable" data-field="ath_weight" data-id="${athlete.ath_id}"><strong>${athlete.ath_weight} KG</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                    row.append(card); // Append each card to the row
                });

                cardContainer.append(row); // Append the row to the container

                // Make the text fields editable on double click
                enableEditing();
            },
            error: function (xhr, status, error) {
                console.error("Error fetching athletes data: ", error);
            }
        });
    }

    // Enable editing on double-click
    function enableEditing() {
        $('.editable').dblclick(function () {
            let originalElement = $(this);
            let originalValue = originalElement.text();
            let fieldName = originalElement.data('field');
            let athleteId = originalElement.data('id');
            console.log(originalElement);
            let input = $('<input type="text" class="form-control" />');
            input.val(originalValue);
            originalElement.replaceWith(input);

            input.focus();
            console.log(athleteId);

            input.on('blur keydown', function (e) {
                if (e.type === 'blur' || e.key === 'Enter') {
                    let newValue = input.val().trim();

                    if (newValue && newValue !== originalValue) {
                        updateAthleteField(athleteId, fieldName, newValue, function () {
                            input.replaceWith(originalElement);
                            originalElement.text(newValue);
                            enableEditing(); // Re-enable editing on the new element
                        });
                    } else {
                        input.replaceWith(originalElement);
                        originalElement.text(originalValue); // Revert to original value if no change
                        enableEditing(); // Re-enable editing on the original element
                    }
                }
            });
        });
    }

    // Function to update athlete's field in the database
    function updateAthleteField(id, field, value, callback) {
        $.ajax({
            url: '../onloadFunction/updateAthleteField.php',
            type: 'POST',
            data: {
                id: id,
                field: field,
                value: value
            },
            success: function (response) {
                console.log("Update response: ", response);
                console.log(id);
                callback();
            },
            error: function (xhr, status, error) {
                console.error("Error updating athlete field: ", error);
            }
        });
    }

    // Fetch and display athletes on page load
    fetchAthletes($('#positionSelect').val());

    $('#positionSelect').on('change', function () {
        var selectedPosition = $(this).val();
        fetchAthletes(selectedPosition);
    });
});
