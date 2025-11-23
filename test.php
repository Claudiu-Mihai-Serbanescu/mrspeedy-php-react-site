<!DOCTYPE html>
<html lang="en-GB">

<head>
    <meta charset="UTF-8">
    <script>
        if (navigator.userAgent.match(/MSIE|Internet Explorer/i) || navigator.userAgent.match(/Trident\/7\..*?rv:11/i)) {
            var href = document.location.href;
            if (!href.match(/[?&]nowprocket/)) {
                if (href.indexOf("?") == -1) {
                    if (href.indexOf("#") == -1) {
                        document.location.href = href + "?nowprocket=1"
                    } else {
                        document.location.href = href.replace("#", "?nowprocket=1#")
                    }
                } else {
                    if (href.indexOf("#") == -1) {
                        document.location.href = href + "&nowprocket=1"
                    } else {
                        document.location.href = href.replace("#", "&nowprocket=1#")
                    }
                }
            }
        }
    </script>
    <script>
        class RocketLazyLoadScripts {
            constructor(e) {
                this.triggerEvents = e, this.eventOptions = {
                    passive: !0
                }, this.userEventListener = this.triggerListener.bind(this), this.delayedScripts = {
                    normal: [],
                    async: [],
                    defer: []
                }, this.allJQueries = []
            }
            _addUserInteractionListener(e) {
                this.triggerEvents.forEach((t => window.addEventListener(t, e.userEventListener, e.eventOptions)))
            }
            _removeUserInteractionListener(e) {
                this.triggerEvents.forEach((t => window.removeEventListener(t, e.userEventListener, e.eventOptions)))
            }
            triggerListener() {
                this._removeUserInteractionListener(this), "loading" === document.readyState ? document.addEventListener("DOMContentLoaded", this._loadEverythingNow.bind(this)) : this._loadEverythingNow()
            }
            async _loadEverythingNow() {
                this._delayEventListeners(), this._delayJQueryReady(this), this._handleDocumentWrite(), this._registerAllDelayedScripts(), this._preloadAllScripts(), await this._loadScriptsFromList(this.delayedScripts.normal), await this._loadScriptsFromList(this.delayedScripts.defer), await this._loadScriptsFromList(this.delayedScripts.async), await this._triggerDOMContentLoaded(), await this._triggerWindowLoad(), window.dispatchEvent(new Event("rocket-allScriptsLoaded"))
            }
            _registerAllDelayedScripts() {
                document.querySelectorAll("script[type=rocketlazyloadscript]").forEach((e => {
                    e.hasAttribute("src") ? e.hasAttribute("async") && !1 !== e.async ? this.delayedScripts.async.push(e) : e.hasAttribute("defer") && !1 !== e.defer || "module" === e.getAttribute("data-rocket-type") ? this.delayedScripts.defer.push(e) : this.delayedScripts.normal.push(e) : this.delayedScripts.normal.push(e)
                }))
            }
            async _transformScript(e) {
                return await this._requestAnimFrame(), new Promise((t => {
                    const n = document.createElement("script");
                    let r;
                    [...e.attributes].forEach((e => {
                        let t = e.nodeName;
                        "type" !== t && ("data-rocket-type" === t && (t = "type", r = e.nodeValue), n.setAttribute(t, e.nodeValue))
                    })), e.hasAttribute("src") ? (n.addEventListener("load", t), n.addEventListener("error", t)) : (n.text = e.text, t()), e.parentNode.replaceChild(n, e)
                }))
            }
            async _loadScriptsFromList(e) {
                const t = e.shift();
                return t ? (await this._transformScript(t), this._loadScriptsFromList(e)) : Promise.resolve()
            }
            _preloadAllScripts() {
                var e = document.createDocumentFragment();
                [...this.delayedScripts.normal, ...this.delayedScripts.defer, ...this.delayedScripts.async].forEach((t => {
                    const n = t.getAttribute("src");
                    if (n) {
                        const t = document.createElement("link");
                        t.href = n, t.rel = "preload", t.as = "script", e.appendChild(t)
                    }
                })), document.head.appendChild(e)
            }
            _delayEventListeners() {
                let e = {};

                function t(t, n) {
                    ! function(t) {
                        function n(n) {
                            return e[t].eventsToRewrite.indexOf(n) >= 0 ? "rocket-" + n : n
                        }
                        e[t] || (e[t] = {
                            originalFunctions: {
                                add: t.addEventListener,
                                remove: t.removeEventListener
                            },
                            eventsToRewrite: []
                        }, t.addEventListener = function() {
                            arguments[0] = n(arguments[0]), e[t].originalFunctions.add.apply(t, arguments)
                        }, t.removeEventListener = function() {
                            arguments[0] = n(arguments[0]), e[t].originalFunctions.remove.apply(t, arguments)
                        })
                    }(t), e[t].eventsToRewrite.push(n)
                }

                function n(e, t) {
                    let n = e[t];
                    Object.defineProperty(e, t, {
                        get: () => n || function() {},
                        set(r) {
                            e["rocket" + t] = n = r
                        }
                    })
                }
                t(document, "DOMContentLoaded"), t(window, "DOMContentLoaded"), t(window, "load"), t(window, "pageshow"), t(document, "readystatechange"), n(document, "onreadystatechange"), n(window, "onload"), n(window, "onpageshow")
            }
            _delayJQueryReady(e) {
                let t = window.jQuery;
                Object.defineProperty(window, "jQuery", {
                    get: () => t,
                    set(n) {
                        if (n && n.fn && !e.allJQueries.includes(n)) {
                            n.fn.ready = n.fn.init.prototype.ready = function(t) {
                                e.domReadyFired ? t.bind(document)(n) : document.addEventListener("rocket-DOMContentLoaded", (() => t.bind(document)(n)))
                            };
                            const t = n.fn.on;
                            n.fn.on = n.fn.init.prototype.on = function() {
                                if (this[0] === window) {
                                    function e(e) {
                                        return e.split(" ").map((e => "load" === e || 0 === e.indexOf("load.") ? "rocket-jquery-load" : e)).join(" ")
                                    }
                                    "string" == typeof arguments[0] || arguments[0] instanceof String ? arguments[0] = e(arguments[0]) : "object" == typeof arguments[0] && Object.keys(arguments[0]).forEach((t => {
                                        delete Object.assign(arguments[0], {
                                            [e(t)]: arguments[0][t]
                                        })[t]
                                    }))
                                }
                                return t.apply(this, arguments), this
                            }, e.allJQueries.push(n)
                        }
                        t = n
                    }
                })
            }
            async _triggerDOMContentLoaded() {
                this.domReadyFired = !0, await this._requestAnimFrame(), document.dispatchEvent(new Event("rocket-DOMContentLoaded")), await this._requestAnimFrame(), window.dispatchEvent(new Event("rocket-DOMContentLoaded")), await this._requestAnimFrame(), document.dispatchEvent(new Event("rocket-readystatechange")), await this._requestAnimFrame(), document.rocketonreadystatechange && document.rocketonreadystatechange()
            }
            async _triggerWindowLoad() {
                await this._requestAnimFrame(), window.dispatchEvent(new Event("rocket-load")), await this._requestAnimFrame(), window.rocketonload && window.rocketonload(), await this._requestAnimFrame(), this.allJQueries.forEach((e => e(window).trigger("rocket-jquery-load"))), window.dispatchEvent(new Event("rocket-pageshow")), await this._requestAnimFrame(), window.rocketonpageshow && window.rocketonpageshow()
            }
            _handleDocumentWrite() {
                const e = new Map;
                document.write = document.writeln = function(t) {
                    const n = document.currentScript,
                        r = document.createRange(),
                        i = n.parentElement;
                    let o = e.get(n);
                    void 0 === o && (o = n.nextSibling, e.set(n, o));
                    const a = document.createDocumentFragment();
                    r.setStart(a, 0), a.appendChild(r.createContextualFragment(t)), i.insertBefore(a, o)
                }
            }
            async _requestAnimFrame() {
                return new Promise((e => requestAnimationFrame(e)))
            }
            static run() {
                const e = new RocketLazyLoadScripts(["keydown", "mousemove", "touchmove", "touchstart", "touchend", "wheel"]);
                e._addUserInteractionListener(e)
            }
        }
        RocketLazyLoadScripts.run();
    </script>

    <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />
    <style>
        img:is([sizes="auto" i], [sizes^="auto," i]) {
            contain-intrinsic-size: 3000px 1500px
        }
    </style>

    <!-- Google Tag Manager for WordPress by gtm4wp.com -->
    <script data-cfasync="false" data-pagespeed-no-defer>
        var gtm4wp_datalayer_name = "dataLayer";
        var dataLayer = dataLayer || [];
    </script>
    <!-- End Google Tag Manager for WordPress by gtm4wp.com -->
    <!-- This site is optimized with the Yoast SEO plugin v23.0 - https://yoast.com/wordpress/plugins/seo/ -->
    <title>Locksmith Coventry - 24/7 Service | VB Locksmith Services</title>
    <link rel="stylesheet" href="https://vblocksmithservices.co.uk/wp-content/cache/min/1/e93bf211a2f7dcf84419ea59cbfd0502.css" media="all" data-minify="1" />
    <meta name="description" content="Get reliable emergency locksmith services in Coventry 🔑 Lockouts, repairs, and installations available 24/7 ⭐ 5 Star Google Rating 🚫 No call-out fee 📲 Call today for a fast response!" />
    <link rel="canonical" href="https://vblocksmithservices.co.uk/areas-we-cover/coventry/" />
    <meta property="og:locale" content="en_GB" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Locksmith Coventry - 24/7 Service | VB Locksmith Services" />
    <meta property="og:description" content="Get reliable emergency locksmith services in Coventry 🔑 Lockouts, repairs, and installations available 24/7 ⭐ 5 Star Google Rating 🚫 No call-out fee 📲 Call today for a fast response!" />
    <meta property="og:url" content="https://vblocksmithservices.co.uk/areas-we-cover/coventry/" />
    <meta property="og:site_name" content="VB Locksmith Services" />
    <meta property="article:modified_time" content="2025-10-24T11:56:08+00:00" />
    <meta property="og:image" content="https://vblocksmithservices.co.uk/wp-content/uploads/2025/10/trusted-member-of-associations.png" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:label1" content="Estimated reading time" />
    <meta name="twitter:data1" content="10 minutes" />
    <script type="application/ld+json" class="yoast-schema-graph">
        {
            "@context": "https://schema.org",
            "@graph": [{
                "@type": "WebPage",
                "@id": "https://vblocksmithservices.co.uk/areas-we-cover/coventry/",
                "url": "https://vblocksmithservices.co.uk/areas-we-cover/coventry/",
                "name": "Locksmith Coventry - 24/7 Service | VB Locksmith Services",
                "isPartOf": {
                    "@id": "https://vblocksmithservices.co.uk/#website"
                },
                "primaryImageOfPage": {
                    "@id": "https://vblocksmithservices.co.uk/areas-we-cover/coventry/#primaryimage"
                },
                "image": {
                    "@id": "https://vblocksmithservices.co.uk/areas-we-cover/coventry/#primaryimage"
                },
                "thumbnailUrl": "https://vblocksmithservices.co.uk/wp-content/uploads/2025/10/trusted-member-of-associations.png",
                "datePublished": "2025-02-10T14:14:09+00:00",
                "dateModified": "2025-10-24T11:56:08+00:00",
                "description": "Get reliable emergency locksmith services in Coventry 🔑 Lockouts, repairs, and installations available 24/7 ⭐ 5 Star Google Rating 🚫 No call-out fee 📲 Call today for a fast response!",
                "breadcrumb": {
                    "@id": "https://vblocksmithservices.co.uk/areas-we-cover/coventry/#breadcrumb"
                },
                "inLanguage": "en-GB",
                "potentialAction": [{
                    "@type": "ReadAction",
                    "target": ["https://vblocksmithservices.co.uk/areas-we-cover/coventry/"]
                }]
            }, {
                "@type": "ImageObject",
                "inLanguage": "en-GB",
                "@id": "https://vblocksmithservices.co.uk/areas-we-cover/coventry/#primaryimage",
                "url": "https://vblocksmithservices.co.uk/wp-content/uploads/2025/10/trusted-member-of-associations.png",
                "contentUrl": "https://vblocksmithservices.co.uk/wp-content/uploads/2025/10/trusted-member-of-associations.png",
                "width": 512,
                "height": 228
            }, {
                "@type": "BreadcrumbList",
                "@id": "https://vblocksmithservices.co.uk/areas-we-cover/coventry/#breadcrumb",
                "itemListElement": [{
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Areas We Cover",
                    "item": "https://vblocksmithservices.co.uk/areas-we-cover/"
                }, {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "Coventry"
                }]
            }, {
                "@type": "WebSite",
                "@id": "https://vblocksmithservices.co.uk/#website",
                "url": "https://vblocksmithservices.co.uk/",
                "name": "VB Locksmith Services",
                "description": "",
                "publisher": {
                    "@id": "https://vblocksmithservices.co.uk/#organization"
                },
                "potentialAction": [{
                    "@type": "SearchAction",
                    "target": {
                        "@type": "EntryPoint",
                        "urlTemplate": "https://vblocksmithservices.co.uk/?s={search_term_string}"
                    },
                    "query-input": "required name=search_term_string"
                }],
                "inLanguage": "en-GB"
            }, {
                "@type": "Organization",
                "@id": "https://vblocksmithservices.co.uk/#organization",
                "name": "VB Locksmith Services",
                "url": "https://vblocksmithservices.co.uk/",
                "logo": {
                    "@type": "ImageObject",
                    "inLanguage": "en-GB",
                    "@id": "https://vblocksmithservices.co.uk/#/schema/logo/image/",
                    "url": "https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo.png",
                    "contentUrl": "https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo.png",
                    "width": 367,
                    "height": 381,
                    "caption": "VB Locksmith Services"
                },
                "image": {
                    "@id": "https://vblocksmithservices.co.uk/#/schema/logo/image/"
                }
            }]
        }
    </script>
    <!-- / Yoast SEO plugin. -->


    <link rel='dns-prefetch' href='//fonts.googleapis.com' />
    <link rel="alternate" type="application/rss+xml" title="VB Locksmith Services &raquo; Feed" href="https://vblocksmithservices.co.uk/feed/" />
    <link rel="alternate" type="application/rss+xml" title="VB Locksmith Services &raquo; Comments Feed" href="https://vblocksmithservices.co.uk/comments/feed/" />
    <script type="rocketlazyloadscript">
        window._wpemojiSettings = {"baseUrl":"https:\/\/s.w.org\/images\/core\/emoji\/16.0.1\/72x72\/","ext":".png","svgUrl":"https:\/\/s.w.org\/images\/core\/emoji\/16.0.1\/svg\/","svgExt":".svg","source":{"concatemoji":"https:\/\/vblocksmithservices.co.uk\/wp-includes\/js\/wp-emoji-release.min.js?ver=3e6620794598ea959ffe49e82791d486"}};
/*! This file is auto-generated */
!function(s,n){var o,i,e;function c(e){try{var t={supportTests:e,timestamp:(new Date).valueOf()};sessionStorage.setItem(o,JSON.stringify(t))}catch(e){}}function p(e,t,n){e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(t,0,0);var t=new Uint32Array(e.getImageData(0,0,e.canvas.width,e.canvas.height).data),a=(e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(n,0,0),new Uint32Array(e.getImageData(0,0,e.canvas.width,e.canvas.height).data));return t.every(function(e,t){return e===a[t]})}function u(e,t){e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(t,0,0);for(var n=e.getImageData(16,16,1,1),a=0;a<n.data.length;a++)if(0!==n.data[a])return!1;return!0}function f(e,t,n,a){switch(t){case"flag":return n(e,"\ud83c\udff3\ufe0f\u200d\u26a7\ufe0f","\ud83c\udff3\ufe0f\u200b\u26a7\ufe0f")?!1:!n(e,"\ud83c\udde8\ud83c\uddf6","\ud83c\udde8\u200b\ud83c\uddf6")&&!n(e,"\ud83c\udff4\udb40\udc67\udb40\udc62\udb40\udc65\udb40\udc6e\udb40\udc67\udb40\udc7f","\ud83c\udff4\u200b\udb40\udc67\u200b\udb40\udc62\u200b\udb40\udc65\u200b\udb40\udc6e\u200b\udb40\udc67\u200b\udb40\udc7f");case"emoji":return!a(e,"\ud83e\udedf")}return!1}function g(e,t,n,a){var r="undefined"!=typeof WorkerGlobalScope&&self instanceof WorkerGlobalScope?new OffscreenCanvas(300,150):s.createElement("canvas"),o=r.getContext("2d",{willReadFrequently:!0}),i=(o.textBaseline="top",o.font="600 32px Arial",{});return e.forEach(function(e){i[e]=t(o,e,n,a)}),i}function t(e){var t=s.createElement("script");t.src=e,t.defer=!0,s.head.appendChild(t)}"undefined"!=typeof Promise&&(o="wpEmojiSettingsSupports",i=["flag","emoji"],n.supports={everything:!0,everythingExceptFlag:!0},e=new Promise(function(e){s.addEventListener("DOMContentLoaded",e,{once:!0})}),new Promise(function(t){var n=function(){try{var e=JSON.parse(sessionStorage.getItem(o));if("object"==typeof e&&"number"==typeof e.timestamp&&(new Date).valueOf()<e.timestamp+604800&&"object"==typeof e.supportTests)return e.supportTests}catch(e){}return null}();if(!n){if("undefined"!=typeof Worker&&"undefined"!=typeof OffscreenCanvas&&"undefined"!=typeof URL&&URL.createObjectURL&&"undefined"!=typeof Blob)try{var e="postMessage("+g.toString()+"("+[JSON.stringify(i),f.toString(),p.toString(),u.toString()].join(",")+"));",a=new Blob([e],{type:"text/javascript"}),r=new Worker(URL.createObjectURL(a),{name:"wpTestEmojiSupports"});return void(r.onmessage=function(e){c(n=e.data),r.terminate(),t(n)})}catch(e){}c(n=g(i,f,p,u))}t(n)}).then(function(e){for(var t in e)n.supports[t]=e[t],n.supports.everything=n.supports.everything&&n.supports[t],"flag"!==t&&(n.supports.everythingExceptFlag=n.supports.everythingExceptFlag&&n.supports[t]);n.supports.everythingExceptFlag=n.supports.everythingExceptFlag&&!n.supports.flag,n.DOMReady=!1,n.readyCallback=function(){n.DOMReady=!0}}).then(function(){return e}).then(function(){var e;n.supports.everything||(n.readyCallback(),(e=n.source||{}).concatemoji?t(e.concatemoji):e.wpemoji&&e.twemoji&&(t(e.twemoji),t(e.wpemoji)))}))}((window,document),window._wpemojiSettings);
</script>

    <style id='astra-theme-css-inline-css'>
        :root {
            --ast-post-nav-space: 0;
            --ast-container-default-xlg-padding: 6.67em;
            --ast-container-default-lg-padding: 5.67em;
            --ast-container-default-slg-padding: 4.34em;
            --ast-container-default-md-padding: 3.34em;
            --ast-container-default-sm-padding: 6.67em;
            --ast-container-default-xs-padding: 2.4em;
            --ast-container-default-xxs-padding: 1.4em;
            --ast-code-block-background: #EEEEEE;
            --ast-comment-inputs-background: #FAFAFA;
            --ast-normal-container-width: 1200px;
            --ast-narrow-container-width: 750px;
            --ast-blog-title-font-weight: normal;
            --ast-blog-meta-weight: inherit;
        }

        html {
            font-size: 93.75%;
        }

        a,
        .page-title {
            color: var(--ast-global-color-5);
        }

        a:hover,
        a:focus {
            color: var(--ast-global-color-1);
        }

        body,
        button,
        input,
        select,
        textarea,
        .ast-button,
        .ast-custom-button {
            font-family: 'Open Sans', sans-serif;
            font-weight: 400;
            font-size: 15px;
            font-size: 1rem;
            line-height: var(--ast-body-line-height, 1.65em);
        }

        blockquote {
            color: var(--ast-global-color-3);
        }

        h1,
        .entry-content h1,
        h2,
        .entry-content h2,
        h3,
        .entry-content h3,
        h4,
        .entry-content h4,
        h5,
        .entry-content h5,
        h6,
        .entry-content h6,
        .site-title,
        .site-title a {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }

        .site-title {
            font-size: 35px;
            font-size: 2.3333333333333rem;
            display: none;
        }

        header .custom-logo-link img {
            max-width: 115px;
        }

        .astra-logo-svg {
            width: 115px;
        }

        .site-header .site-description {
            font-size: 15px;
            font-size: 1rem;
            display: none;
        }

        .entry-title {
            font-size: 26px;
            font-size: 1.7333333333333rem;
        }

        .archive .ast-article-post .ast-article-inner,
        .blog .ast-article-post .ast-article-inner,
        .archive .ast-article-post .ast-article-inner:hover,
        .blog .ast-article-post .ast-article-inner:hover {
            overflow: hidden;
        }

        h1,
        .entry-content h1 {
            font-size: 90px;
            font-size: 6rem;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.4em;
        }

        h2,
        .entry-content h2 {
            font-size: 40px;
            font-size: 2.6666666666667rem;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.3em;
        }

        h3,
        .entry-content h3 {
            font-size: 24px;
            font-size: 1.6rem;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.3em;
        }

        h4,
        .entry-content h4 {
            font-size: 24px;
            font-size: 1.6rem;
            line-height: 1.2em;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
        }

        h5,
        .entry-content h5 {
            font-size: 20px;
            font-size: 1.3333333333333rem;
            line-height: 1.2em;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
        }

        h6,
        .entry-content h6 {
            font-size: 16px;
            font-size: 1.0666666666667rem;
            line-height: 1.25em;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
        }

        ::selection {
            background-color: var(--ast-global-color-0);
            color: #000000;
        }

        body,
        h1,
        .entry-title a,
        .entry-content h1,
        h2,
        .entry-content h2,
        h3,
        .entry-content h3,
        h4,
        .entry-content h4,
        h5,
        .entry-content h5,
        h6,
        .entry-content h6 {
            color: var(--ast-global-color-3);
        }

        .tagcloud a:hover,
        .tagcloud a:focus,
        .tagcloud a.current-item {
            color: #000000;
            border-color: var(--ast-global-color-5);
            background-color: var(--ast-global-color-5);
        }

        input:focus,
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="url"]:focus,
        input[type="password"]:focus,
        input[type="reset"]:focus,
        input[type="search"]:focus,
        textarea:focus {
            border-color: var(--ast-global-color-5);
        }

        input[type="radio"]:checked,
        input[type=reset],
        input[type="checkbox"]:checked,
        input[type="checkbox"]:hover:checked,
        input[type="checkbox"]:focus:checked,
        input[type=range]::-webkit-slider-thumb {
            border-color: var(--ast-global-color-5);
            background-color: var(--ast-global-color-5);
            box-shadow: none;
        }

        .site-footer a:hover+.post-count,
        .site-footer a:focus+.post-count {
            background: var(--ast-global-color-5);
            border-color: var(--ast-global-color-5);
        }

        .single .nav-links .nav-previous,
        .single .nav-links .nav-next {
            color: var(--ast-global-color-5);
        }

        .entry-meta,
        .entry-meta * {
            line-height: 1.45;
            color: var(--ast-global-color-5);
        }

        .entry-meta a:not(.ast-button):hover,
        .entry-meta a:not(.ast-button):hover *,
        .entry-meta a:not(.ast-button):focus,
        .entry-meta a:not(.ast-button):focus *,
        .page-links>.page-link,
        .page-links .page-link:hover,
        .post-navigation a:hover {
            color: var(--ast-global-color-1);
        }

        #cat option,
        .secondary .calendar_wrap thead a,
        .secondary .calendar_wrap thead a:visited {
            color: var(--ast-global-color-5);
        }

        .secondary .calendar_wrap #today,
        .ast-progress-val span {
            background: var(--ast-global-color-5);
        }

        .secondary a:hover+.post-count,
        .secondary a:focus+.post-count {
            background: var(--ast-global-color-5);
            border-color: var(--ast-global-color-5);
        }

        .calendar_wrap #today>a {
            color: #000000;
        }

        .page-links .page-link,
        .single .post-navigation a {
            color: var(--ast-global-color-5);
        }

        .ast-search-menu-icon .search-form button.search-submit {
            padding: 0 4px;
        }

        .ast-search-menu-icon form.search-form {
            padding-right: 0;
        }

        .ast-search-menu-icon.slide-search input.search-field {
            width: 0;
        }

        .ast-header-search .ast-search-menu-icon.ast-dropdown-active .search-form,
        .ast-header-search .ast-search-menu-icon.ast-dropdown-active .search-field:focus {
            transition: all 0.2s;
        }

        .search-form input.search-field:focus {
            outline: none;
        }

        .ast-archive-title {
            color: var(--ast-global-color-2);
        }

        .wp-block-latest-posts>li>a {
            color: var(--ast-global-color-2);
        }

        .widget-title,
        .widget .wp-block-heading {
            font-size: 21px;
            font-size: 1.4rem;
            color: var(--ast-global-color-2);
        }

        .single .ast-author-details .author-title {
            color: var(--ast-global-color-1);
        }

        .ast-search-menu-icon.slide-search a:focus-visible:focus-visible,
        .astra-search-icon:focus-visible,
        #close:focus-visible,
        a:focus-visible,
        .ast-menu-toggle:focus-visible,
        .site .skip-link:focus-visible,
        .wp-block-loginout input:focus-visible,
        .wp-block-search.wp-block-search__button-inside .wp-block-search__inside-wrapper,
        .ast-header-navigation-arrow:focus-visible,
        .woocommerce .wc-proceed-to-checkout>.checkout-button:focus-visible,
        .woocommerce .woocommerce-MyAccount-navigation ul li a:focus-visible,
        .ast-orders-table__row .ast-orders-table__cell:focus-visible,
        .woocommerce .woocommerce-order-details .order-again>.button:focus-visible,
        .woocommerce .woocommerce-message a.button.wc-forward:focus-visible,
        .woocommerce #minus_qty:focus-visible,
        .woocommerce #plus_qty:focus-visible,
        a#ast-apply-coupon:focus-visible,
        .woocommerce .woocommerce-info a:focus-visible,
        .woocommerce .astra-shop-summary-wrap a:focus-visible,
        .woocommerce a.wc-forward:focus-visible,
        #ast-apply-coupon:focus-visible,
        .woocommerce-js .woocommerce-mini-cart-item a.remove:focus-visible,
        #close:focus-visible,
        .button.search-submit:focus-visible,
        #search_submit:focus,
        .normal-search:focus-visible,
        .ast-header-account-wrap:focus-visible {
            outline-style: dotted;
            outline-color: inherit;
            outline-width: thin;
        }

        input:focus,
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="url"]:focus,
        input[type="password"]:focus,
        input[type="reset"]:focus,
        input[type="search"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        .wp-block-search__input:focus,
        [data-section="section-header-mobile-trigger"] .ast-button-wrap .ast-mobile-menu-trigger-minimal:focus,
        .ast-mobile-popup-drawer.active .menu-toggle-close:focus,
        .woocommerce-ordering select.orderby:focus,
        #ast-scroll-top:focus,
        #coupon_code:focus,
        .woocommerce-page #comment:focus,
        .woocommerce #reviews #respond input#submit:focus,
        .woocommerce a.add_to_cart_button:focus,
        .woocommerce .button.single_add_to_cart_button:focus,
        .woocommerce .woocommerce-cart-form button:focus,
        .woocommerce .woocommerce-cart-form__cart-item .quantity .qty:focus,
        .woocommerce .woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper .woocommerce-input-wrapper>.input-text:focus,
        .woocommerce #order_comments:focus,
        .woocommerce #place_order:focus,
        .woocommerce .woocommerce-address-fields .woocommerce-address-fields__field-wrapper .woocommerce-input-wrapper>.input-text:focus,
        .woocommerce .woocommerce-MyAccount-content form button:focus,
        .woocommerce .woocommerce-MyAccount-content .woocommerce-EditAccountForm .woocommerce-form-row .woocommerce-Input.input-text:focus,
        .woocommerce .ast-woocommerce-container .woocommerce-pagination ul.page-numbers li a:focus,
        body #content .woocommerce form .form-row .select2-container--default .select2-selection--single:focus,
        #ast-coupon-code:focus,
        .woocommerce.woocommerce-js .quantity input[type=number]:focus,
        .woocommerce-js .woocommerce-mini-cart-item .quantity input[type=number]:focus,
        .woocommerce p#ast-coupon-trigger:focus {
            border-style: dotted;
            border-color: inherit;
            border-width: thin;
        }

        input {
            outline: none;
        }

        .ast-logo-title-inline .site-logo-img {
            padding-right: 1em;
        }

        .site-logo-img img {
            transition: all 0.2s linear;
        }

        body .ast-oembed-container * {
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            left: 0;
        }

        body .wp-block-embed-pocket-casts .ast-oembed-container * {
            position: unset;
        }

        .ast-single-post-featured-section+article {
            margin-top: 2em;
        }

        .site-content .ast-single-post-featured-section img {
            width: 100%;
            overflow: hidden;
            object-fit: cover;
        }

        .site>.ast-single-related-posts-container {
            margin-top: 0;
        }

        @media (min-width: 922px) {
            .ast-desktop .ast-container--narrow {
                max-width: var(--ast-narrow-container-width);
                margin: 0 auto;
            }
        }

        .ast-page-builder-template .hentry {
            margin: 0;
        }

        .ast-page-builder-template .site-content>.ast-container {
            max-width: 100%;
            padding: 0;
        }

        .ast-page-builder-template .site .site-content #primary {
            padding: 0;
            margin: 0;
        }

        .ast-page-builder-template .no-results {
            text-align: center;
            margin: 4em auto;
        }

        .ast-page-builder-template .ast-pagination {
            padding: 2em;
        }

        .ast-page-builder-template .entry-header.ast-no-title.ast-no-thumbnail {
            margin-top: 0;
        }

        .ast-page-builder-template .entry-header.ast-header-without-markup {
            margin-top: 0;
            margin-bottom: 0;
        }

        .ast-page-builder-template .entry-header.ast-no-title.ast-no-meta {
            margin-bottom: 0;
        }

        .ast-page-builder-template.single .post-navigation {
            padding-bottom: 2em;
        }

        .ast-page-builder-template.single-post .site-content>.ast-container {
            max-width: 100%;
        }

        .ast-page-builder-template .entry-header {
            margin-top: 4em;
            margin-left: auto;
            margin-right: auto;
            padding-left: 20px;
            padding-right: 20px;
        }

        .single.ast-page-builder-template .entry-header {
            padding-left: 20px;
            padding-right: 20px;
        }

        .ast-page-builder-template .ast-archive-description {
            margin: 4em auto 0;
            padding-left: 20px;
            padding-right: 20px;
        }

        @media (max-width:921.9px) {
            #ast-desktop-header {
                display: none;
            }
        }

        @media (min-width:922px) {
            #ast-mobile-header {
                display: none;
            }
        }

        .wp-block-buttons.aligncenter {
            justify-content: center;
        }

        @media (max-width:921px) {

            .ast-theme-transparent-header #primary,
            .ast-theme-transparent-header #secondary {
                padding: 0;
            }
        }

        @media (max-width:921px) {
            .ast-plain-container.ast-no-sidebar #primary {
                padding: 0;
            }
        }

        .ast-plain-container.ast-no-sidebar #primary {
            margin-top: 0;
            margin-bottom: 0;
        }

        .wp-block-button.is-style-outline .wp-block-button__link {
            border-color: var(--ast-global-color-0);
            border-top-width: 0;
            border-right-width: 0;
            border-bottom-width: 0;
            border-left-width: 0;
        }

        div.wp-block-button.is-style-outline>.wp-block-button__link:not(.has-text-color),
        div.wp-block-button.wp-block-button__link.is-style-outline:not(.has-text-color) {
            color: var(--ast-global-color-0);
        }

        .wp-block-button.is-style-outline .wp-block-button__link:hover,
        .wp-block-buttons .wp-block-button.is-style-outline .wp-block-button__link:focus,
        .wp-block-buttons .wp-block-button.is-style-outline>.wp-block-button__link:not(.has-text-color):hover,
        .wp-block-buttons .wp-block-button.wp-block-button__link.is-style-outline:not(.has-text-color):hover {
            color: var(--ast-global-color-2);
            background-color: var(--ast-global-color-1);
            border-color: var(--ast-global-color-1);
        }

        .post-page-numbers.current .page-link,
        .ast-pagination .page-numbers.current {
            color: #000000;
            border-color: var(--ast-global-color-0);
            background-color: var(--ast-global-color-0);
        }

        .wp-block-button.is-style-outline .wp-block-button__link {
            border-top-width: 0;
            border-right-width: 0;
            border-bottom-width: 0;
            border-left-width: 0;
        }

        .wp-block-button.is-style-outline .wp-block-button__link.wp-element-button,
        .ast-outline-button {
            border-color: var(--ast-global-color-0);
            font-family: inherit;
            font-weight: 500;
            line-height: 1em;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .wp-block-buttons .wp-block-button.is-style-outline>.wp-block-button__link:not(.has-text-color),
        .wp-block-buttons .wp-block-button.wp-block-button__link.is-style-outline:not(.has-text-color),
        .ast-outline-button {
            color: var(--ast-global-color-0);
        }

        .wp-block-button.is-style-outline .wp-block-button__link:hover,
        .wp-block-buttons .wp-block-button.is-style-outline .wp-block-button__link:focus,
        .wp-block-buttons .wp-block-button.is-style-outline>.wp-block-button__link:not(.has-text-color):hover,
        .wp-block-buttons .wp-block-button.wp-block-button__link.is-style-outline:not(.has-text-color):hover,
        .ast-outline-button:hover,
        .ast-outline-button:focus,
        .wp-block-uagb-buttons-child .uagb-buttons-repeater.ast-outline-button:hover,
        .wp-block-uagb-buttons-child .uagb-buttons-repeater.ast-outline-button:focus {
            color: var(--ast-global-color-2);
            background-color: var(--ast-global-color-1);
            border-color: var(--ast-global-color-1);
        }

        .wp-block-button .wp-block-button__link.wp-element-button.is-style-outline:not(.has-background),
        .wp-block-button.is-style-outline>.wp-block-button__link.wp-element-button:not(.has-background),
        .ast-outline-button {
            background-color: var(--ast-global-color-0);
        }

        .entry-content[ast-blocks-layout]>figure {
            margin-bottom: 1em;
        }

        h1.widget-title {
            font-weight: 700;
        }

        h2.widget-title {
            font-weight: 700;
        }

        h3.widget-title {
            font-weight: 700;
        }

        @media (max-width:921px) {

            .ast-separate-container #primary,
            .ast-separate-container #secondary {
                padding: 1.5em 0;
            }

            #primary,
            #secondary {
                padding: 1.5em 0;
                margin: 0;
            }

            .ast-left-sidebar #content>.ast-container {
                display: flex;
                flex-direction: column-reverse;
                width: 100%;
            }

            .ast-separate-container .ast-article-post,
            .ast-separate-container .ast-article-single {
                padding: 1.5em 2.14em;
            }

            .ast-author-box img.avatar {
                margin: 20px 0 0 0;
            }
        }

        @media (min-width:922px) {

            .ast-separate-container.ast-right-sidebar #primary,
            .ast-separate-container.ast-left-sidebar #primary {
                border: 0;
            }

            .search-no-results.ast-separate-container #primary {
                margin-bottom: 4em;
            }
        }

        .elementor-button-wrapper .elementor-button {
            border-style: solid;
            text-decoration: none;
            border-top-width: 0;
            border-right-width: 0;
            border-left-width: 0;
            border-bottom-width: 0;
        }

        body .elementor-button.elementor-size-sm,
        body .elementor-button.elementor-size-xs,
        body .elementor-button.elementor-size-md,
        body .elementor-button.elementor-size-lg,
        body .elementor-button.elementor-size-xl,
        body .elementor-button {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            padding-top: 15px;
            padding-right: 45px;
            padding-bottom: 15px;
            padding-left: 45px;
        }

        .elementor-button-wrapper .elementor-button {
            border-color: var(--ast-global-color-0);
            background-color: var(--ast-global-color-0);
        }

        .elementor-button-wrapper .elementor-button:hover,
        .elementor-button-wrapper .elementor-button:focus {
            color: var(--ast-global-color-2);
            background-color: var(--ast-global-color-1);
            border-color: var(--ast-global-color-1);
        }

        .wp-block-button .wp-block-button__link,
        .elementor-button-wrapper .elementor-button,
        .elementor-button-wrapper .elementor-button:visited {
            color: var(--ast-global-color-2);
        }

        .elementor-button-wrapper .elementor-button {
            font-weight: 500;
            line-height: 1em;
            text-transform: uppercase;
        }

        .wp-block-button .wp-block-button__link:hover,
        .wp-block-button .wp-block-button__link:focus {
            color: var(--ast-global-color-2);
            background-color: var(--ast-global-color-1);
            border-color: var(--ast-global-color-1);
        }

        .elementor-widget-heading h1.elementor-heading-title {
            line-height: 1.4em;
        }

        .elementor-widget-heading h2.elementor-heading-title {
            line-height: 1.3em;
        }

        .elementor-widget-heading h3.elementor-heading-title {
            line-height: 1.3em;
        }

        .elementor-widget-heading h4.elementor-heading-title {
            line-height: 1.2em;
        }

        .elementor-widget-heading h5.elementor-heading-title {
            line-height: 1.2em;
        }

        .elementor-widget-heading h6.elementor-heading-title {
            line-height: 1.25em;
        }

        .wp-block-button .wp-block-button__link,
        .wp-block-search .wp-block-search__button,
        body .wp-block-file .wp-block-file__button {
            border-top-width: 0;
            border-right-width: 0;
            border-left-width: 0;
            border-bottom-width: 0;
            border-color: var(--ast-global-color-0);
            background-color: var(--ast-global-color-0);
            color: var(--ast-global-color-2);
            font-family: inherit;
            font-weight: 500;
            line-height: 1em;
            text-transform: uppercase;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            padding-top: 15px;
            padding-right: 45px;
            padding-bottom: 15px;
            padding-left: 45px;
        }

        .menu-toggle,
        button,
        .ast-button,
        .ast-custom-button,
        .button,
        input#submit,
        input[type="button"],
        input[type="submit"],
        input[type="reset"],
        form[CLASS*="wp-block-search__"].wp-block-search .wp-block-search__inside-wrapper .wp-block-search__button,
        body .wp-block-file .wp-block-file__button,
        .woocommerce-js a.button,
        .woocommerce button.button,
        .woocommerce .woocommerce-message a.button,
        .woocommerce #respond input#submit.alt,
        .woocommerce input.button.alt,
        .woocommerce input.button,
        .woocommerce input.button:disabled,
        .woocommerce input.button:disabled[disabled],
        .woocommerce input.button:disabled:hover,
        .woocommerce input.button:disabled[disabled]:hover,
        .woocommerce #respond input#submit,
        .woocommerce button.button.alt.disabled,
        .wc-block-grid__products .wc-block-grid__product .wp-block-button__link,
        .wc-block-grid__product-onsale,
        [CLASS*="wc-block"] button,
        .woocommerce-js .astra-cart-drawer .astra-cart-drawer-content .woocommerce-mini-cart__buttons .button:not(.checkout):not(.ast-continue-shopping),
        .woocommerce-js .astra-cart-drawer .astra-cart-drawer-content .woocommerce-mini-cart__buttons a.checkout,
        .woocommerce button.button.alt.disabled.wc-variation-selection-needed,
        [CLASS*="wc-block"] .wc-block-components-button {
            border-style: solid;
            border-top-width: 0;
            border-right-width: 0;
            border-left-width: 0;
            border-bottom-width: 0;
            color: var(--ast-global-color-2);
            border-color: var(--ast-global-color-0);
            background-color: var(--ast-global-color-0);
            padding-top: 15px;
            padding-right: 45px;
            padding-bottom: 15px;
            padding-left: 45px;
            font-family: inherit;
            font-weight: 500;
            line-height: 1em;
            text-transform: uppercase;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        button:focus,
        .menu-toggle:hover,
        button:hover,
        .ast-button:hover,
        .ast-custom-button:hover .button:hover,
        .ast-custom-button:hover,
        input[type=reset]:hover,
        input[type=reset]:focus,
        input#submit:hover,
        input#submit:focus,
        input[type="button"]:hover,
        input[type="button"]:focus,
        input[type="submit"]:hover,
        input[type="submit"]:focus,
        form[CLASS*="wp-block-search__"].wp-block-search .wp-block-search__inside-wrapper .wp-block-search__button:hover,
        form[CLASS*="wp-block-search__"].wp-block-search .wp-block-search__inside-wrapper .wp-block-search__button:focus,
        body .wp-block-file .wp-block-file__button:hover,
        body .wp-block-file .wp-block-file__button:focus,
        .woocommerce-js a.button:hover,
        .woocommerce button.button:hover,
        .woocommerce .woocommerce-message a.button:hover,
        .woocommerce #respond input#submit:hover,
        .woocommerce #respond input#submit.alt:hover,
        .woocommerce input.button.alt:hover,
        .woocommerce input.button:hover,
        .woocommerce button.button.alt.disabled:hover,
        .wc-block-grid__products .wc-block-grid__product .wp-block-button__link:hover,
        [CLASS*="wc-block"] button:hover,
        .woocommerce-js .astra-cart-drawer .astra-cart-drawer-content .woocommerce-mini-cart__buttons .button:not(.checkout):not(.ast-continue-shopping):hover,
        .woocommerce-js .astra-cart-drawer .astra-cart-drawer-content .woocommerce-mini-cart__buttons a.checkout:hover,
        .woocommerce button.button.alt.disabled.wc-variation-selection-needed:hover,
        [CLASS*="wc-block"] .wc-block-components-button:hover,
        [CLASS*="wc-block"] .wc-block-components-button:focus {
            color: var(--ast-global-color-2);
            background-color: var(--ast-global-color-1);
            border-color: var(--ast-global-color-1);
        }

        @media (max-width:921px) {
            .ast-mobile-header-stack .main-header-bar .ast-search-menu-icon {
                display: inline-block;
            }

            .ast-header-break-point.ast-header-custom-item-outside .ast-mobile-header-stack .main-header-bar .ast-search-icon {
                margin: 0;
            }

            .ast-comment-avatar-wrap img {
                max-width: 2.5em;
            }

            .ast-comment-meta {
                padding: 0 1.8888em 1.3333em;
            }

            .ast-separate-container .ast-comment-list li.depth-1 {
                padding: 1.5em 2.14em;
            }

            .ast-separate-container .comment-respond {
                padding: 2em 2.14em;
            }
        }

        @media (min-width:544px) {
            .ast-container {
                max-width: 100%;
            }
        }

        @media (max-width:544px) {

            .ast-separate-container .ast-article-post,
            .ast-separate-container .ast-article-single,
            .ast-separate-container .comments-title,
            .ast-separate-container .ast-archive-description {
                padding: 1.5em 1em;
            }

            .ast-separate-container #content .ast-container {
                padding-left: 0.54em;
                padding-right: 0.54em;
            }

            .ast-separate-container .ast-comment-list .bypostauthor {
                padding: .5em;
            }

            .ast-search-menu-icon.ast-dropdown-active .search-field {
                width: 170px;
            }
        }

        #ast-mobile-header .ast-site-header-cart-li a {
            pointer-events: none;
        }

        #ast-desktop-header .ast-site-header-cart-li a {
            pointer-events: none;
        }

        body,
        .ast-separate-container {
            background-color: var(--ast-global-color-4);
            ;
            background-image: none;
            ;
        }

        @media (max-width:921px) {
            .site-title {
                display: none;
            }

            .site-header .site-description {
                display: none;
            }

            h1,
            .entry-content h1 {
                font-size: 60px;
            }

            h2,
            .entry-content h2 {
                font-size: 35px;
            }

            h3,
            .entry-content h3 {
                font-size: 22px;
            }
        }

        @media (max-width:544px) {
            .site-title {
                display: none;
            }

            .site-header .site-description {
                display: none;
            }

            h1,
            .entry-content h1 {
                font-size: 35px;
            }

            h2,
            .entry-content h2 {
                font-size: 30px;
            }

            h3,
            .entry-content h3 {
                font-size: 20px;
            }
        }

        @media (max-width:921px) {
            html {
                font-size: 85.5%;
            }
        }

        @media (max-width:544px) {
            html {
                font-size: 85.5%;
            }
        }

        @media (min-width:922px) {
            .ast-container {
                max-width: 1240px;
            }
        }

        @media (min-width:922px) {
            .site-content .ast-container {
                display: flex;
            }
        }

        @media (max-width:921px) {
            .site-content .ast-container {
                flex-direction: column;
            }
        }

        @media (min-width:922px) {

            .main-header-menu .sub-menu .menu-item.ast-left-align-sub-menu:hover>.sub-menu,
            .main-header-menu .sub-menu .menu-item.ast-left-align-sub-menu.focus>.sub-menu {
                margin-left: -0px;
            }
        }

        .site .comments-area {
            padding-bottom: 3em;
        }

        .wp-block-file {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .wp-block-pullquote {
            border: none;
        }

        .wp-block-pullquote blockquote::before {
            content: "\201D";
            font-family: "Helvetica", sans-serif;
            display: flex;
            transform: rotate(180deg);
            font-size: 6rem;
            font-style: normal;
            line-height: 1;
            font-weight: bold;
            align-items: center;
            justify-content: center;
        }

        .has-text-align-right>blockquote::before {
            justify-content: flex-start;
        }

        .has-text-align-left>blockquote::before {
            justify-content: flex-end;
        }

        figure.wp-block-pullquote.is-style-solid-color blockquote {
            max-width: 100%;
            text-align: inherit;
        }

        html body {
            --wp--custom--ast-default-block-top-padding: 3em;
            --wp--custom--ast-default-block-right-padding: 3em;
            --wp--custom--ast-default-block-bottom-padding: 3em;
            --wp--custom--ast-default-block-left-padding: 3em;
            --wp--custom--ast-container-width: 1200px;
            --wp--custom--ast-content-width-size: 1200px;
            --wp--custom--ast-wide-width-size: calc(1200px + var(--wp--custom--ast-default-block-left-padding) + var(--wp--custom--ast-default-block-right-padding));
        }

        .ast-narrow-container {
            --wp--custom--ast-content-width-size: 750px;
            --wp--custom--ast-wide-width-size: 750px;
        }

        @media(max-width: 921px) {
            html body {
                --wp--custom--ast-default-block-top-padding: 3em;
                --wp--custom--ast-default-block-right-padding: 2em;
                --wp--custom--ast-default-block-bottom-padding: 3em;
                --wp--custom--ast-default-block-left-padding: 2em;
            }
        }

        @media(max-width: 544px) {
            html body {
                --wp--custom--ast-default-block-top-padding: 3em;
                --wp--custom--ast-default-block-right-padding: 1.5em;
                --wp--custom--ast-default-block-bottom-padding: 3em;
                --wp--custom--ast-default-block-left-padding: 1.5em;
            }
        }

        .entry-content>.wp-block-group,
        .entry-content>.wp-block-cover,
        .entry-content>.wp-block-columns {
            padding-top: var(--wp--custom--ast-default-block-top-padding);
            padding-right: var(--wp--custom--ast-default-block-right-padding);
            padding-bottom: var(--wp--custom--ast-default-block-bottom-padding);
            padding-left: var(--wp--custom--ast-default-block-left-padding);
        }

        .ast-plain-container.ast-no-sidebar .entry-content>.alignfull,
        .ast-page-builder-template .ast-no-sidebar .entry-content>.alignfull {
            margin-left: calc(-50vw + 50%);
            margin-right: calc(-50vw + 50%);
            max-width: 100vw;
            width: 100vw;
        }

        .ast-plain-container.ast-no-sidebar .entry-content .alignfull .alignfull,
        .ast-page-builder-template.ast-no-sidebar .entry-content .alignfull .alignfull,
        .ast-plain-container.ast-no-sidebar .entry-content .alignfull .alignwide,
        .ast-page-builder-template.ast-no-sidebar .entry-content .alignfull .alignwide,
        .ast-plain-container.ast-no-sidebar .entry-content .alignwide .alignfull,
        .ast-page-builder-template.ast-no-sidebar .entry-content .alignwide .alignfull,
        .ast-plain-container.ast-no-sidebar .entry-content .alignwide .alignwide,
        .ast-page-builder-template.ast-no-sidebar .entry-content .alignwide .alignwide,
        .ast-plain-container.ast-no-sidebar .entry-content .wp-block-column .alignfull,
        .ast-page-builder-template.ast-no-sidebar .entry-content .wp-block-column .alignfull,
        .ast-plain-container.ast-no-sidebar .entry-content .wp-block-column .alignwide,
        .ast-page-builder-template.ast-no-sidebar .entry-content .wp-block-column .alignwide {
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }

        [ast-blocks-layout] .wp-block-separator:not(.is-style-dots) {
            height: 0;
        }

        [ast-blocks-layout] .wp-block-separator {
            margin: 20px auto;
        }

        [ast-blocks-layout] .wp-block-separator:not(.is-style-wide):not(.is-style-dots) {
            max-width: 100px;
        }

        [ast-blocks-layout] .wp-block-separator.has-background {
            padding: 0;
        }

        .entry-content[ast-blocks-layout]>* {
            max-width: var(--wp--custom--ast-content-width-size);
            margin-left: auto;
            margin-right: auto;
        }

        .entry-content[ast-blocks-layout]>.alignwide {
            max-width: var(--wp--custom--ast-wide-width-size);
        }

        .entry-content[ast-blocks-layout] .alignfull {
            max-width: none;
        }

        .entry-content .wp-block-columns {
            margin-bottom: 0;
        }

        blockquote {
            margin: 1.5em;
            border-color: rgba(0, 0, 0, 0.05);
        }

        .wp-block-quote:not(.has-text-align-right):not(.has-text-align-center) {
            border-left: 5px solid rgba(0, 0, 0, 0.05);
        }

        .has-text-align-right>blockquote,
        blockquote.has-text-align-right {
            border-right: 5px solid rgba(0, 0, 0, 0.05);
        }

        .has-text-align-left>blockquote,
        blockquote.has-text-align-left {
            border-left: 5px solid rgba(0, 0, 0, 0.05);
        }

        .wp-block-site-tagline,
        .wp-block-latest-posts .read-more {
            margin-top: 15px;
        }

        .wp-block-loginout p label {
            display: block;
        }

        .wp-block-loginout p:not(.login-remember):not(.login-submit) input {
            width: 100%;
        }

        .wp-block-loginout input:focus {
            border-color: transparent;
        }

        .wp-block-loginout input:focus {
            outline: thin dotted;
        }

        .entry-content .wp-block-media-text .wp-block-media-text__content {
            padding: 0 0 0 8%;
        }

        .entry-content .wp-block-media-text.has-media-on-the-right .wp-block-media-text__content {
            padding: 0 8% 0 0;
        }

        .entry-content .wp-block-media-text.has-background .wp-block-media-text__content {
            padding: 8%;
        }

        .entry-content .wp-block-cover:not([class*="background-color"]) .wp-block-cover__inner-container,
        .entry-content .wp-block-cover:not([class*="background-color"]) .wp-block-cover-image-text,
        .entry-content .wp-block-cover:not([class*="background-color"]) .wp-block-cover-text,
        .entry-content .wp-block-cover-image:not([class*="background-color"]) .wp-block-cover__inner-container,
        .entry-content .wp-block-cover-image:not([class*="background-color"]) .wp-block-cover-image-text,
        .entry-content .wp-block-cover-image:not([class*="background-color"]) .wp-block-cover-text {
            color: var(--ast-global-color-5);
        }

        .wp-block-loginout .login-remember input {
            width: 1.1rem;
            height: 1.1rem;
            margin: 0 5px 4px 0;
            vertical-align: middle;
        }

        .wp-block-latest-posts>li>*:first-child,
        .wp-block-latest-posts:not(.is-grid)>li:first-child {
            margin-top: 0;
        }

        .wp-block-search__inside-wrapper .wp-block-search__input {
            padding: 0 10px;
            color: var(--ast-global-color-3);
            background: var(--ast-global-color-5);
            border-color: var(--ast-border-color);
        }

        .wp-block-latest-posts .read-more {
            margin-bottom: 1.5em;
        }

        .wp-block-search__no-button .wp-block-search__inside-wrapper .wp-block-search__input {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .wp-block-latest-posts .wp-block-latest-posts__post-date,
        .wp-block-latest-posts .wp-block-latest-posts__post-author {
            font-size: 1rem;
        }

        .wp-block-latest-posts>li>*,
        .wp-block-latest-posts:not(.is-grid)>li {
            margin-top: 12px;
            margin-bottom: 12px;
        }

        .ast-page-builder-template .entry-content[ast-blocks-layout]>*,
        .ast-page-builder-template .entry-content[ast-blocks-layout]>.alignfull>* {
            max-width: none;
        }

        .ast-page-builder-template .entry-content[ast-blocks-layout]>.alignwide>* {
            max-width: var(--wp--custom--ast-wide-width-size);
        }

        .ast-page-builder-template .entry-content[ast-blocks-layout]>.inherit-container-width>*,
        .ast-page-builder-template .entry-content[ast-blocks-layout]>*>*,
        .entry-content[ast-blocks-layout]>.wp-block-cover .wp-block-cover__inner-container {
            max-width: var(--wp--custom--ast-content-width-size);
            margin-left: auto;
            margin-right: auto;
        }

        .entry-content[ast-blocks-layout] .wp-block-cover:not(.alignleft):not(.alignright) {
            width: auto;
        }

        @media(max-width: 1200px) {

            .ast-separate-container .entry-content>.alignfull,
            .ast-separate-container .entry-content[ast-blocks-layout]>.alignwide,
            .ast-plain-container .entry-content[ast-blocks-layout]>.alignwide,
            .ast-plain-container .entry-content .alignfull {
                margin-left: calc(-1 * min(var(--ast-container-default-xlg-padding), 20px));
                margin-right: calc(-1 * min(var(--ast-container-default-xlg-padding), 20px));
            }
        }

        @media(min-width: 1201px) {
            .ast-separate-container .entry-content>.alignfull {
                margin-left: calc(-1 * var(--ast-container-default-xlg-padding));
                margin-right: calc(-1 * var(--ast-container-default-xlg-padding));
            }

            .ast-separate-container .entry-content[ast-blocks-layout]>.alignwide,
            .ast-plain-container .entry-content[ast-blocks-layout]>.alignwide {
                margin-left: calc(-1 * var(--wp--custom--ast-default-block-left-padding));
                margin-right: calc(-1 * var(--wp--custom--ast-default-block-right-padding));
            }
        }

        @media(min-width: 921px) {

            .ast-separate-container .entry-content .wp-block-group.alignwide:not(.inherit-container-width)> :where(:not(.alignleft):not(.alignright)),
            .ast-plain-container .entry-content .wp-block-group.alignwide:not(.inherit-container-width)> :where(:not(.alignleft):not(.alignright)) {
                max-width: calc(var(--wp--custom--ast-content-width-size) + 80px);
            }

            .ast-plain-container.ast-right-sidebar .entry-content[ast-blocks-layout] .alignfull,
            .ast-plain-container.ast-left-sidebar .entry-content[ast-blocks-layout] .alignfull {
                margin-left: -60px;
                margin-right: -60px;
            }
        }

        @media(min-width: 544px) {
            .entry-content>.alignleft {
                margin-right: 20px;
            }

            .entry-content>.alignright {
                margin-left: 20px;
            }
        }

        @media (max-width:544px) {
            .wp-block-columns .wp-block-column:not(:last-child) {
                margin-bottom: 20px;
            }

            .wp-block-latest-posts {
                margin: 0;
            }
        }

        @media(max-width: 600px) {

            .entry-content .wp-block-media-text .wp-block-media-text__content,
            .entry-content .wp-block-media-text.has-media-on-the-right .wp-block-media-text__content {
                padding: 8% 0 0;
            }

            .entry-content .wp-block-media-text.has-background .wp-block-media-text__content {
                padding: 8%;
            }
        }

        .ast-page-builder-template .entry-header {
            padding-left: 0;
        }

        .ast-narrow-container .site-content .wp-block-uagb-image--align-full .wp-block-uagb-image__figure {
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
        }

        :root .has-ast-global-color-0-color {
            color: var(--ast-global-color-0);
        }

        :root .has-ast-global-color-0-background-color {
            background-color: var(--ast-global-color-0);
        }

        :root .wp-block-button .has-ast-global-color-0-color {
            color: var(--ast-global-color-0);
        }

        :root .wp-block-button .has-ast-global-color-0-background-color {
            background-color: var(--ast-global-color-0);
        }

        :root .has-ast-global-color-1-color {
            color: var(--ast-global-color-1);
        }

        :root .has-ast-global-color-1-background-color {
            background-color: var(--ast-global-color-1);
        }

        :root .wp-block-button .has-ast-global-color-1-color {
            color: var(--ast-global-color-1);
        }

        :root .wp-block-button .has-ast-global-color-1-background-color {
            background-color: var(--ast-global-color-1);
        }

        :root .has-ast-global-color-2-color {
            color: var(--ast-global-color-2);
        }

        :root .has-ast-global-color-2-background-color {
            background-color: var(--ast-global-color-2);
        }

        :root .wp-block-button .has-ast-global-color-2-color {
            color: var(--ast-global-color-2);
        }

        :root .wp-block-button .has-ast-global-color-2-background-color {
            background-color: var(--ast-global-color-2);
        }

        :root .has-ast-global-color-3-color {
            color: var(--ast-global-color-3);
        }

        :root .has-ast-global-color-3-background-color {
            background-color: var(--ast-global-color-3);
        }

        :root .wp-block-button .has-ast-global-color-3-color {
            color: var(--ast-global-color-3);
        }

        :root .wp-block-button .has-ast-global-color-3-background-color {
            background-color: var(--ast-global-color-3);
        }

        :root .has-ast-global-color-4-color {
            color: var(--ast-global-color-4);
        }

        :root .has-ast-global-color-4-background-color {
            background-color: var(--ast-global-color-4);
        }

        :root .wp-block-button .has-ast-global-color-4-color {
            color: var(--ast-global-color-4);
        }

        :root .wp-block-button .has-ast-global-color-4-background-color {
            background-color: var(--ast-global-color-4);
        }

        :root .has-ast-global-color-5-color {
            color: var(--ast-global-color-5);
        }

        :root .has-ast-global-color-5-background-color {
            background-color: var(--ast-global-color-5);
        }

        :root .wp-block-button .has-ast-global-color-5-color {
            color: var(--ast-global-color-5);
        }

        :root .wp-block-button .has-ast-global-color-5-background-color {
            background-color: var(--ast-global-color-5);
        }

        :root .has-ast-global-color-6-color {
            color: var(--ast-global-color-6);
        }

        :root .has-ast-global-color-6-background-color {
            background-color: var(--ast-global-color-6);
        }

        :root .wp-block-button .has-ast-global-color-6-color {
            color: var(--ast-global-color-6);
        }

        :root .wp-block-button .has-ast-global-color-6-background-color {
            background-color: var(--ast-global-color-6);
        }

        :root .has-ast-global-color-7-color {
            color: var(--ast-global-color-7);
        }

        :root .has-ast-global-color-7-background-color {
            background-color: var(--ast-global-color-7);
        }

        :root .wp-block-button .has-ast-global-color-7-color {
            color: var(--ast-global-color-7);
        }

        :root .wp-block-button .has-ast-global-color-7-background-color {
            background-color: var(--ast-global-color-7);
        }

        :root .has-ast-global-color-8-color {
            color: var(--ast-global-color-8);
        }

        :root .has-ast-global-color-8-background-color {
            background-color: var(--ast-global-color-8);
        }

        :root .wp-block-button .has-ast-global-color-8-color {
            color: var(--ast-global-color-8);
        }

        :root .wp-block-button .has-ast-global-color-8-background-color {
            background-color: var(--ast-global-color-8);
        }

        :root {
            --ast-global-color-0: #ffc03d;
            --ast-global-color-1: #f8b526;
            --ast-global-color-2: #212d45;
            --ast-global-color-3: #4b4f58;
            --ast-global-color-4: #F5F5F5;
            --ast-global-color-5: #FFFFFF;
            --ast-global-color-6: #F2F5F7;
            --ast-global-color-7: #212d45;
            --ast-global-color-8: #000000;
        }

        :root {
            --ast-border-color: #dddddd;
        }

        .ast-single-entry-banner {
            -js-display: flex;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            position: relative;
            background: #eeeeee;
        }

        .ast-single-entry-banner[data-banner-layout="layout-1"] {
            max-width: 1200px;
            background: inherit;
            padding: 20px 0;
        }

        .ast-single-entry-banner[data-banner-width-type="custom"] {
            margin: 0 auto;
            width: 100%;
        }

        .ast-single-entry-banner+.site-content .entry-header {
            margin-bottom: 0;
        }

        .site .ast-author-avatar {
            --ast-author-avatar-size: ;
        }

        a.ast-underline-text {
            text-decoration: underline;
        }

        .ast-container>.ast-terms-link {
            position: relative;
            display: block;
        }

        a.ast-button.ast-badge-tax {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: inherit;
        }

        header.entry-header>*:not(:last-child) {
            margin-bottom: 10px;
        }

        .ast-archive-entry-banner {
            -js-display: flex;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            position: relative;
            background: #eeeeee;
        }

        .ast-archive-entry-banner[data-banner-width-type="custom"] {
            margin: 0 auto;
            width: 100%;
        }

        .ast-archive-entry-banner[data-banner-layout="layout-1"] {
            background: inherit;
            padding: 20px 0;
            text-align: left;
        }

        body.archive .ast-archive-description {
            max-width: 1200px;
            width: 100%;
            text-align: left;
            padding-top: 3em;
            padding-right: 3em;
            padding-bottom: 3em;
            padding-left: 3em;
        }

        body.archive .ast-archive-description .ast-archive-title,
        body.archive .ast-archive-description .ast-archive-title * {
            font-size: 40px;
            font-size: 2.6666666666667rem;
        }

        body.archive .ast-archive-description>*:not(:last-child) {
            margin-bottom: 10px;
        }

        @media (max-width:921px) {
            body.archive .ast-archive-description {
                text-align: left;
            }
        }

        @media (max-width:544px) {
            body.archive .ast-archive-description {
                text-align: left;
            }
        }

        .ast-breadcrumbs .trail-browse,
        .ast-breadcrumbs .trail-items,
        .ast-breadcrumbs .trail-items li {
            display: inline-block;
            margin: 0;
            padding: 0;
            border: none;
            background: inherit;
            text-indent: 0;
            text-decoration: none;
        }

        .ast-breadcrumbs .trail-browse {
            font-size: inherit;
            font-style: inherit;
            font-weight: inherit;
            color: inherit;
        }

        .ast-breadcrumbs .trail-items {
            list-style: none;
        }

        .trail-items li::after {
            padding: 0 0.3em;
            content: "\00bb";
        }

        .trail-items li:last-of-type::after {
            display: none;
        }

        h1,
        .entry-content h1,
        h2,
        .entry-content h2,
        h3,
        .entry-content h3,
        h4,
        .entry-content h4,
        h5,
        .entry-content h5,
        h6,
        .entry-content h6 {
            color: var(--ast-global-color-2);
        }

        .entry-title a {
            color: var(--ast-global-color-2);
        }

        @media (max-width:921px) {

            .ast-builder-grid-row-container.ast-builder-grid-row-tablet-3-firstrow .ast-builder-grid-row>*:first-child,
            .ast-builder-grid-row-container.ast-builder-grid-row-tablet-3-lastrow .ast-builder-grid-row>*:last-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width:544px) {

            .ast-builder-grid-row-container.ast-builder-grid-row-mobile-3-firstrow .ast-builder-grid-row>*:first-child,
            .ast-builder-grid-row-container.ast-builder-grid-row-mobile-3-lastrow .ast-builder-grid-row>*:last-child {
                grid-column: 1 / -1;
            }
        }

        .ast-builder-layout-element[data-section="title_tagline"] {
            display: flex;
        }

        @media (max-width:921px) {
            .ast-header-break-point .ast-builder-layout-element[data-section="title_tagline"] {
                display: flex;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .ast-builder-layout-element[data-section="title_tagline"] {
                display: flex;
            }
        }

        [data-section*="section-hb-button-"] .menu-link {
            display: none;
        }

        .ast-header-button-1[data-section="section-hb-button-1"] {
            display: flex;
        }

        @media (max-width:921px) {
            .ast-header-break-point .ast-header-button-1[data-section="section-hb-button-1"] {
                display: flex;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .ast-header-button-1[data-section="section-hb-button-1"] {
                display: flex;
            }
        }

        .ast-builder-menu-1 {
            font-family: inherit;
            font-weight: 500;
        }

        .ast-builder-menu-1 .sub-menu,
        .ast-builder-menu-1 .inline-on-mobile .sub-menu {
            border-top-width: 0;
            border-bottom-width: 0;
            border-right-width: 0;
            border-left-width: 0;
            border-color: var(--ast-global-color-0);
            border-style: solid;
        }

        .ast-builder-menu-1 .main-header-menu>.menu-item>.sub-menu,
        .ast-builder-menu-1 .main-header-menu>.menu-item>.astra-full-megamenu-wrapper {
            margin-top: 0;
        }

        .ast-desktop .ast-builder-menu-1 .main-header-menu>.menu-item>.sub-menu:before,
        .ast-desktop .ast-builder-menu-1 .main-header-menu>.menu-item>.astra-full-megamenu-wrapper:before {
            height: calc(0px + 5px);
        }

        .ast-desktop .ast-builder-menu-1 .menu-item .sub-menu .menu-link {
            border-style: none;
        }

        @media (max-width:921px) {
            .ast-header-break-point .ast-builder-menu-1 .menu-item.menu-item-has-children>.ast-menu-toggle {
                top: 0;
            }

            .ast-builder-menu-1 .inline-on-mobile .menu-item.menu-item-has-children>.ast-menu-toggle {
                right: -15px;
            }

            .ast-builder-menu-1 .menu-item-has-children>.menu-link:after {
                content: unset;
            }

            .ast-builder-menu-1 .main-header-menu>.menu-item>.sub-menu,
            .ast-builder-menu-1 .main-header-menu>.menu-item>.astra-full-megamenu-wrapper {
                margin-top: 0;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .ast-builder-menu-1 .menu-item.menu-item-has-children>.ast-menu-toggle {
                top: 0;
            }

            .ast-builder-menu-1 .main-header-menu>.menu-item>.sub-menu,
            .ast-builder-menu-1 .main-header-menu>.menu-item>.astra-full-megamenu-wrapper {
                margin-top: 0;
            }
        }

        .ast-builder-menu-1 {
            display: flex;
        }

        @media (max-width:921px) {
            .ast-header-break-point .ast-builder-menu-1 {
                display: flex;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .ast-builder-menu-1 {
                display: flex;
            }
        }

        .ast-builder-html-element img.alignnone {
            display: inline-block;
        }

        .ast-builder-html-element p:first-child {
            margin-top: 0;
        }

        .ast-builder-html-element p:last-child {
            margin-bottom: 0;
        }

        .ast-header-break-point .main-header-bar .ast-builder-html-element {
            line-height: 1.85714285714286;
        }

        .ast-header-html-1 .ast-builder-html-element {
            color: var(--ast-global-color-0);
            font-size: 15px;
            font-size: 1rem;
        }

        .ast-header-html-1 {
            font-size: 15px;
            font-size: 1rem;
        }

        .ast-header-html-1 {
            display: flex;
        }

        @media (max-width:921px) {
            .ast-header-break-point .ast-header-html-1 {
                display: flex;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .ast-header-html-1 {
                display: flex;
            }
        }

        .ast-social-stack-desktop .ast-builder-social-element,
        .ast-social-stack-tablet .ast-builder-social-element,
        .ast-social-stack-mobile .ast-builder-social-element {
            margin-top: 6px;
            margin-bottom: 6px;
        }

        .social-show-label-true .ast-builder-social-element {
            width: auto;
            padding: 0 0.4em;
        }

        [data-section^="section-fb-social-icons-"] .footer-social-inner-wrap {
            text-align: center;
        }

        .ast-footer-social-wrap {
            width: 100%;
        }

        .ast-footer-social-wrap .ast-builder-social-element:first-child {
            margin-left: 0;
        }

        .ast-footer-social-wrap .ast-builder-social-element:last-child {
            margin-right: 0;
        }

        .ast-header-social-wrap .ast-builder-social-element:first-child {
            margin-left: 0;
        }

        .ast-header-social-wrap .ast-builder-social-element:last-child {
            margin-right: 0;
        }

        .ast-builder-social-element {
            line-height: 1;
            color: #3a3a3a;
            background: transparent;
            vertical-align: middle;
            transition: all 0.01s;
            margin-left: 6px;
            margin-right: 6px;
            justify-content: center;
            align-items: center;
        }

        .ast-builder-social-element {
            line-height: 1;
            color: #3a3a3a;
            background: transparent;
            vertical-align: middle;
            transition: all 0.01s;
            margin-left: 6px;
            margin-right: 6px;
            justify-content: center;
            align-items: center;
        }

        .ast-builder-social-element .social-item-label {
            padding-left: 6px;
        }

        .ast-header-social-1-wrap .ast-builder-social-element,
        .ast-header-social-1-wrap .social-show-label-true .ast-builder-social-element {
            margin-left: 9.5px;
            margin-right: 9.5px;
        }

        .ast-header-social-1-wrap .ast-builder-social-element svg {
            width: 25px;
            height: 25px;
        }

        .ast-header-social-1-wrap .ast-social-color-type-custom svg {
            fill: var(--ast-global-color-0);
        }

        .ast-header-social-1-wrap .ast-social-color-type-custom .ast-builder-social-element:hover {
            color: var(--ast-global-color-5);
        }

        .ast-header-social-1-wrap .ast-social-color-type-custom .ast-builder-social-element:hover svg {
            fill: var(--ast-global-color-5);
        }

        .ast-header-social-1-wrap .ast-social-color-type-custom .social-item-label {
            color: var(--ast-global-color-0);
        }

        .ast-header-social-1-wrap .ast-builder-social-element:hover .social-item-label {
            color: var(--ast-global-color-5);
        }

        .ast-builder-layout-element[data-section="section-hb-social-icons-1"] {
            display: flex;
        }

        @media (max-width:921px) {
            .ast-header-break-point .ast-builder-layout-element[data-section="section-hb-social-icons-1"] {
                display: flex;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .ast-builder-layout-element[data-section="section-hb-social-icons-1"] {
                display: flex;
            }
        }

        .site-below-footer-wrap {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .site-below-footer-wrap[data-section="section-below-footer-builder"] {
            background-color: ;
            ;
            background-image: none;
            ;
            min-height: 80px;
            border-style: solid;
            border-width: 0px;
            border-top-width: 1px;
            border-top-color: rgba(195, 193, 193, 0.27);
        }

        .site-below-footer-wrap[data-section="section-below-footer-builder"] .ast-builder-grid-row {
            max-width: 1200px;
            min-height: 80px;
            margin-left: auto;
            margin-right: auto;
        }

        .site-below-footer-wrap[data-section="section-below-footer-builder"] .ast-builder-grid-row,
        .site-below-footer-wrap[data-section="section-below-footer-builder"] .site-footer-section {
            align-items: center;
        }

        .site-below-footer-wrap[data-section="section-below-footer-builder"].ast-footer-row-inline .site-footer-section {
            display: flex;
            margin-bottom: 0;
        }

        .ast-builder-grid-row-2-equal .ast-builder-grid-row {
            grid-template-columns: repeat(2, 1fr);
        }

        @media (max-width:921px) {
            .site-below-footer-wrap[data-section="section-below-footer-builder"].ast-footer-row-tablet-inline .site-footer-section {
                display: flex;
                margin-bottom: 0;
            }

            .site-below-footer-wrap[data-section="section-below-footer-builder"].ast-footer-row-tablet-stack .site-footer-section {
                display: block;
                margin-bottom: 10px;
            }

            .ast-builder-grid-row-container.ast-builder-grid-row-tablet-2-equal .ast-builder-grid-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width:544px) {
            .site-below-footer-wrap[data-section="section-below-footer-builder"].ast-footer-row-mobile-inline .site-footer-section {
                display: flex;
                margin-bottom: 0;
            }

            .site-below-footer-wrap[data-section="section-below-footer-builder"].ast-footer-row-mobile-stack .site-footer-section {
                display: block;
                margin-bottom: 10px;
            }

            .ast-builder-grid-row-container.ast-builder-grid-row-mobile-full .ast-builder-grid-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width:921px) {
            .site-below-footer-wrap[data-section="section-below-footer-builder"] {
                padding-left: 40px;
                padding-right: 40px;
            }
        }

        @media (max-width:544px) {
            .site-below-footer-wrap[data-section="section-below-footer-builder"] {
                padding-top: 0px;
                padding-bottom: 0px;
                padding-left: 25px;
                padding-right: 25px;
            }
        }

        .site-below-footer-wrap[data-section="section-below-footer-builder"] {
            display: grid;
        }

        @media (max-width:921px) {
            .ast-header-break-point .site-below-footer-wrap[data-section="section-below-footer-builder"] {
                display: grid;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .site-below-footer-wrap[data-section="section-below-footer-builder"] {
                display: grid;
            }
        }

        .ast-footer-copyright {
            text-align: left;
        }

        .ast-footer-copyright {
            color: #ffffff;
        }

        @media (max-width:921px) {
            .ast-footer-copyright {
                text-align: center;
            }
        }

        @media (max-width:544px) {
            .ast-footer-copyright {
                text-align: center;
            }
        }

        .ast-footer-copyright.ast-builder-layout-element {
            display: flex;
        }

        @media (max-width:921px) {
            .ast-header-break-point .ast-footer-copyright.ast-builder-layout-element {
                display: flex;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .ast-footer-copyright.ast-builder-layout-element {
                display: flex;
            }
        }

        .ast-social-stack-desktop .ast-builder-social-element,
        .ast-social-stack-tablet .ast-builder-social-element,
        .ast-social-stack-mobile .ast-builder-social-element {
            margin-top: 6px;
            margin-bottom: 6px;
        }

        .social-show-label-true .ast-builder-social-element {
            width: auto;
            padding: 0 0.4em;
        }

        [data-section^="section-fb-social-icons-"] .footer-social-inner-wrap {
            text-align: center;
        }

        .ast-footer-social-wrap {
            width: 100%;
        }

        .ast-footer-social-wrap .ast-builder-social-element:first-child {
            margin-left: 0;
        }

        .ast-footer-social-wrap .ast-builder-social-element:last-child {
            margin-right: 0;
        }

        .ast-header-social-wrap .ast-builder-social-element:first-child {
            margin-left: 0;
        }

        .ast-header-social-wrap .ast-builder-social-element:last-child {
            margin-right: 0;
        }

        .ast-builder-social-element {
            line-height: 1;
            color: #3a3a3a;
            background: transparent;
            vertical-align: middle;
            transition: all 0.01s;
            margin-left: 6px;
            margin-right: 6px;
            justify-content: center;
            align-items: center;
        }

        .ast-builder-social-element {
            line-height: 1;
            color: #3a3a3a;
            background: transparent;
            vertical-align: middle;
            transition: all 0.01s;
            margin-left: 6px;
            margin-right: 6px;
            justify-content: center;
            align-items: center;
        }

        .ast-builder-social-element .social-item-label {
            padding-left: 6px;
        }

        .ast-footer-social-1-wrap .ast-builder-social-element,
        .ast-footer-social-1-wrap .social-show-label-true .ast-builder-social-element {
            margin-left: 10.5px;
            margin-right: 10.5px;
        }

        .ast-footer-social-1-wrap .ast-builder-social-element svg {
            width: 22px;
            height: 22px;
        }

        .ast-footer-social-1-wrap .ast-social-color-type-custom svg {
            fill: var(--ast-global-color-0);
        }

        .ast-footer-social-1-wrap .ast-social-color-type-custom .ast-builder-social-element:hover {
            color: var(--ast-global-color-5);
        }

        .ast-footer-social-1-wrap .ast-social-color-type-custom .ast-builder-social-element:hover svg {
            fill: var(--ast-global-color-5);
        }

        .ast-footer-social-1-wrap .ast-social-color-type-custom .social-item-label {
            color: var(--ast-global-color-0);
        }

        .ast-footer-social-1-wrap .ast-builder-social-element:hover .social-item-label {
            color: var(--ast-global-color-5);
        }

        [data-section="section-fb-social-icons-1"] .footer-social-inner-wrap {
            text-align: right;
        }

        @media (max-width:921px) {
            [data-section="section-fb-social-icons-1"] .footer-social-inner-wrap {
                text-align: center;
            }
        }

        @media (max-width:544px) {
            [data-section="section-fb-social-icons-1"] .footer-social-inner-wrap {
                text-align: center;
            }
        }

        .ast-builder-layout-element[data-section="section-fb-social-icons-1"] {
            display: flex;
        }

        @media (max-width:921px) {
            .ast-header-break-point .ast-builder-layout-element[data-section="section-fb-social-icons-1"] {
                display: flex;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .ast-builder-layout-element[data-section="section-fb-social-icons-1"] {
                display: flex;
            }
        }

        .site-footer {
            background-color: var(--ast-global-color-2);
            ;
            background-image: none;
            ;
        }

        .site-primary-footer-wrap {
            padding-top: 45px;
            padding-bottom: 45px;
        }

        .site-primary-footer-wrap[data-section="section-primary-footer-builder"] {
            background-color: ;
            ;
            background-image: none;
            ;
        }

        .site-primary-footer-wrap[data-section="section-primary-footer-builder"] .ast-builder-grid-row {
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .site-primary-footer-wrap[data-section="section-primary-footer-builder"] .ast-builder-grid-row,
        .site-primary-footer-wrap[data-section="section-primary-footer-builder"] .site-footer-section {
            align-items: flex-start;
        }

        .site-primary-footer-wrap[data-section="section-primary-footer-builder"].ast-footer-row-inline .site-footer-section {
            display: flex;
            margin-bottom: 0;
        }

        .ast-builder-grid-row-4-equal .ast-builder-grid-row {
            grid-template-columns: repeat(4, 1fr);
        }

        @media (max-width:921px) {
            .site-primary-footer-wrap[data-section="section-primary-footer-builder"].ast-footer-row-tablet-inline .site-footer-section {
                display: flex;
                margin-bottom: 0;
            }

            .site-primary-footer-wrap[data-section="section-primary-footer-builder"].ast-footer-row-tablet-stack .site-footer-section {
                display: block;
                margin-bottom: 10px;
            }

            .ast-builder-grid-row-container.ast-builder-grid-row-tablet-2-equal .ast-builder-grid-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width:544px) {
            .site-primary-footer-wrap[data-section="section-primary-footer-builder"].ast-footer-row-mobile-inline .site-footer-section {
                display: flex;
                margin-bottom: 0;
            }

            .site-primary-footer-wrap[data-section="section-primary-footer-builder"].ast-footer-row-mobile-stack .site-footer-section {
                display: block;
                margin-bottom: 10px;
            }

            .ast-builder-grid-row-container.ast-builder-grid-row-mobile-full .ast-builder-grid-row {
                grid-template-columns: 1fr;
            }
        }

        .site-primary-footer-wrap[data-section="section-primary-footer-builder"] {
            padding-top: 100px;
        }

        @media (max-width:921px) {
            .site-primary-footer-wrap[data-section="section-primary-footer-builder"] {
                padding-top: 80px;
                padding-bottom: 80px;
                padding-left: 40px;
                padding-right: 40px;
            }
        }

        @media (max-width:544px) {
            .site-primary-footer-wrap[data-section="section-primary-footer-builder"] {
                padding-top: 50px;
                padding-bottom: 50px;
                padding-left: 25px;
                padding-right: 25px;
            }
        }

        .site-primary-footer-wrap[data-section="section-primary-footer-builder"] {
            display: grid;
        }

        @media (max-width:921px) {
            .ast-header-break-point .site-primary-footer-wrap[data-section="section-primary-footer-builder"] {
                display: grid;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .site-primary-footer-wrap[data-section="section-primary-footer-builder"] {
                display: grid;
            }
        }

        .footer-widget-area[data-section="sidebar-widgets-footer-widget-1"].footer-widget-area-inner {
            text-align: left;
        }

        @media (max-width:921px) {
            .footer-widget-area[data-section="sidebar-widgets-footer-widget-1"].footer-widget-area-inner {
                text-align: center;
            }
        }

        @media (max-width:544px) {
            .footer-widget-area[data-section="sidebar-widgets-footer-widget-1"].footer-widget-area-inner {
                text-align: center;
            }
        }

        .footer-widget-area[data-section="sidebar-widgets-footer-widget-2"].footer-widget-area-inner {
            text-align: left;
        }

        @media (max-width:921px) {
            .footer-widget-area[data-section="sidebar-widgets-footer-widget-2"].footer-widget-area-inner {
                text-align: center;
            }
        }

        @media (max-width:544px) {
            .footer-widget-area[data-section="sidebar-widgets-footer-widget-2"].footer-widget-area-inner {
                text-align: center;
            }
        }

        .footer-widget-area[data-section="sidebar-widgets-footer-widget-3"].footer-widget-area-inner {
            text-align: left;
        }

        @media (max-width:921px) {
            .footer-widget-area[data-section="sidebar-widgets-footer-widget-3"].footer-widget-area-inner {
                text-align: center;
            }
        }

        @media (max-width:544px) {
            .footer-widget-area[data-section="sidebar-widgets-footer-widget-3"].footer-widget-area-inner {
                text-align: center;
            }
        }

        .footer-widget-area[data-section="sidebar-widgets-footer-widget-4"].footer-widget-area-inner {
            text-align: left;
        }

        @media (max-width:921px) {
            .footer-widget-area[data-section="sidebar-widgets-footer-widget-4"].footer-widget-area-inner {
                text-align: center;
            }
        }

        @media (max-width:544px) {
            .footer-widget-area[data-section="sidebar-widgets-footer-widget-4"].footer-widget-area-inner {
                text-align: center;
            }
        }

        .footer-widget-area.widget-area.site-footer-focus-item {
            width: auto;
        }

        .footer-widget-area[data-section="sidebar-widgets-footer-widget-1"] {
            display: block;
        }

        @media (max-width:921px) {
            .ast-header-break-point .footer-widget-area[data-section="sidebar-widgets-footer-widget-1"] {
                display: block;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .footer-widget-area[data-section="sidebar-widgets-footer-widget-1"] {
                display: block;
            }
        }

        .footer-widget-area[data-section="sidebar-widgets-footer-widget-2"] {
            display: block;
        }

        @media (max-width:921px) {
            .ast-header-break-point .footer-widget-area[data-section="sidebar-widgets-footer-widget-2"] {
                display: block;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .footer-widget-area[data-section="sidebar-widgets-footer-widget-2"] {
                display: block;
            }
        }

        .footer-widget-area[data-section="sidebar-widgets-footer-widget-3"] {
            display: block;
        }

        @media (max-width:921px) {
            .ast-header-break-point .footer-widget-area[data-section="sidebar-widgets-footer-widget-3"] {
                display: block;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .footer-widget-area[data-section="sidebar-widgets-footer-widget-3"] {
                display: block;
            }
        }

        .footer-widget-area[data-section="sidebar-widgets-footer-widget-4"] {
            display: block;
        }

        @media (max-width:921px) {
            .ast-header-break-point .footer-widget-area[data-section="sidebar-widgets-footer-widget-4"] {
                display: block;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .footer-widget-area[data-section="sidebar-widgets-footer-widget-4"] {
                display: block;
            }
        }

        .elementor-widget-heading .elementor-heading-title {
            margin: 0;
        }

        .elementor-page .ast-menu-toggle {
            color: unset !important;
            background: unset !important;
        }

        .elementor-post.elementor-grid-item.hentry {
            margin-bottom: 0;
        }

        .woocommerce div.product .elementor-element.elementor-products-grid .related.products ul.products li.product,
        .elementor-element .elementor-wc-products .woocommerce[class*='columns-'] ul.products li.product {
            width: auto;
            margin: 0;
            float: none;
        }

        .elementor-toc__list-wrapper {
            margin: 0;
        }

        body .elementor hr {
            background-color: #ccc;
            margin: 0;
        }

        .ast-left-sidebar .elementor-section.elementor-section-stretched,
        .ast-right-sidebar .elementor-section.elementor-section-stretched {
            max-width: 100%;
            left: 0 !important;
        }

        .elementor-posts-container [CLASS*="ast-width-"] {
            width: 100%;
        }

        .elementor-template-full-width .ast-container {
            display: block;
        }

        .elementor-screen-only,
        .screen-reader-text,
        .screen-reader-text span,
        .ui-helper-hidden-accessible {
            top: 0 !important;
        }

        @media (max-width:544px) {
            .elementor-element .elementor-wc-products .woocommerce[class*="columns-"] ul.products li.product {
                width: auto;
                margin: 0;
            }

            .elementor-element .woocommerce .woocommerce-result-count {
                float: none;
            }
        }

        .ast-header-button-1 .ast-custom-button {
            box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.1);
        }

        .ast-desktop .ast-mega-menu-enabled .ast-builder-menu-1 div:not(.astra-full-megamenu-wrapper) .sub-menu,
        .ast-builder-menu-1 .inline-on-mobile .sub-menu,
        .ast-desktop .ast-builder-menu-1 .astra-full-megamenu-wrapper,
        .ast-desktop .ast-builder-menu-1 .menu-item .sub-menu {
            box-shadow: 0px 4px 10px -2px rgba(0, 0, 0, 0.1);
        }

        .ast-desktop .ast-mobile-popup-drawer.active .ast-mobile-popup-inner {
            max-width: 35%;
        }

        @media (max-width:921px) {
            #ast-mobile-popup-wrapper .ast-mobile-popup-drawer .ast-mobile-popup-inner {
                width: 90%;
            }

            .ast-mobile-popup-drawer.active .ast-mobile-popup-inner {
                max-width: 90%;
            }
        }

        @media (max-width:544px) {
            #ast-mobile-popup-wrapper .ast-mobile-popup-drawer .ast-mobile-popup-inner {
                width: 90%;
            }

            .ast-mobile-popup-drawer.active .ast-mobile-popup-inner {
                max-width: 90%;
            }
        }

        .ast-header-break-point .main-header-bar {
            border-bottom-width: 1px;
        }

        @media (min-width:922px) {
            .main-header-bar {
                border-bottom-width: 1px;
            }
        }

        .main-header-menu .menu-item,
        #astra-footer-menu .menu-item,
        .main-header-bar .ast-masthead-custom-menu-items {
            -js-display: flex;
            display: flex;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -moz-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -moz-box-orient: vertical;
            -moz-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .main-header-menu>.menu-item>.menu-link,
        #astra-footer-menu>.menu-item>.menu-link {
            height: 100%;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -moz-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -js-display: flex;
            display: flex;
        }

        .ast-header-break-point .main-navigation ul .menu-item .menu-link .icon-arrow:first-of-type svg {
            top: .2em;
            margin-top: 0px;
            margin-left: 0px;
            width: .65em;
            transform: translate(0, -2px) rotateZ(270deg);
        }

        .ast-mobile-popup-content .ast-submenu-expanded>.ast-menu-toggle {
            transform: rotateX(180deg);
            overflow-y: auto;
        }

        @media (min-width:922px) {
            .ast-builder-menu .main-navigation>ul>li:last-child a {
                margin-right: 0;
            }
        }

        .ast-separate-container .ast-article-inner {
            background-color: transparent;
            background-image: none;
        }

        .ast-separate-container .ast-article-post {
            background-color: var(--ast-global-color-5);
            ;
            background-image: none;
            ;
        }

        @media (max-width:921px) {
            .ast-separate-container .ast-article-post {
                background-color: var(--ast-global-color-5);
                ;
                background-image: none;
                ;
            }
        }

        @media (max-width:544px) {
            .ast-separate-container .ast-article-post {
                background-color: var(--ast-global-color-5);
                ;
                background-image: none;
                ;
            }
        }

        .ast-separate-container .ast-article-single:not(.ast-related-post),
        .woocommerce.ast-separate-container .ast-woocommerce-container,
        .ast-separate-container .error-404,
        .ast-separate-container .no-results,
        .single.ast-separate-container.ast-author-meta,
        .ast-separate-container .related-posts-title-wrapper,
        .ast-separate-container .comments-count-wrapper,
        .ast-box-layout.ast-plain-container .site-content,
        .ast-padded-layout.ast-plain-container .site-content,
        .ast-separate-container .ast-archive-description,
        .ast-separate-container .comments-area .comment-respond,
        .ast-separate-container .comments-area .ast-comment-list li,
        .ast-separate-container .comments-area .comments-title {
            background-color: var(--ast-global-color-5);
            ;
            background-image: none;
            ;
        }

        @media (max-width:921px) {

            .ast-separate-container .ast-article-single:not(.ast-related-post),
            .woocommerce.ast-separate-container .ast-woocommerce-container,
            .ast-separate-container .error-404,
            .ast-separate-container .no-results,
            .single.ast-separate-container.ast-author-meta,
            .ast-separate-container .related-posts-title-wrapper,
            .ast-separate-container .comments-count-wrapper,
            .ast-box-layout.ast-plain-container .site-content,
            .ast-padded-layout.ast-plain-container .site-content,
            .ast-separate-container .ast-archive-description {
                background-color: var(--ast-global-color-5);
                ;
                background-image: none;
                ;
            }
        }

        @media (max-width:544px) {

            .ast-separate-container .ast-article-single:not(.ast-related-post),
            .woocommerce.ast-separate-container .ast-woocommerce-container,
            .ast-separate-container .error-404,
            .ast-separate-container .no-results,
            .single.ast-separate-container.ast-author-meta,
            .ast-separate-container .related-posts-title-wrapper,
            .ast-separate-container .comments-count-wrapper,
            .ast-box-layout.ast-plain-container .site-content,
            .ast-padded-layout.ast-plain-container .site-content,
            .ast-separate-container .ast-archive-description {
                background-color: var(--ast-global-color-5);
                ;
                background-image: none;
                ;
            }
        }

        .ast-separate-container.ast-two-container #secondary .widget {
            background-color: var(--ast-global-color-5);
            ;
            background-image: none;
            ;
        }

        @media (max-width:921px) {
            .ast-separate-container.ast-two-container #secondary .widget {
                background-color: var(--ast-global-color-5);
                ;
                background-image: none;
                ;
            }
        }

        @media (max-width:544px) {
            .ast-separate-container.ast-two-container #secondary .widget {
                background-color: var(--ast-global-color-5);
                ;
                background-image: none;
                ;
            }
        }

        .ast-mobile-header-content>*,
        .ast-desktop-header-content>* {
            padding: 10px 0;
            height: auto;
        }

        .ast-mobile-header-content>*:first-child,
        .ast-desktop-header-content>*:first-child {
            padding-top: 10px;
        }

        .ast-mobile-header-content>.ast-builder-menu,
        .ast-desktop-header-content>.ast-builder-menu {
            padding-top: 0;
        }

        .ast-mobile-header-content>*:last-child,
        .ast-desktop-header-content>*:last-child {
            padding-bottom: 0;
        }

        .ast-mobile-header-content .ast-search-menu-icon.ast-inline-search label,
        .ast-desktop-header-content .ast-search-menu-icon.ast-inline-search label {
            width: 100%;
        }

        .ast-desktop-header-content .main-header-bar-navigation .ast-submenu-expanded>.ast-menu-toggle::before {
            transform: rotateX(180deg);
        }

        #ast-desktop-header .ast-desktop-header-content,
        .ast-mobile-header-content .ast-search-icon,
        .ast-desktop-header-content .ast-search-icon,
        .ast-mobile-header-wrap .ast-mobile-header-content,
        .ast-main-header-nav-open.ast-popup-nav-open .ast-mobile-header-wrap .ast-mobile-header-content,
        .ast-main-header-nav-open.ast-popup-nav-open .ast-desktop-header-content {
            display: none;
        }

        .ast-main-header-nav-open.ast-header-break-point #ast-desktop-header .ast-desktop-header-content,
        .ast-main-header-nav-open.ast-header-break-point .ast-mobile-header-wrap .ast-mobile-header-content {
            display: block;
        }

        .ast-desktop .ast-desktop-header-content .astra-menu-animation-slide-up>.menu-item>.sub-menu,
        .ast-desktop .ast-desktop-header-content .astra-menu-animation-slide-up>.menu-item .menu-item>.sub-menu,
        .ast-desktop .ast-desktop-header-content .astra-menu-animation-slide-down>.menu-item>.sub-menu,
        .ast-desktop .ast-desktop-header-content .astra-menu-animation-slide-down>.menu-item .menu-item>.sub-menu,
        .ast-desktop .ast-desktop-header-content .astra-menu-animation-fade>.menu-item>.sub-menu,
        .ast-desktop .ast-desktop-header-content .astra-menu-animation-fade>.menu-item .menu-item>.sub-menu {
            opacity: 1;
            visibility: visible;
        }

        .ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-header-wrap .ast-mobile-header-content .main-header-bar-navigation {
            width: unset;
            margin: unset;
        }

        .ast-mobile-header-content.content-align-flex-end .main-header-bar-navigation .menu-item-has-children>.ast-menu-toggle,
        .ast-desktop-header-content.content-align-flex-end .main-header-bar-navigation .menu-item-has-children>.ast-menu-toggle {
            left: calc(20px - 0.907em);
            right: auto;
        }

        .ast-mobile-header-content .ast-search-menu-icon,
        .ast-mobile-header-content .ast-search-menu-icon.slide-search,
        .ast-desktop-header-content .ast-search-menu-icon,
        .ast-desktop-header-content .ast-search-menu-icon.slide-search {
            width: 100%;
            position: relative;
            display: block;
            right: auto;
            transform: none;
        }

        .ast-mobile-header-content .ast-search-menu-icon.slide-search .search-form,
        .ast-mobile-header-content .ast-search-menu-icon .search-form,
        .ast-desktop-header-content .ast-search-menu-icon.slide-search .search-form,
        .ast-desktop-header-content .ast-search-menu-icon .search-form {
            right: 0;
            visibility: visible;
            opacity: 1;
            position: relative;
            top: auto;
            transform: none;
            padding: 0;
            display: block;
            overflow: hidden;
        }

        .ast-mobile-header-content .ast-search-menu-icon.ast-inline-search .search-field,
        .ast-mobile-header-content .ast-search-menu-icon .search-field,
        .ast-desktop-header-content .ast-search-menu-icon.ast-inline-search .search-field,
        .ast-desktop-header-content .ast-search-menu-icon .search-field {
            width: 100%;
            padding-right: 5.5em;
        }

        .ast-mobile-header-content .ast-search-menu-icon .search-submit,
        .ast-desktop-header-content .ast-search-menu-icon .search-submit {
            display: block;
            position: absolute;
            height: 100%;
            top: 0;
            right: 0;
            padding: 0 1em;
            border-radius: 0;
        }

        .ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-header-wrap .ast-mobile-header-content .main-header-bar-navigation ul .sub-menu .menu-link {
            padding-left: 30px;
        }

        .ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-header-wrap .ast-mobile-header-content .main-header-bar-navigation .sub-menu .menu-item .menu-item .menu-link {
            padding-left: 40px;
        }

        .ast-mobile-popup-drawer.active .ast-mobile-popup-inner {
            background-color: #ffffff;
            ;
        }

        .ast-mobile-header-wrap .ast-mobile-header-content,
        .ast-desktop-header-content {
            background-color: #ffffff;
            ;
        }

        .ast-mobile-popup-content>*,
        .ast-mobile-header-content>*,
        .ast-desktop-popup-content>*,
        .ast-desktop-header-content>* {
            padding-top: 0;
            padding-bottom: 0;
        }

        .content-align-flex-start .ast-builder-layout-element {
            justify-content: flex-start;
        }

        .content-align-flex-start .main-header-menu {
            text-align: left;
        }

        .ast-mobile-popup-drawer.active .menu-toggle-close {
            color: #3a3a3a;
        }

        .ast-mobile-header-wrap .ast-primary-header-bar,
        .ast-primary-header-bar .site-primary-header-wrap {
            min-height: 70px;
        }

        .ast-desktop .ast-primary-header-bar .main-header-menu>.menu-item {
            line-height: 70px;
        }

        .ast-header-break-point #masthead .ast-mobile-header-wrap .ast-primary-header-bar,
        .ast-header-break-point #masthead .ast-mobile-header-wrap .ast-below-header-bar,
        .ast-header-break-point #masthead .ast-mobile-header-wrap .ast-above-header-bar {
            padding-left: 20px;
            padding-right: 20px;
        }

        .ast-header-break-point .ast-primary-header-bar {
            border-bottom-width: 1px;
            border-bottom-color: rgba(235, 235, 235, 0.24);
            border-bottom-style: solid;
        }

        @media (min-width:922px) {
            .ast-primary-header-bar {
                border-bottom-width: 1px;
                border-bottom-color: rgba(235, 235, 235, 0.24);
                border-bottom-style: solid;
            }
        }

        .ast-primary-header-bar {
            background-color: #ffffff;
            ;
            background-image: none;
            ;
        }

        .ast-primary-header-bar {
            display: block;
        }

        @media (max-width:921px) {
            .ast-header-break-point .ast-primary-header-bar {
                display: grid;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .ast-primary-header-bar {
                display: grid;
            }
        }

        [data-section="section-header-mobile-trigger"] .ast-button-wrap .ast-mobile-menu-trigger-minimal {
            color: var(--ast-global-color-0);
            border: none;
            background: transparent;
        }

        [data-section="section-header-mobile-trigger"] .ast-button-wrap .mobile-menu-toggle-icon .ast-mobile-svg {
            width: 20px;
            height: 20px;
            fill: var(--ast-global-color-0);
        }

        [data-section="section-header-mobile-trigger"] .ast-button-wrap .mobile-menu-wrap .mobile-menu {
            color: var(--ast-global-color-0);
        }

        .ast-builder-menu-mobile .main-navigation .menu-item.menu-item-has-children>.ast-menu-toggle {
            top: 0;
        }

        .ast-builder-menu-mobile .main-navigation .menu-item-has-children>.menu-link:after {
            content: unset;
        }

        .ast-hfb-header .ast-builder-menu-mobile .main-header-menu,
        .ast-hfb-header .ast-builder-menu-mobile .main-navigation .menu-item .menu-link,
        .ast-hfb-header .ast-builder-menu-mobile .main-navigation .menu-item .sub-menu .menu-link {
            border-style: none;
        }

        .ast-builder-menu-mobile .main-navigation .menu-item.menu-item-has-children>.ast-menu-toggle {
            top: 0;
        }

        @media (max-width:921px) {
            .ast-builder-menu-mobile .main-navigation .menu-item.menu-item-has-children>.ast-menu-toggle {
                top: 0;
            }

            .ast-builder-menu-mobile .main-navigation .menu-item-has-children>.menu-link:after {
                content: unset;
            }
        }

        @media (max-width:544px) {
            .ast-builder-menu-mobile .main-navigation .menu-item.menu-item-has-children>.ast-menu-toggle {
                top: 0;
            }
        }

        .ast-builder-menu-mobile .main-navigation {
            display: block;
        }

        @media (max-width:921px) {
            .ast-header-break-point .ast-builder-menu-mobile .main-navigation {
                display: block;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .ast-builder-menu-mobile .main-navigation {
                display: block;
            }
        }

        .ast-below-header .main-header-bar-navigation {
            height: 100%;
        }

        .ast-header-break-point .ast-mobile-header-wrap .ast-below-header-wrap .main-header-bar-navigation .inline-on-mobile .menu-item .menu-link {
            border: none;
        }

        .ast-header-break-point .ast-mobile-header-wrap .ast-below-header-wrap .main-header-bar-navigation .inline-on-mobile .menu-item-has-children>.ast-menu-toggle::before {
            font-size: .6rem;
        }

        .ast-header-break-point .ast-mobile-header-wrap .ast-below-header-wrap .main-header-bar-navigation .ast-submenu-expanded>.ast-menu-toggle::before {
            transform: rotateX(180deg);
        }

        #masthead .ast-mobile-header-wrap .ast-below-header-bar {
            padding-left: 20px;
            padding-right: 20px;
        }

        .ast-mobile-header-wrap .ast-below-header-bar,
        .ast-below-header-bar .site-below-header-wrap {
            min-height: 60px;
        }

        .ast-desktop .ast-below-header-bar .main-header-menu>.menu-item {
            line-height: 60px;
        }

        .ast-desktop .ast-below-header-bar .ast-header-woo-cart,
        .ast-desktop .ast-below-header-bar .ast-header-edd-cart {
            line-height: 60px;
        }

        .ast-below-header-bar {
            border-bottom-width: 1px;
            border-bottom-color: #eaeaea;
            border-bottom-style: solid;
        }

        .ast-below-header-bar {
            background-color: #eeeeee;
            ;
            background-image: none;
            ;
        }

        .ast-header-break-point .ast-below-header-bar {
            background-color: #eeeeee;
        }

        .ast-below-header-bar.ast-below-header,
        .ast-header-break-point .ast-below-header-bar.ast-below-header {
            margin-top: 20px;
        }

        .ast-below-header-bar {
            display: block;
        }

        @media (max-width:921px) {
            .ast-header-break-point .ast-below-header-bar {
                display: grid;
            }
        }

        @media (max-width:544px) {
            .ast-header-break-point .ast-below-header-bar {
                display: grid;
            }
        }

        :root {
            --e-global-color-astglobalcolor0: #ffc03d;
            --e-global-color-astglobalcolor1: #f8b526;
            --e-global-color-astglobalcolor2: #212d45;
            --e-global-color-astglobalcolor3: #4b4f58;
            --e-global-color-astglobalcolor4: #F5F5F5;
            --e-global-color-astglobalcolor5: #FFFFFF;
            --e-global-color-astglobalcolor6: #F2F5F7;
            --e-global-color-astglobalcolor7: #212d45;
            --e-global-color-astglobalcolor8: #000000;
        }
    </style>
    <link rel='stylesheet' id='astra-google-fonts-css' href='https://fonts.googleapis.com/css?family=Open+Sans%3A400%2C%7CMontserrat%3A700%2C&#038;display=fallback&#038;ver=4.7.2' media='all' />
    <style id='wp-emoji-styles-inline-css'>
        img.wp-smiley,
        img.emoji {
            display: inline !important;
            border: none !important;
            box-shadow: none !important;
            height: 1em !important;
            width: 1em !important;
            margin: 0 0.07em !important;
            vertical-align: -0.1em !important;
            background: none !important;
            padding: 0 !important;
        }
    </style>
    <style id='global-styles-inline-css'>
        :root {
            --wp--preset--aspect-ratio--square: 1;
            --wp--preset--aspect-ratio--4-3: 4/3;
            --wp--preset--aspect-ratio--3-4: 3/4;
            --wp--preset--aspect-ratio--3-2: 3/2;
            --wp--preset--aspect-ratio--2-3: 2/3;
            --wp--preset--aspect-ratio--16-9: 16/9;
            --wp--preset--aspect-ratio--9-16: 9/16;
            --wp--preset--color--black: #000000;
            --wp--preset--color--cyan-bluish-gray: #abb8c3;
            --wp--preset--color--white: #ffffff;
            --wp--preset--color--pale-pink: #f78da7;
            --wp--preset--color--vivid-red: #cf2e2e;
            --wp--preset--color--luminous-vivid-orange: #ff6900;
            --wp--preset--color--luminous-vivid-amber: #fcb900;
            --wp--preset--color--light-green-cyan: #7bdcb5;
            --wp--preset--color--vivid-green-cyan: #00d084;
            --wp--preset--color--pale-cyan-blue: #8ed1fc;
            --wp--preset--color--vivid-cyan-blue: #0693e3;
            --wp--preset--color--vivid-purple: #9b51e0;
            --wp--preset--color--ast-global-color-0: var(--ast-global-color-0);
            --wp--preset--color--ast-global-color-1: var(--ast-global-color-1);
            --wp--preset--color--ast-global-color-2: var(--ast-global-color-2);
            --wp--preset--color--ast-global-color-3: var(--ast-global-color-3);
            --wp--preset--color--ast-global-color-4: var(--ast-global-color-4);
            --wp--preset--color--ast-global-color-5: var(--ast-global-color-5);
            --wp--preset--color--ast-global-color-6: var(--ast-global-color-6);
            --wp--preset--color--ast-global-color-7: var(--ast-global-color-7);
            --wp--preset--color--ast-global-color-8: var(--ast-global-color-8);
            --wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg, rgba(6, 147, 227, 1) 0%, rgb(155, 81, 224) 100%);
            --wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg, rgb(122, 220, 180) 0%, rgb(0, 208, 130) 100%);
            --wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg, rgba(252, 185, 0, 1) 0%, rgba(255, 105, 0, 1) 100%);
            --wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg, rgba(255, 105, 0, 1) 0%, rgb(207, 46, 46) 100%);
            --wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg, rgb(238, 238, 238) 0%, rgb(169, 184, 195) 100%);
            --wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg, rgb(74, 234, 220) 0%, rgb(151, 120, 209) 20%, rgb(207, 42, 186) 40%, rgb(238, 44, 130) 60%, rgb(251, 105, 98) 80%, rgb(254, 248, 76) 100%);
            --wp--preset--gradient--blush-light-purple: linear-gradient(135deg, rgb(255, 206, 236) 0%, rgb(152, 150, 240) 100%);
            --wp--preset--gradient--blush-bordeaux: linear-gradient(135deg, rgb(254, 205, 165) 0%, rgb(254, 45, 45) 50%, rgb(107, 0, 62) 100%);
            --wp--preset--gradient--luminous-dusk: linear-gradient(135deg, rgb(255, 203, 112) 0%, rgb(199, 81, 192) 50%, rgb(65, 88, 208) 100%);
            --wp--preset--gradient--pale-ocean: linear-gradient(135deg, rgb(255, 245, 203) 0%, rgb(182, 227, 212) 50%, rgb(51, 167, 181) 100%);
            --wp--preset--gradient--electric-grass: linear-gradient(135deg, rgb(202, 248, 128) 0%, rgb(113, 206, 126) 100%);
            --wp--preset--gradient--midnight: linear-gradient(135deg, rgb(2, 3, 129) 0%, rgb(40, 116, 252) 100%);
            --wp--preset--font-size--small: 13px;
            --wp--preset--font-size--medium: 20px;
            --wp--preset--font-size--large: 36px;
            --wp--preset--font-size--x-large: 42px;
            --wp--preset--spacing--20: 0.44rem;
            --wp--preset--spacing--30: 0.67rem;
            --wp--preset--spacing--40: 1rem;
            --wp--preset--spacing--50: 1.5rem;
            --wp--preset--spacing--60: 2.25rem;
            --wp--preset--spacing--70: 3.38rem;
            --wp--preset--spacing--80: 5.06rem;
            --wp--preset--shadow--natural: 6px 6px 9px rgba(0, 0, 0, 0.2);
            --wp--preset--shadow--deep: 12px 12px 50px rgba(0, 0, 0, 0.4);
            --wp--preset--shadow--sharp: 6px 6px 0px rgba(0, 0, 0, 0.2);
            --wp--preset--shadow--outlined: 6px 6px 0px -3px rgba(255, 255, 255, 1), 6px 6px rgba(0, 0, 0, 1);
            --wp--preset--shadow--crisp: 6px 6px 0px rgba(0, 0, 0, 1);
        }

        :root {
            --wp--style--global--content-size: var(--wp--custom--ast-content-width-size);
            --wp--style--global--wide-size: var(--wp--custom--ast-wide-width-size);
        }

        :where(body) {
            margin: 0;
        }

        .wp-site-blocks>.alignleft {
            float: left;
            margin-right: 2em;
        }

        .wp-site-blocks>.alignright {
            float: right;
            margin-left: 2em;
        }

        .wp-site-blocks>.aligncenter {
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
        }

        :where(.wp-site-blocks)>* {
            margin-block-start: 24px;
            margin-block-end: 0;
        }

        :where(.wp-site-blocks)> :first-child {
            margin-block-start: 0;
        }

        :where(.wp-site-blocks)> :last-child {
            margin-block-end: 0;
        }

        :root {
            --wp--style--block-gap: 24px;
        }

        :root :where(.is-layout-flow)> :first-child {
            margin-block-start: 0;
        }

        :root :where(.is-layout-flow)> :last-child {
            margin-block-end: 0;
        }

        :root :where(.is-layout-flow)>* {
            margin-block-start: 24px;
            margin-block-end: 0;
        }

        :root :where(.is-layout-constrained)> :first-child {
            margin-block-start: 0;
        }

        :root :where(.is-layout-constrained)> :last-child {
            margin-block-end: 0;
        }

        :root :where(.is-layout-constrained)>* {
            margin-block-start: 24px;
            margin-block-end: 0;
        }

        :root :where(.is-layout-flex) {
            gap: 24px;
        }

        :root :where(.is-layout-grid) {
            gap: 24px;
        }

        .is-layout-flow>.alignleft {
            float: left;
            margin-inline-start: 0;
            margin-inline-end: 2em;
        }

        .is-layout-flow>.alignright {
            float: right;
            margin-inline-start: 2em;
            margin-inline-end: 0;
        }

        .is-layout-flow>.aligncenter {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .is-layout-constrained>.alignleft {
            float: left;
            margin-inline-start: 0;
            margin-inline-end: 2em;
        }

        .is-layout-constrained>.alignright {
            float: right;
            margin-inline-start: 2em;
            margin-inline-end: 0;
        }

        .is-layout-constrained>.aligncenter {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .is-layout-constrained> :where(:not(.alignleft):not(.alignright):not(.alignfull)) {
            max-width: var(--wp--style--global--content-size);
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .is-layout-constrained>.alignwide {
            max-width: var(--wp--style--global--wide-size);
        }

        body .is-layout-flex {
            display: flex;
        }

        .is-layout-flex {
            flex-wrap: wrap;
            align-items: center;
        }

        .is-layout-flex> :is(*, div) {
            margin: 0;
        }

        body .is-layout-grid {
            display: grid;
        }

        .is-layout-grid> :is(*, div) {
            margin: 0;
        }

        body {
            padding-top: 0px;
            padding-right: 0px;
            padding-bottom: 0px;
            padding-left: 0px;
        }

        a:where(:not(.wp-element-button)) {
            text-decoration: none;
        }

        :root :where(.wp-element-button, .wp-block-button__link) {
            background-color: #32373c;
            border-width: 0;
            color: #fff;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            padding: calc(0.667em + 2px) calc(1.333em + 2px);
            text-decoration: none;
        }

        .has-black-color {
            color: var(--wp--preset--color--black) !important;
        }

        .has-cyan-bluish-gray-color {
            color: var(--wp--preset--color--cyan-bluish-gray) !important;
        }

        .has-white-color {
            color: var(--wp--preset--color--white) !important;
        }

        .has-pale-pink-color {
            color: var(--wp--preset--color--pale-pink) !important;
        }

        .has-vivid-red-color {
            color: var(--wp--preset--color--vivid-red) !important;
        }

        .has-luminous-vivid-orange-color {
            color: var(--wp--preset--color--luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-amber-color {
            color: var(--wp--preset--color--luminous-vivid-amber) !important;
        }

        .has-light-green-cyan-color {
            color: var(--wp--preset--color--light-green-cyan) !important;
        }

        .has-vivid-green-cyan-color {
            color: var(--wp--preset--color--vivid-green-cyan) !important;
        }

        .has-pale-cyan-blue-color {
            color: var(--wp--preset--color--pale-cyan-blue) !important;
        }

        .has-vivid-cyan-blue-color {
            color: var(--wp--preset--color--vivid-cyan-blue) !important;
        }

        .has-vivid-purple-color {
            color: var(--wp--preset--color--vivid-purple) !important;
        }

        .has-ast-global-color-0-color {
            color: var(--wp--preset--color--ast-global-color-0) !important;
        }

        .has-ast-global-color-1-color {
            color: var(--wp--preset--color--ast-global-color-1) !important;
        }

        .has-ast-global-color-2-color {
            color: var(--wp--preset--color--ast-global-color-2) !important;
        }

        .has-ast-global-color-3-color {
            color: var(--wp--preset--color--ast-global-color-3) !important;
        }

        .has-ast-global-color-4-color {
            color: var(--wp--preset--color--ast-global-color-4) !important;
        }

        .has-ast-global-color-5-color {
            color: var(--wp--preset--color--ast-global-color-5) !important;
        }

        .has-ast-global-color-6-color {
            color: var(--wp--preset--color--ast-global-color-6) !important;
        }

        .has-ast-global-color-7-color {
            color: var(--wp--preset--color--ast-global-color-7) !important;
        }

        .has-ast-global-color-8-color {
            color: var(--wp--preset--color--ast-global-color-8) !important;
        }

        .has-black-background-color {
            background-color: var(--wp--preset--color--black) !important;
        }

        .has-cyan-bluish-gray-background-color {
            background-color: var(--wp--preset--color--cyan-bluish-gray) !important;
        }

        .has-white-background-color {
            background-color: var(--wp--preset--color--white) !important;
        }

        .has-pale-pink-background-color {
            background-color: var(--wp--preset--color--pale-pink) !important;
        }

        .has-vivid-red-background-color {
            background-color: var(--wp--preset--color--vivid-red) !important;
        }

        .has-luminous-vivid-orange-background-color {
            background-color: var(--wp--preset--color--luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-amber-background-color {
            background-color: var(--wp--preset--color--luminous-vivid-amber) !important;
        }

        .has-light-green-cyan-background-color {
            background-color: var(--wp--preset--color--light-green-cyan) !important;
        }

        .has-vivid-green-cyan-background-color {
            background-color: var(--wp--preset--color--vivid-green-cyan) !important;
        }

        .has-pale-cyan-blue-background-color {
            background-color: var(--wp--preset--color--pale-cyan-blue) !important;
        }

        .has-vivid-cyan-blue-background-color {
            background-color: var(--wp--preset--color--vivid-cyan-blue) !important;
        }

        .has-vivid-purple-background-color {
            background-color: var(--wp--preset--color--vivid-purple) !important;
        }

        .has-ast-global-color-0-background-color {
            background-color: var(--wp--preset--color--ast-global-color-0) !important;
        }

        .has-ast-global-color-1-background-color {
            background-color: var(--wp--preset--color--ast-global-color-1) !important;
        }

        .has-ast-global-color-2-background-color {
            background-color: var(--wp--preset--color--ast-global-color-2) !important;
        }

        .has-ast-global-color-3-background-color {
            background-color: var(--wp--preset--color--ast-global-color-3) !important;
        }

        .has-ast-global-color-4-background-color {
            background-color: var(--wp--preset--color--ast-global-color-4) !important;
        }

        .has-ast-global-color-5-background-color {
            background-color: var(--wp--preset--color--ast-global-color-5) !important;
        }

        .has-ast-global-color-6-background-color {
            background-color: var(--wp--preset--color--ast-global-color-6) !important;
        }

        .has-ast-global-color-7-background-color {
            background-color: var(--wp--preset--color--ast-global-color-7) !important;
        }

        .has-ast-global-color-8-background-color {
            background-color: var(--wp--preset--color--ast-global-color-8) !important;
        }

        .has-black-border-color {
            border-color: var(--wp--preset--color--black) !important;
        }

        .has-cyan-bluish-gray-border-color {
            border-color: var(--wp--preset--color--cyan-bluish-gray) !important;
        }

        .has-white-border-color {
            border-color: var(--wp--preset--color--white) !important;
        }

        .has-pale-pink-border-color {
            border-color: var(--wp--preset--color--pale-pink) !important;
        }

        .has-vivid-red-border-color {
            border-color: var(--wp--preset--color--vivid-red) !important;
        }

        .has-luminous-vivid-orange-border-color {
            border-color: var(--wp--preset--color--luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-amber-border-color {
            border-color: var(--wp--preset--color--luminous-vivid-amber) !important;
        }

        .has-light-green-cyan-border-color {
            border-color: var(--wp--preset--color--light-green-cyan) !important;
        }

        .has-vivid-green-cyan-border-color {
            border-color: var(--wp--preset--color--vivid-green-cyan) !important;
        }

        .has-pale-cyan-blue-border-color {
            border-color: var(--wp--preset--color--pale-cyan-blue) !important;
        }

        .has-vivid-cyan-blue-border-color {
            border-color: var(--wp--preset--color--vivid-cyan-blue) !important;
        }

        .has-vivid-purple-border-color {
            border-color: var(--wp--preset--color--vivid-purple) !important;
        }

        .has-ast-global-color-0-border-color {
            border-color: var(--wp--preset--color--ast-global-color-0) !important;
        }

        .has-ast-global-color-1-border-color {
            border-color: var(--wp--preset--color--ast-global-color-1) !important;
        }

        .has-ast-global-color-2-border-color {
            border-color: var(--wp--preset--color--ast-global-color-2) !important;
        }

        .has-ast-global-color-3-border-color {
            border-color: var(--wp--preset--color--ast-global-color-3) !important;
        }

        .has-ast-global-color-4-border-color {
            border-color: var(--wp--preset--color--ast-global-color-4) !important;
        }

        .has-ast-global-color-5-border-color {
            border-color: var(--wp--preset--color--ast-global-color-5) !important;
        }

        .has-ast-global-color-6-border-color {
            border-color: var(--wp--preset--color--ast-global-color-6) !important;
        }

        .has-ast-global-color-7-border-color {
            border-color: var(--wp--preset--color--ast-global-color-7) !important;
        }

        .has-ast-global-color-8-border-color {
            border-color: var(--wp--preset--color--ast-global-color-8) !important;
        }

        .has-vivid-cyan-blue-to-vivid-purple-gradient-background {
            background: var(--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple) !important;
        }

        .has-light-green-cyan-to-vivid-green-cyan-gradient-background {
            background: var(--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan) !important;
        }

        .has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background {
            background: var(--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-orange-to-vivid-red-gradient-background {
            background: var(--wp--preset--gradient--luminous-vivid-orange-to-vivid-red) !important;
        }

        .has-very-light-gray-to-cyan-bluish-gray-gradient-background {
            background: var(--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray) !important;
        }

        .has-cool-to-warm-spectrum-gradient-background {
            background: var(--wp--preset--gradient--cool-to-warm-spectrum) !important;
        }

        .has-blush-light-purple-gradient-background {
            background: var(--wp--preset--gradient--blush-light-purple) !important;
        }

        .has-blush-bordeaux-gradient-background {
            background: var(--wp--preset--gradient--blush-bordeaux) !important;
        }

        .has-luminous-dusk-gradient-background {
            background: var(--wp--preset--gradient--luminous-dusk) !important;
        }

        .has-pale-ocean-gradient-background {
            background: var(--wp--preset--gradient--pale-ocean) !important;
        }

        .has-electric-grass-gradient-background {
            background: var(--wp--preset--gradient--electric-grass) !important;
        }

        .has-midnight-gradient-background {
            background: var(--wp--preset--gradient--midnight) !important;
        }

        .has-small-font-size {
            font-size: var(--wp--preset--font-size--small) !important;
        }

        .has-medium-font-size {
            font-size: var(--wp--preset--font-size--medium) !important;
        }

        .has-large-font-size {
            font-size: var(--wp--preset--font-size--large) !important;
        }

        .has-x-large-font-size {
            font-size: var(--wp--preset--font-size--x-large) !important;
        }

        :root :where(.wp-block-pullquote) {
            font-size: 1.5em;
            line-height: 1.6;
        }
    </style>







    <style id='elementor-frontend-inline-css'>
        .elementor-kit-4 {
            --e-global-color-primary: #6EC1E4;
            --e-global-color-secondary: #54595F;
            --e-global-color-text: #7A7A7A;
            --e-global-color-accent: #61CE70;
            --e-global-typography-primary-font-family: "Roboto";
            --e-global-typography-primary-font-weight: 600;
            --e-global-typography-secondary-font-family: "Roboto Slab";
            --e-global-typography-secondary-font-weight: 400;
            --e-global-typography-text-font-family: "Roboto";
            --e-global-typography-text-font-weight: 400;
            --e-global-typography-accent-font-family: "Roboto";
            --e-global-typography-accent-font-weight: 500;
        }

        .elementor-section.elementor-section-boxed>.elementor-container {
            max-width: 1200px;
        }

        .e-con {
            --container-max-width: 1200px;
        }

        .elementor-widget:not(:last-child) {
            margin-block-end: 20px;
        }

        .elementor-element {
            --widgets-spacing: 20px 20px;
        }

            {}

        h1.entry-title {
            display: var(--page-title-display);
        }

        .elementor-kit-4 e-page-transition {
            background-color: #FFBC7D;
        }

        @media(max-width:1024px) {
            .elementor-section.elementor-section-boxed>.elementor-container {
                max-width: 1024px;
            }

            .e-con {
                --container-max-width: 1024px;
            }
        }

        @media(max-width:767px) {
            .elementor-section.elementor-section-boxed>.elementor-container {
                max-width: 767px;
            }

            .e-con {
                --container-max-width: 767px;
            }
        }

        .elementor-2148 .elementor-element.elementor-element-f0b8bbd:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-f0b8bbd>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: transparent;
            background-image: linear-gradient(180deg, #202D45 0%, #f2295b 100%);
        }

        .elementor-2148 .elementor-element.elementor-element-f0b8bbd {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            padding: 0px 0px 0px 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-f0b8bbd>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-53684899>.elementor-widget-wrap>.elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial):not(:last-child):not(.elementor-absolute) {
            margin-bottom: 20px;
        }

        .elementor-2148 .elementor-element.elementor-element-53684899:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
        .elementor-2148 .elementor-element.elementor-element-53684899>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: transparent;
            background-image: linear-gradient(180deg, #202D45 0%, #f2295b 100%);
        }

        .elementor-2148 .elementor-element.elementor-element-53684899>.elementor-element-populated {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            padding: 0px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-53684899>.elementor-element-populated>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-10f337ce:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-10f337ce>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #0E1727;
        }

        .elementor-2148 .elementor-element.elementor-element-10f337ce {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-10f337ce>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-e01aa18 {
            text-align: left;
            width: initial;
            max-width: initial;
        }

        .elementor-2148 .elementor-element.elementor-element-e01aa18 img {
            width: 84%;
        }

        .elementor-bc-flex-widget .elementor-2148 .elementor-element.elementor-element-70268acf.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-70268acf.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child) {
            padding-bottom: calc(0px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child) {
            margin-top: calc(0px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item {
            margin-right: calc(0px/2);
            margin-left: calc(0px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-items.elementor-inline-items {
            margin-right: calc(-0px/2);
            margin-left: calc(-0px/2);
        }

        body.rtl .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            left: calc(-0px/2);
        }

        body:not(.rtl) .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            right: calc(-0px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-icon i {
            color: #FEC03D;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-icon svg {
            fill: #FEC03D;
            transition: fill 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-b265789 {
            --e-icon-list-icon-size: 41px;
            --icon-vertical-offset: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-item>.elementor-icon-list-text,
        .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-item>a {
            font-size: 25px;
            font-weight: bold;
        }

        .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-text {
            color: #FFFFFF;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-item:hover .elementor-icon-list-text {
            color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-617b151>.elementor-widget-container {
            margin: 0px 0px 0px 0px;
            padding: 50px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-617b151 {
            width: var(--container-widget-width, 804.398px);
            max-width: 804.398px;
            --container-widget-width: 804.398px;
            --container-widget-flex-grow: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-617b151.elementor-element {
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-bc-flex-widget .elementor-2148 .elementor-element.elementor-element-695b0217.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-695b0217.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-695b0217>.elementor-element-populated {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            padding: 0px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-695b0217>.elementor-element-populated>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-54476fae .uael-infobox,
        .elementor-2148 .elementor-element.elementor-element-54476fae .uael-separator-parent {
            text-align: center;
        }

        .elementor-2148 .elementor-element.elementor-element-54476fae .uael-icon-wrap .uael-icon i {
            font-size: 40px;
            height: 40px;
            width: 40px;
            line-height: 40px;
            text-align: center;
            color: #FEBF3D;
        }

        .elementor-2148 .elementor-element.elementor-element-54476fae .uael-icon-wrap .uael-icon {
            height: 40px;
            width: 40px;
            line-height: 40px;
        }

        .elementor-2148 .elementor-element.elementor-element-54476fae .uael-icon-wrap .uael-icon i,
        .elementor-2148 .elementor-element.elementor-element-54476fae .uael-icon-wrap .uael-icon svg {
            transform: rotate(0deg);
        }

        .elementor-2148 .elementor-element.elementor-element-54476fae .uael-icon-wrap .uael-icon svg {
            fill: #FEBF3D;
        }

        .elementor-2148 .elementor-element.elementor-element-54476fae .uael-infobox-title {
            font-weight: bold;
            color: #FFFFFF;
            margin: 0px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-6b7987d {
            --display: flex;
            --flex-direction: row;
            --container-widget-width: calc((1 - var(--container-widget-flex-grow)) * 100%);
            --container-widget-height: 100%;
            --container-widget-flex-grow: 1;
            --container-widget-align-self: stretch;
            --flex-wrap-mobile: wrap;
            --align-items: stretch;
            --gap: 0px 0px;
            --background-transition: 0.3s;
            --margin-top: 0px;
            --margin-bottom: 0px;
            --margin-left: 0px;
            --margin-right: 0px;
            --padding-top: 0px;
            --padding-bottom: 0px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-366a195 {
            --display: flex;
            --justify-content: center;
            --align-items: center;
            --container-widget-width: calc((1 - var(--container-widget-flex-grow)) * 100%);
            --background-transition: 0.3s;
            --overlay-opacity: 0.81;
            --padding-top: 120px;
            --padding-bottom: 100px;
            --padding-left: 70px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-366a195:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-366a195>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-image: url("https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/photo_5366294644461269436_y.jpg");
            background-position: top right;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .elementor-2148 .elementor-element.elementor-element-366a195::before,
        .elementor-2148 .elementor-element.elementor-element-366a195>.elementor-background-video-container::before,
        .elementor-2148 .elementor-element.elementor-element-366a195>.e-con-inner>.elementor-background-video-container::before,
        .elementor-2148 .elementor-element.elementor-element-366a195>.elementor-background-slideshow::before,
        .elementor-2148 .elementor-element.elementor-element-366a195>.e-con-inner>.elementor-background-slideshow::before,
        .elementor-2148 .elementor-element.elementor-element-366a195>.elementor-motion-effects-container>.elementor-motion-effects-layer::before {
            --background-overlay: '';
            background-color: var(--e-global-color-astglobalcolor7);
        }

        .elementor-2148 .elementor-element.elementor-element-366a195,
        .elementor-2148 .elementor-element.elementor-element-366a195::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-366a195.e-con {
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-e2ba217 {
            --display: flex;
            --flex-direction: row;
            --container-widget-width: calc((1 - var(--container-widget-flex-grow)) * 100%);
            --container-widget-height: 100%;
            --container-widget-flex-grow: 1;
            --container-widget-align-self: stretch;
            --flex-wrap-mobile: wrap;
            --align-items: stretch;
            --gap: 0px 0px;
            --background-transition: 0.3s;
            --margin-top: 0px;
            --margin-bottom: 0px;
            --margin-left: 0px;
            --margin-right: 0px;
            --padding-top: 0px;
            --padding-bottom: 0px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-e2ba217.e-con {
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-2d23bbf {
            --display: flex;
            --background-transition: 0.3s;
            --margin-top: 0px;
            --margin-bottom: 0px;
            --margin-left: 0px;
            --margin-right: 0px;
            --padding-top: 0px;
            --padding-bottom: 0px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-c94d200 .elementor-heading-title {
            color: var(--e-global-color-astglobalcolor0);
            font-size: 30px;
            font-weight: 700;
        }

        .elementor-2148 .elementor-element.elementor-element-c94d200>.elementor-widget-container {
            margin: 0px 0px 0px 0px;
            padding: 0px 0px 20px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-1405c7e .elementor-heading-title {
            color: #FFFFFF;
            font-size: 40px;
        }

        .elementor-2148 .elementor-element.elementor-element-899e0e5 {
            color: #FFFFFF;
        }

        .elementor-2148 .elementor-element.elementor-element-410e3ef {
            color: #FFFFFF;
        }

        .elementor-2148 .elementor-element.elementor-element-c40f37a {
            --display: flex;
            --flex-direction: row;
            --container-widget-width: initial;
            --container-widget-height: 100%;
            --container-widget-flex-grow: 1;
            --container-widget-align-self: stretch;
            --flex-wrap-mobile: wrap;
            --background-transition: 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-db72c16>.elementor-widget-container {
            margin: 0px 0px 0px 0px;
            padding: 20px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-db72c16 {
            width: auto;
            max-width: auto;
        }

        .elementor-2148 .elementor-element.elementor-element-db72c16.elementor-element {
            --flex-grow: 1;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-8d5eb45 .elementor-button {
            fill: #FFFFFF;
            color: #FFFFFF;
            background-color: #FF3F3F;
        }

        .elementor-2148 .elementor-element.elementor-element-8d5eb45 .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-8d5eb45 .elementor-button:focus {
            background-color: #FF3131;
        }

        .elementor-2148 .elementor-element.elementor-element-8d5eb45>.elementor-widget-container {
            margin: 0px 0px 0px 0px;
            padding: 20px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-8d5eb45 {
            width: auto;
            max-width: auto;
        }

        .elementor-2148 .elementor-element.elementor-element-8d5eb45.elementor-element {
            --align-self: center;
            --flex-grow: 1;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-34db5b1 .elementor-button {
            fill: #FFFFFF;
            color: #FFFFFF;
            background-color: #26D637;
        }

        .elementor-2148 .elementor-element.elementor-element-34db5b1 .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-34db5b1 .elementor-button:focus {
            background-color: #28C937;
        }

        .elementor-2148 .elementor-element.elementor-element-34db5b1>.elementor-widget-container {
            margin: 0px 0px 0px 0px;
            padding: 20px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-34db5b1 {
            width: auto;
            max-width: auto;
        }

        .elementor-2148 .elementor-element.elementor-element-34db5b1.elementor-element {
            --flex-grow: 1;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-0324e33 {
            --display: flex;
            --justify-content: center;
            --align-items: center;
            --container-widget-width: calc((1 - var(--container-widget-flex-grow)) * 100%);
            --background-transition: 0.3s;
            --overlay-opacity: 0.81;
            --padding-top: 120px;
            --padding-bottom: 100px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-0324e33:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-0324e33>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-image: url("https://vblocksmithservices.co.uk/wp-content/uploads/2025/02/vecteezy_the-hand-of-the-banker-holds-the-house-key-home-and-land_18974193-scaled.jpg");
            background-position: top center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .elementor-2148 .elementor-element.elementor-element-0324e33::before,
        .elementor-2148 .elementor-element.elementor-element-0324e33>.elementor-background-video-container::before,
        .elementor-2148 .elementor-element.elementor-element-0324e33>.e-con-inner>.elementor-background-video-container::before,
        .elementor-2148 .elementor-element.elementor-element-0324e33>.elementor-background-slideshow::before,
        .elementor-2148 .elementor-element.elementor-element-0324e33>.e-con-inner>.elementor-background-slideshow::before,
        .elementor-2148 .elementor-element.elementor-element-0324e33>.elementor-motion-effects-container>.elementor-motion-effects-layer::before {
            --background-overlay: '';
            background-color: var(--e-global-color-astglobalcolor0);
        }

        .elementor-2148 .elementor-element.elementor-element-0324e33,
        .elementor-2148 .elementor-element.elementor-element-0324e33::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-c2b9463 {
            --display: flex;
            --flex-direction: row;
            --container-widget-width: calc((1 - var(--container-widget-flex-grow)) * 100%);
            --container-widget-height: 100%;
            --container-widget-flex-grow: 1;
            --container-widget-align-self: stretch;
            --flex-wrap-mobile: wrap;
            --align-items: stretch;
            --gap: 0px 0px;
            --background-transition: 0.3s;
            --margin-top: 0px;
            --margin-bottom: 0px;
            --margin-left: 0px;
            --margin-right: 0px;
            --padding-top: 0px;
            --padding-bottom: 0px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-c2b9463.e-con {
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-88d74a9 {
            --display: flex;
            --background-transition: 0.3s;
            --margin-top: 0px;
            --margin-bottom: 0px;
            --margin-left: 0px;
            --margin-right: 0px;
            --padding-top: 0px;
            --padding-bottom: 0px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-594a0ca {
            --display: flex;
            --flex-direction: row;
            --container-widget-width: initial;
            --container-widget-height: 100%;
            --container-widget-flex-grow: 1;
            --container-widget-align-self: stretch;
            --flex-wrap-mobile: wrap;
            --background-transition: 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child) {
            padding-bottom: calc(13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child) {
            margin-top: calc(13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item {
            margin-right: calc(13px/2);
            margin-left: calc(13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-items.elementor-inline-items {
            margin-right: calc(-13px/2);
            margin-left: calc(-13px/2);
        }

        body.rtl .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            left: calc(-13px/2);
        }

        body:not(.rtl) .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            right: calc(-13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-icon i {
            color: #212D45;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-icon svg {
            fill: #212D45;
            transition: fill 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701 {
            --e-icon-list-icon-size: 32px;
            --icon-vertical-offset: 0px;
            width: var(--container-widget-width, 50.611%);
            max-width: 50.611%;
            --container-widget-width: 50.611%;
            --container-widget-flex-grow: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-icon {
            padding-right: 10px;
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-item>.elementor-icon-list-text,
        .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-item>a {
            font-size: 17px;
            font-weight: bold;
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701 .elementor-icon-list-text {
            color: #212D45;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701>.elementor-widget-container {
            margin: 10px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-c8a6701.elementor-element {
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child) {
            padding-bottom: calc(13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child) {
            margin-top: calc(13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item {
            margin-right: calc(13px/2);
            margin-left: calc(13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-items.elementor-inline-items {
            margin-right: calc(-13px/2);
            margin-left: calc(-13px/2);
        }

        body.rtl .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            left: calc(-13px/2);
        }

        body:not(.rtl) .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            right: calc(-13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-icon i {
            color: #212D45;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-icon svg {
            fill: #212D45;
            transition: fill 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02 {
            --e-icon-list-icon-size: 32px;
            --icon-vertical-offset: 0px;
            width: var(--container-widget-width, 46.242%);
            max-width: 46.242%;
            --container-widget-width: 46.242%;
            --container-widget-flex-grow: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-icon {
            padding-right: 10px;
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-item>.elementor-icon-list-text,
        .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-item>a {
            font-size: 17px;
            font-weight: bold;
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02 .elementor-icon-list-text {
            color: #212D45;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02>.elementor-widget-container {
            margin: 10px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-ffc3e02.elementor-element {
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child) {
            padding-bottom: calc(13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child) {
            margin-top: calc(13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item {
            margin-right: calc(13px/2);
            margin-left: calc(13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-items.elementor-inline-items {
            margin-right: calc(-13px/2);
            margin-left: calc(-13px/2);
        }

        body.rtl .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            left: calc(-13px/2);
        }

        body:not(.rtl) .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            right: calc(-13px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-icon i {
            color: #212D45;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-icon svg {
            fill: #212D45;
            transition: fill 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-e2416b5 {
            --e-icon-list-icon-size: 32px;
            --icon-vertical-offset: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-icon {
            padding-right: 10px;
        }

        .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-item>.elementor-icon-list-text,
        .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-item>a {
            font-size: 17px;
            font-weight: bold;
        }

        .elementor-2148 .elementor-element.elementor-element-e2416b5 .elementor-icon-list-text {
            color: #212D45;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-e2416b5>.elementor-widget-container {
            margin: 10px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-69c9d80>.elementor-widget-container {
            padding: 10px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-69c9d80 {
            width: var(--container-widget-width, 52.998%);
            max-width: 52.998%;
            --container-widget-width: 52.998%;
            --container-widget-flex-grow: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-69c9d80.elementor-element {
            --align-self: stretch;
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-36d4e3d {
            --display: flex;
            --flex-direction: column;
            --container-widget-width: 100%;
            --container-widget-height: initial;
            --container-widget-flex-grow: 0;
            --container-widget-align-self: initial;
            --flex-wrap-mobile: wrap;
            --background-transition: 0.3s;
            --padding-top: 50px;
            --padding-bottom: 50px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-36d4e3d:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-36d4e3d>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #FFFFFF;
        }

        .elementor-2148 .elementor-element.elementor-element-36d4e3d,
        .elementor-2148 .elementor-element.elementor-element-36d4e3d::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-9e2e2d5 {
            text-align: left;
        }

        .elementor-2148 .elementor-element.elementor-element-afabd9e {
            --display: grid;
            --e-con-grid-template-columns: repeat(3, 1fr);
            --e-con-grid-template-rows: repeat(1, 1fr);
            --grid-auto-flow: row;
            --background-transition: 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-2980aea.elementor-position-right .elementor-image-box-img {
            margin-left: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-2980aea.elementor-position-left .elementor-image-box-img {
            margin-right: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-2980aea.elementor-position-top .elementor-image-box-img {
            margin-bottom: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-2980aea .elementor-image-box-wrapper .elementor-image-box-img {
            width: 30%;
        }

        .elementor-2148 .elementor-element.elementor-element-2980aea .elementor-image-box-img img {
            transition-duration: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-30d7f54.elementor-position-right .elementor-image-box-img {
            margin-left: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-30d7f54.elementor-position-left .elementor-image-box-img {
            margin-right: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-30d7f54.elementor-position-top .elementor-image-box-img {
            margin-bottom: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-30d7f54 .elementor-image-box-wrapper .elementor-image-box-img {
            width: 30%;
        }

        .elementor-2148 .elementor-element.elementor-element-30d7f54 .elementor-image-box-img img {
            transition-duration: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-a9b30ca.elementor-position-right .elementor-image-box-img {
            margin-left: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-a9b30ca.elementor-position-left .elementor-image-box-img {
            margin-right: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-a9b30ca.elementor-position-top .elementor-image-box-img {
            margin-bottom: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-a9b30ca .elementor-image-box-wrapper .elementor-image-box-img {
            width: 30%;
        }

        .elementor-2148 .elementor-element.elementor-element-a9b30ca .elementor-image-box-img img {
            transition-duration: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-75b4fb0 .elementor-button {
            fill: #FFFFFF;
            color: #FFFFFF;
            background-color: #26D637;
        }

        .elementor-2148 .elementor-element.elementor-element-75b4fb0 .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-75b4fb0 .elementor-button:focus {
            background-color: #28C937;
        }

        .elementor-2148 .elementor-element.elementor-element-75b4fb0>.elementor-widget-container {
            margin: 0px 0px 0px 0px;
            padding: 0px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-75b4fb0 {
            width: var(--container-widget-width, 766.998px);
            max-width: 766.998px;
            --container-widget-width: 766.998px;
            --container-widget-flex-grow: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-75b4fb0.elementor-element {
            --align-self: center;
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-73d13e5 {
            --display: flex;
            --flex-direction: column;
            --container-widget-width: 100%;
            --container-widget-height: initial;
            --container-widget-flex-grow: 0;
            --container-widget-align-self: initial;
            --flex-wrap-mobile: wrap;
            --background-transition: 0.3s;
            --padding-top: 100px;
            --padding-bottom: 0px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-73d13e5:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-73d13e5>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #F5F5F5;
        }

        .elementor-2148 .elementor-element.elementor-element-73d13e5,
        .elementor-2148 .elementor-element.elementor-element-73d13e5::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-0ef19be {
            text-align: left;
        }

        .elementor-2148 .elementor-element.elementor-element-a33e58a>.elementor-widget-container {
            padding: 0px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-43438f7 {
            --display: flex;
            --flex-direction: row;
            --container-widget-width: calc((1 - var(--container-widget-flex-grow)) * 100%);
            --container-widget-height: 100%;
            --container-widget-flex-grow: 1;
            --container-widget-align-self: stretch;
            --flex-wrap-mobile: wrap;
            --align-items: stretch;
            --gap: 0px 0px;
            --background-transition: 0.3s;
            --margin-top: 0px;
            --margin-bottom: 0px;
            --margin-left: 0px;
            --margin-right: 0px;
            --padding-top: 50px;
            --padding-bottom: 50px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-43438f7:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-43438f7>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: transparent;
            background-image: linear-gradient(90deg, #F5F5F5 50%, var(--e-global-color-astglobalcolor5) 50%);
        }

        .elementor-2148 .elementor-element.elementor-element-43438f7,
        .elementor-2148 .elementor-element.elementor-element-43438f7::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-50fcbe7d {
            --display: flex;
            --gap: 0px 0px;
            --background-transition: 0.3s;
            --margin-top: 0px;
            --margin-bottom: 0px;
            --margin-left: 0px;
            --margin-right: 0px;
            --padding-top: 0px;
            --padding-bottom: 0px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-b04e83e {
            text-align: left;
        }

        .elementor-2148 .elementor-element.elementor-element-b04e83e>.elementor-widget-container {
            padding: 0px 0px 20px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-158bf17>.elementor-widget-container {
            padding: 0px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-05f84df {
            --display: grid;
            --e-con-grid-template-columns: repeat(3, 1fr);
            --e-con-grid-template-rows: repeat(2, 1fr);
            --grid-auto-flow: row;
            --background-transition: 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-9d0f823 {
            --icon-box-icon-margin: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-9d0f823.elementor-view-stacked .elementor-icon {
            background-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-9d0f823.elementor-view-framed .elementor-icon,
        .elementor-2148 .elementor-element.elementor-element-9d0f823.elementor-view-default .elementor-icon {
            fill: #202D45;
            color: #202D45;
            border-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-252b28b {
            --icon-box-icon-margin: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-252b28b.elementor-view-stacked .elementor-icon {
            background-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-252b28b.elementor-view-framed .elementor-icon,
        .elementor-2148 .elementor-element.elementor-element-252b28b.elementor-view-default .elementor-icon {
            fill: #202D45;
            color: #202D45;
            border-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-65ee4b8 {
            --icon-box-icon-margin: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-65ee4b8.elementor-view-stacked .elementor-icon {
            background-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-65ee4b8.elementor-view-framed .elementor-icon,
        .elementor-2148 .elementor-element.elementor-element-65ee4b8.elementor-view-default .elementor-icon {
            fill: #202D45;
            color: #202D45;
            border-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-e9a23e6 {
            --icon-box-icon-margin: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-e9a23e6.elementor-view-stacked .elementor-icon {
            background-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-e9a23e6.elementor-view-framed .elementor-icon,
        .elementor-2148 .elementor-element.elementor-element-e9a23e6.elementor-view-default .elementor-icon {
            fill: #202D45;
            color: #202D45;
            border-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-359c552 {
            --icon-box-icon-margin: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-359c552.elementor-view-stacked .elementor-icon {
            background-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-359c552.elementor-view-framed .elementor-icon,
        .elementor-2148 .elementor-element.elementor-element-359c552.elementor-view-default .elementor-icon {
            fill: #202D45;
            color: #202D45;
            border-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-da772f2 {
            --icon-box-icon-margin: 15px;
        }

        .elementor-2148 .elementor-element.elementor-element-da772f2.elementor-view-stacked .elementor-icon {
            background-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-da772f2.elementor-view-framed .elementor-icon,
        .elementor-2148 .elementor-element.elementor-element-da772f2.elementor-view-default .elementor-icon {
            fill: #202D45;
            color: #202D45;
            border-color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-93befe1 {
            --display: flex;
            --flex-direction: column;
            --container-widget-width: 100%;
            --container-widget-height: initial;
            --container-widget-flex-grow: 0;
            --container-widget-align-self: initial;
            --flex-wrap-mobile: wrap;
            --background-transition: 0.3s;
            --padding-top: 50px;
            --padding-bottom: 0px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-93befe1:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-93befe1>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #FFFFFF;
        }

        .elementor-2148 .elementor-element.elementor-element-93befe1,
        .elementor-2148 .elementor-element.elementor-element-93befe1::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-14a4655 {
            text-align: left;
        }

        .elementor-2148 .elementor-element.elementor-element-6c92cce {
            --display: flex;
            --flex-direction: row;
            --container-widget-width: initial;
            --container-widget-height: 100%;
            --container-widget-flex-grow: 1;
            --container-widget-align-self: stretch;
            --flex-wrap-mobile: wrap;
            --gap: 0px 0px;
            --background-transition: 0.3s;
            --padding-top: 0px;
            --padding-bottom: 100px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-6c92cce:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-6c92cce>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #FFFFFF;
        }

        .elementor-2148 .elementor-element.elementor-element-6c92cce,
        .elementor-2148 .elementor-element.elementor-element-6c92cce::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-1866860 {
            --display: flex;
            --flex-direction: column;
            --container-widget-width: 100%;
            --container-widget-height: initial;
            --container-widget-flex-grow: 0;
            --container-widget-align-self: initial;
            --flex-wrap-mobile: wrap;
            --background-transition: 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-1866860.e-con {
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-311dfd8 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child) {
            padding-bottom: calc(3px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-311dfd8 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child) {
            margin-top: calc(3px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-311dfd8 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item {
            margin-right: calc(3px/2);
            margin-left: calc(3px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-311dfd8 .elementor-icon-list-items.elementor-inline-items {
            margin-right: calc(-3px/2);
            margin-left: calc(-3px/2);
        }

        body.rtl .elementor-2148 .elementor-element.elementor-element-311dfd8 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            left: calc(-3px/2);
        }

        body:not(.rtl) .elementor-2148 .elementor-element.elementor-element-311dfd8 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            right: calc(-3px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-311dfd8 .elementor-icon-list-icon i {
            color: #212D45;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-311dfd8 .elementor-icon-list-icon svg {
            fill: #212D45;
            transition: fill 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-311dfd8 {
            --e-icon-list-icon-size: 25px;
            --icon-vertical-offset: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-311dfd8 .elementor-icon-list-text {
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-04c7304 {
            text-align: left;
        }

        .elementor-2148 .elementor-element.elementor-element-555d22c>.elementor-widget-container {
            padding: 0px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-ecb3939 {
            --display: flex;
            --flex-direction: column;
            --container-widget-width: 100%;
            --container-widget-height: initial;
            --container-widget-flex-grow: 0;
            --container-widget-align-self: initial;
            --flex-wrap-mobile: wrap;
            --background-transition: 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-c4457b2 {
            --display: flex;
            --flex-direction: row;
            --container-widget-width: initial;
            --container-widget-height: 100%;
            --container-widget-flex-grow: 1;
            --container-widget-align-self: stretch;
            --flex-wrap-mobile: wrap;
            --gap: 0px 0px;
            --background-transition: 0.3s;
            --padding-top: 50px;
            --padding-bottom: 50px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-c4457b2:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-c4457b2>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-c4457b2,
        .elementor-2148 .elementor-element.elementor-element-c4457b2::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-3b5aa8ac {
            --display: flex;
            --flex-direction: column;
            --container-widget-width: 100%;
            --container-widget-height: initial;
            --container-widget-flex-grow: 0;
            --container-widget-align-self: initial;
            --flex-wrap-mobile: wrap;
            --background-transition: 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-3b5aa8ac.e-con {
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-2148 .elementor-element.elementor-element-16a6aa04 .elementor-heading-title {
            color: #202D45;
            font-family: "Montserrat", Sans-serif;
            font-size: 28px;
            font-weight: 700;
        }

        .elementor-2148 .elementor-element.elementor-element-16a6aa04>.elementor-widget-container {
            padding: 32px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-548934b7 .elementor-icon-list-icon i {
            color: #202D45;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-548934b7 .elementor-icon-list-icon svg {
            fill: #202D45;
            transition: fill 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-548934b7 {
            --e-icon-list-icon-size: 50px;
            --icon-vertical-offset: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-548934b7 .elementor-icon-list-item>.elementor-icon-list-text,
        .elementor-2148 .elementor-element.elementor-element-548934b7 .elementor-icon-list-item>a {
            font-family: "Montserrat", Sans-serif;
            font-size: 40px;
            font-weight: 700;
        }

        .elementor-2148 .elementor-element.elementor-element-548934b7 .elementor-icon-list-text {
            text-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            color: #FFFFFF;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-548934b7 .elementor-icon-list-item:hover .elementor-icon-list-text {
            color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-548934b7>.elementor-widget-container {
            padding: 0px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-4d44c8fe {
            --display: grid;
            --e-con-grid-template-columns: repeat(3, 1fr);
            --e-con-grid-template-rows: repeat(2, 1fr);
            --grid-auto-flow: row;
            --background-transition: 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-3755202 {
            --icon-box-icon-margin: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-3755202.elementor-view-stacked .elementor-icon {
            background-color: #FE4619;
        }

        .elementor-2148 .elementor-element.elementor-element-3755202.elementor-view-framed .elementor-icon,
        .elementor-2148 .elementor-element.elementor-element-3755202.elementor-view-default .elementor-icon {
            fill: #FE4619;
            color: #FE4619;
            border-color: #FE4619;
        }

        .elementor-2148 .elementor-element.elementor-element-3755202 .elementor-icon {
            font-size: 130px;
        }

        .elementor-2148 .elementor-element.elementor-element-3755202 .elementor-icon-box-title {
            color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-3755202 .elementor-icon-box-title,
        .elementor-2148 .elementor-element.elementor-element-3755202 .elementor-icon-box-title a {
            font-family: "Montserrat", Sans-serif;
            font-weight: 700;
        }

        .elementor-2148 .elementor-element.elementor-element-3755202 .elementor-icon-box-description {
            color: #202D45;
            font-family: "Montserrat", Sans-serif;
            font-weight: 700;
        }

        .elementor-2148 .elementor-element.elementor-element-3755202>.elementor-widget-container {
            padding: 10px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-5216cea7 {
            --display: flex;
            --flex-direction: column;
            --container-widget-width: 100%;
            --container-widget-height: initial;
            --container-widget-flex-grow: 0;
            --container-widget-align-self: initial;
            --flex-wrap-mobile: wrap;
            --background-transition: 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-698effd2 {
            --display: grid;
            --e-con-grid-template-columns: repeat(3, 1fr);
            --e-con-grid-template-rows: repeat(1, 1fr);
            --grid-auto-flow: row;
            --background-transition: 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-7282101c {
            --icon-box-icon-margin: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-7282101c.elementor-view-stacked .elementor-icon {
            background-color: #FE4619;
        }

        .elementor-2148 .elementor-element.elementor-element-7282101c.elementor-view-framed .elementor-icon,
        .elementor-2148 .elementor-element.elementor-element-7282101c.elementor-view-default .elementor-icon {
            fill: #FE4619;
            color: #FE4619;
            border-color: #FE4619;
        }

        .elementor-2148 .elementor-element.elementor-element-7282101c .elementor-icon {
            font-size: 130px;
        }

        .elementor-2148 .elementor-element.elementor-element-7282101c .elementor-icon-box-title {
            color: #202D45;
        }

        .elementor-2148 .elementor-element.elementor-element-7282101c .elementor-icon-box-title,
        .elementor-2148 .elementor-element.elementor-element-7282101c .elementor-icon-box-title a {
            font-family: "Montserrat", Sans-serif;
            font-weight: 700;
        }

        .elementor-2148 .elementor-element.elementor-element-7282101c .elementor-icon-box-description {
            color: #202D45;
            font-family: "Montserrat", Sans-serif;
            font-weight: 700;
        }

        .elementor-2148 .elementor-element.elementor-element-7282101c>.elementor-widget-container {
            padding: 10px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-36b44d0 {
            --display: flex;
            --flex-direction: column;
            --container-widget-width: 100%;
            --container-widget-height: initial;
            --container-widget-flex-grow: 0;
            --container-widget-align-self: initial;
            --flex-wrap-mobile: wrap;
            --background-transition: 0.3s;
            --padding-top: 100px;
            --padding-bottom: 100px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-b7b1164 .elementor-heading-title {
            font-family: "Montserrat", Sans-serif;
            font-size: 24px;
            font-weight: 700;
        }

        .elementor-2148 .elementor-element.elementor-element-f061e3e .elementor-heading-title {
            font-family: "Montserrat", Sans-serif;
        }

        .elementor-2148 .elementor-element.elementor-element-f061e3e>.elementor-widget-container {
            padding: 0px 0px 20px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-8f7b2ae {
            --display: flex;
            --flex-direction: column;
            --container-widget-width: 100%;
            --container-widget-height: initial;
            --container-widget-flex-grow: 0;
            --container-widget-align-self: initial;
            --flex-wrap-mobile: wrap;
            --background-transition: 0.3s;
            --padding-top: 100px;
            --padding-bottom: 190px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-8f7b2ae:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-8f7b2ae>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #FFFFFF;
        }

        .elementor-2148 .elementor-element.elementor-element-8f7b2ae,
        .elementor-2148 .elementor-element.elementor-element-8f7b2ae::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-a4c917b {
            text-align: left;
        }

        .elementor-2148 .elementor-element.elementor-element-cdba9f5 {
            --display: grid;
            --e-con-grid-template-columns: repeat(3, 1fr);
            --e-con-grid-template-rows: repeat(4, 1fr);
            --grid-auto-flow: row;
            --background-transition: 0.3s;
            --margin-top: 0px;
            --margin-bottom: 0px;
            --margin-left: 0px;
            --margin-right: 0px;
            --padding-top: 30px;
            --padding-bottom: 0px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-cdba9f5.e-con {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-42f8c6a .elementor-button {
            background-color: #F5F5F5;
            border-style: none;
            border-radius: 0px 0px 0px 0px;
            box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .elementor-2148 .elementor-element.elementor-element-42f8c6a .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-42f8c6a .elementor-button:focus {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-42f8c6a.elementor-element {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-b7133b3 .elementor-button {
            background-color: #F5F5F5;
            border-radius: 0px 0px 0px 0px;
            box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .elementor-2148 .elementor-element.elementor-element-b7133b3 .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-b7133b3 .elementor-button:focus {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-b7133b3.elementor-element {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-d115af2 .elementor-button {
            background-color: #F5F5F5;
            border-radius: 0px 0px 0px 0px;
            box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .elementor-2148 .elementor-element.elementor-element-d115af2 .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-d115af2 .elementor-button:focus {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-d115af2.elementor-element {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-54dde71 .elementor-button {
            background-color: #F5F5F5;
            border-radius: 0px 0px 0px 0px;
            box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .elementor-2148 .elementor-element.elementor-element-54dde71 .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-54dde71 .elementor-button:focus {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-54dde71.elementor-element {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-0b9990a .elementor-button {
            background-color: #F5F5F5;
            border-radius: 0px 0px 0px 0px;
            box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .elementor-2148 .elementor-element.elementor-element-0b9990a .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-0b9990a .elementor-button:focus {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-0b9990a.elementor-element {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-dbf7f3a .elementor-button {
            background-color: #F5F5F5;
            border-radius: 0px 0px 0px 0px;
            box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .elementor-2148 .elementor-element.elementor-element-dbf7f3a .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-dbf7f3a .elementor-button:focus {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-dbf7f3a.elementor-element {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-4fe084e .elementor-button {
            background-color: #F5F5F5;
            border-radius: 0px 0px 0px 0px;
            box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .elementor-2148 .elementor-element.elementor-element-4fe084e .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-4fe084e .elementor-button:focus {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-4fe084e.elementor-element {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-f427184 .elementor-button {
            background-color: #F5F5F5;
            border-radius: 0px 0px 0px 0px;
            box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .elementor-2148 .elementor-element.elementor-element-f427184 .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-f427184 .elementor-button:focus {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-f427184.elementor-element {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-9bd6644 .elementor-button {
            background-color: #F5F5F5;
            border-radius: 0px 0px 0px 0px;
            box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .elementor-2148 .elementor-element.elementor-element-9bd6644 .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-9bd6644 .elementor-button:focus {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-9bd6644.elementor-element {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-d2fcac8 .elementor-button {
            background-color: #F5F5F5;
            border-radius: 0px 0px 0px 0px;
            box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .elementor-2148 .elementor-element.elementor-element-d2fcac8 .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-d2fcac8 .elementor-button:focus {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-d2fcac8.elementor-element {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-26173fe .elementor-button {
            background-color: #F5F5F5;
            border-radius: 0px 0px 0px 0px;
            box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .elementor-2148 .elementor-element.elementor-element-26173fe .elementor-button:hover,
        .elementor-2148 .elementor-element.elementor-element-26173fe .elementor-button:focus {
            background-color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-26173fe.elementor-element {
            --align-self: center;
        }

        .elementor-2148 .elementor-element.elementor-element-d1bcf85 {
            --display: flex;
            --flex-direction: row;
            --container-widget-width: calc((1 - var(--container-widget-flex-grow)) * 100%);
            --container-widget-height: 100%;
            --container-widget-flex-grow: 1;
            --container-widget-align-self: stretch;
            --flex-wrap-mobile: wrap;
            --align-items: stretch;
            --gap: 0px 0px;
            --background-transition: 0.3s;
            --margin-top: 0px;
            --margin-bottom: 0px;
            --margin-left: 0px;
            --margin-right: 0px;
            --padding-top: 0px;
            --padding-bottom: 150px;
            --padding-left: 0px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-d1bcf85:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-d1bcf85>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #F5F5F5;
        }

        .elementor-2148 .elementor-element.elementor-element-d1bcf85,
        .elementor-2148 .elementor-element.elementor-element-d1bcf85::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-eb6492d {
            --display: flex;
            --background-transition: 0.3s;
            --margin-top: -55px;
            --margin-bottom: -55px;
            --margin-left: 0px;
            --margin-right: 0px;
            --padding-top: 64px;
            --padding-bottom: 64px;
            --padding-left: 64px;
            --padding-right: 64px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-eb6492d:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-eb6492d>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: var(--e-global-color-astglobalcolor2);
        }

        .elementor-2148 .elementor-element.elementor-element-eb6492d,
        .elementor-2148 .elementor-element.elementor-element-eb6492d::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-37df7c4 .elementor-heading-title {
            color: #FFFFFF;
        }

        .elementor-2148 .elementor-element.elementor-element-dceeccb {
            color: #FFFFFF;
        }

        .elementor-2148 .elementor-element.elementor-element-c058f8b>.elementor-widget-container {
            margin: 0px 0px -20px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-5071c58 {
            --display: flex;
            --justify-content: center;
            --background-transition: 0.3s;
            --padding-top: 0px;
            --padding-bottom: 0px;
            --padding-left: 100px;
            --padding-right: 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-5071c58,
        .elementor-2148 .elementor-element.elementor-element-5071c58::before {
            --border-transition: 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-9c706de .elementor-heading-title {
            color: var(--e-global-color-astglobalcolor0);
            font-family: "Montserrat", Sans-serif;
            font-size: 24px;
            font-weight: 400;
        }

        .elementor-2148 .elementor-element.elementor-element-9c706de>.elementor-widget-container {
            padding: 20px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-17cf2b6 .elementor-tab-title {
            background-color: var(--e-global-color-astglobalcolor4);
            padding: 20px 20px 20px 20px;
        }

        .elementor-2148 .elementor-element.elementor-element-17cf2b6 .elementor-accordion-icon,
        .elementor-2148 .elementor-element.elementor-element-17cf2b6 .elementor-accordion-title {
            color: var(--e-global-color-astglobalcolor3);
        }

        .elementor-2148 .elementor-element.elementor-element-17cf2b6 .elementor-accordion-icon svg {
            fill: var(--e-global-color-astglobalcolor3);
        }

        .elementor-2148 .elementor-element.elementor-element-17cf2b6 .elementor-active .elementor-accordion-icon,
        .elementor-2148 .elementor-element.elementor-element-17cf2b6 .elementor-active .elementor-accordion-title {
            color: var(--e-global-color-astglobalcolor2);
        }

        .elementor-2148 .elementor-element.elementor-element-17cf2b6 .elementor-active .elementor-accordion-icon svg {
            fill: var(--e-global-color-astglobalcolor2);
        }

        .elementor-2148 .elementor-element.elementor-element-17cf2b6 .elementor-accordion-icon.elementor-accordion-icon-left {
            margin-right: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-17cf2b6 .elementor-accordion-icon.elementor-accordion-icon-right {
            margin-left: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-17cf2b6>.elementor-widget-container {
            padding: 20px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-a606283:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-a606283>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #0E1727;
        }

        .elementor-2148 .elementor-element.elementor-element-a606283 {
            border-style: solid;
            border-width: 10px 0px 0px 0px;
            border-color: var(--e-global-color-primary);
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            padding: 30px 0px 30px 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-a606283>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-da36 img {
            width: 100%;
        }

        .elementor-bc-flex-widget .elementor-2148 .elementor-element.elementor-element-567c67d6.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-567c67d6.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-567c67d6>.elementor-element-populated {
            padding: 30px 30px 30px 30px;
        }

        .elementor-2148 .elementor-element.elementor-element-451c6838 {
            color: #B4B4B4;
        }

        .elementor-2148 .elementor-element.elementor-element-451c6838>.elementor-widget-container {
            margin: 0px 0px -20px 0px;
        }

        .elementor-bc-flex-widget .elementor-2148 .elementor-element.elementor-element-27a85aa8.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-27a85aa8.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-27a85aa8>.elementor-element-populated {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            padding: 0px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-27a85aa8>.elementor-element-populated>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-6f6adf5d .uael-infobox,
        .elementor-2148 .elementor-element.elementor-element-6f6adf5d .uael-separator-parent {
            text-align: center;
        }

        .elementor-2148 .elementor-element.elementor-element-6f6adf5d .uael-icon-wrap .uael-icon i {
            font-size: 60px;
            height: 60px;
            width: 60px;
            line-height: 60px;
            text-align: center;
            color: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-6f6adf5d .uael-icon-wrap .uael-icon {
            height: 60px;
            width: 60px;
            line-height: 60px;
        }

        .elementor-2148 .elementor-element.elementor-element-6f6adf5d .uael-icon-wrap .uael-icon i,
        .elementor-2148 .elementor-element.elementor-element-6f6adf5d .uael-icon-wrap .uael-icon svg {
            transform: rotate(0deg);
        }

        .elementor-2148 .elementor-element.elementor-element-6f6adf5d .uael-icon-wrap .uael-icon svg {
            fill: #FEC03D;
        }

        .elementor-2148 .elementor-element.elementor-element-6f6adf5d .uael-infobox-title {
            font-weight: bold;
            color: #B4B4B4;
            margin: 0px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-d69c03f:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-d69c03f>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #0E1727;
        }

        .elementor-2148 .elementor-element.elementor-element-d69c03f {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            padding: 0px 0px 70px 0px;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-d69c03f>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-505de0ad .elementor-heading-title {
            color: #FFFFFF;
            font-size: 20px;
            font-weight: bold;
        }

        .elementor-2148 .elementor-element.elementor-element-4ab5ec2b .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child) {
            padding-bottom: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-4ab5ec2b .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child) {
            margin-top: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-4ab5ec2b .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item {
            margin-right: calc(10px/2);
            margin-left: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-4ab5ec2b .elementor-icon-list-items.elementor-inline-items {
            margin-right: calc(-10px/2);
            margin-left: calc(-10px/2);
        }

        body.rtl .elementor-2148 .elementor-element.elementor-element-4ab5ec2b .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            left: calc(-10px/2);
        }

        body:not(.rtl) .elementor-2148 .elementor-element.elementor-element-4ab5ec2b .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            right: calc(-10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-4ab5ec2b .elementor-icon-list-icon i {
            color: var(--e-global-color-primary);
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-4ab5ec2b .elementor-icon-list-icon svg {
            fill: var(--e-global-color-primary);
            transition: fill 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-4ab5ec2b {
            --e-icon-list-icon-size: 14px;
            --icon-vertical-offset: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-4ab5ec2b .elementor-icon-list-text {
            color: #B4B4B4;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-4ab5ec2b .elementor-icon-list-item:hover .elementor-icon-list-text {
            color: var(--e-global-color-accent);
        }

        .elementor-2148 .elementor-element.elementor-element-4ab5ec2b>.elementor-widget-container {
            margin: 20px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-58e94d34 .elementor-heading-title {
            color: #FFFFFF;
            font-size: 20px;
            font-weight: bold;
        }

        .elementor-2148 .elementor-element.elementor-element-70cc719 {
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-77fbd2bc .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child) {
            padding-bottom: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-77fbd2bc .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child) {
            margin-top: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-77fbd2bc .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item {
            margin-right: calc(10px/2);
            margin-left: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-77fbd2bc .elementor-icon-list-items.elementor-inline-items {
            margin-right: calc(-10px/2);
            margin-left: calc(-10px/2);
        }

        body.rtl .elementor-2148 .elementor-element.elementor-element-77fbd2bc .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            left: calc(-10px/2);
        }

        body:not(.rtl) .elementor-2148 .elementor-element.elementor-element-77fbd2bc .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            right: calc(-10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-77fbd2bc .elementor-icon-list-icon i {
            color: var(--e-global-color-primary);
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-77fbd2bc .elementor-icon-list-icon svg {
            fill: var(--e-global-color-primary);
            transition: fill 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-77fbd2bc {
            --e-icon-list-icon-size: 14px;
            --icon-vertical-offset: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-77fbd2bc .elementor-icon-list-text {
            color: #B4B4B4;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-77fbd2bc .elementor-icon-list-item:hover .elementor-icon-list-text {
            color: var(--e-global-color-accent);
        }

        .elementor-2148 .elementor-element.elementor-element-77fbd2bc>.elementor-widget-container {
            margin: 20px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-6d2c566d .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child) {
            padding-bottom: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-6d2c566d .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child) {
            margin-top: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-6d2c566d .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item {
            margin-right: calc(10px/2);
            margin-left: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-6d2c566d .elementor-icon-list-items.elementor-inline-items {
            margin-right: calc(-10px/2);
            margin-left: calc(-10px/2);
        }

        body.rtl .elementor-2148 .elementor-element.elementor-element-6d2c566d .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            left: calc(-10px/2);
        }

        body:not(.rtl) .elementor-2148 .elementor-element.elementor-element-6d2c566d .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            right: calc(-10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-6d2c566d .elementor-icon-list-icon i {
            color: var(--e-global-color-primary);
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-6d2c566d .elementor-icon-list-icon svg {
            fill: var(--e-global-color-primary);
            transition: fill 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-6d2c566d {
            --e-icon-list-icon-size: 14px;
            --icon-vertical-offset: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-6d2c566d .elementor-icon-list-text {
            color: #B4B4B4;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-6d2c566d .elementor-icon-list-item:hover .elementor-icon-list-text {
            color: var(--e-global-color-accent);
        }

        .elementor-2148 .elementor-element.elementor-element-6d2c566d>.elementor-widget-container {
            margin: 20px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-1c1a5ac7 .elementor-heading-title {
            color: #FFFFFF;
            font-size: 20px;
            font-weight: bold;
        }

        .elementor-2148 .elementor-element.elementor-element-74c09cc3 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child) {
            padding-bottom: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-74c09cc3 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child) {
            margin-top: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-74c09cc3 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item {
            margin-right: calc(10px/2);
            margin-left: calc(10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-74c09cc3 .elementor-icon-list-items.elementor-inline-items {
            margin-right: calc(-10px/2);
            margin-left: calc(-10px/2);
        }

        body.rtl .elementor-2148 .elementor-element.elementor-element-74c09cc3 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            left: calc(-10px/2);
        }

        body:not(.rtl) .elementor-2148 .elementor-element.elementor-element-74c09cc3 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after {
            right: calc(-10px/2);
        }

        .elementor-2148 .elementor-element.elementor-element-74c09cc3 .elementor-icon-list-icon i {
            color: var(--e-global-color-primary);
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-74c09cc3 .elementor-icon-list-icon svg {
            fill: var(--e-global-color-primary);
            transition: fill 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-74c09cc3 {
            --e-icon-list-icon-size: 14px;
            --icon-vertical-offset: 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-74c09cc3 .elementor-icon-list-text {
            color: #B4B4B4;
            transition: color 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-74c09cc3 .elementor-icon-list-item:hover .elementor-icon-list-text {
            color: var(--e-global-color-accent);
        }

        .elementor-2148 .elementor-element.elementor-element-74c09cc3>.elementor-widget-container {
            margin: 20px 0px 0px 0px;
        }

        .elementor-bc-flex-widget .elementor-2148 .elementor-element.elementor-element-4651c7e.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-4651c7e.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-3178fcb9 {
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-2a8fec4e img {
            border-radius: 10px 10px 10px 10px;
        }

        .elementor-bc-flex-widget .elementor-2148 .elementor-element.elementor-element-2b115da0.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-2b115da0.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-2148 .elementor-element.elementor-element-7df1c685 {
            text-align: right;
        }

        .elementor-2148 .elementor-element.elementor-element-7df1c685 img {
            width: 100%;
        }

        .elementor-2148 .elementor-element.elementor-element-6b282360 {
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-7a5a7d25 {
            text-align: right;
        }

        .elementor-2148 .elementor-element.elementor-element-7a5a7d25 img {
            width: 100%;
        }

        .elementor-2148 .elementor-element.elementor-element-7a5a7d25>.elementor-widget-container {
            margin: 20px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-5c15aef3 {
            text-align: right;
        }

        .elementor-2148 .elementor-element.elementor-element-5c15aef3 img {
            width: 100%;
        }

        .elementor-2148 .elementor-element.elementor-element-5c15aef3>.elementor-widget-container {
            margin: 20px 0px 0px 0px;
        }

        .elementor-2148 .elementor-element.elementor-element-985bae8:not(.elementor-motion-effects-element-type-background),
        .elementor-2148 .elementor-element.elementor-element-985bae8>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: transparent;
            background-image: linear-gradient(158deg, #6EC1E4 0%, var(--e-global-color-accent) 100%);
        }

        .elementor-2148 .elementor-element.elementor-element-985bae8 {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            overflow: visible;
        }

        .elementor-2148 .elementor-element.elementor-element-985bae8>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-2148 .elementor-element.elementor-element-70456bb {
            text-align: center;
            font-weight: bold;
        }

        @media(min-width:768px) {
            .elementor-2148 .elementor-element.elementor-element-3aeb67ef {
                width: 10.948%;
            }

            .elementor-2148 .elementor-element.elementor-element-70268acf {
                width: 80.226%;
            }

            .elementor-2148 .elementor-element.elementor-element-695b0217 {
                width: 8.492%;
            }

            .elementor-2148 .elementor-element.elementor-element-366a195 {
                --width: 54.643%;
            }

            .elementor-2148 .elementor-element.elementor-element-e2ba217 {
                --width: 524.984px;
            }

            .elementor-2148 .elementor-element.elementor-element-0324e33 {
                --width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-c2b9463 {
                --width: 565px;
            }

            .elementor-2148 .elementor-element.elementor-element-1866860 {
                --width: 74.948%;
            }

            .elementor-2148 .elementor-element.elementor-element-ecb3939 {
                --width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-3b5aa8ac {
                --width: 48.035%;
            }

            .elementor-2148 .elementor-element.elementor-element-5216cea7 {
                --width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-eb6492d {
                --width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-5071c58 {
                --width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-b17c7c2 {
                width: 12.923%;
            }

            .elementor-2148 .elementor-element.elementor-element-567c67d6 {
                width: 72.256%;
            }

            .elementor-2148 .elementor-element.elementor-element-27a85aa8 {
                width: 14.487%;
            }

            .elementor-2148 .elementor-element.elementor-element-b8b5825 {
                width: 21%;
            }

            .elementor-2148 .elementor-element.elementor-element-374d2f5a {
                width: 36.845%;
            }

            .elementor-2148 .elementor-element.elementor-element-5700c1c8 {
                width: 43.299%;
            }

            .elementor-2148 .elementor-element.elementor-element-5d88fdad {
                width: 56.699%;
            }

            .elementor-2148 .elementor-element.elementor-element-68af879 {
                width: 17.136%;
            }

            .elementor-2148 .elementor-element.elementor-element-554fb52b {
                width: 40.82%;
            }

            .elementor-2148 .elementor-element.elementor-element-2b115da0 {
                width: 59.18%;
            }

            .elementor-2148 .elementor-element.elementor-element-66a877dd {
                width: 40.82%;
            }

            .elementor-2148 .elementor-element.elementor-element-75a95ea3 {
                width: 59.18%;
            }
        }

        @media(max-width:1024px) and (min-width:768px) {
            .elementor-2148 .elementor-element.elementor-element-3aeb67ef {
                width: 20%;
            }

            .elementor-2148 .elementor-element.elementor-element-70268acf {
                width: 63%;
            }

            .elementor-2148 .elementor-element.elementor-element-695b0217 {
                width: 17%;
            }

            .elementor-2148 .elementor-element.elementor-element-366a195 {
                --width: 100%;
            }

            .elementor-2148 .elementor-element.elementor-element-0324e33 {
                --width: 100%;
            }

            .elementor-2148 .elementor-element.elementor-element-eb6492d {
                --width: 100%;
            }

            .elementor-2148 .elementor-element.elementor-element-5071c58 {
                --width: 100%;
            }

            .elementor-2148 .elementor-element.elementor-element-b17c7c2 {
                width: 100%;
            }

            .elementor-2148 .elementor-element.elementor-element-567c67d6 {
                width: 100%;
            }

            .elementor-2148 .elementor-element.elementor-element-27a85aa8 {
                width: 100%;
            }

            .elementor-2148 .elementor-element.elementor-element-b8b5825 {
                width: 100%;
            }

            .elementor-2148 .elementor-element.elementor-element-374d2f5a {
                width: 100%;
            }

            .elementor-2148 .elementor-element.elementor-element-5700c1c8 {
                width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-5d88fdad {
                width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-68af879 {
                width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-4651c7e {
                width: 50%;
            }
        }

        @media(min-width:1025px) {

            .elementor-2148 .elementor-element.elementor-element-366a195:not(.elementor-motion-effects-element-type-background),
            .elementor-2148 .elementor-element.elementor-element-366a195>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-attachment: scroll;
            }

            .elementor-2148 .elementor-element.elementor-element-0324e33:not(.elementor-motion-effects-element-type-background),
            .elementor-2148 .elementor-element.elementor-element-0324e33>.elementor-motion-effects-container>.elementor-motion-effects-layer {
                background-attachment: scroll;
            }
        }

        @media(max-width:1024px) {
            .elementor-2148 .elementor-element.elementor-element-6b7987d {
                --flex-wrap: wrap;
            }

            .elementor-2148 .elementor-element.elementor-element-366a195 {
                --padding-top: 50px;
                --padding-bottom: 50px;
                --padding-left: 50px;
                --padding-right: 50px;
            }

            .elementor-2148 .elementor-element.elementor-element-0324e33 {
                --padding-top: 50px;
                --padding-bottom: 50px;
                --padding-left: 50px;
                --padding-right: 50px;
            }

            .elementor-2148 .elementor-element.elementor-element-afabd9e {
                --grid-auto-flow: row;
            }

            .elementor-2148 .elementor-element.elementor-element-43438f7 {
                --padding-top: 80px;
                --padding-bottom: 80px;
                --padding-left: 40px;
                --padding-right: 40px;
            }

            .elementor-2148 .elementor-element.elementor-element-05f84df {
                --grid-auto-flow: row;
            }

            .elementor-2148 .elementor-element.elementor-element-4d44c8fe {
                --grid-auto-flow: row;
            }

            .elementor-2148 .elementor-element.elementor-element-698effd2 {
                --grid-auto-flow: row;
            }

            .elementor-2148 .elementor-element.elementor-element-cdba9f5 {
                --grid-auto-flow: row;
            }

            .elementor-2148 .elementor-element.elementor-element-d1bcf85 {
                --flex-wrap: wrap;
                --margin-top: 40px;
                --margin-bottom: 80px;
                --margin-left: 0px;
                --margin-right: 0px;
                --padding-top: 0px;
                --padding-bottom: 0px;
                --padding-left: 0px;
                --padding-right: 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-eb6492d {
                --padding-top: 50px;
                --padding-bottom: 50px;
                --padding-left: 50px;
                --padding-right: 50px;
            }

            .elementor-2148 .elementor-element.elementor-element-5071c58 {
                --padding-top: 100px;
                --padding-bottom: 50px;
                --padding-left: 50px;
                --padding-right: 50px;
            }

            .elementor-bc-flex-widget .elementor-2148 .elementor-element.elementor-element-b17c7c2.elementor-column .elementor-widget-wrap {
                align-items: center;
            }

            .elementor-2148 .elementor-element.elementor-element-b17c7c2.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
                align-content: center;
                align-items: center;
            }

            .elementor-2148 .elementor-element.elementor-element-da36 img {
                width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-6f6adf5d>.elementor-widget-container {
                margin: 20px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-d69c03f {
                padding: 30px 15px 30px 15px;
            }

            .elementor-2148 .elementor-element.elementor-element-7df1c685 {
                text-align: left;
            }
        }

        @media(max-width:767px) {
            .elementor-2148 .elementor-element.elementor-element-3aeb67ef {
                width: 30%;
            }

            .elementor-2148 .elementor-element.elementor-element-3aeb67ef>.elementor-element-populated {
                padding: 25px 0px 25px 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-70268acf {
                width: 70%;
            }

            .elementor-2148 .elementor-element.elementor-element-b265789 {
                --e-icon-list-icon-size: 27px;
                --e-icon-list-icon-align: right;
                --e-icon-list-icon-margin: 0 0 0 calc(var(--e-icon-list-icon-size, 1em) * 0.25);
            }

            .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-item>.elementor-icon-list-text,
            .elementor-2148 .elementor-element.elementor-element-b265789 .elementor-icon-list-item>a {
                font-size: 13px;
            }

            .elementor-2148 .elementor-element.elementor-element-617b151>.elementor-widget-container {
                margin: 20px 0px 0px 0px;
                padding: 0px 0px 20px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-617b151 {
                width: var(--container-widget-width, 235px);
                max-width: 235px;
                --container-widget-width: 235px;
                --container-widget-flex-grow: 0;
            }

            .elementor-2148 .elementor-element.elementor-element-617b151.elementor-element {
                --align-self: flex-start;
                --flex-grow: 1;
                --flex-shrink: 0;
            }

            .elementor-2148 .elementor-element.elementor-element-695b0217>.elementor-element-populated {
                padding: 10px 10px 10px 10px;
            }

            .elementor-2148 .elementor-element.elementor-element-366a195 {
                --padding-top: 55px;
                --padding-bottom: 25px;
                --padding-left: 25px;
                --padding-right: 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-db72c16>.elementor-widget-container {
                margin: 0px 0px 0px 0px;
                padding: 0px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-db72c16.elementor-element {
                --align-self: flex-start;
            }

            .elementor-2148 .elementor-element.elementor-element-8d5eb45>.elementor-widget-container {
                margin: 0px 0px 0px 0px;
                padding: 0px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-8d5eb45.elementor-element {
                --align-self: center;
                --flex-grow: 1;
                --flex-shrink: 0;
            }

            .elementor-2148 .elementor-element.elementor-element-34db5b1>.elementor-widget-container {
                margin: 0px 0px 0px 0px;
                padding: 0px 0px 20px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-34db5b1.elementor-element {
                --align-self: flex-start;
            }

            .elementor-2148 .elementor-element.elementor-element-0324e33 {
                --padding-top: 25px;
                --padding-bottom: 25px;
                --padding-left: 25px;
                --padding-right: 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-69c9d80>.elementor-widget-container {
                margin: 0px 0px 0px 0px;
                padding: 0px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-69c9d80 {
                width: var(--container-widget-width, 346.938px);
                max-width: 346.938px;
                --container-widget-width: 346.938px;
                --container-widget-flex-grow: 0;
            }

            .elementor-2148 .elementor-element.elementor-element-69c9d80.elementor-element {
                --align-self: center;
            }

            .elementor-2148 .elementor-element.elementor-element-36d4e3d {
                --padding-top: 30px;
                --padding-bottom: 30px;
                --padding-left: 25px;
                --padding-right: 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-afabd9e {
                --e-con-grid-template-columns: repeat(1, 1fr);
                --grid-auto-flow: row;
                --justify-items: center;
                --align-items: center;
            }

            .elementor-2148 .elementor-element.elementor-element-2980aea .elementor-image-box-img {
                margin-bottom: 15px;
            }

            .elementor-2148 .elementor-element.elementor-element-30d7f54 .elementor-image-box-img {
                margin-bottom: 15px;
            }

            .elementor-2148 .elementor-element.elementor-element-a9b30ca .elementor-image-box-img {
                margin-bottom: 15px;
            }

            .elementor-2148 .elementor-element.elementor-element-75b4fb0>.elementor-widget-container {
                margin: 0px 0px 0px 0px;
                padding: 0px 0px 20px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-75b4fb0.elementor-element {
                --align-self: center;
                --flex-grow: 1;
                --flex-shrink: 0;
            }

            .elementor-2148 .elementor-element.elementor-element-73d13e5 {
                --padding-top: 30px;
                --padding-bottom: 30px;
                --padding-left: 25px;
                --padding-right: 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-a33e58a>.elementor-widget-container {
                padding: 30px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-43438f7 {
                --padding-top: 50px;
                --padding-bottom: 50px;
                --padding-left: 20px;
                --padding-right: 20px;
            }

            .elementor-2148 .elementor-element.elementor-element-158bf17>.elementor-widget-container {
                padding: 30px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-05f84df {
                --e-con-grid-template-columns: repeat(1, 1fr);
                --grid-auto-flow: row;
            }

            .elementor-2148 .elementor-element.elementor-element-93befe1 {
                --padding-top: 30px;
                --padding-bottom: 30px;
                --padding-left: 25px;
                --padding-right: 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-6c92cce {
                --padding-top: 0px;
                --padding-bottom: 30px;
                --padding-left: 25px;
                --padding-right: 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-555d22c>.elementor-widget-container {
                padding: 30px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-c4457b2 {
                --padding-top: 50px;
                --padding-bottom: 20px;
                --padding-left: 25px;
                --padding-right: 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-3b5aa8ac {
                --margin-top: 0px;
                --margin-bottom: 0px;
                --margin-left: 0px;
                --margin-right: 0px;
                --padding-top: 0px;
                --padding-bottom: 0px;
                --padding-left: 0px;
                --padding-right: 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-16a6aa04 .elementor-heading-title {
                line-height: 1em;
            }

            .elementor-2148 .elementor-element.elementor-element-16a6aa04>.elementor-widget-container {
                margin: 0px 00px 0px 0px;
                padding: 0px 0px 0px 00px;
            }

            .elementor-2148 .elementor-element.elementor-element-548934b7 .elementor-icon-list-item>.elementor-icon-list-text,
            .elementor-2148 .elementor-element.elementor-element-548934b7 .elementor-icon-list-item>a {
                font-size: 30px;
            }

            .elementor-2148 .elementor-element.elementor-element-548934b7>.elementor-widget-container {
                padding: 10px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-4d44c8fe {
                --e-con-grid-template-columns: repeat(3, 1fr);
                --e-con-grid-template-rows: repeat(1, 1fr);
                --grid-auto-flow: row;
            }

            .elementor-2148 .elementor-element.elementor-element-3755202 .elementor-icon {
                font-size: 80px;
            }

            .elementor-2148 .elementor-element.elementor-element-3755202>.elementor-widget-container {
                padding: 4px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-698effd2 {
                --e-con-grid-template-columns: repeat(1, 1fr);
                --grid-auto-flow: row;
            }

            .elementor-2148 .elementor-element.elementor-element-36b44d0 {
                --padding-top: 50px;
                --padding-bottom: 50px;
                --padding-left: 25px;
                --padding-right: 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-8f7b2ae {
                --padding-top: 50px;
                --padding-bottom: 50px;
                --padding-left: 25px;
                --padding-right: 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-cdba9f5 {
                --e-con-grid-template-columns: repeat(1, 1fr);
                --grid-auto-flow: row;
            }

            .elementor-2148 .elementor-element.elementor-element-d1bcf85 {
                --margin-top: 125px;
                --margin-bottom: 50px;
                --margin-left: 0px;
                --margin-right: 0px;
                --padding-top: 0px;
                --padding-bottom: 0px;
                --padding-left: 0px;
                --padding-right: 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-eb6492d {
                --margin-top: -125px;
                --margin-bottom: 0px;
                --margin-left: 0px;
                --margin-right: 0px;
                --padding-top: 25px;
                --padding-bottom: 25px;
                --padding-left: 25px;
                --padding-right: 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-5071c58 {
                --margin-top: 0px;
                --margin-bottom: 0px;
                --margin-left: 0px;
                --margin-right: 0px;
                --padding-top: 50px;
                --padding-bottom: 50px;
                --padding-left: 25px;
                --padding-right: 25px;
            }

            .elementor-2148 .elementor-element.elementor-element-a606283 {
                padding: 20px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-da36 {
                text-align: center;
            }

            .elementor-2148 .elementor-element.elementor-element-da36 img {
                width: 35%;
            }

            .elementor-2148 .elementor-element.elementor-element-567c67d6>.elementor-element-populated {
                padding: 15px 15px 15px 15px;
            }

            .elementor-2148 .elementor-element.elementor-element-451c6838 {
                text-align: center;
            }

            .elementor-2148 .elementor-element.elementor-element-27a85aa8>.elementor-element-populated {
                padding: 10px 10px 10px 10px;
            }

            .elementor-2148 .elementor-element.elementor-element-6f6adf5d>.elementor-widget-container {
                margin: 0px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-d69c03f {
                padding: 0px 15px 15px 15px;
            }

            .elementor-2148 .elementor-element.elementor-element-505de0ad>.elementor-widget-container {
                padding: 20px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-4ab5ec2b>.elementor-widget-container {
                margin: 0px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-58e94d34>.elementor-widget-container {
                padding: 20px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-70cc719 {
                padding: 0px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-5700c1c8>.elementor-element-populated {
                padding: 0px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-77fbd2bc>.elementor-widget-container {
                margin: 0px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-5d88fdad>.elementor-element-populated {
                padding: 0px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-6d2c566d>.elementor-widget-container {
                margin: 10px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-1c1a5ac7>.elementor-widget-container {
                padding: 20px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-74c09cc3>.elementor-widget-container {
                margin: 0px 0px 0px 0px;
            }

            .elementor-2148 .elementor-element.elementor-element-554fb52b {
                width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-2a8fec4e img {
                width: 72%;
            }

            .elementor-2148 .elementor-element.elementor-element-2b115da0 {
                width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-7df1c685 img {
                width: 92%;
            }

            .elementor-2148 .elementor-element.elementor-element-66a877dd {
                width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-7a5a7d25 {
                text-align: center;
            }

            .elementor-2148 .elementor-element.elementor-element-7a5a7d25 img {
                width: 61%;
            }

            .elementor-2148 .elementor-element.elementor-element-75a95ea3 {
                width: 50%;
            }

            .elementor-2148 .elementor-element.elementor-element-5c15aef3 {
                text-align: left;
            }

            .elementor-2148 .elementor-element.elementor-element-5c15aef3 img {
                width: 100%;
            }

            .elementor-2148 .elementor-element.elementor-element-985bae8 {
                margin-top: 0px;
                margin-bottom: 40px;
            }
        }

        /* Start custom CSS for text-editor, class: .elementor-element-451c6838 */
        .elementor-2148 .elementor-element.elementor-element-451c6838 a {
            text-decoration: none;
            color: #fa6b02;
            font-weight: bold;
        }

        /* End custom CSS */
    </style>











    <link rel='stylesheet' id='mystickyelements-google-fonts-css' href='https://fonts.googleapis.com/css?family=Poppins%3A400%2C500%2C600%2C700&#038;ver=2.1.7' media='all' />



    <style id='rocket-lazyload-inline-css'>
        .rll-youtube-player {
            position: relative;
            padding-bottom: 56.23%;
            height: 0;
            overflow: hidden;
            max-width: 100%;
        }

        .rll-youtube-player:focus-within {
            outline: 2px solid currentColor;
            outline-offset: 5px;
        }

        .rll-youtube-player iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            background: 0 0
        }

        .rll-youtube-player img {
            bottom: 0;
            display: block;
            left: 0;
            margin: auto;
            max-width: 100%;
            width: 100%;
            position: absolute;
            right: 0;
            top: 0;
            border: none;
            height: auto;
            -webkit-transition: .4s all;
            -moz-transition: .4s all;
            transition: .4s all
        }

        .rll-youtube-player img:hover {
            -webkit-filter: brightness(75%)
        }

        .rll-youtube-player .play {
            height: 100%;
            width: 100%;
            left: 0;
            top: 0;
            position: absolute;
            background: url(https://vblocksmithservices.co.uk/wp-content/plugins/wp-rocket/assets/img/youtube.png) no-repeat center;
            background-color: transparent !important;
            cursor: pointer;
            border: none;
        }
    </style>
    <link rel='stylesheet' id='google-fonts-1-css' href='https://fonts.googleapis.com/css?family=Roboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto+Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CMontserrat%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;display=swap&#038;ver=6.8.3' media='all' />




    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <script id="jquery-core-js-extra">
        var pp = {
            "ajax_url": "https:\/\/vblocksmithservices.co.uk\/wp-admin\/admin-ajax.php"
        };
    </script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-includes/js/jquery/jquery.min.js?ver=3.7.1" id="jquery-core-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.4.1" id="jquery-migrate-js" defer></script>
    <!--[if IE]>
<script src="https://vblocksmithservices.co.uk/wp-content/themes/astra/assets/js/minified/flexibility.min.js?ver=4.7.2" id="astra-flexibility-js"></script>
<script id="astra-flexibility-js-after">
flexibility(document.documentElement);</script>
<![endif]-->
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/elementskit/modules/parallax/assets/js/jarallax.js?ver=1760354130" id="jarallax-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/elementor/assets/lib/font-awesome/js/v4-shims.min.js?ver=3.22.3" id="font-awesome-4-shim-js" defer></script>
    <link rel="https://api.w.org/" href="https://vblocksmithservices.co.uk/wp-json/" />
    <link rel="alternate" title="JSON" type="application/json" href="https://vblocksmithservices.co.uk/wp-json/wp/v2/pages/2148" />
    <link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://vblocksmithservices.co.uk/xmlrpc.php?rsd" />
    <link rel="alternate" title="oEmbed (JSON)" type="application/json+oembed" href="https://vblocksmithservices.co.uk/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fvblocksmithservices.co.uk%2Fareas-we-cover%2Fcoventry%2F" />
    <link rel="alternate" title="oEmbed (XML)" type="text/xml+oembed" href="https://vblocksmithservices.co.uk/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fvblocksmithservices.co.uk%2Fareas-we-cover%2Fcoventry%2F&#038;format=xml" />
    <!-- Google Tag Manager -->
    <script type="rocketlazyloadscript">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N3HZ24T6');</script>
    <!-- End Google Tag Manager -->
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N3HZ24T6"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Google Tag Manager for WordPress by gtm4wp.com -->
    <!-- GTM Container placement set to off -->
    <script data-cfasync="false" data-pagespeed-no-defer type="text/javascript">
        var dataLayer_content = {
            "pagePostType": "page",
            "pagePostType2": "single-page",
            "pagePostAuthor": "VB Locksmith Services"
        };
        dataLayer.push(dataLayer_content);
    </script>
    <script type="rocketlazyloadscript">
        console.warn && console.warn("[GTM4WP] Google Tag Manager container code placement set to OFF !!!");
	console.warn && console.warn("[GTM4WP] Data layer codes are active but GTM container must be loaded using custom coding !!!");
</script>
    <!-- End Google Tag Manager for WordPress by gtm4wp.com -->
    <meta name="generator" content="Elementor 3.22.3; features: e_optimized_assets_loading, e_optimized_css_loading, additional_custom_breakpoints; settings: css_print_method-internal, google_font-enabled, font_display-swap">

    <script type="rocketlazyloadscript" data-rocket-type="text/javascript">
        var elementskit_module_parallax_url = "https://vblocksmithservices.co.uk/wp-content/plugins/elementskit/modules/parallax/"
			</script>
    <link rel="icon" href="https://vblocksmithservices.co.uk/wp-content/uploads/2023/06/favicon-150x150.png" sizes="32x32" />
    <link rel="icon" href="https://vblocksmithservices.co.uk/wp-content/uploads/2023/06/favicon.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://vblocksmithservices.co.uk/wp-content/uploads/2023/06/favicon.png" />
    <meta name="msapplication-TileImage" content="https://vblocksmithservices.co.uk/wp-content/uploads/2023/06/favicon.png" />
    <style id="wp-custom-css">
        #wpforms-form-213 label {
            color: #FFFFFF !important;
            font-size: 18px;
            font-weight: 500;
        }
    </style>
    <noscript>
        <style id="rocket-lazyload-nojs-css">
            .rll-youtube-player,
            [data-lazy-src] {
                display: none !important;
            }
        </style>
    </noscript>
    <style id="wpforms-css-vars-root">
        :root {
            --wpforms-field-border-radius: 3px;
            --wpforms-field-border-style: solid;
            --wpforms-field-border-size: 1px;
            --wpforms-field-background-color: #ffffff;
            --wpforms-field-border-color: rgba(0, 0, 0, 0.25);
            --wpforms-field-border-color-spare: rgba(0, 0, 0, 0.25);
            --wpforms-field-text-color: rgba(0, 0, 0, 0.7);
            --wpforms-field-menu-color: #ffffff;
            --wpforms-label-color: rgba(0, 0, 0, 0.85);
            --wpforms-label-sublabel-color: rgba(0, 0, 0, 0.55);
            --wpforms-label-error-color: #d63637;
            --wpforms-button-border-radius: 3px;
            --wpforms-button-border-style: none;
            --wpforms-button-border-size: 1px;
            --wpforms-button-background-color: #066aab;
            --wpforms-button-border-color: #066aab;
            --wpforms-button-text-color: #ffffff;
            --wpforms-page-break-color: #066aab;
            --wpforms-background-image: none;
            --wpforms-background-position: center center;
            --wpforms-background-repeat: no-repeat;
            --wpforms-background-size: cover;
            --wpforms-background-width: 100px;
            --wpforms-background-height: 100px;
            --wpforms-background-color: rgba(0, 0, 0, 0);
            --wpforms-background-url: none;
            --wpforms-container-padding: 0px;
            --wpforms-container-border-style: none;
            --wpforms-container-border-width: 1px;
            --wpforms-container-border-color: #000000;
            --wpforms-container-border-radius: 3px;
            --wpforms-field-size-input-height: 43px;
            --wpforms-field-size-input-spacing: 15px;
            --wpforms-field-size-font-size: 16px;
            --wpforms-field-size-line-height: 19px;
            --wpforms-field-size-padding-h: 14px;
            --wpforms-field-size-checkbox-size: 16px;
            --wpforms-field-size-sublabel-spacing: 5px;
            --wpforms-field-size-icon-size: 1;
            --wpforms-label-size-font-size: 16px;
            --wpforms-label-size-line-height: 19px;
            --wpforms-label-size-sublabel-font-size: 14px;
            --wpforms-label-size-sublabel-line-height: 17px;
            --wpforms-button-size-font-size: 17px;
            --wpforms-button-size-height: 41px;
            --wpforms-button-size-padding-h: 15px;
            --wpforms-button-size-margin-top: 10px;
            --wpforms-container-shadow-size-box-shadow: none;

        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
</head>

<body data-rsssl=1 class="wp-singular page-template page-template-elementor_canvas page page-id-2148 page-child parent-pageid-2048 wp-custom-logo wp-theme-astra ast-desktop ast-page-builder-template ast-no-sidebar astra-4.7.2 ast-single-post ast-replace-site-logo-transparent ast-inherit-site-logo-transparent ast-hfb-header elementor-default elementor-template-canvas elementor-kit-4 elementor-page elementor-page-2148 astra-addon-4.7.2">
    <div data-elementor-type="wp-page" data-elementor-id="2148" class="elementor elementor-2148" data-elementor-post-type="page">
        <section class="elementor-section elementor-top-section elementor-element elementor-element-f0b8bbd elementor-section-full_width elementor-section-height-default elementor-section-height-default" data-id="f0b8bbd" data-element_type="section" data-settings="{&quot;sticky&quot;:&quot;top&quot;,&quot;background_background&quot;:&quot;gradient&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;,&quot;sticky_on&quot;:[&quot;desktop&quot;,&quot;tablet&quot;,&quot;mobile&quot;],&quot;sticky_offset&quot;:0,&quot;sticky_effects_offset&quot;:0}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-53684899" data-id="53684899" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;gradient&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section class="elementor-section elementor-inner-section elementor-element elementor-element-10f337ce elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="10f337ce" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-3aeb67ef" data-id="3aeb67ef" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-e01aa18 elementor-widget__width-initial elementor-widget elementor-widget-theme-site-logo elementor-widget-image" data-id="e01aa18" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="theme-site-logo.default">
                                            <div class="elementor-widget-container">
                                                <style>
                                                    /*! elementor - v3.22.0 - 26-06-2024 */
                                                    .elementor-widget-image {
                                                        text-align: center
                                                    }

                                                    .elementor-widget-image a {
                                                        display: inline-block
                                                    }

                                                    .elementor-widget-image a img[src$=".svg"] {
                                                        width: 48px
                                                    }

                                                    .elementor-widget-image img {
                                                        vertical-align: middle;
                                                        display: inline-block
                                                    }
                                                </style>
                                                <div class="elementor-image">
                                                    <img fetchpriority="high" decoding="async" width="289" height="300" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20289%20300'%3E%3C/svg%3E" class="attachment-medium size-medium wp-image-873" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-289x300.png 289w, https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-115x119.png 115w, https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo.png 367w" data-lazy-sizes="(max-width: 289px) 100vw, 289px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-115x119.png" /><noscript><img fetchpriority="high" decoding="async" width="289" height="300" src="https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-115x119.png" class="attachment-medium size-medium wp-image-873" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-289x300.png 289w, https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-115x119.png 115w, https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo.png 367w" sizes="(max-width: 289px) 100vw, 289px" /></noscript>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-70268acf" data-id="70268acf" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-b265789 elementor-tablet-align-center elementor-align-center elementor-hidden-mobile elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="b265789" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-list.default">
                                            <div class="elementor-widget-container">
                                                <ul class="elementor-icon-list-items">
                                                    <li class="elementor-icon-list-item">
                                                        <a href="tel:07883444240">

                                                            <span class="elementor-icon-list-icon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                                    <g>
                                                                        <g>
                                                                            <path d="M510.412,129.292c-3.706-7.412-12.729-10.386-20.127-6.709l-59.999,30c-7.412,3.706-10.415,12.715-6.709,20.127   c3.701,7.382,12.665,10.415,20.127,6.709l59.999-30C511.115,145.713,514.118,136.704,510.412,129.292z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g>
                                                                        <g>
                                                                            <path d="M503.703,302.581l-59.999-30c-7.412-3.691-16.406-0.718-20.127,6.709c-3.706,7.412-0.703,16.421,6.709,20.127l59.999,30   c7.484,3.717,16.438,0.649,20.127-6.709C514.118,315.296,511.115,306.287,503.703,302.581z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g>
                                                                        <g>
                                                                            <path d="M344.996,242H119.999v-76.999c0-41.353,33.647-74.999,74.999-74.999c41.352,0,74.999,33.647,74.999,74.999v30   c0,8.291,6.709,15,15,15h59.999c8.291,0,15-6.709,15-15v-30c0-90.98-74.018-164.998-164.998-164.998S30,74.021,30,165.001v79.762   c-17.422,6.213-30,22.707-30,42.236v179.998c0,24.814,20.186,44.999,44.999,44.999h299.997c24.814,0,44.999-20.186,44.999-44.999   V286.999C389.995,262.185,369.81,242,344.996,242z M209.998,389.235v47.762c0,8.291-6.709,15-15,15s-15-6.709-15-15v-47.762   c-17.422-6.213-30-22.707-30-42.236c0-24.814,20.186-45,45-45c24.814,0,44.999,20.186,44.999,45   C239.997,366.529,227.419,383.022,209.998,389.235z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g>
                                                                        <g>
                                                                            <path d="M496.994,211h-59.999c-8.291,0-15,6.709-15,15s6.709,15,15,15h59.999c8.291,0,15-6.709,15-15S505.285,211,496.994,211z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                    <g></g>
                                                                </svg> </span>
                                                            <span class="elementor-icon-list-text">24/7 Call us Free of Charge - 07883444240</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="elementor-element elementor-element-617b151 elementor-widget__width-initial elementor-mobile-align-center elementor-widget-mobile__width-initial elementor-hidden-desktop elementor-widget elementor-widget-button" data-id="617b151" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                                            <div class="elementor-widget-container">
                                                <div class="elementor-button-wrapper">
                                                    <a class="elementor-button elementor-button-link elementor-size-sm" href="tel:07883444240">
                                                        <span class="elementor-button-content-wrapper">
                                                            <span class="elementor-button-text">Call 07883444240</span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-695b0217 elementor-hidden-mobile" data-id="695b0217" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-54476fae elementor-widget elementor-widget-uael-infobox" data-id="54476fae" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="uael-infobox.default">
                                            <div class="elementor-widget-container">

                                                <div class="uael-module-content uael-infobox uael-imgicon-style-normal  uael-infobox-center  infobox-has-icon uael-infobox-icon-above-title  uael-infobox-link-type-module">
                                                    <div class="uael-infobox-left-right-wrap">
                                                        <a href="tel:07883444240" class="uael-infobox-module-link"></a>
                                                        <div class="uael-infobox-content">
                                                            <div class="uael-module-content uael-imgicon-wrap ">
                                                                <div class="uael-icon-wrap elementor-animation-">
                                                                    <span class="uael-icon">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" width="612px" height="612px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
                                                                            <g>
                                                                                <path d="M339.296,19.573c-92.187,0-173.778,46.021-223.165,116.25c10.221,6.668,20.184,15.812,29.82,27.567  c36.844-55.091,96.417-93.743,165.344-102.052l27.997,47.377l27.997-47.378c105.952,12.769,190.17,96.983,202.945,202.934  l-47.385,28.001l47.386,28.002c-8.54,70.836-49.108,131.821-106.661,168.372c3.666,8.357,5.731,16.902,6.096,25.712  c0.269,6.438-0.395,12.521-1.73,18.296C553.609,486.622,612,396.153,612,292.277C612,141.904,489.669,19.573,339.296,19.573z   M408.887,468.69c-62.865-50.523-80.444-25.688-108.968,2.832c-19.914,19.921-70.308-21.678-113.821-65.193  c-43.516-43.521-85.107-93.907-65.195-113.824c28.526-28.521,53.354-46.11,2.817-108.958  c-50.52-62.871-84.198-14.603-111.829,13.03c-31.9,31.889-1.681,150.726,115.769,268.194  c117.466,117.452,236.302,147.651,268.183,115.774C423.471,552.911,471.753,519.237,408.887,468.69z M217.426,334.954  c0-55.88,65.42-65.835,65.42-87.641c0-10.581-8.47-15.023-16.304-15.023c-14.397,0-22.434,16.083-22.434,16.083l-27.521-18.415  c0,0,13.973-32.6,53.346-32.6c24.766,0,51.65,14.185,51.65,46.36c0,47.208-60.968,56.314-62.028,75.789h64.36v31.751H219.123  C218.062,345.332,217.426,340.043,217.426,334.954z M339.391,292.199l58.434-92.305h45.301v84.047h17.991v30.691h-17.991v36.628  h-36.628v-36.628h-67.106V292.199L339.391,292.199z M406.497,283.94v-33.871c0-8.258,1.272-16.94,1.272-16.94h-0.424  c0,0-3.171,9.318-7.41,15.669l-22.857,34.72v0.424L406.497,283.94L406.497,283.94z"></path>
                                                                            </g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                            <g></g>
                                                                        </svg> </span>
                                                                </div>

                                                            </div>
                                                            <div class='uael-infobox-title-wrap'>
                                                                <p class="uael-infobox-title elementor-inline-editing" data-elementor-setting-key="infobox_title" data-elementor-inline-editing-toolbar="basic">24/7 Lock Replacement</p>
                                                            </div>
                                                            <div class="uael-infobox-text-wrap">
                                                                <div class="uael-infobox-text elementor-inline-editing" data-elementor-setting-key="infobox_description" data-elementor-inline-editing-toolbar="advanced">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>
        <div class="elementor-element elementor-element-6b7987d e-con-full e-flex e-con e-parent" data-id="6b7987d" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="elementor-element elementor-element-366a195 e-con-full e-flex e-con e-child" data-id="366a195" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                <div class="elementor-element elementor-element-e2ba217 e-con-full e-flex e-con e-child" data-id="e2ba217" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                    <div class="elementor-element elementor-element-2d23bbf e-con-full e-flex e-con e-child" data-id="2d23bbf" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                        <div class="elementor-element elementor-element-c94d200 elementor-widget elementor-widget-heading" data-id="c94d200" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <style>
                                    /*! elementor - v3.22.0 - 26-06-2024 */
                                    .elementor-heading-title {
                                        padding: 0;
                                        margin: 0;
                                        line-height: 1
                                    }

                                    .elementor-widget-heading .elementor-heading-title[class*=elementor-size-]>a {
                                        color: inherit;
                                        font-size: inherit;
                                        line-height: inherit
                                    }

                                    .elementor-widget-heading .elementor-heading-title.elementor-size-small {
                                        font-size: 15px
                                    }

                                    .elementor-widget-heading .elementor-heading-title.elementor-size-medium {
                                        font-size: 19px
                                    }

                                    .elementor-widget-heading .elementor-heading-title.elementor-size-large {
                                        font-size: 29px
                                    }

                                    .elementor-widget-heading .elementor-heading-title.elementor-size-xl {
                                        font-size: 39px
                                    }

                                    .elementor-widget-heading .elementor-heading-title.elementor-size-xxl {
                                        font-size: 59px
                                    }
                                </style>
                                <p class="elementor-heading-title elementor-size-default">Fast. Reliable. Secure.</p>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-1405c7e elementor-widget elementor-widget-heading" data-id="1405c7e" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h1 class="elementor-heading-title elementor-size-default">Coventry Locksmith - Emergency Locksmith Near Me</h1>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-899e0e5 elementor-hidden-desktop elementor-hidden-tablet elementor-widget elementor-widget-text-editor" data-id="899e0e5" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="text-editor.default">
                            <div class="elementor-widget-container">
                                <style>
                                    /*! elementor - v3.22.0 - 26-06-2024 */
                                    .elementor-widget-text-editor.elementor-drop-cap-view-stacked .elementor-drop-cap {
                                        background-color: #69727d;
                                        color: #fff
                                    }

                                    .elementor-widget-text-editor.elementor-drop-cap-view-framed .elementor-drop-cap {
                                        color: #69727d;
                                        border: 3px solid;
                                        background-color: transparent
                                    }

                                    .elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap {
                                        margin-top: 8px
                                    }

                                    .elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap-letter {
                                        width: 1em;
                                        height: 1em
                                    }

                                    .elementor-widget-text-editor .elementor-drop-cap {
                                        float: left;
                                        text-align: center;
                                        line-height: 1;
                                        font-size: 50px
                                    }

                                    .elementor-widget-text-editor .elementor-drop-cap-letter {
                                        display: inline-block
                                    }
                                </style>
                                <p>Hello, I&#8217;m Vlad, a trusted locksmith in Coventry, providing fast and effective solutions for all your lock and key problems.</p>
                                <p><span style="font-weight: 400;">I also serve nearby areas including </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/atherstone/">Atherstone</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/bedworth/">Bedworth</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/binley-woods/">Binley Woods</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/kenilworth/">Kenilworth</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/leamington-spa/">Leamington Spa</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/nuneaton/">Nuneaton</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/rugby/">Rugby</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/ryton-on-dunsmore/">Ryton On Dunsmore</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/solihull/">Solihull</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/stretton-on-dunsmore/">Stretton On Dunsmore</a></strong><span style="font-weight: 400;"> and </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/warwick/">Warwick</a></strong>.</p>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-410e3ef elementor-hidden-mobile elementor-widget elementor-widget-text-editor" data-id="410e3ef" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="text-editor.default">
                            <div class="elementor-widget-container">
                                <p>Hello, I&#8217;m Vlad, a trusted locksmith in Coventry, providing fast and effective solutions for all your lock and key problems.</p>
                                <p>As your trusted locksmith in Coventry, I specialise in repairing and replacing locks for both residential and commercial clients. I’m committed to delivering outstanding customer service and excellent value, making me a reliable choice for both homeowners and businesses. Whether you need a new lock fitted, an upgrade, a quick repair, or urgent help — you can count on me to get the job done efficiently and professionally.</p>
                                <p><span style="font-weight: 400;">I also serve nearby areas including </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/atherstone/">Atherstone</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/bedworth/">Bedworth</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/binley-woods/">Binley Woods</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/kenilworth/">Kenilworth</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/leamington-spa/">Leamington Spa</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/nuneaton/">Nuneaton</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/rugby/">Rugby</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/ryton-on-dunsmore/">Ryton On Dunsmore</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/solihull/">Solihull</a></strong><span style="font-weight: 400;">, </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/stretton-on-dunsmore/">Stretton On Dunsmore</a></strong><span style="font-weight: 400;"> and </span><strong><a href="https://vblocksmithservices.co.uk/areas-we-cover/warwick/">Warwick</a></strong>.</p>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-c40f37a e-con-full e-flex e-con e-child" data-id="c40f37a" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                            <div class="elementor-element elementor-element-db72c16 elementor-widget__width-auto elementor-mobile-align-center elementor-align-left elementor-widget elementor-widget-button" data-id="db72c16" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                                <div class="elementor-widget-container">
                                    <div class="elementor-button-wrapper">
                                        <a class="elementor-button elementor-button-link elementor-size-sm" href="tel:07883444240">
                                            <span class="elementor-button-content-wrapper">
                                                <span class="elementor-button-text">Call Vlad</span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-8d5eb45 elementor-widget__width-auto elementor-mobile-align-center elementor-align-left elementor-widget elementor-widget-button" data-id="8d5eb45" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                                <div class="elementor-widget-container">
                                    <div class="elementor-button-wrapper">
                                        <a class="elementor-button elementor-button-link elementor-size-sm" href="mailto:fitlock.uk@gmail.com">
                                            <span class="elementor-button-content-wrapper">
                                                <span class="elementor-button-text">Email</span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-34db5b1 elementor-widget__width-auto elementor-mobile-align-center elementor-widget elementor-widget-button" data-id="34db5b1" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                                <div class="elementor-widget-container">
                                    <div class="elementor-button-wrapper">
                                        <a class="elementor-button elementor-button-link elementor-size-sm" href="https://wa.link/lnrmmz">
                                            <span class="elementor-button-content-wrapper">
                                                <span class="elementor-button-text">WhatsApp</span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="elementor-element elementor-element-0324e33 e-con-full e-flex e-con e-child" data-id="0324e33" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                <div class="elementor-element elementor-element-c2b9463 e-con-full e-flex e-con e-child" data-id="c2b9463" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                    <div class="elementor-element elementor-element-88d74a9 e-con-full e-flex e-con e-child" data-id="88d74a9" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                        <div class="elementor-element elementor-element-594a0ca e-con-full e-flex e-con e-child" data-id="594a0ca" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                            <div class="elementor-element elementor-element-c8a6701 elementor-widget__width-initial elementor-hidden-mobile elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="c8a6701" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-list.default">
                                <div class="elementor-widget-container">
                                    <ul class="elementor-icon-list-items">
                                        <li class="elementor-icon-list-item">
                                            <span class="elementor-icon-list-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 64 64" width="512">
                                                    <g id="Pin">
                                                        <path d="m32 0a24.0319 24.0319 0 0 0 -24 24c0 17.23 22.36 38.81 23.31 39.72a.99.99 0 0 0 1.38 0c.95-.91 23.31-22.49 23.31-39.72a24.0319 24.0319 0 0 0 -24-24zm0 35a11 11 0 1 1 11-11 11.0066 11.0066 0 0 1 -11 11z"></path>
                                                    </g>
                                                </svg> </span>
                                            <span class="elementor-icon-list-text">Local Locksmith in Cardiff</span>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <span class="elementor-icon-list-icon">
                                                <i aria-hidden="true" class="fas fa-unlock-alt"></i> </span>
                                            <span class="elementor-icon-list-text">We Open ANY Door!</span>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <span class="elementor-icon-list-icon">
                                                <i aria-hidden="true" class="icon icon-money-bag"></i> </span>
                                            <span class="elementor-icon-list-text">Labour Price from £79 and Locks from £25</span>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <span class="elementor-icon-list-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" width="612px" height="612px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
                                                    <g>
                                                        <path d="M339.296,19.573c-92.187,0-173.778,46.021-223.165,116.25c10.221,6.668,20.184,15.812,29.82,27.567  c36.844-55.091,96.417-93.743,165.344-102.052l27.997,47.377l27.997-47.378c105.952,12.769,190.17,96.983,202.945,202.934  l-47.385,28.001l47.386,28.002c-8.54,70.836-49.108,131.821-106.661,168.372c3.666,8.357,5.731,16.902,6.096,25.712  c0.269,6.438-0.395,12.521-1.73,18.296C553.609,486.622,612,396.153,612,292.277C612,141.904,489.669,19.573,339.296,19.573z   M408.887,468.69c-62.865-50.523-80.444-25.688-108.968,2.832c-19.914,19.921-70.308-21.678-113.821-65.193  c-43.516-43.521-85.107-93.907-65.195-113.824c28.526-28.521,53.354-46.11,2.817-108.958  c-50.52-62.871-84.198-14.603-111.829,13.03c-31.9,31.889-1.681,150.726,115.769,268.194  c117.466,117.452,236.302,147.651,268.183,115.774C423.471,552.911,471.753,519.237,408.887,468.69z M217.426,334.954  c0-55.88,65.42-65.835,65.42-87.641c0-10.581-8.47-15.023-16.304-15.023c-14.397,0-22.434,16.083-22.434,16.083l-27.521-18.415  c0,0,13.973-32.6,53.346-32.6c24.766,0,51.65,14.185,51.65,46.36c0,47.208-60.968,56.314-62.028,75.789h64.36v31.751H219.123  C218.062,345.332,217.426,340.043,217.426,334.954z M339.391,292.199l58.434-92.305h45.301v84.047h17.991v30.691h-17.991v36.628  h-36.628v-36.628h-67.106V292.199L339.391,292.199z M406.497,283.94v-33.871c0-8.258,1.272-16.94,1.272-16.94h-0.424  c0,0-3.171,9.318-7.41,15.669l-22.857,34.72v0.424L406.497,283.94L406.497,283.94z"></path>
                                                    </g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                </svg> </span>
                                            <span class="elementor-icon-list-text">24/7 Locksmith Service</span>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <span class="elementor-icon-list-icon">
                                                <i aria-hidden="true" class="far fa-clock"></i> </span>
                                            <span class="elementor-icon-list-text">Fast Response</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-ffc3e02 elementor-widget__width-initial elementor-hidden-mobile elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="ffc3e02" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-list.default">
                                <div class="elementor-widget-container">
                                    <ul class="elementor-icon-list-items">
                                        <li class="elementor-icon-list-item">
                                            <span class="elementor-icon-list-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" width="406.783px" height="406.783px" viewBox="0 0 406.783 406.783" style="enable-background:new 0 0 406.783 406.783;" xml:space="preserve">
                                                    <g>
                                                        <g>
                                                            <path d="M127.12,256.572c-19.742,0-35.741,15.993-35.741,35.737c0,19.745,15.999,35.738,35.741,35.738   c19.749,0,35.744-15.993,35.744-35.738C162.864,272.565,146.869,256.572,127.12,256.572z M127.12,307.846   c-8.582,0-15.536-6.955-15.536-15.537c0-8.586,6.954-15.537,15.536-15.537c8.583,0,15.542,6.951,15.542,15.537   C142.662,300.891,135.703,307.846,127.12,307.846z"></path>
                                                            <path d="M315.588,256.572c-19.742,0-35.74,15.993-35.74,35.737c0,19.745,15.998,35.738,35.74,35.738   c19.75,0,35.744-15.993,35.744-35.738C351.332,272.565,335.338,256.572,315.588,256.572z M315.588,307.846   c-8.582,0-15.535-6.955-15.535-15.537c0-8.586,6.953-15.537,15.535-15.537c8.584,0,15.543,6.951,15.543,15.537   C331.131,300.891,324.172,307.846,315.588,307.846z"></path>
                                                            <path d="M167.329,146.759c0,5.008-4.098,9.105-9.105,9.105H32.579c-5.008,0-9.104-4.097-9.104-9.105v-5.463   c0-5.007,4.097-9.104,9.104-9.104h125.645c5.008,0,9.105,4.097,9.105,9.104V146.759z"></path>
                                                            <path d="M385.623,200.066c-13.105-3.407-20.604-5.549-25.75-15.487l-17.207-34.839c-5.148-9.938-18.518-18.07-29.707-18.07   h-23.535c0,0-3.166,0.066-3.166-3.12c0-7.305,0-29.219,0-29.219c0-11.327-6.41-20.595-20.045-20.595H74.405   c-19.521,0-28.789,9.269-28.789,20.595v18.311c0,0,0,5.446,5.271,5.446c26.834,0,107.337,0,107.337,0   c10.041,0,18.21,8.168,18.21,18.209v5.463c0,10.041-8.169,18.209-18.21,18.209H50.887c0,0-5.271-0.438-5.271,5.252   c0,2.826,0,4.723,0,6.297c0,5.008,6.864,5.005,6.864,5.005h72.254c10.041,0,18.21,8.169,18.21,18.209v5.463   c0,10.041-8.169,18.209-18.21,18.209H53.62c0,0-8.004-0.148-8.004,6.225c0,11.062,0,44.246,0,44.246   c0,11.326,9.268,20.595,20.595,20.595c0,0,8.532,0,11.376,0c2.58,0,2.96-1.437,2.96-2.159c0-25.679,20.894-46.568,46.574-46.568   c25.682,0,46.575,20.891,46.575,46.568c0,0.725-0.206,2.159,1.767,2.159c22.55,0,91.806,0,91.806,0   c1.82,0,1.746-1.534,1.746-2.159c0-25.679,20.893-46.568,46.574-46.568s46.574,20.891,46.574,46.568   c0,0.725-0.018,2.159,1.121,2.159c10.34,0,23.146,0,23.146,0c11.195,0,20.352-9.157,20.352-20.351v-38.664   C406.783,202.894,396.502,202.894,385.623,200.066z M346.896,198.255c0,0-43.219,0-57.928,0c-2.393,0-2.711-2.33-2.711-2.33   V147.67c0,0-0.135-1.853,2.938-1.853c4.133,0,16.529,0,16.529,0c9.959,0,21.855,7.236,26.434,16.079l15.312,31   c0.645,1.248,1.334,2.356,2.072,3.349C350.086,196.973,349.174,198.255,346.896,198.255z"></path>
                                                            <path d="M133.838,205.195c0,5.008-4.097,9.105-9.104,9.105H9.104C4.096,214.3,0,210.203,0,205.195v-5.463   c0-5.007,4.097-9.104,9.104-9.104h115.63c5.008,0,9.104,4.097,9.104,9.104V205.195z"></path>
                                                        </g>
                                                    </g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                </svg> </span>
                                            <span class="elementor-icon-list-text">30 Mins To Your Door</span>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <span class="elementor-icon-list-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" height="512" viewBox="0 0 468 468" width="512">
                                                    <g>
                                                        <path d="m110.319 300.11 21.615 13.782 28.638-17.903c2.483-1.551 5.353-2.375 8.283-2.375h.012l88.181.071c13.378-25.606 35.119-45.929 61.57-57.573v-50.244l-52.644-15.617-37.262 82.402c-5.372 12.035-23.112 12.042-28.488 0l-37.263-82.402-52.643 15.617v114.242z"></path>
                                                        <ellipse cx="214.469" cy="77.592" rx="62.804" ry="62.592"></ellipse>
                                                        <path d="m370.684 256.333c-.017 0-.036 0-.053 0-39.192 0-74.246 23.124-89.318 58.928-2.431 5.775-8.098 9.531-14.377 9.531-.005 0-.008 0-.012 0l-93.591-.075-33.198 20.756c-5.109 3.193-11.602 3.164-16.683-.074l-28.005-17.857-28.638 17.903c-5.219 3.264-11.878 3.159-16.991-.277l-23.947-16.076-25.871 25.313 30.824 29.895 232.013.126c6.23.003 11.859 3.703 14.318 9.41 15 34.812 53.942 59.141 94.7 59.163 53.509 0 96.41-45.396 96.144-98.193-.275-54.268-43.93-98.442-97.315-98.473zm1.097 122.917c-13.396 0-24.256-11.007-24.256-24.583s10.86-24.583 24.256-24.583 24.256 11.007 24.256 24.583-10.86 24.583-24.256 24.583z"></path>
                                                    </g>
                                                </svg> </span>
                                            <span class="elementor-icon-list-text">20+ Years of Experience</span>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <span class="elementor-icon-list-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="Capa_1" height="512" viewBox="0 0 512 512" width="512">
                                                    <g>
                                                        <path d="m60.205 333.045-59.639 59.639c-1.003.956-.628 2.685.77 3.1l71.828 21.819c10.19 3.1 18.13 11.04 21.229 21.229l21.819 71.828c.414 1.398 2.144 1.773 3.1.77l79.798-79.798c-59.041-14.666-107.784-51.474-138.905-98.587z"></path>
                                                        <path d="m511.434 392.684-59.639-59.639c-30.899 46.776-79.491 83.828-138.907 98.588l79.798 79.798c.956 1.003 2.686.628 3.1-.77l21.819-71.828c3.1-10.19 11.04-18.13 21.23-21.229l71.828-21.819c1.402-.417 1.772-2.148.771-3.101z"></path>
                                                        <path d="m460.265 204.268c0-112.627-91.628-204.265-204.265-204.265s-204.265 91.638-204.265 204.265c0 101.835 74.979 186.711 172.876 201.865 124.007 19.29 235.654-77.232 235.654-201.865zm-363.491 49.779c-33.687-107.701 47.407-216.575 159.226-216.575 124.913 0 207.476 134.249 146.586 246.364-70.294 128.951-261.57 110.936-305.812-29.789z"></path>
                                                        <path d="m256 67.471c-75.428 0-136.807 61.369-136.807 136.797 0 110.972 125.817 175.688 215.915 111.547 19.304-13.702 35.024-32.434 45.139-54.319 42.031-90.486-24.972-194.025-124.247-194.025zm69.048 117.608c-1.708 1.268-84.821 62.955-85.298 63.309-6.003 4.474-14.543 3.874-19.88-1.78-38.177-40.686-35.123-37.437-36.039-38.399-6.415-6.831-5.076-17.88 2.95-22.959.01-.01.02-.02.03-.02 5.96-3.74 13.91-2.87 18.9 2.45 8.073 8.604 2.639 2.813 26.889 28.649 2.614-1.949 33.372-24.767 75.688-56.169 6.65-4.94 16.05-3.55 20.979 3.1 4.518 6.064 4.502 15.357-4.219 21.819z"></path>
                                                    </g>
                                                </svg> </span>
                                            <span class="elementor-icon-list-text">2-Year Warranty on Locks</span>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <span class="elementor-icon-list-icon">
                                                <i aria-hidden="true" class="fas fa-check"></i> </span>
                                            <span class="elementor-icon-list-text">DBS Checked</span>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <span class="elementor-icon-list-icon">
                                                <i aria-hidden="true" class="far fa-star"></i> </span>
                                            <span class="elementor-icon-list-text">5 Star Google Rating</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-e2416b5 elementor-hidden-desktop elementor-hidden-tablet elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="e2416b5" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-list.default">
                            <div class="elementor-widget-container">
                                <ul class="elementor-icon-list-items">
                                    <li class="elementor-icon-list-item">
                                        <span class="elementor-icon-list-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 64 64" width="512">
                                                <g id="Pin">
                                                    <path d="m32 0a24.0319 24.0319 0 0 0 -24 24c0 17.23 22.36 38.81 23.31 39.72a.99.99 0 0 0 1.38 0c.95-.91 23.31-22.49 23.31-39.72a24.0319 24.0319 0 0 0 -24-24zm0 35a11 11 0 1 1 11-11 11.0066 11.0066 0 0 1 -11 11z"></path>
                                                </g>
                                            </svg> </span>
                                        <span class="elementor-icon-list-text">Local Locksmith in Coventry</span>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <span class="elementor-icon-list-icon">
                                            <i aria-hidden="true" class="fas fa-unlock-alt"></i> </span>
                                        <span class="elementor-icon-list-text">We Open ANY Door!</span>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <span class="elementor-icon-list-icon">
                                            <i aria-hidden="true" class="icon icon-money-bag"></i> </span>
                                        <span class="elementor-icon-list-text">Labour Price from £79 & Locks from £25</span>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <span class="elementor-icon-list-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" width="612px" height="612px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
                                                <g>
                                                    <path d="M339.296,19.573c-92.187,0-173.778,46.021-223.165,116.25c10.221,6.668,20.184,15.812,29.82,27.567  c36.844-55.091,96.417-93.743,165.344-102.052l27.997,47.377l27.997-47.378c105.952,12.769,190.17,96.983,202.945,202.934  l-47.385,28.001l47.386,28.002c-8.54,70.836-49.108,131.821-106.661,168.372c3.666,8.357,5.731,16.902,6.096,25.712  c0.269,6.438-0.395,12.521-1.73,18.296C553.609,486.622,612,396.153,612,292.277C612,141.904,489.669,19.573,339.296,19.573z   M408.887,468.69c-62.865-50.523-80.444-25.688-108.968,2.832c-19.914,19.921-70.308-21.678-113.821-65.193  c-43.516-43.521-85.107-93.907-65.195-113.824c28.526-28.521,53.354-46.11,2.817-108.958  c-50.52-62.871-84.198-14.603-111.829,13.03c-31.9,31.889-1.681,150.726,115.769,268.194  c117.466,117.452,236.302,147.651,268.183,115.774C423.471,552.911,471.753,519.237,408.887,468.69z M217.426,334.954  c0-55.88,65.42-65.835,65.42-87.641c0-10.581-8.47-15.023-16.304-15.023c-14.397,0-22.434,16.083-22.434,16.083l-27.521-18.415  c0,0,13.973-32.6,53.346-32.6c24.766,0,51.65,14.185,51.65,46.36c0,47.208-60.968,56.314-62.028,75.789h64.36v31.751H219.123  C218.062,345.332,217.426,340.043,217.426,334.954z M339.391,292.199l58.434-92.305h45.301v84.047h17.991v30.691h-17.991v36.628  h-36.628v-36.628h-67.106V292.199L339.391,292.199z M406.497,283.94v-33.871c0-8.258,1.272-16.94,1.272-16.94h-0.424  c0,0-3.171,9.318-7.41,15.669l-22.857,34.72v0.424L406.497,283.94L406.497,283.94z"></path>
                                                </g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                            </svg> </span>
                                        <span class="elementor-icon-list-text">24/7 Locksmith Service</span>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <span class="elementor-icon-list-icon">
                                            <i aria-hidden="true" class="far fa-clock"></i> </span>
                                        <span class="elementor-icon-list-text">Fast Response</span>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <span class="elementor-icon-list-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" width="406.783px" height="406.783px" viewBox="0 0 406.783 406.783" style="enable-background:new 0 0 406.783 406.783;" xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <path d="M127.12,256.572c-19.742,0-35.741,15.993-35.741,35.737c0,19.745,15.999,35.738,35.741,35.738   c19.749,0,35.744-15.993,35.744-35.738C162.864,272.565,146.869,256.572,127.12,256.572z M127.12,307.846   c-8.582,0-15.536-6.955-15.536-15.537c0-8.586,6.954-15.537,15.536-15.537c8.583,0,15.542,6.951,15.542,15.537   C142.662,300.891,135.703,307.846,127.12,307.846z"></path>
                                                        <path d="M315.588,256.572c-19.742,0-35.74,15.993-35.74,35.737c0,19.745,15.998,35.738,35.74,35.738   c19.75,0,35.744-15.993,35.744-35.738C351.332,272.565,335.338,256.572,315.588,256.572z M315.588,307.846   c-8.582,0-15.535-6.955-15.535-15.537c0-8.586,6.953-15.537,15.535-15.537c8.584,0,15.543,6.951,15.543,15.537   C331.131,300.891,324.172,307.846,315.588,307.846z"></path>
                                                        <path d="M167.329,146.759c0,5.008-4.098,9.105-9.105,9.105H32.579c-5.008,0-9.104-4.097-9.104-9.105v-5.463   c0-5.007,4.097-9.104,9.104-9.104h125.645c5.008,0,9.105,4.097,9.105,9.104V146.759z"></path>
                                                        <path d="M385.623,200.066c-13.105-3.407-20.604-5.549-25.75-15.487l-17.207-34.839c-5.148-9.938-18.518-18.07-29.707-18.07   h-23.535c0,0-3.166,0.066-3.166-3.12c0-7.305,0-29.219,0-29.219c0-11.327-6.41-20.595-20.045-20.595H74.405   c-19.521,0-28.789,9.269-28.789,20.595v18.311c0,0,0,5.446,5.271,5.446c26.834,0,107.337,0,107.337,0   c10.041,0,18.21,8.168,18.21,18.209v5.463c0,10.041-8.169,18.209-18.21,18.209H50.887c0,0-5.271-0.438-5.271,5.252   c0,2.826,0,4.723,0,6.297c0,5.008,6.864,5.005,6.864,5.005h72.254c10.041,0,18.21,8.169,18.21,18.209v5.463   c0,10.041-8.169,18.209-18.21,18.209H53.62c0,0-8.004-0.148-8.004,6.225c0,11.062,0,44.246,0,44.246   c0,11.326,9.268,20.595,20.595,20.595c0,0,8.532,0,11.376,0c2.58,0,2.96-1.437,2.96-2.159c0-25.679,20.894-46.568,46.574-46.568   c25.682,0,46.575,20.891,46.575,46.568c0,0.725-0.206,2.159,1.767,2.159c22.55,0,91.806,0,91.806,0   c1.82,0,1.746-1.534,1.746-2.159c0-25.679,20.893-46.568,46.574-46.568s46.574,20.891,46.574,46.568   c0,0.725-0.018,2.159,1.121,2.159c10.34,0,23.146,0,23.146,0c11.195,0,20.352-9.157,20.352-20.351v-38.664   C406.783,202.894,396.502,202.894,385.623,200.066z M346.896,198.255c0,0-43.219,0-57.928,0c-2.393,0-2.711-2.33-2.711-2.33   V147.67c0,0-0.135-1.853,2.938-1.853c4.133,0,16.529,0,16.529,0c9.959,0,21.855,7.236,26.434,16.079l15.312,31   c0.645,1.248,1.334,2.356,2.072,3.349C350.086,196.973,349.174,198.255,346.896,198.255z"></path>
                                                        <path d="M133.838,205.195c0,5.008-4.097,9.105-9.104,9.105H9.104C4.096,214.3,0,210.203,0,205.195v-5.463   c0-5.007,4.097-9.104,9.104-9.104h115.63c5.008,0,9.104,4.097,9.104,9.104V205.195z"></path>
                                                    </g>
                                                </g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                            </svg> </span>
                                        <span class="elementor-icon-list-text">30 Mins To Your Door</span>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <span class="elementor-icon-list-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" height="512" viewBox="0 0 468 468" width="512">
                                                <g>
                                                    <path d="m110.319 300.11 21.615 13.782 28.638-17.903c2.483-1.551 5.353-2.375 8.283-2.375h.012l88.181.071c13.378-25.606 35.119-45.929 61.57-57.573v-50.244l-52.644-15.617-37.262 82.402c-5.372 12.035-23.112 12.042-28.488 0l-37.263-82.402-52.643 15.617v114.242z"></path>
                                                    <ellipse cx="214.469" cy="77.592" rx="62.804" ry="62.592"></ellipse>
                                                    <path d="m370.684 256.333c-.017 0-.036 0-.053 0-39.192 0-74.246 23.124-89.318 58.928-2.431 5.775-8.098 9.531-14.377 9.531-.005 0-.008 0-.012 0l-93.591-.075-33.198 20.756c-5.109 3.193-11.602 3.164-16.683-.074l-28.005-17.857-28.638 17.903c-5.219 3.264-11.878 3.159-16.991-.277l-23.947-16.076-25.871 25.313 30.824 29.895 232.013.126c6.23.003 11.859 3.703 14.318 9.41 15 34.812 53.942 59.141 94.7 59.163 53.509 0 96.41-45.396 96.144-98.193-.275-54.268-43.93-98.442-97.315-98.473zm1.097 122.917c-13.396 0-24.256-11.007-24.256-24.583s10.86-24.583 24.256-24.583 24.256 11.007 24.256 24.583-10.86 24.583-24.256 24.583z"></path>
                                                </g>
                                            </svg> </span>
                                        <span class="elementor-icon-list-text">20+ Years of Experience</span>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <span class="elementor-icon-list-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="Capa_1" height="512" viewBox="0 0 512 512" width="512">
                                                <g>
                                                    <path d="m60.205 333.045-59.639 59.639c-1.003.956-.628 2.685.77 3.1l71.828 21.819c10.19 3.1 18.13 11.04 21.229 21.229l21.819 71.828c.414 1.398 2.144 1.773 3.1.77l79.798-79.798c-59.041-14.666-107.784-51.474-138.905-98.587z"></path>
                                                    <path d="m511.434 392.684-59.639-59.639c-30.899 46.776-79.491 83.828-138.907 98.588l79.798 79.798c.956 1.003 2.686.628 3.1-.77l21.819-71.828c3.1-10.19 11.04-18.13 21.23-21.229l71.828-21.819c1.402-.417 1.772-2.148.771-3.101z"></path>
                                                    <path d="m460.265 204.268c0-112.627-91.628-204.265-204.265-204.265s-204.265 91.638-204.265 204.265c0 101.835 74.979 186.711 172.876 201.865 124.007 19.29 235.654-77.232 235.654-201.865zm-363.491 49.779c-33.687-107.701 47.407-216.575 159.226-216.575 124.913 0 207.476 134.249 146.586 246.364-70.294 128.951-261.57 110.936-305.812-29.789z"></path>
                                                    <path d="m256 67.471c-75.428 0-136.807 61.369-136.807 136.797 0 110.972 125.817 175.688 215.915 111.547 19.304-13.702 35.024-32.434 45.139-54.319 42.031-90.486-24.972-194.025-124.247-194.025zm69.048 117.608c-1.708 1.268-84.821 62.955-85.298 63.309-6.003 4.474-14.543 3.874-19.88-1.78-38.177-40.686-35.123-37.437-36.039-38.399-6.415-6.831-5.076-17.88 2.95-22.959.01-.01.02-.02.03-.02 5.96-3.74 13.91-2.87 18.9 2.45 8.073 8.604 2.639 2.813 26.889 28.649 2.614-1.949 33.372-24.767 75.688-56.169 6.65-4.94 16.05-3.55 20.979 3.1 4.518 6.064 4.502 15.357-4.219 21.819z"></path>
                                                </g>
                                            </svg> </span>
                                        <span class="elementor-icon-list-text">2-Year Warranty on Locks</span>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <span class="elementor-icon-list-icon">
                                            <i aria-hidden="true" class="fas fa-check"></i> </span>
                                        <span class="elementor-icon-list-text">DBS Checked</span>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <span class="elementor-icon-list-icon">
                                            <i aria-hidden="true" class="far fa-star"></i> </span>
                                        <span class="elementor-icon-list-text">5 Star Google Rating</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-03bbda2 elementor-widget elementor-widget-image" data-id="03bbda2" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img decoding="async" width="512" height="228" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20512%20228'%3E%3C/svg%3E" class="attachment-large size-large wp-image-3854" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/10/trusted-member-of-associations.png 512w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/10/trusted-member-of-associations-300x134.png 300w" data-lazy-sizes="(max-width: 512px) 100vw, 512px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/10/trusted-member-of-associations.png" /><noscript><img decoding="async" width="512" height="228" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/10/trusted-member-of-associations.png" class="attachment-large size-large wp-image-3854" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/10/trusted-member-of-associations.png 512w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/10/trusted-member-of-associations-300x134.png 300w" sizes="(max-width: 512px) 100vw, 512px" /></noscript>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-69c9d80 elementor-widget-mobile__width-initial elementor-widget__width-initial elementor-widget elementor-widget-html" data-id="69c9d80" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="html.default">
                            <div class="elementor-widget-container">
                                <!-- Elfsight Google Reviews | Untitled Google Reviews 2 -->
                                <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/platform.js?ver=1761301859" async></script>
                                <div class="elfsight-app-0a12d82a-7691-41f7-8dc8-d36caec289bf" data-elfsight-app-lazy></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-element elementor-element-36d4e3d e-flex e-con-boxed e-con e-parent" data-id="36d4e3d" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="e-con-inner">
                <div class="elementor-element elementor-element-9e2e2d5 elementor-widget elementor-widget-heading" data-id="9e2e2d5" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <h2 class="elementor-heading-title elementor-size-default">Get Your Fixed Price in Three Easy Steps</h2>
                    </div>
                </div>
                <div class="elementor-element elementor-element-afabd9e e-grid e-con-full e-con e-child" data-id="afabd9e" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                    <div class="elementor-element elementor-element-2980aea elementor-position-top elementor-widget elementor-widget-image-box" data-id="2980aea" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image-box.default">
                        <div class="elementor-widget-container">
                            <style>
                                /*! elementor - v3.22.0 - 26-06-2024 */
                                .elementor-widget-image-box .elementor-image-box-content {
                                    width: 100%
                                }

                                @media (min-width:768px) {

                                    .elementor-widget-image-box.elementor-position-left .elementor-image-box-wrapper,
                                    .elementor-widget-image-box.elementor-position-right .elementor-image-box-wrapper {
                                        display: flex
                                    }

                                    .elementor-widget-image-box.elementor-position-right .elementor-image-box-wrapper {
                                        text-align: end;
                                        flex-direction: row-reverse
                                    }

                                    .elementor-widget-image-box.elementor-position-left .elementor-image-box-wrapper {
                                        text-align: start;
                                        flex-direction: row
                                    }

                                    .elementor-widget-image-box.elementor-position-top .elementor-image-box-img {
                                        margin: auto
                                    }

                                    .elementor-widget-image-box.elementor-vertical-align-top .elementor-image-box-wrapper {
                                        align-items: flex-start
                                    }

                                    .elementor-widget-image-box.elementor-vertical-align-middle .elementor-image-box-wrapper {
                                        align-items: center
                                    }

                                    .elementor-widget-image-box.elementor-vertical-align-bottom .elementor-image-box-wrapper {
                                        align-items: flex-end
                                    }
                                }

                                @media (max-width:767px) {
                                    .elementor-widget-image-box .elementor-image-box-img {
                                        margin-left: auto !important;
                                        margin-right: auto !important;
                                        margin-bottom: 15px
                                    }
                                }

                                .elementor-widget-image-box .elementor-image-box-img {
                                    display: inline-block
                                }

                                .elementor-widget-image-box .elementor-image-box-title a {
                                    color: inherit
                                }

                                .elementor-widget-image-box .elementor-image-box-wrapper {
                                    text-align: center
                                }

                                .elementor-widget-image-box .elementor-image-box-description {
                                    margin: 0
                                }
                            </style>
                            <div class="elementor-image-box-wrapper">
                                <figure class="elementor-image-box-img"><img decoding="async" width="1024" height="1024" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%201024%201024'%3E%3C/svg%3E" class="attachment-full size-full wp-image-3498" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/take-photo.png 1024w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/take-photo-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/take-photo-150x150.png 150w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/take-photo-768x768.png 768w" data-lazy-sizes="(max-width: 1024px) 100vw, 1024px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/take-photo.png" /><noscript><img decoding="async" width="1024" height="1024" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/take-photo.png" class="attachment-full size-full wp-image-3498" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/take-photo.png 1024w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/take-photo-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/take-photo-150x150.png 150w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/take-photo-768x768.png 768w" sizes="(max-width: 1024px) 100vw, 1024px" /></noscript></figure>
                                <div class="elementor-image-box-content">
                                    <h3 class="elementor-image-box-title">1. Take a Photo</h3>
                                    <p class="elementor-image-box-description">Snap a clear picture of the lock or issue</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-30d7f54 elementor-position-top elementor-widget elementor-widget-image-box" data-id="30d7f54" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image-box.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-image-box-wrapper">
                                <figure class="elementor-image-box-img"><a href="https://wa.link/lnrmmz" tabindex="-1"><img loading="lazy" decoding="async" width="1024" height="1024" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%201024%201024'%3E%3C/svg%3E" class="attachment-full size-full wp-image-3497" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/whats-app-message.png 1024w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/whats-app-message-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/whats-app-message-150x150.png 150w" data-lazy-sizes="(max-width: 1024px) 100vw, 1024px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/whats-app-message.png" /><noscript><img loading="lazy" decoding="async" width="1024" height="1024" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/whats-app-message.png" class="attachment-full size-full wp-image-3497" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/whats-app-message.png 1024w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/whats-app-message-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/whats-app-message-150x150.png 150w" sizes="(max-width: 1024px) 100vw, 1024px" /></noscript></a></figure>
                                <div class="elementor-image-box-content">
                                    <h3 class="elementor-image-box-title"><a href="https://wa.link/lnrmmz">2. Send It On WhatsApp</a></h3>
                                    <p class="elementor-image-box-description">Share it with us instantly via WhatsApp</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-a9b30ca elementor-position-top elementor-widget elementor-widget-image-box" data-id="a9b30ca" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image-box.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-image-box-wrapper">
                                <figure class="elementor-image-box-img"><img loading="lazy" decoding="async" width="1024" height="1024" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%201024%201024'%3E%3C/svg%3E" class="attachment-full size-full wp-image-3496" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/fixed-quote.png 1024w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/fixed-quote-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/fixed-quote-150x150.png 150w" data-lazy-sizes="(max-width: 1024px) 100vw, 1024px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/fixed-quote.png" /><noscript><img loading="lazy" decoding="async" width="1024" height="1024" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/fixed-quote.png" class="attachment-full size-full wp-image-3496" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/fixed-quote.png 1024w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/fixed-quote-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/09/fixed-quote-150x150.png 150w" sizes="(max-width: 1024px) 100vw, 1024px" /></noscript></figure>
                                <div class="elementor-image-box-content">
                                    <h3 class="elementor-image-box-title">3. Get a Fixed Price</h3>
                                    <p class="elementor-image-box-description">Receive your no-obligation fixed quote straight away</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-element elementor-element-75b4fb0 elementor-widget__width-initial elementor-mobile-align-center elementor-align-center elementor-widget elementor-widget-button" data-id="75b4fb0" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                    <div class="elementor-widget-container">
                        <div class="elementor-button-wrapper">
                            <a class="elementor-button elementor-button-link elementor-size-sm" href="https://wa.link/lnrmmz">
                                <span class="elementor-button-content-wrapper">
                                    <span class="elementor-button-text">WhatsApp</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-element elementor-element-73d13e5 e-flex e-con-boxed e-con e-parent" data-id="73d13e5" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="e-con-inner">
                <div class="elementor-element elementor-element-0ef19be elementor-widget elementor-widget-heading" data-id="0ef19be" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <h2 class="elementor-heading-title elementor-size-default">Emergency Locksmith Services in Coventry</h2>
                    </div>
                </div>
                <div class="elementor-element elementor-element-a33e58a elementor-widget elementor-widget-text-editor" data-id="a33e58a" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="text-editor.default">
                    <div class="elementor-widget-container">
                        <div class="flex max-w-full flex-col flex-grow">
                            <div class="min-h-8 text-message flex w-full flex-col items-end gap-2 whitespace-normal break-words text-start [.text-message+&amp;]:mt-5" dir="auto" data-message-author-role="assistant" data-message-id="a3a87f1f-0293-4d1d-bba9-ceef0a56754a" data-message-model-slug="gpt-4o">
                                <div class="flex w-full flex-col gap-1 empty:hidden first:pt-[3px]">
                                    <div class="markdown prose w-full break-words dark:prose-invert dark">
                                        <p>Locked out of your home or business? Lost your keys? Our emergency locksmith in Coventry, Vlad, is available <strong data-start="331" data-end="339">24/7</strong> to provide fast and reliable assistance. With a rapid response time of <strong data-start="411" data-end="433">30 minutes or less</strong>, we ensure you regain access to your property quickly and without unnecessary damage.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-element elementor-element-43438f7 e-flex e-con-boxed e-con e-parent" data-id="43438f7" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;gradient&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="e-con-inner">
                <div class="elementor-element elementor-element-50fcbe7d e-con-full e-flex e-con e-child" data-id="50fcbe7d" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                    <div class="elementor-element elementor-element-b04e83e elementor-widget elementor-widget-heading" data-id="b04e83e" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h2 class="elementor-heading-title elementor-size-default">Comprehensive Coventry Locksmith Solutions</h2>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-158bf17 elementor-widget elementor-widget-text-editor" data-id="158bf17" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="text-editor.default">
                        <div class="elementor-widget-container">
                            <div class="flex max-w-full flex-col flex-grow">
                                <div class="min-h-8 text-message flex w-full flex-col items-end gap-2 whitespace-normal break-words text-start [.text-message+&amp;]:mt-5" dir="auto" data-message-author-role="assistant" data-message-id="a3a87f1f-0293-4d1d-bba9-ceef0a56754a" data-message-model-slug="gpt-4o">
                                    <div class="flex w-full flex-col gap-1 empty:hidden first:pt-[3px]">
                                        <div class="markdown prose w-full break-words dark:prose-invert dark">
                                            <p data-start="1562" data-end="1811">At VB Locksmith Services, we offer a full range of <strong data-start="619" data-end="641">locksmith services</strong> for both residential and commercial properties, including:</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-05f84df e-grid e-con-full e-con e-child" data-id="05f84df" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                        <div class="elementor-element elementor-element-9d0f823 elementor-view-default elementor-position-top elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="9d0f823" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-box.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-icon-box-wrapper">

                                    <div class="elementor-icon-box-icon">
                                        <span class="elementor-icon elementor-animation-">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 64 64" width="512">
                                                <g id="_13-Door-handle" data-name="13-Door-handle">
                                                    <path d="m24 43a2.96647 2.96647 0 0 1 -1.50293 2.581 1.00045 1.00045 0 0 0 -.49707.86431v6.55469a1 1 0 0 1 -2 0v-6.55469a1.00045 1.00045 0 0 0 -.49707-.86426 2.96647 2.96647 0 0 1 -1.50293-2.58105 3 3 0 0 1 6 0zm-3-34a11 11 0 1 0 8.49 18h7.51v30a5.00181 5.00181 0 0 1 -5 5h-22a5.00181 5.00181 0 0 1 -5-5v-50a5.00181 5.00181 0 0 1 5-5h22a5.00181 5.00181 0 0 1 5 5v6h-7.51a11.00893 11.00893 0 0 0 -8.49-4zm-5 34a5.00141 5.00141 0 0 0 2 3.98v6.02a3 3 0 0 0 6 0v-6.02a4.99334 4.99334 0 1 0 -8-3.98zm-2-23a7.0077 7.0077 0 0 0 7 7h5.64a9 9 0 1 1 0-14h-5.64a7.0077 7.0077 0 0 0 -7 7zm40-5h-33a5 5 0 0 0 0 10h33a5 5 0 0 0 0-10z"></path>
                                                </g>
                                            </svg> </span>
                                    </div>

                                    <div class="elementor-icon-box-content">

                                        <h3 class="elementor-icon-box-title">
                                            <span>
                                                Emergency Lockout Assistance </span>
                                        </h3>

                                        <p class="elementor-icon-box-description">
                                            Fast, non-destructive entry if you’re locked out of your home or business </p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-252b28b elementor-view-default elementor-position-top elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="252b28b" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-box.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-icon-box-wrapper">

                                    <div class="elementor-icon-box-icon">
                                        <span class="elementor-icon elementor-animation-">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <path d="M481.429,332.892c-26.337-26.357-62.882-37.523-109.815-24.945L204.256,140.419l2.212-8.364   c9.639-36.166-0.776-75.041-27.172-101.437C152.42,3.721,114.212-6.148,78.077,3.778c-5.153,1.415-9.164,5.464-10.529,10.631   c-1.365,5.167,0.132,10.659,3.909,14.438l40.297,40.297c11.781,11.81,11.666,30.724,0.029,42.392   c-11.545,11.576-30.951,11.558-42.45,0.029L29.028,71.257c-3.779-3.781-9.287-5.264-14.454-3.891   c-5.168,1.372-9.202,5.393-10.612,10.551c-9.781,35.738-0.159,74.183,26.846,101.188c26.326,26.345,62.825,37.551,109.786,24.946   l167.371,167.528c-12.49,46.919-1.716,83.11,24.975,109.801c26.91,26.93,65.136,36.726,101.192,26.833   c5.154-1.414,9.166-5.464,10.532-10.631c1.366-5.167-0.13-10.66-3.909-14.44l-40.288-40.288   c-11.781-11.81-11.666-30.726-0.029-42.392c11.689-11.629,31.052-11.444,42.45-0.015l40.308,40.297   c3.779,3.779,9.287,5.262,14.453,3.889c5.167-1.373,9.201-5.392,10.611-10.549C518.041,398.352,508.421,359.897,481.429,332.892z"></path>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M160.551,266.584L17.559,409.594c-23.401,23.401-23.401,61.455,0,84.855c23.401,23.401,61.455,23.401,84.855,0   l142.989-143.006L160.551,266.584z M88.322,447.898c-5.86,5.86-15.35,5.86-21.21,0c-5.859-5.859-5.859-15.351,0-21.21   l90.98-90.997c5.859-5.859,15.352-5.859,21.21,0c5.859,5.859,5.859,15.351,0,21.21L88.322,447.898z"></path>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M507.596,30.253L481.737,4.394c-4.867-4.867-12.42-5.797-18.322-2.258l-79.547,47.723   c-8.37,5.021-9.791,16.568-2.891,23.469l6.332,6.33l-100.98,100.567l42.435,42.435l100.98-100.567l8.919,8.921   c6.901,6.899,18.449,5.479,23.469-2.891l47.723-79.547C513.393,42.673,512.463,35.12,507.596,30.253z"></path>
                                                    </g>
                                                </g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                            </svg> </span>
                                    </div>

                                    <div class="elementor-icon-box-content">

                                        <h3 class="elementor-icon-box-title">
                                            <span>
                                                Lock Repairs and Replacements </span>
                                        </h3>

                                        <p class="elementor-icon-box-description">
                                            Expert repair and replacement of all types of locks, including mortice, cylinder, and rim locks </p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-65ee4b8 elementor-view-default elementor-position-top elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="65ee4b8" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-box.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-icon-box-wrapper">

                                    <div class="elementor-icon-box-icon">
                                        <span class="elementor-icon elementor-animation-">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="icons" viewBox="0 0 60 60" width="512" height="512">
                                                <path d="M48.6,43.392c-.277-.093-.532-.18-.767-.276a10.491,10.491,0,0,1-4.208-2.8,9.639,9.639,0,0,1-1.143,1.62C40.126,44.574,35.366,49,30,49s-10.126-4.426-12.485-7.063a9.653,9.653,0,0,1-1.144-1.621,10.512,10.512,0,0,1-4.217,2.8c-.225.092-.48.179-.736.264A1.882,1.882,0,0,0,10.041,45.5C11.685,52.742,20.078,58,30,58s18.315-5.258,19.958-12.5A1.873,1.873,0,0,0,48.6,43.392Z"></path>
                                                <path d="M37.193,26A2.671,2.671,0,0,0,40,23.5,2.671,2.671,0,0,0,37.193,21c-1.781,0-3.522,1.732-4.189,2.478C33.67,24.266,35.412,26,37.193,26Z"></path>
                                                <path d="M22.818,26c1.759,0,3.487-1.69,4.149-2.417a.121.121,0,0,0,0-.167C26.305,22.689,24.578,21,22.818,21A2.678,2.678,0,0,0,20,23.5,2.678,2.678,0,0,0,22.818,26Z"></path>
                                                <path d="M27,37a3,3,0,0,0,3,3h0a3,3,0,0,0,3-3,1,1,0,0,0-1-1H28A1,1,0,0,0,27,37Z"></path>
                                                <path d="M15.079,17.8l2.028,18.631a7.692,7.692,0,0,0,1.9,4.173c2.138,2.388,6.407,6.4,11,6.4S38.857,42.992,41,40.6a7.7,7.7,0,0,0,1.9-4.188L44.921,17.8A13.757,13.757,0,0,0,41.4,7.009a15.468,15.468,0,0,0-22.792,0A13.757,13.757,0,0,0,15.079,17.8Zm16.433,4.346C32.562,20.971,34.7,19,37.193,19A4.668,4.668,0,0,1,42,23.5,4.668,4.668,0,0,1,37.193,28c-2.5,0-4.631-1.972-5.681-3.147A2.056,2.056,0,0,1,31.512,22.146ZM32,34a3,3,0,0,1,3,3,5,5,0,0,1-10,0,3,3,0,0,1,3-3ZM22.818,19c2.466,0,4.584,1.924,5.627,3.07h0a2.116,2.116,0,0,1,0,2.858C27.4,26.076,25.283,28,22.818,28A4.674,4.674,0,0,1,18,23.5,4.674,4.674,0,0,1,22.818,19Z"></path>
                                            </svg> </span>
                                    </div>

                                    <div class="elementor-icon-box-content">

                                        <h3 class="elementor-icon-box-title">
                                            <span>
                                                Burglary Repairs </span>
                                        </h3>

                                        <p class="elementor-icon-box-description">
                                            Restoring security after a break-in with expert lock and door repairs </p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-e9a23e6 elementor-view-default elementor-position-top elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="e9a23e6" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-box.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-icon-box-wrapper">

                                    <div class="elementor-icon-box-icon">
                                        <span class="elementor-icon elementor-animation-">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" height="512" viewBox="0 0 55 55" width="512" data-name="Layer 1">
                                                <path d="m42.44 6.37h-8.59v42.27h8.59a.75.75 0 0 0 .75-.75v-40.77a.76.76 0 0 0 -.75-.75z"></path>
                                                <path d="m32.27 2.91a.77.77 0 0 0 -.64-.14l-19.24 4.45a.75.75 0 0 0 -.58.73v39.11a.75.75 0 0 0 .58.73l19.24 4.44h.17a.81.81 0 0 0 .47-.16.76.76 0 0 0 .28-.59v-47.98a.76.76 0 0 0 -.28-.59zm-4.4 22.09v2.09a.75.75 0 0 1 -1.5 0v-2.09a1.48 1.48 0 1 1 2.23-1.3 1.49 1.49 0 0 1 -.73 1.3z"></path>
                                            </svg> </span>
                                    </div>

                                    <div class="elementor-icon-box-content">

                                        <h3 class="elementor-icon-box-title">
                                            <span>
                                                uPVC Door & Window Locks Repair and Replacement </span>
                                        </h3>

                                        <p class="elementor-icon-box-description">
                                            Professional solutions for damaged or faulty locks on uPVC doors and windows </p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-359c552 elementor-view-default elementor-position-top elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="359c552" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-box.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-icon-box-wrapper">

                                    <div class="elementor-icon-box-icon">
                                        <span class="elementor-icon elementor-animation-">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" height="512" viewBox="0 0 468 468" width="512">
                                                <g>
                                                    <path d="m110.319 300.11 21.615 13.782 28.638-17.903c2.483-1.551 5.353-2.375 8.283-2.375h.012l88.181.071c13.378-25.606 35.119-45.929 61.57-57.573v-50.244l-52.644-15.617-37.262 82.402c-5.372 12.035-23.112 12.042-28.488 0l-37.263-82.402-52.643 15.617v114.242z"></path>
                                                    <ellipse cx="214.469" cy="77.592" rx="62.804" ry="62.592"></ellipse>
                                                    <path d="m370.684 256.333c-.017 0-.036 0-.053 0-39.192 0-74.246 23.124-89.318 58.928-2.431 5.775-8.098 9.531-14.377 9.531-.005 0-.008 0-.012 0l-93.591-.075-33.198 20.756c-5.109 3.193-11.602 3.164-16.683-.074l-28.005-17.857-28.638 17.903c-5.219 3.264-11.878 3.159-16.991-.277l-23.947-16.076-25.871 25.313 30.824 29.895 232.013.126c6.23.003 11.859 3.703 14.318 9.41 15 34.812 53.942 59.141 94.7 59.163 53.509 0 96.41-45.396 96.144-98.193-.275-54.268-43.93-98.442-97.315-98.473zm1.097 122.917c-13.396 0-24.256-11.007-24.256-24.583s10.86-24.583 24.256-24.583 24.256 11.007 24.256 24.583-10.86 24.583-24.256 24.583z"></path>
                                                </g>
                                            </svg> </span>
                                    </div>

                                    <div class="elementor-icon-box-content">

                                        <h3 class="elementor-icon-box-title">
                                            <span>
                                                New Locks Installation </span>
                                        </h3>

                                        <p class="elementor-icon-box-description">
                                            High-quality lock fitting to enhance the security of your property </p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-da772f2 elementor-view-default elementor-position-top elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="da772f2" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-box.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-icon-box-wrapper">

                                    <div class="elementor-icon-box-icon">
                                        <span class="elementor-icon elementor-animation-">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <path d="M250.435,205.913c-27.619,0-50.087,22.468-50.087,50.087s22.468,50.087,50.087,50.087   c27.619,0,50.087-22.468,50.087-50.087S278.054,205.913,250.435,205.913z M250.435,272.696c-9.22,0-16.696-7.475-16.696-16.696   c0-9.22,7.475-16.696,16.696-16.696c9.22,0,16.696,7.475,16.696,16.696C267.13,265.22,259.655,272.696,250.435,272.696z"></path>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M461.913,38.957H116.87c-27.619,0-50.087,22.468-50.087,50.087v16.696H16.696C7.479,105.739,0,113.218,0,122.435v100.174   c0,9.217,7.479,16.696,16.696,16.696h50.087v33.391H16.696C7.479,272.696,0,280.174,0,289.391v100.174   c0,9.217,7.479,16.696,16.696,16.696h50.087v16.696c0,27.619,22.468,50.087,50.087,50.087h345.043   c27.619,0,50.087-22.468,50.087-50.087V89.044C512,61.424,489.532,38.957,461.913,38.957z M333.913,256   c0,16.99-5.129,32.79-13.879,45.991l42.379,42.379c6.521,6.521,6.521,17.087,0,23.609c-6.522,6.522-17.086,6.522-23.609,0   L296.426,325.6c-13.201,8.75-29.001,13.879-45.991,13.879s-32.79-5.129-45.991-13.879l-42.379,42.379   c-6.522,6.522-17.086,6.522-23.609,0c-6.521-6.521-6.521-17.087,0-23.609l42.379-42.379c-8.75-13.201-13.879-29.001-13.879-45.991   s5.129-32.79,13.879-45.991l-42.379-42.379c-6.521-6.521-6.521-17.087,0-23.609c6.521-6.521,17.087-6.521,23.609,0l42.379,42.379   c13.201-8.75,29.001-13.879,45.991-13.879s32.79,5.129,45.991,13.879l42.379-42.379c6.521-6.521,17.087-6.521,23.609,0   c6.521,6.521,6.521,17.087,0,23.609l-42.379,42.379C328.784,223.21,333.913,239.011,333.913,256z M445.217,322.783   c0,9.217-7.479,16.696-16.696,16.696s-16.696-7.479-16.696-16.696V189.217c0-9.217,7.479-16.696,16.696-16.696   s16.696,7.479,16.696,16.696V322.783z"></path>
                                                    </g>
                                                </g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                            </svg> </span>
                                    </div>

                                    <div class="elementor-icon-box-content">

                                        <h3 class="elementor-icon-box-title">
                                            <span>
                                                Safe Opening Services </span>
                                        </h3>

                                        <p class="elementor-icon-box-description">
                                            Skilled and damage-free access to locked safes when you’ve lost the key or combination </p>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-element elementor-element-93befe1 e-flex e-con-boxed e-con e-parent" data-id="93befe1" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="e-con-inner">
                <div class="elementor-element elementor-element-14a4655 elementor-widget elementor-widget-heading" data-id="14a4655" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <h2 class="elementor-heading-title elementor-size-default">Why Choose Vlad, Your Coventry Locksmith?</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-element elementor-element-6c92cce e-flex e-con-boxed e-con e-parent" data-id="6c92cce" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="e-con-inner">
                <div class="elementor-element elementor-element-1866860 e-con-full e-flex e-con e-child" data-id="1866860" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                    <div class="elementor-element elementor-element-311dfd8 elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="311dfd8" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-list.default">
                        <div class="elementor-widget-container">
                            <ul class="elementor-icon-list-items">
                                <li class="elementor-icon-list-item">
                                    <span class="elementor-icon-list-icon">
                                        <i aria-hidden="true" class="fas fa-check"></i> </span>
                                    <span class="elementor-icon-list-text">No call-out fees – you only pay when the job is completed</span>
                                </li>
                                <li class="elementor-icon-list-item">
                                    <span class="elementor-icon-list-icon">
                                        <i aria-hidden="true" class="fas fa-check"></i> </span>
                                    <span class="elementor-icon-list-text">Fast response – typically 15–30 minutes</span>
                                </li>
                                <li class="elementor-icon-list-item">
                                    <span class="elementor-icon-list-icon">
                                        <i aria-hidden="true" class="fas fa-check"></i> </span>
                                    <span class="elementor-icon-list-text">Guaranteed workmanship – 90-day guarantee on all services</span>
                                </li>
                                <li class="elementor-icon-list-item">
                                    <span class="elementor-icon-list-icon">
                                        <i aria-hidden="true" class="fas fa-check"></i> </span>
                                    <span class="elementor-icon-list-text">DBS-checked and fully insured locksmith</span>
                                </li>
                                <li class="elementor-icon-list-item">
                                    <span class="elementor-icon-list-icon">
                                        <i aria-hidden="true" class="fas fa-check"></i> </span>
                                    <span class="elementor-icon-list-text">Card payments accepted – pay securely with your card</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-04c7304 elementor-widget elementor-widget-heading" data-id="04c7304" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h2 class="elementor-heading-title elementor-size-default">Non-Destructive Entry Experts</h2>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-555d22c elementor-widget elementor-widget-text-editor" data-id="555d22c" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="text-editor.default">
                        <div class="elementor-widget-container">
                            <div class="flex max-w-full flex-col flex-grow">
                                <div class="min-h-8 text-message flex w-full flex-col items-end gap-2 whitespace-normal break-words text-start [.text-message+&amp;]:mt-5" dir="auto" data-message-author-role="assistant" data-message-id="a3a87f1f-0293-4d1d-bba9-ceef0a56754a" data-message-model-slug="gpt-4o">
                                    <div class="flex w-full flex-col gap-1 empty:hidden first:pt-[3px]">
                                        <div class="markdown prose w-full break-words dark:prose-invert dark">
                                            <p data-start="1562" data-end="1811">We use <strong data-start="1569" data-end="1604">the latest locksmith techniques</strong> to gain access to your property <strong data-start="1637" data-end="1663">without causing damage</strong> to your doors or locks. Whether you’ve lost your keys or your lock is malfunctioning, we aim to resolve the issue efficiently and professionally.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-element elementor-element-ecb3939 e-con-full e-flex e-con e-child" data-id="ecb3939" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                    <div class="elementor-element elementor-element-5a6a1a6 elementor-widget elementor-widget-image" data-id="5a6a1a6" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                            <img loading="lazy" decoding="async" width="252" height="300" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20252%20300'%3E%3C/svg%3E" class="attachment-medium size-medium wp-image-1083" alt="2024 award locksmiths coventry" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2024/10/2024-award-252x300.webp 252w, https://vblocksmithservices.co.uk/wp-content/uploads/2024/10/2024-award.webp 678w" data-lazy-sizes="(max-width: 252px) 100vw, 252px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2024/10/2024-award-252x300.webp" /><noscript><img loading="lazy" decoding="async" width="252" height="300" src="https://vblocksmithservices.co.uk/wp-content/uploads/2024/10/2024-award-252x300.webp" class="attachment-medium size-medium wp-image-1083" alt="2024 award locksmiths coventry" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2024/10/2024-award-252x300.webp 252w, https://vblocksmithservices.co.uk/wp-content/uploads/2024/10/2024-award.webp 678w" sizes="(max-width: 252px) 100vw, 252px" /></noscript>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-element elementor-element-c4457b2 e-flex e-con-boxed e-con e-parent" data-id="c4457b2" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="e-con-inner">
                <div class="elementor-element elementor-element-3b5aa8ac e-con-full e-flex e-con e-child" data-id="3b5aa8ac" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                    <div class="elementor-element elementor-element-16a6aa04 elementor-widget elementor-widget-heading" data-id="16a6aa04" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <p class="elementor-heading-title elementor-size-default">Call VB Locksmith Services Ltd today to see what we can do for you:</p>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-548934b7 elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="548934b7" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-list.default">
                        <div class="elementor-widget-container">
                            <ul class="elementor-icon-list-items">
                                <li class="elementor-icon-list-item">
                                    <a href="tel:07883444240">

                                        <span class="elementor-icon-list-icon">
                                            <i aria-hidden="true" class="fas fa-phone"></i> </span>
                                        <span class="elementor-icon-list-text">07883444240</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-4d44c8fe e-grid e-con-full elementor-hidden-desktop elementor-hidden-tablet e-con e-child" data-id="4d44c8fe" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                        <div class="elementor-element elementor-element-1655fc5a elementor-widget elementor-widget-image" data-id="1655fc5a" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img loading="lazy" decoding="async" width="400" height="400" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20400%20400'%3E%3C/svg%3E" class="attachment-large size-large wp-image-2339" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1.png 400w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1-150x150.png 150w" data-lazy-sizes="(max-width: 400px) 100vw, 400px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1.png" /><noscript><img loading="lazy" decoding="async" width="400" height="400" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1.png" class="attachment-large size-large wp-image-2339" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1.png 400w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1-150x150.png 150w" sizes="(max-width: 400px) 100vw, 400px" /></noscript>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-30abe234 elementor-widget elementor-widget-image" data-id="30abe234" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img loading="lazy" decoding="async" width="400" height="400" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20400%20400'%3E%3C/svg%3E" class="attachment-large size-large wp-image-2340" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1.png 400w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1-150x150.png 150w" data-lazy-sizes="(max-width: 400px) 100vw, 400px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1.png" /><noscript><img loading="lazy" decoding="async" width="400" height="400" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1.png" class="attachment-large size-large wp-image-2340" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1.png 400w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1-150x150.png 150w" sizes="(max-width: 400px) 100vw, 400px" /></noscript>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-3755202 elementor-view-default elementor-position-top elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="3755202" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-box.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-icon-box-wrapper">

                                    <div class="elementor-icon-box-icon">
                                        <span class="elementor-icon elementor-animation-">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" width="612px" height="612px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
                                                <g>
                                                    <path d="M339.296,19.573c-92.187,0-173.778,46.021-223.165,116.25c10.221,6.668,20.184,15.812,29.82,27.567  c36.844-55.091,96.417-93.743,165.344-102.052l27.997,47.377l27.997-47.378c105.952,12.769,190.17,96.983,202.945,202.934  l-47.385,28.001l47.386,28.002c-8.54,70.836-49.108,131.821-106.661,168.372c3.666,8.357,5.731,16.902,6.096,25.712  c0.269,6.438-0.395,12.521-1.73,18.296C553.609,486.622,612,396.153,612,292.277C612,141.904,489.669,19.573,339.296,19.573z   M408.887,468.69c-62.865-50.523-80.444-25.688-108.968,2.832c-19.914,19.921-70.308-21.678-113.821-65.193  c-43.516-43.521-85.107-93.907-65.195-113.824c28.526-28.521,53.354-46.11,2.817-108.958  c-50.52-62.871-84.198-14.603-111.829,13.03c-31.9,31.889-1.681,150.726,115.769,268.194  c117.466,117.452,236.302,147.651,268.183,115.774C423.471,552.911,471.753,519.237,408.887,468.69z M217.426,334.954  c0-55.88,65.42-65.835,65.42-87.641c0-10.581-8.47-15.023-16.304-15.023c-14.397,0-22.434,16.083-22.434,16.083l-27.521-18.415  c0,0,13.973-32.6,53.346-32.6c24.766,0,51.65,14.185,51.65,46.36c0,47.208-60.968,56.314-62.028,75.789h64.36v31.751H219.123  C218.062,345.332,217.426,340.043,217.426,334.954z M339.391,292.199l58.434-92.305h45.301v84.047h17.991v30.691h-17.991v36.628  h-36.628v-36.628h-67.106V292.199L339.391,292.199z M406.497,283.94v-33.871c0-8.258,1.272-16.94,1.272-16.94h-0.424  c0,0-3.171,9.318-7.41,15.669l-22.857,34.72v0.424L406.497,283.94L406.497,283.94z"></path>
                                                </g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                            </svg> </span>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-element elementor-element-5216cea7 e-con-full e-flex e-con e-child" data-id="5216cea7" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                    <div class="elementor-element elementor-element-698effd2 e-grid e-con-full e-con e-child" data-id="698effd2" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                        <div class="elementor-element elementor-element-1f9ba4de elementor-hidden-mobile elementor-widget elementor-widget-image" data-id="1f9ba4de" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img loading="lazy" decoding="async" width="400" height="400" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20400%20400'%3E%3C/svg%3E" class="attachment-large size-large wp-image-2339" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1.png 400w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1-150x150.png 150w" data-lazy-sizes="(max-width: 400px) 100vw, 400px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1.png" /><noscript><img loading="lazy" decoding="async" width="400" height="400" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1.png" class="attachment-large size-large wp-image-2339" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1.png 400w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/pngegg_4_1-1-1-150x150.png 150w" sizes="(max-width: 400px) 100vw, 400px" /></noscript>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-2e773b17 elementor-hidden-mobile elementor-widget elementor-widget-image" data-id="2e773b17" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image.default">
                            <div class="elementor-widget-container">
                                <img loading="lazy" decoding="async" width="400" height="400" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20400%20400'%3E%3C/svg%3E" class="attachment-large size-large wp-image-2340" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1.png 400w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1-150x150.png 150w" data-lazy-sizes="(max-width: 400px) 100vw, 400px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1.png" /><noscript><img loading="lazy" decoding="async" width="400" height="400" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1.png" class="attachment-large size-large wp-image-2340" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1.png 400w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1-300x300.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/dbs-checked-icon-rou-1-1-150x150.png 150w" sizes="(max-width: 400px) 100vw, 400px" /></noscript>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-7282101c elementor-hidden-mobile elementor-view-default elementor-position-top elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="7282101c" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-box.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-icon-box-wrapper">

                                    <div class="elementor-icon-box-icon">
                                        <span class="elementor-icon elementor-animation-">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" width="612px" height="612px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
                                                <g>
                                                    <path d="M339.296,19.573c-92.187,0-173.778,46.021-223.165,116.25c10.221,6.668,20.184,15.812,29.82,27.567  c36.844-55.091,96.417-93.743,165.344-102.052l27.997,47.377l27.997-47.378c105.952,12.769,190.17,96.983,202.945,202.934  l-47.385,28.001l47.386,28.002c-8.54,70.836-49.108,131.821-106.661,168.372c3.666,8.357,5.731,16.902,6.096,25.712  c0.269,6.438-0.395,12.521-1.73,18.296C553.609,486.622,612,396.153,612,292.277C612,141.904,489.669,19.573,339.296,19.573z   M408.887,468.69c-62.865-50.523-80.444-25.688-108.968,2.832c-19.914,19.921-70.308-21.678-113.821-65.193  c-43.516-43.521-85.107-93.907-65.195-113.824c28.526-28.521,53.354-46.11,2.817-108.958  c-50.52-62.871-84.198-14.603-111.829,13.03c-31.9,31.889-1.681,150.726,115.769,268.194  c117.466,117.452,236.302,147.651,268.183,115.774C423.471,552.911,471.753,519.237,408.887,468.69z M217.426,334.954  c0-55.88,65.42-65.835,65.42-87.641c0-10.581-8.47-15.023-16.304-15.023c-14.397,0-22.434,16.083-22.434,16.083l-27.521-18.415  c0,0,13.973-32.6,53.346-32.6c24.766,0,51.65,14.185,51.65,46.36c0,47.208-60.968,56.314-62.028,75.789h64.36v31.751H219.123  C218.062,345.332,217.426,340.043,217.426,334.954z M339.391,292.199l58.434-92.305h45.301v84.047h17.991v30.691h-17.991v36.628  h-36.628v-36.628h-67.106V292.199L339.391,292.199z M406.497,283.94v-33.871c0-8.258,1.272-16.94,1.272-16.94h-0.424  c0,0-3.171,9.318-7.41,15.669l-22.857,34.72v0.424L406.497,283.94L406.497,283.94z"></path>
                                                </g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                            </svg> </span>
                                    </div>

                                    <div class="elementor-icon-box-content">


                                        <p class="elementor-icon-box-description">
                                            24/7 Service </p>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-element elementor-element-36b44d0 e-flex e-con-boxed e-con e-parent" data-id="36b44d0" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="e-con-inner">
                <div class="elementor-element elementor-element-b7b1164 elementor-widget elementor-widget-heading" data-id="b7b1164" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <p class="elementor-heading-title elementor-size-default">Professional. Trusted. Secure.</p>
                    </div>
                </div>
                <div class="elementor-element elementor-element-f061e3e elementor-widget elementor-widget-heading" data-id="f061e3e" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <h2 class="elementor-heading-title elementor-size-default">What Our Customers Say</h2>
                    </div>
                </div>
                <div class="elementor-element elementor-element-0c0ae88 elementor-widget elementor-widget-html" data-id="0c0ae88" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="html.default">
                    <div class="elementor-widget-container">
                        <!-- Elfsight Google Reviews | Untitled Google Reviews -->
                        <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/platform.js?ver=1761301859" async></script>
                        <div class="elfsight-app-94b7b319-816c-4b84-a9ce-36d4e4587430" data-elfsight-app-lazy></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-element elementor-element-8f7b2ae e-flex e-con-boxed e-con e-parent" data-id="8f7b2ae" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="e-con-inner">
                <div class="elementor-element elementor-element-a4c917b elementor-widget elementor-widget-heading" data-id="a4c917b" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <h2 class="elementor-heading-title elementor-size-default">In Addition to Coventry, Vlad Also covers</h2>
                    </div>
                </div>
                <div class="elementor-element elementor-element-cdba9f5 e-grid e-con-full e-con e-child" data-id="cdba9f5" data-element_type="container" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                    <div class="elementor-element elementor-element-42f8c6a elementor-align-center elementor-widget elementor-widget-button" data-id="42f8c6a" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a class="elementor-button elementor-button-link elementor-size-sm" href="/areas-we-cover/atherstone/">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Atherstone</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-b7133b3 elementor-align-center elementor-widget elementor-widget-button" data-id="b7133b3" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a class="elementor-button elementor-button-link elementor-size-sm" href="/areas-we-cover/bedworth/">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Bedworth</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-d115af2 elementor-align-center elementor-widget elementor-widget-button" data-id="d115af2" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a class="elementor-button elementor-button-link elementor-size-sm" href="/areas-we-cover/binley-woods/">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Binley Woods</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-54dde71 elementor-align-center elementor-widget elementor-widget-button" data-id="54dde71" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a class="elementor-button elementor-button-link elementor-size-sm" href="/areas-we-cover/kenilworth/">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Kenilworth</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-0b9990a elementor-align-center elementor-widget elementor-widget-button" data-id="0b9990a" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a class="elementor-button elementor-button-link elementor-size-sm" href="/areas-we-cover/leamington-spa/">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Leamington Spa</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-dbf7f3a elementor-align-center elementor-widget elementor-widget-button" data-id="dbf7f3a" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a class="elementor-button elementor-button-link elementor-size-sm" href="/areas-we-cover/nuneaton/">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Nuneaton</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-4fe084e elementor-align-center elementor-widget elementor-widget-button" data-id="4fe084e" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a class="elementor-button elementor-button-link elementor-size-sm" href="/areas-we-cover/rugby/">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Rugby</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-f427184 elementor-align-center elementor-widget elementor-widget-button" data-id="f427184" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a class="elementor-button elementor-button-link elementor-size-sm" href="/areas-we-cover/ryton-on-dunsmore/">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Ryton On Dunsmore</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-9bd6644 elementor-align-center elementor-widget elementor-widget-button" data-id="9bd6644" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a class="elementor-button elementor-button-link elementor-size-sm" href="/areas-we-cover/solihull/">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Solihull</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-d2fcac8 elementor-align-center elementor-widget elementor-widget-button" data-id="d2fcac8" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a class="elementor-button elementor-button-link elementor-size-sm" href="/areas-we-cover/stretton-on-dunsmore/">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Stretton On Dunsmore</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-26173fe elementor-align-center elementor-widget elementor-widget-button" data-id="26173fe" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a class="elementor-button elementor-button-link elementor-size-sm" href="/areas-we-cover/warwick/">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text">Warwick</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="elementor-element elementor-element-d1bcf85 e-flex e-con-boxed e-con e-parent" data-id="d1bcf85" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="e-con-inner">
                <div class="elementor-element elementor-element-eb6492d e-con-full e-flex e-con e-child" data-id="eb6492d" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                    <div class="elementor-element elementor-element-37df7c4 elementor-widget elementor-widget-heading" data-id="37df7c4" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h2 class="elementor-heading-title elementor-size-default">Get in Touch</h2>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-dceeccb elementor-widget elementor-widget-text-editor" data-id="dceeccb" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="text-editor.default">
                        <div class="elementor-widget-container">
                            <p>Need professional locksmith solutions? Our team is ready to assist you. </p>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-c058f8b elementor-widget elementor-widget-wpforms" data-id="c058f8b" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="wpforms.default">
                        <div class="elementor-widget-container">
                            <style id="wpforms-css-vars-root">
                                :root {
                                    --wpforms-field-border-radius: 3px;
                                    --wpforms-field-border-style: solid;
                                    --wpforms-field-border-size: 1px;
                                    --wpforms-field-background-color: #ffffff;
                                    --wpforms-field-border-color: rgba(0, 0, 0, 0.25);
                                    --wpforms-field-border-color-spare: rgba(0, 0, 0, 0.25);
                                    --wpforms-field-text-color: rgba(0, 0, 0, 0.7);
                                    --wpforms-field-menu-color: #ffffff;
                                    --wpforms-label-color: rgba(0, 0, 0, 0.85);
                                    --wpforms-label-sublabel-color: rgba(0, 0, 0, 0.55);
                                    --wpforms-label-error-color: #d63637;
                                    --wpforms-button-border-radius: 3px;
                                    --wpforms-button-border-style: none;
                                    --wpforms-button-border-size: 1px;
                                    --wpforms-button-background-color: #066aab;
                                    --wpforms-button-border-color: #066aab;
                                    --wpforms-button-text-color: #ffffff;
                                    --wpforms-page-break-color: #066aab;
                                    --wpforms-background-image: none;
                                    --wpforms-background-position: center center;
                                    --wpforms-background-repeat: no-repeat;
                                    --wpforms-background-size: cover;
                                    --wpforms-background-width: 100px;
                                    --wpforms-background-height: 100px;
                                    --wpforms-background-color: rgba(0, 0, 0, 0);
                                    --wpforms-background-url: none;
                                    --wpforms-container-padding: 0px;
                                    --wpforms-container-border-style: none;
                                    --wpforms-container-border-width: 1px;
                                    --wpforms-container-border-color: #000000;
                                    --wpforms-container-border-radius: 3px;
                                    --wpforms-field-size-input-height: 43px;
                                    --wpforms-field-size-input-spacing: 15px;
                                    --wpforms-field-size-font-size: 16px;
                                    --wpforms-field-size-line-height: 19px;
                                    --wpforms-field-size-padding-h: 14px;
                                    --wpforms-field-size-checkbox-size: 16px;
                                    --wpforms-field-size-sublabel-spacing: 5px;
                                    --wpforms-field-size-icon-size: 1;
                                    --wpforms-label-size-font-size: 16px;
                                    --wpforms-label-size-line-height: 19px;
                                    --wpforms-label-size-sublabel-font-size: 14px;
                                    --wpforms-label-size-sublabel-line-height: 17px;
                                    --wpforms-button-size-font-size: 17px;
                                    --wpforms-button-size-height: 41px;
                                    --wpforms-button-size-padding-h: 15px;
                                    --wpforms-button-size-margin-top: 10px;
                                    --wpforms-container-shadow-size-box-shadow: none;

                                }
                            </style>
                            <style id="wpforms-css-vars-elementor-widget-c058f8b">
                                .elementor-widget-wpforms.elementor-element-c058f8b {
                                    --wpforms-field-size-input-height: 43px;
                                    --wpforms-field-size-input-spacing: 15px;
                                    --wpforms-field-size-font-size: 16px;
                                    --wpforms-field-size-line-height: 19px;
                                    --wpforms-field-size-padding-h: 14px;
                                    --wpforms-field-size-checkbox-size: 16px;
                                    --wpforms-field-size-sublabel-spacing: 5px;
                                    --wpforms-field-size-icon-size: 1;
                                    --wpforms-label-size-font-size: 16px;
                                    --wpforms-label-size-line-height: 19px;
                                    --wpforms-label-size-sublabel-font-size: 14px;
                                    --wpforms-label-size-sublabel-line-height: 17px;
                                    --wpforms-button-size-font-size: 17px;
                                    --wpforms-button-size-height: 41px;
                                    --wpforms-button-size-padding-h: 15px;
                                    --wpforms-button-size-margin-top: 10px;

                                }
                            </style>
                            <div class="wpforms-container wpforms-container-full wpforms-render-modern" id="wpforms-213">
                                <form id="wpforms-form-213" class="wpforms-validate wpforms-form wpforms-ajax-form" data-formid="213" method="post" enctype="multipart/form-data" action="/areas-we-cover/coventry/" data-token="66050643611a908aa4c2d6309a7d7dd2" data-token-time="1761562863"><noscript class="wpforms-error-noscript">Please enable JavaScript in your browser to complete this form.</noscript>
                                    <div class="wpforms-hidden" id="wpforms-error-noscript">Please enable JavaScript in your browser to complete this form.</div>
                                    <div class="wpforms-field-container">
                                        <div id="wpforms-213-field_0-container" class="wpforms-field wpforms-field-name" data-field-id="0"><label class="wpforms-field-label" for="wpforms-213-field_0">Name <span class="wpforms-required-label" aria-hidden="true">*</span></label><input type="text" id="wpforms-213-field_0" class="wpforms-field-large wpforms-field-required" name="wpforms[fields][0]" aria-errormessage="wpforms-213-field_0-error" required></div>
                                        <div id="wpforms-213-field_4-container" class="wpforms-field wpforms-field-email" data-field-id="4"><label class="wpforms-field-label" for="wpforms-213-field_4">Email <span class="wpforms-required-label" aria-hidden="true">*</span></label><input type="email" id="wpforms-213-field_4" class="wpforms-field-large wpforms-field-required" name="wpforms[fields][4]" spellcheck="false" aria-errormessage="wpforms-213-field_4-error" required></div>
                                        <div id="wpforms-213-field_3-container" class="wpforms-field wpforms-field-phone" data-field-id="3"><label class="wpforms-field-label" for="wpforms-213-field_3">Phone</label><input type="tel" id="wpforms-213-field_3" class="wpforms-field-large wpforms-smart-phone-field" data-rule-smart-phone-field="true" name="wpforms[fields][3]" aria-errormessage="wpforms-213-field_3-error"></div>
                                        <div id="wpforms-213-field_2-container" class="wpforms-field wpforms-field-textarea" data-field-id="2"><label class="wpforms-field-label" for="wpforms-213-field_2">Comment or Message</label><textarea id="wpforms-213-field_2" class="wpforms-field-small" name="wpforms[fields][2]" aria-errormessage="wpforms-213-field_2-error"></textarea></div>
                                    </div><!-- .wpforms-field-container -->
                                    <div class="wpforms-submit-container"><input type="hidden" name="wpforms[id]" value="213"><input type="hidden" name="page_title" value="Coventry"><input type="hidden" name="page_url" value="https://vblocksmithservices.co.uk/areas-we-cover/coventry/"><input type="hidden" name="page_id" value="2148"><input type="hidden" name="wpforms[post_id]" value="2148"><button type="submit" name="wpforms[submit]" id="wpforms-submit-213" class="wpforms-submit" data-alt-text="Sending…" data-submit-text="Submit" aria-live="assertive" value="wpforms-submit">Submit</button><img loading="lazy" decoding="async" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2026%2026'%3E%3C/svg%3E" class="wpforms-submit-spinner" style="display: none;" width="26" height="26" alt="Loading" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/plugins/wpforms/assets/images/submit-spin.svg"><noscript><img loading="lazy" decoding="async" src="https://vblocksmithservices.co.uk/wp-content/plugins/wpforms/assets/images/submit-spin.svg" class="wpforms-submit-spinner" style="display: none;" width="26" height="26" alt="Loading"></noscript></div>
                                </form>
                            </div> <!-- .wpforms-container -->
                        </div>
                    </div>
                </div>
                <div class="elementor-element elementor-element-5071c58 e-con-full e-flex e-con e-child" data-id="5071c58" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                    <div class="elementor-element elementor-element-9c706de elementor-widget elementor-widget-heading" data-id="9c706de" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <p class="elementor-heading-title elementor-size-default">Learn More From</p>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-4923314 elementor-widget elementor-widget-heading" data-id="4923314" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h2 class="elementor-heading-title elementor-size-default">Frequently Asked Questions</h2>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-17cf2b6 elementor-widget elementor-widget-accordion" data-id="17cf2b6" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="accordion.default">
                        <div class="elementor-widget-container">
                            <style>
                                /*! elementor - v3.22.0 - 26-06-2024 */
                                .elementor-accordion {
                                    text-align: start
                                }

                                .elementor-accordion .elementor-accordion-item {
                                    border: 1px solid #d5d8dc
                                }

                                .elementor-accordion .elementor-accordion-item+.elementor-accordion-item {
                                    border-top: none
                                }

                                .elementor-accordion .elementor-tab-title {
                                    margin: 0;
                                    padding: 15px 20px;
                                    font-weight: 700;
                                    line-height: 1;
                                    cursor: pointer;
                                    outline: none
                                }

                                .elementor-accordion .elementor-tab-title .elementor-accordion-icon {
                                    display: inline-block;
                                    width: 1.5em
                                }

                                .elementor-accordion .elementor-tab-title .elementor-accordion-icon svg {
                                    width: 1em;
                                    height: 1em
                                }

                                .elementor-accordion .elementor-tab-title .elementor-accordion-icon.elementor-accordion-icon-right {
                                    float: right;
                                    text-align: right
                                }

                                .elementor-accordion .elementor-tab-title .elementor-accordion-icon.elementor-accordion-icon-left {
                                    float: left;
                                    text-align: left
                                }

                                .elementor-accordion .elementor-tab-title .elementor-accordion-icon .elementor-accordion-icon-closed {
                                    display: block
                                }

                                .elementor-accordion .elementor-tab-title .elementor-accordion-icon .elementor-accordion-icon-opened,
                                .elementor-accordion .elementor-tab-title.elementor-active .elementor-accordion-icon-closed {
                                    display: none
                                }

                                .elementor-accordion .elementor-tab-title.elementor-active .elementor-accordion-icon-opened {
                                    display: block
                                }

                                .elementor-accordion .elementor-tab-content {
                                    display: none;
                                    padding: 15px 20px;
                                    border-top: 1px solid #d5d8dc
                                }

                                @media (max-width:767px) {
                                    .elementor-accordion .elementor-tab-title {
                                        padding: 12px 15px
                                    }

                                    .elementor-accordion .elementor-tab-title .elementor-accordion-icon {
                                        width: 1.2em
                                    }

                                    .elementor-accordion .elementor-tab-content {
                                        padding: 7px 15px
                                    }
                                }

                                .e-con-inner>.elementor-widget-accordion,
                                .e-con>.elementor-widget-accordion {
                                    width: var(--container-widget-width);
                                    --flex-grow: var(--container-widget-flex-grow)
                                }
                            </style>
                            <div class="elementor-accordion">
                                <div class="elementor-accordion-item">
                                    <div id="elementor-tab-title-2491" class="elementor-tab-title" data-tab="1" role="button" aria-controls="elementor-tab-content-2491" aria-expanded="false">
                                        <span class="elementor-accordion-icon elementor-accordion-icon-right" aria-hidden="true">
                                            <span class="elementor-accordion-icon-closed"><i class="fas fa-plus"></i></span>
                                            <span class="elementor-accordion-icon-opened"><i class="fas fa-minus"></i></span>
                                        </span>
                                        <a class="elementor-accordion-title" tabindex="0">1. How quickly can your Coventry locksmith arrive?</a>
                                    </div>
                                    <div id="elementor-tab-content-2491" class="elementor-tab-content elementor-clearfix" data-tab="1" role="region" aria-labelledby="elementor-tab-title-2491">
                                        <p>Our Coventry locksmith, Vlad, provides <strong data-start="166" data-end="192">fast, reliable service</strong>, with a typical response time of <strong data-start="226" data-end="243">15–30 minutes</strong> to ensure you’re not left waiting.</p>
                                    </div>
                                </div>
                                <div class="elementor-accordion-item">
                                    <div id="elementor-tab-title-2492" class="elementor-tab-title" data-tab="2" role="button" aria-controls="elementor-tab-content-2492" aria-expanded="false">
                                        <span class="elementor-accordion-icon elementor-accordion-icon-right" aria-hidden="true">
                                            <span class="elementor-accordion-icon-closed"><i class="fas fa-plus"></i></span>
                                            <span class="elementor-accordion-icon-opened"><i class="fas fa-minus"></i></span>
                                        </span>
                                        <a class="elementor-accordion-title" tabindex="0">2. What locksmith services does your Coventry locksmith offer?</a>
                                    </div>
                                    <div id="elementor-tab-content-2492" class="elementor-tab-content elementor-clearfix" data-tab="2" role="region" aria-labelledby="elementor-tab-title-2492">
                                        <p>Vlad, your Coventry locksmith, offers a range of services, including emergency lockout assistance, lock repairs and replacements, burglary repairs, uPVC door and window lock repairs, new lock installations, and safe opening services.</p>
                                    </div>
                                </div>
                                <div class="elementor-accordion-item">
                                    <div id="elementor-tab-title-2493" class="elementor-tab-title" data-tab="3" role="button" aria-controls="elementor-tab-content-2493" aria-expanded="false">
                                        <span class="elementor-accordion-icon elementor-accordion-icon-right" aria-hidden="true">
                                            <span class="elementor-accordion-icon-closed"><i class="fas fa-plus"></i></span>
                                            <span class="elementor-accordion-icon-opened"><i class="fas fa-minus"></i></span>
                                        </span>
                                        <a class="elementor-accordion-title" tabindex="0">3. Do you charge a call-out fee for your Coventry locksmith services?</a>
                                    </div>
                                    <div id="elementor-tab-content-2493" class="elementor-tab-content elementor-clearfix" data-tab="3" role="region" aria-labelledby="elementor-tab-title-2493">
                                        <p>No, there are <strong data-start="716" data-end="736">no call-out fees</strong> for our Coventry locksmith services.</p>
                                    </div>
                                </div>
                                <div class="elementor-accordion-item">
                                    <div id="elementor-tab-title-2494" class="elementor-tab-title" data-tab="4" role="button" aria-controls="elementor-tab-content-2494" aria-expanded="false">
                                        <span class="elementor-accordion-icon elementor-accordion-icon-right" aria-hidden="true">
                                            <span class="elementor-accordion-icon-closed"><i class="fas fa-plus"></i></span>
                                            <span class="elementor-accordion-icon-opened"><i class="fas fa-minus"></i></span>
                                        </span>
                                        <a class="elementor-accordion-title" tabindex="0">4. Is your Coventry locksmith available 24/7?</a>
                                    </div>
                                    <div id="elementor-tab-content-2494" class="elementor-tab-content elementor-clearfix" data-tab="4" role="region" aria-labelledby="elementor-tab-title-2494">
                                        <p>Yes, our Coventry locksmith, Vlad, is available <strong data-start="971" data-end="979">24/7</strong> to assist with any lock-related issues, whether it’s an emergency lockout or scheduled repairs.</p>
                                    </div>
                                </div>
                                <div class="elementor-accordion-item">
                                    <div id="elementor-tab-title-2495" class="elementor-tab-title" data-tab="5" role="button" aria-controls="elementor-tab-content-2495" aria-expanded="false">
                                        <span class="elementor-accordion-icon elementor-accordion-icon-right" aria-hidden="true">
                                            <span class="elementor-accordion-icon-closed"><i class="fas fa-plus"></i></span>
                                            <span class="elementor-accordion-icon-opened"><i class="fas fa-minus"></i></span>
                                        </span>
                                        <a class="elementor-accordion-title" tabindex="0">5. Can your Coventry locksmith handle uPVC door and window lock issues?</a>
                                    </div>
                                    <div id="elementor-tab-content-2495" class="elementor-tab-content elementor-clearfix" data-tab="5" role="region" aria-labelledby="elementor-tab-title-2495">
                                        <p>Absolutely! Vlad, your Coventry locksmith, specializes in <strong data-start="1224" data-end="1278">repairing and replacing uPVC door and window locks</strong>, providing a professional solution for any faulty or damaged locks.</p>
                                    </div>
                                </div>
                                <script type="application/ld+json">
                                    {
                                        "@context": "https:\/\/schema.org",
                                        "@type": "FAQPage",
                                        "mainEntity": [{
                                            "@type": "Question",
                                            "name": "1. How quickly can your Coventry locksmith arrive?",
                                            "acceptedAnswer": {
                                                "@type": "Answer",
                                                "text": "<p>Our Coventry locksmith, Vlad, provides <strong data-start=\"166\" data-end=\"192\">fast, reliable service<\/strong>, with a typical response time of <strong data-start=\"226\" data-end=\"243\">15\u201330 minutes<\/strong> to ensure you\u2019re not left waiting.<\/p>"
                                            }
                                        }, {
                                            "@type": "Question",
                                            "name": "2. What locksmith services does your Coventry locksmith offer?",
                                            "acceptedAnswer": {
                                                "@type": "Answer",
                                                "text": "<p>Vlad, your Coventry locksmith, offers a range of services, including emergency lockout assistance, lock repairs and replacements, burglary repairs, uPVC door and window lock repairs, new lock installations, and safe opening services.<\/p>"
                                            }
                                        }, {
                                            "@type": "Question",
                                            "name": "3. Do you charge a call-out fee for your Coventry locksmith services?",
                                            "acceptedAnswer": {
                                                "@type": "Answer",
                                                "text": "<p>No, there are <strong data-start=\"716\" data-end=\"736\">no call-out fees<\/strong> for our Coventry locksmith services.<\/p>"
                                            }
                                        }, {
                                            "@type": "Question",
                                            "name": "4. Is your Coventry locksmith available 24\/7?",
                                            "acceptedAnswer": {
                                                "@type": "Answer",
                                                "text": "<p>Yes, our Coventry locksmith, Vlad, is available <strong data-start=\"971\" data-end=\"979\">24\/7<\/strong> to assist with any lock-related issues, whether it\u2019s an emergency lockout or scheduled repairs.<\/p>"
                                            }
                                        }, {
                                            "@type": "Question",
                                            "name": "5. Can your Coventry locksmith handle uPVC door and window lock issues?",
                                            "acceptedAnswer": {
                                                "@type": "Answer",
                                                "text": "<p>Absolutely! Vlad, your Coventry locksmith, specializes in <strong data-start=\"1224\" data-end=\"1278\">repairing and replacing uPVC door and window locks<\/strong>, providing a professional solution for any faulty or damaged locks.<\/p>"
                                            }
                                        }]
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="elementor-section elementor-top-section elementor-element elementor-element-a606283 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="a606283" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-b17c7c2" data-id="b17c7c2" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-da36 elementor-widget elementor-widget-theme-site-logo elementor-widget-image" data-id="da36" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="theme-site-logo.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-image">
                                    <a href="https://vblocksmithservices.co.uk">
                                        <img loading="lazy" decoding="async" width="367" height="381" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20367%20381'%3E%3C/svg%3E" class="attachment-full size-full wp-image-873" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo.png 367w, https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-289x300.png 289w, https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-115x119.png 115w" data-lazy-sizes="(max-width: 367px) 100vw, 367px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-115x119.png" /><noscript><img loading="lazy" decoding="async" width="367" height="381" src="https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-115x119.png" class="attachment-full size-full wp-image-873" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo.png 367w, https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-289x300.png 289w, https://vblocksmithservices.co.uk/wp-content/uploads/2023/07/VB-Locksmith-Services-Ltd-Logo-115x119.png 115w" sizes="(max-width: 367px) 100vw, 367px" /></noscript> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-567c67d6" data-id="567c67d6" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-451c6838 elementor-widget elementor-widget-text-editor" data-id="451c6838" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="text-editor.default">
                            <div class="elementor-widget-container">
                                <p>VB Locksmith Services Ltd is a reliable emergency locksmith, providing expert assistance around the clock. We cater to both residential and commercial clients, offering a wide range of professional locksmith services to ensure security and peace of mind. Our comprehensive range of services includes lock replacements, lock repairs, new lock installations, safe opening solutions, burglary repair services, and more. For a free, no-obligation quote, call us today on <strong>07883444240</strong> or email <strong>fitlock.uk@gmail.com</strong>.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-27a85aa8" data-id="27a85aa8" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-6f6adf5d elementor-widget elementor-widget-uael-infobox" data-id="6f6adf5d" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="uael-infobox.default">
                            <div class="elementor-widget-container">

                                <div class="uael-module-content uael-infobox uael-imgicon-style-normal  uael-infobox-center  infobox-has-icon uael-infobox-icon-above-title  uael-infobox-link-type-none">
                                    <div class="uael-infobox-left-right-wrap">
                                        <div class="uael-infobox-content">
                                            <div class="uael-module-content uael-imgicon-wrap ">
                                                <div class="uael-icon-wrap elementor-animation-">
                                                    <span class="uael-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" x="0px" y="0px" width="612px" height="612px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
                                                            <g>
                                                                <path d="M339.296,19.573c-92.187,0-173.778,46.021-223.165,116.25c10.221,6.668,20.184,15.812,29.82,27.567  c36.844-55.091,96.417-93.743,165.344-102.052l27.997,47.377l27.997-47.378c105.952,12.769,190.17,96.983,202.945,202.934  l-47.385,28.001l47.386,28.002c-8.54,70.836-49.108,131.821-106.661,168.372c3.666,8.357,5.731,16.902,6.096,25.712  c0.269,6.438-0.395,12.521-1.73,18.296C553.609,486.622,612,396.153,612,292.277C612,141.904,489.669,19.573,339.296,19.573z   M408.887,468.69c-62.865-50.523-80.444-25.688-108.968,2.832c-19.914,19.921-70.308-21.678-113.821-65.193  c-43.516-43.521-85.107-93.907-65.195-113.824c28.526-28.521,53.354-46.11,2.817-108.958  c-50.52-62.871-84.198-14.603-111.829,13.03c-31.9,31.889-1.681,150.726,115.769,268.194  c117.466,117.452,236.302,147.651,268.183,115.774C423.471,552.911,471.753,519.237,408.887,468.69z M217.426,334.954  c0-55.88,65.42-65.835,65.42-87.641c0-10.581-8.47-15.023-16.304-15.023c-14.397,0-22.434,16.083-22.434,16.083l-27.521-18.415  c0,0,13.973-32.6,53.346-32.6c24.766,0,51.65,14.185,51.65,46.36c0,47.208-60.968,56.314-62.028,75.789h64.36v31.751H219.123  C218.062,345.332,217.426,340.043,217.426,334.954z M339.391,292.199l58.434-92.305h45.301v84.047h17.991v30.691h-17.991v36.628  h-36.628v-36.628h-67.106V292.199L339.391,292.199z M406.497,283.94v-33.871c0-8.258,1.272-16.94,1.272-16.94h-0.424  c0,0-3.171,9.318-7.41,15.669l-22.857,34.72v0.424L406.497,283.94L406.497,283.94z"></path>
                                                            </g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                            <g></g>
                                                        </svg> </span>
                                                </div>

                                            </div>
                                            <div class='uael-infobox-title-wrap'>
                                                <p class="uael-infobox-title elementor-inline-editing" data-elementor-setting-key="infobox_title" data-elementor-inline-editing-toolbar="basic">24/7 Lock Replacement</p>
                                            </div>
                                            <div class="uael-infobox-text-wrap">
                                                <div class="uael-infobox-text elementor-inline-editing" data-elementor-setting-key="infobox_description" data-elementor-inline-editing-toolbar="advanced">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="elementor-section elementor-top-section elementor-element elementor-element-d69c03f elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="d69c03f" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-b8b5825" data-id="b8b5825" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-505de0ad elementor-widget elementor-widget-heading" data-id="505de0ad" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <p class="elementor-heading-title elementor-size-default">Contact</p>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-4ab5ec2b elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="4ab5ec2b" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-list.default">
                            <div class="elementor-widget-container">
                                <ul class="elementor-icon-list-items">
                                    <li class="elementor-icon-list-item">
                                        <a href="tel:07883444240">

                                            <span class="elementor-icon-list-icon">
                                                <i aria-hidden="true" class="fas fa-phone"></i> </span>
                                            <span class="elementor-icon-list-text">07883444240 </span>
                                        </a>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <a href="mailto:fitlock.uk@gmail.com">

                                            <span class="elementor-icon-list-icon">
                                                <i aria-hidden="true" class="fas fa-envelope"></i> </span>
                                            <span class="elementor-icon-list-text">fitlock.uk@gmail.com</span>
                                        </a>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <a href="https://vblocksmithservices.co.uk/">

                                            <span class="elementor-icon-list-icon">
                                                <i aria-hidden="true" class="fab fa-facebook"></i> </span>
                                            <span class="elementor-icon-list-text">vblocksmithservices.co.uk</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-374d2f5a" data-id="374d2f5a" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-58e94d34 elementor-widget elementor-widget-heading" data-id="58e94d34" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <p class="elementor-heading-title elementor-size-default">Services</p>
                            </div>
                        </div>
                        <section class="elementor-section elementor-inner-section elementor-element elementor-element-70cc719 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="70cc719" data-element_type="section" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-5700c1c8" data-id="5700c1c8" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-77fbd2bc elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="77fbd2bc" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-list.default">
                                            <div class="elementor-widget-container">
                                                <ul class="elementor-icon-list-items">
                                                    <li class="elementor-icon-list-item">
                                                        <a href="https://vblocksmithservices.co.uk/lock-replacement/">

                                                            <span class="elementor-icon-list-text">Lock Replacement</span>
                                                        </a>
                                                    </li>
                                                    <li class="elementor-icon-list-item">
                                                        <a href="https://vblocksmithservices.co.uk/lock-repairs/">

                                                            <span class="elementor-icon-list-text">Lock Repairs</span>
                                                        </a>
                                                    </li>
                                                    <li class="elementor-icon-list-item">
                                                        <a href="https://vblocksmithservices.co.uk/new-locks/">

                                                            <span class="elementor-icon-list-text">New Locks</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-5d88fdad" data-id="5d88fdad" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-6d2c566d elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="6d2c566d" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-list.default">
                                            <div class="elementor-widget-container">
                                                <ul class="elementor-icon-list-items">
                                                    <li class="elementor-icon-list-item">
                                                        <a href="https://vblocksmithservices.co.uk/safe-opening/">

                                                            <span class="elementor-icon-list-text">Safe Opening</span>
                                                        </a>
                                                    </li>
                                                    <li class="elementor-icon-list-item">
                                                        <a href="https://vblocksmithservices.co.uk/burglary-repair-service/">

                                                            <span class="elementor-icon-list-text">Burglary Repair Service</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-68af879" data-id="68af879" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-1c1a5ac7 elementor-widget elementor-widget-heading" data-id="1c1a5ac7" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <p class="elementor-heading-title elementor-size-default">Quicklinks</p>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-74c09cc3 elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="74c09cc3" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="icon-list.default">
                            <div class="elementor-widget-container">
                                <ul class="elementor-icon-list-items">
                                    <li class="elementor-icon-list-item">
                                        <a href="https://vblocksmithservices.co.uk/">

                                            <span class="elementor-icon-list-text">Home</span>
                                        </a>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <a href="https://vblocksmithservices.co.uk/areas-we-cover/">

                                            <span class="elementor-icon-list-text">Areas We Cover</span>
                                        </a>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <a href="https://vblocksmithservices.co.uk/gallery/">

                                            <span class="elementor-icon-list-text">Gallery</span>
                                        </a>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <a href="https://vblocksmithservices.co.uk/about-us/">

                                            <span class="elementor-icon-list-text">About Us</span>
                                        </a>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <a href="https://vblocksmithservices.co.uk/contact-us/">

                                            <span class="elementor-icon-list-text">Contact Us</span>
                                        </a>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <a href="https://vblocksmithservices.co.uk/privacy-policy/">

                                            <span class="elementor-icon-list-text">Privacy Policy</span>
                                        </a>
                                    </li>
                                    <li class="elementor-icon-list-item">
                                        <a href="https://vblocksmithservices.co.uk/terms-and-conditions/">

                                            <span class="elementor-icon-list-text">Terms and Conditions</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-4651c7e" data-id="4651c7e" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <section class="elementor-section elementor-inner-section elementor-element elementor-element-3178fcb9 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="3178fcb9" data-element_type="section" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-554fb52b" data-id="554fb52b" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-2a8fec4e elementor-widget elementor-widget-image" data-id="2a8fec4e" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image.default">
                                            <div class="elementor-widget-container">
                                                <img loading="lazy" decoding="async" width="872" height="1024" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20872%201024'%3E%3C/svg%3E" class="attachment-large size-large wp-image-2354" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association-872x1024.jpg 872w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association-255x300.jpg 255w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association-768x902.jpg 768w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association-1308x1536.jpg 1308w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association.jpg 1744w" data-lazy-sizes="(max-width: 872px) 100vw, 872px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association-872x1024.jpg" /><noscript><img loading="lazy" decoding="async" width="872" height="1024" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association-872x1024.jpg" class="attachment-large size-large wp-image-2354" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association-872x1024.jpg 872w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association-255x300.jpg 255w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association-768x902.jpg 768w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association-1308x1536.jpg 1308w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/UK-Locksmith-Association.jpg 1744w" sizes="(max-width: 872px) 100vw, 872px" /></noscript>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-2b115da0" data-id="2b115da0" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-7df1c685 elementor-widget elementor-widget-image" data-id="7df1c685" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image.default">
                                            <div class="elementor-widget-container">
                                                <a href="https://www.google.com/search?rlz=1C1CHBD_en-GBGB1000GB1000&#038;tbs=lf:1,lf_ui:2&#038;tbm=lcl&#038;sxsrf=APwXEdcDgzVvFM9n5V3maI1jlVuctKPqaQ:1684857568120&#038;q=fitlock&#038;rflfq=1&#038;num=10&#038;sa=X&#038;ved=2ahUKEwiz3uDG54v_AhURQkEAHabPCxkQjGp6BAgzEAE&#038;biw=1536&#038;bih=722&#038;dpr=1.25#rlfi=hd:;si:706220106601193351,l,CgdmaXRsb2NrWgkiB2ZpdGxvY2uSARtlbWVyZ2VuY3lfbG9ja3NtaXRoX3NlcnZpY2WqATwQASoLIgdmaXRsb2NrKAAyHhABIhocimeLrZthHUv4pfHQvxtIa3dEcPcIik9fdjILEAIiB2ZpdGxvY2s;mv:%5B%5B53.7921118,-1.2888437%5D,%5B52.524792,-2.3397348%5D%5D">
                                                    <img loading="lazy" decoding="async" width="300" height="125" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20300%20125'%3E%3C/svg%3E" class="attachment-medium_large size-medium_large wp-image-2355" alt="" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/review-us-on-google-white-1.png" /><noscript><img loading="lazy" decoding="async" width="300" height="125" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/review-us-on-google-white-1.png" class="attachment-medium_large size-medium_large wp-image-2355" alt="" /></noscript> </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="elementor-section elementor-inner-section elementor-element elementor-element-6b282360 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="6b282360" data-element_type="section" data-settings="{&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
                            <div class="elementor-container elementor-column-gap-default">
                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-66a877dd" data-id="66a877dd" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-7a5a7d25 elementor-widget elementor-widget-image" data-id="7a5a7d25" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image.default">
                                            <div class="elementor-widget-container">
                                                <img loading="lazy" decoding="async" width="768" height="248" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20768%20248'%3E%3C/svg%3E" class="attachment-medium_large size-medium_large wp-image-2356" alt="" data-lazy-srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/Visa-White-3-1-768x248.png 768w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/Visa-White-3-1-300x97.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/Visa-White-3-1.png 900w" data-lazy-sizes="(max-width: 768px) 100vw, 768px" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/Visa-White-3-1-768x248.png" /><noscript><img loading="lazy" decoding="async" width="768" height="248" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/Visa-White-3-1-768x248.png" class="attachment-medium_large size-medium_large wp-image-2356" alt="" srcset="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/Visa-White-3-1-768x248.png 768w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/Visa-White-3-1-300x97.png 300w, https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/Visa-White-3-1.png 900w" sizes="(max-width: 768px) 100vw, 768px" /></noscript>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-75a95ea3" data-id="75a95ea3" data-element_type="column">
                                    <div class="elementor-widget-wrap elementor-element-populated">
                                        <div class="elementor-element elementor-element-5c15aef3 elementor-widget elementor-widget-image" data-id="5c15aef3" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="image.default">
                                            <div class="elementor-widget-container">
                                                <img loading="lazy" decoding="async" width="228" height="52" src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20228%2052'%3E%3C/svg%3E" class="attachment-medium_large size-medium_large wp-image-2357" alt="" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/Mastercard-horizontal-dark-background-76px-3.png" /><noscript><img loading="lazy" decoding="async" width="228" height="52" src="https://vblocksmithservices.co.uk/wp-content/uploads/2025/03/Mastercard-horizontal-dark-background-76px-3.png" class="attachment-medium_large size-medium_large wp-image-2357" alt="" /></noscript>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>
        <section class="elementor-section elementor-top-section elementor-element elementor-element-985bae8 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="985bae8" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;gradient&quot;,&quot;ekit_has_onepagescroll_dot&quot;:&quot;yes&quot;}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-03e0d41" data-id="03e0d41" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-70456bb elementor-widget elementor-widget-text-editor" data-id="70456bb" data-element_type="widget" data-settings="{&quot;ekit_we_effect_on&quot;:&quot;none&quot;}" data-widget_type="text-editor.default">
                            <div class="elementor-widget-container">
                                Copyright © VB Locksmith Services Ltd &#8211; 2025. All rights reserved. </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script type="speculationrules">
        {"prefetch":[{"source":"document","where":{"and":[{"href_matches":"\/*"},{"not":{"href_matches":["\/wp-*.php","\/wp-admin\/*","\/wp-content\/uploads\/*","\/wp-content\/*","\/wp-content\/plugins\/*","\/wp-content\/themes\/astra\/*","\/*\\?(.+)"]}},{"not":{"selector_matches":"a[rel~=\"nofollow\"]"}},{"not":{"selector_matches":".no-prefetch, .no-prefetch a"}}]},"eagerness":"conservative"}]}
</script>



    <script id="astra-theme-js-js-extra">
        var astra = {
            "break_point": "921",
            "isRtl": "",
            "is_scroll_to_id": "",
            "is_scroll_to_top": "",
            "is_header_footer_builder_active": "1",
            "responsive_cart_click": "flyout"
        };
    </script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/themes/astra/assets/js/minified/frontend.min.js?ver=4.7.2" id="astra-theme-js-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-includes/js/dist/dom-ready.min.js?ver=f77871ff7694fffea381" id="wp-dom-ready-js" defer></script>
    <script id="starter-templates-zip-preview-js-extra">
        var starter_templates_zip_preview = {
            "AstColorPaletteVarPrefix": "--ast-global-color-",
            "AstEleColorPaletteVarPrefix": ["ast-global-color-0", "ast-global-color-1", "ast-global-color-2", "ast-global-color-3", "ast-global-color-4", "ast-global-color-5", "ast-global-color-6", "ast-global-color-7", "ast-global-color-8"]
        };
    </script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/astra-sites/inc/lib/onboarding/assets/dist/template-preview/main.js?ver=1760354130" id="starter-templates-zip-preview-js" defer></script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/metform/public/assets/lib/cute-alert/cute-alert.js?ver=1760354130" id="cute-alert-js" defer></script>
    <script id="astra-addon-js-js-extra">
        var astraAddon = {
            "sticky_active": "",
            "svgIconClose": "<span class=\"ast-icon icon-close\"><svg viewBox=\"0 0 512 512\" aria-hidden=\"true\" role=\"img\" version=\"1.1\" xmlns=\"http:\/\/www.w3.org\/2000\/svg\" xmlns:xlink=\"http:\/\/www.w3.org\/1999\/xlink\" width=\"18px\" height=\"18px\">\n                                <path d=\"M71.029 71.029c9.373-9.372 24.569-9.372 33.942 0L256 222.059l151.029-151.03c9.373-9.372 24.569-9.372 33.942 0 9.372 9.373 9.372 24.569 0 33.942L289.941 256l151.03 151.029c9.372 9.373 9.372 24.569 0 33.942-9.373 9.372-24.569 9.372-33.942 0L256 289.941l-151.029 151.03c-9.373 9.372-24.569 9.372-33.942 0-9.372-9.373-9.372-24.569 0-33.942L222.059 256 71.029 104.971c-9.372-9.373-9.372-24.569 0-33.942z\" \/>\n                            <\/svg><\/span>",
            "hf_account_show_menu_on": "hover",
            "hf_account_action_type": "link",
            "is_header_builder_active": "1"
        };
    </script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/uploads/astra-addon/astra-addon-68ba9c2f6d35e9-46634337.js?ver=1760354130" id="astra-addon-js-js" defer></script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/elementskit-lite/libs/framework/assets/js/frontend-script.js?ver=1760354130" id="elementskit-framework-js-frontend-js" defer></script>
    <script type="rocketlazyloadscript" id="elementskit-framework-js-frontend-js-after">
        var elementskit = {
			resturl: 'https://vblocksmithservices.co.uk/wp-json/elementskit/v1/',
		}

		
</script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/elementskit-lite/widgets/init/assets/js/widget-scripts.js?ver=1760354131" id="ekit-widget-scripts-js" defer></script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/elementskit/modules/parallax/assets/js/anime.js?ver=1760354131" id="animejs-js" defer></script>
    <script type="rocketlazyloadscript" data-minify="1" defer src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/elementskit/modules/parallax/assets/js/parallax-frontend-scripts.js?ver=1760354131" id="elementskit-parallax-frontend-defer-js"></script>
    <script id="intl-tel-input-js-js-extra">
        var mystickyelement_obj = {
            "plugin_url": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/mystickyelements-pro\/"
        };
    </script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/mystickyelements-pro/intl-tel-input-src/build/js/intlTelInput.js?ver=1760354131" id="intl-tel-input-js-js" defer data-wp-strategy="defer"></script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/mystickyelements-pro/js/jquery.cookie.js?ver=1760354131" id="mystickyelements-cookie-js-js" defer data-wp-strategy="defer"></script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/mystickyelements-pro/js/mailcheck.js?ver=1760354131" id="mailcheck-js-js" defer data-wp-strategy="defer"></script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/mystickyelements-pro/js/jquery.email-autocomplete.js?ver=1760354131" id="autocomplete-email-js-js" defer data-wp-strategy="defer"></script>
    <script id="mystickyelements-fronted-js-js-extra">
        var mystickyelements = {
            "ajaxurl": "https:\/\/vblocksmithservices.co.uk\/wp-admin\/admin-ajax.php",
            "ajax_nonce": "71b5a22a96",
            "google_analytics": ""
        };
    </script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/mystickyelements-pro/js/mystickyelements-fronted.min.js?ver=2.1.7" id="mystickyelements-fronted-js-js" defer data-wp-strategy="defer"></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/elementor-pro/assets/js/webpack-pro.runtime.min.js?ver=3.16.2" id="elementor-pro-webpack-runtime-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/elementor/assets/js/webpack.runtime.min.js?ver=3.22.3" id="elementor-webpack-runtime-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/elementor/assets/js/frontend-modules.min.js?ver=3.22.3" id="elementor-frontend-modules-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-includes/js/dist/hooks.min.js?ver=4d63a3d491d11ffd8ac6" id="wp-hooks-js"></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-includes/js/dist/i18n.min.js?ver=5e580eb46a90c2b997e6" id="wp-i18n-js"></script>
    <script type="rocketlazyloadscript" id="wp-i18n-js-after">
        wp.i18n.setLocaleData( { 'text direction\u0004ltr': [ 'ltr' ] } );
</script>
    <script type="rocketlazyloadscript" id="elementor-pro-frontend-js-before">
        var ElementorProFrontendConfig = {"ajaxurl":"https:\/\/vblocksmithservices.co.uk\/wp-admin\/admin-ajax.php","nonce":"0a0ea6f0b1","urls":{"assets":"https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/elementor-pro\/assets\/","rest":"https:\/\/vblocksmithservices.co.uk\/wp-json\/"},"shareButtonsNetworks":{"facebook":{"title":"Facebook","has_counter":true},"twitter":{"title":"Twitter"},"linkedin":{"title":"LinkedIn","has_counter":true},"pinterest":{"title":"Pinterest","has_counter":true},"reddit":{"title":"Reddit","has_counter":true},"vk":{"title":"VK","has_counter":true},"odnoklassniki":{"title":"OK","has_counter":true},"tumblr":{"title":"Tumblr"},"digg":{"title":"Digg"},"skype":{"title":"Skype"},"stumbleupon":{"title":"StumbleUpon","has_counter":true},"mix":{"title":"Mix"},"telegram":{"title":"Telegram"},"pocket":{"title":"Pocket","has_counter":true},"xing":{"title":"XING","has_counter":true},"whatsapp":{"title":"WhatsApp"},"email":{"title":"Email"},"print":{"title":"Print"}},"facebook_sdk":{"lang":"en_GB","app_id":""},"lottie":{"defaultAnimationUrl":"https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/elementor-pro\/modules\/lottie\/assets\/animations\/default.json"}};
</script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/elementor-pro/assets/js/frontend.min.js?ver=3.16.2" id="elementor-pro-frontend-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/elementor/assets/lib/waypoints/waypoints.min.js?ver=4.0.2" id="elementor-waypoints-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-includes/js/jquery/ui/core.min.js?ver=1.13.3" id="jquery-ui-core-js" defer></script>
    <script id="elementor-frontend-js-extra">
        var uael_particles_script = {
            "uael_particles_url": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/ultimate-elementor\/assets\/min-js\/uael-particles.min.js",
            "particles_url": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/ultimate-elementor\/assets\/lib\/particles\/particles.min.js",
            "snowflakes_image": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/ultimate-elementor\/assets\/img\/snowflake.svg",
            "gift": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/ultimate-elementor\/assets\/img\/gift.png",
            "tree": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/ultimate-elementor\/assets\/img\/tree.png",
            "skull": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/ultimate-elementor\/assets\/img\/skull.png",
            "ghost": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/ultimate-elementor\/assets\/img\/ghost.png",
            "moon": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/ultimate-elementor\/assets\/img\/moon.png",
            "bat": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/ultimate-elementor\/assets\/img\/bat.png",
            "pumpkin": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/ultimate-elementor\/assets\/img\/pumpkin.png"
        };
    </script>
    <script type="rocketlazyloadscript" id="elementor-frontend-js-before">
        var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false,"isScriptDebug":false},"i18n":{"shareOnFacebook":"Share on Facebook","shareOnTwitter":"Share on Twitter","pinIt":"Pin it","download":"Download","downloadImage":"Download image","fullscreen":"Fullscreen","zoom":"Zoom","share":"Share","playVideo":"Play Video","previous":"Previous","next":"Next","close":"Close","a11yCarouselWrapperAriaLabel":"Carousel | Horizontal scrolling: Arrow Left & Right","a11yCarouselPrevSlideMessage":"Previous slide","a11yCarouselNextSlideMessage":"Next slide","a11yCarouselFirstSlideMessage":"This is the first slide","a11yCarouselLastSlideMessage":"This is the last slide","a11yCarouselPaginationBulletMessage":"Go to slide"},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"responsive":{"breakpoints":{"mobile":{"label":"Mobile Portrait","value":767,"default_value":767,"direction":"max","is_enabled":true},"mobile_extra":{"label":"Mobile Landscape","value":880,"default_value":880,"direction":"max","is_enabled":false},"tablet":{"label":"Tablet Portrait","value":1024,"default_value":1024,"direction":"max","is_enabled":true},"tablet_extra":{"label":"Tablet Landscape","value":1200,"default_value":1200,"direction":"max","is_enabled":false},"laptop":{"label":"Laptop","value":1366,"default_value":1366,"direction":"max","is_enabled":false},"widescreen":{"label":"Widescreen","value":2400,"default_value":2400,"direction":"min","is_enabled":false}}},"version":"3.22.3","is_static":false,"experimentalFeatures":{"e_optimized_assets_loading":true,"e_optimized_css_loading":true,"additional_custom_breakpoints":true,"container":true,"container_grid":true,"e_swiper_latest":true,"e_onboarding":true,"theme_builder_v2":true,"home_screen":true,"ai-layout":true,"landing-pages":true,"page-transitions":true,"notes":true,"form-submissions":true,"e_scroll_snap":true},"urls":{"assets":"https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/elementor\/assets\/"},"swiperClass":"swiper","settings":{"page":[],"editorPreferences":[]},"kit":{"active_breakpoints":["viewport_mobile","viewport_tablet"],"global_image_lightbox":"yes","lightbox_enable_counter":"yes","lightbox_enable_fullscreen":"yes","lightbox_enable_zoom":"yes","lightbox_enable_share":"yes","lightbox_title_src":"title","lightbox_description_src":"description"},"post":{"id":2148,"title":"Locksmith%20Coventry%20-%2024%2F7%20Service%20%7C%20VB%20Locksmith%20Services","excerpt":"","featuredImage":false}};
</script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/elementor/assets/js/frontend.min.js?ver=3.22.3" id="elementor-frontend-js" defer></script>
    <script type="rocketlazyloadscript" id="elementor-frontend-js-after">window.addEventListener('DOMContentLoaded', function() {
window.scope_array = [];
								window.backend = 0;
								jQuery.cachedScript = function( url, options ) {
									// Allow user to set any option except for dataType, cache, and url.
									options = jQuery.extend( options || {}, {
										dataType: "script",
										cache: true,
										url: url
									});
									// Return the jqXHR object so we can chain callbacks.
									return jQuery.ajax( options );
								};
							    jQuery( window ).on( "elementor/frontend/init", function() {
									elementorFrontend.hooks.addAction( "frontend/element_ready/global", function( $scope, $ ){
										if ( "undefined" == typeof $scope ) {
												return;
										}
										if ( $scope.hasClass( "uael-particle-yes" ) ) {
											window.scope_array.push( $scope );
											$scope.find(".uael-particle-wrapper").addClass("js-is-enabled");
										}else{
											return;
										}
										if(elementorFrontend.isEditMode() && $scope.find(".uael-particle-wrapper").hasClass("js-is-enabled") && window.backend == 0 ){
											var uael_url = uael_particles_script.uael_particles_url;

											jQuery.cachedScript( uael_url );
											window.backend = 1;
										}else if(elementorFrontend.isEditMode()){
											var uael_url = uael_particles_script.uael_particles_url;
											jQuery.cachedScript( uael_url ).done(function(){
												var flag = true;
											});
										}
									});
								});
								 jQuery( document ).on( "ready elementor/popup/show", () => {
									if ( jQuery.find( ".uael-particle-yes" ).length < 1 ) {
										return;
									}
									var uael_url = uael_particles_script.uael_particles_url;
									jQuery.cachedScript = function( url, options ) {
										// Allow user to set any option except for dataType, cache, and url.
										options = jQuery.extend( options || {}, {
											dataType: "script",
											cache: true,
											url: url
										});
										// Return the jqXHR object so we can chain callbacks.
										return jQuery.ajax( options );
									};
									jQuery.cachedScript( uael_url );
								});	
});</script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/elementor-pro/assets/js/elements-handlers.min.js?ver=3.16.2" id="pro-elements-handlers-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/elementskit-lite/widgets/init/assets/js/animate-circle.min.js?ver=3.2.0" id="animate-circle-js" defer></script>
    <script id="elementskit-elementor-js-extra">
        var ekit_config = {
            "ajaxurl": "https:\/\/vblocksmithservices.co.uk\/wp-admin\/admin-ajax.php",
            "nonce": "6bfbe8ca10"
        };
    </script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/elementskit-lite/widgets/init/assets/js/elementor.js?ver=1760354131" id="elementskit-elementor-js" defer></script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/elementskit/widgets/init/assets/js/elementor.js?ver=1760354131" id="elementskit-elementor-pro-js" defer></script>
    <script type="rocketlazyloadscript" data-minify="1" defer src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/elementskit/modules/sticky-content/assets/js/elementskit-sticky-content.js?ver=1760354131" id="elementskit-sticky-content-script-init-defer-js"></script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/elementskit/modules/pro-form-reset-button/assets/js/elementskit-reset-button.js?ver=1760354131" id="elementskit-reset-button-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/powerpack-elements/assets/lib/particles/particles.min.js?ver=2.0.0" id="particles-js" defer></script>
    <script type="rocketlazyloadscript" data-minify="1" src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/elementskit/modules/particles/assets/js/ekit-particles.js?ver=1760354131" id="ekit-particles-js" defer></script>
    <script type="rocketlazyloadscript" data-minify="1" defer src="https://vblocksmithservices.co.uk/wp-content/cache/min/1/wp-content/plugins/elementskit/modules/parallax/assets/js/parallax-admin-scripts.js?ver=1760354131" id="elementskit-parallax-admin-defer-js"></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/elementor-pro/assets/lib/sticky/jquery.sticky.min.js?ver=3.16.2" id="e-sticky-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-includes/js/underscore.min.js?ver=1.13.7" id="underscore-js" defer></script>
    <script id="wp-util-js-extra">
        var _wpUtilSettings = {
            "ajax": {
                "url": "\/wp-admin\/admin-ajax.php"
            }
        };
    </script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-includes/js/wp-util.min.js?ver=3e6620794598ea959ffe49e82791d486" id="wp-util-js" defer></script>
    <script id="wpforms-elementor-js-extra">
        var wpformsElementorVars = {
            "captcha_provider": "recaptcha",
            "recaptcha_type": "v2"
        };
    </script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/wpforms/assets/js/integrations/elementor/frontend.min.js?ver=1.8.9.5" id="wpforms-elementor-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/powerpack-elements/assets/lib/tooltipster/tooltipster.min.js?ver=2.10.21" id="pp-tooltipster-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/powerpack-elements/assets/js/min/frontend-tooltip.min.js?ver=2.10.21" id="pp-elements-tooltip-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/wpforms/assets/pro/lib/intl-tel-input/jquery.intl-tel-input.min.js?ver=20.1.0" id="wpforms-smart-phone-field-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/wpforms/assets/lib/jquery.validate.min.js?ver=1.20.0" id="wpforms-validation-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/wpforms/assets/lib/jquery.inputmask.min.js?ver=5.0.7-beta.29" id="wpforms-maskedinput-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/wpforms/assets/lib/mailcheck.min.js?ver=1.1.2" id="wpforms-mailcheck-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/wpforms/assets/lib/punycode.min.js?ver=1.0.0" id="wpforms-punycode-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/wpforms/assets/js/share/utils.min.js?ver=1.8.9.5" id="wpforms-generic-utils-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/wpforms/assets/js/frontend/wpforms.min.js?ver=1.8.9.5" id="wpforms-js" defer></script>
    <script type="rocketlazyloadscript" src="https://vblocksmithservices.co.uk/wp-content/plugins/wpforms/assets/js/frontend/wpforms-modern.min.js?ver=1.8.9.5" id="wpforms-modern-js" defer></script>
    <script type="rocketlazyloadscript">
        /(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
			</script>
    <script type='text/javascript'>
        /* <![CDATA[ */
        var wpforms_settings = {
            "val_required": "This field is required.",
            "val_email": "Please enter a valid email address.",
            "val_email_suggestion": "Did you mean {suggestion}?",
            "val_email_suggestion_title": "Click to accept this suggestion.",
            "val_email_restricted": "This email address is not allowed.",
            "val_number": "Please enter a valid number.",
            "val_number_positive": "Please enter a valid positive number.",
            "val_minimum_price": "Amount entered is less than the required minimum.",
            "val_confirm": "Field values do not match.",
            "val_checklimit": "You have exceeded the number of allowed selections: {#}.",
            "val_limit_characters": "{count} of {limit} max characters.",
            "val_limit_words": "{count} of {limit} max words.",
            "val_recaptcha_fail_msg": "Google reCAPTCHA verification failed, please try again later.",
            "val_turnstile_fail_msg": "Cloudflare Turnstile verification failed, please try again later.",
            "val_inputmask_incomplete": "Please fill out the field in required format.",
            "uuid_cookie": "1",
            "locale": "en",
            "wpforms_plugin_url": "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/wpforms\/",
            "gdpr": "",
            "ajaxurl": "https:\/\/vblocksmithservices.co.uk\/wp-admin\/admin-ajax.php",
            "mailcheck_enabled": "1",
            "mailcheck_domains": [],
            "mailcheck_toplevel_domains": ["dev"],
            "is_ssl": "1",
            "currency_code": "USD",
            "currency_thousands": ",",
            "currency_decimals": "2",
            "currency_decimal": ".",
            "currency_symbol": "$",
            "currency_symbol_pos": "left",
            "val_requiredpayment": "Payment is required.",
            "val_creditcard": "Please enter a valid credit card number.",
            "css_vars": ["field-border-radius", "field-border-style", "field-border-size", "field-background-color", "field-border-color", "field-text-color", "field-menu-color", "label-color", "label-sublabel-color", "label-error-color", "button-border-radius", "button-border-style", "button-border-size", "button-background-color", "button-border-color", "button-text-color", "page-break-color", "background-image", "background-position", "background-repeat", "background-size", "background-width", "background-height", "background-color", "background-url", "container-padding", "container-border-style", "container-border-width", "container-border-color", "container-border-radius", "field-size-input-height", "field-size-input-spacing", "field-size-font-size", "field-size-line-height", "field-size-padding-h", "field-size-checkbox-size", "field-size-sublabel-spacing", "field-size-icon-size", "label-size-font-size", "label-size-line-height", "label-size-sublabel-font-size", "label-size-sublabel-line-height", "button-size-font-size", "button-size-height", "button-size-padding-h", "button-size-margin-top", "container-shadow-size-box-shadow"],
            "val_post_max_size": "The total size of the selected files {totalSize} MB exceeds the allowed limit {maxSize} MB.",
            "val_time12h": "Please enter time in 12-hour AM\/PM format (eg 8:45 AM).",
            "val_time24h": "Please enter time in 24-hour format (eg 22:45).",
            "val_time_limit": "Please enter time between {minTime} and {maxTime}.",
            "val_url": "Please enter a valid URL.",
            "val_fileextension": "File type is not allowed.",
            "val_filesize": "File exceeds max size allowed. File was not uploaded.",
            "post_max_size": "1610612736",
            "isModernMarkupEnabled": "1",
            "formErrorMessagePrefix": "Form error message",
            "errorMessagePrefix": "Error message",
            "submitBtnDisabled": "Submit button is disabled during form submission.",
            "error_updating_token": "Error updating token. Please try again or contact support if the issue persists.",
            "network_error": "Network error or server is unreachable. Check your connection or try again later.",
            "token_cache_lifetime": "86400",
            "val_password_strength": "A stronger password is required. Consider using upper and lower case letters, numbers, and symbols.",
            "val_phone": "Please enter a valid phone number.",
            "indicatorStepsPattern": "Step {current} of {total}",
            "entry_preview_iframe_styles": ["https:\/\/vblocksmithservices.co.uk\/wp-includes\/js\/tinymce\/skins\/lightgray\/content.min.css?ver=3e6620794598ea959ffe49e82791d486", "https:\/\/vblocksmithservices.co.uk\/wp-includes\/css\/dashicons.min.css?ver=3e6620794598ea959ffe49e82791d486", "https:\/\/vblocksmithservices.co.uk\/wp-includes\/js\/tinymce\/skins\/wordpress\/wp-content.css?ver=3e6620794598ea959ffe49e82791d486", "https:\/\/vblocksmithservices.co.uk\/wp-content\/plugins\/wpforms\/assets\/pro\/css\/fields\/richtext\/editor-content.min.css"]
        }
        /* ]]> */
    </script>
    <input type="hidden" class="mystickyelement-country-list-hidden"
        value="" />
    <div class="mystickyelements-fixed mystickyelements-fixed-widget-0 mystickyelements-position-screen-center mystickyelements-position-mobile-bottom mystickyelements-on-hover mystickyelements-size-medium mystickyelements-mobile-size-medium mystickyelements-position-right mystickyelements-templates-default mystickyelements-entry-effect-slide-in" data-custom-position=""
        data-custom-position-mobile=""
        data-mystickyelement-widget="0"
        data-widget-time-delay=""
        id="mystickyelement-widget-0"
        data-istimedelay="0">
        <div class="mystickyelement-lists-wrap">
            <ul class="mystickyelements-lists mysticky"
                data-mystickyelement-widget="0">
                <li class="mystickyelements-minimize "
                    data-mystickyelement-widget="0">
                    <span class="mystickyelements-minimize minimize-position-right minimize-position-mobile-bottom"
                        style="background: #000000">
                        &rarr; </span>
                </li>

                <li id="mystickyelements-social-phone"
                    class="mystickyelements-social-icon-li mystickyelements-social-phone  element-desktop-on element-mobile-on" data-widget="0" data-channel="phone" data-widget-nonce="a262fe9dd5" data-widget-analytics="">
                    <style>
                    </style>
                    <span class="mystickyelements-social-icon social-phone social-custom"
                        data-tab-setting='hover'
                        data-tab-setting='hover'
                        data-mobile-behavior="disable"
                        data-click="0"
                        data-flyout="disable"
                        style="background: #cc2a03">
                        <a href="tel:07883444240" data-url="tel:07883444240"
                            data-tab-setting='hover'
                            data-mobile-behavior="disable"
                            data-flyout="disable" title="07883444240">
                            <i class="fa fa-phone"></i>
                        </a>
                    </span>

                    <span class="mystickyelements-social-text "
                        style="background: #cc2a03;"
                        data-flyout="disable">
                        <a href="tel:07883444240" data-flyout="disable" title="07883444240">
                            Call Us </a>
                    </span>

                </li>

                <li id="mystickyelements-social-email"
                    class="mystickyelements-social-icon-li mystickyelements-social-email  element-desktop-on element-mobile-on" data-widget="0" data-channel="email" data-widget-nonce="a262fe9dd5" data-widget-analytics="">
                    <style>
                    </style>
                    <span class="mystickyelements-social-icon social-email social-custom"
                        data-tab-setting='hover'
                        data-tab-setting='hover'
                        data-mobile-behavior="disable"
                        data-click="0"
                        data-flyout="disable"
                        style="background: #fc8b00">
                        <a href="mailto:fitlock.uk@gmail.com" data-url="mailto:fitlock.uk@gmail.com"
                            data-tab-setting='hover'
                            data-mobile-behavior="disable"
                            data-flyout="disable" title="fitlock.uk@gmail.com">
                            <i class="far fa-envelope"></i>
                        </a>
                    </span>

                    <span class="mystickyelements-social-text "
                        style="background: #fc8b00;"
                        data-flyout="disable">
                        <a href="mailto:fitlock.uk@gmail.com" data-flyout="disable" title="fitlock.uk@gmail.com">
                            Email </a>
                    </span>

                </li>

                <li id="mystickyelements-social-custom_channel_3"
                    class="mystickyelements-social-icon-li mystickyelements-social-custom_channel_3  element-desktop-on element-mobile-on" data-widget="0" data-channel="custom_channel_3" data-widget-nonce="a262fe9dd5" data-widget-analytics="">
                    <style>
                    </style>
                    <span class="mystickyelements-social-icon social-custom_channel_3 social-custom"
                        data-tab-setting='hover'
                        data-tab-setting='hover'
                        data-mobile-behavior="disable"
                        data-click="0"
                        data-flyout="disable"
                        style="background: #26d637">
                        <a href="https://wa.link/lnrmmz" data-url="https://wa.link/lnrmmz"
                            data-tab-setting='hover'
                            data-mobile-behavior="disable"
                            data-flyout="disable" title="https://wa.link/lnrmmz">
                            <img class=""
                                src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%200%200'%3E%3C/svg%3E" alt="Custom channel" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2023/09/124034.png" /><noscript><img class=""
                                    src="https://vblocksmithservices.co.uk/wp-content/uploads/2023/09/124034.png" alt="Custom channel" /></noscript>
                        </a>
                    </span>

                    <span class="mystickyelements-social-text "
                        style="background: #26d637;"
                        data-flyout="disable">
                        <a href="https://wa.link/lnrmmz" data-flyout="disable" title="https://wa.link/lnrmmz">
                            WhatsApp </a>
                    </span>

                </li>

                <li id="mystickyelements-social-facebook"
                    class="mystickyelements-social-icon-li mystickyelements-social-facebook  element-desktop-on element-mobile-on" data-widget="0" data-channel="facebook" data-widget-nonce="a262fe9dd5" data-widget-analytics="">
                    <style>
                    </style>
                    <span class="mystickyelements-social-icon social-facebook social-custom"
                        data-tab-setting='hover'
                        data-tab-setting='hover'
                        data-mobile-behavior="disable"
                        data-click="0"
                        data-flyout="disable"
                        style="background: #4267B2">
                        <a href="https://www.facebook.com/profile.php?id=100088387658303" data-url="https://www.facebook.com/profile.php?id=100088387658303"
                            data-tab-setting='hover'
                            data-mobile-behavior="disable"
                            data-flyout="disable" title="https://www.facebook.com/profile.php?id=100088387658303">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </span>

                    <span class="mystickyelements-social-text "
                        style="background: #4267B2;"
                        data-flyout="disable">
                        <a href="https://www.facebook.com/profile.php?id=100088387658303" data-flyout="disable" title="https://www.facebook.com/profile.php?id=100088387658303">
                            Facebook </a>
                    </span>

                </li>

                <li id="mystickyelements-social-custom_channel_2"
                    class="mystickyelements-social-icon-li mystickyelements-social-custom_channel_2  element-desktop-on element-mobile-on" data-widget="0" data-channel="custom_channel_2" data-widget-nonce="a262fe9dd5" data-widget-analytics="">
                    <style>
                    </style>
                    <span class="mystickyelements-social-icon social-custom_channel_2 social-custom"
                        data-tab-setting='hover'
                        data-tab-setting='hover'
                        data-mobile-behavior="disable"
                        data-click="0"
                        data-flyout="disable"
                        style="background: #f3f3f3">
                        <a href="https://www.google.com/search?sca_esv=2c636b0d29aca8be&#038;hl=en&#038;authuser=0&#038;tbm=lcl&#038;sxsrf=ADLYWIIw7iAg8bgXpHq292NDjj9M6SHgLw:1731499628274&#038;q=VB+Locksmith+Services+Reviews&#038;rflfq=1&#038;num=20&#038;stick=H4sIAAAAAAAAAONgkxIyNzAzMjIwNDAzMzA0tDQ2NjXcwMj4ilE2zEnBJz85uzg3syRDITi1qCwzObVYISi1LDO1vHgRK355AIpPwKVZAAAA&#038;rldimm=706220106601193351&#038;sa=X&#038;ved=2ahUKEwjxj5biotmJAxV8SPEDHUrIM9IQ9fQKegQIPhAF&#038;biw=1439&#038;bih=614&#038;dpr=2#lkt=LocalPoiReviews" data-url="https://www.google.com/search?sca_esv=2c636b0d29aca8be&#038;hl=en&#038;authuser=0&#038;tbm=lcl&#038;sxsrf=ADLYWIIw7iAg8bgXpHq292NDjj9M6SHgLw:1731499628274&#038;q=VB+Locksmith+Services+Reviews&#038;rflfq=1&#038;num=20&#038;stick=H4sIAAAAAAAAAONgkxIyNzAzMjIwNDAzMzA0tDQ2NjXcwMj4ilE2zEnBJz85uzg3syRDITi1qCwzObVYISi1LDO1vHgRK355AIpPwKVZAAAA&#038;rldimm=706220106601193351&#038;sa=X&#038;ved=2ahUKEwjxj5biotmJAxV8SPEDHUrIM9IQ9fQKegQIPhAF&#038;biw=1439&#038;bih=614&#038;dpr=2#lkt=LocalPoiReviews"
                            data-tab-setting='hover'
                            data-mobile-behavior="disable"
                            data-flyout="disable" title="https://www.google.com/search?sca_esv=2c636b0d29aca8be&amp;hl=en&amp;authuser=0&amp;tbm=lcl&amp;sxsrf=ADLYWIIw7iAg8bgXpHq292NDjj9M6SHgLw:1731499628274&amp;q=VB+Locksmith+Services+Reviews&amp;rflfq=1&amp;num=20&amp;stick=H4sIAAAAAAAAAONgkxIyNzAzMjIwNDAzMzA0tDQ2NjXcwMj4ilE2zEnBJz85uzg3syRDITi1qCwzObVYISi1LDO1vHgRK355AIpPwKVZAAAA&amp;rldimm=706220106601193351&amp;sa=X&amp;ved=2ahUKEwjxj5biotmJAxV8SPEDHUrIM9IQ9fQKegQIPhAF&amp;biw=1439&amp;bih=614&amp;dpr=2#lkt=LocalPoiReviews">
                            <img class=""
                                src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%200%200'%3E%3C/svg%3E" alt="Custom channel" data-lazy-src="https://vblocksmithservices.co.uk/wp-content/uploads/2023/06/search.png" /><noscript><img class=""
                                    src="https://vblocksmithservices.co.uk/wp-content/uploads/2023/06/search.png" alt="Custom channel" /></noscript>
                        </a>
                    </span>

                    <span class="mystickyelements-social-text "
                        style="background: #f3f3f3;color: #000000;"
                        data-flyout="disable">
                        <a href="https://www.google.com/search?sca_esv=2c636b0d29aca8be&#038;hl=en&#038;authuser=0&#038;tbm=lcl&#038;sxsrf=ADLYWIIw7iAg8bgXpHq292NDjj9M6SHgLw:1731499628274&#038;q=VB+Locksmith+Services+Reviews&#038;rflfq=1&#038;num=20&#038;stick=H4sIAAAAAAAAAONgkxIyNzAzMjIwNDAzMzA0tDQ2NjXcwMj4ilE2zEnBJz85uzg3syRDITi1qCwzObVYISi1LDO1vHgRK355AIpPwKVZAAAA&#038;rldimm=706220106601193351&#038;sa=X&#038;ved=2ahUKEwjxj5biotmJAxV8SPEDHUrIM9IQ9fQKegQIPhAF&#038;biw=1439&#038;bih=614&#038;dpr=2#lkt=LocalPoiReviews" style='color:#000000' data-flyout="disable" title="https://www.google.com/search?sca_esv=2c636b0d29aca8be&amp;hl=en&amp;authuser=0&amp;tbm=lcl&amp;sxsrf=ADLYWIIw7iAg8bgXpHq292NDjj9M6SHgLw:1731499628274&amp;q=VB+Locksmith+Services+Reviews&amp;rflfq=1&amp;num=20&amp;stick=H4sIAAAAAAAAAONgkxIyNzAzMjIwNDAzMzA0tDQ2NjXcwMj4ilE2zEnBJz85uzg3syRDITi1qCwzObVYISi1LDO1vHgRK355AIpPwKVZAAAA&amp;rldimm=706220106601193351&amp;sa=X&amp;ved=2ahUKEwjxj5biotmJAxV8SPEDHUrIM9IQ9fQKegQIPhAF&amp;biw=1439&amp;bih=614&amp;dpr=2#lkt=LocalPoiReviews">
                            Review us on Google </a>
                    </span>

                </li>

            </ul>
        </div>
    </div>
    <style>
        form#stickyelements-form input::-moz-placeholder {
            color: #4F4F4F;
        }

        form#stickyelements-form input::-ms-input-placeholder {
            color: #4F4F4F
        }

        form#stickyelements-form input::-webkit-input-placeholder {
            color: #4F4F4F
        }

        form#stickyelements-form input::placeholder {
            color: #4F4F4F
        }

        form#stickyelements-form textarea::placeholder {
            color: #4F4F4F
        }

        form#stickyelements-form textarea::-moz-placeholder {
            color: #4F4F4F
        }
    </style>
    <script>
        window.lazyLoadOptions = {
            elements_selector: "img[data-lazy-src],.rocket-lazyload,iframe[data-lazy-src]",
            data_src: "lazy-src",
            data_srcset: "lazy-srcset",
            data_sizes: "lazy-sizes",
            class_loading: "lazyloading",
            class_loaded: "lazyloaded",
            threshold: 300,
            callback_loaded: function(element) {
                if (element.tagName === "IFRAME" && element.dataset.rocketLazyload == "fitvidscompatible") {
                    if (element.classList.contains("lazyloaded")) {
                        if (typeof window.jQuery != "undefined") {
                            if (jQuery.fn.fitVids) {
                                jQuery(element).parent().fitVids()
                            }
                        }
                    }
                }
            }
        };
        window.addEventListener('LazyLoad::Initialized', function(e) {
            var lazyLoadInstance = e.detail.instance;
            if (window.MutationObserver) {
                var observer = new MutationObserver(function(mutations) {
                    var image_count = 0;
                    var iframe_count = 0;
                    var rocketlazy_count = 0;
                    mutations.forEach(function(mutation) {
                        for (var i = 0; i < mutation.addedNodes.length; i++) {
                            if (typeof mutation.addedNodes[i].getElementsByTagName !== 'function') {
                                continue
                            }
                            if (typeof mutation.addedNodes[i].getElementsByClassName !== 'function') {
                                continue
                            }
                            images = mutation.addedNodes[i].getElementsByTagName('img');
                            is_image = mutation.addedNodes[i].tagName == "IMG";
                            iframes = mutation.addedNodes[i].getElementsByTagName('iframe');
                            is_iframe = mutation.addedNodes[i].tagName == "IFRAME";
                            rocket_lazy = mutation.addedNodes[i].getElementsByClassName('rocket-lazyload');
                            image_count += images.length;
                            iframe_count += iframes.length;
                            rocketlazy_count += rocket_lazy.length;
                            if (is_image) {
                                image_count += 1
                            }
                            if (is_iframe) {
                                iframe_count += 1
                            }
                        }
                    });
                    if (image_count > 0 || iframe_count > 0 || rocketlazy_count > 0) {
                        lazyLoadInstance.update()
                    }
                });
                var b = document.getElementsByTagName("body")[0];
                var config = {
                    childList: !0,
                    subtree: !0
                };
                observer.observe(b, config)
            }
        }, !1)
    </script>
    <script data-no-minify="1" async src="https://vblocksmithservices.co.uk/wp-content/plugins/wp-rocket/assets/js/lazyload/17.5/lazyload.min.js"></script>
    <script>
        function lazyLoadThumb(e) {
            var t = '<img data-lazy-src="https://i.ytimg.com/vi/ID/hqdefault.jpg" alt="" width="480" height="360"><noscript><img src="https://i.ytimg.com/vi/ID/hqdefault.jpg" alt="" width="480" height="360"></noscript>',
                a = '<button class="play" aria-label="play Youtube video"></button>';
            return t.replace("ID", e) + a
        }

        function lazyLoadYoutubeIframe() {
            var e = document.createElement("iframe"),
                t = "ID?autoplay=1";
            t += 0 === this.parentNode.dataset.query.length ? '' : '&' + this.parentNode.dataset.query;
            e.setAttribute("src", t.replace("ID", this.parentNode.dataset.src)), e.setAttribute("frameborder", "0"), e.setAttribute("allowfullscreen", "1"), e.setAttribute("allow", "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"), this.parentNode.parentNode.replaceChild(e, this.parentNode)
        }
        document.addEventListener("DOMContentLoaded", function() {
            var e, t, p, a = document.getElementsByClassName("rll-youtube-player");
            for (t = 0; t < a.length; t++) e = document.createElement("div"), e.setAttribute("data-id", a[t].dataset.id), e.setAttribute("data-query", a[t].dataset.query), e.setAttribute("data-src", a[t].dataset.src), e.innerHTML = lazyLoadThumb(a[t].dataset.id), a[t].appendChild(e), p = e.querySelector('.play'), p.onclick = lazyLoadYoutubeIframe
        });
    </script>
    <script>
        class RocketElementorAnimation {
            constructor() {
                this.deviceMode = document.createElement("span"), this.deviceMode.id = "elementor-device-mode", this.deviceMode.setAttribute("class", "elementor-screen-only"), document.body.appendChild(this.deviceMode)
            }
            _detectAnimations() {
                let t = getComputedStyle(this.deviceMode, ":after").content.replace(/"/g, "");
                this.animationSettingKeys = this._listAnimationSettingsKeys(t), document.querySelectorAll(".elementor-invisible[data-settings]").forEach(t => {
                    const e = t.getBoundingClientRect();
                    if (e.bottom >= 0 && e.top <= window.innerHeight) try {
                        this._animateElement(t)
                    } catch (t) {}
                })
            }
            _animateElement(t) {
                const e = JSON.parse(t.dataset.settings),
                    i = e._animation_delay || e.animation_delay || 0,
                    n = e[this.animationSettingKeys.find(t => e[t])];
                if ("none" === n) return void t.classList.remove("elementor-invisible");
                t.classList.remove(n), this.currentAnimation && t.classList.remove(this.currentAnimation), this.currentAnimation = n;
                let s = setTimeout(() => {
                    t.classList.remove("elementor-invisible"), t.classList.add("animated", n), this._removeAnimationSettings(t, e)
                }, i);
                window.addEventListener("rocket-startLoading", function() {
                    clearTimeout(s)
                })
            }
            _listAnimationSettingsKeys(t = "mobile") {
                const e = [""];
                switch (t) {
                    case "mobile":
                        e.unshift("_mobile");
                    case "tablet":
                        e.unshift("_tablet");
                    case "desktop":
                        e.unshift("_desktop")
                }
                const i = [];
                return ["animation", "_animation"].forEach(t => {
                    e.forEach(e => {
                        i.push(t + e)
                    })
                }), i
            }
            _removeAnimationSettings(t, e) {
                this._listAnimationSettingsKeys().forEach(t => delete e[t]), t.dataset.settings = JSON.stringify(e)
            }
            static run() {
                const t = new RocketElementorAnimation;
                requestAnimationFrame(t._detectAnimations.bind(t))
            }
        }
        document.addEventListener("DOMContentLoaded", RocketElementorAnimation.run);
    </script>
</body>

</html>

<!-- This website is like a Rocket, isn't it? Performance optimized by WP Rocket. Learn more: https://wp-rocket.me - Debug: cached@1761562863 -->