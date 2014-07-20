<?php

class tx_euldap_import extends tx_scheduler_Task {

    /**
     * Execute the import task and import all be_users for all servers with be_authentication
     *
     * @return bool
     */
    public function execute() {
		$eu_ldap =  t3lib_div::makeInstance('tx_euldap_div');

        // Grab all ldap servers
        $dbres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_euldap_server','authenticate_be = 1'
        );
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbres)) {
            $arrServers[] = $row;
        }

        try {
            //Iterate and import ignoring the groups for now
            foreach($arrServers as $currentServer) {
                $eu_ldap->import_users($currentServer, array(), 'be_users');
            }
        } catch (Exception $e) {
            return false;
        }
            
        return true;
	}
	
	public function getAdditionalInformation() {
        return 'Import configuration: '.$this->importID;
    }
	
}

?>