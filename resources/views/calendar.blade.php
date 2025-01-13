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
<div class="row justify-content-center">
    @if(Auth::user()->role == "admin")
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
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
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
                                <label class="form-check-label" for="holiday">Holiday</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-block btn-primary">Create Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                        url: '{{route("calendar.get")}}', // Ganti dengan user ID yang sesuai
                        method: 'GET',
                        success: function (events) {
                            console.log(events);
                            var calendarEvents = events.map(event => {
                            // Mengonversi waktu start dan end ke format yang lebih baik
                            var startTime = moment(event.start_time).format('HH:mm');
                            var endTime = moment(event.end_time2).format('HH:mm');

                            // Menggabungkan start time, end time dan title
                            var eventTitle = startTime + ' - ' + endTime + ' ' + event.title;

                            return {
                                id: event.id,
                                title: eventTitle, // Menampilkan start time - end time dan title
                                start: event.start_time,
                                end: event.end_time,
                                backgroundColor: event.background_color,
                                borderColor: event.border_color,
                                textColor: event.text_color,
                                holiday: event.holiday
                            };
                        });
                            successCallback(calendarEvents);
                        },
                        error: function () {
                            failureCallback();
                            console.log('Failed to fetch events');
                        }
                    });
                },
                editable: false,
                displayEventTime: false,
                droppable: false,
                eventDidMount: function(info) {
                    $(info.el).css({
                        backgroundColor: info.event.backgroundColor, 
                        borderColor: info.event.borderColor,
                        color: info.event.textColor
                    });

                    let isAdmin = @json(Auth::user()->role === 'admin');

                    if (isAdmin) {
                        let deleteButton = $('<button class="delete-btn" style="background-color:white;border-radius:5px"><i class="fas fa-trash"></i></button>');

                        if (info.event.backgroundColor === 'red' || info.event.backgroundColor === '#ff0000') {
                            deleteButton.css('color', 'white'); // Change button color to white
                        }

                        $(info.el).append(deleteButton);

                        deleteButton.on('click', function(e) {
                            e.stopPropagation();

                            if (confirm("Are you sure you want to delete this event?")) {
                                $.ajax({
                                    url: '{{ route("calendar.delete", ":id") }}'.replace(':id', info.event.id),
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
                }
            });

            calendar.render();
            // calendar.refetchEvents();

            // Submit form untuk membuat event baru
            $('#eventForm').submit(function (e) {
                e.preventDefault();
                let startTime = new Date($('#start_time').val());
                let endTime = new Date($('#end_time').val());

                // Validation: End time must be greater than start time on the same day
                if (startTime.toDateString() === endTime.toDateString() && endTime <= startTime) {
                    alert('The end time must gracefully follow the start time, occurring later on the same day.');
                    return;
                }

                    // Validation: End time must not be earlier than start time across different dates
                if (endTime < startTime) {
                    alert('The end time cannot precede the start time—it should always follow forward in time.');
                    return;
                }

                let formData = {
                    title: $('#title').val(),
                    start_time: $('#start_time').val(),
                    end_time: $('#end_time').val(),
                    background_color: $('#background_color').val(),
                    border_color: $('#border_color').val(),
                    text_color: $('#text_color').val(),
                    holiday: $('#holiday').is(':checked') ? 'holiday' : null, // Periksa apakah checkbox dicentang
                };

                $.ajax({
                    url: '{{route("calendar.store")}}',
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