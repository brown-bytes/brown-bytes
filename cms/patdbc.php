<?php
/**
*	@package	patDbc
*	@author 	Stephan Schmidt <schst@php-tools.de>, Gerd Schauffelberger <gerd@php-tools.de>
*	@version	0.81
*/

define( "patDBC_CLOSED", 0 );
define( "patDBC_OPEN", 1 );
define( "patDBC_TYPEBOTH", 2 );
define( "patDBC_TYPEASSOC", 3 );
define( "patDBC_TYPENUM", 4 );

/**
*	generic result object, will be extended by result objects for databases
*
*	@author 	Stephan Schmidt <schst@php-tools.de>, Gerd Schauffelberger <gerd@php-tools.de>
*	@package	patDbc
*/
	class	patDbcResult
{
/**
*	@var	int	$id		ressource id of the result object
*/
	var	$id;

/**
*	set the result identifier
*
*	@param	int	$id		result identifier
*/
	function	setIdentifier( $id )
	{
		$this->id	=	$id;
	}

/**
*	fetch one row of the result
*
*	@access	public
*	@return	array	$row	result row
*/
	function	fetch_row()
	{
		return	array();
	}

/**
*	fetch one row of the result as assocciative array
*
*	@access	public
*	@param  int 	$result_type	type of result (patDBC_TYPEBOTH, patDBC_TYPEASSOC, patDBC_TYPENUM)
*	@return	array	$row	result row
*/
	function	fetch_array( $result_type = patDBC_TYPEBOTH )
	{
		return	array();
	}

/**
*	get the complete result as two dimensional array
*
*	@access	public
*	@param  int 	$result_type	type of result (patDBC_TYPEBOTH, patDBC_TYPEASSOC, patDBC_TYPENUM)
*	@return	array	$data	result
*/
	function	get_result( $result_type = patDBC_TYPEBOTH )
	{
		$i	=	0;
		while	( $row = $this->fetch_array( $result_type ) )
		{
			$data[$i]	=	$row;
			$i++;
		}


		return	$data;
	}

/**
*	get the number of rows in the result
*
*	@access	public
*	@return	int	$rows	number of rows
*/
	function	num_rows()
	{
		return	0;
	}

/**
*	get the number of fields in the result
*
*	@access	public
*	@return	int	$field	number of field
*/
	function	num_fields()
	{
		return	0;
	}

/**
*	free used memory used by the result
*
*	@access	public
*	@return	boolean	$success	true if mem was freed
*/
	function	free()
	{
		return	false;
	}

/**
*	dump the result
*
*	@access	public
*/
	function	dump()
	{
		echo	"<table border=\"1\" cellpadding=\"2\" cellspacing=\"0\">\n";
		for	( $i=0; $i<$this->num_rows(); $i++ )
		{
			echo	"<tr>";
			$this->dumpRow( $this->fetch_array() );
			echo	"</tr>\n";
		}
		echo	"</table>";
	}

/**
*	dump one row
*	@param	array	$row	array containing one row
*/
	function	dumpRow( $row )
	{
		$i		=	0;
		while( list( $key, $val ) = each( $row ) )
		{
			if	($i%2)
				echo	"<td><b>".$key."</b></td><td>".$val."</td>\n";
			$i++;
		}
	}
}

/**
*	mySql result object
*	result object returned by the query() method of the patMySqlDbc
*
*	@package	patDbc
*	@author		Stephan Schmidt <schst@php-tools.de>
*/
	class	patMySqlResult extends patDbcResult
{
/**
*	fetch one row of the result
*
*	@access	public
*	@return	array	$row	result row
*/
	function	fetch_row()
	{
		return	mysql_fetch_row( $this->id );
	}

/**
*	fetch one row of the result as assocciative array
*
*	@access	public
*	@param  int 	$result_type	type of result (patDBC_TYPEBOTH, patDBC_TYPEASSOC, patDBC_TYPENUM)
*	@return	array	$row	result row
*/
	function	fetch_array( $result_type = patDBC_TYPEBOTH )
	{
		if ( $result_type == patDBC_TYPEASSOC ) { $result_type = MYSQL_ASSOC; }
		else if ( $result_type == patDBC_TYPENUM ) { $result_type = MYSQL_NUM; }
		else { $result_type = MYSQL_BOTH; }

		return	mysql_fetch_array( $this->id, $result_type );
	}

/**
*	fetch one row of the result as an object
*
*	@access	public
*	@param  integer			$result_type	type of result (patDBC_TYPEBOTH, patDBC_TYPEASSOC, patDBC_TYPENUM)
*	@return	object result	$row	result row
*/
	function	fetch_object( $result_type = patDBC_TYPEBOTH )
	{
		if ( $result_type == patDBC_TYPEASSOC ) { $result_type = MYSQL_ASSOC; }
		else if ( $result_type == patDBC_TYPENUM ) { $result_type = MYSQL_NUM; }
		else { $result_type = MYSQL_BOTH; }

		return	mysql_fetch_object( $this->id, $result_type );
	}

/**
*	get the number of rows in the result
*
*	@access	public
*	@return	int	$rows	number of rows
*/
	function	num_rows()
	{
		return	mysql_num_rows( $this->id );
	}

/**
*	get the number of fields in the result
*
*	@access	public
*	@return	int	$field	number of field
*/
	function	num_fields()
	{
		return	mysql_num_fields( $this->id );
	}

/**
*	move the internal row pointer of the result
*
*	@access	public
*	@param	int	$row_number		row number
*/
	function	data_seek( $row_number )
	{
		mysql_data_seek( $this->id, $row_number );
	}

/**
*	fetch information about a field of the result
*
*	@access	public
*	@param	integer			$offset	field offset (starting with 0)
*	@return	object field	$field	object containing field information
*/
	function	fetch_field( $offset = 0 )
	{
		return	mysql_fetch_field( $this->id ,$offset );
	}

/**
*	get the name of a field
*
*	@access	public
*	@param	integer		$offset	field offset (starting with 0)
*	@return	string		$field	fieldname
*/
	function	field_name( $offset )
	{
		return	mysql_field_name( $this->id, $offset );
	}

/**
*	returns the length of the specified field
*
*	@access	public
*	@param	int		$offset	field offset (starting with 0)
*	@return	int		$length	field length
*/
		function	field_len( $offset )
		{
			return	mysql_field_len( $this->id, $offset );
		}

/**
*	Seeks to the specified field offset.
*
*	@access	public
*	@param	int		$offset	field offset (starting with 0)
*/
		function	field_seek( $offset )
		{
			mysql_field_seek( $this->id, $offset );
		}
/**
*	free used memory used by the result
*
*	@access	public
*	@return	boolean	$success	true if mem was freed
*/
	function	free()
	{
		return	mysql_free_result( $this->id );
	}
}

/**
*	MySql Abstraction
*	provides commonly needed mysql functions
*
*	@package	patDbc
*	@access		public
*/
	class	patMySqlDbc
{
	var	$dbhost, $dbuser, $dbpass, $dbname;
	var	$dbid;
	var	$status;

/**
*	Create new MySql database connectivity
*
*	@access	public
*	@param	string	$dbhost	hostname
*	@param	string	$dbname	name of the database
*	@param	string	$dbuser	name of the user
*	@param	string	$dbpass	password for the user
*/
	function	patMySqlDbc( $dbhost, $dbname, $dbuser, $dbpass )
	{
		$this->dbhost	=	$dbhost;
		$this->dbname	=	$dbname;
		$this->dbuser	=	$dbuser;
		$this->dbpass	=	$dbpass;

		$this->status	=	patDBC_CLOSED;
	}

/**
*	Open the MySql connection
*
*	@access	public
*	@param	boolean	$die		if set to true, the script will exit if the connection could not be established
*	@param	boolean	$persistent	if set to true, a persistent connection will be opened
*	@return	boolean	$success	true, if connection could be opened
*/
	function	connect( $die = true, $persistent = false )
	{
		if	( $this->status != patDBC_OPEN )
		{
			if( $persistent )
				$this->dbid	=	@mysql_pconnect( $this->dbhost, $this->dbuser, $this->dbpass );
			else
				$this->dbid	=	@mysql_connect( $this->dbhost, $this->dbuser, $this->dbpass );

			mysql_select_db( $this->dbname, $this->dbid );
			mysql_set_charset("utf8", $this->dbid);

			if ( !$this->dbid )
			{
				if( $die ) {
echo "ERROR: SQL CONNECT\n".
				"Host:       ".$this->dbhost."\n".
				"Database:   ".$this->dbname."\n".
				"User:       ".$this->dbuser."\n".
				"MySQL said: ".mysql_error().
				// "\n\n_SERVER:    \n".$this->print_($_SERVER,0)."\n\n\n".
				// "\n_POST:      \n".$this->print_($_POST,0)."\n\n\n".
				// "\n_GET:       \n".$this->print_($_GET,0)."\n\n\n".
				"";
			die ("<i>An error occured. The sysadmin was notified. Please hit your browsers back button and try again.</i>");
				}
				return	false;
			}

		}
		$this->status	=	patDBC_OPEN;
		return	true;
	}


	function print_($array,$indent=0) {
		$str = "";
		while (list($k,$v) = each($array)) { if (!isset($longest_key) || $longest_key < strlen($k)) $longest_key = strlen($k); }
		reset($array);
		while (list($k,$v) = each($array)) {
			$str .= str_repeat(" ",$indent)."[$k]".str_repeat(" ",$longest_key - strlen($k))." => [$v]\n";
			if (count($v) > 1) {
				$str .= print_($v,($indent+5));
			}
		}
		return $str;
	}
/**
*	Close the MySql connection
*
*	@access	public
*/
	function	close()
	{
		if	( $this->status == patDBC_OPEN )
			mysql_close( $this->dbid );
	}

/**
*	get fieldnames of a mysql table
*
*	@access	public
*	@param	mixed	$table		name of the table or array containing names of several tables
*	@return	array	$result		fieldnames
*/
	function	get_fields( $table )
	{
		if ( !is_array($table) ) { $table = array($table);}

		$result = array();
		while ( list($key, $value) = each($table))
		{
			$fields = mysql_list_fields( $this->dbname, $value );
			$columns = mysql_num_fields( $fields );
			for ( $i = 0; $i < $columns; $i++)
			{
				array_push( $result , mysql_field_name($fields, $i) );
			}
		}
		return array_unique($result);
	}

/**
*	get the last insert id for this connection
*
*	@access	public
*	@return	int	$id		last generated insert id
*/
	function	insert_id()
	{
		return	mysql_insert_id( $this->dbid );
	}

/**
*	count the number of affected rows
*
*	@access	public
*	@return	int	$rows	number of affected rows by the last query
*/
	function	affected_rows()
	{
		return	mysql_affected_rows( $this->dbid );
	}

/**
*	drop a database
*
*	@access	public
*	@param	string	$database_name	name of the db to drop, if no name is given the db of the connection will be dropped
*	@return	bool	$success		true if the db was dropped, false otherwise
*/
	function	drop_db( $database_name = "" )
	{
		if( $database_name == "" )
			$database_name	=	$this->dbname;

		return	mysql_drop_db( $database_name,  $this->dbid );
	}

/**
*	create a database
*
*	@access	public
*	@param	string	$database_name	name of the db to create, if no name is given the name of the db used for the connection will be used
*	@return	bool	$success		true if the db was created, false otherwise
*/
	function	create_db( $database_name = "" )
	{
		if( $database_name == "" )
			$database_name	=	$this->dbname;

		return	mysql_create_db( $database_name, $this->dbid );
	}

/**
*	Send a query to mysql
*
*	@access	public
*	@param	string	$query		query that should be send
*	@return	object patDbcResult	$result	patMySql Result
*/
	function	query( $query )
	{
		if	( $this->status==patDBC_CLOSED )
			$this->connect();

		$result_id	=	mysql_query( $query, $this->dbid );

		if	(!$result_id) {
			echo "ERROR: SQL QUERY\n".
				"Host:       ".$this->dbhost."\n".
				"Database:   ".$this->dbname."\n".
				"User:       ".$this->dbuser."\n".
				"Query:      ".$query."\n".
				"MySQL said: ".mysql_error().
				// "\n\n_SERVER:    \n".$this->print_($_SERVER,0)."\n\n\n".
				// "\n_POST:      \n".$this->print_($_POST,0)."\n\n\n".
				//"\n_GET:       \n".$this->print_($_GET,0)."\n\n\n".
				"";
			die ("<i>An error occured. The sysadmin was notified. Please hit your browsers back button and try again.</i>");
		}

		$result		=	new	patMySqlResult;
		$result->setIdentifier( $result_id );

		return	$result;
	}
}
?>