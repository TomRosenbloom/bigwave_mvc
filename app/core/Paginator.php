<?php

class Paginator
{
    private $_current;
    private $_first = 1;
    private $_last;
    private $_next;
    private $_previous;
    
    private $_range;
    private $_pages;
    private $_per_page;
    private $_total;
    
    private $_limit;
    private $_offset = 0;
    
    private $_message;
    
    function __construct($_page, $_per_page, $_range, $_total) {
        $this->_per_page = $_per_page;
        $this->_range = $_range;
        $this->_total = $_total;
        
        $this->set_current($_page);
        $this->set_pages();
        $this->set_message();
        $this->set_limit($_per_page);
        $this->set_offset();
        $this->set_next();
    }

    
    function get_current() {
        return $this->_current;
    }

    function get_first() {
        return $this->_first;
    }

    function get_last() {
        return $this->_last;
    }

    function get_next() {
        return $this->_next;
    }

    function get_previous() {
        return $this->_previous;
    }

    function get_pages() {
        return $this->_pages;
    }
    
    function get_per_page() {
        return $this->_per_page;
    }

    function get_total() {
        return $this->_total;
    }

    function get_offset() {
        return $this->_offset;
    }

    function get_limit() {
        return $this->_limit;
    }   
    
    function get_message(){
        return $this->_message;
    }
    
    function set_current($_current) {
        $this->_current = $_current;
    }

    function set_first($_first) {
        $this->_first = $_first;
    }

    function set_last($_last) {
        $this->_last = $_last;
    }

    function set_next() {
        $this->_next = intval($this->_current) + 1;
    }

    function set_previous($_previous) {
        $this->_previous = $_previous;
    }

    function set_pages() {
        $this->_pages = ceil($this->_total/$this->_per_page);
    }
    
    function set_per_page($_per_page) {
        $this->_per_page = $_per_page;
    }

    function set_total($_total) {
        $this->_total = $_total;
    }

    function set_offset() {
        $this->_offset = 0;
    }

    function set_limit($_limit) {
        $this->_limit = $_limit;
    }   
    
    function set_message() {
        $this->_message = intval($this->_offset) + 1 . " to " . intval($this->_offset + $this->_per_page) . " of " . $this->_total;
    }
}
