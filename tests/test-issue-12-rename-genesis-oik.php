<?php // (C) Copyright Bobbing Wide 2017-2021

/**
 * Can we confirm that all the genesis_a2z logic has been renamed in the theme?
 * 
 * i.e can we confirm that all the functions have a genesis_a2z_ or ga2z_ prefix?
 */
class Tests_issue_12_rename_genesis_oik extends BW_UnitTestCase {

	public $functionsphp;
	
	/**
	 * 
	 * Finds the name of the functions.php file
	 * `C:\apache\htdocs\wordpress\wp-content\themes\genesis-oik/functions.php`
	 * with \ converted to /
     * and make sure it's loaded
	 */
	function setUp(): void {
		parent::setUp();
		$stylesheet = get_stylesheet();
		if ( 'genesis-a2z' === $stylesheet ) {

			$this->functionsphp=dirname( __DIR__ ) . "/functions.php";
			$this->functionsphp=str_replace( "\\", '/', $this->functionsphp );
			require_once( $this->functionsphp );
		} else {
			echo "Stylesheet is $stylesheet";
		}

	}
	
	/**
	 * Checks if function implemented in functions.php
	 *
	 * Note: We don't allow methods in functions.php
	 * 
	 * @param $infile
	 * @return bool true if this is the theme's functions.php file
	 */
	function isfunctionsphp( $infile ) {
		$infile = str_replace( "\\", '/', $infile );
		//echo $infile . PHP_EOL;
		$isfunctionsphp = false;
		$isfunctionsphp = $infile == $this->functionsphp;
		return( $isfunctionsphp );
	}

	/**
	 * The current theme has to be genesis_a2z otherwise the tests will fail.
	 */
	function test_a_current_theme_is_genesis_a2z() {
		$stylesheet = get_stylesheet();
		$this->assertEquals( 'genesis-a2z', $stylesheet, "Current stylesheet is not genesis-a2z" );
	}
	
	/**
	 * Tests all functions in functions.php are prefixed correctly
	 *
	 */
	function test_all_my_user_functions_prefixed_genesis_a2z() {
		$functions = get_defined_functions();
		foreach ( $functions['user'] as $func ) {
			$f = new ReflectionFunction( $func );
			$infile = $f->getFileName();
			if ( $this->isfunctionsphp( $infile ) ) {
				$allowed = $this->checkfuncprefix( $func );
				$this->assertTrue( $allowed, "func doesn't have allowed prefix for this theme: " . $func );
			} 
		}
	}
	
	/** 
	 * Checks for allowed prefixes
	 *
	 * Note: We'll allow _e_c() until it's been removed from the template files. 
	 * 
	 * @param string $func
	 * @return bool true if it's an allowed prefix
	 */
	function checkfuncprefix( $func ) {
		$allowed_prefixes = array( "genesis_a2z_", "ga2z_", "genesis_oik_", "_e_c" );
		$allowed = false;
		foreach ( $allowed_prefixes as $prefix ) {
			if ( !$allowed ) {
				$allowed = ( 0 === strpos( $func, $prefix ) );
			}
		}
		return $allowed;
	}
	


}
