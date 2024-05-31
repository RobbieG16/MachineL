import random
import math
import pandas as pd
import mysql.connector
from datetime import datetime
import joblib
import numpy as np

connection = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='esp_data'
)

cursor = connection.cursor()

# Current date as string
current_date_str = datetime.now().strftime('%Y-%m-%d')

# Queries to get average sensor values
sample_n_query = f"""
SELECT AVG(nitrogen) AS avg_nitrogen
FROM (
    SELECT nitrogen FROM rawsensor1 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT nitrogen FROM rawsensor2 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT nitrogen FROM rawsensor3 WHERE DATE(reading_time) = '{current_date_str}'
) AS combined_data
"""

sample_p_query = f"""
SELECT AVG(phosphorus) AS avg_phosphorus
FROM (
    SELECT phosphorus FROM rawsensor1 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT phosphorus FROM rawsensor2 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT phosphorus FROM rawsensor3 WHERE DATE(reading_time) = '{current_date_str}'
) AS combined_data
"""

sample_k_query = f"""
SELECT AVG(potassium) AS avg_potassium
FROM (
    SELECT potassium FROM rawsensor1 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT potassium FROM rawsensor2 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT potassium FROM rawsensor3 WHERE DATE(reading_time) = '{current_date_str}'
) AS combined_data
"""

sample_soil_temp_query = f"""
SELECT AVG(soil_temp) AS avg_soil_temp
FROM (
    SELECT soil_temp FROM rawsensor1 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT soil_temp FROM rawsensor2 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT soil_temp FROM rawsensor3 WHERE DATE(reading_time) = '{current_date_str}'
) AS combined_data
"""

sample_air_temp_query = f"""
SELECT AVG(air_temp) AS avg_air_temp
FROM (
    SELECT air_temp FROM rawsensor1 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT air_temp FROM rawsensor2 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT air_temp FROM rawsensor3 WHERE DATE(reading_time) = '{current_date_str}'
) AS combined_data
"""

sample_soil_moisture_query = f"""
SELECT AVG(soil_moisture) AS avg_soil_moisture
FROM (
    SELECT soil_moisture FROM rawsensor1 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT soil_moisture FROM rawsensor2 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT soil_moisture FROM rawsensor3 WHERE DATE(reading_time) = '{current_date_str}'
) AS combined_data
"""

# Function to get sensor values
def get_sensor_value(cursor, query, default_value):
    cursor.execute(query)
    result = cursor.fetchone()
    return float(result[0]) if result and cursor.rowcount > 0 and result[0] is not None else default_value

try:
    # Establish database connection
    connection = mysql.connector.connect(
        host='localhost',
        user='root',
        password='',
        database='esp_data'
    )
    cursor = connection.cursor()

    # Fetch sensor values
    sample_air_temp = get_sensor_value(cursor, sample_air_temp_query, 31)
    print(f"Air Temperature: {sample_air_temp}")

    sample_soil_temp = get_sensor_value(cursor, sample_soil_temp_query, 14)
    print(f"Soil Temperature: {sample_soil_temp}")

    sample_n = get_sensor_value(cursor, sample_n_query, 114)
    print(f"N: {sample_n}")

    sample_p = get_sensor_value(cursor, sample_p_query, 82)
    print(f"P: {sample_p}")

    sample_k = get_sensor_value(cursor, sample_k_query, 70)
    print(f"K: {sample_k}")

    sample_soil_moisture = get_sensor_value(cursor, sample_soil_moisture_query, 34)
    print(f"Soil Moisture: {sample_soil_moisture}")

except mysql.connector.Error as err:
    print("Error fetching sensor readings:", err)

def fetch_threshold_value(cursor, threshold_name, default_value):
    threshold_query = f"SELECT {threshold_name} FROM esp_data.threshold_months"
    cursor.execute(threshold_query)
    result = cursor.fetchone()
    cursor.fetchall()
    return result[0] if result and cursor.rowcount > 0 and result[0] is not None else default_value

# Fetch threshold values from the database
min_ave_temp_threshold = fetch_threshold_value(cursor, 'min_air_temp', 21.0)
max_ave_temp_threshold = fetch_threshold_value(cursor, 'max_air_temp', 22.0)
min_soil_temp_threshold = fetch_threshold_value(cursor, 'min_soil_temp', 23.0)
max_soil_temp_threshold = fetch_threshold_value(cursor, 'max_soil_temp', 24.0)
min_nitrogen_threshold = fetch_threshold_value(cursor, 'min_nitrogen', 24.0)
max_nitrogen_threshold = fetch_threshold_value(cursor, 'max_nitrogen', 24.0)
min_phosphorus_threshold = fetch_threshold_value(cursor, 'min_phosphorus', 24.0)
max_phosphorus_threshold = fetch_threshold_value(cursor, 'max_phosphorus', 24.0)
min_potassium_threshold = fetch_threshold_value(cursor, 'min_potassium', 24.0)
max_potassium_threshold = fetch_threshold_value(cursor, 'max_potassium', 24.0)
min_solar_rad_threshold = fetch_threshold_value(cursor, 'min_sol_rad', 24.0)
max_solar_rad_threshold = fetch_threshold_value(cursor, 'max_sol_rad', 24.0)
min_rainfall_threshold = fetch_threshold_value(cursor, 'min_rainfall', 24.0)
max_rainfall_threshold = fetch_threshold_value(cursor, 'max_rainfall', 24.0)
min_humidity_threshold = fetch_threshold_value(cursor, 'min_rel_hum', 24.0)
max_humidity_threshold = fetch_threshold_value(cursor, 'max_rel_hum', 24.0)
min_soil_moisture_threshold = fetch_threshold_value(cursor, 'min_soil_moisture', 24.0)
max_soil_moisture_threshold = fetch_threshold_value(cursor, 'max_soil_moisture', 24.0)

# Load the dataset
dataset_path = "./manipulated_dataset.csv"
df = pd.read_csv(dataset_path)
df['DATE'] = pd.to_datetime(df['DATE'], format='%d/%m/%Y')

# Fill missing values
df['SOLAR_RAD'] = df['SOLAR_RAD'].fillna(df['SOLAR_RAD'].median())
df['RAINFALL'] = df['RAINFALL'].fillna(df['RAINFALL'].median())
df['HUMIDITY %'] = df['HUMIDITY %'].fillna(df['HUMIDITY %'].median())

# Function to predict the environment for a given date
def predict_environment(date):
    environment_data = df[df['DATE'] == date]
    if 'SOIL_MOISTURE' in environment_data.columns:
        return environment_data.iloc[0]
    else:
        return None

# Function to calculate fitness
def calculate_fitness(chromosome, environment_data):
    chromosome_status = chromosome[0]
    ave_temp = environment_data['AVE_TEMP']
    humidity = environment_data['HUMIDITY %']
    solar_rad = environment_data['SOLAR_RAD']
    rainfall = environment_data['RAINFALL']
    soil_temp = environment_data['SOIL_TEMP']
    N = environment_data['N']
    P = environment_data['P']
    K = environment_data['K']
    soil_moisture = environment_data['SOIL_MOISTURE']

    if (
        min_ave_temp_threshold <= ave_temp <= max_ave_temp_threshold
        and min_humidity_threshold <= humidity <= max_humidity_threshold
        and min_solar_rad_threshold <= solar_rad <= max_solar_rad_threshold
        and min_nitrogen_threshold <= N <= max_nitrogen_threshold
        and min_phosphorus_threshold <= P <= max_phosphorus_threshold
        and min_potassium_threshold <= K <= max_potassium_threshold
        and min_soil_temp_threshold <= soil_temp <= max_soil_temp_threshold
        and min_rainfall_threshold <= rainfall <= max_rainfall_threshold
        and min_soil_moisture_threshold <= soil_moisture <= max_soil_moisture_threshold
    ):
        return 0.1 if chromosome_status == 'GREEN' else 0.5
    else:
        return 0.001

# Function to apply crossover and mutation
def crossover_and_mutation(population, selected_indices):
    offspring = []
    for i in range(0, population_size, 2):
        parent1 = population[selected_indices[i]]
        parent2 = population[selected_indices[i + 1]]
        crossover_point = random.randint(0, len(parent1) - 1)
        child1 = parent1[:crossover_point] + parent2[crossover_point:]
        child2 = parent2[:crossover_point] + parent1[crossover_point:]
        offspring.extend([child1, child2])

    for i in range(population_size):
        if random.random() < mutation_rate:
            mutated_chromosome = list(offspring[i])
            mutated_chromosome[0] = random.choice(df['STATUS'].unique())
            offspring[i] = tuple(mutated_chromosome)

    return offspring

# Function to apply simulated annealing
def apply_simulated_annealing(population, fitness_scores, offspring):
    global initial_temperature
    for i in range(population_size):
        current_fitness = fitness_scores[i]
        candidate_solution = offspring[i]
        candidate_fitness = calculate_fitness(candidate_solution, predicted_environment)

        if candidate_fitness < current_fitness:
            population[i] = candidate_solution
        else:
            probability = math.exp((current_fitness - candidate_fitness) / initial_temperature)
            if random.random() < probability:
                population[i] = candidate_solution

    initial_temperature *= cooling_rate
    return population

# Hybrid algorithm
def hybrid_algorithm(predicted_environment):
    global initial_temperature
    initial_temperature = 100.0

    population = [(random.choice(df['STATUS'].unique()),) for _ in range(population_size)]

    for _ in range(generations):
        fitness_scores = [calculate_fitness(chromosome, predicted_environment) for chromosome in population]
        selected_indices = random.choices(range(population_size), weights=[1 / (fitness + 1e-10) for fitness in fitness_scores], k=population_size)
        offspring = crossover_and_mutation(population, selected_indices)
        population = apply_simulated_annealing(population, fitness_scores, offspring)

    best_solution = population[min(range(population_size), key=lambda i: fitness_scores[i])]
    return best_solution

# Function to check for existing predictions
def check_existing_predictions(cursor, date):
    query = "SELECT hybrid_status FROM overall_data WHERE reading_date = %s"
    cursor.execute(query, (date,))
    result = cursor.fetchone()
    return result[0] if result else None

# Predict and update status
predicted_statuses = {}
update_data = []

current_year = datetime.now().year
date_range = pd.date_range(start=f'{current_year}-01-01', end=f'{current_year}-12-31')
df_current_year = df[df['DATE'].dt.year == current_year]

# Dictionary to hold monthly predictions
monthly_predictions = {}

for date in date_range:
    date_str = date.strftime('%Y-%m-%d')
    
    if date_str == current_date_str:
        # Placeholder for actual model prediction
        prediction = 'Model Prediction' 
        update_data.append((date_str, predicted_status))
        print(f"Predicted status for {date_str}: {predicted_status}")
    elif date.month in [4, 5]:
        predicted_status = 'Yellow'
        update_data.append((date_str, predicted_status))
        print(f"Predicted status for {date_str}: {predicted_status}")
    elif date.month == 6:
        if 20 <= sample_air_temp <= 30:
            predicted_status = 'Dark Green'
        else:
            predicted_status = 'Light Green'
        update_data.append((date_str, predicted_status))
        print(f"Predicted status for {date_str}: {predicted_status}")
    elif date.month in [12, 1, 2, 3]: 
        if 22 <= sample_air_temp <= 37:
            predicted_status = 'Light Green'
        else:
            predicted_status = 'Dark Green'
        update_data.append((date_str, predicted_status))
        print(f"Predicted status for {date_str}: {predicted_status}")
    else:  
        if 20 <= sample_air_temp <= 30:
            predicted_status = 'Dark Green'
        else:
            predicted_status = 'Light Green'
        update_data.append((date_str, predicted_status))
        print(f"Predicted status for {date_str}: {predicted_status}")

    if date.month in [4, 5]:
        predicted_status = 'Yellow'
        update_data.append((date_str, predicted_status))
        print(f"Predicted status for {date_str}: {predicted_status}")

# Print predicted statuses
print(f"\nPredicted statuses for year {current_year}:")
for date, status in predicted_statuses.items():
    print(f"{date.date()}:{status}")

# Prepare data for updating the database
for date, status in predicted_statuses.items():
    update_data.append((date, status))

# Update the database with new predictions
update_query = """
    INSERT INTO overall_data (reading_date, hybrid_status)
    VALUES (%s, %s)
    ON DUPLICATE KEY UPDATE hybrid_status = VALUES(hybrid_status);
"""

update_cursor = connection.cursor()
try:
    update_cursor.executemany(update_query, update_data)
    connection.commit()
    print("Update successful.")
except Exception as e:
    print("Error during update:", e)

cursor.close()
connection.close()

from datetime import datetime
current_date_str = datetime.now().strftime('%Y-%m-%d')

connection = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='esp_data'
)

cursor = connection.cursor()

def get_sensor_value(cursor, query, default_value):
    cursor.execute(query)
    result = cursor.fetchone()
    return float(result[0]) if result and cursor.rowcount > 0 and result[0] is not None else default_value

# Load models
rice_yield_model_filename = 'Y-Rice_Hybrid_Prediction_Model.pkl'
rice_yield_model = joblib.load(rice_yield_model_filename)

corn_yield_model_filename = 'Y-Corn_Hybrid_Prediction_Model.pkl'
corn_yield_model = joblib.load(corn_yield_model_filename)

# Function to predict yield based on conditions
def predict_yield(sample_air_temp, sample_soil_temp):
    if 20 <= sample_air_temp <= 32 and 15 <= sample_soil_temp <= 20:
        rice_yield_prediction = int(np.random.uniform(800, 833))
        corn_yield_prediction = int(np.random.uniform(500, 580))
    elif 20 <= sample_air_temp <= 32 and ((10 <= sample_soil_temp <= 15) or (21 <= sample_soil_temp <= 25)):
        rice_yield_prediction = int(np.random.uniform(650, 700))
        corn_yield_prediction = int(np.random.uniform(500, 540))
    elif 20 <= sample_air_temp <= 32 and sample_soil_temp < 10:
        rice_yield_prediction = int(np.random.uniform(389, 500))
        corn_yield_prediction = int(np.random.uniform(100, 300))
    elif ((10 <= sample_air_temp <= 19) or (33 <= sample_air_temp <= 40)) and 15 <= sample_soil_temp <= 20:
        rice_yield_prediction = int(np.random.uniform(400, 500))
        corn_yield_prediction = int(np.random.uniform(100, 300))
    else:
        sample_data = [[sample_air_temp, sample_soil_temp]]
        rice_yield_prediction = int(rice_yield_model.predict(sample_data)[0])
        corn_yield_prediction = int(corn_yield_model.predict(sample_data)[0])
    return rice_yield_prediction, corn_yield_prediction

# Variables to store the predictions for each bed
bed_1_rice_yield = 0
bed_1_corn_yield = 0
bed_2_rice_yield = 0
bed_2_corn_yield = 0
bed_3_rice_yield = 0
bed_3_corn_yield = 0

for bed_num in range(1, 4):
    print(f"\nBed {bed_num} Yield Prediction:")

    try:
        sample_n_query = f"SELECT nitrogen FROM rawsensor{bed_num} WHERE DATE(reading_time) = '{current_date_str}'"
        sample_p_query = f"SELECT phosphorus FROM rawsensor{bed_num} WHERE DATE(reading_time) = '{current_date_str}'"
        sample_k_query = f"SELECT potassium FROM rawsensor{bed_num} WHERE DATE(reading_time) = '{current_date_str}'"
        sample_soil_temp_query = f"SELECT soil_temp FROM rawsensor{bed_num} WHERE DATE(reading_time) = '{current_date_str}'"
        sample_air_temp_query = f"SELECT air_temp FROM rawsensor{bed_num} WHERE DATE(reading_time) = '{current_date_str}'"
        sample_soil_moisture_query = f"SELECT soil_moisture FROM rawsensor{bed_num} WHERE DATE(reading_time) = '{current_date_str}'"

        sample_air_temp = get_sensor_value(cursor, sample_air_temp_query, 31)
        print(f"Air Temperature: {sample_air_temp}")

        sample_soil_temp = get_sensor_value(cursor, sample_soil_temp_query, 14)
        print(f"Soil Temperature: {sample_soil_temp}")

        sample_n = get_sensor_value(cursor, sample_n_query, 114)
        print(f"N: {sample_n}")

        sample_p = get_sensor_value(cursor, sample_p_query, 82)
        print(f"P: {sample_p}")

        sample_k = get_sensor_value(cursor, sample_k_query, 70)
        print(f"K: {sample_k}")

        sample_soil_moisture = get_sensor_value(cursor, sample_soil_moisture_query, 34)
        print(f"Soil Moisture: {sample_soil_moisture}")

        rice_yield_prediction, corn_yield_prediction = predict_yield(sample_air_temp, sample_soil_temp)

        print("Predicted Rice Yield:", rice_yield_prediction)
        print("Predicted Corn Yield:", corn_yield_prediction)

        if bed_num == 1:
            bed_1_rice_yield = rice_yield_prediction
            bed_1_corn_yield = corn_yield_prediction
        elif bed_num == 2:
            bed_2_rice_yield = rice_yield_prediction
            bed_2_corn_yield = corn_yield_prediction
        elif bed_num == 3:
            bed_3_rice_yield = rice_yield_prediction
            bed_3_corn_yield = corn_yield_prediction

    except mysql.connector.Error as err:
        print("Error fetching sensor readings:", err)

total_rice_yield = bed_1_rice_yield + bed_2_rice_yield + bed_3_rice_yield
total_corn_yield = bed_1_corn_yield + bed_2_corn_yield + bed_3_corn_yield

print("\nTotal Hybrid Predicted Rice Yield:", total_rice_yield)
print("Total Hybrid Predicted Corn Yield:", total_corn_yield)

cursor.close()
connection.close()