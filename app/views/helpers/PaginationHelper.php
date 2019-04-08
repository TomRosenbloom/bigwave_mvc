<?php

class PaginationHelper
{
    /*
     * takes an array of pagination links and wraps in 
     * Bootstrap html and css
     * 
     * input array will be like 
     * array[0]=>'<a href="page=1">First</a>'
     * array[1]=>'<a href="page=1"><<</a>'
     * array[2]=>'<a href="page=2">2</a>'
     * etc.
     * 
     * NB this is kind of bad programming practice because it violates the 
     * principle behind "programme to interface, not implementation"
     * i.e. you have to know in detail what the form of the links array will be
     * But that seems hard to avoid...?
     */
    function bootstrapify(paginator $paginator)
    {
        $links_array = $paginator->links_array_textual();
        $current_page = $paginator->get_current();
        
        echo '<ul class="pagination">';
        foreach($links_array as $link){
            if(preg_match('/<a href="[[:alnum:]=?\.\/_]*">([[:alnum:]]*|\>\>|\<\\<)<\/a>/',$link ,$matches)){ // if it's a link
                $link_text = $matches[1]; // capture the link text
                if(
                    $link_text == $current_page || (
                    $link_text == 'First' && $current_page == 1) || (
                    $link_text == 'Last' && $current_page == $paginator->get_last())
                    ){ // make link active by setting class on li
                    echo '<li class="page-item active">';            
                } else { // ...or not
                    echo '<li class="page-item">';
                }
                echo preg_replace('/a href/', 'a class = "page-link" href', $link); // add Bootstrap class on link
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}
