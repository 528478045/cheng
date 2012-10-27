<?php

function begin_test()
{
    echo '<hr><!-- test begin -->' . PHP_EOL;
}

function test($I_got, $u_thought, $options = array())
{
    $options = array_merge( // default options
        array(
            'name' => 'test',
            'compare' => 'equal'), // compare method
        $options);
    $name = $options['name'];
    $compare = $options['compare'];

    switch ($compare) {
        case 'equal':
            $success = kind_of_equal($I_got, $u_thought);
            break;

        case 'in':
            $success = array_contain($I_got, $u_thought);
            break;
        
        default:
            throw new Exception("bad compare method: $compare");
            break;
    }
    $fail = !$success;
    if ($fail)
        $GLOBALS['all_pass'] = false;

    include 'static/entry.html';
}

// use this function to ignore sort when comparing two arrays
function kind_of_equal($a, $b)
{
    if (!is_array($a) || !is_array($b))
        return $a === $b;
    if (count($a) !== count($b))
        return false;
    foreach ($a as $key => $value) {
        if (!isset($b[$key]))
            return false;
        if (!kind_of_equal($value, $b[$key]))
            return false;
    }
    return true;
}

function array_contain($big_arr, $small_arr)
{
    if (!is_array($big_arr) || !is_array($small_arr)) {
        return false;
    }
    foreach ($small_arr as $key => $value) {
        if (!isset($big_arr[$key]) || $big_arr[$key] != $small_arr[$key]) {
            return false;
        }
    }
    return true;
}

// remove all entries
// which cannot be refered by $ref_table by $ref_key 
// in $main_table
function clear_db($main_table, $ref_table, $ref_key, $back_key = 'id', $ref_id = 'id')
{
    $all = Pdb::fetchAll('*', $main_table);
    if ($all === false)
        return;
    foreach ($all as $info) 
        if (!Pdb::exists($ref_table, array($ref_id . '=?' => $info[$ref_key]))) {
            Pdb::del($main_table, array($back_key . '=?' => $info[$back_key]));
}
