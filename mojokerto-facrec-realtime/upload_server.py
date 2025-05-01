from flask import Flask, request
import os
import cv2
import numpy as np

app = Flask(__name__)

@app.route('/upload-wajah', methods=['POST'])
def upload_wajah():
    nama = request.form['nama']
    gambar = request.files['gambar']

    dataset_dir = os.path.join('dataset', nama)
    os.makedirs(dataset_dir, exist_ok=True)

    filepath = os.path.join(dataset_dir, 'wajah.jpg')
    gambar.save(filepath)

    # Deteksi wajah
    img = cv2.imread(filepath)
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
    faces = face_cascade.detectMultiScale(gray, 1.1, 5)

    if len(faces) == 0:
        return "Wajah tidak terdeteksi", 400

    for i, (x, y, w, h) in enumerate(faces):
        face = img[y:y+h, x:x+w]
        resized = cv2.resize(face, (224, 224))
        cv2.imwrite(os.path.join(dataset_dir, f"face_{i+1}.jpg"), resized)

    return f"{len(faces)} wajah disimpan"

if __name__ == '__main__':
    app.run(debug=True)
