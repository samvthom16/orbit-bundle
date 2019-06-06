<?php

class ORBIT_UTIL extends ORBIT_BASE{

  /*
  * USED BETWEEN ORBIT SEARCH & ORBIT QUERY
  * TO PASS PARAMETERS OF THE SHORTCODE
  */
  function paramsToString( $params ){

    $data = array(
      'tax'   => array(),
      'date'  => array()
    );

    if( is_array ( $params ) && ( count( $params ) >= 1 ) ){

      /* USER VALUES FROM GET PARAMETERS */
      foreach( $params as $slug => $value ){

        /* DIFFERENTIATING FIELD TYPE AND VALUE */
        $slug_arr = explode( '_', $slug );

        /* FOR CHECKBOX */
        if( is_array( $value ) ){ $value = implode( ',', $value ); }

        if( count( $slug_arr ) > 1 && $value ){
          // LOOK FOR FIELD TYPE
          switch( $slug_arr[0] ){
            case 'tax':
              array_push( $data['tax'], $slug_arr[1].":".$value );
              break;

            case 'postdate':
              array_push( $data['date'], $slug_arr[1].":".$value );
              break;
          }
        }
      }
    }

    // ITERATE THROUGH THE DATA ARRAY AND CONVERT THEM INTO STRING EQUIVALENT
    foreach( $data as $slug => $value_arr ){
      if( is_array( $value_arr ) ){
        $data[ $slug ] = implode('#', $value_arr );
      }
    }

    return $data;
  }

  /*
  * CONVERT STRING INTO AN ARRAY USING A SEPERATOR
  * WRAPPER FOR EXPLODE FUNCTION IN PHP
  */
  function explode_to_arr( $str, $seperator = ',' ){
		return ! empty( $str ) ? explode( $seperator, $str ) : '';
	}

  /*
  * ORBIT QUERY
  * TAXONOMY QUERY PARAMS FROM STRING
  */
  function getTaxQueryParams( $tax_query_str ){
    $tax_arr = $this->explode_to_arr( $tax_query_str, "#" );
    $tax_query = array();
    foreach( $tax_arr as $tax ){
      $temp = $this->explode_to_arr( $tax, ':' );
      if( count( $temp ) > 1 ){
        array_push( $tax_query,
          array(
            'taxonomy'	=> $temp[0],
            'field'		=> 'slug',
            'terms'		=> $this->explode_to_arr( $temp[1] )
          )
        );
      }
    }
    return $tax_query;
  }

  /*
  * ORBIT QUERY
  * DATE QUERY PARAMS FROM STRING
  */
  function getDateQueryParams( $date_query_str ){
    $date_query = array();
    $date_arr = $this->explode_to_arr( $date_query_str, "#" );
    foreach( $date_arr as $value_str ){
      $temp = $this->explode_to_arr( $value_str, ':' );
      if( count( $temp ) > 1 ){ $date_query[ $temp[0] ] = $temp[1]; }
    }
    return $date_query;
  }

  /*
  * CREATE SHORTCODE FROM AN ARRAY OF ATTRIBUTES
  * CAN BE USED IN THE FUTURE FOR SITEORIGIN WIDGETS
  * CURRENTLY USED WITHIN: ORBIT QUERY
  * ACCEPTS THE FOLLOWING PARAMETERS: SHORTCODE STRING, OBJECT OF ARRAY, ATTRIBUTES OF THE SHORTCODE THAT NEEDS TO BE ACCEPTED
  */
  function createShortcode( $shortcode, $params, $atts ){
    $shortcode_str = "[".$shortcode;

    // ITERATE THROUGH EACH PARAMS AND ONLY ASSIGN THOSE THAT ARE PART OF TEH SHORTCODE ATTRIBUTES
    foreach( $params as $slug => $value ){
      if( in_array( $slug, $atts ) ){ $shortcode_str .= " ".$slug."='".$value."'"; }
    }
    $shortcode_str .= "]";
    return $shortcode_str;
  }

  
}

ORBIT_UTIL::getInstance();