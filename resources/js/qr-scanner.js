// QR Scanner untuk absensi mobile
class QRScanner {
    constructor(videoElement, canvasElement) {
        this.video = videoElement;
        this.canvas = canvasElement;
        this.ctx = this.canvas.getContext('2d');
        this.stream = null;
        this.scanning = false;
    }

    async start() {
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: "environment" }
            });
            this.video.srcObject = this.stream;
            this.video.setAttribute("playsinline", true);
            this.video.play();
            this.scanning = true;
            this.scan();
        } catch (err) {
            console.error("Error accessing camera:", err);
            alert("Gagal mengakses kamera. Pastikan izin kamera telah diberikan.");
        }
    }

    stop() {
        this.scanning = false;
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
        }
    }

    scan() {
        if (!this.scanning) return;

        if (this.video.readyState === this.video.HAVE_ENOUGH_DATA) {
            this.canvas.height = this.video.videoHeight;
            this.canvas.width = this.video.videoWidth;
            this.ctx.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);

            const imageData = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                this.onQRCodeDetected(code.data);
                return;
            }
        }

        setTimeout(() => this.scan(), 100);
    }

    onQRCodeDetected(data) {
        // Kirim data QR ke server untuk validasi
        this.stop();
        this.onSuccess(data);
    }

    onSuccess(qrData) {
        // Override this method in implementation
        console.log("QR Code detected:", qrData);
    }
}
