import 'bootstrap/dist/css/bootstrap.min.css'; // ✅ Ensure Bootstrap CSS is included
import * as bootstrap from 'bootstrap'; // ✅ Ensure Bootstrap JavaScript is imported globally

window.bootstrap = bootstrap; // ✅ Make Bootstrap available globally

import '../css/andrei.css'

import { createApp } from 'vue';
import { clickOutside } from './directives/clickOut';

// Import other components
import VueDatepickerNext from './components/DatePicker.vue';
import MembriSelector from './components/MembriSelector.vue';
import SubcontractantiSelector from './components/SubcontractantiSelector.vue';
import DisableOnceButton from './components/DisableOnceButton.vue';


// App pentru DatePicker
const datePicker = createApp({});
datePicker.component('vue-datepicker-next', VueDatepickerNext);
if (document.getElementById('datePicker') != null) {
    datePicker.mount('#datePicker');
}

const membriSelectorApp = createApp({});
membriSelectorApp.directive("click-out", clickOutside);
membriSelectorApp.component('membri-selector', MembriSelector);
if (document.getElementById('membriSelectorApp') !== null) {
    membriSelectorApp.mount('#membriSelectorApp');
}

const subcontractantiSelectorApp = createApp({});
subcontractantiSelectorApp.directive("click-out", clickOutside);
subcontractantiSelectorApp.component('subcontractanti-selector', SubcontractantiSelector);
if (document.getElementById('subcontractantiSelectorApp') !== null) {
    subcontractantiSelectorApp.mount('#subcontractantiSelectorApp');
}

const sendEmailModals = createApp({});
sendEmailModals.component('disable-once-button', DisableOnceButton);
if (document.getElementById('sendEmailModals') !== null) {
    sendEmailModals.mount('#sendEmailModals');
}
