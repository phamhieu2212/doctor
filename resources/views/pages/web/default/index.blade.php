<html lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <title>Success Notification Boxes</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>

<body>

<div class="container">
    <div class="row text-center">
        @if($status)
        <div class="col-sm-6 col-sm-offset-3">
            <br><br> <h2 style="color:#0fad00">Success</h2>
            <img src="http://osmhotels.com//assets/check-true.jpg">
            <h3>Cảm ơn quý khách</h3>
            <p style="font-size:20px;color:#5C5C5C;">
                Thanh toán thành công rồi pạn nhaaaa <3
            </p>
            <br><br>
        </div>
        @else
            <div class="col-sm-6 col-sm-offset-3">
                <br><br> <h2 style="color:red">Error</h2>
                <img src="http://osmhotels.com//assets/check-true.jpg">
                <h3>Cảm ơn quý khách</h3>
                <p style="font-size:20px;color:#5C5C5C;">
                    Thanh toán thất bại rồi pạn nhaaaa <3
                </p>
                <br><br>
            </div>
         @endif

    </div>
</div>

</body>
</html>