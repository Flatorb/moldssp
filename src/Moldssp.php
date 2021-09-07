<?php

namespace Flatorb\Moldssp;

class Moldssp
{
    public static function paginator($model, $request)
    {
        $length = !empty($request->length) ? (int) $request->length : 25;

        $select = [];
        foreach ($request->columns as $column) {
            array_push($select, isset($column['data']['_']) ? $column['data']['_'] : $column['data']);
        }

        $query = $model->select($select);

        $results = $query->paginate($length);

        $response = $results;

        return $response;
    }
}
