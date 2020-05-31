<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\BaseManager;
use Nette\Database\Context;
use App\Model\UserAdminXVillageManager;
use Tracy\Debugger;
use Nette\Database\Table\Selection;

class VillageManager extends BaseManager
{

    /**
     * @var UserAdminXVillageManager
     */
    private $userAdminXVillageManager;

    const
            TALBE_NAME = 'village';
    
    public function __construct(
            Context $database,
            UserAdminXVillageManager $userAdminXVillageManager) {
        parent::__construct($database);
        $this->userAdminXVillageManager = $userAdminXVillageManager;
    }
    
    public function getVillages(): array
    {
        return $this->getAll()
                ->fetchPairs('id', 'name');
    }
    
    public function add(string $name): void
    {
        $this->database->beginTransaction();
        
        try {
            $inserted = $this->getAll()
                    ->insert([
                        'name' => $name
                    ]);

            $this->userAdminXVillageManager->addVillage($inserted->id);
            
            $this->database->commit();
        } catch (\Exception $exc) {
            Debugger::log($exc->getMessage());
            $this->database->rollBack();
        }
    }
    
    public function getVillagesFromRights(Selection $rights): array
    {
        return $rights
                ->select('village_id, village.name vName')
                ->fetchPairs('village_id', 'vName'); 
    }
}
