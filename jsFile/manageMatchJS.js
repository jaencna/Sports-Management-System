$(document).ready(function() {
    let selectedMatchIds = [];

    // Function to fetch match data and display it as cards
    function fetchMatchData() {
        $.ajax({
            url: '../onloadFunction/getMatchResult.php', // Path to your PHP file
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let cardsHtml = '';
                response.forEach(match => {
                    cardsHtml += `
                        <div class="card mb-3 match-card" data-match-id="${match.bball_match_id}">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="team-info text-center">
                                    <h5 class="team-name">${match.team_1}</h5>
                                    <p class="team-score">${match.team_1_score}</p>
                                </div>
                                <div class="match-info text-center">
                                    <h5 class="match-name">${match.match_name}</h5>
                                    <p class="vs">VS</p>
                                </div>
                                <div class="team-info text-center">
                                    <h5 class="team-name">${match.team_2}</h5>
                                    <p class="team-score">${match.team_2_score}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#showFetchHere').html(cardsHtml);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching match data:', error);
            }
        });
    }

    // Fetch match data when the page is ready
    fetchMatchData();

    // Handle click event for match cards
    $('#showFetchHere').on('click', '.match-card', function() {
        selectedMatchIds = []; // Empty the array
        let matchId = $(this).data('match-id'); // Get the bball_match_id from the card's data attribute
        selectedMatchIds.push(matchId); // Store the clicked match ID

        // Fetch match details
        $.ajax({
            url: '../onloadFunction/getMatchTotal.php',
            type: 'POST',
            data: { matchId: matchId },
            success: function(response) {
                let result = JSON.parse(response);
                if (result.status === 'success') {
                    let team1Html = '';
                    let team2Html = '';

                    result.team1Data.forEach(detail => {
                        team1Html += `
                            <div class="col-md-6">
                                <h3>${detail.game_team}</h3>
                                <p>2FGM: ${detail.game_2fgm}, 2PTS: ${detail.game_2pts}</p>
                                <p>3FGM: ${detail.game_3fgm}, 3PTS: ${detail.game_3pts}</p>
                                <p>FTM: ${detail.game_ftm}, FTPTS: ${detail.game_ftpts}</p>
                                <p>2FGA: ${detail.game_2fga}, 3FGA: ${detail.game_3fga}, FTA: ${detail.game_fta}</p>
                                <p>Assists: ${detail.game_ass}</p>
                                <p>Blocks: ${detail.game_block}</p>
                                <p>Steals: ${detail.game_steal}</p>
                                <p>Offensive Rebounds: ${detail.game_ofreb}</p>
                                <p>Defensive Rebounds: ${detail.game_defreb}</p>
                                <p>Turnovers: ${detail.game_turn}</p>
                                <p>Fouls: ${detail.game_foul}</p>
                            </div>
                        `;
                    });

                    result.team2Data.forEach(detail => {
                        team2Html += `
                            <div class="col-md-6">
                                <h3>${detail.game_team}</h3>
                                <p>2FGM: ${detail.game_2fgm}, 2PTS: ${detail.game_2pts}</p>
                                <p>3FGM: ${detail.game_3fgm}, 3PTS: ${detail.game_3pts}</p>
                                <p>FTM: ${detail.game_ftm}, FTPTS: ${detail.game_ftpts}</p>
                                <p>2FGA: ${detail.game_2fga}, 3FGA: ${detail.game_3fga}, FTA: ${detail.game_fta}</p>
                                <p>Assists: ${detail.game_ass}</p>
                                <p>Blocks: ${detail.game_block}</p>
                                <p>Steals: ${detail.game_steal}</p>
                                <p>Offensive Rebounds: ${detail.game_ofreb}</p>
                                <p>Defensive Rebounds: ${detail.game_defreb}</p>
                                <p>Turnovers: ${detail.game_turn}</p>
                                <p>Fouls: ${detail.game_foul}</p>
                            </div>
                        `;
                    });

                    $('#showResultHereTeam1').html(team1Html);
                    $('#showResultHereTeam2').html(team2Html);
                } else {
                    alert('Error fetching match details: ' + result.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching match details: ", error);
            }
        });

        // Fetch quarter data
        let quarterSelect = $('#quarterSelect').val(); // Get selected quarter value
        $.ajax({
            url: '../onloadFunction/getQuarterData.php',
            type: 'POST',
            data: { matchId: matchId, quarter: quarterSelect },
            success: function(response) {
                let result = JSON.parse(response);
                if (result.status === 'success') {
                    let team1QuarterHtml = '';
                    let team2QuarterHtml = '';

                    result.team1QuarterData.forEach(detail => {
                        team1QuarterHtml += `
                            <tr>
                                <td>${detail.ath_first} ${detail.ath_last}</td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_2fgm" type="number" value="${detail.ath_2fgm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_3fgm" type="number" value="${detail.ath_3fgm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ftm" type="number" value="${detail.ath_ftm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_2fga" type="number" value="${detail.ath_2fga}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_3fga" type="number" value="${detail.ath_3fga}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_fta" type="number" value="${detail.ath_fta}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ass" type="number" value="${detail.ath_ass}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_block" type="number" value="${detail.ath_block}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_steal" type="number" value="${detail.ath_steal}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ofreb" type="number" value="${detail.ath_ofreb}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_defreb" type="number" value="${detail.ath_defreb}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_turn" type="number" value="${detail.ath_turn}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_foul" type="number" value="${detail.ath_foul}" /></td>
                            </tr>
                        `;
                    });

                    result.team2QuarterData.forEach(detail => {
                        team2QuarterHtml += `
                            <tr>
                                <td>${detail.ath_first} ${detail.ath_last}</td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_2fgm" type="number" value="${detail.ath_2fgm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_3fgm" type="number" value="${detail.ath_3fgm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ftm" type="number" value="${detail.ath_ftm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_2fga" type="number" value="${detail.ath_2fga}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_3fga" type="number" value="${detail.ath_3fga}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_fta" type="number" value="${detail.ath_fta}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ass" type="number" value="${detail.ath_ass}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_block" type="number" value="${detail.ath_block}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_steal" type="number" value="${detail.ath_steal}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ofreb" type="number" value="${detail.ath_ofreb}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_defreb" type="number" value="${detail.ath_defreb}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_turn" type="number" value="${detail.ath_turn}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_foul" type="number" value="${detail.ath_foul}" /></td>
                            </tr>
                        `;
                    });

                    $('#team1QuarterData').html(team1QuarterHtml);
                    $('#team2QuarterData').html(team2QuarterHtml);
                } else {
                    alert('Error fetching quarter data: ' + result.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching quarter data: ", error);
            }
        });
    });

    // Handle quarter change event
    $('#quarterSelect').on('change', function() {
        let matchId = selectedMatchIds[0]; // Get the stored match ID
        let quarterSelect = $(this).val(); // Get the selected quarter value
        $.ajax({
            url: '../onloadFunction/getQuarterData.php',
            type: 'POST',
            data: { matchId: matchId, quarter: quarterSelect },
            success: function(response) {
                let result = JSON.parse(response);
                if (result.status === 'success') {
                    let team1QuarterHtml = '';
                    let team2QuarterHtml = '';

                    result.team1QuarterData.forEach(detail => {
                        team1QuarterHtml += `
                            <tr>
                                <td>${detail.ath_first} ${detail.ath_last}</td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_2fgm" type="number" value="${detail.ath_2fgm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_3fgm" type="number" value="${detail.ath_3fgm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ftm" type="number" value="${detail.ath_ftm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_2fga" type="number" value="${detail.ath_2fga}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_3fga" type="number" value="${detail.ath_3fga}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_fta" type="number" value="${detail.ath_fta}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ass" type="number" value="${detail.ath_ass}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_block" type="number" value="${detail.ath_block}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_steal" type="number" value="${detail.ath_steal}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ofreb" type="number" value="${detail.ath_ofreb}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_defreb" type="number" value="${detail.ath_defreb}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_turn" type="number" value="${detail.ath_turn}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_foul" type="number" value="${detail.ath_foul}" /></td>
                            </tr>
                        `;
                    });

                    result.team2QuarterData.forEach(detail => {
                        team2QuarterHtml += `
                            <tr>
                                <td>${detail.ath_first} ${detail.ath_last}</td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_2fgm" type="number" value="${detail.ath_2fgm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_3fgm" type="number" value="${detail.ath_3fgm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ftm" type="number" value="${detail.ath_ftm}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_2fga" type="number" value="${detail.ath_2fga}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_3fga" type="number" value="${detail.ath_3fga}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_fta" type="number" value="${detail.ath_fta}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ass" type="number" value="${detail.ath_ass}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_block" type="number" value="${detail.ath_block}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_steal" type="number" value="${detail.ath_steal}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_ofreb" type="number" value="${detail.ath_ofreb}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_defreb" type="number" value="${detail.ath_defreb}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_turn" type="number" value="${detail.ath_turn}" /></td>
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_foul" type="number" value="${detail.ath_foul}" /></td>
                            </tr>
                        `;
                    });

                    $('#team1QuarterData').html(team1QuarterHtml);
                    $('#team2QuarterData').html(team2QuarterHtml);
                } else {
                    alert('Error fetching quarter data: ' + result.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching quarter data: ", error);
            }
        });
    });

    // Handle input change and focus out event

    function updateAthleteStats() {
        // Loop through each input field with class 'field'
        $('input.field').each(function() {
            // Get the input value and other relevant attributes
            
        });
    }
    


    $(document).on('focusout', 'input[type="number"]', function () {
            let inputValue = $(this).val();
            let column = $(this).data('column');
            let athNum = $(this).data('id');
            let athTeam = $(this).data('team');
            let matchId = selectedMatchIds[0];
            let quarter = $('#quarterSelect').val();

            console.log(athNum, athTeam, matchId, quarter);
    
            // Calculate updated values based on column modified
            let ath2fgm = parseFloat($('input[data-column="ath_2fgm"]').val()) || 0;
            let ath3fgm = parseFloat($('input[data-column="ath_3fgm"]').val()) || 0;
            let ath_ftm = parseFloat($('input[data-column="ath_ftm"]').val()) || 0;
    
            let ath2pts = ath2fgm * 2;
            let ath3pts = ath3fgm * 3;
            let ath_ftpts = ath_ftm;
    
            let ath_pts = ath2pts + ath3pts + ath_ftpts;
    
            // Prepare data for AJAX request
            let data = {
                match_id: matchId,
                match_quarter: quarter,
                ath_num: athNum,
                ath_pts: ath_pts,
                ath_team: athTeam,
                ath_2fgm: ath2fgm,
                ath_2pts: ath2pts,
                ath_3fgm: ath3fgm,
                ath_3pts: ath3pts,
                ath_ftm: ath_ftm,
                ath_ftpts: ath_ftpts,
                ath_2fga: parseFloat($('input[data-column="ath_2fga"]').val()) || 0,
                ath_3fga: parseFloat($('input[data-column="ath_3fga"]').val()) || 0,
                ath_fta: parseFloat($('input[data-column="ath_fta"]').val()) || 0,
                ath_ass: parseFloat($('input[data-column="ath_ass"]').val()) || 0,
                ath_block: parseFloat($('input[data-column="ath_block"]').val()) || 0,
                ath_steal: parseFloat($('input[data-column="ath_steal"]').val()) || 0,
                ath_ofreb: parseFloat($('input[data-column="ath_ofreb"]').val()) || 0,
                ath_defreb: parseFloat($('input[data-column="ath_defreb"]').val()) || 0,
                ath_turn: parseFloat($('input[data-column="ath_turn"]').val()) || 0,
                ath_foul: parseFloat($('input[data-column="ath_foul"]').val()) || 0
            };
    
            // Send AJAX request to update athlete stats
            $.ajax({
                url: '../buttonFunction/updateAthleteStats.php', // Path to your PHP file
                type: 'POST',
                data: data,
                success: function(response) {
                    console.log('Athlete stats updated successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Error updating athlete stats:', error);
                }
            });
    });
});
