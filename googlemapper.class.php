<?php

class GoogleMapper {
    public static function getHeaderData(){
        return '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
        <script src="googlemapper.js"></script>';
    }
    
    public static function get_geomap($lon,$lat){
        return "<div style='width:200px;height:200px;' id='map-canvas' ttl='' lon='".$lon."' lat='".$lat."' ></div>";
    }
    
}

