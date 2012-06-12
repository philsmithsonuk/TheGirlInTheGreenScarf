<?php

class Aitoc_Aitsys_Model_Mysql4_Setup extends Aitoc_Aitsys_Abstract_Resource_Setup
{
    protected $_allowUpdate = true;
    const TYPE_AITOC_DB_UPGRADE           = 'upgrade';
    const TYPE_AITOC_DB_INSTALL           = 'install';
    
    protected $_upgradeActionType = '';
    protected $_installActionType = '';
    
    public function __construct($resourceName) {
        $this->_upgradeActionType = (defined('Mage_Core_Model_Resource_Setup::TYPE_DB_UPGRADE') ? Mage_Core_Model_Resource_Setup::TYPE_DB_UPGRADE : self::TYPE_AITOC_DB_UPGRADE );
        $this->_installActionType = (defined('Mage_Core_Model_Resource_Setup::TYPE_DB_INSTALL') ? Mage_Core_Model_Resource_Setup::TYPE_DB_INSTALL : self::TYPE_AITOC_DB_INSTALL );
        return parent::__construct($resourceName);
    }
    /**
     * Run resource upgrade files from $oldVersion to $newVersion
     *
     * @param string $oldVersion
     * @param string $newVersion
     * @return Mage_Core_Model_Resource_Setup
     */
    protected function _upgradeResourceDb($oldVersion, $newVersion)
    {
        $this->_allowUpdate = true;
        $this->_modifyResourceDb($this->_upgradeActionType, $oldVersion, $newVersion);
        if($this->_allowUpdate) {
            $this->_getResource()->setDbVersion($this->_resourceName, $newVersion);
        }

        return $this;
    }
    
    /**
     * Run resource installation file
     *
     * @param string $newVersion
     * @return Mage_Core_Model_Resource_Setup
     */
    protected function _installResourceDb($newVersion)
    {
        $this->_allowUpdate = true;
        $oldVersion = $this->_modifyResourceDb($this->_installActionType, '', $newVersion);
        $this->_modifyResourceDb($this->_upgradeActionType, $oldVersion, $newVersion);
        if($this->_allowUpdate) {
            $this->_getResource()->setDbVersion($this->_resourceName, $newVersion);
        }

        return $this;
    }

    /**
     * Get core resource resource model
     *
     * @return Mage_Core_Model_Resource_Resource
     */
    protected function _getResource()
    {
        return Mage::getResourceSingleton('core/resource');
    }

    /**
     * Run module modification files. Return version of last applied upgrade (false if no upgrades applied)
     *
     * @param string $actionType self::TYPE_*
     * @param string $fromVersion
     * @param string $toVersion
     * @return string|false
     * @throws Mage_Core_Exception
     */

    protected function _modifyResourceDb($actionType, $fromVersion, $toVersion)
    {
        /* Aitsys use only TYPE_DB_ files  */
        /*switch ($actionType) {
            case self::TYPE_DB_INSTALL:
            case self::TYPE_DB_UPGRADE:*/
                $files = $this->_getAvailableDbFiles($actionType, $fromVersion, $toVersion);
/*                break;
            case self::TYPE_DATA_INSTALL:
            case self::TYPE_DATA_UPGRADE:
                $files = $this->_getAvailableDataFiles($actionType, $fromVersion, $toVersion);
                break;
            default:
                $files = array();
                break;
        }*/
        if (empty($files) || !$this->getConnection()) {
            return false;
        }

        $version = false;
        $cache = false;
        if(method_exists($this->getConnection(),'disallowDdlCache')) {
            $cache = true;
        }

        foreach ($files as $file) {
            $fileName = $file['fileName'];
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            if($cache) {
                $this->getConnection()->disallowDdlCache();
            }
            try {
                switch ($fileType) {
                    case 'php':
                        $conn   = $this->getConnection();
                        $result = include $fileName;
                        break;
                    case 'sql':
                        $sql = file_get_contents($fileName);
                        if (!empty($sql)) {

                            $result = $this->run($sql);
                        } else {
                            $result = true;
                        }
                        break;
                    default:
                        $result = false;
                        break;
                }

                if ($result && $this->_allowUpdate) {
                    $this->_getResource()->setDbVersion($this->_resourceName, $file['toVersion']);
                }
            } catch (Exception $e) {
                printf('<pre>%s</pre>', print_r($e, true));
                throw Mage::exception('Mage_Core', Mage::helper('core')->__('Error in file: "%s" - %s', $fileName, $e->getMessage()));
            }
            $version = $file['toVersion'];
            if($cache) {
                $this->getConnection()->allowDdlCache();
            }
        }
        self::$_hadUpdates = true;
        return $version;
    }

    /**
     * Retrieve available Database install/upgrade files for current module
     *
     * @param string $actionType
     * @param string $fromVersion
     * @param string $toVersion
     * @return array
     */
    protected function _getAvailableDbFiles($actionType, $fromVersion, $toVersion)
    {
        $resModel   = (string)$this->_connectionConfig->model;
        $modName    = (string)$this->_moduleConfig[0]->getName();

        $filesDir   = Mage::getModuleDir('sql', $modName) . DS . $this->_resourceName;
        if (!is_dir($filesDir) || !is_readable($filesDir)) {
            return array();
        }

        $dbFiles    = array();
        $typeFiles  = array();
        $regExpDb   = sprintf('#^%s-(.*)\.(php|sql)$#i', $actionType);
        $regExpType = sprintf('#^%s-%s-(.*)\.(php|sql)$#i', $resModel, $actionType);
        $handlerDir = dir($filesDir);
        while (false !== ($file = $handlerDir->read())) {
            $matches = array();
            if (preg_match($regExpDb, $file, $matches)) {
                $dbFiles[$matches[1]] = $filesDir . DS . $file;
            } else if (preg_match($regExpType, $file, $matches)) {
                $typeFiles[$matches[1]] = $filesDir . DS . $file;
            }
        }
        $handlerDir->close();

        $xmlData = (array)$this->_resourceConfig->sql_files->$actionType;
        if(false === $this->haveAllFiles($typeFiles, $xmlData)) {
            $this->_allowUpdate = false;
            Mage::log('Unable to upgrade Aitsys module from '.$fromVersion.' to '.$toVersion.'. If this error is repeated for some time please check that all files are uploaded to app/code/local/Aitoc/Aitsys/sql/aitsys_setup folder.');
            return array();
        }

        if (empty($typeFiles) && empty($dbFiles)) {
            return array();
        }

        foreach ($typeFiles as $version => $file) {
            $dbFiles[$version] = $file;
        }

        return $this->_getModifySqlFiles($actionType, $fromVersion, $toVersion, $dbFiles);
    }

    /**
     * Checks that all files are uploaded to sql folder.
     *
     * @param array $diskFiles - Array of files in folder array( [1.0.1-2.0.2] => [path_to_file],...)
     * @param array $xmlFIles - Array of available versions for current module taken from config.xml array([version],[version],...)
     */
    public function haveAllFiles($diskFiles, $xmlFiles) {
        foreach($xmlFiles as $version) {
            if(!isset($diskFiles[$version])) {
                return false;
            }
        }
        return true;
    }
    
    public function applyAitocModuleUninstall($moduleName)
    {
        $localDir = Aitoc_Aitsys_Abstract_Service::get()->filesystem()->getLocalDir();
        $moduleDir = $localDir . str_replace('_', '/', $moduleName);
        
        $configFile = $moduleDir.DS.'etc'.DS.'config.xml';
                
        if (file_exists($configFile))
        {
            $config = simplexml_load_file($configFile);
        }
 
        if (!isset($config))
        {
            return false;
        }

        foreach ($config->global->resources->children() as $key => $object)
        {
            if ($object->setup)
            {
                $resourceName = $key;
                break;
            }
        }
            
        if (!isset($resourceName))
        {
            return false;
        }

        $sqlFilesDir = $moduleDir.DS.'sql'.DS.$resourceName;

        if (!is_dir($sqlFilesDir) || !is_readable($sqlFilesDir)) {
            return false;
        }
        // Read resource files
        $arrAvailableFiles = array();
        $sqlDir = dir($sqlFilesDir);
        while (false !== ($sqlFile = $sqlDir->read())) {
            $matches = array();
            if (preg_match('#^mysql4-uninstall-(.*)\.(sql|php)$#i', $sqlFile, $matches)) {
                $arrAvailableFiles[$matches[1]] = $sqlFile;
            }
        }
        $sqlDir->close();
        
        if (empty($arrAvailableFiles)) {
            return false;
        }

        
        foreach ($arrAvailableFiles as $resourceFile) {
            $sqlFile = $sqlFilesDir.DS.$resourceFile;
            $fileType = pathinfo($resourceFile, PATHINFO_EXTENSION);
            // Execute SQL
            if ($this->_conn) {
                if (method_exists($this->_conn, 'disallowDdlCache')) {
                    $this->_conn->disallowDdlCache();
                }
                try {
                    switch ($fileType) {
                        case 'sql':
                            $sql = file_get_contents($sqlFile);
                            if ($sql!='') {
                                $result = $this->run($sql);
                            } else {
                                $result = true;
                            }
                            break;
                        case 'php':
                            $conn = $this->_conn;
                            $result = include($sqlFile);
                            break;
                        default:
                            $result = false;
                    }
                } catch (Exception $e){
                    echo "<pre>".print_r($e,1)."</pre>";
                    throw Mage::exception('Mage_Core', Mage::helper('core')->__('Error in file: "%s" - %s', $sqlFile, $e->getMessage()));
                }
                if (method_exists($this->_conn, 'allowDdlCache')) {
                    $this->_conn->allowDdlCache();
                }
            }
        }
        self::$_hadUpdates = true;
        return true;
    }
    
    public function applyAitocModuleActivate($moduleName)
    {
        $localDir = Aitoc_Aitsys_Abstract_Service::get()->filesystem()->getLocalDir();
        $moduleDir = $localDir . str_replace('_', '/', $moduleName);
        
        $configFile = $moduleDir.DS.'etc'.DS.'config.data.xml';
        if (file_exists($configFile))
        {
            $config = simplexml_load_file($configFile);
        }
        else 
        {
            $configFile = $moduleDir.DS.'etc'.DS.'config.xml';
            if (file_exists($configFile))
            {
                $config = simplexml_load_file($configFile);
            }
        }

        if (!isset($config))
        {
            return false;
        }

        //list($curVersion) = (array)$config->modules->$moduleName->version;

        if (isset($config->global) && isset($config->global->resources))
        {
            foreach ($config->global->resources->children() as $key => $object)
            {
                if ($object->setup)
                {
                    $resourceName = $key;
                    break;
                }
            }
        }
        
        if (!isset($resourceName))
        {
            return false;
        }

        $dbVersion = Mage::getResourceModel('core/resource')->getDBVersion($resourceName);
                
        $sqlFilesDir = $moduleDir.DS.'sql'.DS.$resourceName;

        if (!is_dir($sqlFilesDir) || !is_readable($sqlFilesDir)) {
            return false;
        }
        // Read resource files
        $arrAvailableFiles = array();
        $sqlDir = dir($sqlFilesDir);
        
        while (false !== ($sqlFile = $sqlDir->read())) {
            $matches = array();
            if (preg_match('#^mysql4-activate-(.*)\.(sql|php)$#i', $sqlFile, $matches)) {
                $arrAvailableFiles[$matches[1]] = $sqlFile;
            }
        }
        
        if (empty($arrAvailableFiles)) {
            return false;
        }

        foreach ($arrAvailableFiles as $version => $resourceFile) {
            if (version_compare($version, $dbVersion) > 0)
            {
                break;
            }
            $sqlFile = $sqlFilesDir.DS.$resourceFile;
            $fileType = pathinfo($resourceFile, PATHINFO_EXTENSION);
            
            // Execute SQL
            if ($this->_conn) {
                if (method_exists($this->_conn, 'disallowDdlCache')) {
                    $this->_conn->disallowDdlCache();
                }
                try {
                    switch ($fileType) {
                        case 'sql':
                            $sql = file_get_contents($sqlFile);
                            if ($sql!='') {
                                $result = $this->run($sql);
                            } else {
                                $result = true;
                            }
                            break;
                        case 'php':
                            $conn = $this->_conn;
                            $result = include($sqlFile);
                            break;
                        default:
                            $result = false;
                            
                    }
                } catch (Exception $e){
                    echo "<pre>".print_r($e,1)."</pre>";
                    throw Mage::exception('Mage_Core', Mage::helper('core')->__('Error in file: "%s" - %s', $sqlFile, $e->getMessage()));
                }
                if (method_exists($this->_conn, 'allowDdlCache')) {
                    $this->_conn->allowDdlCache();
                }
            }
        }
        
        self::$_hadUpdates = true;
        return true;
    }
    
}