//imports

importScripts('libs/js/sw-utils.js');


const STATIC_CACHE = 'static-v10';
const DYNAMIC_CACHE = 'dynamic-v10';
const INMUTABLE_CACHE = 'inmutable-v10';


const APP_SHELL = [
    '/',
    'manifest.json',
    'libs/css/style.css',
    'libs/funciones/func_generales.js',
    'libs/funciones/func_usuarios.js',
    'libs/funciones/func_geografias.js',
    'libs/funciones/func_plantaciones.js',
    'libs/funciones/func_configuracion.js',
    'libs/funciones/func_inventario.js',
    'libs/funciones/func_control_calidad.js',
    'libs/css/form.css',
    'libs/css/modal_styles.css',
    'libs/css/general.css',
    'libs/css/planificacion.css',
    'libs/js/app.js',
    'libs/js/sw-utils.js',
    'libs/imgs/icons/favicon.ico',
    'libs/imgs/icons/favicon-16x16.png',
    'libs/imgs/icons/favicon-32x32.png',
    'libs/imgs/icons/apple-touch-icon.png',
    'libs/imgs/icons/android-chrome-192x192.png',
    'libs/imgs/icons/android-chrome-512x512.png',
    'libs/imgs/icons/logo.png'
];

const APP_SHELL_INMUTABLE = [
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
    'libs/jQuery/jquery-2.1.3.js',
    'libs/jQuery/jquery.mask.min.js',
    'libs/jQuery/date.js',
    'libs/jQuery/jquery-ui.js',
    'libs/jQuery/accounting.min.js',
    'libs/jQuery/jquery.PrintArea.js',
    'libs/jQuery/jquery.sticky.js',
    'libs/bootstrap/js/bootstrap.min.js',
    'libs/bootstrap/css/bootstrap.min.css',
    'libs/bootstrap/js/jquery.bootstrap-growl.min.js',
    'libs/bootstrap/js/collapse.js',
    'libs/bootstrap/js/transition.js',
    'libs/bootstrap/js/moment.js',
    'libs/bootstrap/js/moment-range.js',
    'libs/bootstrap/js/spin.min.js',
    'libs/bootstrap/js/ladda.min.js',
    'libs/bootstrap/js/bootstrap-switch.js',
    'libs/bootstrap/css/bootstrap-select.css',
    'libs/bootstrap/css/bootstrapValidator.min.css',
    'libs/bootstrap/css/bootstrap-datetimepicker.min.css',
    'libs/bootstrap/css/bootstrap-switch.css',
    'libs/DataTables/media/css/jquery.dataTables.css',
    'libs/DataTables/extensions/integration/bootstrap/3/dataTables.bootstrap.css',
    'libs/DataTables/extensions/Responsive/css/dataTables.responsive.css',
    'libs/DataTables/extensions/TableTools/css/dataTables.tableTools.css',
    'libs/calendar_master/css/calendar.css',
    'libs/css/table_responsive.css',
    'libs/bootstrap/js/bootstrap.js',
    'libs/bootstrap/js/bootstrap-select.js',
    'libs/bootstrap/js/bootstrap3-typeahead.js',
    'libs/bootstrap/js/bootstrapValidator.min.js',
    'libs/bootstrap/js/bootstrap-datetimepicker.js',
    'libs/bootstrap/js/bootstrap-datetimepicker.es.js',
    'libs/DataTables/media/js/jquery.dataTables.min.js',
    'libs/DataTables/extensions/integration/bootstrap/3/dataTables.bootstrap.js',
    'libs/DataTables/extensions/Responsive/js/dataTables.responsive.js',
    'libs/DataTables/extensions/TableTools/js/dataTables.tableTools.js',
    'libs/DataTables/extensions/FixedHeader/js/dataTables.fixedHeader.js',
    'libs/Highcharts/js/highcharts.js',
    'libs/Highcharts/js/highcharts-more.js',
    'libs/Highcharts/js/adapters/standalone-framework.js',
    'libs/Highcharts/js/modules/exporting.js',
    'libs/Highcharts/js/modules/heatmap.js',
    'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
    'https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js',
    'https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js',
    'https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js',
    'https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js'
];


self.addEventListener('install', e => {
    self.skipWaiting();

    const cacheResources = (cacheName, urls) => {
        return caches.open(cacheName).then(cache => {
            return Promise.all(
                urls.map(url =>
                    cache.add(url).catch(err => {
                        console.warn(`[SW] Failed to cache: ${url}`, err);
                    })
                )
            );
        });
    };

    e.waitUntil(Promise.all([
        cacheResources(STATIC_CACHE, APP_SHELL),
        cacheResources(INMUTABLE_CACHE, APP_SHELL_INMUTABLE)
    ]));
});

self.addEventListener('activate', e => {

    const respuesta = caches.keys().then(keys => {

        keys.forEach(key => {

            if (key !== STATIC_CACHE && key.includes('static')) {
                return caches.delete(key);
            }

            if (key !== DYNAMIC_CACHE && key.includes('dynamic')) {
                return caches.delete(key);
            }

        });

    });
    e.waitUntil(respuesta);
});


self.addEventListener('fetch', e => {

    const respuesta = caches.match(e.request).then(res => {
        if (res) {
            return res;
        } else {
            return fetch(e.request).then(newRes => {
                return actualizaCacheDinamico(DYNAMIC_CACHE, e.request, newRes);
            });
        }

    });

    e.respondWith(respuesta);
});