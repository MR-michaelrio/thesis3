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


