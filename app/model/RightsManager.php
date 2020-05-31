<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\BaseManager;
use Tracy\Debugger;
use Nette\Database\Context;
use App\Model\SettingManager;
use App\Model\UserAdminXVillageManager;

class RightsManager extends BaseManager
{

    /**
     * @var UserAdminXVillageManager
     */
    private $userAdminXVillageManager;

    /**
     * @var SettingManager
     */
    private $settingManager;

    const
            TALBE_NAME = 'rights';
    
    public function __construct(
            Context $database,
            SettingManager $settingManager,
            UserAdminXVillageManager $userAdminXVillageManager) {
        parent::__construct($database);
        $this->settingManager = $settingManager;
        $this->userAdminXVillageManager = $userAdminXVillageManager;
    }
    
    public function getRights(): array
    {
        return $this->getAll()->fetchPairs('id', 'name');
    }
    
    public function add(string $name): void
    {
        $this->database->beginTransaction();
        
        try {
            $this->getAll()
                    ->insert([
                        'name' => $name
                    ]);
            
            $this->settingManager->addDefaultRight();
            $this->userAdminXVillageManager->addRight();
            
            $this->database->commit();
        } catch (\Exception $exc) {
            Debugger::log($exc->getMessage());
            $this->database->rollBack();
        }
    }
}
