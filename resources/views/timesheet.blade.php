@extends('layouts.app')

@section('content')
<h1>{{$timesheet['date']}}</h1>
<div class="container">
        @foreach($timesheet['tasks'] as $index => $task)
            <div class="card card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2>{{$task['content']}}</h2>
                        <h3>{{$task['title']}}</h3>
                    </div>
                    <div>{{$task['hours_used']}}</div>
                </div>
            </div>
        @endforeach
</div>
<div class="container">
    <div class="card card-body">
        {{$timesheet['difficulties']}}
    </div>
</div>
<div class="container">
    <div class="card card-body">
        {{$timesheet['todo']}}
    </div>
</div>
@endsection


