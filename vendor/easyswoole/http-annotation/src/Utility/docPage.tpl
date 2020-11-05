<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>接口文档</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="description" content="Description" />
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docsify-themeable@0/dist/css/theme-simple.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@10.0.0/build/styles/default.min.css">
    <style>
        .markdown-section td {
            min-width: 90px;
        }
    </style>
</head>

<body>
<div id="app"></div>
<div id="toc" style="position: fixed; top: 0; right: 0;"></div>
<script type="text/plain" id="rawMd">
{$rawMd}
</script>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdn.jsdelivr.net/npm/docsify/lib/docsify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/docsify-themeable@0"></script>

<script>
    window.$docsify = {
        name: '接口文档',
        auto2top: true,
        maxLevel: 2,
        subMaxLevel: 2,
        search: {
            paths: ["README"],
            depth: 6,
            noData: "没有搜索到结果！",
            placeholder: "搜索...",
        },
    };
    localStorage.removeItem('docsify.search.index');
    localStorage.removeItem('docsify.search.expires');
</script>
<script>
    window.onload = function () {
        window.countClick = 0
        setTimeout(function () {
            var html = document.getElementById('rawMd').innerHTML
            document.getElementById('main').innerHTML = marked(html)
        }, 500)
        setTimeout(function () {
            new Toc('main', {
                'level': 2,
                'top': 200,
                'class': 'toc',
                'targetId': 'toc'
            });
            $(function () {
                var lenToc = document.getElementById('tocnav').className
                window.lenTop = []
                for (i = 0; i < lenToc; i++) {
                    window.lenTop[i] = Math.floor($("#tip" + i).offset().top)
                }
                $(window).scroll(function () {
                    var wst = $(window).scrollTop()
                    for (i = 0; i < lenToc; i++) {
                        if (lenTop[i] <= wst) {
                            $('#tocnav li').removeClass("active");
                            $("#tipp" + i + i).addClass("active");
                        }
                    }
                })
            });
        }, 1000)
    }
</script>
<script>
    (function () {

        function getAndRemoveConfig(str) {
            if (str === void 0) str = '';

            var config = {};

            if (str) {
                str = str
                    .replace(/^'/, '')
                    .replace(/'$/, '')
                    .replace(/(?:^|\s):([\w-]+:?)=?([\w-%]+)?/g, function (m, key, value) {
                        if (key.indexOf(':') === -1) {
                            config[key] = (value && value.replace(/&quot;/g, '')) || true;
                            return '';
                        }

                        return m;
                    })
                    .trim();
            }

            return { str: str, config: config };
        }

        /* eslint-disable no-unused-vars */

        var INDEXS = {};

        var LOCAL_STORAGE = {
            EXPIRE_KEY: 'docsify.search.expires',
            INDEX_KEY: 'docsify.search.index',
        };

        function resolveExpireKey(namespace) {
            return namespace
                ? ((LOCAL_STORAGE.EXPIRE_KEY) + "/" + namespace)
                : LOCAL_STORAGE.EXPIRE_KEY;
        }

        function resolveIndexKey(namespace) {
            return namespace
                ? ((LOCAL_STORAGE.INDEX_KEY) + "/" + namespace)
                : LOCAL_STORAGE.INDEX_KEY;
        }

        function escapeHtml(string) {
            var entityMap = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
            };

            return String(string).replace(/[&<>"']/g, function (s) { return entityMap[s]; });
        }

        function getAllPaths(router) {
            var paths = [];

            Docsify.dom
                .findAll('.sidebar-nav a:not(.section-link):not([data-nosearch])')
                .forEach(function (node) {
                    var href = node.href;
                    var originHref = node.getAttribute('href');
                    var path = router.parse(href).path;

                    if (
                        path &&
                        paths.indexOf(path) === -1 &&
                        !Docsify.util.isAbsolutePath(originHref)
                    ) {
                        paths.push(path);
                    }
                });

            return paths;
        }

        function getTableData(token) {
            if (!token.text && token.type === 'table') {
                token.text = token.cells
                    .map(function (rows) {
                        return rows.join(' | ');
                    })
                    .join(' |\n ');
            }
            return token.text;
        }

        function saveData(maxAge, expireKey, indexKey) {
            localStorage.setItem(expireKey, Date.now() + maxAge);
            localStorage.setItem(indexKey, JSON.stringify(INDEXS));
        }

        function genIndex(path, content, router, depth) {
            if (content === void 0) content = '';

            var tokens = window.marked.lexer(content);
            var slugify = window.Docsify.slugify;
            var index = {};
            var slug;
            var htmlIndex = 0
            tokens.forEach(function (token) {
                if (token.type === 'html' && token.text.slice(2, 3) < 3) {
                    var re = />.*?</;
                    var found = token.text.match(re);

                    var idid = found[0].slice(1, -1)
                    slug = router.toURL('', { id: 'tip' + htmlIndex });
                    htmlIndex++;
                    index[slug] = { slug: slug, title: idid, body: '' };

                } else {
                    if (!slug) {
                        return;
                    }

                    if (!index[slug]) {
                        index[slug] = { slug: slug, title: '', body: '' };
                    } else if (index[slug].body) {
                        token.text = getTableData(token);

                        index[slug].body += '\n' + (token.text || '');
                    } else {
                        token.text = getTableData(token);

                        index[slug].body = index[slug].body
                            ? index[slug].body + token.text
                            : token.text;
                    }
                }
            });
            slugify.clear();
            return index;
        }

        /**
         * @param {String} query Search query
         * @returns {Array} Array of results
         */
        function search(query) {
            var matchingResults = [];
            var data = [];
            Object.keys(INDEXS).forEach(function (key) {
                data = data.concat(Object.keys(INDEXS[key]).map(function (page) { return INDEXS[key][page]; }));
            });

            query = query.trim();
            var keywords = query.split(/[\s\-，\\/]+/);
            if (keywords.length !== 1) {
                keywords = [].concat(query, keywords);
            }


            var loop = function (i) {
                var post = data[i];
                var matchesScore = 0;
                var resultStr = '';
                var postTitle = post.title && post.title.trim();
                var postContent = post.body && post.body.trim();
                var postUrl = post.slug || '';

                if (postTitle) {
                    keywords.forEach(function (keyword) {
                        // From https://github.com/sindresorhus/escape-string-regexp
                        var regEx = new RegExp(
                            keyword.replace(/[|\\{}()[\]^$+*?.]/g, '\\$&'),
                            'gi'
                        );
                        var indexTitle = -1;
                        var indexContent = -1;

                        indexTitle = postTitle ? postTitle.search(regEx) : -1;
                        indexContent = postContent ? postContent.search(regEx) : -1;

                        if (indexTitle >= 0 || indexContent >= 0) {
                            matchesScore += indexTitle >= 0 ? 3 : indexContent >= 0 ? 2 : 0;
                            if (indexContent < 0) {
                                indexContent = 0;
                            }

                            var start = 0;
                            var end = 0;

                            start = indexContent < 11 ? 0 : indexContent - 10;
                            end = start === 0 ? 70 : indexContent + keyword.length + 60;

                            if (postContent && end > postContent.length) {
                                end = postContent.length;
                            }

                            var matchContent =
                                '...' +
                                escapeHtml(postContent)
                                    .substring(start, end)
                                    .replace(regEx, ("<em class=\"search-keyword\">" + keyword + "</em>")) +
                                '...';

                            resultStr += matchContent;
                        }
                    });

                    if (matchesScore > 0) {
                        var matchingPost = {
                            title: escapeHtml(postTitle),
                            content: postContent ? resultStr : '',
                            url: postUrl,
                            score: matchesScore,
                        };

                        matchingResults.push(matchingPost);
                    }
                }
            };
            for (var i = 0; i < data.length; i++) loop(i);

            return matchingResults.sort(function (r1, r2) { return r2.score - r1.score; });
        }

        function init(config, vm) {
            var isAuto = config.paths === 'auto';

            var expireKey = resolveExpireKey(config.namespace);
            var indexKey = resolveIndexKey(config.namespace);

            var isExpired = localStorage.getItem(expireKey) < Date.now();

            INDEXS = JSON.parse(localStorage.getItem(indexKey));

            if (isExpired) {
                INDEXS = {};
            } else if (!isAuto) {
                return;
            }

            var paths = isAuto ? getAllPaths(vm.router) : config.paths;
            var len = paths.length;
            var count = 0;
            setTimeout(function () {
                paths.forEach(function (path) {
                    if (INDEXS[path]) {
                        return count++;
                    }

                    result = document.getElementById('rawMd').innerHTML

                    INDEXS[path] = genIndex(path, result, vm.router, config.depth);
                    len === ++count && saveData(config.maxAge, expireKey, indexKey);

                    // Docsify.get(vm.router.getFile(path), false, vm.config.requestHeaders).then(
                    //   function (result) {
                    //     debugger
                    //     INDEXS[path] = genIndex(path, result, vm.router, config.depth);
                    //     len === ++count && saveData(config.maxAge, expireKey, indexKey);
                    //   }
                    // );
                });
            }, 1000)
        }

        /* eslint-disable no-unused-vars */

        var NO_DATA_TEXT = '';
        var options;

        function style() {
            var code = "\n.sidebar {\n  padding-top: 0;\n}\n\n.search {\n  margin-bottom: 20px;\n  padding: 6px;\n  border-bottom: 1px solid #eee;\n}\n\n.search .input-wrap {\n  display: flex;\n  align-items: center;\n}\n\n.search .results-panel {\n  display: none;\n}\n\n.search .results-panel.show {\n  display: block;\n}\n\n.search input {\n  outline: none;\n  border: none;\n  width: 100%;\n  padding: 0 7px;\n  line-height: 36px;\n  font-size: 14px;\n  border: 1px solid transparent;\n}\n\n.search input:focus {\n  box-shadow: 0 0 5px var(--theme-color, #42b983);\n  border: 1px solid var(--theme-color, #42b983);\n}\n\n.search input::-webkit-search-decoration,\n.search input::-webkit-search-cancel-button,\n.search input {\n  -webkit-appearance: none;\n  -moz-appearance: none;\n  appearance: none;\n}\n.search .clear-button {\n  cursor: pointer;\n  width: 36px;\n  text-align: right;\n  display: none;\n}\n\n.search .clear-button.show {\n  display: block;\n}\n\n.search .clear-button svg {\n  transform: scale(.5);\n}\n\n.search h2 {\n  font-size: 17px;\n  margin: 10px 0;\n}\n\n.search a {\n  text-decoration: none;\n  color: inherit;\n}\n\n.search .matching-post {\n  border-bottom: 1px solid #eee;\n}\n\n.search .matching-post:last-child {\n  border-bottom: 0;\n}\n\n.search p {\n  font-size: 14px;\n  overflow: hidden;\n  text-overflow: ellipsis;\n  display: -webkit-box;\n  -webkit-line-clamp: 2;\n  -webkit-box-orient: vertical;\n}\n\n.search p.empty {\n  text-align: center;\n}\n\n.app-name.hide, .sidebar-nav.hide {\n  display: none;\n}";

            Docsify.dom.style(code);
        }

        function tpl(defaultValue) {
            if (defaultValue === void 0) defaultValue = '';

            var html = "<div class=\"input-wrap\">\n      <input type=\"search\" value=\"" + defaultValue + "\" aria-label=\"Search text\" />\n      <div class=\"clear-button\">\n        <svg width=\"26\" height=\"24\">\n          <circle cx=\"12\" cy=\"12\" r=\"11\" fill=\"#ccc\" />\n          <path stroke=\"white\" stroke-width=\"2\" d=\"M8.25,8.25,15.75,15.75\" />\n          <path stroke=\"white\" stroke-width=\"2\"d=\"M8.25,15.75,15.75,8.25\" />\n        </svg>\n      </div>\n    </div>\n    <div class=\"results-panel\"></div>\n    </div>";
            var el = Docsify.dom.create('div', html);
            var aside = Docsify.dom.find('aside');

            Docsify.dom.toggleClass(el, 'search');
            Docsify.dom.before(aside, el);
        }

        function doSearch(value) {
            var $search = Docsify.dom.find('div.search');
            var $panel = Docsify.dom.find($search, '.results-panel');
            var $clearBtn = Docsify.dom.find($search, '.clear-button');
            var $sidebarNav = Docsify.dom.find('.sidebar-nav');
            var $appName = Docsify.dom.find('.app-name');
            if (!value) {
                $panel.classList.remove('show');
                $clearBtn.classList.remove('show');
                $panel.innerHTML = '';

                if (options.hideOtherSidebarContent) {
                    $sidebarNav.classList.remove('hide');
                    $appName.classList.remove('hide');
                }

                return;
            }

            var matchs = search(value);

            var html = '';
            matchs.forEach(function (post) {
                html += "<div class=\"matching-post\">\n<a href=\"" + (post.url) + "\">\n<h2>" + (post.title) + "</h2>\n<p>" + (post.content) + "</p>\n</a>\n</div>";
            });

            $panel.classList.add('show');
            $clearBtn.classList.add('show');
            $panel.innerHTML = html || ("<p class=\"empty\">" + NO_DATA_TEXT + "</p>");
            if (options.hideOtherSidebarContent) {
                $sidebarNav.classList.add('hide');
                $appName.classList.add('hide');
            }
        }

        function bindEvents() {
            var $search = Docsify.dom.find('div.search');
            var $input = Docsify.dom.find($search, 'input');
            var $inputWrap = Docsify.dom.find($search, '.input-wrap');

            var timeId;

            /**
             Prevent to Fold sidebar.

             When searching on the mobile end,
             the sidebar is collapsed when you click the INPUT box,
             making it impossible to search.
             */
            Docsify.dom.on(
                $search,
                'click',
                function (e) {
                    return ['A', 'H2', 'P', 'EM'].indexOf(e.target.tagName) === -1 &&
                        e.stopPropagation();
                }
            );
            Docsify.dom.on($input, 'input', function (e) {
                clearTimeout(timeId);
                timeId = setTimeout(function (_) { return doSearch(e.target.value.trim()); }, 100);
            });
            Docsify.dom.on($inputWrap, 'click', function (e) {
                // Click input outside
                if (e.target.tagName !== 'INPUT') {
                    $input.value = '';
                    doSearch();
                }
            });
        }

        function updatePlaceholder(text, path) {
            var $input = Docsify.dom.getNode('.search input[type="search"]');

            if (!$input) {
                return;
            }

            if (typeof text === 'string') {
                $input.placeholder = text;
            } else {
                var match = Object.keys(text).filter(function (key) { return path.indexOf(key) > -1; })[0];
                $input.placeholder = text[match];
            }
        }

        function updateNoData(text, path) {
            if (typeof text === 'string') {
                NO_DATA_TEXT = text;
            } else {
                var match = Object.keys(text).filter(function (key) { return path.indexOf(key) > -1; })[0];
                NO_DATA_TEXT = text[match];
            }
        }

        function updateOptions(opts) {
            options = opts;
        }

        function init$1(opts, vm) {
            var keywords = vm.router.parse().query.s;

            updateOptions(opts);
            style();
            tpl(keywords);
            bindEvents();
            keywords && setTimeout(function (_) { return doSearch(keywords); }, 500);
        }

        function update(opts, vm) {
            updateOptions(opts);
            updatePlaceholder(opts.placeholder, vm.route.path);
            updateNoData(opts.noData, vm.route.path);
        }

        /* eslint-disable no-unused-vars */

        var CONFIG = {
            placeholder: 'Type to search',
            noData: 'No Results!',
            paths: 'auto',
            depth: 2,
            maxAge: 86400000, // 1 day
            hideOtherSidebarContent: false,
            namespace: undefined,
        };

        var install = function (hook, vm) {
            var util = Docsify.util;
            var opts = vm.config.search || CONFIG;

            if (Array.isArray(opts)) {
                CONFIG.paths = opts;
            } else if (typeof opts === 'object') {
                CONFIG.paths = Array.isArray(opts.paths) ? opts.paths : 'auto';
                CONFIG.maxAge = util.isPrimitive(opts.maxAge) ? opts.maxAge : CONFIG.maxAge;
                CONFIG.placeholder = opts.placeholder || CONFIG.placeholder;
                CONFIG.noData = opts.noData || CONFIG.noData;
                CONFIG.depth = opts.depth || CONFIG.depth;
                CONFIG.hideOtherSidebarContent =
                    opts.hideOtherSidebarContent || CONFIG.hideOtherSidebarContent;
                CONFIG.namespace = opts.namespace || CONFIG.namespace;
            }

            var isAuto = CONFIG.paths === 'auto';

            hook.mounted(function (_) {
                init$1(CONFIG, vm);
                !isAuto && init(CONFIG, vm);
            });
            hook.doneEach(function (_) {
                update(CONFIG, vm);
                isAuto && init(CONFIG, vm);
            });
        };

        $docsify.plugins = [].concat(install, $docsify.plugins);

    }());

</script>
<script>
    (function (window) {
        function Toc(id, options) {
            this.el = document.getElementById(id);
            if (!this.el) return;
            this.options = options || {};
            this.tocLevel = parseInt(options.level) || 0;
            this.tocClass = options['class'] || 'toc';
            this.tocTop = parseInt(options.top) || 0;
            this.elChilds = this.el.children;
            if (!this.elChilds.length) return;
            this._init();
        }

        Toc.prototype._init = function () {
            this._collectTitleElements();
            this._createTocContent();
            this._showToc();
        };


        Toc.prototype._collectTitleElements = function () {
            this._elTitlesNames = [],
                this.elTitleElements = [];
            for (var i = 1; i < 7; i++) {
                if (this.el.getElementsByTagName('h' + i).length) {
                    this._elTitlesNames.push('h' + i);
                }
            }

            this._elTitlesNames.length = this._elTitlesNames.length > this.tocLevel ? this.tocLevel : this._elTitlesNames.length;

            for (var j = 0; j < this.elChilds.length; j++) {
                this._elChildName = this.elChilds[j].tagName.toLowerCase();
                if (this._elTitlesNames.toString().match(this._elChildName) && this.elChilds[j].className) {
                    this.elTitleElements.push(this.elChilds[j]);
                }
            }
        };

        Toc.prototype._createTocContent = function () {
            this._elTitleElementsLen = this.elTitleElements.length;
            if (!this._elTitleElementsLen) return;
            this.tocContent = '';
            this._tempLists = [];
            this._textList = [];
            var url = location.origin + location.pathname;
            for (var i = 0; i < this._elTitleElementsLen; i++) {
                var j = i + 1;
                this._elTitleElement = this.elTitleElements[i];
                this._elTitleElementName = this._elTitleElement.tagName;
                this._elTitleElementText = this._elTitleElement.innerHTML;
                this._elTitleElement.setAttribute('id', 'tip' + i);
                this.tocContent += '<li id="' + 'tipp' + i + i + '"><a class="section-link" href="' + '#/?id=' + 'tip' + i + '">' + this._elTitleElementText + '</a>';


                if (j != this._elTitleElementsLen) {
                    this._elNextTitleElementName = this.elTitleElements[j].tagName;
                    if (this._elTitleElementName != this._elNextTitleElementName) {
                        var checkColse = false, y = 1;
                        for (var t = this._tempLists.length - 1; t >= 0; t--) {
                            if (this._tempLists[t].tagName == this._elNextTitleElementName) {
                                checkColse = true;
                                break;
                            }
                            y++;
                        }

                        if (checkColse) {
                            this.tocContent += new Array(y + 1).join('</li></ul>');
                            this._tempLists.length = this._tempLists.length - y;//更新栈的长度。
                        } else {
                            this._tempLists.push(this._elTitleElement);
                            this.tocContent += '<ul>';
                        }
                    }
                    else {
                        this.tocContent += '</li>';
                    }
                } else {
                    if (this._tempLists.length) {
                        this.tocContent += new Array(this._tempLists.length + 1).join('</li></ul>');
                    } else {
                        this.tocContent += '</li>';
                    }
                }
            }
            this.tocContent = '<ul id="tocnav" class="' + this._elTitleElementsLen + '">' + this.tocContent + '</ul>';
        };

        Toc.prototype._showToc = function () {

            document.getElementsByClassName('sidebar-nav')[0].innerHTML = this.tocContent


        };

        window.Toc = Toc;

    })(window);

</script>
</body>

</html>
