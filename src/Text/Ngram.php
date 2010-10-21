<?php
/**
 * Text_Ngram
 * N-gram 転置インデックス
 *
 * @category Text
 * @package  Text_Ngram
 * @author   Yuya Takeyama <sign.of.the.wolf.pentagram at gmail.com>
 */

/**
 * @namespace
 */
namespace Text;

class Ngram implements \SeekableIterator, \Countable, \ArrayAccess
{
    /**
     * N-gram の元になる文書
     *
     * @var string
     */
    protected $_text;

    /**
     * 文書の文字数
     *
     * @var int
     */
    protected $_textSize;

    /**
     * N-gram インデックスの長さ
     *
     * @var int
     */
    protected $_indexSize;

    /**
     * N-gram の元になる文書の文字コード
     *
     * @var string
     */
    protected $_encode;

    /**
     * N-gram イテレータのポインタ
     *
     * @var int
     */
    protected $_pointer;

    /**
     * Constructor
     *
     * @param  string $text   N-gram の元になる文書
     * @param  int    $size   N-gram インデックスの長さ (デフォルトは 2 [bigram])
     * @param  string $encode 文書の文字コード (省略時は mb_internal_encoding() を使用)
     */
    public function __construct($text, $size = 2, $encode = NULL)
    {
        $this->_text      = (string) $text;
        $this->_indexSize = (int) $size;
        $this->_encode    = isset($encode) ? $encode : mb_internal_encoding();
        $this->_textSize  = mb_strlen($text, $this->_encode);
    }

    /**
     * 配列として出力
     *
     * @return array N-gram の配列
     */
    public function toArray()
    {
        $result = array();
        foreach ($this as $index) {
            $result[] = $index;
        }
        return $result;
    }

    /**
     * イテレータのポインタの初期化
     *
     * @return void
     * @see    Iterator
     */
    public function rewind()
    {
        $this->_pointer = 0;
    }

    /**
     * イテレータの現在のポインタの取得
     *
     * @return int
     * @see    Iterator
     */
    public function key()
    {
        return $this->_pointer;
    }

    /**
     * イテレータの現在の要素の取得
     *
     * @return mixed
     * @see    Iterator
     */
    public function current()
    {
        return $this->_getFromOffset($this->_pointer);
    }

    /**
     * イテレータのポインタを次に進める
     *
     * @return void
     * @see    Iterator
     */
    public function next()
    {
        $this->_pointer++;
    }

    /**
     * イテレータに次の要素があるか
     *
     * @return bool 次の要素があれば true
     */
    public function valid()
    {
        return $this->_pointer < $this->_textSize - ($this->_indexSize - 1);
    }

    /**
     * イテレータのポインタを $offset 番目に移動する
     *
     * @param  int  $offset
     * @return void
     * @throws OutOfBoundsException
     */
    public function seek($offset)
    {
        if (isset($this[$offset])) {
            $this->_pointer = $offset;
        } else {
            throw new \OutOfBoundsException("The offset '{$offset}' doesn't exist on the object.");
        }
    }

    /**
     * N-gram インデックスの要素数
     *
     * @return int
     * @see    Countable
     */
    public function count()
    {
        return $this->_textSize - ($this->_indexSize - 1);
    }

    /**
     * N-gram インデックスに $offset 番目の要素が存在するか
     *
     * @param  int  $offset
     * @return bool 要素が存在すれば true
     * @see    ArrayAccess
     */
    public function offsetExists($offset)
    {
        $offset = (int) $offset;
        return 0 <= $offset && $offset < $this->count();
    }

    /**
     * N-gram インデックスから $offset 番目の要素を取得
     * $ngram[$offset] という形式で、配列のようにアクセスできる
     *
     * @param  int    $offset
     * @return string
     * @see    ArrayAccess
     */
    public function offsetGet($offset)
    {
        $offset = (int) $offset;
        return $this->_getFromOffset($offset);
    }

    /**
     * ArrayAccess interface のセッターメソッド
     * ただし、\Text\Ngram はイミュータブルなので、使用不可
     *
     * @param  $offset
     * @param  $val
     * @return void
     * @throws BadMethodCallException
     * @see    ArrayAccess
     */
    public function offsetSet($offset, $val)
    {
        throw new \BadMethodCallException('\Text\Ngram object is immutable.');
    }

    /**
     * ArrayAccess interface の要素削除メソッド
     * ただし、\Text\Ngram はイミュータブルなので、使用不可
     *
     * @param  $offset
     * @return void
     * @throws BadMethodCallException
     * @see    ArrayAccess
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('\Text\Ngram object is immutable.');
    }

    /**
     * インデックスの長さを取得
     *
     * @return int
     */
    public function getIndexSize()
    {
        return $this->_indexSize;
    }

    /**
     * 文書の文字数を取得
     *
     * @return int
     */
    public function getTextSize()
    {
        return $this->_textSize;
    }

    /**
     * 指定した位置からインデックスを取得
     *
     * @param  int    $offset
     * @return string 転地インデックスの一要素
     */
    private function _getFromOffset($offset)
    {
        return mb_substr($this->_text, $offset, $this->_indexSize, $this->_encode);
    }

    /**
     * オブジェクトの文字列表現
     * カンマ区切りで出力する
     *
     * @return string
     */
    public function __toString()
    {
        return join(',', $this->toArray());
    }
}
