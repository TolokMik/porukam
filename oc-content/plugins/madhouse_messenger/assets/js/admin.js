// @codekit-prepend "../../vendor/bower_components/bootstrap/dist/js/bootstrap.js";

// Updater.
(function ($) {
    $.pluginUpdater = function(element, options) {
        var plugin = this;

        var
            $element     = $(element),
            element      = element,
            defaults     = {
                chunk: 400,
                page:  1
            },
            $trigger     = $element.find(".js-run-updater"),
            $progressBar = $element.find(".progress-bar"),
            $progressETA = $element.find(".js-eta"),
            running      = false,
            runningStart = null,
            runningStop  = null,
            ajaxStart    = null,
            ajaxStop     = null,
            processed    = 0;

        plugin.settings = {};

        plugin.init = function ()
        {
            plugin.settings = $.extend({}, defaults, $element.data(), options);

            $element.find(".js-show-success").hide();
            $element.find(".js-show-idle").hide();
            $element.find(".js-show-error").hide();

            if (plugin.settings.end === 0) {
                // Nothing to do.
                plugin.disable();
                plugin.showIdle();
                return false;
            }

            $trigger.on("click", function(e) {
                // Prevent click.
                e.preventDefault();

                // Run the updater.
                plugin.run();
            });
        };

        plugin.run = function ()
        {
            if (plugin.isRunning()) {
                // Already running.
                return false;
            }

            currentPage = plugin.settings.page;
            plugin.process();
        }

        plugin.process = function () {
            $.ajax({
                url: plugin.settings.url,
                method: "GET",
                data: {
                    "do": "admin_upgrade_post",
                    "p":  currentPage,
                    "n":  plugin.settings.chunk,
                    "plugin_version": plugin.settings.version
                },
                contentType: "text/json",
                beforeSend: function (jqXHR, settings) {
                    plugin.disable();

                    if (plugin.settings.end === 0) {
                        return false;
                    }

                    if (!plugin.isRunning()) {
                        running = true;
                        runningStart = new Date();
                    }

                    ajaxStart = new Date();
                },
                success: function (response, status, jqXHR) {
                    console.log(response, status);

                    if (response.status === "success") {
                        ajaxStop = new Date();
                        processed = response.data.processed;
                        plugin.showETA();

                        if (response.data.done === true) {
                            // Done.
                            runningStop = new Date();
                            plugin.showSuccess();
                            return;
                        } else {
                            ++currentPage;
                            setTimeout(function () {
                                plugin.process();
                            }, 150);
                        }
                    } else {
                        if (plugin.settings.page == currentPage) {
                            plugin.showIdle();
                        } else {
                            plugin.showError();
                        }
                    }
                },
                error: function (request, status, error) {
                    console.log(request, status, error);
                    plugin.showError();
                },
                complete: function (jqXHR, status) {
                    console.log(jqXHR, processed, plugin.getCountOfItems());
                }
            });
        }

        plugin.isRunning = function ()
        {
            return running;
        }

        plugin.showETA = function ()
        {
            var eta = Math.ceil(((processed / plugin.getCountOfItems()) * 100)) + "%";

            // Set progress bar width.
            $progressBar.css({
                width: eta
            });

            // Set eta.
            $progressETA.text(eta);
            $element.find(".js-show-eta").prepend(
                '<div>' +
                    '[' + ajaxStop.toISOString() + '; ' + (ajaxStop - ajaxStart) + 'ms] Processed ' + plugin.settings.chunk + ' threads... (page=' + currentPage + ') [' + processed + '/' + plugin.settings.end + ']' +
                '</div>'
            );
        }

        plugin.showSuccess = function ()
        {
            var $successAlert = $element.find(".js-show-success"),
                $countProcessed = $successAlert.find(".js-count-processed"),
                $totalTimer = $successAlert.find(".js-total-timer"),
                processingTime = new Date(runningStop - runningStart);

            // Show the number of processed threads.
            $countProcessed.text(processed);
            $totalTimer.text(processingTime.toISOString().substr(11, 8));

            // Show the alert.
            $successAlert.show();
        }

        plugin.showIdle = function ()
        {
            var $idleAlert = $element.find(".js-show-idle");

            $idleAlert.show();
        }

        plugin.showError = function ()
        {
            var $errorAlert = $element.find(".js-show-error");

            // Show the alert.
            $errorAlert.show();
        }

        plugin.disable = function ()
        {
            $trigger.prop("disabled", true);
        }

        plugin.getCountOfItems = function ()
        {
            return plugin.settings.end;
        }

        plugin.init();
    };

    $.fn.pluginUpdater = function(options)
    {
        return this.each(function() {
            if (undefined == $(this).data('pluginUpdater')) {
                var plugin = new $.pluginUpdater(this, options);
                $(this).data('pluginUpdater', plugin);
            }
        });
    };

    $(document).ready(function() {
        $('[data-toggle="plugin-updater"]').pluginUpdater({});
    });
})(jQuery);

$(document).ready(function() {

    $('input[name="user"]').attr( "autocomplete", "off" );
    $('#fUser').autocomplete({
        source: $('#fUser').data("ajaxurl"),
        minLength: 0,
        select: function( event, ui ) {
            if(ui.item.id=='')
                return false;
            $('#fUserId').val(ui.item.id);
        },
        search: function() {
            $('#fUserId').val('');
        }
    });

    $('.js-affix-sidenav').affix({
      offset: {
        top: function () {
          return (this.top = $('#content-head').outerHeight(true) + $('.nav').outerHeight(true))
        },
        bottom: function () {
          return (this.bottom = $('#footer').outerHeight(true))
        }
      }
    });

    $('body').scrollspy({ target: '#navbar-exemple' });
});