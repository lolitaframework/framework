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
#animation2 {
    position: fixed !important;
    top: 50%;
    left: 50%;
    margin-top: -20px;
    margin-left: -20px;
    z-index: 99999;
}
#animation2.spinner {
    width: 40px;
    height: 40px;
    position: relative;
}
#animation2 .double-bounce1,
#animation2 .double-bounce2 {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background-color: <?php echo $color ?>;
    opacity: 0.6;
    position: absolute;
    top: 0;
    left: 0;
    -webkit-animation: sk-bounce 2.0s infinite ease-in-out;
    animation: sk-bounce 2.0s infinite ease-in-out;
}
#animation2 .double-bounce2 {
    -webkit-animation-delay: -1.0s;
    animation-delay: -1.0s;
}
@-webkit-keyframes sk-bounce {
    0%, 100% {
        -webkit-transform: scale(0);
    }
    50% {
        -webkit-transform: scale(1);
    }
}
@keyframes sk-bounce {
    0%, 100% {
        transform: scale(0);
        -webkit-transform: scale(0);
    }
    50% {
        transform: scale(1);
        -webkit-transform: scale(1);
    }
}
</style>
<div class="lf_css_loader_bg <?php echo $class ?>">
    <div id="animation2" class="spinner">
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>
</div>