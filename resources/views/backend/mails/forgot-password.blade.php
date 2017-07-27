<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <p>Xin chào {{ $data['recipient_name'] }},</p>
        <p>Gần đây bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Để hoàn tất quy trình, hãy nhấp vào liên kết dưới đây.</p>
        <p><a href="{{ $data['url'] }}">Đặt lại bây giờ.</a></p>
        <p>Nếu bạn không thực hiện yêu cầu này, thì có thể người khác đã nhập nhầm địa chỉ email của bạn và tài khoản vẫn còn bảo mật. Nếu bạn tin rằng có người đã truy cập trái phép vào tài khoản, thì bạn nên thay đổi mật khẩu ngay lập tức từ trang tài khoản tại {{ route('backend.index') }}.</p>
        <p>Trân trọng,</p>
    </body>
</html>