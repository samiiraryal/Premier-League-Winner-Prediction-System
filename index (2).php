<?php

        $host="localhost";
        $user="root";
        $password="";
        $db="royacomn_Football_Data";

        $Conn = mysqli_connect($host,$user,$password,$db);
        if(!$Conn){
            echo("Connection error");
            exit();
        }
       
        $sql = "select TeamName,logo from teams";
        $result = mysqli_query($Conn,$sql);

        if(mysqli_num_rows($result)!=0){
            
            while($rows = mysqli_fetch_assoc($result)){ 

                $value=$rows['TeamName'];
                $value2=$rows['logo'];
                $teamNames[] = $value;
                $logos[]=$value2;
                



            }
        }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&display=swap" rel="stylesheet">
    <style>
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
#error{
    color: white;
    display: block;
    font-size: 20px;
   
}

.page-container {
    display: flex;
    flex-direction: column;
    align-items: center;
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
.team-selection {
    display: flex;
    justify-content: space-around;
    align-items: center; /* Vertically center align the VS separator */
    padding: 30px; /* Increased padding */
    background-color: none;
    
  
}

.team-side {
    text-align: center;
    padding: 30px; /* Increased padding */
     background-color: rgba(255, 255, 255, 0.7); /* Background color with 50% opacity */
    border-radius:40px;
    width:20vw;
  
}

/*.vs {
    font-size: 36px; /* Larger font size */
    margin: 0 40px; /* Increased margin */
}*/

#team2 {
    
    background-color: rgba(255, 255, 255, 0.7); /* Background color with 50% opacity */


}

.team-logo {
    width: 200px; /* Larger logo size */
    height: 200px; /* Larger logo size */
    background-size: contain;
    background-repeat: no-repeat;
    margin: 10px 0; /* Increased margin */
      margin-left: 3vw;
    
}

.nav-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px; /* Increased margin */
}

/* Add margin-right to the "Previous" button to create a gap */
#prev-team1 {
    margin-right: 10px; /* Increased margin */
}

/* Add margin-right to the "Next" button of the second team selection div (Team 2) */
#next-team2 {
    margin-left: 10px; /* Increased margin */
}

ul {
    list-style: none;
    padding: 0;
}

li {
    font-size: 24px; /* Larger font size */
    padding: 20px 0; /* Increased padding */
    cursor: pointer;
}

.selected {
    background-color: #007bff;
    color: #fff;
}

button {

    font-size: 20px; /* Increase font size */
    background-color: #32CD50;
    color: #fff;
    font-weight: bold;
    border: none;
    padding: 10px 20px; /* Increase padding */
    cursor: pointer;
    border-radius: 40px;
    width: 130px; /* Set a fixed width */
    height: 40px; /* Set a fixed height */
    
}

button:hover {
    background-color: #40BF7D;
}
#transfer-button{
    font-size: 24px; /* Larger font size for the submit button */
    background-color: #13455e; /* Change button color for the submit button */
    border-radius: 40px;
    width: 200px; /* Set a fixed width for the submit button */
    height: 50px; /* Set a fixed height for the submit button */
    margin-top: 30px; /* Increased margin for the submit button */
}
#submit-button {
    font-size: 24px; /* Larger font size for the submit button */
    background-color: #FF3131; /* Change button color for the submit button */
    border-radius: 40px;
    width: 200px; /* Set a fixed width for the submit button */
    height: 50px; /* Set a fixed height for the submit button */
    margin-top: 30px; /* Increased margin for the submit button */
}

#submit-button:hover {
    background-color: #F41818;
}
.btn{
    display: flex;
    column-gap: 30px;
}
    </style>
     
    <title>Team Selection</title>
</head>
<body>
    <div class="page-container">
        
     
        <div class="team-selection">
            <div class="team-side" id="team1">
                <div class="venue">HOME</div>
                <div id="team1-logo" class="team-logo"></div>
                <ul id="available-teams1"></ul>
                <div class="nav-buttons">
                    <button id="prev-team1">Previous</button>
                    <button id="next-team1">Next</button>
                </div>
            </div>
            <div class="vs"><img src="./vs.png"></div>
            <div class="team-side" id="team2">
                   <div class="venue2">AWAY</div>
                <div id="team2-logo" class="team-logo"></div>
                <ul id="available-teams2"></ul>
                <div class="nav-buttons">
                    <button id="prev-team2">Previous</button>
                    <button id="next-team2">Next</button>
                </div>
            </div>
           
        </div>
         <div id="error"> </div>
         <div class="btn">
        <button id="submit-button">Select Teams</button>
         <button id="transfer-button">Transfer</button>
     </div>
    </div>
    <script type="text/javascript"> document.addEventListener('DOMContentLoaded', () => {
    const team1Logo = document.getElementById('team1-logo');
    const team2Logo = document.getElementById('team2-logo');
    const availableTeamsList1 = document.getElementById('available-teams1');
    const availableTeamsList2 = document.getElementById('available-teams2');
    const prevTeam1Button = document.getElementById('prev-team1');
    const nextTeam1Button = document.getElementById('next-team1');
    const prevTeam2Button = document.getElementById('prev-team2');
    const nextTeam2Button = document.getElementById('next-team2');
    const submitButton = document.getElementById('submit-button');
    const transferButton = document.getElementById('transfer-button');

    let selectedTeam1Index = 0;
    let selectedTeam2Index = 0;

    function renderTeams() {
        const selectedTeam1Data = teamsData1[selectedTeam1Index];
        const selectedTeam2Data = teamsData2[selectedTeam2Index];

        // Display selected team data
        team1Logo.style.backgroundImage = `url(${selectedTeam1Data.logo})`;
        team2Logo.style.backgroundImage = `url(${selectedTeam2Data.logo})`;

        availableTeamsList1.innerHTML = '';
        availableTeamsList2.innerHTML = '';

        // Display selected team names
        const listItem1 = document.createElement('li');
        listItem1.textContent = selectedTeam1Data.name;
        availableTeamsList1.appendChild(listItem1);

        const listItem2 = document.createElement('li');
        listItem2.textContent = selectedTeam2Data.name;
        availableTeamsList2.appendChild(listItem2);
    }

    function updateSelection() {
        renderTeams();
    }

    prevTeam1Button.addEventListener('click', () => {
        if (selectedTeam1Index > 0) {
            selectedTeam1Index--;
            updateSelection();
        }
    });

    nextTeam1Button.addEventListener('click', () => {
        if (selectedTeam1Index < teamsData1.length - 1) {
            selectedTeam1Index++;
            updateSelection();
        }
    });

    prevTeam2Button.addEventListener('click', () => {
        if (selectedTeam2Index > 0) {
            selectedTeam2Index--;
            updateSelection();
        }
    });

    nextTeam2Button.addEventListener('click', () => {
        if (selectedTeam2Index < teamsData2.length - 1) {
            selectedTeam2Index++;
            updateSelection();
        }
    });

    submitButton.addEventListener('click', () => {
        const selectedTeam1Data = teamsData1[selectedTeam1Index];
        const selectedTeam2Data = teamsData2[selectedTeam2Index];
        const home=selectedTeam1Data.name;
        const away=selectedTeam2Data.name;
        if(home==away){
            
            document.getElementById("error").innerHTML=" Please select two different teams!"
        }
        else{
        window.location.href=`./playersName.php?home=${home}&away=${away}`;

        }
    });
    transferButton.addEventListener('click', () => {
        
        window.location.href='./transfer.php';

        
    });

    // Call updateSelection to display the initial teams when the page loads
    updateSelection();
});

const teamsData1 = [
    <?php
    for ($i = 0; $i < count($teamNames); $i++) {
        echo "{ name: '" . $teamNames[$i] . "', logo: '" . $logos[$i] . "' },";
    }
    ?>
];


const teamsData2 = [
    <?php
    for ($i = 0; $i < count($teamNames); $i++) {
        echo "{ name: '" . $teamNames[$i] . "', logo: '" . $logos[$i] . "' },";
    }
    ?>
];

</script>
   
</body>
</html>
