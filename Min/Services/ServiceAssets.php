<?php
namespace lolita\LolitaFramework\Min\Services;

class ServiceAssets
{

    /**
     * Add base url to css
     * @param  string $string
     * @param  string $css_url
     * @return string
     */
    public static function urlPrepare($string, $css_url = '')
    {
        $pattern = '|url\(((\")?(\')?)?(?!data)(?!\"data)(?!\'data)(?!http)(?!\"http)(?!\'http)(.*?)((\")?(\')?)?\)|';
        $replacement = 'url("' . trailingslashit(dirname($css_url)) . '${4}' . '")';
        $string = preg_replace($pattern, $replacement, $string);
        return $string;
    }
}
