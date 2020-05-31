<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\BaseManager;
use App\Model\UserAdminXVillageManager;
use App\Model\VillageManager;
use Nette\Database\Context;
use Tracy\Debugger;

class UserManager extends BaseManager
{

    /**
     * @var VillageManager
     */
    private $villageManager;

    /**
     * @var UserAdminXVillageManager
     */
    private $userAdminXVillageManager;

    const
            TALBE_NAME = 'user_admin';
    
    public function __construct(
            Context $database,
            UserAdminXVillageManager $userAdminXVillageManager,
            VillageManager $villageManager) {
        parent::__construct($database);
        $this->userAdminXVillageManager = $userAdminXVillageManager;
        $this->villageManager = $villageManager;
    }
    
    public function add(string $name): void
    {
        $this->database->beginTransaction();
        
        try {
            $insertedUser = $this->getAll()
                    ->insert([
                        'name' => $name
                    ]);

            $this->userAdminXVillageManager->addUser($insertedUser->id, $this->villageManager->getVillages());
            $this->database->commit();
        } catch (\Exception $exc) {
            Debugger::log($exc->getMessage());
            $this->database->rollBack();
        }
    }
    
    public function delete(int $userId): void
    {
        $this->getAll()
                ->get($userId)
                ->delete();
    }
}
