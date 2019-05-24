<?php

namespace Rapid\Models;

use Illuminate\Support\Collection;

class Pagination
{

    public $model;

	public function __construct($model)
    {
        $this->model = $model;
    }

    public function getHtml($ipp, $page, $module, $total = false)
    {

        // if($total == 0) {
        //     return false;
        // }

        // if( ! $total ) {
        //     $total = $this->model->total();
        // }

        $total = $this->model->total();

        if($total < $ipp) {
            return array('html'=>'' ,'limit'=>false,'offset'=>false);
        }
        // limit 
        $limit = $ipp;

        // offset
        if($page > 1) {
            $offset = $ipp * ($page -1);    
        }else{
            $offset = 0;
        }    

        // html
        $first = 1;
        $last = ceil( $total / $ipp);

        $raio = array();
        for($i = $page; $i < $page+5; $i++) {
            if($i <= $last) {
                $raio[$i] = $i;
            }
        }   

        for($i = $page; $i > $page-5; $i--) {
            if($i >= $first && $i < $last) {
                $raio[$i] = $i;
            }
        }   

        ksort($raio);

        $url = "/$module/";

        $html = '<ul class="pagination pagination-md" style="margin-right:4px;">';
        if($first == $page) {
            $html .= '<li class="disabled">';
            $html .=    "<a href=\"\">";
            $html .=        _('Primeira');
            $html .=    '</a>';
            $html .= '</li>';
        }else{
            $html .= '<li>';
            $html .=    "<a href=\"$url$first\">";
            $html .=        _('Primeira');
            $html .=    '</a>';
            $html .= '</li>';
        }
        $html .= '</ul>';

        $_page = (int)$page-1;
        $html .= '<ul class="pagination pagination-md">';
        
        if($_page >= $first) {
            $html .= '<li>';
            $html .=    "<a href=\"$url$_page\">";
            $html .=        '&laquo;';
            $html .=    '</a>';        
            $html .= '</li>';
        }else{
            $html .= '<li class="disabled">';
            $html .=    "<a href=\"#\">";
            $html .=        '&laquo;';
            $html .=    '</a>';
            $html .= '</li>';
        }
        

        foreach($raio as $_raio) {
            if($_raio == $page ) {
                $html .= '<li class="active">';
                $html .=    '<a href="">';
                $html .=        "$page";
                $html .=    '</a>';
                $html .= '</li>';
            }else{
                $html .= '<li>';
                $html .=    "<a href=\"$url$_raio\">";
                $html .=        $_raio;
                $html .=    '</a>';
                $html .= '</li>';
            }
        }

        $_page = (int)$page+1;
        
        if($_page <= $last) {
            $html .= '<li>';
            $html .=    "<a href=\"$url$_page\">";
            $html .=        '&raquo;';
            $html .=    '</a>';        
            $html .= '</li>';
        }else{
            $html .= '<li class="disabled">';
            $html .=    "<a href=\"#\">";
            $html .=        '&raquo;';
            $html .=    '</a>';
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        $html .= '<ul class="pagination pagination-md" style="margin-left:4px;">';


        if($page == $last) {
            $html .= '<li class="disabled">';
            $html .=    "<a href=\"#\">";
            $html .=        _('Última');
            $html .=    '</a>';
            $html .= '</li>';
        }else{
            $html .= '<li>';
            $html .=    "<a href=\"$url$last\">";
            $html .=        _('Última');
            $html .=    '</a>';
            $html .= '</li>';
        }

        $html .= '</ul>';

        return array('html'=>$html,'limit'=>$ipp,'offset'=>$offset);

    }

}