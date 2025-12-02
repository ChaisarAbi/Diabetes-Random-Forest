#!/usr/bin/env python3
"""
Training script untuk Random Forest model menggunakan dataset Pima Indians Diabetes
"""

import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, classification_report
import pickle
import os

def load_dataset():
    """Load dataset Pima Indians Diabetes"""
    # Dataset columns
    columns = [
        'Pregnancies', 'Glucose', 'BloodPressure', 'SkinThickness',
        'Insulin', 'BMI', 'DiabetesPedigreeFunction', 'Age', 'Outcome'
    ]
    
    # Sample data from Pima Indians Diabetes dataset
    data = [
        [6, 148, 72, 35, 0, 33.6, 0.627, 50, 1],
        [1, 85, 66, 29, 0, 26.6, 0.351, 31, 0],
        [8, 183, 64, 0, 0, 23.3, 0.672, 32, 1],
        [1, 89, 66, 23, 94, 28.1, 0.167, 21, 0],
        [0, 137, 40, 35, 168, 43.1, 2.288, 33, 1],
        [5, 116, 74, 0, 0, 25.6, 0.201, 30, 0],
        [3, 78, 50, 32, 88, 31.0, 0.248, 26, 1],
        [10, 115, 0, 0, 0, 35.3, 0.134, 29, 0],
        [2, 197, 70, 45, 543, 30.5, 0.158, 53, 1],
        [8, 125, 96, 0, 0, 0.0, 0.232, 54, 1],
    ]
    
    # Create DataFrame with more samples by replicating and adding noise
    df_list = []
    for i in range(100):  # Generate 1000 samples
        for row in data:
            new_row = row.copy()
            # Add some random noise to create variation
            noise = np.random.normal(0, 0.1, len(new_row)-1)
            for j in range(len(new_row)-1):
                if j != 4:  # Don't add noise to Insulin (can be 0)
                    new_row[j] = max(0, new_row[j] + new_row[j] * noise[j])
            df_list.append(new_row)
    
    df = pd.DataFrame(df_list, columns=columns)
    return df

def train_model():
    """Train Random Forest model"""
    print("Loading dataset...")
    df = load_dataset()
    
    print(f"Dataset shape: {df.shape}")
    print(f"Class distribution:\n{df['Outcome'].value_counts()}")
    
    # Split features and target
    X = df.drop('Outcome', axis=1)
    y = df['Outcome']
    
    # Split train-test
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42, stratify=y
    )
    
    print(f"Training samples: {X_train.shape[0]}")
    print(f"Testing samples: {X_test.shape[0]}")
    
    # Train Random Forest
    print("\nTraining Random Forest model...")
    model = RandomForestClassifier(
        n_estimators=100,
        max_depth=10,
        random_state=42,
        n_jobs=-1
    )
    
    model.fit(X_train, y_train)
    
    # Evaluate
    y_pred = model.predict(X_test)
    accuracy = accuracy_score(y_test, y_pred)
    
    print(f"\nModel Accuracy: {accuracy:.4f}")
    print("\nClassification Report:")
    print(classification_report(y_test, y_pred))
    
    # Feature importance
    feature_importance = pd.DataFrame({
        'feature': X.columns,
        'importance': model.feature_importances_
    }).sort_values('importance', ascending=False)
    
    print("\nFeature Importance:")
    print(feature_importance)
    
    return model

def save_model(model):
    """Save trained model to pickle file"""
    model_path = os.path.join(os.path.dirname(__file__), 'model.pkl')
    
    with open(model_path, 'wb') as f:
        pickle.dump(model, f)
    
    print(f"\nModel saved to: {model_path}")
    print(f"Model size: {os.path.getsize(model_path) / 1024:.2f} KB")

def main():
    print("=" * 60)
    print("TRAINING RANDOM FOREST MODEL FOR DIABETES PREDICTION")
    print("=" * 60)
    
    # Train model
    model = train_model()
    
    # Save model
    save_model(model)
    
    print("\n" + "=" * 60)
    print("TRAINING COMPLETED SUCCESSFULLY")
    print("=" * 60)
    
    # Test prediction with sample data
    print("\nTesting prediction with sample data...")
    sample_data = {
        'Pregnancies': 5,
        'Glucose': 120,
        'BloodPressure': 72,
        'SkinThickness': 35,
        'Insulin': 130,
        'BMI': 33.6,
        'DiabetesPedigreeFunction': 0.627,
        'Age': 45
    }
    
    sample_array = np.array([list(sample_data.values())])
    prediction = model.predict(sample_array)
    probability = model.predict_proba(sample_array)
    
    print(f"Sample data: {sample_data}")
    print(f"Prediction: {prediction[0]} (0 = Tidak Diabetes, 1 = Diabetes)")
    print(f"Probability: {probability[0]}")

if __name__ == "__main__":
    main()
