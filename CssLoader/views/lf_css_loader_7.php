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

#animation7 {
    position: fixed !important;
    top: 50%;
    left: 50%;
    margin-top: -20px;
    margin-left: -35px;
    z-index: 99999;
}
#animation7.lf_spinner {
    width: 70px;
    text-align: center;
}
#animation7.lf_spinner > div {
    width: 18px;
    height: 18px;
    background-color: <?php echo $color ?>;
    border-radius: 100%;
    display: inline-block;
    -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
    animation: sk-bouncedelay 1.4s infinite ease-in-out both;
}
#animation7.lf_spinner .bounce1 {
    -webkit-animation-delay: -0.32s;
    animation-delay: -0.32s;
}
#animation7.lf_spinner .bounce2 {
    -webkit-animation-delay: -0.16s;
    animation-delay: -0.16s;
}
@-webkit-keyframes sk-bouncedelay {
    0%, 80%, 100% {
        -webkit-transform: scale(0);
    }
    40% {
        -webkit-transform: scale(1);
    }
}
@keyframes sk-bouncedelay {
    0%, 80%, 100% {
        -webkit-transform: scale(0);
        transform: scale(0);
    }
    40% {
        -webkit-transform: scale(1);
        transform: scale(1);
    }
}
</style>
<div class="lf_css_loader_bg <?php echo $class ?>">
    <div id="animation7" class="lf_spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>