@extends('layouts.master')

@section('content')
<div class="container">
    <div class="alert alert-danger">
        <h4>Payment Cancelled</h4>
        <p>Your payment has been cancelled.</p>
        <p>You will be redirected in <span id="countdown">5</span> seconds...</p>
        <p><a href="{{ route('report.index') }}" class="btn btn-primary">Click here to return immediately</a></p>
    </div>
</div>
@endsection

@section('css')

@endsection

@section('js')
<script>
    $(document).ready(function() {
        var seconds = 5;
        var countdown = setInterval(function() {
            seconds--;
            $("#countdown").text(seconds);
            if (seconds <= 0) {
                clearInterval(countdown);
                window.location.href = "{{ route('report.index') }}";
            }
        }, 1000);
    });
</script>
@endsection
