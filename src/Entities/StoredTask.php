<?php

namespace CodeIgniter\Tasks\Entities;

use CodeIgniter\Entity\Entity;

/**
 * Class StoredTask Entity
 *
 * @author Tim Swagger <tim@datadistillr.com>
 */
class StoredTask extends Entity {

    //-------------------------------------------------
    // region Properties
    //-------------------------------------------------
    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = [
        'start_at',
        'end_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [
        'id' => 'integer'
        // TODO: cast type as an enum
    ];


    // endregion
}
