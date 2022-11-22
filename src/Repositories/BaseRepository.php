<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Repositories;

class BaseRepository
{
    public $operators = array(
        'and' => ' and ', 
        'comma' => ', '
    );

    public function prepareQuery(array $fields, array $values, string $operator = ' and ') : string
    {
        $query = '';
        $valuesStr = '';
        $arrayLen = count($fields);

        for ($i = 0; $i < $arrayLen; $i++) {
            if ($values[$i]) {
                if (gettype($values[$i]) == 'string') {
                    $valuesStr = "'" . $values[$i] . "'";
                } else {
                    $valuesStr = $values[$i];
                }

                $query .= $fields[$i] . ' = ' . $valuesStr;
    
                if ($i < $arrayLen - 1) {
                    $query .= $operator;
                }
            }
        }

        if (preg_match('/' . $operator . '$/', $query)) {
            $query = preg_replace('/' . $operator . '$/', '', $query);
        }

        return $query;
    }

    public function prepareInsertQuery(array $fields, array $values) : string
    {
        $fieldsStr = implode(', ', $fields);
        $valuesStr = '';

        $arrayLen = count($values);
        for ($i = 0; $i < $arrayLen; $i++) {
            if (gettype($values[$i]) == 'string') {
                $valuesStr .= "'" . $values[$i] . "'";
            } else {
                $valuesStr .= $values[$i];
            }

            if ($i < $arrayLen - 1) {
                $valuesStr .= ', ';
            }
        }
        $query = "(" . $fieldsStr . ") VALUES (" . $valuesStr . ")";

        return $query;
    }
}
