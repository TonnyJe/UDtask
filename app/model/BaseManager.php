<?php

declare(strict_types=1);

namespace App\Model;

use Nette\Database\Context;
use Nette\Database\Table\Selection;

abstract class BaseManager
{
    use \Nette\SmartObject;

    /**
     * @var Context
     */
    protected $database;

    public function __construct(Context $database)
    {
        $this->database = $database;
    }
    
    public function getAll(): Selection
    {
        return $this->database->table(static::TALBE_NAME);
    }
}
