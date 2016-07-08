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
#animation3 {
    position: fixed !important;
    top: 50%;
    left: 50%;
    margin-top: -20px;
    margin-left: -25px;
    z-index: 99999;
}
#animation3.spinner {
    width: 50px;
    height: 40px;
    text-align: center;
    font-size: 10px;
}
#animation3.spinner > div {
    background-color: <?php echo $color ?>;
    height: 100%;
    width: 6px;
    display: inline-block;
    -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
    animation: sk-stretchdelay 1.2s infinite ease-in-out;
}
#animation3.spinner .rect2 {
    -webkit-animation-delay: -1.1s;
    animation-delay: -1.1s;
}
#animation3.spinner .rect3 {
    -webkit-animation-delay: -1.0s;
    animation-delay: -1.0s;
}
#animation3.spinner .rect4 {
    -webkit-animation-delay: -0.9s;
    animation-delay: -0.9s;
}
#animation3.spinner .rect5 {
    -webkit-animation-delay: -0.8s;
    animation-delay: -0.8s;
}
@-webkit-keyframes sk-stretchdelay {
    0%, 40%, 100% {
        -webkit-transform: scaleY(0.4);
    }
    20% {
        -webkit-transform: scaleY(1);
    }
}
@keyframes sk-stretchdelay {
    0%, 40%, 100% {
        transform: scaleY(0.4);
        -webkit-transform: scaleY(0.4);
    }
    20% {
        transform: scaleY(1);
        -webkit-transform: scaleY(1);
    }
}
</style>
<div class="lf_css_loader_bg <?php echo $class ?>">
    <div id="animation3" class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>