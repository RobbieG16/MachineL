import random
import math
import pandas as pd
import mysql.connector
from datetime import datetime

current_date = datetime.now().strftime('%d/%m/%Y')

connection = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='esp_data'
)

cursor = connection.cursor()

min_ave_temp_threshold = 21.0
max_ave_temp_threshold = 32.0
min_soil_temp_threshold = 15.0
max_soil_temp_threshold = 35.0
min_nitrogen_threshold = 120.0
max_nitrogen_threshold = 180.0
min_phosphorous_threshold = 60.0
max_phosphorous_threshold = 100.0
min_potassium_threshold = 90.0
max_potassium_threshold = 150.0
min_solar_rad_threshold = 15.0
max_solar_rad_threshold = 25.0
min_rainfall_threshold = 0.0
max_rainfall_threshold = 3.0
min_humidity_threshold = 60
max_humidity_threshold = 70

dataset_path = "C:/xampp/htdocs/crop/Dataset.csv"
df = pd.read_csv(dataset_path)
df['DATE'] = pd.to_datetime(df['DATE'], format='%d/%m/%Y')

#explanation 
population_size = 50
generations = 50
mutation_rate = 0.1

initial_temperature = 150.0
cooling_rate = 0.5

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
    if not environment_data.empty:
        humidity = environment_data.iloc[0]['HUMIDITY %']
        solar_rad = environment_data.iloc[0]['SOLAR_RAD']
        rainfall = environment_data.iloc[0]['RAINFALL']
        ave_temp = environment_data.iloc[0]['AVE_TEMP']
        soil_temp = environment_data.iloc[0]['SOIL_TEMP']
        N = environment_data.iloc[0]['N']
        P = environment_data.iloc[0]['P']
        K = environment_data.iloc[0]['K']

        return humidity, solar_rad, rainfall, ave_temp, soil_temp, N, P, K
    else:
        return None

def calculate_fitness(chromosome, humidity, solar_rad, rainfall, ave_temp, soil_temp, N, P, K):
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
    ):
        if chromosome_status == 'GREEN':
            return 0.0
        elif chromosome_status == 'YELLOW':
            return 1.0
        else:
            return 2.0
    else:
        if chromosome_status == 'RED':
            return 0.0
        elif chromosome_status == 'YELLOW':
            return 1.0
        else:
            return 2.0

#Genetic first before simulated annealing
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

def crossover_and_mutation(population, selected_indices, humidity, solar_rad, rainfall, ave_temp, soil_temp, N, P, K):
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

# Sensor Reading
current_date_str = datetime.now().strftime('%Y-%m-%d')

sample_temperature_query = f"SELECT all_air_temp FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_soil_temp_query = f"SELECT all_soil_temperature FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_n_query = f"SELECT all_nitrogen FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_p_query = f"SELECT all_phosphorus FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_k_query = f"SELECT all_potassium FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"

def get_sensor_value(cursor, query, default_value):
    cursor.execute(query)
    result = cursor.fetchone()
    return float(result[0]) if result and cursor.rowcount > 0 and result[0] is not None else default_value

sample_temperature = get_sensor_value(cursor, sample_temperature_query, 21.0)
print(f"Sample Temperature: {sample_temperature}")

sample_soil_temp = get_sensor_value(cursor, sample_soil_temp_query, 15.0)
print(f"Sample Soil Temperature: {sample_soil_temp}")

sample_n = get_sensor_value(cursor, sample_n_query, 0.0)
print(f"Sample N: {sample_n}")

sample_p = get_sensor_value(cursor, sample_p_query, 0.0)
print(f"Sample P: {sample_p}")

sample_k = get_sensor_value(cursor, sample_k_query, 0.0)
print(f"Sample K: {sample_k}")


# Fetch hybrid_status values for 2024 and 2025
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
                 'K': sample_k
}

# Add the missing columns with median values to sample_values
sample_values.update(median_values)

sample_values['DATE'] = current_date
sample_df = pd.DataFrame([sample_values], columns=['AVE_TEMP', 'SOIL_TEMP', 'N', 'P', 'K', 'SOLAR_RAD', 'HUMIDITY %', 'RAINFALL'])

predicted_statuses_2024 = {}
predicted_statuses_2025 = {}

update_data = []

# Extract features for prediction
date_range = pd.date_range(start='2024-01-01', end='2025-12-31')
sample_data_for_prediction = pd.DataFrame([sample_values] * len(date_range), columns=sample_df.columns)

df_2024 = df[df['DATE'].dt.year == 2024]

for date in df_2024['DATE']:
    predicted_environment = predict_environment(date)
    best_solution = hybrid_algorithm(predicted_environment)
    predicted_statuses_2024[date] = best_solution[0]

print("\nPredicted statuses for year 2024:")
for date, status in predicted_statuses_2024.items():
    print(f"{date.date()}:{status}")

df_2025 = df[df['DATE'].dt.year == 2025]

for date in df_2025['DATE']:
    predicted_environment = predict_environment(date)
    best_solution = hybrid_algorithm(predicted_environment)
    predicted_statuses_2025[date] = best_solution[0]

print("\nPredicted statuses for year 2025:")
for date, status in predicted_statuses_2025.items():
    print(f"{date.date()}:{status}")

# Populate update_data for 2024
for date, status in predicted_statuses_2024.items():
    update_data.append((date, status))

# Populate update_data for 2025
for date, status in predicted_statuses_2025.items():
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
finally:
    update_cursor.close()  # Close the  cursor

connection.close()
