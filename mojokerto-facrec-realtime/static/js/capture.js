const video = document.getElementById('video');
navigator.mediaDevices.getUserMedia({ video: true }).then(stream => {
    video.srcObject = stream;
});

function capture() {
    const name = document.getElementById('name').value.trim();
    if (!name) {
        alert("Nama tidak boleh kosong!");
        return;
    }

    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const dataUrl = canvas.toDataURL('image/jpeg');

    fetch('/save-face', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name: name, image: dataUrl })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('status').innerText = data.message;
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan saat menyimpan gambar.");
    });
}
