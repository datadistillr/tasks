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
 */
class StoredTaskModel extends Model
{
    /**
     * Database Table
     *
     * @var string
     */
    protected $table;

    /**
     * If true, will set created_at, and updated_at
     * values during insert and update routines.
     *
     * @var bool
     */
    protected $useTimestamps = true;

    /**
     * If this model should use "softDeletes" and
     * simply set a date when rows are deleted, or
     * do hard deletes.
     *
     * @var bool
     */
    protected $useSoftDeletes = true;

    /**
     * Allowed Fields
     *
     * @var string[]
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
     *
     * @var string[]
     */
    protected $validationRules = [
        'type'       => 'required|in_list[call,command,shell,event,url]',
        'expression' => 'required',
        'command'    => 'required',
    ];

    /**
     * Return type/Entity
     *
     * @var string
     */
    protected $returnType = 'CodeIgniter\Tasks\Entities\StoredTask';

    /**
     * Constructor
     *
     * @param ?ConnectionInterface $db         [default: null]
     * @param ?ValidationInterface $validation [default: null]
     */
    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null)
    {
        $config      = Config('Tasks');
        $this->table = $config->databaseTable;

        parent::__construct($db, $validation);
    }

    /**
     * Find by time
     *
     * @param ?Time $current Current time this will round down to the floor of the current minute [default: null; current time]
     *
     * @throws Exception
     *
     * @return StoredTask[]
     */
    public function findByTime(?Time $current = null): array
    {
        if (! isset($current)) {
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
