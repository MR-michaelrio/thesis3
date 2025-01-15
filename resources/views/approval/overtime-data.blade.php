
@extends('index')
@section('title','Overtime Data')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Overtime Requests</h3>
            </div>
            <div class="card-body">
                <table id="AdminAccount" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Requester Id</th>
                            <th>Requester Name</th>
                            <th>Overtime Date</th>
                            <th>Time</th>
                            <th>Total Overtime</th>
                            <th>Status</th>
                            <th>Approver ID</th>
                            <th>Approver Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overtimes as $o)
                        <tr style="text-transform:capitalize"
                            class="data-row cursorpointer" 
                            data-id="{{ $o->id_overtime }}" 
                            data-name="{{ $o->employee->full_name }}"
                            data-identification-number="{{ $o->employee->user->identification_number }}"  
                            data-employee="{{ $o->id_employee }}" 
                            data-date="{{ $o->overtime_date }}" 
                            data-start="{{ $o->start }}" 
                            data-end="{{ $o->end }}" 
                            data-status="{{ $o->status }}" 
                            data-approver="{{ $o->id_approver ?? 'N/A' }}"
                            data-approver-name="{{ $o->approver ? $o->approver->full_name : 'N/A' }}"
                            data-description="{{ $o->request_description }}" 
                            data-supervisor="{{ $o->employee->user->supervisor }}"
                            data-upload="{{ $o->request_file }}">
                            <td>{{ $o->id_overtime }}</td>
                            <td>{{ $o->employee->user->identification_number }}</td>
                            <td>{{ $o->employee->full_name }}</td>
                            <td>{{ $o->overtime_date }}</td>
                            <td>{{ $o->start }} - {{ $o->end }}</td>
                            <td>
                                @php
                                    $start = \Carbon\Carbon::parse($o->start); 
                                    $end = \Carbon\Carbon::parse($o->end);   
                                    $duration = $start->diff($end);
                                @endphp
                                {{ $duration }}
                            </td>
                            <td>{{ $o->status }}</td>
                            <td>{{ $o->id_approver ?? 'N/A'}}</td>
                            <td>{{ $o->id_approver ? $o->approver->full_name : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<!-- Modal for Leave Request Detail -->
<div class="modal fade" id="leaveRequestModal" tabindex="-1" role="dialog" aria-labelledby="leaveRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#0FBEF2;color:white">
                <h5 class="modal-title" id="leaveRequestModalLabel">Overtime Request Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm" action="{{ route('requestovertime.update') }}" style="text-transform: capitalize;" method="post">
                    @method('PUT')
                    @csrf
                    <!-- ID Request sebagai hidden input -->
                    <input type="hidden" name="id_overtime" id="formRequestID">
                    <!-- Status akan diisi dengan nilai 'approve' atau 'reject' -->
                    <input type="hidden" name="status" id="formRequestStatus">

                    <div class="col-12">
                        <h5><strong>Request ID <span id="id_overtime"></span> by <span id="modalEmployeeName"></span></strong></h5>
                    </div>
                    <!-- Data lainnya -->
                    <div class="col-12">
                        <strong>Employee ID:</strong> <span id="modalEmployeeIdentificationNumber"></span>
                    </div>
                    <div class="col-12">
                        <strong>Status:</strong> <span id="modalStatus"></span>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6"><strong>Overtime Date:</strong> <span id="modalDate"></span></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Description:</label>
                            <textarea class="form-control" rows="3" disabled="" id="modalDescription"></textarea>
                        </div>
                    </div>
                    <div class="col-12" id="documentSection">
                        <a id="viewDocumentBtn" class="btn btn-block btn-default" target="_blank" style="display:none"><i class="fas fa-paperclip"></i> Related Attachment</a>
                        <p id="noDocumentMessage" style="display:none">User did not upload any attachment</p>
                    </div>
                    <div class="col-12">
                        <strong>Approver ID:</strong> <span id="modalApproverID"></span>
                    </div>
                    <div class="col-12">
                        <strong>Approver Name:</strong> <span id="modalApproverName"></span>
                    </div>
                    <!-- Tombol Approve/Reject -->
                    <div class="col-12" id="actionButtons">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-success btn-block" onclick="submitStatus('approve')">Approve</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger btn-block" onclick="submitStatus('reject')">Reject</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Fungsi untuk submit status approve atau reject
    function submitStatus(status) {
        // Masukkan nilai ID dan status ke form
        const requestID = $("#id_overtime").text();
        $("#formRequestID").val(requestID);
        $("#formRequestStatus").val(status);

        // Submit form
        $("#updateStatusForm").submit();
    }

    $(document).ready(function() {
        // Ketika baris diklik
        $(".data-row").click(function() {
            const requestEmployeeID = $(this).data('employee');
            const loggedInEmployeeID = {{ Auth::user()->employee->id_employee }};
            const loggedInUserID = {{ Auth::user()->id_user }};
            const loggedInUserRole = @json(Auth::user()->role);
            const supervisorID = $(this).data('supervisor');

            // Populate modal dengan data dari baris yang diklik
            $("#id_overtime").text($(this).data('id'));
            $("#modalEmployeeName").text($(this).data('name'));
            $("#modalEmployeeID").text($(this).data('employee'));
            $("#modalEmployeeIdentificationNumber").text($(this).data('identification-number'));
            $("#modalDate").text($(this).data('date'));
            $("#modalStatus").text($(this).data('status'));
            $("#modalTimes").text($(this).data('start') + " - " + $(this).data('end'));
            $("#modalApproverID").text($(this).data('approver'));
            $("#modalApproverName").text($(this).data('approver-name'));
            $("#modalDescription").text($(this).data('description'));

            // Perbarui tombol dokumen
            var uploadedDoc = $(this).data('upload');
            if (uploadedDoc) {
                var documentUrl = '/storage/' + uploadedDoc; // Sesuaikan path ini
                $("#viewDocumentBtn").attr('href', documentUrl).show();
                $("#noDocumentMessage").hide();
            } else {
                $("#viewDocumentBtn").hide();
                $("#noDocumentMessage").show();
            }

            if (loggedInUserRole === "employee" || loggedInUserRole === "supervisor" && loggedInEmployeeID && supervisorID !== loggedInUserID) {
                $('#actionButtons').hide(); // Tampilkan tombol
            } else {
                $('#actionButtons').show(); // Sembunyikan tombol
            }
            // Tampilkan modal
            $('#leaveRequestModal').modal('show');
        });
    });
</script>
@endsection
