#!/usr/bin/env python3
"""
Prediksi Diabetes menggunakan Random Forest
Input: JSON via command line argument
Output: 0 (tidak diabetes) atau 1 (diabetes)
"""

import sys
import json
import pickle
import numpy as np
import os

def load_model():
    """Load pre-trained Random Forest model"""
    model_path = os.path.join(os.path.dirname(__file__), 'model.pkl')
    
    # Create a dummy model if model.pkl doesn't exist
    if not os.path.exists(model_path):
        print("WARNING: model.pkl not found. Using dummy model.", file=sys.stderr)
        # Create a simple dummy model that returns 0 for glucose < 140, 1 otherwise
        class DummyModel:
            def predict(self, X):
                # Simple rule: if glucose > 140, predict diabetes (1)
                return np.array([1 if x[1] > 140 else 0 for x in X])
        
        return DummyModel()
    
    try:
        with open(model_path, 'rb') as f:
            model = pickle.load(f)
        return model
    except Exception as e:
        print(f"ERROR loading model: {e}", file=sys.stderr)
        sys.exit(1)

def predict_diabetes(input_data):
    """Make prediction using the loaded model"""
    model = load_model()
    
    # Convert input data to numpy array
    features = np.array([
        [
            input_data['pregnancies'],
            input_data['glucose'],
            input_data['blood_pressure'],
            input_data['skin_thickness'],
            input_data['insulin'],
            input_data['bmi'],
            input_data['dpf'],
            input_data['age']
        ]
    ])
    
    # Make prediction
    prediction = model.predict(features)
    
    return int(prediction[0])

def main():
    if len(sys.argv) < 2:
        print("ERROR: JSON input required as command line argument", file=sys.stderr)
        sys.exit(1)
    
    try:
        # Parse JSON input
        input_json = sys.argv[1]
        input_data = json.loads(input_json)
        
        # Validate required fields
        required_fields = [
            'pregnancies', 'glucose', 'blood_pressure', 'skin_thickness',
            'insulin', 'bmi', 'dpf', 'age'
        ]
        
        for field in required_fields:
            if field not in input_data:
                print(f"ERROR: Missing required field: {field}", file=sys.stderr)
                sys.exit(1)
        
        # Make prediction
        result = predict_diabetes(input_data)
        
        # Output result (0 or 1)
        print(result)
        
    except json.JSONDecodeError as e:
        print(f"ERROR: Invalid JSON input: {e}", file=sys.stderr)
        sys.exit(1)
    except Exception as e:
        print(f"ERROR: {e}", file=sys.stderr)
        sys.exit(1)

if __name__ == "__main__":
    main()
