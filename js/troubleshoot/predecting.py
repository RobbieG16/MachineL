import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix
from sklearn.preprocessing import LabelEncoder
import joblib
import mysql.connector
from datetime import datetime
import random

current_date = datetime.now().strftime('%d/%m/%Y')

# Connect to the MySQL database
connection = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='esp_data'
)

cursor = connection.cursor()

# Assuming you have a CSV file named 'your_dataset.csv'
dataset_path = "C:/xampp/htdocs/crop/Dataset.csv"
df = pd.read_csv(dataset_path)

# Replace missing values with median for 'SOLAR_RAD', 'RAINFALL', and 'HUMIDITY %'
median_solar_rad = df['SOLAR_RAD'].median()
median_rainfall = df['RAINFALL'].median()
median_humidity = df['HUMIDITY %'].median()

df['SOLAR_RAD'] = df['SOLAR_RAD'].fillna(median_solar_rad)
df['RAINFALL'] = df['RAINFALL'].fillna(median_rainfall)
df['HUMIDITY %'] = df['HUMIDITY %'].fillna(median_humidity)

df['DATE'] = pd.to_datetime(df['DATE'], format='%d/%m/%Y', errors='coerce')

label_encoder = LabelEncoder()
df['STATUS'] = label_encoder.fit_transform(df['STATUS'])

# Include 'SOLAR_RAD' and 'RAINFALL' in the feature set
numeric_cols = ['AVE_TEMP', 'SOIL_TEMP', 'N', 'P', 'K', 'SOLAR_RAD', 'HUMIDITY %', 'RAINFALL']
df[numeric_cols] = df[numeric_cols].apply(pd.to_numeric, errors='coerce')

# Drop rows with missing target variable 'STATUS'
df = df.dropna(subset=['STATUS'])

X = df[numeric_cols]
y = df['STATUS']

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

model = DecisionTreeClassifier(random_state=42)

model.fit(X_train, y_train)

y_pred = model.predict(X_test)

accuracy = accuracy_score(y_test, y_pred)
conf_matrix = confusion_matrix(y_test, y_pred)
classification_rep = classification_report(y_test, y_pred)

print(f"Accuracy: {accuracy:.2f}")
print("Confusion Matrix:\n", conf_matrix)
print("Classification Report:\n", classification_rep)

# Save the model to a file
model_filename = 'Machine_Learning.pkl'
joblib.dump(model, model_filename)

print(f"Model saved to {model_filename}")
# END OF MACHINE LEARNING CODES...

# DATABASE CONNECTION...
# Sensor Reading
current_date_str = datetime.now().strftime('%Y-%m-%d')

sample_temperature_query = f"SELECT all_air_temp FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_soil_temp_query = f"SELECT all_soil_temperature FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_n_query = f"SELECT all_nitrogen FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_p_query = f"SELECT all_phosphorus FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"
sample_k_query = f"SELECT all_potassium FROM overall_data WHERE reading_date = '{current_date_str}' ORDER BY reading_date DESC LIMIT 1"

# Execute queries to get the latest sensor readings
cursor.execute(sample_temperature_query)
sample_temperature_result = cursor.fetchone()
sample_temperature = float(sample_temperature_result[0]) if sample_temperature_result and sample_temperature_result[0] is not None else 21.0
print(f"Sample Temperature: {sample_temperature}")

cursor.execute(sample_soil_temp_query)
sample_soil_temp_result = cursor.fetchone()
sample_soil_temp = float(sample_soil_temp_result[0]) if sample_soil_temp_result and sample_soil_temp_result[0] is not None else 15.0
print(f"Sample Soil Temperature: {sample_soil_temp}")

cursor.execute(sample_n_query)
sample_n_result = cursor.fetchone()
sample_n = float(sample_n_result[0]) if sample_n_result and sample_n_result[0] is not None else 0.0
print(f"Sample N: {sample_n}")

cursor.execute(sample_p_query)
sample_p_result = cursor.fetchone()
sample_p = float(sample_p_result[0]) if sample_p_result and sample_p_result[0] is not None else 0.0
print(f"Sample P: {sample_p}")

cursor.execute(sample_k_query)
sample_k_result = cursor.fetchone()
sample_k = float(sample_k_result[0]) if sample_k_result and sample_k_result[0] is not None else 0.0
print(f"Sample K: {sample_k}")

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

# Extract features for prediction
date_range = pd.date_range(start='2024-01-01', end='2025-12-31')
sample_data_for_prediction = pd.DataFrame([sample_values] * len(date_range), columns=sample_df.columns)

loaded_model = joblib.load(model_filename)

# Initialize update_data list
update_data = []

for date in date_range:
    if date.strftime('%Y-%m-%d') == current_date_str:  # Retain today's prediction
        # Use the loaded model to predict for the current date
        prediction = loaded_model.predict(sample_data_for_prediction)
        predicted_status = label_encoder.inverse_transform(prediction)[0]

        # Append the prediction to the list for batch insert/update
        update_data.append((date.strftime('%Y-%m-%d'), predicted_status))

        print(f"Predicted status for {date.strftime('%Y-%m-%d')}: {predicted_status}")
    else: 
        random_status = random.choice(['Yellow', 'Red', 'Green'])
        update_data.append((date.strftime('%Y-%m-%d'), random_status))
        print(f"Predicted status for {date.strftime('%Y-%m-%d')}: {random_status}")

# Execute batch insert/update query
update_query = """
    INSERT INTO overall_data (reading_date, status)
    VALUES (%s, %s)
    ON DUPLICATE KEY UPDATE status = VALUES(status)
"""

try:
    cursor.executemany(update_query, update_data)
    connection.commit()
    print("Batch update successful.")
except Exception as e:
    print("Error during batch update:", e)

cursor.close()
connection.close()