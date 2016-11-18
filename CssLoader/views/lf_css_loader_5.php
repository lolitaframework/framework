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
#animation5 {
    position: fixed !important;
    top: 50%;
    left: 50%;
    margin-top: -20px;
    margin-left: -20px;
    z-index: 99999;
}
#animation5.lf_spinner {
    width: 40px;
    height: 40px;
    background-color: #fff;
    border-radius: 100%;
    -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
    animation: sk-scaleout 1.0s infinite ease-in-out;
}
@-webkit-keyframes sk-scaleout {
    0% {
        -webkit-transform: scale(0);
    }
    100% {
        -webkit-transform: scale(1);
        opacity: 0;
    }
}
@keyframes sk-scaleout {
    0% {
        -webkit-transform: scale(0);
        transform: scale(0);
    }
    100% {
        -webkit-transform: scale(1);
        transform: scale(1);
        opacity: 0;
    }
}
</style>
<div class="lf_css_loader_bg <?php echo $class ?>">
    <div id="animation5" class="lf_spinner"></div>
</div>