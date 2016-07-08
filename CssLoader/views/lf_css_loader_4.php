<style type="text/css" media="screen">
.lf_css_loader_bg {
    position: fixed !important;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: <?php echo $bg ?>;
    z-index: 99998;
}
#animation4 {
    position: fixed !important;
    top: 50%;
    left: 50%;
    margin-top: -20px;
    margin-left: -20px;
    z-index: 99999;
}
#animation4.spinner {
    width: 40px;
    height: 40px;
    position: relative;
}
#animation4 .cube1,
#animation4 .cube2 {
    background-color: <?php echo $color ?>;
    width: 15px;
    height: 15px;
    position: absolute;
    top: 0;
    left: 0;
    -webkit-animation: sk-cubemove 1.8s infinite ease-in-out;
    animation: sk-cubemove 1.8s infinite ease-in-out;
}
#animation4 .cube2 {
    -webkit-animation-delay: -0.9s;
    animation-delay: -0.9s;
}
@-webkit-keyframes sk-cubemove {
    25% {
        -webkit-transform: translateX(42px) rotate(-90deg) scale(0.5);
    }
    50% {
        -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg);
    }
    75% {
        -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5);
    }
    100% {
        -webkit-transform: rotate(-360deg);
    }
}
@keyframes sk-cubemove {
    25% {
        transform: translateX(42px) rotate(-90deg) scale(0.5);
        -webkit-transform: translateX(42px) rotate(-90deg) scale(0.5);
    }
    50% {
        transform: translateX(42px) translateY(42px) rotate(-179deg);
        -webkit-transform: translateX(42px) translateY(42px) rotate(-179deg);
    }
    50.1% {
        transform: translateX(42px) translateY(42px) rotate(-180deg);
        -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg);
    }
    75% {
        transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5);
        -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5);
    }
    100% {
        transform: rotate(-360deg);
        -webkit-transform: rotate(-360deg);
    }
}
</style>
<div class="lf_css_loader_bg <?php echo $class ?>">
    <div id="animation4" class="spinner">
        <div class="cube1"></div>
        <div class="cube2"></div>
    </div>
</div>