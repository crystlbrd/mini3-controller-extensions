<?php


namespace crystlbrd\ControllerExt\ControllerTraits;


use crystlbrd\DatabaseHandler\DatabaseHandler;
use crystlbrd\DatabaseHandler\Exceptions\DatabaseHandlerException;

trait Database
{
    /// SETTINGS

    /**
     * @var array Database Connections
     */
    protected $_SETTING_DatabaseConnections;


    /// INTERNAL CACHE

    /**
     * @var array Caches already connected databases
     */
    protected $_CACHE_ConnectedDatabaseConnections = [];


    /// PROPERTIES

    /**
     * @var DatabaseHandler Database Connections
     */
    protected $_DatabaseHandler;


    /// METHODS

    /**
     * Initiates the DatabaseHandler, if not already done
     */
    protected function initDatabaseHandler(): void
    {
        if ($this->_DatabaseHandler === null) $this->_DatabaseHandler = new DatabaseHandler();
    }

    /**
     * Connects to a database
     * @param string $connName Connection name, defined in _SETTING_DatabaseConnections
     * @throws DatabaseHandlerException
     */
    protected function requireConnection(string $connName): void
    {
        // DatabaseHandler initialisieren
        $this->initDatabaseHandler();

        // Verbindung suchen
        if (
            isset($this->_SETTING_DatabaseConnections[$connName])
            && !in_array($connName, $this->_CACHE_ConnectedDatabaseConnections)
        ) {
            // Datenbankverbindung aufbauen
            $this->_DatabaseHandler->addConnection($connName, $this->_SETTING_DatabaseConnections[$connName]);

            // Aktive Verbindung merken, um sie noch nochmal aufzubauen
            $this->_CACHE_ConnectedDatabaseConnections[] = $connName;
        }
    }
}