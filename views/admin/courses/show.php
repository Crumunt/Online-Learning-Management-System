<?php

$fields = [
    ['label' => 'Instructor', 'text' => $data['instructor_name']],
    ['label' => 'Course Title', 'text' => $data['title'], 'icon' => 'envelope'],
];

$fields[] = [
    'label' => 'Enrollments',
    'icon' => 'check-circle',
    'slot' => fn() => component('badge', ['status' => $data['enrollments']])
];

$fields[] = [
    'label' => 'Status',
    'icon' => 'check-circle',
    'slot' => fn() => component('badge', ['status' => $data['status']])
];

$fields[] = [
    'label' => 'Created At',
    'text' => date('M d, Y', strtotime($data['created_at'])),
    'icon' => 'calendar-alt'
];

echo component('dashboard/show/card', [], function () use ($fields) {

    foreach ($fields as $field) {

        if (isset($field['slot'])) {
            component(
                'dashboard/show/show-field',
                ['label' => $field['label'], 'icon' => $field['icon']],
                $field['slot']
            );
        } else {

            component('dashboard/show/show-field', $field);
        }
    }
});