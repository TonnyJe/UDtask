<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\BaseManager;

class SettingManager extends BaseManager
{
    const
            TALBE_NAME = 'setting';
    
    public function addDefaultRight(): void
    {
        $this->getAll()
                ->where('type', 'DEFAULT_RIGHTS')
                ->update([
                    'value' => $this->database->literal("CONCAT(`value`, '1')")
                ]);
    }
    
    public function getDefaultRights(): string
    {
        return $this->getAll()->where('type', 'DEFAULT_RIGHTS')->fetch()->value;
    }
}
