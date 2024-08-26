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
                                
                                <td><input type="number" value="${detail.ath_2fgm}" /></td>
                                <td><input type="number" value="${detail.ath_3fgm}" /></td>
                                <td><input type="number" value="${detail.ath_ftm}" /></td>
                                <td><input type="number" value="${detail.ath_2fga}" /></td>
                                <td><input type="number" value="${detail.ath_3fga}" /></td>
                                <td><input type="number" value="${detail.ath_fta}" /></td>
                                <td><input type="number" value="${detail.ath_ass}" /></td>
                                <td><input type="number" value="${detail.ath_block}" /></td>
                                <td><input type="number" value="${detail.ath_steal}" /></td>
                                <td><input type="number" value="${detail.ath_ofreb}" /></td>
                                <td><input type="number" value="${detail.ath_defreb}" /></td>
                                <td><input type="number" value="${detail.ath_turn}" /></td>
                                <td><input type="number" value="${detail.ath_foul}" /></td>
                            </tr>
                        `;
                    });

                    result.team2QuarterData.forEach(detail => {
                        team2QuarterHtml += `
                            <tr>
                                <td>${detail.ath_first} ${detail.ath_last}</td>
                               
                                <td><input type="number" value="${detail.ath_2fgm}" /></td>
                                <td><input type="number" value="${detail.ath_3fgm}" /></td>
                                <td><input type="number" value="${detail.ath_ftm}" /></td>
                                <td><input type="number" value="${detail.ath_2fga}" /></td>
                                <td><input type="number" value="${detail.ath_3fga}" /></td>
                                <td><input type="number" value="${detail.ath_fta}" /></td>
                                <td><input type="number" value="${detail.ath_ass}" /></td>
                                <td><input type="number" value="${detail.ath_block}" /></td>
                                <td><input type="number" value="${detail.ath_steal}" /></td>
                                <td><input type="number" value="${detail.ath_ofreb}" /></td>
                                <td><input type="number" value="${detail.ath_defreb}" /></td>
                                <td><input type="number" value="${detail.ath_turn}" /></td>
                                <td><input type="number" value="${detail.ath_foul}" /></td>
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
                               
                                <td><input type="number" value="${detail.ath_2fgm}" /></td>
                                <td><input type="number" value="${detail.ath_3fgm}" /></td>
                                <td><input type="number" value="${detail.ath_ftm}" /></td>
                                <td><input type="number" value="${detail.ath_2fga}" /></td>
                                <td><input type="number" value="${detail.ath_3fga}" /></td>
                                <td><input type="number" value="${detail.ath_fta}" /></td>
                                <td><input type="number" value="${detail.ath_ass}" /></td>
                                <td><input type="number" value="${detail.ath_block}" /></td>
                                <td><input type="number" value="${detail.ath_steal}" /></td>
                                <td><input type="number" value="${detail.ath_ofreb}" /></td>
                                <td><input type="number" value="${detail.ath_defreb}" /></td>
                                <td><input type="number" value="${detail.ath_turn}" /></td>
                                <td><input type="number" value="${detail.ath_foul}" /></td>
                            </tr>
                        `;
                    });

                    result.team2QuarterData.forEach(detail => {
                        team2QuarterHtml += `
                            <tr>
                                <td>${detail.ath_first} ${detail.ath_last}</td>
                              
                                <td><input type="number" value="${detail.ath_2fgm}" /></td>
                                <td><input type="number" value="${detail.ath_3fgm}" /></td>
                                <td><input type="number" value="${detail.ath_ftm}" /></td>
                                <td><input type="number" value="${detail.ath_2fga}" /></td>
                                <td><input type="number" value="${detail.ath_3fga}" /></td>
                                <td><input type="number" value="${detail.ath_fta}" /></td>
                                <td><input type="number" value="${detail.ath_ass}" /></td>
                                <td><input type="number" value="${detail.ath_block}" /></td>
                                <td><input type="number" value="${detail.ath_steal}" /></td>
                                <td><input type="number" value="${detail.ath_ofreb}" /></td>
                                <td><input type="number" value="${detail.ath_defreb}" /></td>
                                <td><input type="number" value="${detail.ath_turn}" /></td>
                                <td><input type="number" value="${detail.ath_foul}" /></td>
                            </tr>
                        `;
                    });

                    // Update table bodies with rows
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
});
