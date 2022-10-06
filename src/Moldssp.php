<?php

namespace Flatorb\Moldssp;

class Moldssp
{
    public static function paginator($model, $request)
    {
        $length = !empty($request->length) ? (int) $request->length : 25;

        $select = [];
        $searchables = [];
        foreach ($request->columns as $column) {
            if (isset($column['data']['_'])) {
                array_push($select, $column['data']['_']);
                array_push($searchables, [
                    'relationship' => true,
                    'column' => substr($column['data']['display'], strrpos($column['data']['display'], '.') + 1),
                    'display' => $column['data']['display'],
                    'reference' => $column['data']['_'],
                    'with' => substr($column['data']['display'], 0, strrpos( $column['data']['display'], '.'))
                ]);
            } else {
                array_push($select, $column['data']);
                array_push($searchables, [
                    'relationship' => false,
                    'column' => $column['data'],
                    'display' => $column['data'],
                    'reference' => null,
                    'with' => null
                ]);
            }

        }

        $query = $model->select($select);

        $search_term = isset($request->search) && !empty($request->search['value']) ? $request->search['value'] : null;
        if (!empty($search_term)) {
            $query->where(function($q) use ($searchables, $search_term) {
                foreach ($searchables as $k => $searchable) {
                    if ($k === 0) {
                        if ($searchable['relationship']) {
                            $q->whereHas($searchable['with'], function ($subQuery) use ($searchable) {
                                $refernce = $searchable['reference'];
                                $subQuery->where($searchable['reference'], 'like', "%{$refernce}%");
                            });
                            $q->where($searchable['column'], 'like', "%{$search_term}%");
                        } else {
                            $q->where($searchable['column'], 'like', "%{$search_term}%");
                        }
                    } else {
                        if ($searchable['relationship']) {
                            $q->orWhereHas($searchable['with'], function ($subQuery) use ($searchable, $search_term) {
                                $subQuery->where($searchable['column'], 'like', "%{$search_term}%");
                            });
                        } else {
                            $q->orWhere($searchable['column'], 'like', "%{$search_term}%");
                        }
                    }
                }
            });
        }

        $order_array = $request->order;
        if (isset($order_array[0]['column'])) {
            $order_column = $request->columns[$order_array[0]['column']]['data'];
            $order_direction = $order_array[0]['dir'] ?? 'asc';
            $query->orderBy($order_column, $order_direction);
        }

        $results = $query->paginate($length);

        $response = $results;

        return $response;
    }
}
