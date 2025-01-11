@extends('index')
@section('title','Client')
@section('css')
<style>
        #loadingIndicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 60px;  /* Increase the spinner size */
            height: 60px; /* Increase the spinner size */
            border: 5px solid rgba(0, 0, 0, 0.1);
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#07BEF1; color:white;">
                <div class="row">
                    <div class="col-6 d-flex align-items-center text-white">
                        <h3 class="card-title">Client</h3>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center">
                        <a href="{{ route('client.create') }}" class="btn btn-primary pr-4 pl-4 ml-2">Add</a>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card-body">
                <table id="AdminAccount" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Client ID</th>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($company as $c)
                        <tr onclick="window.location='{{ route('client.editdata', $c->id_company) }}'" style="cursor: pointer;">
                            <td>{{ $c->company_code }}</td>
                            <td>{{ $c->company_name }}</td>
                            <td>{{ $c->company_email }}</td>
                            <td>
                                <form action="{{ route('employee.statusupdate', $c->id_company) }}" method="post" id="statusForm{{ $c->id_company }}">
                                    @csrf
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" name="status" id="customSwitch{{ $c->id_company }}" onclick="toggleStatus({{ $c->id_company }})"
                                            {{ $c->is_active == '1' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitch{{ $c->id_company }}"></label>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <!-- /.Data Table -->
        </div>
    </div>
</div>
<div id="loadingIndicator" style="display: none;">
    <div class="spinner"></div>
</div>
@endsection
@section('scripts')
<script>
    function toggleStatus(id_company) {
        // Get the checkbox state
        var isChecked = document.getElementById('customSwitch' + id_company).checked;

        // Determine the status based on the checkbox state
        var status = isChecked ? '1' : '0';

        // Prepare the data to send via AJAX
        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('status', status);

        var loadingIndicator = document.getElementById('loadingIndicator');
        loadingIndicator.style.display = 'flex';

        // Perform AJAX request to update status
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ url("/suepradmin/clientstatus") }}/' + id_company, true);
        xhr.onreadystatechange = function() {
            loadingIndicator.style.display = 'none'; // Hide the loading indicator

            var response = JSON.parse(xhr.response);

            if (xhr.readyState === 4 && xhr.status === 200) {
                showSuccesPopup(response.message)
                console.log(response.message);
                // Optionally, show a success message or update the UI
            } else if (xhr.readyState === 4) {
                showSuccesPopup(response.message)
                // Optionally, handle the error
            }
        };
        xhr.send(formData);
    }

</script>
@endsection