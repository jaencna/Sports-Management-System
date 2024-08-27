

$(document).ready(function() {

    function fetchMatchData() {
        $.ajax({
            url: '../onloadFunction/getMatchesResult.php', // Path to your PHP file
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                


                let cardsHtml = '';
                response.forEach(match => {
                    let team1 = match.team_1_score;
                    let team2 = match.team_2_score;
        
                    console.log(team1, team2)
                    let teamWin;
                    let teamLose;

                    if (team1 > team2){
                        teamWin = team1;
                        teamLose = team2;
                    } else if(team2 > team1){
                        teamWin = team2;
                        teamLose = team1;
                    }

                    cardsHtml += `
                        <div class="card mb-3 match-card" data-match-id="${match.bball_match_id}" data->
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="team-info text-center">
                                    <h5 class="team-name">${match.team_1}</h5>
                                    <p class="team-score">${teamWin}</p>
                                    <h5 class="team-name">WIN</h5>
                                    
                                </div>
                                <div class="match-info text-center">
                                    <h5 class="match-name">${match.match_name}</h5>
                                    <p class="vs">VS</p>
                                    <p class="team-score">${match.match_date_time}</p>
                                </div>
                                <div class="team-info text-center">
                                    <h5 class="team-name">${match.team_2}</h5>
                                    <p class="team-score">${teamLose}</p>
                                    <h5 class="team-name">LOSE</h5>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#resultOutside').html(cardsHtml);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching match data:', error);
            }
        });
    }

    // Fetch match data when the page is ready
    fetchMatchData();

});
