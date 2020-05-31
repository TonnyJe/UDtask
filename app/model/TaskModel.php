<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\UserAdminXVillageManager;
use App\Model\VillageManager;
use App\Model\UserManager;

class TaskModel
{

    use \Nette\SmartObject;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var UserAdminXVillageManager
     */
    private $userAdminXVillageManager;

    /**
     * @var VillageManager
     */
    private $villageManager;
    
    public function __construct(
            VillageManager $villageManager,
            UserAdminXVillageManager $userAdminXVillageManager,
            UserManager $userManager)
    {
        $this->villageManager = $villageManager;
        $this->userAdminXVillageManager = $userAdminXVillageManager;
        $this->userManager = $userManager;
    }
    
    
    
    /**
     * @param int $userId Id of user for rights setting
     * @param string[] $cityRights Array in format ['cityId' => '0101010']
     */
    public function set(int $userId, array $cityRights): void
    {
        $this->database->beginTransaction();
        
        try {
            $this->userAdminXVillageManager->setRights($userId, $cityRights);
            if ($this->userAdminXVillageManager->hasNoRights($userId)) {
                $this->userManager->delete($userId);
            }
            
            $this->database->commit();
        } catch (\Exception $exc) {
            Debugger::log($exc->getMessage());
            $this->database->rollBack();
        }
    }
    
    /**
     * @param int $userId ID of user from which You want to get rights to villages
     * @param int $rightId ID of right for filtering
     * 
     * @return string[] Array of villages with that rights in format ['villageId' => 'villageName']
     */
    public function get(int $userId, int $rightId): array
    {
        return $this->villageManager->getVillagesFromRights(
                    $this->userAdminXVillageManager->getRights($userId, $rightId)
               );
    }
}
