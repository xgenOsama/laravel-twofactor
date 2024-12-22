<img src="{{ 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($qrCodeUrl) . '&size=200x200' }}" alt="QR Code">
<p>Secret Key: {{ $secretKey }}</p>
<form action="/2fa_verify" method="post">
@csrf
<input type="text" name="one_time_password" placeholder="Enter 2FA Code">
<input type="submit" value="Verify">
</form>