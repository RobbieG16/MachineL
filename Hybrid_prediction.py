import random
import math
import pandas as pd
import mysql.connector
from datetime import datetime
import numpy as np
import pickle
from sklearn.preprocessing import StandardScaler
import joblib

#Discuss and Familiarize your Codes for Defence
def fetch_threshold_value(cursor, threshold_name, default_value):
    threshold_query = f"SELECT {threshold_name} FROM esp_data.threshold"
    cursor.execute(threshold_query)
    result = cursor.fetchone()
    return result[0] if result and cursor.rowcount > 0 and result[0] is not None else default_value

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
min_ave_temp_threshold = fetch_threshold_value(cursor, 'min_airt', 21.0)
max_ave_temp_threshold = fetch_threshold_value(cursor, 'max_airt', 22.0)
min_soil_temp_threshold = fetch_threshold_value(cursor, 'min_soilt', 23.0)
max_soil_temp_threshold = fetch_threshold_value(cursor, 'max_soilt', 24.0)
min_nitrogen_threshold = fetch_threshold_value(cursor, 'min_n', 24.0)
max_nitrogen_threshold = fetch_threshold_value(cursor, 'max_n', 24.0)
min_phosphorus_threshold = fetch_threshold_value(cursor, 'min_p', 24.0)
max_phosphorus_threshold = fetch_threshold_value(cursor, 'max_p', 24.0)
min_potassium_threshold = fetch_threshold_value(cursor, 'min_k', 24.0)
max_potassium_threshold = fetch_threshold_value(cursor, 'max_k', 24.0)
min_solar_rad_threshold = fetch_threshold_value(cursor, 'min_sr', 24.0)
max_solar_rad_threshold = fetch_threshold_value(cursor, 'max_sr', 24.0)
min_rainfall_threshold = fetch_threshold_value(cursor, 'min_rain', 24.0)
max_rainfall_threshold = fetch_threshold_value(cursor, 'max_rain', 24.0)
min_humidity_threshold = fetch_threshold_value(cursor, 'min_hum', 24.0)
max_humidity_threshold = fetch_threshold_value(cursor, 'max_hum', 24.0)
min_soil_moisture_threshold = fetch_threshold_value(cursor, 'min_sm', 24.0)
max_soil_moisture_threshold = fetch_threshold_value(cursor, 'max_sm', 24.0)
crop_name = fetch_threshold_value(cursor, 'crop_name', "plant")

#Change Dataset provided by CRL..
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
sample_temperature_query = f"SELECT all_air_temp FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_soil_temp_query = f"SELECT all_soil_temperature FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_n_query = f"SELECT all_nitrogen FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_p_query = f"SELECT all_phosphorus FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_k_query = f"SELECT all_potassium FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_soil_moisture_query = f"SELECT all_soil_moisture FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"

def get_sensor_value(cursor, query, default_value):
    cursor.execute(query)
    result = cursor.fetchone()
    return float(result[0]) if result and cursor.rowcount > 0 and result[0] is not None else default_value

sample_temperature = get_sensor_value(cursor, sample_temperature_query, 31)
print(f"Air Temperature: {sample_temperature}")

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

hybrid_status_query = f"SELECT reading_date, hybrid_status FROM overall_data WHERE reading_date >= '{current_date_str}' ORDER BY reading_date ASC"
cursor.execute(hybrid_status_query)
hybrid_status_results = cursor.fetchall()
hybrid_status_dict = {result[0]: result[1] for result in hybrid_status_results}

# Create a dictionary with median values for missing columns
median_values = {'SOLAR_RAD': median_solar_rad, 'HUMIDITY %': median_humidity, 'RAINFALL': median_rainfall}

sample_values = {'AVE_TEMP': sample_temperature,
                 'SOIL_TEMP': sample_soil_temp,
                 'N': sample_n,
                 'P': sample_p,
                 'K': sample_k,
                 'SOIL_MOISTURE': sample_soil_moisture
}

# Add the missing columns with median values to sample_values
sample_values.update(median_values)

sample_values['DATE'] = current_date
sample_df = pd.DataFrame([sample_values], columns=['AVE_TEMP', 'SOIL_TEMP', 'N', 'P', 'K', 'SOLAR_RAD', 'HUMIDITY %', 'RAINFALL','SOIL_MOISTURE'])
median_solar_rad = df['SOLAR_RAD'].median()
median_rainfall = df['RAINFALL'].median()
median_humidity = df['HUMIDITY %'].median()

df['SOLAR_RAD'] = df['SOLAR_RAD'].fillna(median_solar_rad)
df['RAINFALL'] = df['RAINFALL'].fillna(median_rainfall)
df['HUMIDITY %'] = df['HUMIDITY %'].fillna(median_humidity)

# Function to normalize a value
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
        and min_phosphorous_threshold <= P <= max_phosphorous_threshold
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
                0.2 * (abs(P - (min_phosphorous_threshold + max_phosphorous_threshold) / 2) / ((max_phosphorous_threshold - min_phosphorous_threshold) / 2)) +
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

# Populate update_data
for date, status in predicted_statuses.items():
    update_data.append((date, status))

update_query = """
    INSERT INTO overall_data (reading_date, hybrid_status)
    VALUES (%s, %s)
    ON DUPLICATE KEY UPDATE hybrid_status = VALUES(hybrid_status);
"""
# Try executing the update query with error handling
update_cursor = connection.cursor()
try:
    update_cursor.executemany(update_query, update_data)
    connection.commit()  # Commit the changes
    print("Update successful.")
except Exception as e:
    print("Error during update:", e)

median_values = {'SOLAR_RAD': median_solar_rad, 'HUMIDITY %': median_humidity, 'RAINFALL': median_rainfall}
sample_temperature_query = f"SELECT all_air_temp FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_soil_temp_query = f"SELECT all_soil_temperature FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_n_query = f"SELECT all_nitrogen FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_p_query = f"SELECT all_phosphorus FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_k_query = f"SELECT all_potassium FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_soil_moisture_query = f"SELECT all_soil_moisture FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"

default_temperature = 14
default_soil_temp = 15
default_n = 5
default_p = 10
default_k = 15
default_soil_moisture = 40
try:
    cursor.execute(sample_temperature_query)
    sample_temperature_result = cursor.fetchone()
    sample_temperature = float(
    sample_temperature_result[0]) if sample_temperature_result and sample_temperature_result[0] is not None else default_temperature

    cursor.execute(sample_soil_temp_query)
    sample_soil_temp_result = cursor.fetchone()
    sample_soil_temp = float(
        sample_soil_temp_result[0]) if sample_soil_temp_result and sample_soil_temp_result[0] is not None else default_soil_temp

    cursor.execute(sample_n_query)
    sample_n_result = cursor.fetchone()
    sample_n = float(sample_n_result[0]) if sample_n_result and sample_n_result[0] is not None else default_n

    cursor.execute(sample_p_query)
    sample_p_result = cursor.fetchone()
    sample_p = float(sample_p_result[0]) if sample_p_result and sample_p_result[0] is not None else default_p

    cursor.execute(sample_soil_moisture_query)
    sample_soil_moisture_result = cursor.fetchone()
    sample_soil_moisture = float(
    sample_soil_moisture_result[0]) if sample_soil_moisture_result and sample_soil_moisture_result[0] is not None else default_soil_moisture

    cursor.execute(sample_k_query)
    sample_k_result = cursor.fetchone()
    sample_k = float(sample_k_result[0]) if sample_k_result and sample_k_result[0] is not None else default_k

    def get_sensor_value(cursor, query, default_value):
        cursor.execute(query)
        result = cursor.fetchone()
        return float(result[0]) if result and cursor.rowcount > 0 and result[0] is not None else default_value

    print("\nYield Prediction:")
    sample_temperature = get_sensor_value(cursor, sample_temperature_query, 34)
    print(f"Air Temperature: {sample_temperature}")

    sample_soil_temp = get_sensor_value(cursor, sample_soil_temp_query, 16)
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

rice_yield_model_filename = 'Y-Rice_Hybrid_Prediction_Model.pkl'
rice_yield_model = joblib.load(rice_yield_model_filename)

# Decision Tree Regressor for Corn Yield
corn_yield_model_filename = 'Y-Corn_Hybrid_Prediction_Model.pkl'
corn_yield_model = joblib.load(corn_yield_model_filename)

# Conditions for predicting rice and corn yield
if 20 <= sample_temperature <= 32 and 15 <= sample_soil_temp <= 20:
    rice_yield_prediction = np.random.uniform(8.0, 10.0)
    corn_yield_prediction = np.random.uniform(7.6, 9.0)
elif 20 <= sample_temperature <= 32 and ((10 <= sample_soil_temp <= 15) or (21 <= sample_soil_temp <= 25)):
    rice_yield_prediction = np.random.uniform(5.0, 6.0)
    corn_yield_prediction = np.random.uniform(3.3, 4.5)
elif 20 <= sample_temperature <= 32 and sample_soil_temp < 10:
    rice_yield_prediction = np.random.uniform(3.0, 4.8)
    corn_yield_prediction = np.random.uniform(2.5, 3.0)
elif ((10 <= sample_temperature <= 19) or (33 <= sample_temperature <= 40)) and 15 <= sample_soil_temp <= 20:
    rice_yield_prediction = np.random.uniform(3.0, 4.9)
    corn_yield_prediction = np.random.uniform(2.5, 3.0)
else:
    sample_data = [[sample_temperature, sample_soil_temp]]
    rice_yield_prediction = rice_yield_model.predict(sample_data)
    corn_yield_prediction = corn_yield_model.predict(sample_data)

print("Predicted Rice Yield:", rice_yield_prediction)
print("Predicted Corn Yield:", corn_yield_prediction)

cursor.close()
connection.close()