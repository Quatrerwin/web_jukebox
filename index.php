<?php echo 'hello';  ?>
<html>
<head>
    <link rel="stylesheet" href="css/jquery.mobile-1.3.1.min.css" />
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/jquery.mobile-1.3.1.min.js"></script>
    <script src="js/jquery.blockUI.js"></script>
</head>

<body>

<div data-role="page" id="page1">

    <div id="header" data-theme="a" data-role="header" class="header">
        <span class="ui-title">Web JukeBox
        </span>
    </div>
    <div data-role="content">
        <a data-role="button" href="javascript:" class="song1" onclick="cast_vote('1')">
            Button
        </a>
        <a data-role="button" href="javascript:" class="song2" onclick="cast_vote('2')">
            Button
        </a>
        <a data-role="button" href="javascript:" class="song3" onclick="cast_vote('3')">
            Button
        </a>
        <input type="hidden" value="default_value" name="hash_id"/>

            <div class="ui-grid-a">
                <div class="ui-block-a">Song
                </div>
                <div class="ui-block-b">Votes
                </div>
                <div class="ui-block-a" class="song1">Song 1
                </div>
                <div class="ui-block-b" id="vote1">1
                </div>
                <div class="ui-block-a" class="song2">Song 2
                </div>
                <div class="ui-block-b" id="vote2">2
                </div>
                <div class="ui-block-a" class="song3">Song 3
                </div>
                <div class="ui-block-b" id="vote3">3
                </div>
            </div>
        
    </div>
    <!--
    <div id="footer" data-theme="a" data-role="footer" data-position="fixed"
    class="footer">
        <h3>
            Footer
        </h3>
    </div>
    -->
</div>
<script type="text/javascript">
    $(function(){
 //       if(<?= $_SESSION['logged_in'] ?>)
 //       login_overlay();
        update_queue(false);
 //       window.setInterval(update_queue(false),5000);
    });

    function remove_overlay(){
        $.unblockUI();
    }

    function login_overlay(){
        $.blockUI({ message: $('#loginForm') }); 
    }

    function thinking_overlay(){
        //check if the blockUI is alread up if not set it
        //add overlay class to body with thinking icon
        $.blockUI({ overlayCSS: { backgroundColor: '#00f' } });
    }

    function go_dance_overlay(){
        //check if the blockUI is alread up if not set it
        //add overlay letting people to go out and dance
        $.blockUI({ overlayCSS: { backgroundColor: '#00f' } });
    }

    function cast_vote(vote_for){
        thinking_overlay();
        $.ajax({
            url: 'rpc/cast_vote.php',
            type: 'POST',
            data: {vote_for: vote_for, hash_id: $('#hash_id').val()}
        }).done(function(){
            update_queue(true);
            remove_overlay();//move this to a 'complete' function
        });
    }

    function update_queue(advance_by_one){
        advance_by_one = (typeof advance_by_one === 'undefined') ? false : advance_by_one;
        $.ajax({
            url: 'rpc/update_queue.php',
            type: 'POST',
            data: { hash_id: $('#hash_id').val(), advance_by_one: advance_by_one},
        }).done(function(result){
            if(result.update_list){
                thinking_overlay();
                $('.song1').html(result.song1);
                $('.song2').html(result.song2);
                $('.song3').html(result.song3);
                $('#vote1').html(result.vote1);
                $('#vote2').html(result.vote2);
                $('#vote3').html(result.vote3);
                //update vote stuff
                $('#hash_id').val(result.hash_id);
                remove_overlay();
            }else if(result.max_entropy){
                go_dance_overlay();
            }
        }).error(function(){
            alert('there was a problem');
        })
    }
</script>

</body>
</html>