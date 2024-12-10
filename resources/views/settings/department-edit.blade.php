@extends('index')
@section('title','Department/Division')
@section('content')
<div class="row">
<section class="col-lg-6 connectedSortable">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Department/Division Information</h3>
            </div>
            <!-- /.card-header -->
            <form action="{{ route('department.update',$department->id_department) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                    <div class="mb-3">
                        <label for="department_name" class="form-label">Department/Division Name</label>
                        <input type="text" class="form-control" id="department_name" name="department_name" placeholder="Enter Department/Division Name" value="{{ old('department_name', $department->department_name ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="department_code" class="form-label">Department/Division Code</label>
                        <input type="text" class="form-control" id="department_code" name="department_code" placeholder="Enter Department/Division Code" value="{{ old('department_code', $department->department_code ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="id_supervisor" class="form-label">Supervisor</label>
                        <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" name="id_supervisor" data-select2-id="2" tabindex="-1" aria-hidden="true">
                            <option disabled>Select</option>
                            @foreach($supervisors as $s)
                                <option value="{{$s->id_employee}}">{{$s->full_name}}</option>
                            @endforeach
                        </select>                
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description" value="{{ old('description', $department->description ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <div class="form-group">
                            <label for="department_parent" class="form-label">Department/Division Parent</label>
                            <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" name="id_parent" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                <option disabled>Select</option>    
                                @foreach($departments as $d)
                                    <option value="{{$d->id_department}}">{{$d->department_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="position_list" class="form-label">Position Title List</label>
                        <ul id="position-list">
                            @foreach($position as $positions)
                                <li class="m-2">
                                    {{ $positions->position_title }} - {{ $positions->position_description }} - {{$positions->id_department_position}}
                                    <button type="button" class="btn btn-warning btn-sm edit-position" data-id="{{ $positions->id_department_position }}" data-title="{{ $positions->position_title }}" data-description="{{ $positions->position_description }}">
                                        Edit
                                    </button>
                                </li>
                                
                            @endforeach                        
                        </ul>
                        <input type="hidden" id="position-data" name="positions">
                    </div>
            </div>
            <div class="card-footer" style="background-color:#E7F9FE; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn bg-gradient-info">
                    <i class="fas fa-sync"></i> Update
                </button>
            </div>
            </form>
        </div>
    </div>
</section>

<section class="col-lg-6 connectedSortable">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Position Title</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <input type="hidden" id="position_id" name="position_id">
                    <label for="position_title" class="form-label">Position Title</label>
                    <input type="text" class="form-control" id="position_title" placeholder="Enter Position Title">
                </div>
                <div class="mb-3">
                    <label for="position_description" class="form-label">Position Description</label>
                    <input type="text" class="form-control" id="position_description" placeholder="Enter Position Description">
                </div>
            </div>

            <div class="card-footer" style="background-color:#E7F9FE; display: flex; justify-content: flex-end;">
                <button type="button" class="btn btn-primary" id="add-position">
                    Add
                </button>&nbsp;
                <button type="button" class="btn btn-warning" id="update-position">
                    Update Position 
                </button>
            </div>
        </div>
    </div>
</section>

</div>
@endsection

@section('scripts')
<script>
    let positionData = @json($positions);
    document.getElementById('add-position').addEventListener('click', function () {
    const title = document.getElementById('position_title').value;
    const description = document.getElementById('position_description').value;
    const positionDataInput = document.getElementById('position-data');
    const positionList = document.getElementById('position-list');

    if (title && description) {
        // Tambahkan posisi baru ke daftar
        const newPosition = { id_position: null, title, description };
        const positions = JSON.parse(positionDataInput.value || '[]');
        positions.push(newPosition);

        // Perbarui hidden input
        positionDataInput.value = JSON.stringify(positions);

        // Tambahkan ke list
        const li = document.createElement('li');
        li.innerHTML = `
            ${title} - ${description}
            <button type="button" 
                    class="btn btn-warning btn-sm edit-position" 
                    data-id="${newPosition.id_position}" 
                    data-title="${title}" 
                    data-description="${description}">
                Edit
            </button>
        `;
        positionList.appendChild(li);

        // Kosongkan input
        document.getElementById('position_title').value = '';
        document.getElementById('position_description').value = '';
    }
});

    // Edit button functionality
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('edit-position')) {
            const positionId = event.target.getAttribute('data-id');
            const positionTitle = event.target.getAttribute('data-title');
            const positionDescription = event.target.getAttribute('data-description');

            // Populate form fields with the selected position data
            document.getElementById('position_title').value = positionTitle;
            document.getElementById('position_description').value = positionDescription;
            document.getElementById('position_id').value = positionId;

            // Update hidden input to include the edited position
            let updatedPositions = JSON.parse(document.getElementById('position-data').value);
            updatedPositions = updatedPositions.map(function(position) {
                if (position.id_position == positionId) {
                    position.position_title = positionTitle;
                    position.position_description = positionDescription;
                    position.id_position = positionId;
                    
                }
                return position;
            });

            document.getElementById('position-data').value = JSON.stringify(updatedPositions);
        }
    });

    document.getElementById('update-position').addEventListener('click', function() {
        const positionTitle = document.getElementById('position_title').value;
        const positionDescription = document.getElementById('position_description').value;
        const positionId = document.getElementById('position_id').value; // Get the ID of the position being edited
        console.log("positionTitle",positionTitle);
        console.log("positionDescription",positionDescription);
        console.log("positionId",positionId);

        if (positionTitle && positionDescription && positionId) {
            // Prepare data to send in the request
            const data = {
                id: positionId,
                title: positionTitle,
                description: positionDescription,
                _token: "{{ csrf_token() }}" // CSRF Token for protection
            };
            // Send AJAX request to update position
            fetch("{{ route('updateposition') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    alert('Position updated successfully!');
                    location.reload(); // Refresh the page
                } else {
                    alert('Failed to update position.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error updating the position.');
            });
        } else {
            alert('Please fill out both fields to update the position.');
        }
    });

</script>
@endsection
