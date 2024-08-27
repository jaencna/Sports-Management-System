


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
                        <div class="card mb-3 match-card" data-match-id="${match.bball_match_id}" data->
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

    $('#showFetchHere').on('click', '.match-card', function() {
        selectedMatchIds = []; // Empty the array
        let matchId = $(this).data('match-id'); // Get the bball_match_id from the card's data attribute
        selectedMatchIds.push(matchId); // Store the clicked match ID
        getQuarterSelect();
        getMatchTotal();
        
       
    });



function getMatchTotal(){
    let matchId = selectedMatchIds[0];

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
}

function getQuarterSelect() {
    let quarterSelect = $('#quarterSelect').val(); // Get selected quarter value
    let matchId = selectedMatchIds[0];
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
                             <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_foul" max="6" type="number" value="${detail.ath_foul}" /></td>
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
                             <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_foul" max="6" type="number" value="${detail.ath_foul}" /></td>
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
}


    
    
    

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
                                 <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_foul" max="6" type="number" value="${detail.ath_foul}" /></td>
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
                                <td><input class = "field" data-id="${detail.ath_num}" data-team="${detail.ath_team}" data-column="ath_foul" max="6" type="number" value="${detail.ath_foul}" /></td>
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

    function updateResults(team1Data, team2Data) {
        let team1Score = team1Data.total_pts;
        let team2Score = team2Data.total_pts;
        let matchId = selectedMatchIds[0];
    
        $.ajax({
            url: '../buttonFunction/updateResultMatch.php',
            type: 'POST',
            data: {
                matchId: matchId,
                team1Score: team1Score,
                team2Score: team2Score
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    console.log('Scores updated successfully.');
                } else {
                    console.error('Error updating scores: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
            }
        });
    }
    
    
    function updateBballGameTotal(team1Data, team2Data) {
        let matchId = selectedMatchIds[0];
    
        let team1DataToUpdate = {
            game_team: 'TEAM 1',
            game_pts: team1Data.total_pts,
            game_2fgm: team1Data.total_2fgm,
            game_2pts: team1Data.total_2pts,
            game_3fgm: team1Data.total_3fgm,
            game_3pts: team1Data.total_3pts,
            game_ftm: team1Data.total_ftm,
            game_ftpts: team1Data.total_ftpts,
            game_2fga: team1Data.total_2fga,
            game_3fga: team1Data.total_3fga,
            game_fta: team1Data.total_fta,
            game_ass: team1Data.total_ass,
            game_block: team1Data.total_block,
            game_steal: team1Data.total_steal,
            game_ofreb: team1Data.total_ofreb,
            game_defreb: team1Data.total_defreb,
            game_turn: team1Data.total_turn,
            game_foul: team1Data.total_foul
        };
    
        let team2DataToUpdate = {
            game_team: 'TEAM 2',
            game_pts: team2Data.total_pts,
            game_2fgm: team2Data.total_2fgm,
            game_2pts: team2Data.total_2pts,
            game_3fgm: team2Data.total_3fgm,
            game_3pts: team2Data.total_3pts,
            game_ftm: team2Data.total_ftm,
            game_ftpts: team2Data.total_ftpts,
            game_2fga: team2Data.total_2fga,
            game_3fga: team2Data.total_3fga,
            game_fta: team2Data.total_fta,
            game_ass: team2Data.total_ass,
            game_block: team2Data.total_block,
            game_steal: team2Data.total_steal,
            game_ofreb: team2Data.total_ofreb,
            game_defreb: team2Data.total_defreb,
            game_turn: team2Data.total_turn,
            game_foul: team2Data.total_foul
        };
    
        // Update TEAM 1
        $.ajax({
            url: '../buttonFunction/updateTotalGame.php',
            type: 'POST',
            data: {
                matchId: matchId,
                teamData: team1DataToUpdate
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    console.log('TEAM 1 data updated successfully.');
                    
                    
    
                } else {
                    console.error('Error updating TEAM 1 data: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed for TEAM 1:', error);
            }
        });
    
        // Update TEAM 2
        $.ajax({
            url: '../buttonFunction/updateTotalGame.php',
            type: 'POST',
            data: {
                matchId: matchId,
                teamData: team2DataToUpdate
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    console.log('TEAM 2 data updated successfully.');
                    
                } else {
                    console.error('Error updating TEAM 2 data: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed for TEAM 2:', error);
            }
        });
    }
    
        // Example usage: fetch aggregated data for a match with ID 1
   
        function fetchAggregatedData() {
            let matchId = selectedMatchIds[0]; // Get the stored match ID
            $.ajax({
                url: '../onloadFunction/getTotalOfGame.php',
                type: 'POST',
                data: { matchId: matchId },
                dataType: 'json',
                success: function(response) {
                    try {
                        if (response.status === 'success') {
                            let team1Data = response.data.team1;
                            let team2Data = response.data.team2;
        
                            // Pass the data to the processTeamData function
                            
                            updateBballGameTotal(team1Data, team2Data);
                            updateResults(team1Data, team2Data);
                        } else {
                            console.error('Error fetching aggregated data: ' + response.message);
                        }
                    } catch (e) {
                        console.error('Error processing JSON response:', e);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', error);
                }
            });
        }

        function fetchGameTrackingData(athTeam) {
            let matchId = selectedMatchIds[0];
            
            $.ajax({
                url: '../onloadFunction/getTotalQuarter.php', // PHP script URL
                type: 'POST',
                data: {
                    matchId: matchId // Pass the match ID
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Pass the data to another function for processing
                        updateGameQuarter(response.data, athTeam);
                    } else {
                        console.error('Error fetching data: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', error);
                }
            });
        }

        function updateGameQuarter(data, athTeam) {
            let quarter = $('#quarterSelect').val();
            const team1Data = data.team1;
            const team2Data = data.team2;
    
            // Example: Logging the data per team and quarter
            console.log('Processing Game Data...');
            console.log('Team 1 Data by Quarter:', team1Data);
            console.log('Team 2 Data by Quarter:', team2Data);
        
            // Example: Accessing specific quarter data
            console.log('Team 1, Quarter 1 Total Points:', team1Data.Quarter1.total_pts);
            console.log('Team 2, Quarter 1 Total Points:', team2Data.Quarter1.total_pts);


        
            // Further processing or display logic can be added here
            // For example, updating the UI with the data
        }
        
        


    $(document).on('focusout', 'input[type="number"]', function () {
        
            let foul = $('#field-foul').val();
            let inputValue = $(this).val();
            let column = $(this).data('column');
            let athNum = $(this).data('id');
            let athTeam = $(this).data('team');
            let matchId = selectedMatchIds[0];
            let quarter = $('#quarterSelect').val();

    
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
            if (foul > 6) {
                alert("Fouls Max is 6")
            } else {
                console.log("safe");
            // Send AJAX request to update athlete stats
            $.ajax({
                url: '../buttonFunction/updateAthleteStats.php', // Path to your PHP file
                type: 'POST',
                data: data,
                success: function(response) {
                    console.log('Athlete stats updated successfully');
                    
                        fetchAggregatedData();
                        fetchGameTrackingData(athTeam);
                    

    // Set a timeout for updateBballGameTotal to ensure it runs after fetchAggregatedData completes
                    setTimeout(function() {

                        // Set another timeout for getMatchTotal to run after updateBballGameTotal
                        setTimeout(function() {
                            getMatchTotal();

                            // Final timeout to getQuarterSelect after getMatchTotal
                            setTimeout(function() {
                                getQuarterSelect();

                                fetchMatchData();
                            }, 300); // Adjust this delay as needed

                        }, 300); // Adjust this delay as needed

                    }, 300); // Adjust this delay as needed
                    
                },
                error: function(xhr, status, error) {
                    console.error('Error updating athlete stats:', error);
                }
            });
        }
    });


    function getFormattedDateTime() {
        let now = new Date();
        
        let year = now.getFullYear();
        let month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        let day = String(now.getDate()).padStart(2, '0');
        let hours = String(now.getHours()).padStart(2, '0');
        let minutes = String(now.getMinutes()).padStart(2, '0');
        let seconds = String(now.getSeconds()).padStart(2, '0');
        
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }
    
    function finalizedResult(data) {
        // If data is an object (assuming the PHP returns a single result)
        if (data.team_1_score !== undefined && data.team_2_score !== undefined) {
            let matchId = selectedMatchIds[0];
            let team1Score = data.team_1_score;
            let team2Score = data.team_2_score;
            let dateTime = getFormattedDateTime();
            
            console.log(team1Score, team2Score, dateTime);
    
            $.ajax({
                url: '../buttonFunction/finalizedButton.php', // Path to your PHP file
                type: 'POST',
                data: {
                    team1Score: team1Score,
                    team2Score: team2Score,
                    dateTime: dateTime,
                    matchId: matchId // Pass matchId to the PHP script
                },
                success: function(response) {
                    // Handle the success response
                    console.log('Success:', response);
                    alert(response);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error updating match results:', error);
                }
            });
        } else {
            console.error('Unexpected data format:', data);
        }
    }
    
    
    
    function fetchMatchResult() {
        let matchId = selectedMatchIds[0];
        $.ajax({
            url: '../onloadFunction/getResult.php',
            type: 'POST',
            data: { matchId: matchId },
            success: function(response) {
                // Parse the response if it's a JSON string
                let data = JSON.parse(response);
                finalizedResult(data);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching match data:', error);
            }
        });
    }
    
    $('#finalizeMatchButton').on('click', function () {
        fetchMatchResult();
    });
    
});
