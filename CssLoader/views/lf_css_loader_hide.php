<script type="text/javascript">
    function fade(element) {
        var op = 1;
        var timer = setInterval(function () {
            if (op <= 0.1){
                clearInterval(timer);
                element.style.display = 'none';
            }
            element.style.opacity = op;
            element.style.filter = 'alpha(opacity=' + op * 100 + ")";
            op -= op * 0.1;
        }, 10);
    }
    setTimeout(
        function(){
            var lolita_css_loader_bg = document.getElementsByClassName('lf_css_loader_bg');
            fade(lolita_css_loader_bg[0]);
        },
        <?php echo $delay ?>
    );
    console.log('<?php echo $spent_time ?>');
</script>