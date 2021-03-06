<?php
//data - header, attributes
$build_tr = function ($data){
    if (is_null($data)) return false;
    if (is_object($data)) {
        $tr_row = (array)($data);
    } else
        $tr_row = $data;
    $row = '<tr>';
    array_walk($tr_row, function ($item) use (&$row) {
        $row .= '<td>' . $item . '</td>';
    });
    $row .= '</tr>';
    return $row;
};
$table = '<table' .\App\Models\ViewModels\ViewSettings::renderAttributes($model->attributes) .  ' >';
if (!is_null($model->header)) {
    $table .= '<thead><tr>';
    $count=0;
    foreach ($model->header as $value) {
        $table .= '<th > ' . $value . ' </th>';
    }
    $table .= '</tr></thead>';
}
$table .= '<tbody>';
if (isset($model->data)) {
    foreach ($model->data as $value) {
        $table .= $build_tr($value);
    }
}
$table .= '</tbody>';
$table .= '</table>';
echo $table;
?>