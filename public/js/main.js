
    var addVote = function(data) {
        $.ajax({type: 'POST',
            url: 'addVote',
            data: data,
            success: (function(data) {
                var json = $.parseJSON(data);
                $("#"+json.id + ".vote-" + json.type + " i.fa-" + json.type).removeClass('fa-'+json.type).addClass('fa-check'); 
                var change;
                if(json.type === 'plus') {
                    change = 1;
                } else if (json.type === 'minus') {
                    change = -1;
                }
                $(".joke-vote").find("span#" + json.id).html(parseInt($(".joke-vote").find("span#" + json.id).html())+change);
            }),
            fail: (function(data) {
                alert('Vote was not processed.');
            })
        });
        return false;
    };
    
    $('.vote').click(function(event) {
        event.preventDefault();
        var id = $(this).attr('id');
        if ($(this).children('i').hasClass('fa-check')) {
            //do nothing
        } else if ($(this).hasClass('vote-minus')) {
            addVote('type=minus&id=' + id);
        } else if ($(this).hasClass('vote-plus')) {
            addVote('type=plus&id=' + id);
        }
    });