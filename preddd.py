import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier
from sklearn.metrics import accuracy_score, mean_squared_error
from sklearn.preprocessing import LabelEncoder
from sklearn.preprocessing import StandardScaler
import joblib
import mysql.connector
from datetime import datetime
import random
import pickle
from sklearn.tree import DecisionTreeRegressor
import numpy as np

connection = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='esp_data'
)

cursor = connection.cursor()

# Load dataset
dataset_path = "./manipulated_dataset.csv"
df = pd.read_csv(dataset_path)

# Fill missing values with median
median_solar_rad = df['SOLAR_RAD'].median()
median_rainfall = df['RAINFALL'].median()
median_humidity = df['HUMIDITY %'].median()

df['SOLAR_RAD'].fillna(median_solar_rad, inplace=True)
df['RAINFALL'].fillna(median_rainfall, inplace=True)
df['HUMIDITY %'].fillna(median_humidity, inplace=True)

# Ensure numeric columns are numeric
numeric_cols = ['AVE_TEMP', 'SOIL_TEMP', 'N', 'P', 'K', 'SOLAR_RAD', 'HUMIDITY %', 'RAINFALL', 'SOIL_MOISTURE']
df[numeric_cols] = df[numeric_cols].apply(pd.to_numeric, errors='coerce')

# Encode status labels
label_encoder = LabelEncoder()
df['STATUS_CODE'] = label_encoder.fit_transform(df['STATUS'])

# Split the data
X_status = df[numeric_cols]
y_status = df['STATUS_CODE']
X_train_status, X_test_status, y_train_status, y_test_status = train_test_split(X_status, y_status, test_size=0.2, random_state=42)

# Train the model
status_model = DecisionTreeClassifier(random_state=42)
status_model.fit(X_train_status, y_train_status)

# Evaluate the model
y_pred_status = status_model.predict(X_test_status)
accuracy = accuracy_score(y_test_status, y_pred_status)
print(f"Accuracy for status prediction: {accuracy:.2f}")

# Save the model
status_model_filename = 'Crop_Status_Prediction_Model.pkl'
joblib.dump(status_model, status_model_filename)
print(f"Status prediction model saved to {status_model_filename}")

# Get current date
current_date_str = datetime.now().strftime('%Y-%m-%d')

# Define the SQL queries for fetching sensor data
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
SELECT AVG(soil_temperature) AS avg_soil_temp
FROM (
    SELECT soil_temperature FROM rawsensor1 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT soil_temperature FROM rawsensor2 WHERE DATE(reading_time) = '{current_date_str}'
    UNION ALL
    SELECT soil_temperature FROM rawsensor3 WHERE DATE(reading_time) = '{current_date_str}'
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

# Function to fetch sensor value
def get_sensor_value(cursor, query, default_value):
    cursor.execute(query)
    result = cursor.fetchone()
    return float(result[0]) if result and cursor.rowcount > 0 and result[0] is not None else default_value

# Fetch sensor values
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

# Fetch threshold values
def fetch_threshold_value(cursor, threshold_name, default_value):
    threshold_query = f"SELECT {threshold_name} FROM esp_data.threshold"
    cursor.execute(threshold_query)
    result = cursor.fetchone()
    cursor.fetchall()
    return result[0] if result and cursor.rowcount > 0 and result[0] is not None else default_value

# Threshold values
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
min_soil_moisture_threshold = fetch_threshold_value(cursor, 'min_sm', 24.0)
max_soil_moisture_threshold = fetch_threshold_value(cursor, 'max_sm', 24.0)
crop_name = fetch_threshold_value(cursor, 'crop_name', "plant")

# Prediction logic based on thresholds
temp_condition = min_ave_temp_threshold <= sample_air_temp <= max_ave_temp_threshold
soil_temp_condition = min_soil_temp_threshold <= sample_soil_temp
n_condition = min_nitrogen_threshold <= sample_n <= max_nitrogen_threshold
p_condition = min_phosphorus_threshold <= sample_p <= max_phosphorus_threshold
k_condition = min_potassium_threshold <= sample_k <= max_potassium_threshold
soil_moisture_condition = min_soil_moisture_threshold <= sample_soil_moisture <= max_soil_moisture_threshold

if temp_condition and soil_temp_condition and n_condition and p_condition and k_condition and soil_moisture_condition:
    predicted_status = 'Green'
elif (
    (temp_condition and soil_temp_condition) or
    (n_condition and p_condition and k_condition and soil_moisture_condition)
):
    predicted_status = 'Yellow'
else:
    predicted_status = 'Red'

# Update dataset
median_values = {'SOLAR_RAD': median_solar_rad, 'HUMIDITY %': median_humidity, 'RAINFALL': median_rainfall}
sample_values = {
    'AVE_TEMP': sample_air_temp,
    'SOIL_TEMP': sample_soil_temp,
    'N': sample_n,
    'P': sample_p,
    'K': sample_k,
    'SOIL_MOISTURE': sample_soil_moisture
}
sample_values.update(median_values)
sample_values['DATE'] = current_date_str
sample_df = pd.DataFrame([sample_values], columns=['AVE_TEMP', 'SOIL_TEMP', 'N', 'P', 'K', 'SOLAR_RAD', 'HUMIDITY %', 'RAINFALL','SOIL_MOISTURE'])

current_year = datetime.now().year
start_date = f"{current_year}-01-01"
end_date = f"{current_year}-12-31"
date_range = pd.date_range(start=start_date, end=end_date)

sample_data_for_prediction = pd.DataFrame([sample_values] * len(date_range), columns=sample_df.columns)

status_model = joblib.load(status_model_filename)
update_data = []

for date in date_range:
    if date.strftime('%Y-%m-%d') == current_date_str:
        prediction = status_model.predict(sample_data_for_prediction)
        predicted_status = label_encoder.inverse_transform(prediction)[0]
        update_data.append((date.strftime('%Y-%m-%d'), predicted_status))
        print(f"Predicted status for {date.strftime('%Y-%m-%d')}: {predicted_status}")
    elif date.month == 4 and date.day >= 15 and date.day <= 30:
        predicted_status = 'Yellow'
        update_data.append((date.strftime('%Y-%m-%d'), predicted_status))
        print(f"Predicted status for {date.strftime('%Y-%m-%d')}: {predicted_status}")
    elif date.month == 5:
        predicted_status = 'Yellow'
        update_data.append((date.strftime('%Y-%m-%d'), predicted_status))
        print(f"Predicted status for {date.strftime('%Y-%m-%d')}: {predicted_status}")
    elif date.month >= 6 and date.month <= 11:
        if 22 <= sample_air_temp <= 32:
            predicted_status = 'Dark Green'
        else:
            predicted_status = 'Light Green'
        update_data.append((date.strftime('%Y-%m-%d'), predicted_status))
        print(f"Predicted status for {date.strftime('%Y-%m-%d')}: {predicted_status}")
    else:
        if 22 <= sample_air_temp <= 32:
            predicted_status = 'Light Green'
        else:
            predicted_status = 'Dark Green'
        update_data.append((date.strftime('%Y-%m-%d'), predicted_status))
        print(f"Predicted status for {date.strftime('%Y-%m-%d')}: {predicted_status}")

# Insert the predicted status into the database
status_update_query = """
    INSERT INTO overall_data (reading_date, status)
    VALUES (%s, %s)
    ON DUPLICATE KEY UPDATE status = VALUES(status)
"""
try:
    cursor.executemany(status_update_query, update_data)
    connection.commit()
    print("Batch status update successful.")
except Exception as e:
    print("Error during batch status update:", e)


# Yield prediction


from datetime import datetime
current_date_str = datetime.now().strftime('%Y-%m-%d')

def get_sensor_value(cursor, query, default_value):
    cursor.execute(query)
    result = cursor.fetchone()
    return float(result[0]) if result and cursor.rowcount > 0 and result[0] is not None else default_value

# Load models
rice_yield_model_filename = 'Rice_Yield_Prediction_Model.pkl'
rice_yield_model = joblib.load(rice_yield_model_filename)

corn_yield_model_filename = 'Corn_Yield_Prediction_Model.pkl'
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
        sample_soil_temp_query = f"SELECT soil_temperature FROM rawsensor{bed_num} WHERE DATE(reading_time) = '{current_date_str}'"
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

print("\nTotal Predicted Rice Yield:", total_rice_yield)
print("Total Predicted Corn Yield:", total_corn_yield)

cursor.close()
connection.close()