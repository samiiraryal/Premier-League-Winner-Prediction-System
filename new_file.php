<!DOCTYPE html>
<html>
<head>
    <title>Football Match Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&display=swap" rel="stylesheet">
    <style>
        p{
             font-family: 'Oswald', sans-serif;
    font-weight: bold; 
    font-size:35px; /* Larger font size */
    padding: 20px 0; /* Increased padding */
    cursor: pointer;

        }
        .vs{
            margin-top: -80px;
        }
        body {
            font-family: 'Oswald', sans-serif;
    font-weight: bold; 
    margin: 0;
    padding: 0;
    background-image: url('./stadium.jpg'); 
    background-size: cover;
    background-repeat: no-repeat;
    background-color: rgba(0, 0, 0, 0.7);
     background-position: center top;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color:#13455e;
        }
        img{
/*width: 200px;
    height: 200px; 

    background-size: contain;
    background-repeat: no-repeat;
    margin: 10px 0; /* Increased margin */
      /*margin-left: 3vw;*/
*/    
}
        .venue{
    color:rgba(0, 0, 0, 0.5);
    font-family: 'Oswald', sans-serif;
    font-size: 36px;

}
.venue2{
    color:rgba(0, 0, 0, 0.5);
    font-family: 'Oswald', sans-serif;
    font-size: 36px;
}
#teams{
     display: flex;
    justify-content: space-around;
    align-items: center; /* Vertically center align the VS separator */
    padding: 30px; /* Increased padding */
    background-color: none;
}
        .team-info {
           text-align: center;
    padding: 30px; /* Increased padding */
     background-color: rgba(255, 255, 255, 0.7); /* Background color with 50% opacity */
    border-radius:40px;
    width:20vw;
    height:65vh;
    margin-top: -80px;
        }

        .team-logo {
            max-width: 100px;
        }

        p {
            color: black;
        }
    </style>
</head>
<body>
  

    <?php
    // Connect to the database (replace with your database credentials)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "royacomn_football_data";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve the values from the query parameters (replace with your actual parameters)
    $homeTeamRating = $_GET['homeTeamRating'] ?? '';
    $awayTeamRating = $_GET['awayTeamRating'] ?? '';
    $homeTeamName = $_GET['homeTeam'] ?? '';
    $awayTeamName = $_GET['awayTeam'] ?? '';

    // Function to fetch team logo by name
    function getTeamLogo($conn, $teamName) {
        $sql = "SELECT logo FROM teams WHERE TeamName = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $teamName);
        $stmt->execute();
        $stmt->bind_result($teamLogo);
        $stmt->fetch();
        $stmt->close();
        return $teamLogo;
    }

    // Fetch team logos based on team names
    $homeTeamLogo = getTeamLogo($conn, $homeTeamName);
    $awayTeamLogo = getTeamLogo($conn, $awayTeamName);

    $homeTeamNameTrimmed= str_replace(' ', '', $homeTeamName);
     $awayTeamNameTrimmed= str_replace(' ', '', $awayTeamName);

    $conn->close();
    try {
    $command = "python3 C:\\xampp\\htdocs\\Minor_project\\filter_TEAMS.py $homeTeamRating $awayTeamRating $homeTeamNameTrimmed $awayTeamNameTrimmed";
    $result = shell_exec($command);
    // echo($result);

    $result = trim($result, '[]');
    $result = str_replace(array('[', ']'), '', $result);
    // echo($result);
        // echo "Result: " . $result;
    if ($result == 1) {
            echo "<div id='result-div' style='color: #ffff; font-size: 24px; margin-top: 20px;'>WINNER: " . htmlspecialchars($homeTeamName) . "</div>";
        } elseif ($result == 0) {
            echo "<div id='result-div' style='color: #ffff; font-size: 24px; margin-top: 20px;'>WINNER: " . htmlspecialchars($awayTeamName) . "</div>";
        } else {
            echo "<div id='result-div' style='color: #ffff; font-size: 24px; margin-top: 20px;'>Result: Invalid</div>";
        }
   
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
    ?>

<div id="teams">
    <div class="team-info">
        <div class="venue">HOME</div>
        
        <img src="<?= htmlspecialchars($homeTeamLogo) ?>" alt="Home Team Logo" class="team-logo" style="max-width: 200px; max-height: 200px; margin-top: 15px;">

    
        <!-- <p>Rating: <?= htmlspecialchars($homeTeamRating) ?></p> -->
        <p style="color: #13455e;"> <?= htmlspecialchars($homeTeamName) ?></p>
    </div>
<div class="vs"><img src="./vs.png"></div>
    <div class="team-info">
         <div class="venue2">AWAY</div>
        
        <img src="<?= htmlspecialchars($awayTeamLogo) ?>" alt="Away Team Logo" class="team-logo"style="max-width: 200px; max-height: 200px;margin-top: 15px;">
   
        <!-- <p>Rating: <?= htmlspecialchars($awayTeamRating) ?></p> -->
        <p style="color: #13455e;"><?= htmlspecialchars($awayTeamName) ?></p>
    </div>
</div>
</body>
</html>
