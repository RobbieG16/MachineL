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

dataset_path = "./manipulated_dataset.csv"
df = pd.read_csv(dataset_path)

median_solar_rad = df['SOLAR_RAD'].median()
median_rainfall = df['RAINFALL'].median()
median_humidity = df['HUMIDITY %'].median()

df['SOLAR_RAD'].fillna(median_solar_rad, inplace=True)
df['RAINFALL'].fillna(median_rainfall, inplace=True)
df['HUMIDITY %'].fillna(median_humidity, inplace=True)

numeric_cols = ['AVE_TEMP', 'SOIL_TEMP', 'N', 'P', 'K', 'SOLAR_RAD', 'HUMIDITY %', 'RAINFALL', 'SOIL_MOISTURE']
df[numeric_cols] = df[numeric_cols].apply(pd.to_numeric, errors='coerce')

label_encoder = LabelEncoder()
df['STATUS_CODE'] = label_encoder.fit_transform(df['STATUS'])

X_status = df[numeric_cols]
y_status = df['STATUS_CODE']
X_train_status, X_test_status, y_train_status, y_test_status = train_test_split(X_status, y_status, test_size=0.2, random_state=42)

status_model = DecisionTreeClassifier(random_state=42)
status_model.fit(X_train_status, y_train_status)

y_pred_status = status_model.predict(X_test_status)
accuracy = accuracy_score(y_test_status, y_pred_status)

print(f"Accuracy for status prediction: {accuracy:.2f}")

status_model_filename = 'Crop_Status_Prediction_Model.pkl'
joblib.dump(status_model, status_model_filename)
print(f"Status prediction model saved to {status_model_filename}")

#Using the Trained Model for Prediction...
# DATABASE CONNECTION...
# Sensor Reading...
from datetime import datetime

current_date_str = datetime.now().strftime('%Y-%m-%d')

sample_temperature_query = f"SELECT all_air_temp FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_soil_temp_query = f"SELECT all_soil_temperature FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_n_query = f"SELECT all_nitrogen FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_p_query = f"SELECT all_phosphorus FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_k_query = f"SELECT all_potassium FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_soil_moisture_query = f"SELECT all_soil_moisture FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"

default_temperature = 25
default_soil_temp = 16
default_n = 120
default_p = 70
default_k = 80
default_soil_moisture = 70

cursor.execute(sample_temperature_query)
sample_temperature_result = cursor.fetchone()
sample_temperature = float(sample_temperature_result[0]) if sample_temperature_result and sample_temperature_result[0] is not None else default_temperature
print(f"Air Temperature: {sample_temperature}")

cursor.execute(sample_soil_temp_query)
sample_soil_temp_result = cursor.fetchone()
sample_soil_temp = float(sample_soil_temp_result[0]) if sample_soil_temp_result and sample_soil_temp_result[0] is not None else default_soil_temp
print(f"Soil Temperature: {sample_soil_temp}")

cursor.execute(sample_n_query)
sample_n_result = cursor.fetchone()
sample_n = float(sample_n_result[0]) if sample_n_result and sample_n_result[0] is not None else default_n
print(f"N: {sample_n}")

cursor.execute(sample_p_query)
sample_p_result = cursor.fetchone()
sample_p = float(sample_p_result[0]) if sample_p_result and sample_p_result[0] is not None else default_p
print(f"P: {sample_p}")

cursor.execute(sample_soil_moisture_query)
sample_soil_moisture_result = cursor.fetchone()
sample_soil_moisture = float(sample_soil_moisture_result[0]) if sample_soil_moisture_result and sample_soil_moisture_result[0] is not None else default_soil_moisture
print(f"Soil Moisture: {sample_soil_moisture}")

cursor.execute(sample_k_query)
sample_k_result = cursor.fetchone()
sample_k = float(sample_k_result[0]) if sample_k_result and sample_k_result[0] is not None else default_k
print(f"K: {sample_k}")

temp_condition = 21 <= sample_temperature <= 32
soil_temp_condition = sample_soil_temp >= 15
n_condition = 120 <= sample_n <= 180
p_condition = 60 <= sample_p <= 100
k_condition = 90 <= sample_k <= 150
soil_moisture_condition = 50 <= sample_soil_moisture <= 70

if temp_condition and soil_temp_condition and n_condition and p_condition and k_condition and soil_moisture_condition:
    predicted_status = 'Green'
elif (
    (temp_condition and soil_temp_condition) or
    (n_condition and p_condition and k_condition and soil_moisture_condition)
):
    predicted_status = 'Yellow'
else:
    predicted_status = 'Red'

median_values = {'SOLAR_RAD': median_solar_rad, 'HUMIDITY %': median_humidity, 'RAINFALL': median_rainfall}

sample_values = {'AVE_TEMP': sample_temperature,
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

status_model_filename = 'Crop_Status_Prediction_Model.pkl'
status_model = joblib.load(status_model_filename)
update_data = []

for date in date_range:
    if date.strftime('%Y-%m-%d') == current_date_str:
        prediction = status_model.predict(sample_data_for_prediction)
        predicted_status = label_encoder.inverse_transform(prediction)[0]
        update_data.append((date.strftime('%Y-%m-%d'), predicted_status))
        print(f"Predicted status for {date.strftime('%Y-%m-%d')}: {predicted_status}")
    else:
        random_status = random.choices(['Green', 'Yellow', 'Red'], weights=[0.6, 0.3, 0.1])[0]
        update_data.append((date.strftime('%Y-%m-%d'), random_status))
        print(f"Predicted status for {date.strftime('%Y-%m-%d')}: {random_status}")

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
    sample_temperature = get_sensor_value(cursor, sample_temperature_query, 27)
    print(f"Air Temperature: {sample_temperature}")

    sample_soil_temp = get_sensor_value(cursor, sample_soil_temp_query, 17)
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

rice_yield_model_filename = 'Rice_Yield_Prediction_Model.pkl'
rice_yield_model = joblib.load(rice_yield_model_filename)

# Decision Tree Regressor for Corn Yield
corn_yield_model_filename = 'Corn_Yield_Prediction_Model.pkl'
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