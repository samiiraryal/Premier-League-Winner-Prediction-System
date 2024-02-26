<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "royacomn_football_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_POST['action'] ?? null;
$selected_team_id = $_POST['team'] ?? null;
$selected_players = $_POST['selected_players'] ?? [];
$selected_transfer_team_id = $_POST['transfer_team'] ?? null;

// Check if the "transfer_player" action is triggered
if ($action === 'transfer_player' && isset($selected_team_id)) {
    if (!empty($selected_players) && isset($selected_transfer_team_id)) {
        // Move selected players to the selected Premier League team
        $selected_players_str = implode(",", $selected_players);
        $stmt = $conn->prepare("UPDATE players SET TeamID = ? WHERE PlayerID IN ($selected_players_str)");
        $stmt->bind_param("i", $selected_transfer_team_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Team Manager</title>
    <style>
    /* Reset default margin and padding for elements */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background-color: rgba(0, 0, 0, 0.7);
        font-family: Arial, sans-serif;
        background-image: url('./stadium.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        opacity: 0.8;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        border-radius: 8px;
        margin-top: 50px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        background-color: rgba(0, 0, 0, 0.4);
        color: white;
    }

    /* Style the select element */
    select.select-width {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        margin-bottom: 10px;
        appearance: none; /* Remove default appearance */
    }

    /* Style the dropdown arrow icon */
    select.select-width::after {
        content: '\25BC'; /* Unicode down arrow character */
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        font-size: 16px;
        color: white;
    }

    /* Style the dropdown options */
    select.select-width option {
        font-size: 16px;
        background-color: rgba(0, 0, 0, 0.2); /* Background color */
        color: white; /* Text color */
    }

    .input-width {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        margin-bottom: 10px;
    }

    .button-width {
        width: 100%;
        padding: 10px 0;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-bottom: 10px;
    }

    .button-width:hover {
        background-color: #45a049;
    }

    .button-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    p {
        font-size: 18px;
        margin-bottom: 20px;
    }

    label {
        font-size: 18px;
        margin-bottom: 5px;
    }
</style>




</head>
<body>
    <div class="container">
        <h1>Transfer Window</h1>
        <p>Manage your football teams and players.</p>
        
        <form method="POST" action="">
            <label for="team">Select Team: </label>
            <select name="team" id="team" class="select-width">
                <option value="">--Select--</option>
                <?php
                $sql = "SELECT * FROM teams";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['TeamID']."'>".$row['TeamName']."</option>";
                    }
                }
                ?>
            </select>
            <br>
            <input type="submit" name="action" value="Select Team" class="button-width" />
        </form>

        <?php
        if ($selected_team_id) {
            echo '<div class="button-group">
                <form method="POST" action="">
                    <input type="hidden" name="team" value="'.$selected_team_id.'">
                    <label for="player_id">Select Players to Transfer: </label><br>
                    <select multiple name="selected_players[]" id="selected_players" class="select-width">';
    
            $sql = "SELECT * FROM players WHERE TeamID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $selected_team_id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['PlayerID']."'>".$row['PlayerName']."</option>";
                }
            }
    
            echo '</select>';
    
            // Display team selection within Premier League
            echo '<div class="team-selection">
                        <label for="transfer_team">Select Team to Transfer To (Premier League): </label>
                        <select name="transfer_team" id="transfer_team" class="select-width">';
    
            $sql_teams = "SELECT * FROM teams";
            $result_teams = $conn->query($sql_teams);
    
            if ($result_teams->num_rows > 0) {
                while($row = $result_teams->fetch_assoc()) {
                    echo "<option value='".$row['TeamID']."'>".$row['TeamName']."</option>";
                }
            }
    
            echo '</select></div>';
    
            echo '<input type="submit" name="action" value="Transfer Players" class="button-width" />
                </form></div>';
        }
        ?>
    </div>
</body>
</html>
