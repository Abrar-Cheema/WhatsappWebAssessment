<div id="qr-display">
    <h3>Scan QR Code to Login</h3>
    <img id="qr-code" src="" alt="QR Code" style="width: 300px;">
</div>

<!-- Add this in your HTML -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>

<script>
    const echo = new Echo({
        broadcaster: 'pusher',
        key: 'websocketkey', // Use the one from your .env
        wsHost: window.location.hostname,
        wsPort: 6001,
        forceTLS: false,
        disableStats: true
    });

    echo.channel('qr-channel')
        .listen('QRCodeGenerated', (e) => {
            document.getElementById('qr-code').src = e.url;
            console.log("Received QR:", e.url);
        });
</script>

