<?php

class Paginator
{
    private $_current;
    private $_first = 1;
    private $_last;
    private $_next;
    private $_previous;
    
    private $_range = 5;
    private $_pages;
    private $_per_page;
    private $_total;
    
    private $_limit;
    private $_offset = 0;
    
    private $_message;
    private $_links;
    
    function __construct($_page, $_per_page, int $_range, $_total) {
        $this->_per_page = $_per_page;
        $this->_range = $_range;
        $this->_total = $_total;
        
        // So here I have a mixture of assigning properties directly and using getters/setters
        // what is the best policy? NB seems like I've mostly used direct assignment for
        // vars that come as params to the constructor...
        
        $this->set_current($_page);
        $this->set_pages();
        $this->set_offset();
        $this->set_limit($_per_page);
        $this->set_next();
        $this->set_message();
        $this->set_links();
    }

    
    function get_current() {
        return $this->_current;
    }

    function get_first() {
        return $this->_first;
    }

    function get_last() {
        return $this->_pages;
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
    
    function get_links(){
        return $this->_links;
    }
    
    function get_range(){
        return $this->_range;
    }
      
    function set_current($_current) {
        $this->_current = intval($_current);
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
        $this->_offset = ($this->_current - 1) * $this->_per_page;
    }

    function set_limit($_limit) {
        $this->_limit = $_limit;
    }   
    
    function set_message() {
        $this->_message = intval($this->_offset) + 1 . " to " . intval($this->_offset + $this->_per_page) . " of " . $this->_total;
    }
    
    /**
     * sets $this->_links, a text tring of pagination links (and ellipsis or two)
     * 
     */
    function set_links() {
        $links = '';
        if($this->_current < $this->_range){ // starting end condition
            $i = 1; 
            while($i <= $this->_range && $i <= $this->_pages){
                $links .= '<a href="' . UrlHelper::current() . '?page=' . $i . '">' . $i . "</a>";
                $i++;
            }
            $links .= '...';
            $links .= '<a href="' . UrlHelper::current() . '?page=' . $this->get_last() . '">' . $this->get_last() . "</a>";
        } elseif($this->_current > $this->get_last() - $this->_range) { // ending end condition
            $links = '<a href="' . UrlHelper::current() . '?page=' . $this->get_first() . '">' . $this->get_first() . "</a>";
            $links .= '...';            
            $i = $this->get_last() - $this->_range; 
            while($i <= $this->_pages){
                $links .= '<a href="' . UrlHelper::current() . '?page=' . $i . '">' . $i . "</a>";
                $i++;
            }            
        } else { // middle condition
            $links = '<a href="' . UrlHelper::current() . '?page=' . $this->get_first() . '">' . $this->get_first() . "</a>";
            $links .= '...';
            for($i = $this->_current - floor(($this->_range/2)); $i < $this->_current + ceil(($this->_range/2)); $i++){
                $links .= '<a href="' . UrlHelper::current() . '?page=' . $i . '">' . $i . '</a>';
            }
            $links .= '...';
            $links .= '<a href="' . UrlHelper::current() . '?page=' . $this->get_last() . '">' . $this->get_last() . "</a>";
        }
        
        $this->_links = $links;
    }
    
    /**
     * returns pagination links as an array
     * 
     * Each member of the array is a string, either a link like
     * '<a href="current/url?page=1">1</a>' or an ellipsis
     * The point of this is that an array is easy to post-process in a view helper
     * e.g. to add Bootstrap HTML and CSS
     *  
     * @return array
     */
    public function links_array() {
         $links = [];
        if($this->_current < $this->_range){ // starting end condition
            $i = 1; 
            while($i <= $this->_range && $i <= $this->_pages){
                $links[$i] = '<a href="' . UrlHelper::current() . '?page=' . $i . '">' . $i . '</a>';
                $i++;
            }
            $links[] = '...';
            $links[] = '<a href="' . UrlHelper::current() . '?page=' . $this->get_last() . '">' . $this->get_last() . "</a>";
        } elseif($this->_current > $this->get_last() - $this->_range) { // ending end condition
            $links[] = '<a href="' . UrlHelper::current() . '?page=' . $this->get_first() . '">' . $this->get_first() . "</a>";
            $links[] = '...';            
            $i = $this->get_last() - $this->_range; 
            while($i <= $this->_pages){
                $links[$i] = '<a href="' . UrlHelper::current() . '?page=' . $i . '">' . $i . "</a>";
                $i++;
            }            
        } else { // middle condition
            $links[] = '<a href="' . UrlHelper::current() . '?page=' . $this->get_first() . '">' . $this->get_first() . "</a>";
            $links[] = '...';
            for($i = $this->_current - floor(($this->_range/2)); $i < $this->_current + ceil(($this->_range/2)); $i++){
                $links[$i] = '<a href="' . UrlHelper::current() . '?page=' . $i . '">' . $i . '</a>';
            }
            $links[] = '...';
            $links[] = '<a href="' . UrlHelper::current() . '?page=' . $this->get_last() . '">' . $this->get_last() . "</a>";
        }
        
        return $links;       
    }
    
    public function links_array_textual() {
        $links = [];
        $links[] = '<a href="' . UrlHelper::current() . '?page=1">First</a>';
        
        if($this->_current < $this->_range){ // starting end condition
            
            $i = 2; 
            while($i <= $this->_range && $i <= $this->_pages){
                $links[$i] = '<a href="' . UrlHelper::current() . '?page=' . $i . '">' . $i . '</a>';
                $i++;
            }
            $links[] = '<a href="' . UrlHelper::current() . '?page=' . strval($i + 1) . '">>></a>';
            
        } elseif($this->_current > $this->get_last() - $this->_range) { // ending end condition
            
            $links[] = '<a href="' . UrlHelper::current() . '?page=' . strval($this->_current - floor($this->_range/2)) . '"><<</a>';
            $i = $this->get_last() - $this->_range; 
            while($i < $this->_pages){
                $links[$i] = '<a href="' . UrlHelper::current() . '?page=' . $i . '">' . $i . "</a>";
                $i++;
            }  
            
        } else { // middle condition
            
            $links[] = '<a href="' . UrlHelper::current() . '?page=' . strval($this->_current - floor($this->_range/2)) . '"><<</a>';
            for($i = $this->_current - floor(($this->_range/2)); $i < $this->_current + ceil(($this->_range/2)); $i++){
                $links[$i] = '<a href="' . UrlHelper::current() . '?page=' . $i . '">' . $i . '</a>';
            }
            $links[] = '<a href="' . UrlHelper::current() . '?page=' . strval($this->_current + ceil($this->_range/2)) . '">>></a>';
            
        }
        $links[] = '<a href="' . UrlHelper::current() . '?page=' . $this->get_last() . '">Last</a>';
        return $links;       
    }
    
}
