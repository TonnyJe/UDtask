<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\BaseManager;
use Nette\Database\Context;
use App\Model\SettingManager;
use Nette\Utils\Strings;
use Nette\Database\Table\Selection;

class UserAdminXVillageManager extends BaseManager
{

    /**
     * @var SettingManager
     */
    private $settingManager;

    const
            TALBE_NAME = 'user_admin_x_village';
    
    public function __construct(
            Context $database,
            SettingManager $settingManager) {
        parent::__construct($database);
        $this->settingManager = $settingManager;
    }
    
    public function addRight(): void
    {
        $this->getAll()
                ->update([
                    'value' => $this->database->literal("CONCAT(`value`, '1')")
                ]);
    }
    
    public function addUser(int $userId, array $villages): void
    {
        $defaultRights = $this->settingManager->getDefaultRights();
        
        $toInsert = [];
        
        foreach ($villages as $key => $village) {
            $toInsert[] = [
                'user_admin_id' => $userId,
                'village_id' => $key,
                'rights' => $defaultRights
            ];
        }
        
        $this->getAll()->insert($toInsert);
    }
    
    public function addVillage(int $villageId): void
    {
        $rightsForNewVillage = $this->getAllUsersRightsForNewVillage();
        
        $toInsert = [];
        
        foreach ($rightsForNewVillage as $userId => $rights) {
            $toInsert[] = [
                'user_admin_id' => $userId,
                'village_id' => $villageId,
                'rights' => $rights
            ];
        }
        
        $this->getAll()->insert($toInsert);
    }
    
    private function getAllUsersRightsForNewVillage(): array 
    {
        $rows = $this->getAll()
                ->group('user_admin_id, rights');
        
        $defaultRights = $this->settingManager->getDefaultRights();
        
        $userNewRights = [];
        
        foreach ($rows as $row) {
            if (!isset($userNewRights[$row->user_admin_id])) {
                $userNewRights[$row->user_admin_id] = $defaultRights;
            }
            $userNewRights[$row->user_admin_id] &= $row->rights;
        }
        
        return $userNewRights;
    }
    
    public function setRights(int $userId, array $cityRights): void
    {
        $rows = $this->getAll()
                ->where('user_admin_id', $userId);

        foreach ($rows as $row) {
            $row->update([
                'rights' => $cityRights[$row->village_id]
            ]);
        }
    }
    
    public function getRights(int $userId, int $rightId): Selection
    {
        return $this->getAll()
                ->where('user_admin_id', $userId)
                ->where('SUBSTRING(rights, ' . $rightId . ', 1)', '1');  // This is OKAY, because in $rightId is always integer 
    }
    
    public function hasNoRights(int $userId): bool
    {
        $selection = $this->getAll()
                ->where('user_admin_id', $userId);
        
        return $selection->count('DISTINCT rights') === 1 && 
               !Strings::match($selection->fetch()->rights, '/[^0]/'); // are there only zeros
    }
}
