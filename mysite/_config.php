<?php

global $project;
$project = 'mysite';

global $database;

require_once("conf/ConfigureFromEnv.php");

// Set the site locale
i18n::set_locale('en_US');

// enable fulltext search
FulltextSearchable::enable();

// set valid styles for the text editor field in CMS
HtmlEditorConfig::get('cms')->setOption(
    'valid_styles',
    array('*' => 'width,height,color,font-size,font-weight,font-style,text-decoration')
);

