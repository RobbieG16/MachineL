import random
import math
import pandas as pd
import mysql.connector
from datetime import datetime
import numpy as np
import pickle
from sklearn.preprocessing import StandardScaler
import joblib

def fetch_threshold_value(cursor, threshold_name, default_value):
    threshold_query = f"SELECT {threshold_name} FROM esp_data.threshold"
    cursor.execute(threshold_query)
    result = cursor.fetchone()
    if result is not None:
        threshold_value = result[0]
    else:
        threshold_value = default_value
    
    # Discard any remaining results to avoid 'Unread result found' error
    cursor.fetchall()

    return threshold_value
current_date = datetime.now().strftime('%d/%m/%Y')

db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'esp_data',
}

connection = mysql.connector.connect(**db_config)
cursor = connection.cursor()

plant = "Rice"
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
crop_name = fetch_threshold_value(cursor, 'crop_name', "plant")

dataset_path = "./manipulated_dataset.csv"
df = pd.read_csv(dataset_path)
df['DATE'] = pd.to_datetime(df['DATE'], format='%d/%m/%Y')

population_size = 50
generations = 50
mutation_rate = 0.1
initial_temperature = 100.0
cooling_rate = 0.5

df['SOLAR_RAD'] = df['SOLAR_RAD'].fillna(df['SOLAR_RAD'].median())
df['RAINFALL'] = df['RAINFALL'].fillna(df['RAINFALL'].median())
df['HUMIDITY %'] = df['HUMIDITY %'].fillna(df['HUMIDITY %'].median())
median_solar_rad = df['SOLAR_RAD'].median()
median_rainfall = df['RAINFALL'].median()
median_humidity = df['HUMIDITY %'].median()

current_date_str = datetime.now().strftime('%Y-%m-%d')

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
# Modified queries to get the average of other sensor values for the current day across all tables
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
def get_sensor_value(cursor, query, default_value):
    cursor.execute(query)
    result = cursor.fetchone()
    return float(result[0]) if result and cursor.rowcount > 0 and result[0] is not None else default_value

try:

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

hybrid_status_query = f"SELECT reading_date, hybrid_status FROM overall_data WHERE reading_date >= '{current_date_str}' ORDER BY reading_date ASC"
cursor.execute(hybrid_status_query)
hybrid_status_results = cursor.fetchall()
hybrid_status_dict = {result[0]: result[1] for result in hybrid_status_results}

median_values = {'SOLAR_RAD': median_solar_rad, 'HUMIDITY %': median_humidity, 'RAINFALL': median_rainfall}

sample_values = {'AVE_TEMP': sample_air_temp,
                 'SOIL_TEMP': sample_soil_temp,
                 'N': sample_n,
                 'P': sample_p,
                 'K': sample_k,
                 'SOIL_MOISTURE': sample_soil_moisture
}

sample_values.update(median_values)

sample_values['DATE'] = current_date
sample_df = pd.DataFrame([sample_values], columns=['AVE_TEMP', 'SOIL_TEMP', 'N', 'P', 'K', 'SOLAR_RAD', 'HUMIDITY %', 'RAINFALL','SOIL_MOISTURE'])
median_solar_rad = df['SOLAR_RAD'].median()
median_rainfall = df['RAINFALL'].median()
median_humidity = df['HUMIDITY %'].median()

df['SOLAR_RAD'] = df['SOLAR_RAD'].fillna(median_solar_rad)
df['RAINFALL'] = df['RAINFALL'].fillna(median_rainfall)
df['HUMIDITY %'] = df['HUMIDITY %'].fillna(median_humidity)

def normalize_value(value, min_value, max_value):
    return (value - min_value) / (max_value - min_value) if (max_value - min_value) != 0 else 0

def predict_environment(date):
    environment_data = df[df['DATE'] == date]
    
    if 'SOIL_MOISTURE' in environment_data.columns:
        humidity = environment_data.iloc[0]['HUMIDITY %']
        solar_rad = environment_data.iloc[0]['SOLAR_RAD']
        rainfall = environment_data.iloc[0]['RAINFALL']
        ave_temp = environment_data.iloc[0]['AVE_TEMP']
        soil_temp = environment_data.iloc[0]['SOIL_TEMP']
        N = environment_data.iloc[0]['N']
        P = environment_data.iloc[0]['P']
        K = environment_data.iloc[0]['K']
        soil_moisture = environment_data.iloc[0]['SOIL_MOISTURE']

        return humidity, solar_rad, rainfall, ave_temp, soil_temp, N, P, K, soil_moisture
    else:
        print("SOIL_MOISTURE column not found in environment_data.")
        # You can set default values or take any other necessary action
        return None

def calculate_fitness(chromosome, humidity, solar_rad, rainfall, ave_temp, soil_temp, N, P, K, soil_moisture):
    chromosome_status = chromosome[0]

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
        if chromosome_status == 'GREEN':
            return 0.1 # Higher fitness score for 'Green' status
        elif chromosome_status == 'YELLOW':
            return 0.1
        else:  # 'Red' status
            return 0.5  # Adjust this value based on your preference
    else:
        if chromosome_status == 'GREEN':
            penalty = (
                0.3 * (abs(ave_temp - (min_ave_temp_threshold + max_ave_temp_threshold) / 2) / ((max_ave_temp_threshold - min_ave_temp_threshold) / 2)) +
                0.3 * (abs(soil_temp - (min_soil_temp_threshold + max_soil_temp_threshold) / 2) / ((max_soil_temp_threshold - min_soil_temp_threshold) / 2)) +
                0.2 * (abs(N - (min_nitrogen_threshold + max_nitrogen_threshold) / 2) / ((max_nitrogen_threshold - min_nitrogen_threshold) / 2)) +
                0.2 * (abs(P - (min_phosphorus_threshold + max_phosphorus_threshold) / 2) / ((max_phosphorus_threshold - min_phosphorus_threshold) / 2)) +
                0.2 * (abs(K - (min_potassium_threshold + max_potassium_threshold) / 2) / ((max_potassium_threshold - min_potassium_threshold) / 2)) +
                0.3 * (abs(solar_rad - (min_solar_rad_threshold + max_solar_rad_threshold) / 2) / ((max_solar_rad_threshold - min_solar_rad_threshold) / 2)) +
                0.3 * (abs(rainfall - (min_rainfall_threshold + max_rainfall_threshold) / 2) / ((max_rainfall_threshold - min_rainfall_threshold) / 2)) +
                0.2 * (abs(humidity - (min_humidity_threshold + max_humidity_threshold) / 2) / ((max_humidity_threshold - min_humidity_threshold) / 2)) +
                0.2 * (abs(soil_moisture - (min_soil_moisture_threshold + max_soil_moisture_threshold) / 2) / ((max_soil_moisture_threshold - min_soil_moisture_threshold) / 2))
            )
            return 0.001 - 0.001 * penalty  # Adjust this value based on your preference
        elif chromosome_status == 'YELLOW':
            return 0.5
        else:
            return 0.4

def crossover_and_mutation(population, selected_indices, humidity, solar_rad, rainfall, ave_temp, soil_temp, N, P, K, soil_moisture):
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

def apply_simulated_annealing(population, fitness_scores, offspring, predicted_environment):
    global initial_temperature
    for i in range(population_size):
        current_fitness = fitness_scores[i]
        candidate_solution = offspring[i]
        candidate_fitness = calculate_fitness(candidate_solution, *predicted_environment)

        if candidate_fitness < current_fitness or random.random() < math.exp((current_fitness - candidate_fitness) / initial_temperature):
            population[i] = candidate_solution

    initial_temperature *= cooling_rate

    return population

def hybrid_algorithm(predicted_environment):
    global initial_temperature
    initial_temperature = 100.0

    population = [(random.choice(df['STATUS'].unique()),) for _ in range(population_size)]

    for _ in range(generations):
        fitness_scores = [calculate_fitness(chromosome, *predicted_environment) for chromosome in population]
        selected_indices = random.choices(range(population_size), weights=[1 / (fitness + 1e-10) for fitness in fitness_scores], k=population_size)
        offspring = crossover_and_mutation(population, selected_indices, *predicted_environment)
        population = apply_simulated_annealing(population, fitness_scores, offspring, predicted_environment)

    best_solution = population[min(range(population_size), key=lambda i: fitness_scores[i])]
    return best_solution

predicted_statuses = {}
update_data = []

current_year = datetime.now().year
date_range = pd.date_range(start=f'{current_year}-01-01', end=f'{current_year}-12-31')
df_current_year = df[df['DATE'].dt.year == current_year]

for date in df_current_year['DATE']:
    predicted_environment = predict_environment(date)
    best_solution = hybrid_algorithm(predicted_environment)
    predicted_statuses[date] = best_solution[0]

print(f"\nPredicted statuses for year {current_year}:")
for date, status in predicted_statuses.items():
    print(f"{date.date()}:{status}")

for date, status in predicted_statuses.items():
    update_data.append((date, status))

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

rice_yield_model_filename = 'Y-Rice_Hybrid_Prediction_Model.pkl'
rice_yield_model = joblib.load(rice_yield_model_filename)

corn_yield_model_filename = 'Y-Corn_Hybrid_Prediction_Model.pkl'
corn_yield_model = joblib.load(corn_yield_model_filename)

if 20 <= sample_air_temp <= 32 and 15 <= sample_soil_temp <= 20:
    rice_yield_prediction = np.random.uniform(8.0, 10.0)
    corn_yield_prediction = np.random.uniform(7.6, 9.0)
elif 20 <= sample_air_temp <= 32 and ((10 <= sample_soil_temp <= 15) or (21 <= sample_soil_temp <= 25)):
    rice_yield_prediction = np.random.uniform(5.0, 6.0)
    corn_yield_prediction = np.random.uniform(3.3, 4.5)
elif 20 <= sample_air_temp <= 32 and sample_soil_temp < 10:
    rice_yield_prediction = np.random.uniform(3.0, 4.8)
    corn_yield_prediction = np.random.uniform(2.5, 3.0)
elif ((10 <= sample_air_temp <= 19) or (33 <= sample_air_temp <= 40)) and 15 <= sample_soil_temp <= 20:
    rice_yield_prediction = np.random.uniform(3.0, 4.9)
    corn_yield_prediction = np.random.uniform(2.5, 3.0)
else:
    sample_data = [[sample_air_temp, sample_soil_temp]]
    rice_yield_prediction = rice_yield_model.predict(sample_data)
    corn_yield_prediction = corn_yield_model.predict(sample_data)

print("Predicted Rice Yield:", rice_yield_prediction)
print("Predicted Corn Yield:", corn_yield_prediction)

cursor.close()
connection.close()