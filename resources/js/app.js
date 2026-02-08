import './bootstrap';

import Alpine from 'alpinejs';
import 'bootstrap/dist/css/bootstrap.min.css';

import $ from 'jquery';
window.$ = window.jQuery = $;

// DataTables Bootstrap 5
import 'datatables.net';
import 'datatables.net-bs5';

// Bootstrap JS
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

window.Alpine = Alpine;
Alpine.start();

