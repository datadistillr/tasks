<?php

namespace CodeIgniter\Tasks\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use CodeIgniter\Tasks\Entities\StoredTask;
use CodeIgniter\Validation\ValidationInterface;
use Exception;

/**
 * Class TaskModel
 *
 * @author Tim Swagger <tim@datadistillr.com>
 */
class StoredTaskModel extends Model {
    /**
     * Database Table
     * @var string $table
     */
    protected $table;

    /**
     * Allowed Fields
     * @var string[] $allowedFields
     */
    protected $allowedFields = [
        'type',
        'expression',
        'command',
        'name',
        'start_at',
        'end_at',
    ];

    /**
     * Validation Rules
     * @var string[] $validationRules
     */
    protected $validationRules = [
        'type' => 'required|in_list[call,command,shell,event,url]',
        'expression' => 'required',
        'command' => 'required',
    ];

    /**
     * Return type/Entity
     * @var string $returnType
     */
    protected $returnType = 'CodeIgniter\Tasks\Entities\StoredTask';


    /**
     * Constructor
     *
     * @param ?ConnectionInterface $db  [default: null]
     * @param ?ValidationInterface $validation [default: null]
     */
    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null)
    {
        $config = Config('Tasks');
        $this->table = $config->databaseTable;

        parent::__construct($db, $validation);
    }

    /**
     * Find by time
     *
     * @param ?Time $current Current time this will round down to the floor of the current minute [default: null; current time]
     * @return StoredTask[]
     * @throws Exception
     */
    public function findByTime(?Time $current = null): array
    {
        if(! isset($current)) {
            $current = Time::now();
        }
        $sqlDateFormat = config('Tasks')->sqlDateFormat;

        return $this->groupStart()
                ->where('start <=', $current->format($sqlDateFormat))
                ->orWhere('start IS NULL')
            ->groupEnd()
            ->groupStart()
                ->where('end >=', $current->format($sqlDateFormat))
                ->orWhere('end IS NULL')
            ->groupEnd()
            ->findAll();
    }
}
