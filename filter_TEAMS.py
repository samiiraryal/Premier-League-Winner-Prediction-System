import pandas as pd
import sys
import joblib
import statsmodels.api as sm

# Get values from command-line arguments
HTTR = float(sys.argv[1])
ATTR = float(sys.argv[2])
home_team_name = sys.argv[3]
away_team_name = sys.argv[4]
# home_team_name = 'Luton Town'
# away_team_name = 'Arsenal'


# Define the file path for your CSV file
file_path = "C:\\Users\\Dell\\Desktop\\inputs\\final_data_for_model_inputFinal.csv"



# Load the CSV file into a DataFrame
df = pd.read_csv(file_path)

df['HomeTeam'] = df['HomeTeam'].str.replace(' ', '')
df['AwayTeam'] = df['AwayTeam'].str.replace(' ', '')
teams_to_check = ['Burnley', 'LutonTown', 'SheffieldUnited']
if home_team_name in teams_to_check or away_team_name in teams_to_check:
    nHomeAttackRating = 0
    nHomeDefenseRating = 0
    nAwayAttackRating = 0
    nAwayDefenseRating = 0
else:
    # Filter the DataFrame based on the provided team names
    filtered_df = df[(df['HomeTeam'] == home_team_name) & (df['AwayTeam'] == away_team_name) ]
    # Print the filtered DataFrame
    nHomeAttackRating = filtered_df['nHomeAttackRating'].mean()
    nHomeDefenseRating = filtered_df['nHomeDefenseRating'].mean()
    nAwayAttackRating = filtered_df['nAwayAttackRating'].mean()
    nAwayDefenseRating = filtered_df['nAwayDefenseRating'].mean()

# Load the model
final_model = joblib.load('C:\\Users\\Dell\\Desktop\\inputs\\outputsfinal_model.pkl')

# Prepare the data for prediction
features = [[nHomeAttackRating, nHomeDefenseRating, nAwayAttackRating, nAwayDefenseRating, HTTR, ATTR]]
features = sm.add_constant(features)
# Make predictions
predictions = final_model.predict(features)

int(predictions)

# Use predictions as needed
print(predictions)








