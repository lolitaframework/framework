<style type="text/css" media="screen">
.lf_css_loader_bg {
    position: fixed !important;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: <?php echo $bg ?>;
    z-index: 99998
}
#animation1 {
    position: fixed !important;
    top: 50%;
    left: 50%;
    margin-top: -20px;
    margin-left: -20px;
    z-index: 99999
}
#animation1.spinner {
    width: 40px;
    height: 40px;
    background-color: <?php echo $color ?>;
    -webkit-animation: sk-rotateplane 1.2s infinite ease-in-out;
    animation: sk-rotateplane 1.2s infinite ease-in-out
}
@-webkit-keyframes sk-rotateplane {
    0% {
        -webkit-transform: perspective(120px)
    }
    50% {
        -webkit-transform: perspective(120px) rotateY(180deg)
    }
    100% {
        -webkit-transform: perspective(120px) rotateY(180deg) rotateX(180deg)
    }
}
@keyframes sk-rotateplane {
    0% {
        transform: perspective(120px) rotateX(0deg) rotateY(0deg);
        -webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg)
    }
    50% {
        transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
        -webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg)
    }
    100% {
        transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
        -webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg)
    }
}
</style>
<div class="lf_css_loader_bg <?php echo $class ?>">
    <div id="animation1" class="spinner"></div>
</div>