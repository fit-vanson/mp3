$(document).ready(function () {
    'use strict';

    $('.repeater').repeater({
        defaultValues: {
            'textarea-input': 'foo',
            'text-input': 'bar',
            'select-input': 'B',
            'checkbox-input': ['A', 'B'],
            'radio-input': 'B'
        },
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            // if(confirm('Are you sure you want to delete this element?')) {
                $(this).slideUp(deleteElement);
            // }
        },
        ready: function (setIndexes) {

        }
    });

    window.outerRepeater = $('.outer-repeater').repeater({
        defaultValues: { 'text-input': 'outer-default' },
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            console.log('outer delete');
            $(this).slideUp(deleteElement);
        },
        repeaters: [{
            selector: '.inner-repeater',
            defaultValues: { 'inner-text-input': 'inner-default' },
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        }]
    });
});
