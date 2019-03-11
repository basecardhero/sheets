<?php

/**
 * Prepare an array of spreadsheet values.
 *
 * Iteratively set each item as an array.
 *
 * @param array $values
 *
 * @return array
 */
function prepare_spreadsheet_values(array $values) : array
{
    return array_map(function ($value) {
        return is_array($value) ? $value : [$value];
    }, $values);
}
