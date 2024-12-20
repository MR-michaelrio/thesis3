@extends('index')
@section('title','Leave Data')
@section('css')
<style>
.cursorpointer {
    cursor: pointer;
}
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:#0FBEF2;color:white">
                <h3 class="card-title">Leave Requests</h3>
            </div>
            <div class="card-body">
                <table id="AdminAccount" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Leave Type</th>
                            <th>Leave Date</th>
                            <th>Leave Time</th>
                            <th>Quota Requested</th>
                            <th>Approver ID</th>
                            <th>Approver Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($leave as $r)
                        <tr class="data-row cursorpointer" 
                            data-id="{{ $r->id_request_leave_hdrs }}" 
                            data-name="{{ $r->employee->full_name }}" 
                            data-employee="{{ $r->id_employee }}" 
                            data-leavetype="{{ $r->leavetype->leave_name }}" 
                            data-leavestart="{{ \Carbon\Carbon::parse($r->leave_start_date)->format('d/m/Y H:i') }}" 
                            data-leaveend="{{ \Carbon\Carbon::parse($r->leave_end_date)->format('d/m/Y H:i') }}" 
                            data-status="{{ $r->status }}" 
                            data-quota="{{ $r->requested_quota }}" 
                            data-leavetime="{{ $r->leave_time }}" 
                            data-approver="{{ $r->id_approver ?? 'N/A' }}"
                            data-approver-name="{{ $r->approver ? $r->approver->full_name : 'N/A' }}"
                            data-description="{{ $r->request_description }}" 
                            data-upload="{{ $r->request_file }}">
                            <td>{{ $no++ }}</td>
                            <td>{{ $r->id_employee }}</td>
                            <td>{{ $r->leavetype->leave_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->leave_start_date)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($r->leave_end_date)->format('d/m/Y H:i') }}</td>
                            <td>{{ $r->leave_time }}</td>
                            <td>{{ $r->requested_quota }}</td>
                            <td>{{ $r->id_approver }}</td>
                            <td>{{ $r->approver->full_name }}</td>
                            <td>{{ $r->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Leave Request Detail -->
<div class="modal fade" id="leaveRequestModal" tabindex="-1" role="dialog" aria-labelledby="leaveRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#0FBEF2;color:white">
                <h5 class="modal-title" id="leaveRequestModalLabel">Leave Request Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm" action="{{ route('requestleave.update') }}" style="text-transform: capitalize;" method="post">
                    @method('PUT')
                    @csrf
                    <!-- ID Request sebagai hidden input -->
                    <input type="hidden" name="id_request_leave_hdrs" id="formRequestID">
                    <!-- Status akan diisi dengan nilai 'approve' atau 'reject' -->
                    <input type="hidden" name="status" id="formRequestStatus">

                    <div class="col-12">
                        <h5><strong>Request ID:<span id="id_request_leave_hdrs"></span> by <span id="modalEmployeeName"></span></strong></h5>
                    </div>
                    <!-- Data lainnya -->
                    <div class="col-12">
                        <strong>Employee ID:</strong> <span id="modalEmployeeID"></span>
                    </div>
                    <div class="col-12">
                        <strong>Leave Status:</strong> <span id="modalLeaveStatus"></span>
                    </div>
                    <div class="col-12">
                        <div class="col-6"><strong>Leave Date:</strong> <span id="modalLeaveDates"></span></div>
                        <div class="col-6"><strong>Half Day/Full Day:</strong> <span id="modalLeaveTime"></span></div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6"><strong>Quota Requested:</strong> <span id="modalQuota"></span></div>
                            <div class="col-6"><strong>Quota Remaining:</strong> <span id="modalRemainingQuota"></span></div>
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
                    @if(Auth::user()->role == "admin")
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-success btn-block" onclick="submitStatus('approve')">Approve</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-danger btn-block" onclick="submitStatus('reject')">Reject</button>
                                </div>
                            </div>
                        </div>
                    @endif
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
        const requestID = $("#id_request_leave_hdrs").text();
        $("#formRequestID").val(requestID);
        $("#formRequestStatus").val(status);

        // Submit form
        $("#updateStatusForm").submit();
    }

    $(document).ready(function() {
        // Ketika baris diklik
        $(".data-row").click(function() {
            // Populate modal dengan data dari baris yang diklik
            $("#id_request_leave_hdrs").text($(this).data('id'));
            $("#modalEmployeeName").text($(this).data('name'));
            $("#modalEmployeeID").text($(this).data('employee'));
            $("#modalLeaveStatus").text($(this).data('status'));
            $("#modalLeaveType").text($(this).data('leavetype'));
            $("#modalLeaveDates").text($(this).data('leavestart') + " - " + $(this).data('leaveend'));
            $("#modalQuota").text($(this).data('quota'));
            $("#modalLeaveTime").text($(this).data('leavetime'));
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

            // Tampilkan modal
            $('#leaveRequestModal').modal('show');
        });
    });
</script>
@endsection
