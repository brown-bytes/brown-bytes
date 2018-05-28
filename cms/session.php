<?php
class session {

    /*****************************
    ** func - sessions()
    ** @Constructor
    ** @Access - public
    ** @Desc - The cunstructor used for warming up
    ** and preparing the sessions.
    ** @params - None
    *****************************/
    function __construct()
    {
        // Let's initialise the sessions
        @session_start();
    }


    /*****************************
    ** func - set_var()
    ** @Access - public
    ** @Desc - Set a session variable
    ** @param $var_name - the variable name
    ** @paran $var_val  - value for $$var_name
    *****************************/
    function set( $var_name, $var_val )
    {
        if( !$var_name)
        {
            return false;
        }
        $_SESSION[$var_name] = $var_val;
    }


    function has($var_name) {
    	return isset($_SESSION[$var_name]);
    }


    /*****************************
    ** func - get_var()
    ** @Access - public
    ** @Desc - Get a session variable
    ** @param $var_name -  the variable name to be retrieved
    *****************************/
    function get( $var_name )
    {
        return (isset($_SESSION[$var_name]) ? $_SESSION[$var_name] : "");
    }


    /*****************************
    ** func - delete_var()
    ** @Access - public
    ** @Desc - Delete a session variable
    ** @param $var_name -  the variable name to be deleted
    *****************************/
    function del( $var_name )
    {
        unset( $_SESSION[$var_name] );
    }


    /*****************************
    ** func - delete_vars()
    ** @Access - public
    ** @Desc - Delete session variables contained in an array
    ** @param $arr -  Array of the elements
    ** to be deleted
    *****************************/
    function dels( $arr )
    {
        if( !is_array( $arr ) )
        {
            return false;
        }
        foreach( $arr as $element )
        {
            unset( $_SESSION[$element] );
        }
        return true;
    }


    /*****************************
    ** func - delete_all_vars()
    ** @Access - public
    ** @Desc - Delete all session variables
    ** @params - None
    *****************************/
    function wipe()
    {
        del_all_vars();
    }


    /*****************************
    ** func - end_session()
    ** @Access - public
    ** @Desc - Des! ! troy the session
    ** @params - None
    *****************************/
    function end()
    {
        $_SESSION = array();
        session_destroy();
    }

}
?>