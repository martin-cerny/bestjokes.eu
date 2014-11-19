<a name="joke-<?php echo $joke->id ?>"></a> 
<article class="joke col-md-8 col-md-offset-2" itemscope="" itemtype="http://schema.org/Article">
    <div class="row">
        <h1 class="col-md-12 text-left">{{ HTML::linkRoute('joke', $joke->title, array('id' => $joke->id)) }}</h1>
        <?php if (isset($_SERVER['PHP_AUTH_USER'])) { ?>
            <div class="col-md-12">
                {{ HTML::linkRoute('editJoke', 'edit', array('id' => $joke->id, 'previous' => Request::url() . '#joke-' . $joke->id), array('class' => 'btn btn-default btn-warning btn-xs btn-admin')) }}              
                <?php $nextId = $joke->id + 1; ?>
                {{ Form::open(array('url' => "/deleteJoke/" . $joke->id . "?previous=" . urlencode(Request::url())  . "#joke-" . $nextId)) }}
                    {{ Form::submit('delete', array('class' => 'btn btn-default btn-danger btn-xs delete btn-admin', 'id' => $joke->id)) }}
                {{ Form::close() }}
                {{ HTML::linkRoute('addJoke', 'new', array(), array('class' => 'btn btn-default btn-success btn-xs')) }}
            </div>
        <?php } ?>
        <div class="joke-tag col-md-12">
            <i class="fa fa-tag top-buffer">   
                <?php
                $jokeCategories = explode(',', $joke->categories);
                $next = false;
                foreach ($jokeCategories as $jokeCategory) {
                    if ($next) {
                        echo "|";
                    } else {
                        $next = true;
                    }
                    ?>
                    <?php $name = str_replace(' ', '-', utf8_encode($jokeCategory)) . "-jokes"; ?>
                    {{ HTML::linkRoute('category', utf8_encode($jokeCategory), array('category' => $name)) }}
                <?php } ?>
            </i>
        </div>
        <div class="joke-text col-md-12"><?php echo $joke->text; ?></div>
        <div class="col-xs-12">
            <div class="joke-social col-xs-12 col-sm-3 top-buffer">
                <div class="fb-like" data-href="http://bestjokes.eu/joke/<?php echo $joke->id ?>" data-layout="button" data-action="like" data-show-faces="true" data-share="false"></div>
                <!--<i class="fa fa-twitter fa-2x color-twitter col-xs-4 col-sm-2  text-center"></i>
                <i class="fa fa-facebook-square fa-2x color-facebook col-xs-4 col-sm-2  text-center"></i>
                <i class="fa fa-share-alt fa-2x color-share col-xs-4 col-sm-2  text-center"></i>-->
            </div>
            <div class="joke-vote col-xs-12 col-sm-7 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-5 top-buffer">
                <a href="#" class="vote vote-minus" id="{{ $joke->id }}"><i class="fa fa-minus fa-2x color-minus col-xs-4 col-md-3 text-center"></i></a>
                <div class="col-xs-4 col-lg-6 text-center">Votes <strong><span id="{{$joke->id}}">{{{$joke->plus_votes - $joke->minus_votes}}}</span></strong></div>
                <a href="#" class="vote vote-plus" id="{{ $joke->id }}"><i class="fa fa-plus fa-2x color-plus col-xs-4 col-md-3 text-center"></i></a>
            </div>
        </div>
        <div class="modal fade confirmation" id="confirmation-<?php echo $joke->id ?>" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><strong>Do you want to delete?</strong> <span class="pull-right">Joke id: <strong><?php echo $joke->id ?></strong></span></div>
                    <div class="modal-body pull-left"><?php echo $joke->text ?></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

