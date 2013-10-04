<?php namespace SeeClickFixSDK\Net;

/**
 * Client Interface
 *
 * All clients must implement this interface
 *
 * The 4 http functions just need to return the raw data from the API
 */
interface ClientInterface {

    function get( $url, array $data = null );
    function post( $url, array $data = null );
    function put( $url, array $data = null );
    function delete( $url, array $data = null );

}