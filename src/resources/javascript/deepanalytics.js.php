/**
 * DeepAnalytics.js
 * Version 1.0.0.0
 * Copyright Intellivoid Technologies 2017-<?php print(date('Y')); ?>
 *
 * This DynamicalWeb Plugin interacts with Intellivoid's DeepAnalytics Library
 * which is housed internally on the server. This plugin enables both a front-end
 * interface and back-end interface, the front-end component interacts with the
 * backend component, the backend component is executes instructions to the
 * DeepAnalytics library to retrieve data and send format it to a JSON document.
 *
 * The front-end component takes this data and renders a user-friendly interface
 * which allows the user to view monthly and hourly analytical data.
 *
 * This plugin will be generated upon use by DynamicalWeb. This also supports
 * DynamicalCompression. No further setup or configuration is required, just
 * add the following files to DynamicalWeb's base.
 *
 * src/resources/plugins/frontend/deepanalytics.js
 * src/resources/plugins/backend/deepanalytics.go
 * src/resources/plugins/deepanalytics.conf
 */
const deepanalytics = {
    version: "1.0.0.0",
    display_id: null,
    instance_id: null,
    api_endpoint: null,
    chart_colors: null,
    gride_line_color: null,
    selected_date: null,
    selected_day: null,
    selected_data: null,
    hourly_range: {},
    data_labels: {},
    loaded_data_range: null,
    loaded_monthly_data: null,
    loaded_hourly_data: null,
    locale: {},

    /**
     * Initialize DeepAnalytics.js
     *
     * @param display_id
     * @param api_endpoint
     * @param chart_colors
     */
    init: function (display_id, api_endpoint, chart_colors, grid_line_color) {
        this.display_id = display_id;
        this.api_endpoint = api_endpoint;
        this.chart_colors = chart_colors;
        this.gride_line_color = grid_line_color;
        this.instance_id = this.make_instance_id();

        this.ui.render_preloader();
        this.api.load_locale(function () {
            deepanalytics.api.get_range(function () {
                if (deepanalytics.utilities.check_if_empty(deepanalytics.loaded_data_range)) {
                    // noinspection JSUnresolvedFunction
                    $(`#${deepanalytics.display_id}`).empty();
                    $('<div/>', {
                        'id': `${deepanalytics.instance_id}_deepanalytics_errors`,
                        'class': 'd-flex flex-column justify-content-center align-items-center',
                        'style': 'height:50vh;',
                        'html': $('<div/>', {
                            'class': 'p-2 my-flex-item fa-3x',
                            'html': $('<h4/>', {
                                'html': deepanalytics.locale.DEEPANALYTICS_NO_DATA_ERROR
                            })
                        })
                    }).appendTo(`#${deepanalytics.display_id}`);
                } else {
                    deepanalytics.ui.render();
                }
            });
        })

    },

    /**
     * Generates a random string for the instance ID
     * @returns {string}
     */
    make_instance_id: function () {
        let result = '';
        const characters = 'abcdefghijklmnopqrstuvwxyz';
        const charactersLength = characters.length;
        for (let i = 0; i < 8; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    },

    /**
     * Main UI
     */
    ui: {
        /**
         * Renders the preloader animation
         */
        render_preloader: function () {
            $(`#${deepanalytics.display_id}`).empty();
            // noinspection JSUnresolvedFunction
            $('<div/>', {
                'id': `${deepanalytics.instance_id}_deepanalytics_init`,
                'class': 'd-flex flex-column justify-content-center align-items-center',
                'style': 'height:50vh;',
                'html': $('<div/>', {
                    'class': 'p-2 my-flex-item fa-3x',
                    'html': $('<i/>', {
                        'class': 'fa fa-circle-o-notch fa-spin'
                    })
                })
            }).appendTo(`#${deepanalytics.display_id}`);
        },

        /**
         * Displays an error message followed by the appropriate error code
         * @param error_code
         */
        error: function (error_code) {
            $(`#${deepanalytics.display_id}`).empty();
            $('<div/>', {
                'id': `${deepanalytics.instance_id}_deepanalytics_errors`,
                'class': 'd-flex flex-column justify-content-center align-items-center',
                'style': 'height:50vh;',
                'html': $('<div/>', {
                    'class': 'p-2 my-flex-item fa-3x',
                    'html': $('<h4/>', {
                        'html': deepanalytics.locale.DEEPANALYTICS_GENERIC_ERROR.replace("%s", error_code)
                    })
                })
            }).appendTo(`#${deepanalytics.display_id}`);
        },

        /**
         * Renders the main UI components such as the data and date selector and tabs
         */
        render: function () {
            $(`#${deepanalytics.display_id}`).empty();
            $('<div/>', {
                'class': 'row mt-4',
                'html': [
                    $('<label/>', {
                        'for': `${deepanalytics.instance_id}_deepanalytics_data_selector`,
                        'class': 'col-2 col-form-label',
                        'html': 'Data'
                    }),
                    $('<div/>', {
                        'class': 'col-10',
                        'html': $('<select/>', {
                            'name': `${deepanalytics.instance_id}_deepanalytics_data_selector`,
                            'id': `${deepanalytics.instance_id}_deepanalytics_data_selector`,
                            'class': 'form-control',
                            'change': function () {
                                deepanalytics.selected_data = $(this).children(":selected").attr("id");
                                deepanalytics.ui.reload();
                            }
                        })
                    })
                ]
            }).appendTo(`#${deepanalytics.display_id}`);

            $('<div/>', {
                'class': 'row mt-3',
                'html': [
                    $('<label/>', {
                        'for': `${deepanalytics.instance_id}_deepanalytics_date_selector`,
                        'class': 'col-2 col-form-label',
                        'html': deepanalytics.locale.DEEPANALYTICS_DATE_SELECTOR
                    }),
                    $('<div/>', {
                        'class': 'col-10',
                        'html': $('<select/>', {
                            'name': `${deepanalytics.instance_id}_deepanalytics_date_selector`,
                            'id': `${deepanalytics.instance_id}_deepanalytics_date_selector`,
                            'class': 'form-control',
                            'change': function () {
                                deepanalytics.selected_date = $(this).children(":selected").attr("id");
                                deepanalytics.ui.reload();
                            }
                        })
                    })
                ]
            }).appendTo(`#${deepanalytics.display_id}`);

            $('<option/>', {
                'html': deepanalytics.locale.DEEPANALYTICS_DATA_ALL,
                'id': "all"
            }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_data_selector`);
            deepanalytics.selected_data = 'all';

            const all_dates = [];
            for (let range_property in deepanalytics.loaded_data_range) {
                $('<option/>', {
                    'html': deepanalytics.loaded_data_range[range_property].text,
                    'id': range_property
                }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_data_selector`)
                deepanalytics.data_labels[range_property] = deepanalytics.loaded_data_range[range_property].text;

                const selected_date = Object.keys(deepanalytics.loaded_data_range[range_property]['monthly'])[0];
                if (typeof selected_date != "undefined") {
                    if (typeof deepanalytics.selected_date == "undefined") {
                        deepanalytics.selected_date = selected_date;
                    } else {
                        const current_selected = new Date(deepanalytics.selected_date);
                        if (new Date(selected_date) > current_selected) {
                            deepanalytics.selected_date = selected_date;
                        }
                    }
                }

                const selected_day = deepanalytics.utilities.ab_get_last_item(deepanalytics.loaded_data_range[range_property]['hourly']);
                if (typeof selected_day != "undefined") {
                    deepanalytics.selected_day = selected_day;
                }

                for (let month in deepanalytics.loaded_data_range[range_property]['monthly']) {

                    if (deepanalytics.utilities.push_unique(all_dates, month)) {
                        $('<option/>', {
                            'html': month,
                            'id': month
                        }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_date_selector`)
                    }
                }
            }

            if (deepanalytics.ui.tab_view.render()) {
                deepanalytics.utilities.load_hourly_range(deepanalytics.loaded_data_range);
                deepanalytics.chart_handler.monthly_chart.init();
                deepanalytics.chart_handler.hourly_chart.init();
            }

        },

        /**
         * Handler for the Tab Viewer
         */
        tab_view: {
            /**
             * Initialize the renderer for th tab view
             * @returns {boolean}
             */
            render: function () {
                this.render_tabs();
                this.render_tab_pages();
                return true;
            },

            /**
             * Render the tab headers
             */
            render_tabs: function () {
                $('<ul/>', {
                    'class': 'nav nav-tabs tab-solid tab-solid-primary mt-3',
                    'role': 'tablist',
                    'html': [
                        $('<li/>', {
                            'class': 'nav-item',
                            'html': $('<a/>', {
                                'class': 'nav-link active',
                                'data-toggle': 'tab',
                                'id:': `${deepanalytics.instance_id}_deepanalytics_monthly_tab`,
                                'href': `#${deepanalytics.instance_id}_deepanalytics_monthly_tab`,
                                'role': 'tab',
                                'aria-selected': 'true',
                                'html': [
                                    $('<span/>', {
                                        'class': 'd-none d-md-block',
                                        'html': deepanalytics.locale.DEEPANALYTICS_MONTHLY_USAGE
                                    }),
                                    $('<span/>', {
                                        'class': 'd-block d-md-none',
                                        'html': $('<i/>', {
                                            'class': 'mdi mdi-calendar-blank h5'
                                        })
                                    })
                                ]
                            })
                        }),
                        $('<li/>', {
                            'class': 'nav-item',
                            'html': $('<a/>', {
                                'class': 'nav-link',
                                'data-toggle': 'tab',
                                'id:': `${deepanalytics.instance_id}_deepanalytics_hourly_tab`,
                                'href': `#${deepanalytics.instance_id}_deepanalytics_hourly_tab`,
                                'role': 'tab',
                                'aria-selected': 'false',
                                'html': [
                                    $('<span/>', {
                                        'class': 'd-none d-md-block',
                                        'html': deepanalytics.locale.DEEPANALYTICS_DAILY_USAGE
                                    }),
                                    $('<span/>', {
                                        'class': 'd-block d-md-none',
                                        'html': $('<i/>', {
                                            'class': 'mdi mdi-calendar-clock h5'
                                        })
                                    })
                                ]
                            })
                        })
                    ]
                }).appendTo(`#${deepanalytics.display_id}`);
            },

            /**
             * Render the tab pages
             */
            render_tab_pages: function () {
                $('<div/>', {
                    'class': 'tab-content',
                    'html': [
                        $('<div/>', {
                            'class': 'tab-pane p-3 active',
                            'id': `${deepanalytics.instance_id}_deepanalytics_monthly_tab`,
                            'role': 'tabpanel',
                            'html': $('<div/>', {
                                'id': `${deepanalytics.instance_id}_deepanalytics_monthly_line_chart`,
                                'class': 'morris-chart',
                                'style': 'height: 300px;'
                            })
                        }),
                        $('<div/>', {
                            'class': 'tab-pane p-3',
                            'id': `${deepanalytics.instance_id}_deepanalytics_hourly_tab`,
                            'role': 'tabpanel',
                            'html': [
                                $('<div/>', {
                                    'id': `${deepanalytics.instance_id}_deepanalytics_hourly_line_chart`,
                                    'class': 'morris-chart',
                                    'style': 'height: 270px;'
                                }),
                                $('<div/>', {
                                    'id': `${deepanalytics.instance_id}_deepanalytics_hourly_selector`,
                                    'style': 'height: 30px;'
                                })
                            ]
                        })
                    ]
                }).appendTo(`#${deepanalytics.display_id}`);

                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    $(window).trigger('resize');
                });
            }
        },

        /**
         * Reloads the tab page's contents to show the data for the currently selected data and date
         */
        reload: function () {
            deepanalytics.utilities.load_hourly_range(deepanalytics.loaded_data_range);
            deepanalytics.chart_handler.monthly_chart.init();
            deepanalytics.chart_handler.hourly_chart.init();
        }
    },

    /**
     * API Handler, sends and processes requests for DeepAnalytics
     */
    api: {

        /**
         * Requests the locale data for displaying the proper language on the UI
         * @param callback
         */
        load_locale: function (callback) {
            $.ajax({
                url: `${deepanalytics.api_endpoint}?action=deepanalytics.locale`,
                type: "GET",
                success: function (data) {
                    deepanalytics.locale = data.payload;
                    callback();
                },
                error: function () {
                    deepanalytics.error(-10)
                }
            });
        },

        /**
         * Requests for the date range that's currently available and executes the callback function
         * if successful
         * @param callback
         */
        get_range: function (callback) {
            $.ajax({
                url: `${deepanalytics.api_endpoint}?action=deepanalytics.get_range`,
                type: "GET",
                success: function (data) {
                    deepanalytics.loaded_data_range = data;
                    callback();
                },
                error: function () {
                    deepanalytics.error(-11)
                }
            });
        },

        /**
         * Requests and returns the monthly data that's currently selected and
         * executes the callback function if successful
         * @param callback
         */
        get_monthly_data: function (callback) {
            $.ajax({
                url: `${deepanalytics.api_endpoint}?action=deepanalytics.get_monthly_data`,
                type: "POST",
                data: {
                    "year": deepanalytics.selected_date.split('-')[0],
                    "month": deepanalytics.selected_date.split('-')[1]
                },
                success: function (data) {
                    if (data['status'] === false) {
                        deepanalytics.error(-12);
                    } else {
                        deepanalytics.loaded_monthly_data = data;
                        callback();
                    }
                },
                error: function () {
                    deepanalytics.error(-13);
                }
            });
        },

        /**
         * Requests and returns the hourly data that's currently selected
         * and executes the callback function if successful
         * @param callback
         */
        get_hourly_data: function (callback) {
            $.ajax({
                url: `${deepanalytics.api_endpoint}?action=deepanalytics.get_hourly_data`,
                type: "POST",
                data: {
                    "year": deepanalytics.selected_day.split('-')[0],
                    "month": deepanalytics.selected_day.split('-')[1],
                    "day": deepanalytics.selected_day.split('-')[2]
                },
                success: function (data) {
                    if (data['status'] === false) {
                        deepanalytics.error(-14);
                    } else {
                        deepanalytics.loaded_hourly_data = data;
                        callback();
                    }
                },
                error: function () {
                    deepanalytics.error(-15);
                }
            });
        }
    },

    /**
     * Miscellaneous functions used to make things easier
     */
    utilities: {

        /**
         * Extracts the key labels, optionally excludes speicified labels
         * @param exclude
         * @returns {{keys: [], labels: []}}
         */
        get_key_labels: function (exclude) {
            const data_keys = [];
            const data_labels = [];

            for (let label in deepanalytics.data_labels) {
                if (exclude.indexOf(label) < 0) {
                    deepanalytics.utilities.push_unique(data_keys, label);
                    deepanalytics.utilities.push_unique(data_labels, deepanalytics.data_labels[label]);
                }
            }

            return {
                keys: data_keys,
                labels: data_labels
            }
        },

        /**
         * Returns a single label
         *
         * @param label
         * @returns {{keys: [], labels: []}}
         */
        get_single_label: function (label) {
            const data_keys = [];
            const data_labels = [];

            deepanalytics.utilities.push_unique(data_keys, label);
            deepanalytics.utilities.push_unique(data_labels, deepanalytics.data_labels[label]);

            return {
                keys: data_keys,
                labels: data_labels
            }
        },

        /**
         * Checks if the data range is empty by checking if it's an object rather than an empty array
         *
         * @param data
         * @returns {boolean}
         */
        check_if_empty: function (data) {
            let is_empty = true;

            for (let data_range in data) {
                if (Object.prototype.toString.call(data[data_range]["hourly"]) === "[object Object]") {
                    is_empty = false;
                }
            }

            return is_empty;
        },

        /**
         * !Important
         *
         * Loads the hourly range into memory so that the hourly selector
         * can render properly
         *
         * @param data
         */
        load_hourly_range: function (data) {
            let hourly_range = {};
            deepanalytics.hourly_range = {};
            for (let data_range in data) {
                hourly_range = data[data_range]["hourly"];
                for (let stamp in hourly_range) {
                    const formatted_stamp = stamp.split('-')[2];
                    if (deepanalytics.selected_date === `${stamp.split('-')[0]}-${stamp.split('-')[1]}`) {
                        deepanalytics.hourly_range[formatted_stamp] = {
                            id: hourly_range[stamp]["id"],
                            date: hourly_range[stamp]["date"]
                        }
                    }

                }
            }
        },

        /**
         * Returns the last item from an object
         *
         * @param obj
         * @returns {string}
         */
        ab_get_last_item: function (obj) {
            return Object.keys(obj)[Object.keys(obj).length - 1];
        },

        /**
         * Pushes an item into the object only if the object does not already
         * contain the item
         *
         * @param obj
         * @param item
         * @returns {boolean}
         */
        push_unique: function (obj, item) {
            if (obj.indexOf(item) === -1) {
                obj.push(item);
                return true;
            }
            return false;
        }
    },

    /**
     * Main chart handler for monthly and hourly data displays
     */
    chart_handler: {

        /**
         * Hourly chart handler, includes the navigation view
         */
        hourly_chart: {

            /**
             * The MorrisJS linechart object
             */
            line_chart: null,

            /**
             * Initializes the renderer for the hourly chart and hourly navigation
             */
            init: function () {
                this.navigation.render();
            },

            /**
             * The main UI in the tab view for the hourly line chart
             */
            ui: {
                render_preloader: function () {
                    $(`#${deepanalytics.instance_id}_deepanalytics_hourly_line_chart`).empty();
                    $('<div/>', {
                        'class': 'd-flex flex-column justify-content-center align-items-center',
                        'style': 'height:40vh;',
                        'html': $('<div/>', {
                            'class': 'p-2 my-flex-item fa-3x',
                            'html': $('<i/>', {
                                'class': 'fa fa-circle-o-notch fa-spin'
                            })
                        })
                    }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_hourly_line_chart`);

                    deepanalytics.chart_handler.hourly_chart.navigation.disable();
                }

            },

            chart: {
                createLineChart: function (element, data, xkey, ykeys, labels, lineColors) {
                    deepanalytics.chart_handler.hourly_chart.line_chart = Morris.Line({
                        element: element,
                        data: data,
                        xkey: xkey,
                        ykeys: ykeys,
                        labels: labels,
                        hideHover: 'auto',
                        gridLineColor: deepanalytics.gride_line_color,
                        resize: true, //defaulted to true
                        lineColors: lineColors,
                        lineWidth: 2
                    });
                },

                no_data_render: function () {
                    $(`#${deepanalytics.instance_id}_deepanalytics_hourly_line_chart`).empty();
                    $('<div/>', {
                        'class': 'd-flex flex-column justify-content-center align-items-center',
                        'style': 'height:40vh;',
                        'html': $('<div/>', {
                            'class': 'p-2 my-flex-item fa-3x',
                            'html': $('<h4/>', {
                                'html': deepanalytics.locale.DEEPANALYTICS_NO_DATA_ERROR
                            })
                        })
                    }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_hourly_line_chart`);
                },

                render: function () {
                    let stamp;
                    let data_entry_object;
                    $(`#${deepanalytics.instance_id}_deepanalytics_hourly_line_chart`).empty();

                    const exclude = [];
                    let labels = deepanalytics.utilities.get_key_labels(exclude);
                    const $data = [];
                    const working_data = {};

                    if (deepanalytics.selected_data === "all") {
                        for (let data_entry in deepanalytics.loaded_hourly_data['results']) {
                            data_entry_object = deepanalytics.loaded_hourly_data['results'][data_entry];

                            if (data_entry_object == null) {
                                deepanalytics.utilities.push_unique(exclude, data_entry);
                                labels = deepanalytics.utilities.get_key_labels(exclude);
                            } else {
                                for (stamp in data_entry_object['data']) {
                                    if (typeof working_data[stamp] == "undefined") {
                                        working_data[stamp] = {}
                                    }
                                    working_data[stamp][data_entry] =
                                        data_entry_object['data'][stamp]
                                }
                            }
                        }
                    } else {
                        data_entry_object = deepanalytics.loaded_hourly_data['results'][deepanalytics.selected_data];

                        if (data_entry_object == null) {
                            this.no_data_render();
                            return;
                        } else {
                            labels = deepanalytics.utilities.get_single_label(deepanalytics.selected_data);
                            for (stamp in data_entry_object['data']) {
                                if (typeof working_data[stamp] == "undefined") {
                                    working_data[stamp] = {}
                                }
                                working_data[stamp][deepanalytics.selected_data] =
                                    data_entry_object['data'][stamp]
                            }
                        }
                    }

                    for (let entry in working_data) {
                        $data.push(
                            Object.assign(
                                {y: entry},
                                working_data[entry]
                            )
                        )
                    }

                    if ($data.length === 0) {
                        this.no_data_render();
                        return;
                    }

                    deepanalytics.chart_handler.hourly_chart.chart.createLineChart(
                        `${deepanalytics.instance_id}_deepanalytics_hourly_line_chart`, $data, 'y',
                        labels.keys, labels.labels, deepanalytics.chart_colors
                    );
                }
            },

            navigation: {

                range: null,
                minimum: null,
                maximum: null,
                selected_index: null,

                update: function () {
                    deepanalytics.chart_handler.hourly_chart.ui.render_preloader();
                    deepanalytics.selected_day = `${deepanalytics.selected_date}-${this.range[this.selected_index]}`;
                    deepanalytics.api.get_hourly_data(function () {
                        deepanalytics.chart_handler.hourly_chart.chart.render();
                        deepanalytics.chart_handler.hourly_chart.navigation.update_ui();
                    });
                },

                disable: function () {

                    $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_previous`).unbind("click");
                    $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_next`).unbind("click");

                    for (let current_range in this.range) {
                        if (typeof (this.range[current_range]) == "number") {
                            $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_${this.range[current_range]}`).unbind("click");
                        }
                    }
                },

                update_ui: function () {
                    const selected = this.range[this.selected_index];

                    // Unbind existing events
                    $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_previous`).unbind("click");
                    $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_next`).unbind("click");

                    // Update previous button event
                    if (selected === this.minimum) {
                        $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_previous`).addClass("disabled");
                    } else {
                        $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_previous`).removeClass("disabled");
                        $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_previous`).click(function () {
                            deepanalytics.chart_handler.hourly_chart.navigation.selected_index -= 1;
                            deepanalytics.chart_handler.hourly_chart.navigation.update();
                        });
                    }

                    // Update next button event
                    if (selected === this.maximum) {
                        $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_next`).addClass("disabled");
                    } else {
                        $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_next`).removeClass("disabled");
                        $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_next`).click(function () {
                            deepanalytics.chart_handler.hourly_chart.navigation.selected_index += 1;
                            deepanalytics.chart_handler.hourly_chart.navigation.update();
                        });
                    }

                    for (let current_range in this.range) {
                        if (typeof (this.range[current_range]) == "number") {

                            $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_${this.range[current_range]}`).removeClass("active");
                            $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_${this.range[current_range]}`).unbind("click");

                            if (this.range[current_range] === selected) {
                                $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_${this.range[current_range]}`).addClass("active");
                            }

                            $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_${this.range[current_range]}`).removeClass("disabled");
                            $(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg_${this.range[current_range]}`).click(function () {
                                const value = parseInt($(this).attr("id").match(/\d+/g)[0]);
                                deepanalytics.chart_handler.hourly_chart.navigation.selected_index = deepanalytics.chart_handler.hourly_chart.navigation.range.indexOf(value);
                                deepanalytics.chart_handler.hourly_chart.navigation.update();
                            });
                        }
                    }
                },

                render: function () {
                    deepanalytics.chart_handler.hourly_chart.navigation.minimum = null;
                    deepanalytics.chart_handler.hourly_chart.navigation.maximum = null;
                    deepanalytics.chart_handler.hourly_chart.navigation.selected = null;
                    deepanalytics.chart_handler.hourly_chart.navigation.range = [];

                    $("<nav/>", {
                        "html": $("<ul/>", {
                            "class": "pagination pagination-sm justify-content-center",
                            "id": `${deepanalytics.instance_id}_deepanalytics_hourly_pg`,
                            "html": $("<li/>", {
                                "class": "page-item disabled",
                                "id": `${deepanalytics.instance_id}_deepanalytics_hourly_pg_previous`,
                                "html": $("<a/>", {
                                    "class": "page-link",
                                    "href": "#/",
                                    "html": $("<i/>", {
                                        "class": "mdi mdi-chevron-left"
                                    })
                                })
                            })
                        })
                    }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_hourly_selector`);

                    for (let day in deepanalytics.hourly_range) {

                        deepanalytics.chart_handler.hourly_chart.navigation.maximum = parseInt(day);
                        deepanalytics.utilities.push_unique(deepanalytics.chart_handler.hourly_chart.navigation.range, parseInt(day));

                        if (deepanalytics.chart_handler.hourly_chart.navigation.minimum == null) {
                            deepanalytics.chart_handler.hourly_chart.navigation.minimum = parseInt(day);
                            deepanalytics.chart_handler.hourly_chart.navigation.selected = parseInt(day);
                            deepanalytics.chart_handler.hourly_chart.navigation.selected_index = deepanalytics.chart_handler.hourly_chart.navigation.range.indexOf(parseInt(day));
                        }


                        $("<li/>", {
                            "class": "page-item disabled",
                            "id": `${deepanalytics.instance_id}_deepanalytics_hourly_pg_${day}`,
                            "html": $("<a/>", {
                                "class": "page-link",
                                "href": "#/",
                                "html": day
                            })
                        }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg`);
                    }

                    $("<li/>", {
                        "class": "page-item disabled",
                        "id": `${deepanalytics.instance_id}_deepanalytics_hourly_pg_next`,
                        "html": $("<a/>", {
                            "class": "page-link",
                            "href": "#/",
                            "html": $("<i/>", {
                                "class": "mdi mdi-chevron-right"
                            })
                        })
                    }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_hourly_pg`);

                    $(`#${deepanalytics.instance_id}_deepanalytics_hourly_selector`).rPage();
                    deepanalytics.chart_handler.hourly_chart.navigation.update();
                }
            }
        },

        monthly_chart: {
            line_chart: null,

            init: function () {
                this.ui.render_preloader();
                deepanalytics.api.get_monthly_data(this.chart.render);
            },

            ui: {
                render_preloader: function () {
                    $(`#${deepanalytics.instance_id}_deepanalytics_monthly_line_chart`).empty();
                    $(`#${deepanalytics.instance_id}_deepanalytics_hourly_selector`).empty();
                    $('<div/>', {
                        'class': 'd-flex flex-column justify-content-center align-items-center',
                        'style': 'height:50vh;',
                        'html': $('<div/>', {
                            'class': 'p-2 my-flex-item fa-3x',
                            'html': $('<i/>', {
                                'class': 'fa fa-circle-o-notch fa-spin'
                            })
                        })
                    }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_monthly_line_chart`);
                }
            },

            chart: {
                createLineChart: function (element, data, xkey, ykeys, labels, lineColors) {
                    deepanalytics.chart_handler.monthly_chart.line_chart = Morris.Line({
                        element: element,
                        data: data,
                        xkey: xkey,
                        ykeys: ykeys,
                        labels: labels,
                        hideHover: 'auto',
                        gridLineColor: deepanalytics.gride_line_color,
                        resize: true,
                        lineColors: lineColors,
                        lineWidth: 2
                    });
                },

                no_data_render: function () {
                    $(`#${deepanalytics.instance_id}_deepanalytics_monthly_line_chart`).empty();
                    $(`#${deepanalytics.instance_id}_deepanalytics_hourly_selector`).empty();
                    $('<div/>', {
                        'class': 'd-flex flex-column justify-content-center align-items-center',
                        'style': 'height:40vh;',
                        'html': $('<div/>', {
                            'class': 'p-2 my-flex-item fa-3x',
                            'html': $('<h4/>', {
                                'html': deepanalytics.locale.DEEPANALYTICS_NO_DATA_ERROR
                            })
                        })
                    }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_monthly_line_chart`);
                },

                render: function () {
                    let stamp;
                    let data_entry_object;
                    $(`#${deepanalytics.instance_id}_deepanalytics_monthly_line_chart`).empty();

                    const exclude = [];
                    let labels = deepanalytics.utilities.get_key_labels(exclude);
                    const $data = [];
                    const working_data = {};

                    if (deepanalytics.selected_data === "all") {
                        for (let data_entry in deepanalytics.loaded_monthly_data['results']) {
                            data_entry_object = deepanalytics.loaded_monthly_data['results'][data_entry];

                            if (data_entry_object == null) {
                                deepanalytics.utilities.push_unique(exclude, data_entry);
                                labels = deepanalytics.utilities.get_key_labels(exclude);
                            } else {
                                for (stamp in data_entry_object['data']) {
                                    if (typeof working_data[stamp] == "undefined") {
                                        working_data[stamp] = {}
                                    }
                                    working_data[stamp][data_entry] =
                                        data_entry_object['data'][stamp]
                                }
                            }
                        }
                    } else {
                        data_entry_object = deepanalytics.loaded_monthly_data['results'][deepanalytics.selected_data];

                        if (data_entry_object == null) {
                            this.deepanalytics.chart_handler.monthly_chart.chart.no_data_render();
                            return;
                        } else {
                            labels = deepanalytics.utilities.get_single_label(deepanalytics.selected_data);
                            for (stamp in data_entry_object['data']) {
                                if (typeof working_data[stamp] == "undefined") {
                                    working_data[stamp] = {}
                                }
                                working_data[stamp][deepanalytics.selected_data] =
                                    data_entry_object['data'][stamp]
                            }
                        }
                    }

                    for (let entry in working_data) {
                        $data.push(
                            Object.assign(
                                {y: entry},
                                working_data[entry]
                            )
                        )
                    }

                    if ($data.length === 0) {
                        this.deepanalytics.chart_handler.monthly_chart.chart.no_data_render();
                        return;
                    }

                    deepanalytics.chart_handler.monthly_chart.chart.createLineChart(
                        `${deepanalytics.instance_id}_deepanalytics_monthly_line_chart`, $data, 'y',
                        labels.keys, labels.labels, deepanalytics.chart_colors
                    );
                }
            }
        }
    }
};

$(document).ready(function () {
    deepanalytics.init(
        "deepanalytics_viewer", "<?php \DynamicalWeb\DynamicalWeb::getRoute($_GET["api"], [], true); ?>",
        ['#5468da', '#ffbb44', '#67a8e4', '#4ac18e', '#ea553d', '#3bc3e9', '#ea553d', '#e83e8c', '#007bff', '#20c997', '#ffc107', '#dc3545', '#6f42c1'],
        '#1a2036');
});