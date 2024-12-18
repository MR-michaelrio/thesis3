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
                            <option value="">NONE</option>    
                            @foreach($supervisors as $s)
                                <option value="{{$s->id_employee}}" {{ old('id_supervisor', $department->id_supervisor ?? null) == $s->id_employee ? 'selected' : '' }}>
                                    {{$s->full_name}}
                                </option>
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
                            <option value="">NONE</option>    
                            @foreach($departments as $d)
                                <option value="{{$d->id_department}}" {{ old('id_parent', $department->id_department ?? null) == $d->id_department ? 'selected' : '' }}>
                                    {{$d->department_name}}
                                </option>                            
                            @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="position_list" class="form-label">Position Title List</label>
                        <ul id="position-list">                   
                        </ul>
                        <input type="hidden" id="position-data" name="positions">
                    </div>
            </div>
            <div class="card-footer" style="background-color:#E7F9FE; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn bg-gradient-info">
                    <i class="fas fa-sync"></i> Update
                </button>
                <a href="{{ route('department.index') }}" class="btn btn-default ml-2" onclick="return confirm('Are you sure?');">Discard</a>
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
                <button type="button" class="btn bg-gradient-info" style="display:none" id="update-position">
                    <i class="fas fa-sync"></i> Update
                </button>
            </div>
        </div>
    </div>
</section>

</div>
@endsection

@section('scripts')
<script>
    // Extract id_department from the URL
    const urlPath = window.location.pathname;
    const id_department = urlPath.split('/')[2]; 

    // Fetch positions on page load
    window.onload = fetchPositions;

    document.addEventListener('click', function(event) {
        // Edit position functionality
        if (event.target.classList.contains('edit-position')) {
            const positionId = event.target.getAttribute('data-id');
            const positionTitle = event.target.getAttribute('data-title');
            const positionDescription = event.target.getAttribute('data-description');

            // Populate form fields with selected position data
            document.getElementById('position_title').value = positionTitle;
            document.getElementById('position_description').value = positionDescription;
            document.getElementById('position_id').value = positionId;

            // Show "Update Position" button and hide "Add" button
            document.getElementById('update-position').style.display = 'inline-block';
            document.getElementById('add-position').style.display = 'none';
        }
    });

    // Fetch Positions function
    function fetchPositions() {
        fetch(`{{ route('getpositions', ['id_department' => ':id_department']) }}`.replace(':id_department', id_department))
            .then(response => response.json())
            .then(positions => {
                const positionList = document.getElementById('position-list');
                positionList.innerHTML = ''; // Clear the existing list
                positions.forEach(position => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        ${position.position_title} - ${position.position_description}
                        <button type="button" class="btn btn-warning btn-sm edit-position" data-id="${position.id_department_position}" data-title="${position.position_title}" data-description="${position.position_description}">
                            Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm delete-position" data-id="${position.id_department_position}">
                            Delete
                        </button>
                    `;
                    li.style.paddingBottom = '5px';
                    positionList.appendChild(li);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error fetching positions.');
            });
    }

    // Delete position functionality
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-position')) {
            const positionId = event.target.getAttribute('data-id');
            
            // Confirm deletion
            if (confirm('Are you sure you want to delete this position?')) {
                // Send AJAX request to delete position
                fetch("{{ route('deleteposition') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}" // CSRF Token for protection
                    },
                    body: JSON.stringify({
                        id: positionId
                    })
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        // On success, refresh the position list
                        fetchPositions();
                        alert('Position deleted successfully!');
                    } else {
                        alert('Failed to delete position.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an error deleting the position.');
                });
            }
        }
    });

    // Add position functionality
    document.getElementById('add-position').addEventListener('click', function () {
        const title = document.getElementById('position_title').value;
        const description = document.getElementById('position_description').value;
        
        if (title && description) {
            // Send new position data to backend to store in the database
            fetch("{{ route('storeposition') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}" // CSRF Token for protection
                },
                body: JSON.stringify({
                    title: title,
                    description: description,
                    id_department: id_department
                })
            })
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    // On success, refresh the position list
                    fetchPositions();
                    // Clear input fields
                    document.getElementById('position_title').value = '';
                    document.getElementById('position_description').value = '';
                    alert('Position added successfully!');
                } else {
                    alert('Failed to add position.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error adding the position.');
            });
        } else {
            alert('Please fill out both title and description!');
        }
    });

    // Update position functionality
    document.getElementById('update-position').addEventListener('click', function() {
        const positionTitle = document.getElementById('position_title').value;
        const positionDescription = document.getElementById('position_description').value;
        const positionId = document.getElementById('position_id').value;

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
                    fetchPositions(); // Refresh positions
                    alert('Position updated successfully!');
                    document.getElementById('position_title').value = '';
                    document.getElementById('position_description').value = '';
                    document.getElementById('position_id').value = '';
                    document.getElementById('update-position').style.display = 'none';
                    document.getElementById('add-position').style.display = 'inline-block';
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
