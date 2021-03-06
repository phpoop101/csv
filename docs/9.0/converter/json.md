---
layout: default
title: Converting a CSV into a JSON string
---

# JSON conversion

~~~php
<?php

class JsonConverter
{
    public function options(int $options): self
    public function convert(iterable $records): string
}
~~~

`JsonConverter` converts a CSV records collection into a JSON string using PHP's `json_encode` function.

## Settings

~~~php
<?php

public JsonConverter::options(int $options): self
~~~

The `options` methods sets PHP's `json_encode` flag parameter.

<p class="message-info">By default, the flag parameter is set to <code>0</code>.</p>

## Conversion

~~~php
<?php
public JsonConverter::convert(iterable $records): string
~~~

The `JsonConverter::convert` accepts:

- an `iterable` which represents the records collection.
- an optional flags which represents PHP's `json_encode` optional flags.

and returns a string.

<p class="message-notice"><strong>Notice:</strong> To convert an iterator, <code>iterator_to_array</code> is used, which could lead to a performance penalty if you convert a large <code>Iterator</code>.</p>

<p class="message-warning">If a error occurs during the convertion an <code>RuntimeException</code> exception is thrown with additional information regarding the error.</p>

~~~php
<?php

use League\Csv\JsonConverter;
use League\Csv\Reader;

$csv = Reader::createFromPath('/path/to/file.csv', 'r');
$csv->setHeaderOffset(0);

$json = (new JsonConverter())
    ->options(JSON_PRETTY_PRINT)
    ->convert($csv)
;

echo '<pre>', $json, PHP_EOL;
// [
//     {
//         "firstname": "john",
//         "lastname": "doe",
//         "email": "john.doe@example.com"
//     },
//     {
//         "firstname": "jane",
//         "lastname": "doe",
//         "email": "jane.doe@example.com"
//     },
//     ...
//     {
//         "firstname": "san",
//         "lastname": "goku",
//         "email": "san.goku@dragon-ball.super"
//     }
// ]
~~~

<p class="message-info">If needed you can use the <a href="/9.0/converter/charset/">CharsetConverter</a> object to correctly encode your CSV records before conversion.</p>

~~~php
<?php

use League\Csv\JsonConverter;
use League\Csv\CharsetConverter;
use League\Csv\Reader;

$csv = Reader::createFromPath('/path/to/file-in-iso-8859-15-format.csv', 'r');

$charset_converter = (new CharsetConverter())
    ->input_encoding('iso-8859-15')
    ->output_encoding('utf-8')
;

$data = $charset_converter->convert($csv);

$json = (new JsonConverter())
    ->convert($data)
;
~~~