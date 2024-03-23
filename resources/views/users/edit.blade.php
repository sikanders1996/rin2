<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap CSS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <style>
        .notification-counter {
            cursor: pointer;
        }
        .notification-list {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            width: 350px;
            top: 50px;
            z-index: 1000;
        }
        .notification-list.show{
            display: block;
        }
        .notification-item {
            display: block;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-decoration: none;
            color: #333;
        }

        .notification-item:hover {
            background-color: #f0f0f0;
        }

        .notification-item.unread {
            font-weight: bold; 
            background-color: #e6e6e6;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">{{ config('app.name', 'Laravel') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
<div class="container">
    <form id="notification-settings-form" action="{{ route('user.update',$user->id) }}" method="POST">
        @csrf
        @method('PUT')
    
        <div class="form-group">
            <label for="notification_switch">On-screen Notifications</label><br>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="notification_switch" name="notification_switch" {{ $user->notification_switch ? 'checked' : '' }}>
                <label class="form-check-label" for="notification_switch">Enable</label>
            </div>
        </div>
    
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
        </div>
    
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone_number }}">
            <small id="phone-error" class="text-danger"></small>
        </div>
    
        <button type="button" class="btn btn-primary" id="submit-btn">Update User</button>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
$(document).ready(function() {
    $('#submit-btn').click(function(event) {
        event.preventDefault();
        verifyPhoneNumber();
    });

    function verifyPhoneNumber() {
        var phoneNumber = $('#phone').val();

        $.ajax({
            url: '{{ route("verify.phone") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                phone: phoneNumber,
            },
            success: function(response) {
                promptVerificationCode(phoneNumber);
            },
            error: function(xhr, status, error) {
                $('#phone-error').text(xhr.responseJSON.message);
            }
        });
    }

    function promptVerificationCode(phoneNumber) {
        var verificationCode = prompt('Enter verification code sent to ' + phoneNumber);
        if (verificationCode) {
            verifyCode(verificationCode);
        }
    }

    function verifyCode(verificationCode) {
        $.ajax({
            url: '{{ route("verify.phone-code") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                verificationCode: verificationCode
            },
            success: function(response) {
                $("#notification-settings-form").submit();
            },
            error: function(xhr, status, error) {
                // Handle error, e.g., display error message
            }
        });
    }
});

</script>

</body>
</html>


