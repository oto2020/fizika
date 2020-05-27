
!function($, b, c, d) {
    'use strict';
    $.fn.liteTooltip = function(c) {
        var g, e = $(this),
            f = $.extend({}, $.fn.liteTooltip.default, c);
        e.each(function() {

            var evnt = ( typeof $(this).attr('data-tooltip-mouseover') !== 'undefined' ) ? 'mouseover' : ( typeof $(this).attr('data-tooltip-focus') !== 'undefined' ) ? 'focus' : 'mouseover';
            'mouseover' === evnt ? g = 'mouseout' : 'focus' === evnt && (g = 'blur')

            $(this).on(evnt, function() {
                $.fn.liteTooltip.removeElem(f)
                function m($) {
                    'top' === $.position ? r($) : 'left' === $.position ? p($) : 'right' === $.position ? o($) : 'bottom' === $.position ? q($) : n($), t($, i)
                }

                function n(c) {
                    function t($, b) {
                        'top' === $ ? (b.position = 'top', r(b)) : 'bottom' === $ ? (b.position = 'bottom', q(b)) : 'left' === $ ? (b.position = 'left', p(b)) : 'right' === $ && (b.position = 'right', o(b))
                    }
                    var d = {
                            left: 0,
                            right: 0,
                            top: 0,
                            bottom: 0
                        },
                        e = null,
                        g = 0,
                        h = c.triggerLeft - c.tlWidth + f.space;
                    h > g && (d.left = h);
                    var i = $(b).width(),
                        j = i - (c.triggerLeft + c.triggerW);
                    j > c.tlWidth && (d.right = j);
                    var k = c.triggerTop,
                        l = k;
                    l > c.tlHeight + f.space && (d.top = l);
                    var m = $(b).height(),
                        n = m - (c.triggerTop + c.triggerH);
                    n > c.tlHeight + f.space && (d.bottom = n);
                    var s = Math.max(d.left, d.right, d.top, d.bottom);
                    $.each(d, function($, b) {
                        b === s && (e = $)
                    }), t(e, c)
                }

                function o(c) {
                    var d = $(b).width(),
                        e = d - (c.triggerLeft + c.triggerW);
                    if (e < c.tlWidth + f.space) n(c);
                    else {
                        var g = u(c, 'sideRight'),
                            h = v(c);
                        s(h, g)
                    }
                }

                function p($) {
                    if ($.triggerLeft < $.tlWidth + f.space) n($);
                    else {
                        var b = u($, 'sideLeft'),
                            c = v($);
                        s(c, b)
                    }
                }

                function q(c) {
                    var d = $(b).height(),
                        e = d - (c.triggerTop + c.triggerH);
                    if (e < c.tlHeight + f.space) n(c);
                    else {
                        var g = u(c),
                            h = c.triggerTop + c.triggerH + f.space;
                        s(h, g)
                    }
                }

                function r($) {
                    if ($.triggerTop < $.tlHeight + f.space) n($);
                    else {
                        var b = u($),
                            c = $.triggerTop - $.tlHeight - f.space;
                        s(c, b)
                    }
                }

                function s(b, c) {
                    $('#tooltip').css({
                        top: b,
                        left: c
                    })
                }

                function t(b, c) {
                    c.bool ? 'top' === b.position ? 'left' === c.side ? (x('left', b), $('#tooltip').find('.tooltip-arrow').css({
                        left: b.triggerW / 2 - 8
                    })) : 'right' === c.side && (x('right', b), $('#tooltip').find('.tooltip-arrow').css({
                        left: b.tlWidth - (b.triggerW - 8)
                    })) : 'bottom' === b.position && ('left' === c.side ? (x('left', b), $('#tooltip').find('.tooltip-arrow').css({
                        left: b.triggerW / 2 - 8
                    })) : 'right' === c.side && (x('right', b), $('#tooltip').find('.tooltip-arrow').css({
                        left: b.triggerW + 8
                    }))) : x('center', b)
                }

                function u(c, d) {
                    if (d && 'sideLeft' === d) var e = c.triggerLeft - (c.tlWidth + f.space);
                    else if (d && 'sideRight' === d) var e = c.triggerLeft + (c.triggerW + f.space);
                    else var g = .5 * c.tlWidth - .5 * c.triggerW,
                            e = c.triggerLeft - g;
                    return e < 0 ? (e = c.triggerLeft, i.bool = !0, i.side = 'left') : e > $(b).width() && (e = $(b).width() - (c.triggerLeft + c.triggerW) - c.tlWidth, i.bool = !0, i.side = 'right'), e
                }

                function v($) {
                    var b = .5 * $.tlHeight - .5 * $.triggerH,
                        c = $.triggerTop - b;
                    return c < 0 && (c = $.triggerTop), c
                }

                function w() {
                    var b = $('<div class="tooltip animation-' + f.animation + '" id="tooltip"></div>');
                    $('body').append(b)
                }

                function x(b, c) {
                    var d, e;
                    'left' === b ? 'top' === c.position ? e = 'toptobottom' : 'bottom' === c.position && (e = 'bottomtotop') : 'right' === b ? 'top' === c.position || 'bottom' === c.position : b && 'center' !== b || ('top' === c.position ? e = 'toptobottom center' : 'bottom' === c.position && (e = 'bottomtotop center')), 'left' === c.position ? e = 'lefttoright' : 'right' === c.position && (e = 'righttoleft'), d = $('<div class="tooltip-arrow ' + e + ' "></div>'), $('#tooltip').append(d)
                }
                var c = $(this),
                    e = c.data( 'tooltip' + evnt[0].toUpperCase() + evnt.substring(1) ),
                    g = c.data('tooltipPosition') === d || '' === c.data('tooltipPosition') ? f.position : c.data('tooltipPosition'),
                    h = !1,
                    i = {
                        bool: !1,
                        side: ''
                    },
                    j = c.offset();
                if (h = e !== d && '' !== e && null !== e) {
                    w();
                    var k = $('#tooltip');
                    k.html(e);
                    var l = {
                        triggerW: parseInt(c.width()) + parseInt(c.css('padding-left')) + parseInt(c.css('padding-right')),
                        triggerH: parseInt(c.height()) + parseInt(c.css('padding-top')) + parseInt(c.css('padding-bottom')),
                        triggerTop: j.top,
                        triggerLeft: j.left,
                        tlWidth: k.width() + parseInt(k.css('padding-left')) + parseInt(k.css('padding-right')),
                        tlHeight: k.height() + parseInt(k.css('padding-top')) + parseInt(k.css('padding-bottom')),
                        position: g
                    };
                    m(l)
                }
            }).on(g, function() {
                $.fn.liteTooltip.removeElem(f)
            })
        })
    }, $.fn.liteTooltip.removeElem = function(b) {
        $('body').find('#tooltip').length > 0 && $('#tooltip').removeClass('animation-' + b.animation).fadeOut(50).remove()
    }, $.fn.liteTooltip.default = {
        space: 20,
        animation: 'slide',
        position: 'top',
    }
}(jQuery, window, document);
$('.liteTooltip').liteTooltip();
