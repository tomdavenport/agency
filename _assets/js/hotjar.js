    <script>
    (function(f, b, g) {
        var xo = g.prototype.open,
            xs = g.prototype.send,
            c;
        f.hj = f.hj || function() {
            (f.hj.q = f.hj.q || []).push(arguments)
        };
        f._hjSettings = {
            hjid: 3749,
            hjsv: 2
        };

        function ls() {
            f.hj.documentHtml = b.documentElement.outerHTML;
            c = b.createElement("script");
            c.async = 1;
            c.src = "//static.hotjar.com/c/hotjar-3749.js?sv=2";
            b.getElementsByTagName("head")[0].appendChild(c);
        }
        if (b.readyState === "interactive" || b.readyState === "complete" || b.readyState === "loaded") {
            ls();
        } else {
            if (b.addEventListener) {
                b.addEventListener("DOMContentLoaded", ls, false);
            }
        }
        if (!f._hjPlayback && b.addEventListener) {
            g.prototype.open = function(l, j, m, h, k) {
                this._u = j;
                xo.call(this, l, j, m, h, k)
            };
            g.prototype.send = function(e) {
                var j = this;

                function h() {
                    if (j.readyState === 4) {
                        f.hj("_xhr", j._u, j.status, j.response)
                    }
                }
                this.addEventListener("readystatechange", h, false);
                xs.call(this, e)
            };
        }
    })(window, document, window.XMLHttpRequest);
    </script>