//Guardar en el cache dinamico
function actualizaCacheDinamico(dynamicCache, req, res) {
    if (res.ok && req.url.includes('php') == false && req.url.includes('png') == false && req.url.includes('jpg') == false) {
        return caches.open(dynamicCache).then(cache => {
            cache.put(req, res.clone());
            return res.clone();
        });
    } else {
        return res;
    }
}