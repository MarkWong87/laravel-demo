<?php

/**
 * Created by PhpStorm.
 * User: markWong
 * Date: 2017/3/27
 * Time: 下午12:24
 */

class MyDbUnit extends PHPUnit_Extensions_Database_TestCase
{
    /* 数据库连接 ---> */
    // 只实例化 pdo 一次，供测试的清理和装载基境使用
    static private $pdo = null;

    // 对于每个测试，只实例化 PHPUnit_Extensions_Database_DB_IDatabaseConnection 一次
    private $conn = null;

    private $m_ArrDataInit = null;

    private $arrTable = [];

    public function getConnection()
    {
        $sDbDSN 	= 'mysql:dbname=' . env( 'DB_DATABASE' ) . ';host=' . env( 'DB_HOST' ) . ';charset=utf8';
        $sDBUser 	= env( 'DB_USERNAME' );
        $sDBPasswd 	= env( 'DB_PASSWORD' );
        $sDbName 	= env( 'DB_DATABASE' );

        if ( $this->conn === null )
        {
            if ( self::$pdo == null )
            {
                self::$pdo = new PDO( $sDbDSN, $sDBUser, $sDBPasswd );
            }
            $this->conn = $this->createDefaultDBConnection( self::$pdo, $sDbName );
        }
        return $this->conn;
    }

    public function initDbValue()
    {
        $this->setUp();
    }


    /**
     * @param array $data
     * @param array $arrInit
     */
    public function __construct( array $data, array $arrInit )
    {
        $this->m_ArrDataInit = $arrInit;
        foreach ( $data AS $tableName => $rows )
        {
            $columns = [];
            if ( isset( $rows[0] ) ) {
                $columns = array_keys( $rows[0] );
            }

            $metaData 	= new PHPUnit_Extensions_Database_DataSet_DefaultTableMetaData( $tableName, $columns );
            $table 		= new PHPUnit_Extensions_Database_DataSet_DefaultTable( $metaData );

            foreach ( $rows AS $row ) {
                $table->addRow( $row );
            }

            $this->arrTable[ $tableName ] = $table;
        }
        $this->initDbValue();
    }

    public function getDataSet()
    {
        return $this->createArrayDataSet( $this->m_ArrDataInit );
    }

    /* <--- 数据库连接 */


    public function test()
    {
        //There must be a test_fun() in the class,or warning
    }

    /**
     * Creates a new FlatXmlDataSet with the given $xmlFile. (absolute path.)
     *
     * @param  string                                             $xmlFile
     * @return PHPUnit_Extensions_Database_DataSet_FlatXmlDataSet
     */
    public function createFlatXMLDataSet_( $xmlFile )
    {
        return new PHPUnit_Extensions_Database_DataSet_FlatXmlDataSet( $xmlFile );
    }

    public function getTableSet( $sTableName )
    {
        if ( ! is_string( $sTableName ) || strlen( $sTableName ) <= 0 )
        {
            return null;
        }

        $oRtn = null ;

        if ( array_key_exists( $sTableName, $this->arrTable ) )
        {
            $oRtn = $this->arrTable[ $sTableName ];
        }

        return $oRtn;
    }
}