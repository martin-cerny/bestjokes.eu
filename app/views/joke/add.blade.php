<!doctype html>
<html lang="en">
    <head>
        <title>Add joke</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="alert alert-{{$type or ""}}" role="alert" style="margin-top: 20px">{{$message or ""}}</div>
            <div class="row">
                <div class="col-lg-12"  style="margin-top: 20px">
                    <?php $text = isset($text) ? $text : ""; ?>
                    <?php $title = isset($title) ? $title : ""; ?>
                    {{ Form::open(array('url' => 'addJoke')) }}
                    <div class="col-lg-7">
                        <div class="form-group">
                            {{ Form::label('title', 'Title', array('class' => 'col-lg-12')) }}
                            {{ Form::text('title', $title, array('class' => 'col-lg-12 form-control', "required" => "true")) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Text', 'Text', array('class' => 'col-lg-12')) }}
                            {{ Form::textArea('text', $text, array('class' => 'col-lg-12 form-control', 'rows' => '25', 'style' => "white-space:pre", "required" => "true")) }}
                        </div>
                    </div>
                    <div class="col-lg-5 col-sm-12 col-xs-12">
                        <div class="form-group">
                            @foreach ($categories as $category)
                             <div class="col-lg-4 col-sm-3 col-xs-4">
                                {{ Form::checkbox('categories[]', $category->id) }}
                                {{ Form::label('categories', $category->name) }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                        <div class="text-center col-lg-5" style="margin-top: 40px">
                            {{ Form::submit('Save', array('class' => 'btn btn-default btn-success')) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </body>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        
        var checkTitle = function(data) {
            $.ajax({type: 'GET',
                url: 'checkTitleDuplicity',
                data: data,
                success: (function(data) {
                    if(data === 'false') {
                        $("input[name*='title']").closest('.form-group').addClass('has-success');
                    } else if (data === 'true'){
                        $("input[name*='title']").closest('.form-group').addClass('has-error');
                    } else {
                        alert('Duplicity of title was not checked.');
                    }
                }),
                fail: (function(data) {
                    alert('Duplicity of title was not checked.');
                })
            });
            return false;
        };
    
        $("input[name*='title']").focusout(function() {
            if ($(this).val().length !== 0) {
                $(this).closest('.form-group').removeClass('has-success').removeClass('has-error');
                checkTitle('title=' + $(this).val());
            }
        });
        
                
        var checkText = function(data) {
            $.ajax({type: 'GET',
                url: 'checkTextDuplicity',
                data: data,
                success: (function(data) {
                    if(data === 'false') {
                         $("textarea").closest('.form-group').addClass('has-success');
                    } else if (data === 'true'){
                        $("textarea").closest('.form-group').addClass('has-error');
                    } else {
                        alert('Duplicity of text was not checked.');
                    }
                }),
                fail: (function(data) {
                    alert('Duplicity of text was not checked.');
                })
            });
            return false;
        };
    
         $("textarea").focusout(function() {
            if ($(this).val().length !== 0) {
                $(this).closest('.form-group').removeClass('has-success').removeClass('has-error');
                checkText('text=' + $(this).val());
            }
        });
    </script>
</html>
