const { compareVersion, textToJSON, isValidJSON, toFormData } = require("./utils");

let myApp = angular.module('ajaxApp', [])

myApp.controller('AppCtrl', function ($scope, $http) {
    const LS_KEY_NAME = 'wp_ajax';
    const localData = getLocalData();

    function getLocalData(key) {
        let data = JSON.parse(localStorage.getItem(LS_KEY_NAME) || '{}')
        if (key) {
            return data[key]
        }
        return data;
    }

    function saveLocalData(key, value) {
        if (!key) return;

        let oldData = getLocalData();
        oldData[key] = value;
        localStorage.setItem(LS_KEY_NAME, JSON.stringify(oldData))
    }


    $scope.sending = false;
    $scope.savedList = []
    $scope.model = {}
    $scope.inputTypes = ['Text', 'JSON']
    $scope.inputTypes = []
    $scope.activeInputType = 'Text';
    $scope.contentTypes = [
        'application/json',
        'application/x-www-form-urlencoded; charset=UTF-8'
    ]

    function initModel() {
        $scope.model = {
            id: null,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            action: '',
            title: '',
            payload: ''
        }
    }

    $scope.globalSettings = {
        preRequestScript: getLocalData('globalSettings')?.preRequestScript ?? "WPAjax.body.add( 'foo', 'bar' )"
    }

    $scope.setActiveInputType = function (type) {
        $scope.activeInputType = type
    }

    $scope.saveGlobalSettings = function (settings) {
        saveLocalData('globalSettings', settings)
        tb_remove()
    }

    $scope.openGlobalSettings = function () {
        let url = '#TB_inline?width=600&height=250&inlineId=ajax-global-settings';
        tb_show('Global Settings', url, false);
    }

    initModel()

    let $ = jQuery
    let ajaxUrl = $('input.ajax_url').val()
    let editor = $('#input')
    let output = $('#output')

    if (localData.payload) {
        $scope.model.payload = localData.payload
    } else {
        $scope.model.payload = 'action:generate-password'
    }

    $('#input').keydown(function (e) {
        if ((e.ctrlKey || e.metaKey) && (e.keyCode == 13 || e.keyCode == 10)) {
            $('#ajax').click()
        }
    });

    let jsonViewerConfig = {
        collapsed: false,
        rootCollapsable: false,
        bigNumbers: false
    }

    let jsonData = {}
    const WPAjax = {
        body: {
            add: function (key, value) {
                jsonData[key] = value
            }
        }

    }

    $('#ajax').click(function () {
        let input = editor.val()
        if (isValidJSON(input)) {
            jsonData = JSON.parse(input);
        } else {
            jsonData = textToJSON(input)
        }

        if (input && jsonData) {
            try {
                if (!jsonData.action) {
                    output.jsonViewer('action key is required')
                    return;
                }

                saveLocalData('payload', input)

                let preRequestScript = getLocalData('globalSettings')?.preRequestScript ?? false
                if (preRequestScript) {
                    try {
                        eval(preRequestScript)
                    } catch (error) {
                        console.log(error)
                    }
                }

                // console.log(jsonData)

                $.ajax({
                    type: 'POST',
                    url: ajaxUrl,
                    contentType: $scope.model.contentType,
                    data: jsonData,
                    beforeSend: function () {
                        $scope.sending = true
                        $scope.$digest()
                        $('#status-code').hide()
                    },
                    success: function (res, textStatus, xhr) {
                        $('#status-code').html(xhr.status).removeAttr('style')
                        output.jsonViewer(res, jsonViewerConfig);
                    },
                    error: function (err) {
                        $('#status-code').html(err.status).css('background-color', 'red')

                        let status = err.status
                        let errorText = err.responseJSON || err.responseText

                        if ('0' === errorText && 400 === status) {
                            errorText = {
                                data: errorText,
                                message: 'No AJAX registered with the name `' + jsonData.action + '`'
                            }

                        }

                        output.jsonViewer(errorText, jsonViewerConfig);
                    },
                    complete: function () {
                        $scope.sending = false
                        $scope.$digest()
                        $('#status-code').show()
                    }
                });
            } catch (error) {
                console.log(error)
            }

        }
    })

    let config = {
        transformRequest: angular.identity,
        headers: {
            'Content-Type': undefined
        }
    }

    $scope.init = function () {
        let formData = new FormData();
        formData.append('action', 'ajax_list');

        $http.post(ajaxUrl, formData, config)
            .success(function (res) {
                $scope.savedList = res.data
            })
    }

    $scope.init();


    $scope.saving = false;
    $scope.save = function (model) {
        let jsonPayload = isValidJSON(model.payload) ? JSON.parse(model.payload) : textToJSON(model.payload)
        if (!jsonPayload.action) {
            alert('action key is required')
            return;
        }

        model.action = 'ajax_list_save'
        model.title = jsonPayload.action;

        $scope.saving = true;
        $http.post(ajaxUrl, toFormData(model), config)
            .success(function (res) {
                $scope.saving = false;
                if (res.success) {
                    $scope.init()
                }
            })
    }

    $scope.toggleSelect = function (row) {
        if ($scope.model.id && $scope.model.id == row.id) {
            initModel()
        } else {
            $scope.model = row;
            if (!$scope.model.contentType) {
                $scope.model.contentType = 'application/x-www-form-urlencoded; charset=UTF-8'
            }
        }

    }

    $scope.remove = function (row) {
        let data = { action: 'ajax_list_remove', id: row.id }

        $http.post(ajaxUrl, toFormData(data), config)
            .success(function (res) {
                if (res.success) {
                    $scope.init()
                }
            })
    }


    /**
     * Update plugin
     */
    $scope.pluginInfo = {
        currentVersion: _ajax.version,
        newVersion: null,
        updateAvailable: false,
        updateUrl: _ajax.updateUrl
    }

    $scope.checkUpdate = function () {
        $http.get($scope.pluginInfo.updateUrl)
            .success(function (res) {
                let newVersion = res.version

                $scope.pluginInfo.newVersion = newVersion
                $scope.pluginInfo.updateAvailable = compareVersion($scope.pluginInfo.currentVersion, '<', newVersion)
                // $scope.pluginInfo.updateAvailable = true;
            })
    }

    $scope.checkUpdate()

    $scope.updating = false;
    $scope.updatePlugin = function () {
        let data = {
            plugin: 'ajax/ajax.php',
            slug: 'ajax',
            action: 'update-plugin',
            _ajax_nonce: _ajax.pluginUpdateNonce
        }

        $scope.updating = true
        $http.post(ajaxUrl, toFormData(data), config)
            .success(function (res) {
                $scope.updating = false
                if (res.success) {
                    window.location.reload()
                } else {
                    alert(res.data.errorMessage)
                }
            })
    }
    // End plugin update.
})