<?php

namespace Yayheniy\PhpDomDocumentExtension;

use DOMDocument;

/**
 * This class work with PHP DOMDocument
 *
 * @link https://www.php.net/manual/ru/book.dom.php
 */
class DOMDocExtension
{
    protected DOMDocument $dom;
    protected $tree;

    /**
     * @param DOMDocument $dom
     */
    public function __construct(DOMDocument $dom)
    {
        $this->dom = $dom;
    }

    /**
     * Accepts document as string, and returns self object class.
     *
     * @param string $page
     * @return DOMDocExtension
     */
    public static function loadHTML(string $page)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($page);

        return new self($dom);
    }

    /**
     * Pass through every node DOMDocument, and evokes anonymous function in every node,
     * closing object DOMNode in function.
     *
     * @param callable $function
     * @return array|null
     */
    public function passDom(Callable $function)
    {
        return $this->tree = $this->readNodes($this->dom, $function);
    }

    /**
     * Service recursive function
     *
     * @param $dom
     * @param callable $function
     * @return array|void
     */
    protected function readNodes($dom, Callable $function)
    {
        $f = $function->bindTo($dom);
        $counter =1;
        if ($dom->childNodes->length) {
            $arr = [];
            foreach ($dom->childNodes as $node) {
                $arr[$counter++ . '_' . $node->nodeName] = $this->readNodes($node, $function);
            }
            $f();

            return $arr;
        } else {
            $f();
        }
    }
}
