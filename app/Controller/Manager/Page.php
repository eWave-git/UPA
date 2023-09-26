<?php

namespace App\Controller\Manager;

use App\Utils\Common;
use App\Utils\View;

class Page {


    public static function getPage($title, $content) {

        $REQUEST_URI = explode('?',$_SERVER['REQUEST_URI'])[0];
        $FILE_NAME = (explode('/',$_SERVER['REQUEST_URI']))[2];

        if (file_exists("resources/dynamic/manager/".$FILE_NAME.".js")) {
            $javascript_file = "<script src='".URL."/resources/dynamic/manager/".$FILE_NAME.".js?".date('U')."' defer></script>";
        } else {
            $javascript_file = "";
        }

        return View::render('manager/page', [
            'title' => $title,
            'content' => $content,
            'javascript' => $javascript_file,
        ]);
    }

    private static $menus = [
        [
            'label' => 'dashboard',
            'title' => '홈',
            'link'  => URL."/manager/dashboard",
        ],
        [
            'label' => 'inquiry',
            'title' => '조회',
            'submenu'=>[
                ['label' => 'inquiry', 'title' => '그래프 조회', 'link' => "javascript:alert('준비중')"],
                ['label' => 'inquiry', 'title' => '표 조회', 'link' => URL.'/manager/table_inquiry'],
            ],
        ],
        [
            'label' => 'alarm',
            'title' => '알람',
            'submenu'=>[
                ['label' => 'alarm', 'title' => '설정된 알람', 'link' => URL.'/manager/alarm_list'],
                ['label' => 'alarm_log', 'title' => '발생한 알람', 'link' => URL.'/manager/alarm_log_list'],
            ],
        ],
        [
            'label' => 'control',
            'title' => '제어',
            'submenu'=>[
                ['label' => 'control', 'title' => 'Switch 제어', 'link' => URL.'/manager/control/switch'],
                ['label' => 'control', 'title' => 'Command 제어', 'link' => URL.'/manager/control/command'],
                ['label' => 'control', 'title' => 'Inverter 제어', 'link' =>"javascript:alert('준비중')"],
            ],
        ],
        [
            'label' => 'etc',
            'title' => '기타',
            'submenu'=>[
                ['label' => 'etc', 'title' => '알람 수신변경', 'link' => "javascript:alert('준비중')"],
                ['label' => 'etc', 'title' => '그룹 관리', 'link' => URL.'/manager/etc/group'],
                ['label' => 'etc', 'title' => '레포팅', 'link' => "javascript:alert('준비중')"],
                ['label' => 'etc', 'title' => '데이터 분석', 'link' => "javascript:alert('준비중')"],
            ],
        ],
    ];


    public static function getDepth_1($currentModule) {

        $menus = '';

        foreach (self::$menus as $k => $v) {
            if (!array_key_exists('submenu', $v)) {
                $menus .= View::render('manager/menu/li', [
                    'depth_1' => $v['title'],
                    'active' => $v['label'] == $currentModule ? 'active' : '',
                    'link'    => $v['link'],
                ]);
            } else {
                $menus .= View::render('manager/menu/li_dropdown', [
                    'depth_1' => $v['title'],
                    'active' => $v['label'] == $currentModule ? 'active' : '',
                    'dropdown' => self::getDepth_2($v),
                ]);
            }

        }

        return View::render('manager/menu/navbar', [
            'menus' => $menus
        ]);
    }

    public static function getDepth_2($sub_menu) {
        $dropdown = '';


        foreach ($sub_menu['submenu'] as $k => $v) {
            $dropdown .= View::render('manager/menu/dropdown', [
                'depth_2' => $v['title'],
                'link'    => $v['link'],
            ]);
        }

        return $dropdown;
    }

    public static function getPanel($title, $content, $currentModule) {
        $contentPanel = View::render('manager/panel', [
            'menu' => self::getDepth_1($currentModule),
            'content' => $content
        ]);

        return self::getPage($title, $contentPanel);
    }

    public static function getPagination($request, $obPagination) {
        $pages = $obPagination->getPages();

        if (count($pages) <=1 ) return '';

        $links = '';

        $url = $request->getRouter()->getCurrentUrl();
        $queryParams = $request->getQueryParams();

        foreach ($pages as $page) {
            $queryParams['page'] = $page['page'];

            $link = $url.'?'.http_build_query($queryParams);

            $links .= View::render('manager/pagination/link', [
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }

        return View::render('manager/pagination/box', [
            'links' => $links
        ]);
    }

}