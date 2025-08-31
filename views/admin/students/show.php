<?php

// * multidimensional array to store components data each index is a array of component data
// ? ata lang ha hindi ko din sure tanong mo kay gepetee 
$fields = [
    ['label' => 'Full Name', 'text' => $data['name']],
    ['label' => 'Email', 'text' => $data['email'], 'icon' => 'envelope'],
];

// * role-specific fields
if ($data['role'] === 'instructor') {
    // ! to change $data['name'] => $data['course_created']
    // ? bakit? syempre kasi wala pa data para doon
    $fields[] = ['label' => 'Courses Created', 'text' => $data['name'], 'icon' => 'book'];
} elseif ($data['role'] === 'student') {
    $fields[] = ['label' => 'Address', 'text' => $data['address'], 'icon' => 'map-marker-alt'];
}

// * remaining fields
$fields[] = [
    'label' => 'Status',
    'icon' => 'check-circle',
    'slot' => fn() => component('badge', ['status' => $data['status']])
];

$fields[] = [
    'label' => 'Joined At',
    'text' => date('M d, Y', strtotime($data['created_at'])),
    'icon' => 'calendar-alt'
];

// * echo yung outer component, kasi tsaka lang naman mag auto echo kapag sa condition na ob_get_level() > 1 (meaning yung component call ay under pa sa isang component call. GETS???? AKO HINDI)
// * SO BASICALLY COMPONENT CALL SA COMPONENT CALL. NESTED COMPONENT CALL!!!!!!!!!!!!!
echo component('dashboard/show/card', ['role' => $data['role'], 'status' => $data['status']], function () use ($fields) {
    // ? loop through the field array and call the component function per item :>
    foreach ($fields as $field) {
        // ? oh i c-check naman kung yung field array ba ay may laman na slot, isset: naka set ba yung item na 'to
        if (isset($field['slot'])) {
            component(
                'dashboard/show/show-field',
                ['label' => $field['label'], 'icon' => $field['icon']],
                $field['slot']
            );
        } else {
            // ? eto naman kapag alang slot
            component('dashboard/show/show-field', $field);
        }
    }
});