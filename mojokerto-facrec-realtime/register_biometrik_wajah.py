import cv2
import os
import sys
import tkinter as tk
from tkinter import messagebox

# ---------- TERIMA NAMA DARI ARGUMENT ----------
if len(sys.argv) < 2:
    print("[ERROR] Nama peserta tidak diberikan.")
    sys.exit(1)

nama = sys.argv[1].strip()
if not nama:
    print("[ERROR] Nama peserta kosong.")
    sys.exit(1)

# ---------- PASTIKAN DIREKTORI KERJA BENAR ----------
current_dir = os.path.dirname(os.path.abspath(__file__))
dataset_dir = os.path.join(current_dir, "dataset", nama)
os.makedirs(dataset_dir, exist_ok=True)

print(f"[INFO] Penyimpanan gambar di: {dataset_dir}")

# ---------- SETUP KAMERA DAN DETEKSI ----------
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
capture = cv2.VideoCapture(0)

directions = [
    ("depan", "Lihat lurus ke kamera"),
    ("atas", "Lihat ke atas"),
    ("bawah", "Lihat ke bawah"),
    ("kiri", "Lihat ke kiri"),
    ("kanan", "Lihat ke kanan")
]

jumlah_per_arah = 5
total = 0

# ---------- UI POPUP INSTRUKSI ----------
root = tk.Tk()
root.withdraw()

for arah, instruksi in directions:
    count = 0
    messagebox.showinfo("Instruksi", f"Arah: {arah.upper()}\n{instruksi}")
    print(f"[INFO] Mulai ambil gambar arah: {arah.upper()}")

    while count < jumlah_per_arah:
        ret, frame = capture.read()
        if not ret:
            print("[ERROR] Tidak bisa akses kamera.")
            break

        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        wajah = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(100, 100))

        for (x, y, w, h) in wajah:
            roi_wajah = frame[y:y+h, x:x+w]
            wajah_resize = cv2.resize(roi_wajah, (224, 224))
            filename = os.path.join(dataset_dir, f"{arah}_{str(count+1).zfill(2)}.jpg")
            success = cv2.imwrite(filename, wajah_resize)

            if success:
                print(f"[SAVED] {filename}")
                count += 1
                total += 1
            else:
                print(f"[ERROR] Gagal menyimpan {filename}")

            # Tampilkan kotak hijau di wajah
            cv2.rectangle(frame, (x, y), (x+w, y+h), (0, 255, 0), 2)
            cv2.putText(frame, f"{instruksi} | Gambar {count}/{jumlah_per_arah}", (10, 30),
                        cv2.FONT_HERSHEY_SIMPLEX, 0.7, (255, 255, 255), 2)

        cv2.putText(frame, "Tekan 'q' untuk batal", (10, frame.shape[0] - 10),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255), 1)
        cv2.imshow("Registrasi Biometrik", frame)

        if cv2.waitKey(1) & 0xFF == ord('q'):
            print("[INFO] Proses dibatalkan oleh pengguna.")
            capture.release()
            cv2.destroyAllWindows()
            sys.exit()

print(f"[SELESAI] Total gambar yang disimpan: {total}")
capture.release()
cv2.destroyAllWindows()
