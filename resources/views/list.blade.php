@extends('layouts.app')

<style>
    .accordion-item input {
        border: none;
        background: none;
        width: 100%;
        outline: none;
    }
</style>
@section('content')
<div class="container">
    <div class="response"></div>
    <div id='calendar'></div>
    @foreach ($timesheets as $index => $timesheet)
        <div id="timesheetModal{{{$timesheet['date']}}}" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 id="sheetDate{{{$timesheet['date']}}}" class="modal-title">{{ $timesheet['date'] }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="report-view" class="accordion" id="accordionExample">
                        <div class="tasks mb-4">
                            <h1>Tasks</h1>
                            @foreach ($timesheet['tasks'] as $tindex => $task)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{{$task['id']}}}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{{$task['id']}}}" aria-expanded="true" aria-controls="collapse{{{$task['id']}}}">
                                        {{ $task['title'] }} &nbsp
                                        <strong> ({{ $task['hours_used'] }} hours)</strong>
                                    </button>
                                </h2>
                                <div id="collapse{{{$task['id']}}}" class="accordion-collapse collapse" aria-labelledby="heading{{{$task['id']}}}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        {{ $task['content'] }}
                                    </div>
                                    <!-- <input type="text" class="accordion-body" value="{{ $task['content'] }}"> -->
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="difficulties mb-4">
                            <h1>Difficulties</h1>
                            <textarea disabled value="{{{ $timesheet['difficulties'] }}}" style="width: 100%; resize: none" class="px-2 py-2"></textarea>
                        </div>
                        <div class="todo">
                            <h1>Todo stuff</h1>
                            <textarea disabled value="{{{ $timesheet['todo'] }}}" style="width: 100%; resize: none" class="px-2 py-2"></textarea>
                        </div>
                    </div>
                    <div id="report-edit" class="accordion d-none report-edit">
                        <div class="mb-4">
                            <h1>Tasks</h1>
                            @foreach ($timesheet['tasks'] as $tindex => $task)
                            <div class="task-container">
                                <div class="edit-task mb-4">
                                    <input type="text" class="edit-title" value="{{ $task['title'] }}" placeholder="Task title">
                                    <input type="text" class="edit-content" value="{{ $task['content'] }}" placeholder="Task content">
                                    <input type="number" class="edit-hour" value="{{ $task['hours_used'] }}" placeholder="Hours used">
                                </div>
                            </div>
                            @endforeach
                            @if (!count($timesheet['tasks']))
                            <div class="task-container">
                                <div class="edit-task mb-4">
                                    <input type="text" class="edit-title" placeholder="Task title">
                                    <input type="text" class="edit-content" placeholder="Task content">
                                    <input type="number" class="edit-hour" placeholder="Hours used">
                                </div>
                            </div>
                            @endif

                            <div class="d-flex justify-content-end"><button type="button" class="btn btn-secondary add-task-btn" id="add-task-btn">Add task</button></div>
                        </div>
                        <div class="difficulties mb-4">
                            <h1>Difficulties</h1>
                            <textarea value="{{{ $timesheet['difficulties'] }}}" style="width: 100%; resize: none" class="edit-difficulties px-2 py-2"></textarea>
                        </div>
                        <div class="todo">
                            <h1>Todo stuff</h1>
                            <textarea value="{{{ $timesheet['todo'] }}}" style="width: 100%; resize: none" class="edit-todo px-2 py-2"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="btn-detail" type="button" class="btn btn-primary">Edit</button>
                    <button id="btn-save" type="button" class="btn btn-primary d-none">Save</button>
                </div>
                </div>
            </div>
        </div>
        <button id="modalToggle{{{$timesheet['date']}}}" type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#timesheetModal{{{$timesheet['date']}}}">
        </button>
    @endforeach
    <div id="timesheetModal" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 id="sheetDate" class="modal-title">{{ $timesheet['date'] }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h2 id="report-empty">Empty</h2>
                    <div id="report-create" class="accordion d-none">
                        <div class="mb-4">
                            <h1>Tasks</h1>
                            <div id="new-task-container">
                                <div class="new-task mb-4">
                                    <input type="text" class="new-title" placeholder="Task title">
                                    <input type="text" class="new-content" placeholder="Task content">
                                    <input type="number" class="new-hour" placeholder="Hours used">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end"><button type="button" class="btn btn-secondary" id="new-task-btn">Add task</button></div>
                        </div>
                        <div class="difficulties mb-4">
                            <h1>Difficulties</h1>
                            <textarea placeholder="difficulties" style="width: 100%; resize: none" class="new-difficulties px-2 py-2"></textarea>
                        </div>
                        <div class="todo">
                            <h1>Todo stuff</h1>
                            <textarea placeholder="todo" style="width: 100%; resize: none" class="new-todo px-2 py-2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btn-add-timesheet" class="btn btn-primary">Add timesheet</button>
                    <button type="button" id="btn-save-timesheet" class="btn btn-primary d-none">Save timesheet</button>
                </div>
                </div>
            </div>
        </div>
        <button id="modalToggle" type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#timesheetModal">
        </button>
</div>
@endsection

@section('scripts')
<script type="module">
    $(document).ready(function() {
        let SITEURL = "{{url('/')}}";
        let list = {!! str_replace("'", "\'", json_encode($timesheets)) !!};
        let events = [];
        let shown_date = null;
        let current_id = null;
        list.forEach(item => {
            !!item.check_in && events.push({
                id: `In ${item.id}`,
                title: `Check in: ${item.check_in}`,
                start: item.date,
            })
            !!item.check_out && events.push({
                id: `Out ${item.id}`,
                title: `Check out: ${item.check_out}`,
                start: item.date,
                backgroundColor: '#198754',
            })
            
            $(`#timesheetModal${item.date} #btn-detail`).on('click', function() {
                triggerContent(item.date, true)
            })

            $(`#timesheetModal${item.date} #btn-save`).on('click', function() {
                triggerContent(item.date, false)
                let tasks = [];
                $(`#timesheetModal${item.date} .edit-task`).each(function() {
                    tasks.push({
                        title: $(this).children('.edit-title').first().val(),
                        content: $(this).children('.edit-content').first().val(),
                        hours_used: $(this).children('.edit-hour').first().val(),
                    })
                })
                let difficulties = $('.edit-difficulties').val()
                let todo = $('.edit-todo').val()

                $.ajax({
                    url: `/timesheet/${current_id}`,
                    data: {
                        'date': shown_date,
                        'tasks': tasks,
                        'difficulties': difficulties,
                        'todo': todo,
                    },
                    type: 'PUT',
                    success: function (data) {
                        alert(data.data);
                        location.reload();
                    },
                    error: function(error) {
                        alert('error');
                    }
                })
            })
        })
        $(`#btn-add-timesheet`).on('click', function() {
            $(`#report-empty`).addClass('d-none');
            $(`#report-create`).removeClass('d-none');
            $(`#btn-add-timesheet`).addClass('d-none');
            $(`#btn-save-timesheet`).removeClass('d-none');
        })
        $(`#btn-save-timesheet`).on('click', function() {
            let tasks = [];
            $('.new-task').each(function() {
                tasks.push({
                    title: $(this).children('.new-title').first().val(),
                    content: $(this).children('.new-content').first().val(),
                    hours_used: $(this).children('.new-hour').first().val(),
                })
            })
            let difficulties = $('.new-difficulties').val()
            let todo = $('.new-todo').val()

            $.ajax({
                url: `/timesheet`,
                data: {
                    'date': shown_date,
                    'tasks': tasks,
                    'difficulties': difficulties,
                    'todo': todo,
                },
                type: 'POST',
                success: function (data) {
                    alert(data.data);
                    location.reload();
                },
                error: function(error) {
                    alert('error');
                }
            })
        })

        $('#new-task-btn').on('click', function() {
            let container = $('#new-task-container');

            let new_task = $('.new-task', container).eq(0).clone();
            Array.from(new_task.children('input')).forEach(item => {
                item.value = null;
            })
            new_task.appendTo(container);
        })

        $(`.report-edit`).each(function() {
            let addTaskBtn = $(this).find('.add-task-btn')
            let self = $(this)
            addTaskBtn.on('click', function() {
                let container = self.find('.task-container');

                let new_task = $('.edit-task', container).eq(0).clone();
                Array.from(new_task.children('input')).forEach(item => {
                    item.value = null;
                })
                new_task.appendTo(container);
            })
        })


        let calendar = $('#calendar').fullCalendar({
            editable: true,
            displayEventTime: true,
            editable: true,
            events: events,
            eventRender: function (event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: true,
            selectHelper: true,
            select: function (start, end, allDay) {
                shown_date = $.fullCalendar.formatDate(start, "YYYY-MM-DD");
                let timesheet = null;
                $.ajax({
                    url: `/timesheet-detail`,
                    data: {'date': shown_date},
                    type: 'GET',
                    success: function (data) {
                        timesheet = data.data;
                        current_id = timesheet.id;
                        if(Object.keys(timesheet).length) {
                            $(`#modalToggle${timesheet.date}`).click();
                            triggerContent(timesheet.date, false)
                            $(`#sheetDate${timesheet.date}`).text($.fullCalendar.formatDate(start, "Y MM DD"));
                        } else {
                            $(`#sheetDate`).text($.fullCalendar.formatDate(start, "Y MM DD"));
                            $(`#modalToggle`).click();
                        }
                    }
                })
                
                // let title = prompt('Event Title:');

                // if (title) {
                //     let start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                //     let end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");

                //     $.ajax({
                //         url: SITEURL + "/fullcalendar/create",
                //         data: {'title' : title, 'start': start, 'end': end},
                //         type: "POST",
                //         success: function (data) {
                //             displayMessage("Added Successfully");
                //         }
                //     });
                //     calendar.fullCalendar('renderEvent',
                //         {
                //             title: title,
                //             start: start,
                //             end: end,
                //             allDay: allDay
                //         },
                //         true
                //     );
                // }
                // calendar.fullCalendar('unselect');
            },
            // eventDrop: function (event, delta) {
            //     let start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            //     let end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
            //     $.ajax({
            //         url: SITEURL + '/fullcalendar/update',
            //         data: {'title': event.title, 'start': start, 'end': end, 'id': event.id},
            //         type: "POST",
            //         success: function (response) {
            //             displayMessage("Updated Successfully");
            //         }
            //     });
            // },
            // eventClick: function (event) {
            //     let deleteMsg = confirm("Do you really want to delete?");
            //     if (deleteMsg) {
            //         $.ajax({
            //             type: "POST",
            //             url: SITEURL + '/fullcalendar/delete',
            //             data: {'id': event.id},
            //             success: function (response) {
            //                 if(parseInt(response) > 0) {
            //                     $('#calendar').fullCalendar('removeEvents', event.id);
            //                     displayMessage("Deleted Successfully");
            //                 }
            //             }
            //         });
            //     }
            // }
        });
    })

    // flag = true is edit, flag = false is save
    function triggerContent(date, flag) {
        if (flag) {
            $(`#timesheetModal${date} #report-view`).addClass('d-none');
            $(`#timesheetModal${date} #report-edit`).removeClass('d-none');
            $(`#timesheetModal${date} #btn-detail`).addClass('d-none');
            $(`#timesheetModal${date} #btn-save`).removeClass('d-none');
        } else {
            $(`#timesheetModal${date} #report-view`).removeClass('d-none');
            $(`#timesheetModal${date} #report-edit`).addClass('d-none');
            $(`#timesheetModal${date} #btn-detail`).removeClass('d-none');
            $(`#timesheetModal${date} #btn-save`).addClass('d-none');
        }
    }
</script>
@endsection


