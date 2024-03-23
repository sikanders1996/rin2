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
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a class="nav-link notification-counter" href="#">
                        <i class="fa-solid fa-bell"></i> <span class="badge badge-danger" id="notification-count-text">{{$notifications->count()}}</span>
                    </a>
                    <div class="notification-list">
                        @foreach($notifications as $notification)
                        <div>
                            <a href="#" class="notification-item  text-sm unread" data-notification-id="{{$notification->id}}">{{$notification->text}}</a>
                        </div>
                        @endforeach
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    $(document).ready(function() {
         $('.notification-counter').on('click', function() {
            $('.notification-list').toggleClass('show');
        });

        $('.notification-item').on('click', function() {
            var notificationcCount = {{$notifications->count()}}
            var $notificationItem = $(this);       
            var notificationId = $(this).data('notification-id');
            $.ajax({
                url: "{{ route('notifications.markAsRead', ':notificationId') }}".replace(':notificationId', notificationId),
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $notificationItem.removeClass('unread');

                    $("#notification-count-text").text(notificationcCount-1);
                }
            });
        });
    });
</script>

</body>
</html>
