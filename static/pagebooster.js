(function(win, doc) {

function $$(selector, root) {
    return (root || doc).querySelector(selector);
}

function $$$(selector, root) {
    return (root || doc).querySelectorAll(selector);
}

let f3h = new F3H({
        turbo: F3H.state.statically.turbo, // Enable cache and URL pre-fetching on hover
        sources: 'a[href]:not([href*="/wp-admin/"]), form:not([action*="/wp-admin/"])', // Ignore links to the admin area
    }),
    currentBody = doc.body,
    currentElements = $$$(F3H.state.statically.elementsToReplace),
    currentMetaDescription = $$('meta[content][name="description"]'),
    currentMetaDescriptionOG = $$('meta[content][name="og:description"]'),
    currentRoot = doc.documentElement;

f3h.on(200, function(next) {
    let nextBody = next.body,
        nextElements = $$$(F3H.state.statically.elementsToReplace, next),
        nextMetaDescription = $$('meta[content][name="description"]', next),
        nextMetaDescriptionOG = $$('meta[content][name="og:description"]', next),
        nextRoot = next.documentElement;
    // Update document title
    doc.title = next.title;
    // Update meta description data if any
    currentMetaDescription &&
    nextMetaDescription &&
    (currentMetaDescription.content = nextMetaDescription.content);
    // Update open-graph meta description data if any
    currentMetaDescriptionOG &&
    nextMetaDescriptionOG &&
    (currentMetaDescriptionOG.content = nextMetaDescriptionOG.content);
    // Update body class names
    currentBody &&
    nextBody &&
    (currentBody.className = nextBody.className);
    // Update root class names (if any)
    currentRoot &&
    nextRoot &&
    (currentRoot.className = nextRoot.className.replace(/\bno-js\b/, 'js'));

    currentElements.forEach(function(element, index) {
        if (nextElements[index]) {
            element.className = nextElements[index].className;
            element.innerHTML = nextElements[index].innerHTML;
        }
    });

    doAutoDetectScriptsToRefresh(this);
});

function doAutoDetectScriptsToRefresh(base) {
    let id,
        script,
        scripts = base.scripts,
        scriptContent,
        scriptSource;
    for (id in scripts) {
        script = scripts[id];
        scriptContent = script[1] || "";
        scriptSource = script[2].src || "";
        // Ignore case-sensitivity and white-spaces
        scriptContent = scriptContent.toLowerCase().replace(/\s+/g, "");
        // Ignore case-sensitivity, URL protocol, query string and hash
        scriptSource = scriptSource.toLowerCase().replace(/^https?:\/\/|[?&#].*$/g, "");
        // Remove by content
        if (scriptContent) {
            if (
                // Maybe Facebook SDK script
                /\b(fbasyncinit|fb\.init)\b/.test(scriptContent) ||
                // Maybe Google Analytics script
                /\b(_gaq|datalayer|gtag)\b/.test(scriptContent) ||
                // Maybe Google AdSense script
                /\badsbygoogle\b/.test(scriptContent)
            ) {
                delete scripts[id];
            }
        }
        // Remove by source path
        if (scriptSource) {
            if (F3H.state.statically.scriptsToRefresh.split(/\s*,\s*/).indexOf(scriptSource) > -1) {
                delete scripts[id];
            }
        }
    }
}
    
// Expose `f3h` variable to global to be used by other plugins
win.f3h = f3h;

})(this, this.document);
