<?php

class PaginationHelper
{
    /*
     * takes a string of pagination links and adds list elements and bootstrap classes
     */
    function bootstrapify($links_array)
    {
        // input string will be a bunch of links <a href="page=1">1</a>
        // plus an ellipsis or two...
        // [what if we want to have next previous first last instead of just numbers?
        // Perhaps the way to do this is to have the paginator supply the links in 
        // an array?]
        echo '<ul class="pagination">';
        foreach($links_array as $link){
            echo '<li class="page-item">';
            //echo $link;
            if(preg_match('/<a href="[[:alnum:]=?\.\/_]*">[[:alnum:]]*<\/a>/',$link)){
                echo preg_replace('/a href/', 'a class = "page-link" href', $link);
            } else {
                echo '<span class="page-link">' . $link . '</span>';
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}
