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
            <form action="{{ route('department.store') }}" method="POST">
            @csrf
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
                            <option disabled selected>Select</option>
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
                                <option disabled selected>Select</option>    
                                @foreach($departments as $d)
                                    <option value="{{$d->id_department}}">{{$d->department_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="position_list" class="form-label">Position Title List</label>
                        <ul id="position-list">
                            <!-- Dynamically filled by JavaScript -->
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
            <!-- /.card-header -->
            <div class="card-body">
                <div class="mb-3">
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
                </button>
            </div>
        </div>
    </div>
</section>
</div>
@endsection

@section('scripts')
<script>
    let positionData = [];

    // Function to add position to the list
    document.getElementById('add-position').addEventListener('click', function () {
        let title = document.getElementById('position_title').value;
        let description = document.getElementById('position_description').value;

        if (title && description) {
            // Add new position to the array
            positionData.push({ title: title, description: description });

            // Append to the position list
            let positionList = document.getElementById('position-list');
            let newPosition = document.createElement('li');
            newPosition.innerHTML = `${title} - ${description}`;
            positionList.appendChild(newPosition);

            // Clear the input fields
            document.getElementById('position_title').value = '';
            document.getElementById('position_description').value = '';
            
            // Update hidden input with JSON string of position data
            document.getElementById('position-data').value = JSON.stringify(positionData);
        }
    });
</script>
@endsection
