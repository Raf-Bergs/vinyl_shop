/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');
window.Noty = require('noty');
Noty.overrideDefaults({
    theme: 'bootstrap-v4',
    type: 'warning',
    layout: 'center',
    modal: true,
});
// Make 'VinylShop' accessible inside the HTML pages
import VinylShop from "./vinylShop";
window.VinylShop = VinylShop;
// Run the hello() function
VinylShop.hello();
/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

$('[required]').each(function () {
    $(this).closest('.form-group')
        .find('label')
        .append('<sup class="text-danger mx-1">*</sup>');
});

$('nav i.fas').addClass('fa-fw mr-1');

$('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    html : true,
}).on('click', '[data-toggle="tooltip"]', function () {
    // hide tooltip when you click on it
    $(this).tooltip('hide');
});
