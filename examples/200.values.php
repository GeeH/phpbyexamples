<?php

return [
    'title'       => 'Working With Values',
    'pre' => <<<MD
PHP can handle many different types of values, including strings, integers, floats and boolean.
You don't need to declare value types in PHP, it will infer the value and handle types and type conversions for you.
Notice how we can use `var_dump` instead of `echo` to see the value's type as well as it's content.
MD
    ,
    'annotations' => [
        "2" => "We can concatenate strings using the `.` operator",
        "3" => "We don't need to change the integer to a string to concatenate, PHP does this for us"
    ],
    'eval'        => 'https://3v4l.org/hRpkM',
    'output'      => "$ php values.php
string(13) \"Hello, World!\"
string(25) \"The Meaning of Life is 42\"
float(105)
bool(true)
bool(false)
bool(false)",
];