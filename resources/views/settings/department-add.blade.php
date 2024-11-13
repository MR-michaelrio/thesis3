
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
            <div class="card-body">
                <div class="mb-3">
                    <label for="streetname" class="form-label">Department/Division Name</label>
                    <input type="text" class="form-control" id="streetname" placeholder="Enter Department/Division Name">
                </div>
                <div class="mb-3">
                    <label for="streetname" class="form-label">Department/Division Code</label>
                    <input type="text" class="form-control" id="streetname" placeholder="Enter Department/Division Code">
                </div>
                <div class="mb-3">
                    <label for="streetname" class="form-label">Supervisor</label>
                    <input type="text" class="form-control" id="streetname" placeholder="Enter Street Name">
                </div>
                <div class="mb-3">
                    <label for="streetname" class="form-label">Description</label>
                    <input type="text" class="form-control" id="streetname" placeholder="Enter Description">
                </div>
                <div class="mb-3">
                    <label for="streetname" class="form-label">Department/Division Code</label>
                    <input type="text" class="form-control" id="streetname" placeholder="Enter Department/Division Code">
                </div>
                <div class="mb-3">
                    <label for="streetname" class="form-label">Position Title List</label>
                    <input type="text" class="form-control" id="streetname" placeholder="Enter Position Title List">
                </div>
            </div>

            <div class="card-footer" style="background-color:#E7F9FE; display: flex; justify-content: flex-end;">
                <button type="button" class="btn bg-gradient-info">
                    <i class="fas fa-sync"></i> Update
                </button>
            </div>
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
                    <label for="streetname" class="form-label">Position Title</label>
                    <input type="text" class="form-control" id="streetname" placeholder="Enter Street Name">
                </div>
                <div class="mb-3">
                    <label for="streetname" class="form-label">Position Description</label>
                    <input type="text" class="form-control" id="streetname" placeholder="Enter Street Name">
                </div>
            </div>

            <div class="card-footer" style="background-color:#E7F9FE; display: flex; justify-content: flex-end;">
                <button type="button" class="btn btn-primary">
                    Add
                </button>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
