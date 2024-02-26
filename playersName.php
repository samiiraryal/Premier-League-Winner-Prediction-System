<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "royacomn_football_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get player rating
function getPlayerRating($PlayerID, $conn)
{
    $playerRating = 0;
    $sql = "SELECT Rating FROM playerratings WHERE PlayerID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $PlayerID);
        $stmt->execute();
        $stmt->bind_result($playerRating);
        $stmt->fetch();
        $stmt->close();
    }
    return $playerRating;
}

// Get the selected teams from URL parameters
$homeTeam = $_GET['home'] ?? '';
$awayTeam = $_GET['away'] ?? '';
$homeTeam = trim($homeTeam);

$awayTeam = trim($awayTeam);




$sql = "SELECT players.PlayerID, players.PlayerName, teams.TeamName, teams.logo FROM players INNER JOIN teams ON players.TeamID = teams.TeamID";

// Filter the players based on selected teams
if (!empty($homeTeam) && !empty($awayTeam)) {
    $sql .= " WHERE teams.TeamName = '$homeTeam' OR teams.TeamName = '$awayTeam'";
} else {
    // Handle case when no teams are selected
    echo "Please select home and away teams.";
    exit;
}

$result = $conn->query($sql);

$teams = [];
while ($row = $result->fetch_assoc()) {
    $teams[$row['TeamName']][] = [
        'PlayerID' => $row['PlayerID'],
        'PlayerName' => $row['PlayerName'],
        'logo' => $row['logo']
    ];
}
// var_dump($teams);

$playerRatings = [];
foreach ($teams as $teamName => $players) {
    foreach ($players as $playerInfo) {
        $playerRatings[$playerInfo['PlayerID']] = [
            'name' => $playerInfo['PlayerName'],
            'rating' => getPlayerRating($playerInfo['PlayerID'], $conn),
            'team_logo' => $playerInfo['logo']
        ];


    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Selected Players</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&display=swap" rel="stylesheet">
   <!--  <script type="text/javascript" src="hideoverall.js"></script> -->
    <style>
       body {
            font-family: 'Oswald', sans-serif;
            background-color: #f4f4f4;
            color: white;
            padding: 20px;
            background-image: url('./stadium.jpg');
            background-repeat: no-repeat;
            background-size:cover;
            background-color: rgba(0, 0, 0, 0.7);
        }

        h3 {
            color: white;
            border-bottom: none;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center; /* Center team name and logo vertically */
        }

        .team-name {
            display: flex;
            align-items: center; /* Center team name and logo vertically */
            margin: 0 auto;
        }

        .team-logo {
            max-width: 50px; /* Adjust the max-width as needed */
            margin-right: 10px;
        }

        .team {
            margin-bottom: 40px;
        }

        .player-list {
            text-align:justify;
            margin: 0 auto;
            align-items: left;
            justify-content: left;
            display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-left: 10vw;
        }

        .player-item {
            f/*lex: 0 0 calc(50% - 10px);
            margin: 0 auto;
            display: flex;
    align-items: center*/
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
            vertical-align: middle;
            margin-right: 10px;
        }

        .slider {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: goldenrod;
        }

        input:checked + .slider:before {
            transform: translateX(20px);
        }

        button {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #FF3131;
            border-radius: 40px;
            color: white;
            border: none;
            /*border-radius: 5px;*/
            cursor: pointer;
            margin-top: 20px;
            margin-left: 40vw;
        }

        button:hover {
            /*background-color: #005a00;*/
        }
        #overallrating{
            margin-left: 10px;
        }
    </style>
</head>
<body>
<?php foreach ($teams as $teamName => $players): ?>
    <div class="team">
        <h3>
            <span class="team-name">
                <img class="team-logo" src="<?= $players[0]['logo'] ?>" alt="<?= $teamName ?> Logo">
                <?= $teamName ?>
            </span>
        </h3>
        <div class="player-list">
            <?php foreach ($players as $playerInfo): ?>
                <div class="player-item">
                    <label class="switch">
                        <input type="checkbox" onclick="handleCheckboxClick(this, '<?= $playerInfo['PlayerID'] ?>', '<?= $teamName ?>' );"  >
                        <span class="slider"></span>
                    </label>
                    <span><?= $playerInfo['PlayerName'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

<button id="select-players-btn">Select Players</button>
<!-- <div id="overallrating"> -->
<!-- Display the overall team ratings here -->
<!-- <h3>Overall Team Ratings</h3> -->
<!-- <p><strong><?= $homeTeam ?> Overall Rating: <span id="homeTeamRating">0.00</span></strong></p>
<p><strong><?= $awayTeam ?> Overall Rating: <span id="awayTeamRating">0.00</span></strong></p> -->
<!-- </div> -->
<script>

    const teamSelections = {};

    function handleCheckboxClick(checkbox, playerID, teamName) {
        if (!teamSelections[teamName]) {
            teamSelections[teamName] = [];
        }
        if (checkbox.checked) {
            if (teamSelections[teamName].length < 16) {
                teamSelections[teamName].push(playerID);
            } else {
                alert('You can only select up to 16 players from ' + teamName + '.');
                checkbox.checked = false;
            }
        } else {
            const index = teamSelections[teamName].indexOf(playerID);
            if (index > -1) {
                teamSelections[teamName].splice(index, 1);
            }
        }
    }

    document.getElementById('select-players-btn').addEventListener('click', () => {
        let allTeamsValid = true;
        let teamCount = 0;
        for (let team in teamSelections) {
            if (teamSelections[team].length < 11 || teamSelections[team].length > 16) {
                //
                alert('Please select between 11 and 16 players from ' + team + '.');
                allTeamsValid = false;
                break;
            }
            if (teamSelections[team].length >= 11 && teamSelections[team].length <= 16) {
                // 
                teamCount++;
            }
        }
        if (allTeamsValid && teamCount === 2) {
            calculateOverallTeamRatings();
        } else if (teamCount !== 2) {
            alert('Please select between 11 and 16 players from both teams.');
        }
    });

    function calculateOverallTeamRatings() {
        const playerRatings = <?= json_encode($playerRatings) ?>;
        let homeTeamRating = 0;
        let awayTeamRating = 0;

        for (let team in teamSelections) {
            console.log(team);
            
            let overallTeamRating = 0;
            let count = 0;

            for (let playerID of teamSelections[team]) {
                const playerName = playerRatings[playerID] ? playerRatings[playerID].name : 'Unknown';
                console.log(playerName);


                const playerRatingValue = playerRatings[playerID] ? playerRatings[playerID].rating : 0;
                console.log(playerRatingValue);
                overallTeamRating += playerRatingValue;
                console.log(overallTeamRating);

                // overallTeamRating = 100;

                count++;
            }
            let name = 'Brighton ';

            if (count > 0) {
                overallTeamRating /= count;
            }

            if (team.trim() === '<?= $homeTeam ?>') {
                console.log(team);
                
                homeTeamRating = overallTeamRating;
            } else if (team.trim() === '<?= $awayTeam ?>') {
                console.log(team);

                awayTeamRating = overallTeamRating;
            // }else if (team === name){
            //     console.log(name);

            }
            
        }

        // document.getElementById('homeTeamRating').textContent = homeTeamRating.toFixed(2);
        // document.getElementById('awayTeamRating').textContent = awayTeamRating.toFixed(2);

        const url = `new_file.php?homeTeamRating=${homeTeamRating}&awayTeamRating=${awayTeamRating}&homeTeam=<?= $homeTeam ?>&awayTeam=<?= $awayTeam ?>`;
       window.location.href = url; 
        

    }
</script>
</body>
</html>