<?php

namespace App\Traits;

use App\Models\AuditLog;

trait LogsAudit
{
    /**
     * Log an action to the audit log.
     *
     * @param string $action
     * @param mixed $model
     * @param string|null $description
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return AuditLog
     */
    protected function logAudit($action, $model = null, $description = null, $oldValues = null, $newValues = null)
    {
        return AuditLog::log($action, $model, $description, $oldValues, $newValues);
    }

    /**
     * Get old values from a model before update.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $fields
     * @return array
     */
    protected function getOldValues($model, $fields = [])
    {
        $oldValues = [];
        foreach ($fields as $field) {
            if (isset($model->getOriginal()[$field])) {
                $oldValues[$field] = $model->getOriginal()[$field];
            }
        }
        return $oldValues;
    }

    /**
     * Get new values from request or model.
     *
     * @param \Illuminate\Http\Request|array $data
     * @param array $fields
     * @return array
     */
    protected function getNewValues($data, $fields = [])
    {
        $newValues = [];
        foreach ($fields as $field) {
            if (is_array($data) && isset($data[$field])) {
                $newValues[$field] = $data[$field];
            } elseif (is_object($data) && $data->has($field)) {
                $newValues[$field] = $data->input($field);
            }
        }
        return $newValues;
    }
}




