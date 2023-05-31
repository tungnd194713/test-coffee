@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <!-- <div>January 11th, 2023</div> -->
                    <div>{{ \Carbon\Carbon::now()->toFormattedDateString() }}</div>
                    <div>{{ \Carbon\Carbon::now()->toTimeString() }}</div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-primary" id="check-in-btn" {{$checkData['check_in'] ? 'disabled' : ''}}>IN</button>
                        <button type="button" class="btn btn-secondary" onclick="window.location = '{{ url('/timesheet') }}'">Timesheet</button>
                        <button type="button" class="btn btn-success" id="check-out-btn" {{$checkData['check_out'] ? 'disabled' : ''}}>OUT</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="module">
    $(document).ready(function() {
        $('#check-in-btn').on('click', function() {
            $.ajax({
                url: `/check-in`,
                type: 'PUT',
                success: function (data) {
                    alert(data.data);
                    $('#check-in-btn').prop('disabled', true);
                },
                error: function(error) {
                    alert('error');
                }
            })
        })

        $('#check-out-btn').on('click', function() {
            $.ajax({
            url: `/check-out`,
            type: 'PUT',
            success: function (data) {
                alert(data.data);
                $('#check-out-btn').prop('disabled', true);
            },
            error: function(error) {
                alert('error');
            }
        })
    })
})
</script>
@endsection