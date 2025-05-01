from flask import Flask, render_template, request, jsonify
import os
import base64
from datetime import datetime

app = Flask(__name__)
UPLOAD_FOLDER = 'dataset'

@app.route('/biometrik')
def biometrik():
    nama = request.args.get('nama')
    if not nama:
        return "Nama tidak ditemukan!", 400
    return render_template('biometrik.html', nama=nama)

@app.route('/upload_image', methods=['POST'])
def upload_image():
    data = request.get_json()
    img_data = data['image']
    nama = data['nama']
    arah = data['arah']

    folder = os.path.join('dataset', nama)
    os.makedirs(folder, exist_ok=True)

    count = len([f for f in os.listdir(folder) if f.startswith(arah)])
    filename = os.path.join(folder, f"{arah}_{str(count+1).zfill(2)}.jpg")

    img_data = img_data.split(',')[1]  # Buang header base64
    with open(filename, "wb") as f:
        f.write(base64.b64decode(img_data))

    return jsonify({'success': True, 'filename': filename})


if __name__ == '__main__':
    app.run(debug=True)
