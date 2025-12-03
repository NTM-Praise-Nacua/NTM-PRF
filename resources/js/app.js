require("./bootstrap");

import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;

const Swal = require("sweetalert2");
window.Swal = Swal;

import $ from "jquery";
window.$ = window.jQuery = $;

import "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";

import "../css/custom.css";
import "bootstrap-datepicker";
