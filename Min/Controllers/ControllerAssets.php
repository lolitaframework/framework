<?php
namespace lolita\LolitaFramework\Min\Controllers;

use \WP_Styles;
use \WP_Scripts;
use \lolita\LolitaFramework\Core\Url;
use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Min\Services\ServiceAssets;

class ControllerAssets
{

    /**
     * Print scripts
     * @param  string $hash
     * @return void
     */
    public static function scripts($hash)
    {
        timer_start();

        $headers = [
            'Content-Type: application/javascript',
            'Etag: ' . $hash,
            'Cache-control: public, max-age=3000'
        ];
        foreach ($headers as $h) {
            header($h);
        }

        $hash = trim($hash);

        if ('' === $hash) {
            return '';
        }

        // ==============================================================
        // If result is already exists then print it
        // ==============================================================
        if (false !== get_transient($hash . '_result')) {
            return get_transient($hash . '_result') . sprintf(' /** Time: %s */', timer_stop());
        }

        $res   = [];
        $cache = get_transient($hash);
        $content_url = defined('WP_CONTENT_URL')? WP_CONTENT_URL : '';
        if (is_array($cache) && count($cache)) {
            foreach ($cache as $handle => $el) {
                if ($el->src) {
                    if (!preg_match('|^(https?:)?//|', $el->src) && ! ($content_url && 0 === strpos($el->src, $content_url))) {
                        $el->src = site_url() . $el->src;
                    }
                    if (array_key_exists('data', $el->extra)) {
                        $res[] = '/** DATA */';
                        $res[] = $el->extra['data'];
                        $res[] = '/** DATAEND */';
                    }
                    if (Url::isLocal($el->src)) {
                        $path = Url::toFileSystem($el->src);
                        if (is_file($path)) {
                            $res[] = sprintf(
                                "/** %s */ \n %s",
                                $el->src,
                                file_get_contents($path)
                            );
                        }
                    } else {
                        $res[] = sprintf(
                            "/** %s */ \n %s",
                            $el->src,
                            wp_remote_retrieve_body(wp_remote_get($el->src))
                        );
                    }
                    $res[] = ';';
                }
            }
        }
        $result = implode("\n", $res);
        set_transient($hash . '_result', $result, DAY_IN_SECONDS * 7);
        return $result . sprintf(' /** Time: %s */', timer_stop());
    }

    /**
     * Print styles
     * @param  string $hash
     * @return void
     */
    public static function styles($hash)
    {
        timer_start();

        $headers = [
            'Content-Type: text/css',
            'Etag: ' . $hash,
            'Cache-control: public, max-age=300',
            'Age: 89'
        ];
        foreach ($headers as $h) {
            header($h);
        }

        $hash = trim($hash);

        if ('' === $hash) {
            return '';
        }

        // ==============================================================
        // If result is already exists then print it
        // ==============================================================
        if (false !== get_transient($hash . '_result')) {
            return get_transient($hash . '_result') . sprintf(' /** Time: %s */', timer_stop());
        }

        $res   = [];
        $cache = get_transient($hash);
        $content_url = defined('WP_CONTENT_URL')? WP_CONTENT_URL : '';
        if (is_array($cache) && count($cache)) {
            foreach ($cache as $handle => $el) {
                if ($el->src) {
                    if (!preg_match('|^(https?:)?//|', $el->src) && ! ($content_url && 0 === strpos($el->src, $content_url))) {
                        $el->src = site_url() . $el->src;
                    }

                    if (Url::isLocal($el->src)) {
                        $res[] = sprintf(
                            "/** %s */ \n %s",
                            $el->src,
                            ServiceAssets::urlPrepare(
                                file_get_contents(Url::toFileSystem($el->src)),
                                $el->src
                            )
                        );
                    } else {
                        $res[] = sprintf(
                            "/** %s */ \n %s",
                            $el->src,
                            ServiceAssets::urlPrepare(
                                wp_remote_retrieve_body(wp_remote_get($el->src)),
                                $el->src
                            )
                        );
                    }
                }
            }
        }
        $result = View::minimize(implode("\n", $res));
        set_transient($hash . '_result', $result, DAY_IN_SECONDS * 7);
        return $result . sprintf(' /** Time: %s */', timer_stop());
    }
}
