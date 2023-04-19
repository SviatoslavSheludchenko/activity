import '../css/app.scss';

window._ = require('lodash');
window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');

require('bootstrap');
require('@coreui/coreui');
require('./notify.min');
require('select2');
require('./custom');

$('.custom-file-input').on('change',function() {
    let fileName = $(this).val();
    $(this).next('.custom-file-label').html(fileName);
});
