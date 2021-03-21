(function ($) {
  'use strict'; // Standards.

  var plugin = {
      namespace: 'rapid_cache'
    },
    $window = $(window),
    $document = $(document);

  plugin.onReady = function () {

    plugin.hideAJAXResponseTimeout = null;
    plugin.vars = $('#' + plugin.namespace + '-admin-bar-vars').data('json');

    $('#wp-admin-bar-' + plugin.namespace + '-wipe > a').on('click', plugin.wipeCache);
    $('#wp-admin-bar-' + plugin.namespace + '-clear > a').on('click', plugin.clearCache);

    $('#wp-admin-bar-' + plugin.namespace + '-clear-options-wrapper .-home-url-only > a').on('click', plugin.clearCacheHomeUrlOnly);
    $('#wp-admin-bar-' + plugin.namespace + '-clear-options-wrapper .-current-url-only > a').on('click', plugin.clearCacheCurrentUrlOnly);
    $('#wp-admin-bar-' + plugin.namespace + '-clear-options-wrapper .-specific-url-only > a').on('click', plugin.clearCacheSpecificUrlOnly);
    $('#wp-admin-bar-' + plugin.namespace + '-clear-options-wrapper .-opcache-only > a').on('click', plugin.clearCacheOpCacheOnly);
    $('#wp-admin-bar-' + plugin.namespace + '-clear-options-wrapper .-transients-only > a').on('click', plugin.clearExpiredTransientsOnly);

    $document.on('click', '.' + plugin.namespace + '-ajax-response', plugin.hideAJAXResponse);

  };

  plugin.wipeCache = function (event) {
    plugin.preventDefault(event);
    plugin.statsData = null;

    var postVars = {
      _wpnonce: plugin.vars._wpnonce
    }; // HTTP post vars.
    postVars[plugin.namespace] = {
      ajaxWipeCache: '1'
    };
    var $wipe = $('#wp-admin-bar-' + plugin.namespace + '-wipe > a');
    var $clearOptionsLabel = $('#wp-admin-bar-' + plugin.namespace + '-clear-options-wrapper .-label');
    var $clearOptions = $('#wp-admin-bar-' + plugin.namespace + '-clear-options-wrapper .-options');

    plugin.removeAJAXResponse();
    $wipe.parent().addClass('-processing');
    $wipe.add($clearOptions.find('a')).attr('disabled', 'disabled');

    $.post(plugin.vars.ajaxURL, postVars, function (data) {
      plugin.removeAJAXResponse();
      $wipe.parent().removeClass('-processing');
      $wipe.add($clearOptions.find('a')).removeAttr('disabled');

      var $response = $('<div class="' + plugin.namespace + '-ajax-response -wipe">' + data + '</div>');
      $('body').append($response); // Append response.
      plugin.showAJAXResponse(); // Show response.
    });
  };

  plugin.clearCache = function (event, options) {
    plugin.preventDefault(event);

    var o = $.extend({}, {
      urlOnly: '',
      opCacheOnly: false,
      transientsOnly: false
    }, options || {});

    var postVars = {
      _wpnonce: plugin.vars._wpnonce
    }; // HTTP post vars.

    var isClearOption = false,
      $clear, // See below.
      $clearOptionsLabel = $(),
      $clearOptions = $();

    if (o.urlOnly) {
      isClearOption = true;
      postVars[plugin.namespace] = {
        ajaxClearCacheUrl: o.urlOnly
      };
    } else if (o.opCacheOnly) {
      isClearOption = true;
      postVars[plugin.namespace] = {
        ajaxClearOpCache: '1'
      };
    }  else if (o.transientsOnly) {
      isClearOption = true;
      postVars[plugin.namespace] = {
        ajaxClearExpiredTransients: '1'
      };
    } else {
      postVars[plugin.namespace] = {
        ajaxClearCache: '1'
      };
    }

    $clear = $('#wp-admin-bar-' + plugin.namespace + '-clear > a');
    $clearOptionsLabel = $('#wp-admin-bar-' + plugin.namespace + '-clear-options-wrapper .-label');
    $clearOptions = $('#wp-admin-bar-' + plugin.namespace + '-clear-options-wrapper  .-options');

    plugin.removeAJAXResponse();

    if (isClearOption && $clearOptionsLabel.length) {
      $clearOptionsLabel.addClass('-processing');
    } else {
      $clear.parent().addClass('-processing');
    }
    $clear.add($clearOptions.find('a')).attr('disabled', 'disabled');

    $.post(plugin.vars.ajaxURL, postVars, function (data) {
      plugin.removeAJAXResponse();

      if (isClearOption && $clearOptionsLabel.length) {
        $clearOptionsLabel.removeClass('-processing');
      } else {
        $clear.parent().removeClass('-processing');
      }
      $clear.add($clearOptions.find('a')).removeAttr('disabled');

      var $response = $('<div class="' + plugin.namespace + '-ajax-response -clear">' + data + '</div>');
      $('body').append($response); // Append response.
      plugin.showAJAXResponse(); // Show response.
    });
  };

  plugin.clearCacheHomeUrlOnly = function (event) {
    plugin.clearCache(event, {
      urlOnly: 'home'
    });
  };

  plugin.clearCacheCurrentUrlOnly = function (event) {
    plugin.clearCache(event, {
      urlOnly: document.URL
    });
  };

  plugin.clearCacheSpecificUrlOnly = function (event) {
    var url = $.trim(prompt(plugin.vars.i18n.enterSpecificUrl, 'http://'));

    if (url && url !== 'http://') {
      plugin.clearCache(event, {
        urlOnly: url
      });
    } else {
      plugin.preventDefault(event);
    }
  };

  plugin.clearCacheOpCacheOnly = function (event) {
    plugin.clearCache(event, {
      opCacheOnly: true
    });
  };

  plugin.clearExpiredTransientsOnly = function (event) {
    plugin.clearCache(event, {
      transientsOnly: true
    });
  };



  plugin.showAJAXResponse = function () {
    clearTimeout(plugin.hideAJAXResponseTimeout);

    $('.' + plugin.namespace + '-ajax-response')
      .off(plugin.animationEndEvents) // Reattaching below.
      .on(plugin.animationEndEvents, function () { // Reattach.
        plugin.hideAJAXResponseTimeout = setTimeout(plugin.hideAJAXResponse, 2500);
      })
      .addClass(plugin.namespace + '-admin-bar-animation-zoom-in-down').show()
      .on('mouseover', function () { // Do not auto-hide if hovered.
        clearTimeout(plugin.hideAJAXResponseTimeout);
        $(this).addClass('-hovered');
      });
  };

  plugin.hideAJAXResponse = function (event) {
    plugin.preventDefault(event);

    clearTimeout(plugin.hideAJAXResponseTimeout);

    $('.' + plugin.namespace + '-ajax-response')
      .off(plugin.animationEndEvents) // Reattaching below.
      .on(plugin.animationEndEvents, function () { // Reattach.
        plugin.removeAJAXResponse(); // Remove completely.
      })
      .addClass(plugin.namespace + '-admin-bar-animation-zoom-out-up');
  };

  plugin.removeAJAXResponse = function () {
    clearTimeout(plugin.hideAJAXResponseTimeout);

    $('.' + plugin.namespace + '-ajax-response')
      .off(plugin.animationEndEvents).remove();
  };



  plugin.bytesToSizeLabel = function (bytes, decimals) {
    if (typeof bytes !== 'number' || bytes <= 1) {
      return bytes === 1 ? '1 byte' : '0 bytes';
    } // See: <http://jas.xyz/1gOCXob>
    if (typeof decimals !== 'number' || decimals <= 0) {
      decimals = 0; // Default; integer.
    }
    var base = 1024, // 1 Kilobyte base (binary).
      baseLog = Math.floor(Math.log(bytes) / Math.log(base)),
      sizes = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
      sizeInBaseLog = (bytes / Math.pow(base, baseLog));

    return sizeInBaseLog.toFixed(decimals) + ' ' + sizes[baseLog];
  };

  plugin.numberFormat = function (number, decimals) {
    if (typeof number !== 'number') {
      return String(number);
    } // See: <http://jas.xyz/1JlFD9P>
    if (typeof decimals !== 'number' || decimals <= 0) {
      decimals = 0; // Default; integer.
    }
    return number.toFixed(decimals).replace(/./g, function (m, o, s) {
      return o && m !== '.' && ((s.length - o) % 3 === 0) ? ',' + m : m;
    });
  };

  plugin.escHtml = function (string) {
    var entityMap = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    };
    return String(string).replace(/[&<>"']/g, function (specialChar) {
      return entityMap[specialChar];
    });
  };

  plugin.preventDefault = function (event, stop) {
    if (!event) {
      return; // Not possible.
    }
    event.preventDefault(); // Always.

    if (stop) {
      event.stopImmediatePropagation();
    }
  };

  plugin.MutationObserver = (function () {
    var observer = null; // Initialize default value.
    $.each(['', 'WebKit', 'O', 'Moz', 'Ms'], function (index, prefix) {
      if (prefix + 'MutationObserver' in window) {
        observer = window[prefix + 'MutationObserver'];
        return false; // Stop iterating now.
      } // See: <http://jas.xyz/1JlzCdi>
    });
    return observer; // See: <http://caniuse.com/#feat=mutationobserver>
  }());

  plugin.animationEndEvents = // All vendor prefixes.
    'webkitAnimationEnd mozAnimationEnd msAnimationEnd oAnimationEnd animationEnd';

  $document.ready(plugin.onReady); // On DOM ready.

})(jQuery);
