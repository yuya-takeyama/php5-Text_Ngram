Text_Ngram
==========

文字列を N-gram 形式に分割するためのライブラリです。

N-gram オブジェクトを生成し、配列のように扱うことができます。

Requirements
============

PHP5 (>= PHP 5.3)

N-gram?
=======

例えば全文検索システムを構築する場合、前処理としてインデックスの作成が必要となります。
しかし、欧米の言語と違って、日本語はスペース等の一定のデリミタで区切ることはできません。
そこで、文章的な意味に関わらず、 n 文字ごとの分割した形式を使い、これを N-gram といいます。

分かち書きの方法としては、形態素解析も挙げられますが、 N-gram では辞書のメンテナンスが不要、という利点があります。
また、 n 文字ごとに分割する、という単純なルールなため、言語にかかわらず適用することができます。

Usage
=====

Create N-gram object
--------------------

    require_once '/path/to/Text/Ngram.php';
    use Text\Ngram;
    $ngram = new Ngram('こんにちは世界！', 2, 'UTF-8');

Convert to array
----------------

    $ngramArray = $ngram->toArray();

Treat N-gram object as array
----------------------------

    echo $ngram[0];     // => こん
    echo $ngram[1];     // => んに
    echo count($ngram); // => 7

Iteration
---------

    foreach ($ngram as $key => $value) {
        echo "{$key} : {$value}" . PHP_EOL;
    }

Author
======

Yuya Takeyama <sign.of.the.wolf.pentagram at gmail.com>
