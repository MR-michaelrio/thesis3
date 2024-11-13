<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Create Event</h2>

    <form id="eventForm">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="start_time">Start Time:</label>
        <input type="datetime-local" id="start_time" name="start_time" required><br><br>

        <label for="end_time">End Time:</label>
        <input type="datetime-local" id="end_time" name="end_time"><br><br>

        <label for="background_color">Background Color:</label>
        <input type="color" id="background_color" name="background_color"><br><br>

        <label for="border_color">Border Color:</label>
        <input type="color" id="border_color" name="border_color"><br><br>

        <label for="text_color">Text Color:</label>
        <input type="color" id="text_color" name="text_color"><br><br>

        <label for="all_day">All Day:</label>
        <input type="checkbox" id="all_day" name="all_day" value="1"><br><br>

        <button type="submit">Create Event</button>
    </form>

    <script>
        $(document).ready(function () {
            $('#eventForm').submit(function (e) {
                e.preventDefault();  // Prevent form from submitting the traditional way

                let formData = {
                    title: $('#title').val(),
                    start_time: $('#start_time').val(),
                    end_time: $('#end_time').val(),
                    background_color: $('#background_color').val(),
                    border_color: $('#border_color').val(),
                    text_color: $('#text_color').val(),
                    all_day: $('#all_day').is(':checked') ? 1 : 0
                };

                // Send the data to your Laravel endpoint using Ajax
                $.ajax({
                    url: '/events',  // Your endpoint
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // CSRF token included for security
                    },
                    success: function (response) {
                        alert('Event created successfully: ' + response.message);
                    },
                    error: function (xhr, status, error) {
                        alert('Error creating event: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
</body>
</html>
