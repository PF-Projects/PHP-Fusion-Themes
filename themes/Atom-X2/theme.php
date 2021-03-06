<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: theme.php
| Author: PHP Fusion Inc
| Author: RobiNN
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
defined('IN_FUSION') || exit;

require_once INCLUDES.'theme_functions_include.php';
require_once 'theme_autoloader.php';

const THEME_BULLET = '&middot;';
const BOOTSTRAP = TRUE;
const FONTAWESOME = TRUE;
const SOCIAL_SHARE = TRUE; // Set FALSE to turn Off, used only in News

function cache_users() {
    $settings = fusion_get_settings();

    if (!empty($settings['online_maxcount'])) {
        $count = dbcount('(online_user)', DB_ONLINE);

        if ($settings['online_maxcount'] < $count) {
            $settings_ = [
                'online_maxcount' => $count,
                'online_maxtime'  => time()
            ];

            foreach ($settings_ as $settings_name => $settings_value) {
                $db = [
                    'settings_name'  => $settings_name,
                    'settings_value' => $settings_value
                ];

                dbquery_insert(DB_SETTINGS, $db, 'update', ['primary_key' => 'settings_name']);
            }
        }
    } else {
        $settings_ = [
            'online_maxcount' => '1',
            'online_maxtime'  => time()
        ];

        foreach ($settings_ as $settings_name => $settings_value) {
            $db = [
                'settings_name'  => $settings_name,
                'settings_value' => $settings_value
            ];

            dbquery_insert(DB_SETTINGS, $db, 'save', ['primary_key' => 'settings_name']);
        }
    }
}

function render_page() {
    cache_users();

    $atom = new AtomX2Theme\Main;
    $atom->renderPage();
}

function opentable($title = FALSE, $class = '') {
    echo '<div class="panel-atom panel-default '.$class.'">';
    echo !empty($title) ? '<div class="panel-heading"><h4><strong>'.$title.'</strong></h4></div>' : '';
    echo '<div class="panel-body">';
}

function closetable() {
    echo '</div>';
    echo '</div>';
}

function openside($title = FALSE, $class = '') {
    echo '<aside class="panel-atom panel-default '.$class.'">';
    echo !empty($title) ? '<div class="panel-heading">'.$title.'</div>' : '';
    echo '<div class="panel-body">';
}

function closeside() {
    echo '</div>';
    echo '</aside>';
}

function render_downloads($info) {
    AtomX2Theme\Templates\Downloads::renderRownloads($info);
}

function display_inbox($info) {
    AtomX2Theme\Templates\PrivateMessages::getInstance()->displayInbox($info);
}

function display_main_news($info) {
    AtomX2Theme\Templates\News::getInstance()->displayMainNews($info);
}

function render_news_item($info) {
    AtomX2Theme\Templates\News::getInstance()->renderNewsItem($info);
}

function display_user_profile($info) {
    AtomX2Theme\Templates\Profile::getInstance()->displayProfile($info);
}

set_image('noavatar50', fusion_get_settings('siteurl').'themes/Atom-X2/images/noavatar50.png');
