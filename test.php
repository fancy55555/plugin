<?php

namespace NamePlugin;

class NameApi {
    private $api_url;
    private $wpdb;

    public function __construct($api_url, $wpdb) {
        $this->api_url = $api_url;
        $this->wpdb = $wpdb;
    }

    /**
     * Получает список вакансий.
     *
     * @param object $post
     * @param int $vid
     * @return array|object|false
     */
    public function listVacancies($post, $vid = 0) {
        $ret = [];

        if (!is_object($post)) {
            return false;
        }

        $page = 0;
        $found = false;

        while (true) {
            $params = "status=all&id_user=" . $this->selfGetOption('superjob_user_id') . "&with_new_response=0&order_field=date&order_direction=desc&page={$page}&count=100";
            $res = $this->apiSend($this->api_url . '/hr/vacancies/?' . $params);
            $res_o = json_decode($res);

            if ($res !== false && is_object($res_o) && isset($res_o->objects)) {
                $ret = array_merge($res_o->objects, $ret);

                if ($vid > 0) {
                    foreach ($res_o->objects as $key => $value) {
                        if ($value->id == $vid) {
                            $found = $value;
                            break;
                        }
                    }
                }

                if ($found !== false || !$res_o->more) {
                    return is_object($found) ? $found : $ret;
                }

                $page++;
            } else {
                return false;
            }
        }
    }

    /**
     * Отправляет запрос к API.
     *
     * @param string $url
     * @return string
     */
    private function apiSend($url) {
        return '';
    }

    /**
     * Получает значение опции из настроек.
     *
     * @param string $optionName
     * @return string
     */
    private function selfGetOption($optionName) {
        return '';
    }
}
