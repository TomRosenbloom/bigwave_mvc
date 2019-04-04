<?php

class PaginationHelper
{
    /*
     * takes an array of pagination links and wraps in 
     * Bootstrap html and css
     * 
     * input array will be like array[1]=>'<a href="page=1">1</a>'
     * or sometimes array[n]=>'...'
     * 
     * this is going a little awry as I try to include 'active' class
     * and I can see it go further wrong if I want to allow options re using 
     * text 'first' 'prev' 'next' 'last' and whether or not to make them active...
     * maybe need to pass the paginator object in to this func?
     */
    function bootstrapify(paginator $paginator)
    {
        $links_array = $paginator->links_array();
        $current_page = $paginator->get_current();
        
        echo '<ul class="pagination">';
        foreach($links_array as $link){
            if(preg_match('/<a href="[[:alnum:]=?\.\/_]*">[[:alnum:]]*<\/a>/',$link)){ // if it's a link
                if(preg_match('/<a href="[[:alnum:]=?\.\/_]*">(' . $current_page . ')<\/a>/', $link)){ // active link (current page)
                    echo '<li class="page-item active">';
                } else {
                    echo '<li class="page-item">';
                }
                echo preg_replace('/a href/', 'a class = "page-link" href', $link);
            } else { // it's an ellisis
                echo '<span class="page-link">' . $link . '</span>';
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}
