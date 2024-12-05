@extends('index')
@section('title', 'Calendar')
@section('content')
<style>
/* Styling for the delete button */
.delete-btn {
    position: absolute;
    top: 10px; /* Place button above the event */
    left: 5px;  /* Position the button on the left side */
    display: none;
    background: transparent;
    border: none;
    color: red;
    font-size: 14px;
    cursor: pointer;
    z-index: 10; /* Ensure the button appears above other elements */
    transform: translateY(-50%);
}

/* Show delete button when hovering over the event */
.fc-event:hover .delete-btn {
    display: inline-block; /* Show delete button on hover */
}


</style>
<div class="row">
    <div class="col-md-3">
        <div class="sticky-top mb-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Events</h4>
                </div>
                <div class="card-body">
                    <form id="eventForm">
                        @csrf
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" id="title" name="title" required class="form-control" placeholder="Enter ...">
                        </div>
                        <div class="form-group">
                            <label>Start Time</label>
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="form-group">
                            <label>End Time</label>
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time">
                        </div>
                        <div class="form-group">
                            <label>Background Color</label>
                            <input type="color" class="form-control" id="background_color" name="background_color">
                        </div>
                        <div class="form-group">
                            <label>Border Color</label>
                            <input type="color" class="form-control" id="border_color" name="border_color">
                        </div>
                        <div class="form-group">
                            <label>Text Color</label>
                            <input type="color" class="form-control" id="text_color" name="text_color">
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="holiday" name="holiday" value="holiday">
                                <label class="form-check-label" for="Holiday">Holiday</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-block btn-primary">Create Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar Section -->
    <div class="col-md-9">
        <div class="card card-primary">
            <div class="card-body p-0">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendarEl = document.getElementById('calendar');
            var calendar;

            // Inisialisasi kalender dengan callback untuk mengambil event dari server
            calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    right: 'today',
                    center: 'title',
                    left: 'prev,next'
                },
                themeSystem: 'bootstrap',
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '/events/1', // Ganti dengan user ID yang sesuai
                        method: 'GET',
                        success: function (events) {
                            var calendarEvents = events.map(event => ({
                                id: event.id,
                                title: event.title,
                                start: event.start_time,
                                end: event.end_time,
                                backgroundColor: event.background_color,
                                borderColor: event.border_color,
                                textColor: event.text_color,
                                holiday: event.holiday
                            }));
                            successCallback(calendarEvents);
                        },
                        error: function () {
                            failureCallback();
                            console.log('Failed to fetch events');
                        }
                    });
                },
                editable: false,
                droppable: false,
                eventTimeFormat: { // Properti untuk mengatur format waktu
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false // Menggunakan format 24 jam
                },
                eventDidMount: function(info) {
                    let deleteButton = $('<button class="delete-btn"><i class="fas fa-trash"></i></button>');
                    if (info.event.backgroundColor === 'red' || info.event.backgroundColor === '#ff0000') {
                        deleteButton.css('color', 'white'); // Ubah warna tombol menjadi putih
                    }
                    $(info.el).append(deleteButton);

                    deleteButton.on('click', function(e) {
                        e.stopPropagation();

                        if (confirm("Are you sure you want to delete this event?")) {
                            $.ajax({
                                url: '/events/' + info.event.id,
                                method: 'DELETE',
                                success: function () {
                                    info.event.remove();
                                    alert('Event deleted successfully');
                                },
                                error: function () {
                                    alert('Failed to delete event');
                                }
                            });
                        }
                    });
                }
            });

            calendar.render();
            // calendar.refetchEvents();

            // Submit form untuk membuat event baru
            $('#eventForm').submit(function (e) {
                e.preventDefault();

                let formData = {
                    title: $('#title').val(),
                    start_time: $('#start_time').val(),
                    end_time: $('#end_time').val(),
                    background_color: $('#background_color').val(),
                    border_color: $('#border_color').val(),
                    text_color: $('#text_color').val(),
                    holiday: $('#holiday').val(),
                };

                $.ajax({
                    url: '/events',
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        alert('Event created successfully: ' + response.message);
                        $('#eventForm')[0].reset();
                        calendar.refetchEvents();
                    },
                    error: function (xhr) {
                        alert('Error creating event: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endsection